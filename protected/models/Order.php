<?php

/**
 * This is the model class for table "order".
 *
 * The followings are the available columns in table 'order':
 * @property integer $ID
 * @property string $EXTERNAL_ID
 * @property integer $VENDOR_ID
 * @property string $DATE
 *
 * The followings are the available model relations:
 * @property Vendor $vENDOR
 * @property ProductOrder[] $productOrders
 */
class Order extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Order the static model class
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
		return 'order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('VENDOR_ID', 'numerical', 'integerOnly'=>true),
			array('EXTERNAL_ID', 'length', 'max'=>60),
			array('DATE', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, EXTERNAL_ID, VENDOR_ID, DATE', 'safe', 'on'=>'search'),
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
			'vENDOR' => array(self::BELONGS_TO, 'Vendor', 'VENDOR_ID'),
			'productOrders' => array(self::HAS_MANY, 'ProductOrder', 'ORDER_ID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'EXTERNAL_ID' => 'External',
			'VENDOR_ID' => 'Vendor',
			'DATE' => 'Date',
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
		$criteria->compare('EXTERNAL_ID',$this->EXTERNAL_ID,true);
		$criteria->compare('VENDOR_ID',$this->VENDOR_ID);
		$criteria->compare('DATE',$this->DATE,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}