<?php

/**
 * This is the model class for table "product".
 *
 * The followings are the available columns in table 'product':
 * @property integer $ID
 * @property string $COST
 * @property integer $STATUS
 * @property integer $STYLE
 * @property integer $COLOR
 * @property integer $SIZE
 * @property integer $AVAILABLE
 *
 * The followings are the available model relations:
 * @property JobLine[] $jobLines
 * @property Lookup $cOLOR
 * @property Lookup $sIZE
 * @property Lookup $sTATUS
 * @property Lookup $sTYLE
 * @property ProductOrder[] $productOrders
 */
class Product extends CActiveRecord
{
	//product statuses
	const IN_STOCK = 16; //in stock
	const ORDERED = 17; //no inventory, ordered
	const BACKORDERED = 18; //backordered by supplier(s)
	const NO_STOCK = 19; //no inventory, not ordered
	const PLACEHOLDER = 32; //basically a temporary stock item, which,
							//if ordered, becomes a permanent stock item.
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Product the static model class
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
		return 'product';
	}
	
	public function beforeSave(){
		if(parent::beforeSave()){
			//change the product status based on the inventory amount.
			if($this->AVAILABLE > 0){
				$this->STATUS = Product::IN_STOCK;
			}
			return true;
		} else {
			return false;
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
			array('STATUS, STYLE, COLOR, SIZE, AVAILABLE', 'numerical', 'integerOnly'=>true),
			array('COST', 'numerical'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, COST, STATUS, STYLE, COLOR, SIZE, AVAILABLE', 'safe', 'on'=>'search'),
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
			'jobLines' => array(self::HAS_MANY, 'JobLine', 'PRODUCT_ID'),
			'color' => array(self::BELONGS_TO, 'Lookup', 'COLOR'),
			'size' => array(self::BELONGS_TO, 'Lookup', 'SIZE'),
			'status' => array(self::BELONGS_TO, 'Lookup', 'STATUS'),
			'style' => array(self::BELONGS_TO, 'Lookup', 'STYLE'),
			'orders' => array(self::HAS_MANY, 'ProductOrder', 'PRODUCT_ID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'COST' => 'Cost',
			'STATUS' => 'Status',
			'STYLE' => 'Style',
			'COLOR' => 'Color',
			'SIZE' => 'Size',
			'AVAILABLE' => 'Surplus Inventory',
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
		$criteria->compare('COST',$this->COST,true);
		$criteria->compare('STATUS',$this->STATUS);
		$criteria->compare('STYLE',$this->STYLE);
		$criteria->compare('COLOR',$this->COLOR);
		$criteria->compare('SIZE',$this->SIZE);
		$criteria->compare('AVAILABLE',$this->AVAILABLE);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Gets a string representing a summary of this product. Only style, color,
	 * and size are included.
	 */
	public function getSummary(){
		return $this->color->TEXT . ' ' . $this->style->TEXT . ', ' . $this->size->TEXT;
	}
}