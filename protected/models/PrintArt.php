<?php

/**
 * This is the model class for table "print_art".
 *
 * The followings are the available columns in table 'print_art':
 * @property integer $ID
 * @property integer $PRINT_ID
 * @property integer $USER_ID
 * @property integer $FILE_TYPE
 * @property string $FILE
 * @property string $DESCRIPTION
 * @property string $TIMESTAMP
 *
 * The followings are the available model relations:
 * @property Print $pRINT
 * @property User $uSER
 * @property Lookup $fILETYPE
 */
class PrintArt extends CActiveRecord
{
	const IMAGE = 106; //an image file, which can be interpreted directly as an image
	const DESIGN = 107; //a design file, which is of a more complex format than an image
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return PrintArt the static model class
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
		return 'print_art';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('FILE_TYPE, DESCRIPTION', 'required'),
			array('PRINT_ID, USER_ID, FILE_TYPE', 'numerical', 'integerOnly'=>true),
			array('FILE', 'safe'),
			array('DESCRIPTION', 'length', 'max'=>100),
			array('ID, TIMESTAMP', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, PRINT_ID, USER_ID, FILE_TYPE, FILE, DESCRIPTION, TIMESTAMP', 'safe', 'on'=>'search'),
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
			'PRINT_JOB' => array(self::BELONGS_TO, 'PrintJob', 'PRINT_ID'),
			'USER' => array(self::BELONGS_TO, 'User', 'USER_ID'),
			'TYPE' => array(self::BELONGS_TO, 'Lookup', 'FILE_TYPE'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'PRINT_ID' => 'Print',
			'USER_ID' => 'User',
			'FILE_TYPE' => 'File Type',
			'FILE' => 'File',
			'DESCRIPTION' => 'Description',
			'TIMESTAMP' => 'Timestamp',
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
		$criteria->compare('PRINT_ID',$this->PRINT_ID);
		$criteria->compare('USER_ID',$this->USER_ID);
		$criteria->compare('FILE_TYPE',$this->FILE_TYPE);
		$criteria->compare('FILE',$this->FILE,true);
		$criteria->compare('DESCRIPTION',$this->DESCRIPTION,true);
		$criteria->compare('TIMESTAMP',$this->TIMESTAMP,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Does the necessary file manipulation to ensure that the $_FILES
	 * value given by $rawFile is property stored in the file system.
	 * @param object $rawFile The file to be saved.
	 * @param string $attribute The attribute in which to place the final file path.
	 * 
	 * Requires that a print job parent already exists.
	 */
	protected function createFile($rawFile){
		if($rawFile){
			$fileDir = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'PrintJob'.$this->PRINT_JOB->ID;
			$destination = $fileDir.DIRECTORY_SEPARATOR.$rawFile['name'];
			if(!file_exists($fileDir)){
				mkdir($fileDir, 0777, true);
			}
			
			if(move_uploaded_file($rawFile['tmp_name'], $destination)){
				$this->FILE = $destination;
			} else {
				$this->FILE = null;
			}		
		}
	}
	
	protected function beforeSave(){
		if(parent::beforeSave()){
			if($this->FILE && !is_string($this->FILE)){
				$this->createFile($this->FILE);
			}
			return true;
		} else {
			return false;
		}
	}
	
	protected function beforeDelete(){
		if(parent::beforeDelete()){
			return isset($this->FILE) ? unlink($this->FILE) : true;
		} else {
			return false;
		}
	}
}