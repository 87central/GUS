<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $ID
 * @property string $EMAIL
 * @property string $PASSWORD
 * @property string $FIRST
 * @property string $LAST
 * @property string $PHONE
 * @property integer $ROLE
 *
 * The followings are the available model relations:
 * @property Customer[] $customers
 * @property Job[] $jobs
 * @property JobLine[] $jobLines
 * @property Print[] $prints
 * @property Lookup $rOLE
 */
class User extends CActiveRecord
{
	private $_retrievedPassword; //the password retreived on a find operation
	
	const CUSTOMER_ROLE = 14;
	const DEFAULT_ROLE = 13;
	const ADMIN_ROLE = 15;
	const LEAD_ROLE = 16;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
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
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ROLE', 'numerical', 'integerOnly'=>true),
			array('EMAIL, PASSWORD, FIRST, LAST', 'length', 'max'=>45),
			array('PHONE', 'length', 'max'=>20),
			array('isAdmin, isLead, isCustomer, isPrinter', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, EMAIL, PASSWORD, FIRST, LAST, PHONE, ROLE', 'safe', 'on'=>'search'),
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
			'customers' => array(self::HAS_MANY, 'Customer', 'USER_ID'),
			'jobs' => array(self::HAS_MANY, 'Job', 'LEADER_ID'),
			'jobLines' => array(self::HAS_MANY, 'JobLine', 'APPROVAL_USER'),
			'prints' => array(self::HAS_MANY, 'Print', 'APPROVAL_USER'),
			'rOLE' => array(self::BELONGS_TO, 'Lookup', 'ROLE'),
			'assignedEvents' => array(self::HAS_MANY, 'EventLog', 'USER_ASSIGNED'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'EMAIL' => 'Email',
			'PASSWORD' => 'Password',
			'FIRST' => 'First',
			'LAST' => 'Last',
			'PHONE' => 'Phone',
			'ROLE' => 'Role',
			'isAdmin'=> 'Administrator?',
			'isLead'=>'Project Lead?',
			'isPrinter'=>'Printer?',
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
		$criteria->compare('EMAIL',$this->EMAIL,true);
		$criteria->compare('PASSWORD',$this->PASSWORD,true);
		$criteria->compare('FIRST',$this->FIRST,true);
		$criteria->compare('LAST',$this->LAST,true);
		$criteria->compare('PHONE',$this->PHONE,true);
		$criteria->compare('ROLE',$this->ROLE);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Gets the list of users with the given role, where the role is one of 
	 * the constants defined in this class.
	 */
	public static function listUsersWithRole($role){
		switch($role){
			case User::CUSTOMER_ROLE : $index = 3;
									   break; 
			case User::DEFAULT_ROLE : $index = 4;
									  break;
			case User::ADMIN_ROLE : $index = 1;
									break;
			case User::LEAD_ROLE : $index = 2;
								   break;
		}
		return User::model()->findAll('SUBSTR(`ROLE`, '.$index.', 1) = 1');
	}
	
	protected function afterFind(){
		parent::afterFind();
		$this->_retrievedPassword = $this->getAttribute('PASSWORD');
		$this->setAttribute('PASSWORD', null);
	}
	
	/**
	 * Determines whether the given password matches the password retrieved from the database.
	 * @param string $password The password to test.
	 * @return boolean True if the passwords match, otherwise false.
	 */
	public function validatePassword($password){
		return $this->hashPassword($password) == $this->_retrievedPassword;
	}
	
	/**
	 * Gets a hashed version of the password.
	 * @return The hashed password, in a 40-character string.
	 */
	private function hashPassword($pass){
		$hashed = hash_hmac('md5', $pass, PrivateField::get('hashkey'));
		$hashed = hash_hmac('sha1', $hashed, PrivateField::get('hashkey'));
		return $hashed;
	}
	
	protected function beforeSave(){
		if(parent::beforeSave()){		
			if($this->PASSWORD != null){
				$this->setAttribute('PASSWORD', $this->hashPassword($this->PASSWORD));	
			} else {
				$this->setAttribute('PASSWORD', $this->_retrievedPassword);				
			}			
		}
		return true;
	}
	
	public function getIsAdmin(){
		return $this->ROLE[0] == '1';
	}
	
	public function setIsAdmin($value){
		Yii::trace('Setting is admin. Value is '.$value.' role is '.$this->ROLE, 'application.models.User');
		if($value){
			Yii::trace('IsAdmin true branch.', 'application.models.User');
			$this->ROLE = substr_replace($this->ROLE, '1', 0, 1);
		} else {
			Yii::trace('IsAdmin false branch.', 'application.models.User');
			$this->ROLE = substr_replace($this->ROLE, '0', 0, 1);
		}
		Yii::trace('Set is admin. Role is '.$this->ROLE, 'application.models.User');
	}
	
	public function getIsCustomer(){
		return $this->ROLE[2] == '1';
	}
	
	public function setIsCustomer($value){
		Yii::trace('Setting is customer. Value is '.$value.' role is '.$this->ROLE, 'application.models.User');
		if($value){
			Yii::trace('IsCustomer true branch.', 'application.models.User');
			$this->ROLE = substr_replace($this->ROLE, '1', 2, 1);
		} else {
			Yii::trace('IsCustomer false branch.', 'application.models.User');
			$this->ROLE = substr_replace($this->ROLE, '0', 2, 1);
		}
		Yii::trace('Set is customer. Role is '.$this->ROLE, 'application.models.User');
	}
	
	public function getIsLead(){
		return $this->ROLE[1] == '1';
	}
	
	public function setIsLead($value){
		Yii::trace('Setting is leader. Value is '.$value.' role is '.$this->ROLE, 'application.models.User');
		if($value){
			Yii::trace('IsLead true branch.', 'application.models.User');
			$this->ROLE = substr_replace($this->ROLE, '1', 1, 1);
		} else {
			Yii::trace('IsLead false branch.', 'application.models.User');
			$this->ROLE = substr_replace($this->ROLE, '0', 1, 1);
		}
		Yii::trace('Set is lead. Role is '.$this->ROLE, 'application.models.User');
	}
	
	public function getIsPrinter(){
		return $this->ROLE[3] == '1';
	}
	
	public function setIsPrinter($value){
		Yii::trace('Setting is printer. Value is '.$value.' role is '.$this->ROLE, 'application.models.User');
		if($value){
			Yii::trace('IsPrinter true branch.', 'application.models.User');
			$this->ROLE = substr_replace($this->ROLE, '1', 3, 1);
		} else {
			Yii::trace('IsPrinter false branch.', 'application.models.User');
			$this->ROLE = substr_replace($this->ROLE, '0', 3, 1);
		}
		Yii::trace('Set is printer. Role is '.$this->ROLE, 'application.models.User');
	}
}