<?php

/**
 * This is the model class for table "invoice_line".
 *
 * The followings are the available columns in table 'invoice_line':
 * @property integer $ID
 * @property integer $INVOICE_ID
 * @property integer $ITEM_TYPE_ID
 * @property string $DESCRIPTION
 * @property string $QUANTITY
 * @property string $RATE
 * @property string $AMOUNT
 *
 * The followings are the available model relations:
 * @property Invoice $iNVOICE
 * @property Lookup $iTEMTYPE
 */
class InvoiceLine extends CActiveRecord
{
	const PRINTING = 111;
	const GENERAL = 112;
	const ARTWORK = 113;
	const SETUP = 114;
	const RUSH = 115;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return InvoiceLine the static model class
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
		return 'invoice_line';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('DESCRIPTION, AMOUNT', 'required'),
			array('INVOICE_ID, ITEM_TYPE_ID', 'numerical', 'integerOnly'=>true),
			array('DESCRIPTION', 'length', 'max'=>300),
			array('QUANTITY, RATE', 'length', 'max'=>6),
			array('AMOUNT', 'length', 'max'=>8),
			array('QUANTITY, RATE, AMOUNT', 'numerical'),
			array('ID', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, INVOICE_ID, ITEM_TYPE_ID, DESCRIPTION, QUANTITY, RATE, AMOUNT', 'safe', 'on'=>'search'),
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
			'INVOICE' => array(self::BELONGS_TO, 'Invoice', 'INVOICE_ID'),
			'ITEM_TYPE' => array(self::BELONGS_TO, 'Lookup', 'ITEM_TYPE_ID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'INVOICE_ID' => 'Invoice',
			'ITEM_TYPE_ID' => 'Item Type',
			'DESCRIPTION' => 'Description',
			'QUANTITY' => 'Quantity',
			'RATE' => 'Rate',
			'AMOUNT' => 'Amount',
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
		$criteria->compare('INVOICE_ID',$this->INVOICE_ID);
		$criteria->compare('ITEM_TYPE_ID',$this->ITEM_TYPE_ID);
		$criteria->compare('DESCRIPTION',$this->DESCRIPTION,true);
		$criteria->compare('QUANTITY',$this->QUANTITY,true);
		$criteria->compare('RATE',$this->RATE,true);
		$criteria->compare('AMOUNT',$this->AMOUNT,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	protected function beforeValidate(){
		if($this->isNewRecord) $this->ID = null;
		if(!is_numeric($this->QUANTITY)) $this->QUANTITY = null;
		if(!is_numeric($this->RATE)) $this->RATE = null;
		return parent::beforeValidate();
	}
}