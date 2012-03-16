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
			array('ART, MOCK_UP', 'length', 'max'=>200),
			array('APPROVAL_DATE, files', 'safe'),
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
			'files'=>array(self::HAS_MANY, 'PrintArt', 'PRINT_ID'),
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
			'MOCK_UP'=> 'Mock Up',
			'COST' => 'Art Charge',
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
		$criteria->compare('MOCK_UP', $this->MOCK_UP, true);
		$criteria->compare('COST',$this->COST,true);
		$criteria->compare('APPROVAL_DATE',$this->APPROVAL_DATE,true);
		$criteria->compare('APPROVAL_USER',$this->APPROVAL_USER);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Does the necessary file manipulation to ensure that the $_FILES
	 * value given by $rawFile is property stored in the file system.
	 * @param object $rawFile The file to be saved.
	 * @param string $attribute The attribute in which to place the final file path.
	 */
	protected function createAttributeFile($rawFile, $attribute){
		if($rawFile){
			$fileDir = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.get_class($this).DIRECTORY_SEPARATOR.$attribute;
			$destination = $fileDir.DIRECTORY_SEPARATOR.$rawFile['name'];
			if(!file_exists($fileDir)){
				mkdir($fileDir, 0777, true);
			}
			if(move_uploaded_file($rawFile['tmp_name'], $destination)){
				$this->$attribute = $destination;
			}			
		}
	}
	
	/**
	 * Does the necessary file manipulation to ensure that the $_FILES
	 * value given by $rawFile is properly stored in the file system.
	 */
	public function createArtFile($rawFile){
		$this->createAttributeFile($rawFile, 'ART');
	}
	
	/**
	 * Does the necessary file manipulation to ensure that the $_FILES
	 * value given by $rawFile is properly stored in the file system.
	 */
	public function createMockUpFile($rawFile){
		$this->createAttributeFile($rawFile, 'MOCK_UP');
	}
	
	/** Fills this model's attributes and relations from an array of attributes.
	 * @param array $attributes The attribute array. This may contain values for
	 * all of the attributes as well as the "files" relation, which should
	 * be the key to an array with sets of attributes of PrintArt models.
	 * @param array $files The file array.
	 */
	public function loadFromArray($attributes, $files){
		$attributesInternal = $attributes;
		if(isset($attributesInternal['files'])){
			$artFiles = $attributesInternal['files'];
			unset($attributesInternal['files']);
		} else {
			$artFiles = null;
		}
		foreach($attributesInternal as $name=>$value){
			$this->$name = $value;
		}
		if($artFiles){
			$keyedArtFiles = array();
			foreach($this->files as $line){
				$keyedArtFiles[(string) $line->ID] = $line;
			}
			$newArtFiles = array();
			for($i = 0; $i < count($artFiles); $i++){
				if(isset($artFiles[$i]) && is_array($artFiles[$i])){
					$lineID = (string) $artFiles[$i]['ID'];
					if(isset($keyedArtFiles[$lineID])){
						$line = $keyedArtFiles[$lineID];
					} else {
						$line = new PrintArt;
					}
					$originalFile = $line['FILE'];
					$line->attributes = $artFiles[$i];
					if($files['PrintJob']){
						$keys = array_keys($files['PrintJob']);
						$file = array();
						foreach($keys as $key){
							$file[$key] = $files['PrintJob'][$key]['files'][$i]['FILE'];
						}
						if(!$file['error']){
							$line->FILE = $file;
						}
					}
					if($originalFile && !$line->FILE){
						$line->FILE = $originalFile;
					}					
					$newArtFiles[] = $line;
				}
			}
			$this->files = $newArtFiles;
		}	
	}
	
	protected function beforeValidate(){
		if(parent::beforeValidate()){
			$valid = true;
			foreach($this->files as $line){
				$line->PRINT_ID = $this->ID;
				$valid = $valid && $line->validate();
			}
			return $valid;
		} else {
			return false;
		}
	}
	
	protected function afterSave(){
		parent::afterSave();
		if(isset($this->files)){
			foreach($this->files as $line){
				$line->PRINT_ID = $this->ID;
				$line->save();
			}
		}		
	}

	/**
	 * Gets the total number of passes for this print overall.
	 */
	public function getPass(){
		return $this->FRONT_PASS + $this->BACK_PASS + $this->SLEEVE_PASS;
	}
	
	/**
	 * Gets a value indicating whether or not there are design files associated
	 * with the print.
	 * @return boolean True if there is art associated, otherwise false.
	 */
	public function getHasArt(){
		$hasArt = PrintArt::model()->findByAttributes(array('PRINT_ID'=>$this->ID, 'FILE_TYPE'=>PrintArt::DESIGN));
		if($hasArt){
			return true;
		} else {
			return false;
		}
	}
}