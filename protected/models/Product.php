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
	const DELETED = 44; //69 in GUS prod
	
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
			if($this->AVAILABLE > 0){
				$this->STATUS = Product::IN_STOCK;
			}
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
			array('STATUS, STYLE, COLOR, SIZE, AVAILABLE', 'numerical', 'integerOnly'=>true),
			array('COST', 'numerical'),
			array('VENDOR_ID, VENDOR_ITEM_ID', 'required'),
			array('VENDOR_ITEM_ID', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, COST, STATUS, STYLE, COLOR, SIZE, AVAILABLE', 'safe', 'on'=>'search'),
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
			'color' => array(self::BELONGS_TO, 'Lookup', 'COLOR'),
			'size' => array(self::BELONGS_TO, 'Lookup', 'SIZE'),
			'status' => array(self::BELONGS_TO, 'Lookup', 'STATUS'),
			'style' => array(self::BELONGS_TO, 'Lookup', 'STYLE'),
			'orders' => array(self::HAS_MANY, 'ProductOrder', 'PRODUCT_ID'),
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
			'STYLE' => 'Style',
			'COLOR' => 'Color',
			'SIZE' => 'Size',
			'AVAILABLE' => 'Surplus Inventory',
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

		$criteria->compare('ID',$this->ID);
		$criteria->compare('COST',$this->COST,true);
		$criteria->compare('STATUS',$this->STATUS);
		$criteria->compare('STYLE',$this->STYLE);
		$criteria->compare('COLOR',$this->COLOR);
		$criteria->compare('SIZE',$this->SIZE);
		$criteria->compare('AVAILABLE',$this->AVAILABLE);
		$criteria->compare('VENDOR_ITEM_ID', $this->VENDOR_ITEM_ID, true);
		$criteria->compare('STATUS', Product::DELETED, false, '<>');

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Gets a string representing a summary of this product. Only style, color,
	 * and size are included.
	 */
	public function getSummary(){
		return $this->color->TEXT . ' ' . $this->style->TEXT . ', ' . $this->size->TEXT;
	}
	
	/**
	 * Gets a string representing the vendor information of this product.
	 */
	public function getVendorStyle(){
		if(isset($this->VENDOR_ID) && isset($this->style)){
			$summary = $this->style->TEXT . ' - ' . $this->VENDOR->NAME; 
		} else {
			$summary = $this->ID . ' - ' . '8/7 Central';
		}
		return $summary;
	}
	
	/**
	 * Gets the colors that are available for the given vendor item ID.
	 * @return array An array containing instances of Lookup with the allowed colors.
	 */
	public static function getAllowedColors($itemID){
		$style = Lookup::model()->findByAttributes(array('TEXT'=>$itemID));
		$results = Product::model()->findAllByAttributes(array('STYLE'=>$style->ID), 'STATUS != '.Product::DELETED);
		$colors = array();
		foreach($results as $product){
			$colors[(string) $product->COLOR] = $product->color;
		}
		return $colors;
	}
	
	/**
	 * Gets the sizes that are available for the given vendor item ID.
	 * @return array An array containing instances of Lookup with the allowed sizes.
	 */
	public static function getAllowedSizes($itemID){
		$style = Lookup::model()->findByAttributes(array('TEXT'=>$itemID));
		$results = Product::model()->findAllByAttributes(array('STYLE'=>$style->ID), 'STATUS <> '.Product::DELETED);
		$sizes = array();
		foreach($results as $product){
			$sizes[(string) $product->SIZE] = $product->size;
		}
		return $sizes;
	}
	
	public static function getStyle($itemID){
		$style = Lookup::model()->findByAttributes(array('TEXT'=>$itemID));
		return $style;
	}
	
	/**
	 * Gets the cost of the item, provided by the manufacturer.
	 * @return float The cost of the item.
	 */
	public static function getCost($itemID){
		$style = Lookup::model()->findByAttributes(array('TEXT'=>$itemID));
		$results = Product::model()->findByAttributes(array('STYLE'=>$style->ID), 'STATUS <> '.Product::DELETED);
		return $results->COST;
	}
	
	public function delete(){
		$this->STATUS = Product::DELETED;
		return $this->save();
	}
}