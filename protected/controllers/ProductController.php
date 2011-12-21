<?php

class ProductController extends Controller
{

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('view', 'update', 'index', 'findProduct', 'allowedOptions'),
				'users'=>array('@'),
				'expression'=>"Yii::app()->user->getState('isDefaultRole');",
			),
			array('allow',
				'actions'=>array(),
				'users'=>array('@'),
				'expression'=>"Yii::app()->user->getState('isLead');",
			),
			array('allow',
				'actions'=>array(),
				'users'=>array('@'),
				'expression'=>"Yii::app()->user->getState('isCustomer');",
			),
			array('allow',
				'users'=>array('@'),
				'expression'=>"Yii::app()->user->getState('isAdmin');",
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Product;
		$statusList = Lookup::listValues('ProductStatus');
		$colorList = Lookup::listValues('Color');
		$styleList = Lookup::listValues('Style');
		$sizeList = Lookup::listValues('Size');
		$vendorList = Vendor::model()->findAll();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Product']))
		{
			$model->attributes=$_POST['Product'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->ID));
		}

		$this->render('create',array(
			'model'=>$model,
			'statusList'=>$statusList,
			'colorList'=>$colorList,
			'styleList'=>$styleList,
			'sizeList'=>$sizeList,
			'vendorList'=>$vendorList,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$statusList = Lookup::listValues('ProductStatus');
		$colorList = Lookup::listValues('Color');
		$styleList = Lookup::listValues('Style');
		$sizeList = Lookup::listValues('Size');
		$vendorList = Vendor::model()->findAll();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Product']))
		{
			$model->attributes=$_POST['Product'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->ID));
		}

		$this->render('update',array(
			'model'=>$model,
			'statusList'=>$statusList,
			'colorList'=>$colorList,
			'styleList'=>$styleList,
			'sizeList'=>$sizeList,
			'vendorList'=>$vendorList,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Product', array(
			'pagination'=>false,
			'sort'=>array(
				'defaultOrder'=>'STYLE, SIZE, COLOR',
			),
		));
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Product('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Product']))
			$model->attributes=$_GET['Product'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	public function actionFindProduct($response='render'){
		$term = $_GET['term'];
		$model = new Product('search');
		$model->unsetAttributes();
		$model->VENDOR_ITEM_ID = $term;
		$results = $model->search();
		$prefiltered = array();
		//we first want to filter out any duplicate records
		foreach($results->data as $result){
			$prefiltered[(string) $result->VENDOR_ITEM_ID] = $result;
		}
		$results = new CArrayDataProvider($prefiltered, array(
			'keyField'=>'ID',
		));
		//then we can continue with what we were doing
		$juiResults = array();
		foreach($results->data as $result){
			$juiResults[] = array(
				'label'=>$result->vendorStyle,
				'value'=>$result->vendorStyle,
				'id'=>$result->VENDOR_ITEM_ID,
			);
		}
		switch($response){
			case 'json' : header('Content-Type: text/json'); echo CJSON::encode($results); break;
			case 'juijson' : header('Content-Type: text/json'); echo CJSON::encode($juiResults); break;
			default : $this->render('index', array('dataProvider'=>$results)); break;
		}
	}
	
	public function actionAllowedOptions($itemID, $namePrefix, $count){
		//ajax only - return json of allowed colors and sizes
		$results = array(
			'colors'=>Product::getAllowedColors($itemID),
			'sizes'=>Product::getAllowedSizes($itemID),
			'style'=>Product::getStyle($itemID),
			'colors-name'=>CHtml::getIdByName($namePrefix . "[$count]" . 'colors'),
			'productCost'=>Product::getCost($itemID),
		);
		echo CJSON::encode($results);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Product::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='product-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
