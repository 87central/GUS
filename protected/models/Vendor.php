<?php

/**
 * This is the model class for table "vendor".
 *
 * The followings are the available columns in table 'vendor':
 * @property integer $ID
 * @property string $NAME
 * @property string $EMAIL
 * @property integer $PHONE
 *
 * The followings are the available model relations:
 * @property Order[] $orders
 */
class Vendor extends CActiveRecord
{
	private $_abbrev; //the name abbreviation
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Vendor the static model class
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
		return 'vendor';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('PHONE', 'numerical', 'integerOnly'=>true),
			array('NAME, EMAIL', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, NAME, EMAIL, PHONE', 'safe', 'on'=>'search'),
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
			'orders' => array(self::HAS_MANY, 'Order', 'VENDOR_ID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'NAME' => 'Name',
			'EMAIL' => 'Email',
			'PHONE' => 'Phone',
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
		$criteria->compare('NAME',$this->NAME,true);
		$criteria->compare('EMAIL',$this->EMAIL,true);
		$criteria->compare('PHONE',$this->PHONE);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Gets an abbreviated version of the vendor name.
	 */
	public function getNameAbbreviation(){
		if($this->_abbrev === null){
			$split = preg_split('/\s+/', $this->NAME);
			$abbrev = '';
			foreach($split as $word){
				$abbrev = $abbrev + strtoupper($word[0]);
			}
			$this->_abbrev = $abbrev;
		}
		return $this->_abbrev;
	}
}