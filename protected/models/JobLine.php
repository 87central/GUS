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
	private $_style; //id of the style lookup item
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
	
	protected function afterFind(){
		$this->_oldQuantity = $this->QUANTITY;
		$this->_oldProductID = $this->PRODUCT_ID;
		$product = $this->product;
		if($product){
			$this->_color = $product->COLOR;
			$this->_style = $product->STYLE;
			$this->_size = $product->SIZE;
		}
	}
	
	protected function beforeSave(){
		/*Because lines may be modified by admins AFTER they are approved,
		 * we need to adjust the inventory quantity appropriately if the quantity changes.
		 * We also need to ensure that the product described does exist. If not, we will
		 * create a placeholder stock item.*/
		
		if($this->_oldProductID !== null){
			if($this->_oldProductID != $this->PRODUCT_ID){
				$oldProduct = Product::model()->findByPk((int) $this->_oldProductID);
			} else {
				$oldProduct = $this->product;
			}
		}
		
		if($oldProduct && $this->isApproved){
			$oldProduct->AVAILABLE += $this->_oldQuantity;
		}
		
		$newProduct = Product::model()->findByAttributes(array(
			'COLOR'=>$this->_color,
			'STYLE'=>$this->_style,
			'SIZE'=>$this->_size,
		));
		
		if($newProduct === null){
			$newProduct = new Product;
			$newProduct->COLOR = $this->_color;
			$newProduct->STYLE = $this->_style;
			$newProduct->SIZE = $this->_size;
			$newProduct->STATUS = Product::PLACEHOLDER;
			if($this->isApproved){
				$newProduct->AVAILABLE = 0 - $this->QUANTITY;
			}			
		} else {
			if($newProduct->ID == $this->product->ID){
				$newProduct = $this->product;
			}
			if($this->isApproved){			
				$newProduct->AVAILABLE -= $this->QUANTITY;
			}
		}		
		if(parent::beforeSave() && $newProduct->save() && ($oldProduct == null || $oldProduct->save())){
			$this->PRODUCT_ID = $newProduct->ID;
			return true;
		} else {
			return false;
		}
	}
	
	protected function beforeDelete(){
		$product = $this->product;
		if($this->isApproved){
			$product->AVAILABLE += $this->QUANTITY;
		}
		return parent::beforeDelete() && $product->save();
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
			array('APPROVAL_DATE', 'safe'),
			array('PRICE', 'numerical'),
			array('color, size, style', 'numerical'),
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
			'product' => array(self::BELONGS_TO, 'Product', 'PRODUCT_ID'),
			'aPPROVALUSER' => array(self::BELONGS_TO, 'User', 'APPROVAL_USER'),
			'job' => array(self::BELONGS_TO, 'Job', 'JOB_ID'),
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
			'QUANTITY' => 'Quantity',
			'PRICE' => 'Price',
			'APPROVAL_DATE' => 'Approval Date',
			'APPROVAL_USER' => 'Approval User',
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
		$criteria->compare('QUANTITY',$this->QUANTITY);
		$criteria->compare('PRICE',$this->PRICE,true);
		$criteria->compare('APPROVAL_DATE',$this->APPROVAL_DATE,true);
		$criteria->compare('APPROVAL_USER',$this->APPROVAL_USER);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public function getColor(){
		if(isset($this->_color)){
			return $this->_color;
		} else {
			if($this->product !== null){
				return $this->product->COLOR;
			} else {
				return null;
			}
		}
	}
	
	public function setColor($value){
		$this->_changed = $value != $this->_color;
		$this->_color = $value;
	}
	
	public function getSize(){
		if(isset($this->_size)){
			return $this->_size;
		} else {
			if($this->product !== null){
				return $this->product->SIZE;
			} else {
				return null;
			}
		}
	}
	
	public function setSize($value){
		$this->_changed = $value != $this->_size;
		$this->_size = $value;
	}
	
	public function getStyle(){
		if(isset($this->_style)){
			return $this->_style;
		} else {
			if($this->product !== null){
				return $this->product->STYLE;
			} else {
				return null;
			}
		}
	}
	
	public function setStyle($value){
		$this->_changed = $value != $this->_style;
		$this->_style = $value;
	}
	
	/**
	 * Approves the job line, which subtracts from inventory.
	 * Returns true if approved successfully.
	 */
	public function approve(){
		$this->APPROVAL_USER = Yii::app()->user->id;
		$this->APPROVAL_DATE = DateConverter::toDatabaseTime(time(), true);
		$product = $this->product;
		$product->AVAILABLE -= $this->QUANTITY;
		$value = $product->save();
		$value = $value && $this->save();
		return $value;
	}
	
	/**
	 * Unapproves the job line, which adds back to inventory.
	 */
	public function unapprove(){
		$this->APPROVAL_USER = null;
		$this->APPROVAL_DATE = null;
		$product = $this->product;
		$product->AVAILABLE += $this->QUANTITY;
		$value = $product->save();
		$value = $value && $this->save();
		return $value;
	}
	
	/**
	 * Gets the total cost of the job line.
	 */
	public function getTotal(){
		return $this->PRICE * $this->QUANTITY;
	}
	
	/**
	 * Gets a value indicating whether or not this line is approved.
	 */
	public function getIsApproved(){
		return ($this->APPROVAL_USER != null);
	}
}