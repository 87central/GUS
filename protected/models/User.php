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
	const CUSTOMER_ROLE = 1;
	const DEFAULT_ROLE = 2;
	const ADMIN_ROLE = 3;
	
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
		return User::model()->findAllByAttributes(array('ROLE'=>$role));
	}
}