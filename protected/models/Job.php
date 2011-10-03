<?php

/**
 * This is the model class for table "job".
 *
 * The followings are the available columns in table 'job':
 * @property integer $ID
 * @property integer $CUSTOMER_ID
 * @property integer $LEADER_ID
 * @property string $DESCRIPTION
 * @property string $NOTES
 * @property string $ISSUES
 * @property integer $RUSH
 * @property string $SET_UP_FEE
 * @property integer $SCORE
 * @property string $QUOTE
 *
 * The followings are the available model relations:
 * @property Customer $cUSTOMER
 * @property User $lEADER
 * @property JobLine[] $jobLines
 */
class Job extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Job the static model class
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
		return 'job';
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
			array('CUSTOMER_ID, LEADER_ID, RUSH, SCORE', 'numerical', 'integerOnly'=>true),
			array('SET_UP_FEE, QUOTE', 'length', 'max'=>2),
			array('DESCRIPTION, NOTES, ISSUES', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, CUSTOMER_ID, LEADER_ID, DESCRIPTION, NOTES, ISSUES, RUSH, SET_UP_FEE, SCORE, QUOTE', 'safe', 'on'=>'search'),
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
			'lEADER' => array(self::BELONGS_TO, 'User', 'LEADER_ID'),
			'jobLines' => array(self::HAS_MANY, 'JobLine', 'JOB_ID'),			
			'printJob' => array(self::BELONGS_TO, 'PrintJob', 'PRINT_ID'),
			'events'=> array(self::HAS_MANY, 'EventLog', 'OBJECT_ID', 'condition'=>'OBJECT_TYPE = \'Job\'', 'index'=>'EVENT_ID'),
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
			'LEADER_ID' => 'Leader',
			'DESCRIPTION' => 'Description',
			'NOTES' => 'Notes',
			'ISSUES' => 'Issues',
			'RUSH' => 'Rush',
			'SET_UP_FEE' => 'Set Up Fee',
			'SCORE' => 'Score',
			'QUOTE' => 'Quote',
			'totalPasses' => 'Passes',
		);
	}
	
	/**
	 * Gets an array mapping attribute names to event IDs.
	 * @return array The resultant array.
	 */
	protected function eventAttributes(){
		return array(
			'dueDate'=>EventLog::JOB_DUE,
			'printDate'=>EventLog::JOB_PRINT,
			'pickUpDate'=>EventLog::JOB_PICKUP,
		);
	}
	
	public function __get($name){
		$found = false;
		$originalName = $name;
		//first, determine if client code is requesting a "formatted" attribute
		if(($pos = strpos('formatted', $name)) === 0){
			$formatted = true;
			$name = substr($name, 9); //9 is length of "formatted"
			$first = substr($name, 0, 1); //get first character
			$first = strtolower($first);
			$name = substr_replace($name, $first, 0, 1);
		} else {
			$formatted = false;
		}
		
		//then, if the (unformatted) attribute is an event attribute,
		//get the event value
		foreach($this->eventAttributes() as $attrName => $eventID){
			if(strcmp($name, $attrName) == 0){
				$event = $this->getEventModel($eventID);
				if($formatted){
					if($event->DATE !== null){
						$formatter = new CFormatter;
						$value = $formatter->formatDate($event->DATE);
					} else {
						$value = null;
					}
				} else {
					$value = $event->DATE;
				}
				$found = true;
			}
		}
		
		//if we found it, return it, otherwise, return what the parent thinks 
		//is a matching attribute 
		if(!$found){
			return parent::__get($name);
		} else {
			return $value;
		}
	}
	
	public function __set($name, $value){
		$found = false;
		$originalName = $name;
		//first, determine if client code is requesting a "formatted" attribute
		if(($pos = strpos('formatted', $name)) === 0){
			$formatted = true;
			$name = substr($name, 9); //9 is length of "formatted"
			$first = substr($name, 0, 1); //get first character
			$first = strtolower($first);
			$name = substr_replace($name, $first, 0, 1);
		} else {
			$formatted = false;
		}
		
		//then, if the (unformatted) attribute is an event attribute,
		//set the event value
		foreach($this->eventAttributes() as $attrName => $eventID){
			if(strcmp($name, $attrName) == 0){
				$event = $this->getEventModel($eventID);
				if($formatted){
					$event->DATE = strtotime($value);
				} else {
					$event->DATE = $value;
				}
				$found = true;
			}
		}
		
		//if we found it, set it, otherwise, set what the parent thinks 
		//is a matching attribute 
		if(!$found){
			parent::__set($name, $value);
		}
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
		$criteria->compare('LEADER_ID',$this->LEADER_ID);
		$criteria->compare('DESCRIPTION',$this->DESCRIPTION,true);
		$criteria->compare('NOTES',$this->NOTES,true);
		$criteria->compare('ISSUES',$this->ISSUES,true);
		$criteria->compare('RUSH',$this->RUSH);
		$criteria->compare('SET_UP_FEE',$this->SET_UP_FEE,true);
		$criteria->compare('SCORE',$this->SCORE);
		$criteria->compare('QUOTE',$this->QUOTE,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	protected function getEventModel($eventID){
		if(!isset($this->events[$eventID])){
			$event = new EventLog;
			$event->assocObject = $this;
			$event->EVENT_ID = $eventID;
			$this->events[$eventID] = $event;
		} else {
			$event = $this->events[$eventID];
		}
		return $event;
	}
	
	protected function afterSave(){
		parent::afterSave();
		if(isset($this->events)){
			foreach($this->events as $event){
				$event->OBJECT_ID = $this->ID;
				$event->save();
			}
		}
	}
	
	public function getTotalPasses(){
		return count($this->jobLines) * $this->printJob->PASS;
	}
	
	public function getHasArt(){
		$result = false;
		if($this->printJob !== null){
			if($this->printJob->ART != null){
				$result = true;
			}
		}
		return $result;
	}
	
	//this was in the mockup, but I'm not quite sure what it's for!
	public function getHasSizes(){
		return true;
	}
}