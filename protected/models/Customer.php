<?php

/**
 * This is the model class for table "customer".
 *
 * The followings are the available columns in table 'customer':
 * @property integer $ID
 * @property integer $USER_ID
 * @property string $COMPANY
 * @property string $NOTES
 * @property string $TERMS
 *
 * The followings are the available model relations:
 * @property CreditCard[] $creditCards
 * @property User $uSER
 * @property Job[] $jobs
 */
class Customer extends CActiveRecord
{
	/**
	 * The user model associated with this customer.
	 */
	private $userModel;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Customer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function __construct($scenario = 'insert'){
		parent::__construct($scenario);
		$this->userModel = new User;
		//want to have the underlying user loaded alongside the customer
		//$this->USER = $this->userModel;
	}
	
	protected function beforeFind(){
		parent::beforeFind();
		//$this->USER = null;
	}
	
	protected function beforeSave(){
		if(parent::beforeSave()){
			$saved = $this->userModel->save();
			$this->USER_ID = $this->userModel->ID;
			return $saved;
		} else {
			return false;
		}
	}
	
	protected function afterFind(){
		parent::afterFind();
		$this->userModel = $this->USER;
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'customer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array_merge($this->userModel->rules(), array(
			array('COMPANY', 'length', 'max'=>45),
			array('NOTES, TERMS', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, USER_ID, COMPANY, NOTES, TERMS', 'safe', 'on'=>'search'),
		));
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'creditCards' => array(self::HAS_MANY, 'CreditCard', 'CUSTOMER_ID'),
			'USER' => array(self::BELONGS_TO, 'User', 'USER_ID'),
			'jobs' => array(self::HAS_MANY, 'Job', 'CUSTOMER_ID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array_merge($this->userModel->attributeLabels(), array(
			'ID' => 'ID',
			'USER_ID' => 'User',
			'COMPANY' => 'Company',
			'NOTES' => 'Notes',
			'TERMS' => 'Terms',
		));
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
		$criteria->compare('USER_ID',$this->USER_ID);
		$criteria->compare('COMPANY',$this->COMPANY,true);
		$criteria->compare('NOTES',$this->NOTES,true);
		$criteria->compare('TERMS',$this->TERMS,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public function getSummary(){
		return $this->userModel->FIRST . ' ' . $this->userModel->LAST . ', ' . $this->COMPANY;
	}
	
	public function __get($name){
		try {
			$value = parent::__get($name);
		} catch(Exception $e){
			$value = $this->userModel->$name;
		}
		return $value;
	} 
	
	public function __set($name, $value){
		if($name === 'ID'){
			//if setting primary key, set that of the customer, not of the user.
			parent::__set($name, $value);
		} else {
			try {
				$this->userModel->$name = $value;
			} catch(Exception $e){
				parent::__set($name, $value);
			}
		}
	}
}