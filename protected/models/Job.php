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
	private $_dueEvent;
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
	
	protected function getDueDateModel(){
		if($this->_dueEvent === null){
			$event = EventLog::model()->findByAttributes(array(
				'OBJECT_TYPE'=>'Job',
				'OBJECT_ID'=>$this->ID,
				'EVENT_ID'=>EventLog::JOB_DUE,
			));
			
			if($event === null){
				$event = new EventLog;
				$event->assocObject = $this;
				$event->EVENT_ID = EventLog::JOB_DUE; 
			}
			$this->_dueEvent = $event;
		}
		return $this->_dueEvent;
	}
	
	public function getDueDate(){
		$event = $this->dueDateModel;
		return $event->DATE;
	}
	
	public function setDueDate($value){
		$this->dueDateModel->DATE = $value;
	}
	
	public function getFormattedDueDate(){
		if($this->dueDate == null){
			return null;
		} else {
			$formatter = new CFormatter;
			return $formatter->formatDate($this->dueDate);
		}
	}
	
	public function setFormattedDueDate($value){
		$this->dueDate = strtotime($value);
	}
	
	protected function afterSave(){
		parent::afterSave();
		if($this->_dueEvent !== null){
			$this->_dueEvent->OBJECT_ID = $this->ID;
			$this->_dueEvent->save();
		}
	}
}