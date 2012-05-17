<?php

/**
 * This is the model class for table "job_line".
 *
 * The followings are the available columns in table 'job_line':
 * @property integer $ID
 * @property integer $JOB_ID
 * @property integer $PRODUCT_ID
 * @property integer $PRINT_ID
 * @property integer $QUANTITY
 * @property string $PRICE
 * @property string $APPROVAL_DATE
 * @property integer $APPROVAL_USER
 *
 * The followings are the available model relations:
 * @property Product $pRODUCT
 * @property User $aPPROVALUSER
 * @property Job $jOB
 * @property Print $pRINT
 */
class JobLine extends CActiveRecord
{
	private $_color; //id of the color lookup item
	private $_size; //id of the size lookup item
	private $_oldQuantity = 0;
	private $_oldProductID;
	private $_changed;
	
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return JobLine the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	//TODO fix this
	/*protected function afterFind(){
		$this->_oldQuantity = $this->QUANTITY;
		$this->_oldProductID = $this->PRODUCT_ID;
		$product = $this->product;
		if($product){
			$this->_color = $product->COLOR;
			$this->_size = $product->SIZE;
		}
	}*/
	
	//TODO fix this
	/*protected function beforeSave(){
		/*Because lines may be modified by admins AFTER they are approved,
		 * we need to adjust the inventory quantity appropriately if the quantity changes.
		 * We also need to ensure that the product described does exist. If not, we will
		 * create a placeholder stock item./
		$oldProduct = null;
		if($this->_oldProductID !== null){
			if($this->_oldProductID != $this->PRODUCT_ID){
				$oldProduct = Product::model()->findByPk((int) $this->_oldProductID);
			} else {
				$oldProduct = $this->product;
			}
		}
		
		if(isset($oldProduct) && $oldProduct && $this->isApproved){
			$oldProduct->AVAILABLE += $this->_oldQuantity;
		}
		
		$newProduct = Product::model()->findByAttributes(array(
			'ID'=>$this->PRODUCT_ID,
		));
		
		if($newProduct !== null){
			if($this->isApproved){			
				$newProduct->AVAILABLE -= $this->QUANTITY;
			}
		}	
		
		if(parent::beforeSave() && ($newProduct == null || $newProduct->save()) && ($oldProduct == null || $oldProduct->save())){
			$this->PRODUCT_ID = $newProduct->ID;
			return true;
		} else {
			return false;
		}
	}*/
	
	protected function beforeDelete(){			
		$adjusted = true;
		foreach($this->sizes as $sizeLine){
			if($this->isApproved){			
				$sizeLine->productLine->AVAILABLE += $sizeLine->QUANTITY;
				$adjusted = $adjusted && $sizeLIne->productLine->save();
			}
			if($adjusted){
				$adjusted = $adjusted && $sizeLine->delete();
			}
		}
		return parent::beforeDelete() && $adjusted;
	}
	
	protected function beforeValidate(){
		if(parent::beforeValidate()){
			$valid = true;
			foreach($this->sizes as $line){
				$line->JOB_LINE_ID = $this->ID;
				$valid = $valid && $line->validate();
			}
			return $valid;
		} else {
			return false;
		}
	}

