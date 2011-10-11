<?php

/**
 * This is the model class for table "product_order".
 *
 * The followings are the available columns in table 'product_order':
 * @property integer $ID
 * @property integer $PRODUCT_ID
 * @property integer $ORDER_ID
 * @property integer $QUANTITY_ORDERED
 * @property integer $QUANTITY_ARRIVED
 * @property string $COST
 *
 * The followings are the available model relations:
 * @property Order $oRDER
 * @property Product $pRODUCT
 */
class ProductOrder extends CActiveRecord
{
	private $_quantityArrived;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return ProductOrder the static model class
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
		return 'product_order';
	}
	
	protected function afterFind(){
		parent::afterFind();
		$this->_quantityArrived = $this->QUANTITY_ARRIVED;
	}
	
	protected function beforeSave(){
		if(parent::beforeSave()){
			/*
			 *If the order hasn't actually arrived, don't do anything with the quantities, but
			 *if the order has indeed arrived, there are several cases.
			 *
			 * Either the user is adjusting the quantity arrived, in which case the value
			 * in the private variable will not be null, or they are entering it for the
			 * first time, in which case the private variable will be null.
			 * 
			 * Within the above cases, there are also two possibilities. It is possible
			 * that there IS a value in the "new" quantity arrived field, or that there is
			 * no value. In the case of no value, we assume that the quantity ordered is the
			 * same as the quantity arrived. In the case that there is a value, we assume
			 * that the quantity arrived is both more accurate than the quantity ordered
			 * and the retrieved quantity arrived, and we set the availability of inventory
			 * appropriately.
			 */
			if($this->ORDER->STATUS = Order::ARRIVED){
				$product = $this->PRODUCT;
				if($this->_quantityArrived === null){
					if($this->QUANTITY_ARRIVED !== null){
						$product->AVAILABLE  = $product->AVAILABLE - $this->QUANTITY_ORDERED + $this->QUANTITY_ARRIVED;	
					} else {
						$product->AVAILABLE += $this->QUANTITY_ORDERED;
					}
				} else {
					if($this->QUANTITY_ARRIVED !== null){
						$product->AVAILABLE = $product->AVAILABLE - $this->_quantityArrived + $this->QUANTITY_ARRIVED;
					} else {
						$product->AVAILABLE = $product->AVAILABLE - $this->_quantityArrived + $this->QUANTITY_ORDERED;
					}
				}	
				$product->save();			
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
			array('QUANTITY_ORDERED, QUANTITY_ARRIVED', 'numerical', 'integerOnly'=>true),
			array('COST', 'numerical'),
			array('ORDER_ID, PRODUCT_ID', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, PRODUCT_ID, ORDER_ID, QUANTITY_ORDERED, QUANTITY_ARRIVED, COST', 'safe', 'on'=>'search'),
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
			'ORDER' => array(self::BELONGS_TO, 'Order', 'ORDER_ID'),
			'PRODUCT' => array(self::BELONGS_TO, 'Product', 'PRODUCT_ID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'PRODUCT_ID' => 'Product',
			'ORDER_ID' => 'Order',
			'QUANTITY_ORDERED' => 'Quantity Ordered',
			'QUANTITY_ARRIVED' => 'Quantity Arrived',
			'COST' => 'Cost',
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
		$criteria->compare('PRODUCT_ID',$this->PRODUCT_ID);
		$criteria->compare('ORDER_ID',$this->ORDER_ID);
		$criteria->compare('QUANTITY_ORDERED',$this->QUANTITY_ORDERED);
		$criteria->compare('QUANTITY_ARRIVED',$this->QUANTITY_ARRIVED);
		$criteria->compare('COST',$this->COST,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}