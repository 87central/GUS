<?php

/**
 * This is the model class for table "invoice".
 *
 * The followings are the available columns in table 'invoice':
 * @property integer $ID
 * @property integer $CUSTOMER_ID
 * @property integer $USER_ID
 * @property string $TITLE
 * @property string $DATE
 * @property string $TERMS
 * @property string $TAX_RATE
 * @property string $TIMESTAMP
 *
 * The followings are the available model relations:
 * @property Customer $cUSTOMER
 * @property User $uSER
 * @property InvoiceLine[] $invoiceLines
 */
class Invoice extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Invoice the static model class
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
		return 'invoice';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('CUSTOMER_ID, USER_ID, TITLE, TIMESTAMP', 'required'),
			array('CUSTOMER_ID, USER_ID', 'numerical', 'integerOnly'=>true),
			array('TITLE', 'length', 'max'=>200),
			array('TAX_RATE', 'length', 'max'=>3),
			array('DATE, TERMS', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, CUSTOMER_ID, USER_ID, TITLE, DATE, TERMS, TAX_RATE, TIMESTAMP', 'safe', 'on'=>'search'),
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
			'uSER' => array(self::BELONGS_TO, 'User', 'USER_ID'),
			'invoiceLines' => array(self::HAS_MANY, 'InvoiceLine', 'INVOICE_ID'),
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
			'USER_ID' => 'User',
			'TITLE' => 'Title',
			'DATE' => 'Date',
			'TERMS' => 'Terms',
			'TAX_RATE' => 'Tax Rate',
			'TIMESTAMP' => 'Timestamp',
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
		$criteria->compare('USER_ID',$this->USER_ID);
		$criteria->compare('TITLE',$this->TITLE,true);
		$criteria->compare('DATE',$this->DATE,true);
		$criteria->compare('TERMS',$this->TERMS,true);
		$criteria->compare('TAX_RATE',$this->TAX_RATE,true);
		$criteria->compare('TIMESTAMP',$this->TIMESTAMP,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}