	protected function afterSave(){
		parent::afterSave();
		if(isset($this->sizes)){
			foreach($this->sizes as $line){
				$line->JOB_LINE_ID = $this->ID;
				$line->save();
			}
		}
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'job_line';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('JOB_ID, PRODUCT_ID, QUANTITY, APPROVAL_USER', 'numerical', 'integerOnly'=>true),
			array('APPROVAL_DATE, ID', 'safe'),
			array('PRICE', 'numerical'),
			array('PRODUCT_COLOR', 'numerical'),			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, JOB_ID, PRODUCT_ID, QUANTITY, PRICE, APPROVAL_DATE, APPROVAL_USER', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'product' => array(self::BELONGS_TO, 'Product', 'ID'),
			'aPPROVALUSER' => array(self::BELONGS_TO, 'User', 'APPROVAL_USER'),
			'job' => array(self::BELONGS_TO, 'Job', 'JOB_ID'),
			'ORDER_LINE'=>array(self::BELONGS_TO, 'ProductOrder', 'PRODUCT_ORDER_ID'),
			'sizes'=>array(self::HAS_MANY, 'JobLineSize', 'JOB_LINE_ID'),
			'color'=>array(self::BELONGS_TO, 'Lookup', 'PRODUCT_COLOR'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'JOB_ID' => 'Job',
			'PRODUCT_ID' => 'Product',
			'PRICE' => 'Price',
			'APPROVAL_DATE' => 'Approval Date',
			'APPROVAL_USER' => 'Approval User',
			'PRODUCT_COLOR'=> 'Color',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('ID',$this->ID);
		$criteria->compare('JOB_ID',$this->JOB_ID);
		$criteria->compare('PRODUCT_ID',$this->PRODUCT_ID);
		$criteria->compare('PRICE',$this->PRICE,true);
		$criteria->compare('APPROVAL_DATE',$this->APPROVAL_DATE,true);
		$criteria->compare('APPROVAL_USER',$this->APPROVAL_USER);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Approves the job line, which subtracts from inventory.
	 * Returns true if approved successfully.
	 */
	public function approve(){
		$this->APPROVAL_USER = Yii::app()->user->id;
		$this->APPROVAL_DATE = DateConverter::toDatabaseTime(time(), true);
		
		$approved = true;
		foreach($this->sizes as $sizeLine){
			$productLine = $sizeLine->productLine;
			$productLine->AVAILABLE -= $sizeLine->QUANTITY;
			$approved = $approved && $productLine->save();
		}
		
		$value = $approved && $this->save();
		return $approved;
	}
	
	/**
	 * Unapproves the job line, which adds back to inventory.
	 */
	public function unapprove(){
		$this->APPROVAL_USER = null;
		$this->APPROVAL_DATE = null;
		
		$unapproved = true;
		foreach($this->sizes as $sizeLine){
			$productLine = $sizeLine->productLine;
			$productLine->AVAILABLE += $sizeLine->QUANTITY;
			$unapproved = $unapproved && $productLine->save();
		}
		
		$unapproved = $unapproved && $this->save();
		return $unapproved;
	}
	
	/** Fills this model's attributes and relations from an array of attributes.
	 * @param array $attributes The attribute array. This may contain values for
	 * all of the attributes as well as the "sizes" relation, which should
	 * be the key to an array with sets of attributes of JobLineSize models.
	 */
	public function loadFromArray($attributes){
		$attributesInternal = $attributes;
		if(isset($attributesInternal['sizes'])){
			$sizes = $attributesInternal['sizes'];
			unset($attributesInternal['sizes']);
		} else {
			$sizes = null;
		}
		foreach($attributesInternal as $name=>$value){
			$this->$name = $value;
		}
		if($sizes){
			$keyedSizeLines = array();
			foreach($this->sizes as $sizeLine){
				$keyedSizeLines[(string) $sizeLine->ID] = $sizeLine;
			}
			$newSizeLines = array();
			for($i = 0; $i < count($sizes); $i++){
				if(isset($sizes[$i]) && is_array($sizes[$i])){
					$lineID = $sizes[$i]['ID'];
					if(isset($keyedSizeLines[$lineID])){
						$line = $keyedSizeLines[$lineID];
					} else {
						$line = new JobLineSize;
					}
					$line->attributes = $sizes[$i];
					if($line->SIZE){ //can't have a line that isn't associated with a product
						$newSizeLines[] = $line;
					}
				}
			}
			$this->sizes = $newSizeLines;
		}		
	}
	
	/**
	 * Gets the total cost of the job line.
	 */
	public function getTotal(){
		//right now, we just want to hack this for the extra large fee
		//$xlSizes = array(38, 39, 40, 51, 73, 74, 79, 80, 100);
		//$xlSizes = array(39, 40, 73, 74, 80);
		$total = 0;
		foreach($this->sizes as $sizeLine){
			$fee = $sizeLine->isExtraLarge;
			if($fee === false) $fee = 0;
			$total += ($this->PRICE + $fee) * $sizeLine->QUANTITY;
		}
		return $total;
	}
	
	/**
	 * Gets the total number of garments in this job line.
	 */
	public function getGarmentCount(){
		$garments = 0;
		foreach($this->sizes as $sizeLine){
			$garments += $sizeLine->QUANTITY;
		}
		return $garments;
	}
	
	/**
	 * Gets a value indicating whether or not this line is approved.
	 */
	public function getIsApproved(){
		return ($this->APPROVAL_USER != null);
	}
	
	/**
	 * Gets a value indicating whether or not the order associated with the productOrder
	 * associated with this line has been checked in.
	 */
	public function getIsCheckedIn(){
		return $this->orderLine->ORDER->isCheckedIn && !$this->orderLine->isShort($this);
	}
}