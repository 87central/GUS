<?php

/**
 * This is the model class for table "job_line".
 *
 * The followings are the available columns in table 'job_line':
 * @property integer $ID
 * @property integer $JOB_ID
 * @property integer $PRODUCT_ID
 * @property integer $PRINT_ID
 * @property integer $QUANTITY
 * @property string $PRICE
 * @property string $APPROVAL_DATE
 * @property integer $APPROVAL_USER
 *
 * The followings are the available model relations:
 * @property Product $pRODUCT
 * @property User $aPPROVALUSER
 * @property Job $jOB
 * @property Print $pRINT
 */
class JobLine extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return JobLine the static model class
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
		return 'job_line';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('JOB_ID, PRODUCT_ID, PRINT_ID, QUANTITY, APPROVAL_USER', 'numerical', 'integerOnly'=>true),
			array('PRICE', 'length', 'max'=>2),
			array('APPROVAL_DATE', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, JOB_ID, PRODUCT_ID, PRINT_ID, QUANTITY, PRICE, APPROVAL_DATE, APPROVAL_USER', 'safe', 'on'=>'search'),
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
			'pRODUCT' => array(self::BELONGS_TO, 'Product', 'PRODUCT_ID'),
			'aPPROVALUSER' => array(self::BELONGS_TO, 'User', 'APPROVAL_USER'),
			'jOB' => array(self::BELONGS_TO, 'Job', 'JOB_ID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'JOB_ID' => 'Job',
			'PRODUCT_ID' => 'Product',
			'PRINT_ID' => 'Print',
			'QUANTITY' => 'Quantity',
			'PRICE' => 'Price',
			'APPROVAL_DATE' => 'Approval Date',
			'APPROVAL_USER' => 'Approval User',
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
		$criteria->compare('JOB_ID',$this->JOB_ID);
		$criteria->compare('PRODUCT_ID',$this->PRODUCT_ID);
		$criteria->compare('PRINT_ID',$this->PRINT_ID);
		$criteria->compare('QUANTITY',$this->QUANTITY);
		$criteria->compare('PRICE',$this->PRICE,true);
		$criteria->compare('APPROVAL_DATE',$this->APPROVAL_DATE,true);
		$criteria->compare('APPROVAL_USER',$this->APPROVAL_USER);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}