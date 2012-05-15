<?php

/**
 * This is the model class for table "product_line".
 *
 * The followings are the available columns in table 'product_line':
 * @property integer $PRODUCT_ID
 * @property integer $COLOR
 * @property integer $SIZE
 * @property integer $AVAILABLE
 *
 * The followings are the available model relations:
 * @property Lookup $cOLOR
 * @property Product $pRODUCT
 * @property Lookup $sIZE
 */
class ProductLine extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ProductLine the static model class
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
		return 'product_line';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('PRODUCT_ID, COLOR, SIZE', 'required'),
			array('PRODUCT_ID, COLOR, SIZE, AVAILABLE', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('PRODUCT_ID, COLOR, SIZE, AVAILABLE', 'safe', 'on'=>'search'),
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
			'color' => array(self::BELONGS_TO, 'Lookup', 'COLOR'),
			'product' => array(self::BELONGS_TO, 'Product', 'PRODUCT_ID'),
			'size' => array(self::BELONGS_TO, 'Lookup', 'SIZE'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'PRODUCT_ID' => 'Product',
			'COLOR' => 'Color',
			'SIZE' => 'Size',
			'AVAILABLE' => 'Available',
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

		$criteria->compare('PRODUCT_ID',$this->PRODUCT_ID);
		$criteria->compare('COLOR',$this->COLOR);
		$criteria->compare('SIZE',$this->SIZE);
		$criteria->compare('AVAILABLE',$this->AVAILABLE);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}