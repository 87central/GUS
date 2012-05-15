<?php

/**
 * This is the model class for table "product".
 *
 * The followings are the available columns in table 'product':
 * @property integer $ID
 * @property string $COST
 * @property integer $STATUS
 * @property integer $STYLE
 * @property integer $COLOR
 * @property integer $SIZE
 * @property integer $AVAILABLE
 *
 * The followings are the available model relations:
 * @property JobLine[] $jobLines
 * @property Lookup $cOLOR
 * @property Lookup $sIZE
 * @property Lookup $sTATUS
 * @property Lookup $sTYLE
 * @property ProductOrder[] $productOrders
 */
class Product extends CActiveRecord
{
	//product statuses
	const IN_STOCK = 16; //in stock
	const ORDERED = 17; //no inventory, ordered
	const BACKORDERED = 18; //backordered by supplier(s)
	const NO_STOCK = 19; //no inventory, not ordered
	const PLACEHOLDER = 32; //basically a temporary stock item, which,
							//if ordered, becomes a permanent stock item.
	const DELETED = 69; //69 in GUS prod
	
	const EXTRA_LARGE_FEE = 2;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Product the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'product';
	}
	
	public function beforeSave(){
		if(parent::beforeSave()){
			//change the product status based on the inventory amount.
			/*if($this->AVAILABLE > 0){
				$this->STATUS = Product::IN_STOCK;
			}*/
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('STATUS', 'numerical', 'integerOnly'=>true),
			array('COST', 'numerical'),
			array('VENDOR_ID, VENDOR_ITEM_ID', 'required'),
			array('VENDOR_ITEM_ID', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, COST, STATUS', 'safe', 'on'=>'search'),
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
			'jobLines' => array(self::HAS_MANY, 'JobLine', 'PRODUCT_ID'),
			'status' => array(self::BELONGS_TO, 'Lookup', 'STATUS'),
			'orders' => array(self::HAS_MANY, 'ProductOrder', 'PRODUCT_ID'),
			'lines'=> array(self::HAS_MANY, 'ProductLine', 'PRODUCT_ID'),
			'VENDOR'=> array(self::BELONGS_TO, 'Vendor', 'VENDOR_ID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'COST' => 'Cost',
			'STATUS' => 'Status',
			'VENDOR_ID' => 'Vendor',
			'VENDOR_ITEM_ID' => 'Vendor Item ID',
			'vendorStyle' => 'Style',
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

		//$criteria->compare('ID',$this->ID);
		//$criteria->compare('COST',$this->COST,true);
		//$criteria->compare('STATUS',$this->STATUS);
		//$criteria->compare('STYLE',$this->STYLE);
		//$criteria->compare('COLOR',$this->COLOR);
		//$criteria->compare('SIZE',$this->SIZE);
		//$criteria->compare('AVAILABLE',$this->AVAILABLE);
		$criteria->compare('VENDOR_ITEM_ID', $this->VENDOR_ITEM_ID, true);
		$criteria->compare('STATUS', '<> '.Product::DELETED, false);
		$criteria->limit = -1;
		

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
			'pagination'=>false,
		));
	}
	
	/**
	 * Gets a string representing a summary of this product. Only style, color,
	 * and size are included.
	 */
	public function getSummary(){
		//return $this->color->TEXT . ' ' . $this->style->TEXT . ', ' . $this->size->TEXT;
		return $this->vendorStyle;
	}
	
	/**
	 * Gets a string representing the vendor information of this product.
	 */
	public function getVendorStyle(){
		if(isset($this->VENDOR_ID) && isset($this->VENDOR_ITEM_ID)){
			$summary = $this->VENDOR_ITEM_ID . ' - ' . $this->VENDOR->NAME; 
		} else {
			$summary = $this->ID . ' - ' . '8/7 Central';
		}
		return $summary;
	}
	
	/**
	 * Gets the cost of the item, provided by the manufacturer.
	 * @return float The cost of the item.
	 */
	public static function getCost($itemID){
		$results = Product::model()->findByAttributes(array('VENDOR_ITEM_ID'=>$itemID), 'STATUS <> '.Product::DELETED);
		if($results) {
			return $results->COST;
		} else {
			return null;
		}
	}
	
	/**
	 * Gets the set of products associated with a given vendor item ID.
	 * @param string $itemID The vendor item ID.
	 * @return Product The first item matching the search.
	 */
	public static function getProduct($itemID){
		$results = Product::model()->findByAttributes(array('VENDOR_ITEM_ID'=>$itemID), 'STATUS <> '.Product::DELETED);		
		return $results;
	}
	
	/**
	 * Gets the set of product lines associated with a given vendor item ID.
	 * @param int $vendorID The vendor ID.
	 * @param string $itemID The vendor item ID.
	 * @return array An array mapping the set of valid color IDs to the set of valid sizeIDs to the set of product lines. E.g.
	 * $result[colorID][sizeID] will be an instance of a ProductLine.
	 */
	public static function getProductLines($vendorID, $itemID){
		$results = Product::model()->findByAttributes(array('VENDOR_ITEM_ID'=>$itemID, 'VENDOR_ID'=>$vendorID), 'STATUS <> '.Product::DELETED);
		$finalResults = array();
		foreach($results as $line){
			$finalResults[(string) $line->COLOR][(string) $line->SIZE] = $line;
		}
		return $finalResults;
	}
	
	/**
	 * Gets the colors allowed for the product.
	 */
	public function getAllowedColors(){
		$finalResults = array();
		foreach($this->lines as $line){
			$finalResults[(string) $line->COLOR] = $line->color;
		}
		return $finalResults;
	}
	
	/**
	 * Gets the sizes allowed for the product.
	 */
	public function getAllowedSizes(){
		$finalResults = array();
		foreach($this->lines as $line){
			$finalResults[(string) $line->SIZE] = $line->size;	
		}
		return $finalResults;
	}
	
	public function delete(){
		$this->STATUS = Product::DELETED;
		return $this->save();
	}
}