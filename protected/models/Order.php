<?php

/**
 * This is the model class for table "order".
 *
 * The followings are the available columns in table 'order':
 * @property integer $ID
 * @property string $EXTERNAL_ID
 * @property integer $VENDOR_ID
 * @property string $DATE
 *
 * The followings are the available model relations:
 * @property Vendor $vENDOR
 * @property ProductOrder[] $productOrders
 */
class Order extends CActiveRecord
{
	//order statuses
	const CREATED = 20; //order has been created, but not yet submitted to a vendor
	const ORDERED = 21; //order has been submitted to a vendor (and paid)
	const ARRIVED = 22; //order has been checked in
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Order the static model class
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
		return 'order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('VENDOR_ID', 'numerical', 'integerOnly'=>true),
			array('EXTERNAL_ID', 'length', 'max'=>60),
			array('DATE', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, EXTERNAL_ID, VENDOR_ID, DATE', 'safe', 'on'=>'search'),
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
			'VENDOR' => array(self::BELONGS_TO, 'Vendor', 'VENDOR_ID'),
			'lines' => array(self::HAS_MANY, 'ProductOrder', 'ORDER_ID'),
			'events'=> array(self::HAS_MANY, 'EventLog', 'OBJECT_ID', 'condition'=>'OBJECT_TYPE = \'Order\'', 'index'=>'EVENT_ID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'EXTERNAL_ID' => 'External',
			'VENDOR_ID' => 'Vendor',
			'DATE' => 'Date',
			'STATUS'=>'Status',
		);
	}
	
	/**
	 * Gets an array mapping attribute names to event IDs.
	 * @return array The resultant array.
	 */
	protected function eventAttributes(){
		return array(
			'created'=>EventLog::ORDER_CREATED,
			'ordered'=>EventLog::ORDER_PLACED,
			'arrived'=>EventLog::ORDER_ARRIVED,
			'placed'=>EventLog::ORDER_PLACED,
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
		$criteria->compare('EXTERNAL_ID',$this->EXTERNAL_ID,true);
		$criteria->compare('VENDOR_ID',$this->VENDOR_ID);
		$criteria->compare('DATE',$this->DATE,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public function getName(){
		if($this->VENDOR == null || $this->EXTERNAL_ID == null){
			return 'Order #'.$this->ID;
		} else {
			return $this->VENDOR->nameAbbreviation . ' - ' . $this->EXTERNAL_ID;	
		}		
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
		if(isset($attributesInternal['lines'])){
			$lines = $attributesInternal['lines'];
			unset($attributesInternal['lines']);
		} else {
			$lines = null;
		}
		foreach($attributesInternal as $name=>$value){
			$this->$name = $value;
		}
		if($lines){
			$keyedLines = array();
			foreach($this->lines as $line){
				$keyedLines[(string) $line->ID] = $line;
			}
			$newLines = array();
			for($i = 0; $i < count($lines); $i++){
				$lineID = $lines[$i]['ID'];
				if(isset($keyedLines[$lineID])){
					$line = $keyedLines[$lineID];
				} else {
					$line = new ProductOrder;
				}
				$line->attributes = $lines[$i];
				$newLines[] = $line;
			}
			$this->lines = $newLines;
		}		
	}
	
	protected function beforeValidate(){
		if(parent::beforeValidate()){
			if($this->isNewRecord){
				$this->STATUS = Order::CREATED;
				$this->created = DateConverter::toDatabaseTime(time(), true);
			}
			$valid = true;
			foreach($this->lines as $line){
				$line->ORDER_ID = $this->ID;
				$valid = $valid && $line->validate();
			}
			return $valid;
		} else {
			return false;
		}
	}
	
	protected function afterSave(){
		parent::afterSave();
		//ensures that there is an event created for each event attribute.			
		if(!isset($this->events) || count($this->events) < count($this->eventAttributes())){
			foreach($this->eventAttributes() as $eventID){
				$this->getEventModel($eventID)->save();
			}
		}
		if(isset($this->lines)){
			foreach($this->lines as $line){
				$line->ORDER_ID = $this->ID;
				$line->save();
			}
		}
		
	}
	
	/**
	 * Gets a value indicating whether or not this order can be placed.
	 * @return boolean True if the order can be placed, otherwise false.
	 */
	public function getCanPlace(){
		return $this->STATUS == Order::CREATED && $this->VENDOR_ID != null;
	}
	
	/**
	 * Gets a value indicating whether or not this order can be checked in.
	 * @return boolean True if the order can be checked in, otherwise false.
	 */
	public function getCanCheckin(){
		return $this->STATUS == Order::ORDERED;
	}
	
	/**
	 * Places the order.
	 */
	public function place(){
		if($this->canPlace){
			$this->STATUS = Order::ORDERED;
			$this->ordered = DateConverter::toUserTime(time());
			foreach($this->lines as $line){
				$product = $line->PRODUCT;
				$product->STATUS = Product::ORDERED;
				$product->save();
			}
			$this->save();
		} else {
			throw new CException('Could not place the order at this time.');
		}
	}
	
	/**
	 * Checks in the order, updating inventory where necessary.
	 */
	public function checkin(){
		if($this->canCheckin){
			$this->STATUS = Order::ARRIVED;
			$this->arrived = DateConverter::toUserTime(time());
			$this->save();
		} else {
			throw new CException('Could not check in the order at this time.');
		}
	}
}