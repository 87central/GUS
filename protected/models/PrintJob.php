<?php

/**
 * This is the model class for table "print".
 *
 * The followings are the available columns in table 'print':
 * @property integer $ID
 * @property integer $PASS
 * @property string $ART
 * @property string $COST
 * @property string $APPROVAL_DATE
 * @property integer $APPROVAL_USER
 *
 * The followings are the available model relations:
 * @property Job[] $jobs
 * @property User $aPPROVALUSER
 */
class PrintJob extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return PrintJob the static model class
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
		return 'print';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('FRONT_PASS, BACK_PASS, SLEEVE_PASS, APPROVAL_USER', 'numerical', 'integerOnly'=>true),
			array('ART', 'length', 'max'=>200),
			array('APPROVAL_DATE', 'safe'),
			array('COST', 'numerical'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, PASS, ART, COST, APPROVAL_DATE, APPROVAL_USER', 'safe', 'on'=>'search'),
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
			'jobs' => array(self::HAS_MANY, 'Job', 'PRINT_ID'),
			'approver' => array(self::BELONGS_TO, 'User', 'APPROVAL_USER'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'FRONT_PASS' => 'Number of passes on front',
			'BACK_PASS'=>'Number of passes on back',
			'SLEEVE_PASS'=>'Number of passes on sleeve',
			'ART' => 'Art',
			'COST' => 'Cost',
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
		$criteria->compare('PASS',$this->PASS);
		$criteria->compare('ART',$this->ART,true);
		$criteria->compare('COST',$this->COST,true);
		$criteria->compare('APPROVAL_DATE',$this->APPROVAL_DATE,true);
		$criteria->compare('APPROVAL_USER',$this->APPROVAL_USER);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Does the necessary file manipulation to ensure that the $_FILES
	 * value given by $rawFile is properly stored in the file system.
	 */
	public function createArtFile($rawFile){
		if($rawFile){
			$fileDir = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.get_class($this);
			$destination = $fileDir.DIRECTORY_SEPARATOR.$rawFile['name'];
			
			if(move_uploaded_file($rawFile['tmp_name'], $destination)){
				$this->ART = $destination;
			}			
		}
	}
	
	/**
	 * Gets the total number of passes for this print overall.
	 */
	public function getPass(){
		return $this->FRONT_PASS + $this->BACK_PASS + $this->SLEEVE_PASS;
	}
}