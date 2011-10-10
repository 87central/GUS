<?php

/**
 * This is the model class for table "event_log".
 *
 * The followings are the available columns in table 'event_log':
 * @property integer $ID
 * @property integer $OBJECT_ID
 * @property integer $EVENT_ID
 * @property string $DATE
 * @property string $TIMESTAMP
 * @property integer $USER_ID
 * @property integer $USER_ASSIGNED
 * @property string $COMMENTS
 * @property integer $OBJECT_TYPE
 *
 * The followings are the available model relations:
 * @property User $assigned
 * @property Lookup $event
 * @property Lookup $object_type
 * @property User $USER
 * 
 * In order to save correctly, at least the following properties should be set:
 * $assocObject, $USER_ID, $DATE, $EVENT_ID. The others  are optional.
 */
class EventLog extends CActiveRecord
{
	const JOB_DUE = 10;
	const JOB_PRINT = 11;
	const JOB_PICKUP = 12;
	const ORDER_PLACED = 24;
	const ORDER_CREATED = 23;
	const ORDER_ARRIVED = 25;
	
	private $_object;
	/**
	 * Returns the static model of the specified AR class.
	 * @return EventLog the static model class
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
		return 'event_log';
	}
	
	/**
	 * Gets a user-friendly message associated with this event. The message should
	 * not be HTML-encoded.
	 */
	public function getMessage(){
		$assoc = $this->assocObject;
		$eventCore = $this->event;
		if($eventCore && $assoc){
			return $this->evaluateExpression($eventCore->EXTENDED, array('assoc'=>$assoc));
		} else {
			return null;
		}
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('OBJECT_ID, EVENT_ID, OBJECT_TYPE', 'required'),
			array('OBJECT_ID, EVENT_ID, USER_ASSIGNED', 'numerical', 'integerOnly'=>true),
			array('DATE, TIMESTAMP, COMMENTS', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, OBJECT_ID, EVENT_ID, DATE, TIMESTAMP, USER_ID, USER_ASSIGNED, COMMENTS, OBJECT_TYPE', 'safe', 'on'=>'search'),
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
			'assigned' => array(self::BELONGS_TO, 'User', 'USER_ASSIGNED'),
			'event' => array(self::BELONGS_TO, 'Lookup', 'EVENT_ID'),
			'object_type' => array(self::BELONGS_TO, 'Lookup', 'OBJECT_TYPE'),
			'USER' => array(self::BELONGS_TO, 'User', 'USER_ID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'OBJECT_ID' => 'Object',
			'EVENT_ID' => 'Event',
			'DATE' => 'Date',
			'TIMESTAMP' => 'Timestamp',
			'USER_ID' => 'User',
			'USER_ASSIGNED' => 'User Assigned',
			'COMMENTS' => 'Comments',
			'OBJECT_TYPE' => 'Object Type',
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
		$criteria->compare('OBJECT_ID',$this->OBJECT_ID);
		$criteria->compare('EVENT_ID',$this->EVENT_ID);
		$criteria->compare('DATE',$this->DATE,true);
		$criteria->compare('TIMESTAMP',$this->TIMESTAMP,true);
		$criteria->compare('USER_ID',$this->USER_ID);
		$criteria->compare('USER_ASSIGNED',$this->USER_ASSIGNED);
		$criteria->compare('COMMENTS',$this->COMMENTS,true);
		$criteria->compare('OBJECT_TYPE',$this->OBJECT_TYPE);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Gets the object associated with this event.
	 */
	public function getAssocObject(){
		if($this->_object === null){
			$model = call_user_func(array($this->OBJECT_TYPE, 'model'));
			$object = $model->findByPk((int) $this->OBJECT_ID);
		}
		return $object;	
	}
	
	/**
	 * Sets the object associated with this event.
	 */
	public function setAssocObject($value){
		$this->OBJECT_TYPE = get_class($value);
		$this->OBJECT_ID = $value->ID;
		$this->_object = $value;
	}
	
	protected function afterFind(){
		parent::afterFind();
		//convert the dates retrieved from the database to a form
		//which can be recognized by datepickers and such on the front end,
		//and which is a little more user-friendly

		$value = $this->DATE;
		$value = strtotime($value);
		$value = DateConverter::toUserTime($value, true);
		$this->DATE = $value;
	}
	
	protected function beforeSave(){
		if(parent::beforeSave()){
			$value = $this->DATE;
			$value = strtotime($value);
			$value = DateConverter::toDatabaseTime($value, true);
			$this->DATE = $value;
			$this->TIMESTAMP = new CDbExpression('NOW()');
			if($this->USER_ID == null){
				$this->USER_ID = Yii::app()->user->id; //should have at least the current user associated with the event.
			}
			return true;
		} else {
			return false;
		}
	}
}