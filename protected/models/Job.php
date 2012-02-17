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
	//job statuses 
	const CREATED = 26; //the job has just been created, and perhaps a quote has been given
	const INVOICED = 31; //a formal invoice has been sent.
	const PAID = 27; //the invoice was received and the customer has paid for it.
	const SCHEDULED = 28; //the job has been scheduled on the timeline.
	const COMPLETED = 29; //the job has been completed.
	const CANCELED = 30; //the job has been canceled.
	
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
			array('RUSH, SCORE', 'numerical', 'integerOnly'=>true),
			array('ID, NAME, DESCRIPTION, NOTES, ISSUES, STATUS', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, CUSTOMER_ID, LEADER_ID, NAME, DESCRIPTION, NOTES, ISSUES, RUSH, SET_UP_FEE, SCORE, QUOTE', 'safe', 'on'=>'search'),
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
			'CUSTOMER' => array(self::BELONGS_TO, 'Customer', 'CUSTOMER_ID'),
			'LEADER' => array(self::BELONGS_TO, 'User', 'LEADER_ID'),
			'PRINTER'=> array(self::BELONGS_TO, 'User', 'PRINTER_ID'),
			'jobLines' => array(self::HAS_MANY, 'JobLine', 'JOB_ID'),			
			'printJob' => array(self::BELONGS_TO, 'PrintJob', 'PRINT_ID'),
			'events'=> array(self::HAS_MANY, 'EventLog', 'OBJECT_ID', 'condition'=>'OBJECT_TYPE = \'Job\'', 'index'=>'EVENT_ID'),
			'status'=>array(self::BELONGS_TO, 'Lookup', 'STATUS'),
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
			'QUOTE' => 'Quoted',
			'score' => 'Auto Score',
			'quote' => 'Auto Quote Total',
			'totalPasses' => 'Passes',
			'formattedDueDate'=> 'Due Date',
			'formattedPickUpDate' =>'Pickup Date',
			'NAME'=>'Name',
			'PRINTER'=>'Printer',
			'PRINTER_ID'=>'Printer',
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
		if(($pos = strpos($name, 'formatted')) === 0){
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
						$value = $event->DATE;
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
		if(strlen($name) > 9 && substr($name, 0, 9) === 'formatted'){
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
					$event->DATE = $value;
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
	
	/**
	 * Gets a list of jobs with the given status value.
	 * @param mixed $status The status value, or array of status values, by which to filter.
	 * @return array The set of jobs with the given status(es).
	 */
	public static function listJobsByStatus($status){
		return Job::model()->findAllByAttributes(array('STATUS'=>$status));
	}
	
	protected function getEventModel($eventID){
		$events = array();
		foreach($this->events as $event){
			$events[(string) $event->EVENT_ID] = $event;
		}
		if(!isset($events[(string) $eventID])){
			$event = new EventLog;
			$event->assocObject = $this;
			$event->EVENT_ID = $eventID;
			if($this->events === null){
				$this->events = array();
			}
			//$this->events[(string) $eventID] = $event;
			$events[(string) $eventID] = $event;
		} else {
			$event = $events[(string) $eventID];
		}
		$this->events = $events;		
		return $event;
	}
	
	/** Fills this model's attributes and relations from an array of attributes.
	 * @param array $attributes The attribute array. This may contain values for
	 * all of the attributes as well as the "jobLines" relation, which should
	 * be the key to an array with sets of attributes of JobLine models.
	 */
	public function loadFromArray($attributes){
		$attributesInternal = $attributes;
		if(isset($attributesInternal['jobLines'])){
			$jobLines = $attributesInternal['jobLines'];
			unset($attributesInternal['jobLines']);
		} else {
			$jobLines = null;
		}
		foreach($attributesInternal as $name=>$value){
			$this->$name = $value;
		}
		if($jobLines){
			$keyedJobLines = array();
			foreach($this->jobLines as $line){
				$keyedJobLines[(string) $line->ID] = $line;
			}
			$newJobLines = array();
			for($i = 0; $i < count($jobLines); $i++){
				if(isset($jobLines[$i]) && is_array($jobLines[$i])){
					$lineID = $jobLines[$i]['ID'];
					if(isset($keyedJobLines[$lineID])){
						$line = $keyedJobLines[$lineID];
					} else {
						$line = new JobLine;
					}
					$line->attributes = $jobLines[$i];
					if($line->PRODUCT_ID){ //can't have a line that isn't associated with a product
						$newJobLines[] = $line;
					}
				}
			}
			$this->jobLines = $newJobLines;
		}		
	}
	
	protected function beforeValidate(){
		if(parent::beforeValidate()){
			if($this->STATUS == null){
				$this->STATUS = Job::CREATED;
			}
			$valid = true;
			foreach($this->jobLines as $line){
				$line->JOB_ID = $this->ID;
				$valid = $valid && $line->validate();
			}
			return $valid;
		} else {
			return false;
		}
	}
	
	protected function beforeSave(){
		if(parent::beforeSave()){
			//ensures that there is an event created for each event attribute. 
			foreach($this->eventAttributes() as $eventID){
				$this->getEventModel($eventID);
			}
			return true;
		} else {
			return false;
		}
	}
	
	protected function afterSave(){
		parent::afterSave();
		if(isset($this->events)){
			foreach($this->events as $event){				
				$event->OBJECT_ID = $this->ID;				
				$event->save();
			}
		}
		if(isset($this->jobLines)){
			foreach($this->jobLines as $line){
				$line->JOB_ID = $this->ID;
				$line->save();
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
		$result = true;
		if(count($this->jobLines) == 0){
			$result = false;
		} else {
			foreach($this->jobLines as $line){
				$result = $result && $line->isApproved;
			}
		}
		return $result; 
	}
	
	/**
	 * Gets the total cost (for the customer) of the job.
	 */
	public function getTotal(){
		$front = 0;
		$back = 0;
		$sleeve = 0;
		if($this->printJob){
			$front = $this->printJob->FRONT_PASS;
			$back = $this->printJob->BACK_PASS;
			$sleeve = $this->printJob->SLEEVE_PASS;
		}
		
		$garmentTotal = CostCalculator::calculateTotal($this->garmentCount, $front, $back, $sleeve, 0);
		$garmentTotal += $this->garmentCost;
		return $garmentTotal + $this->SET_UP_FEE + ($this->printJob == null ? 0 : $this->printJob->COST);
	}
	
	/**
	 * Gets the total auto-generated cost (for the customer) for each garment.
	 */
	public function getGarmentPrice(){
		$garments = $this->garmentCount;		
		return ($garments == 0 ? 0 : $this->total / $garments);
	}
	
	public function getGarmentCount(){
		$garments = 0;
		foreach($this->jobLines as $line){
			$garments += $line->QUANTITY;
		}
		return $garments;
	}
	
	/**
	 * Gets the total auto-generated cost (for 8/7 central) for each garment. This
	 * is in contrast to getGarmentPrice() which retrieves the cost <i>for the customer</i>.
	 */
	public function getGarmentCost(){
		$garments = 0;
		foreach($this->jobLines as $line){
			$garments += $line->QUANTITY * $line->product->COST;
		}
		return $garments;
	}
	
	/**
	 * Gets the score of the job. The score is essentially a time
	 * estimate, in minutes, of how long the job will take.
	 */
	public function getScore(){
		$base = 30; //Ben's request
		$passes = $this->printJob == null ? 0 : $this->printJob->PASS;
		$lines = 0; //for quantity of all lines
		foreach($this->jobLines as $line) {
			$lines += $line->QUANTITY;
		}
		return $base + $passes * $lines;
	}
	
	/**
	 * Gets a value indicating whether or not all orders associated with the 
	 * job have been checked in.
	 */
	public function getIsCheckedIn(){
		$checkedIn = count($this->jobLines) > 0;
		foreach($this->jobLines as $line){
			$checkedIn = $checkedIn && $line->isCheckedIn;
		}
		return $checkedIn;
	}
	
	/**
	 * Gets a list of orders associated with this job.
	 */
	public function getOrders(){
		$orders = array();
		foreach($this->jobLines as $jobLine){
			$order = $jobLine->ORDER_LINE->ORDER;
			$orders[(string) $order->ID] = $order;
		}
		return $orders;
	}
}