<?php

/**
 * This is the model class for table "job_line_size".
 *
 * The followings are the available columns in table 'job_line_size':
 * @property integer $JOB_LINE_ID
 * @property integer $SIZE
 * @property integer $QUANTITY
 */
class JobLineSize extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return JobLineSize the static model class
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
		return 'job_line_size';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('JOB_LINE_ID, SIZE', 'required'),
			array('JOB_LINE_ID, SIZE, QUANTITY', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('JOB_LINE_ID, SIZE, QUANTITY', 'safe', 'on'=>'search'),
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
			'line' => array(self::BELONGS_TO, 'JobLine', 'JOB_LINE_ID'),
			'size'=>array(self::BELONGS_TO, 'Lookup', 'SIZE'),
			//'productLine'=>array(self::HAS_ONE, 'ProductLine', 'condition'=>'productLine.PRODUCT_ID = line.PRODUCT_ID AND productLine.COLOR = line.PRODUCT_COLOR AND productLine.SIZE = t.SIZE'),
		);
	}
	
	/**
	 * Gets the job associated with this job line size.
	 */
	public function getJob(){
		return $this->line->job;
	}
	
	/**
	 * Gets the product line associated with this job line size.
	 */
	public function getProductLine(){
		$jobLine = $this->line;
		if($jobLine){
			$productLine = ProductLine::model()->findByPk(array('PRODUCT_ID'=>$jobLine->PRODUCT_ID, 'COLOR'=>$jobLine->PRODUCT_COLOR, 'SIZE'=>$this->SIZE));
		} else {
			$productLine = null;
		}
		return $productLine;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'JOB_LINE_ID' => 'Job Line',
			'SIZE' => 'Size',
			'QUANTITY' => 'Quantity',
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

		$criteria->compare('JOB_LINE_ID',$this->JOB_LINE_ID);
		$criteria->compare('SIZE',$this->SIZE);
		$criteria->compare('QUANTITY',$this->QUANTITY);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Gets a value indicating whether or not this line should be charged as "extra large".
	 * @return False if this line should not be charged, otherwise the per-garment fee.
	 */
	public function getIsExtraLarge(){
		$xlSizes = array(39, 40, 73, 74, 80);
		return (array_search($this->SIZE, $xlSizes) !== false) ? Product::EXTRA_LARGE_FEE : false;
	}
	
	/**
	 * Gets the total <i>unit cost</i> of the line, including the extra large fee.
	 */
	public function getUnitCost(){
		$xl = $this->isExtraLarge;
		$productLine = $this->productLine;
		if($productLine){
			$total = $this->productLine->product->COST;
		} else {
			$total = 0;
		}
		if($xl !== false){
			$total += $xl;
		}
		return $total;
	}
	
	/**
	 * Gets the total cost of all units in the line.
	 */
	public function getTotal(){
		return $this->unitCost * $this->QUANTITY;
	}
}