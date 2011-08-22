<?php

/**
 * This is the model class for table "credit_card".
 *
 * The followings are the available columns in table 'credit_card':
 * @property integer $ID
 * @property integer $CUSTOMER_ID
 * @property string $NAME
 * @property string $NUMBER
 * @property string $EXPIRATION
 * @property string $ZIP
 * @property string $SEC_CODE
 *
 * The followings are the available model relations:
 * @property Customer $cUSTOMER
 */
class CreditCard extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CreditCard the static model class
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
		return 'credit_card';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('CUSTOMER_ID', 'required'),
			array('CUSTOMER_ID', 'numerical', 'integerOnly'=>true),
			array('NAME, NUMBER, EXPIRATION, ZIP, SEC_CODE', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, CUSTOMER_ID, NAME, NUMBER, EXPIRATION, ZIP, SEC_CODE', 'safe', 'on'=>'search'),
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
			'cUSTOMER' => array(self::BELONGS_TO, 'Customer', 'CUSTOMER_ID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'CUSTOMER_ID' => 'Customer',
			'NAME' => 'Name',
			'NUMBER' => 'Number',
			'EXPIRATION' => 'Expiration',
			'ZIP' => 'Zip',
			'SEC_CODE' => 'Sec Code',
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
		$criteria->compare('CUSTOMER_ID',$this->CUSTOMER_ID);
		$criteria->compare('NAME',$this->NAME,true);
		$criteria->compare('NUMBER',$this->NUMBER,true);
		$criteria->compare('EXPIRATION',$this->EXPIRATION,true);
		$criteria->compare('ZIP',$this->ZIP,true);
		$criteria->compare('SEC_CODE',$this->SEC_CODE,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}