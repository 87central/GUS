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