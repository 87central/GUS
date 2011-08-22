<?php

/**
 * This is the model class for table "lookup".
 *
 * The followings are the available columns in table 'lookup':
 * @property integer $ID
 * @property integer $CODE
 * @property string $TEXT
 * @property string $EXTENDED
 * @property integer $ORDER
 * @property string $TYPE
 *
 * The followings are the available model relations:
 * @property Product[] $products
 * @property Product[] $products1
 * @property Product[] $products2
 * @property Product[] $products3
 * @property User[] $users
 */
class Lookup extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Lookup the static model class
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
		return 'lookup';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('CODE, TYPE', 'required'),
			array('CODE, ORDER', 'numerical', 'integerOnly'=>true),
			array('TEXT', 'length', 'max'=>60),
			array('TYPE', 'length', 'max'=>45),
			array('EXTENDED', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, CODE, TEXT, EXTENDED, ORDER, TYPE', 'safe', 'on'=>'search'),
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
			'products' => array(self::HAS_MANY, 'Product', 'COLOR'),
			'products1' => array(self::HAS_MANY, 'Product', 'SIZE'),
			'products2' => array(self::HAS_MANY, 'Product', 'STATUS'),
			'products3' => array(self::HAS_MANY, 'Product', 'STYLE'),
			'users' => array(self::HAS_MANY, 'User', 'ROLE'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'CODE' => 'Code',
			'TEXT' => 'Text',
			'EXTENDED' => 'Extended',
			'ORDER' => 'Order',
			'TYPE' => 'Type',
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
		$criteria->compare('CODE',$this->CODE);
		$criteria->compare('TEXT',$this->TEXT,true);
		$criteria->compare('EXTENDED',$this->EXTENDED,true);
		$criteria->compare('ORDER',$this->ORDER);
		$criteria->compare('TYPE',$this->TYPE,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}