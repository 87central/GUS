<?php

class OrderController extends Controller
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
				'actions'=>array('create', 'update', 'view', 'index', 'newLine', 'deleteLine'),
				'users'=>array('@'),
				'expression'=>"Yii::app()->user->getState('isDefaultRole');",
			),
			array('allow',
				'actions'=>array('checkin', 'place', 'create', 'update', 'view', 'index', 'newLine', 'deleteLine'),
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
	public function actionCreate($vendor=null)
	{
		if($vendor === null){
			$this->redirect(array('order/create', 'vendor'=>Vendor::model()->find()->ID));
		}
		$model=new Order;
		$vendors = Vendor::model()->findAll();
		$products = Product::model()->findAllByAttributes(array('VENDOR_ID'=>$vendor));
		$neededProducts = array();		
		$jobLines = JobLine::model()->findAllByAttributes(array('PRODUCT_ORDER_ID'=>null));
		foreach($jobLines as $line){
			$id = $line->product->ID;
			if($line->product->VENDOR_ID == $vendor){
				$neededProducts[$id]['ID'] = $id;
				$neededProducts[$id]['PRODUCT'] = $line->product;
				$neededProducts[$id]['LINES'][] = $line->ID;
			}
		}
		
		$newNeededProducts = array();
		foreach($neededProducts as $key=>$needed){
			$newNeededProducts[$key]['ID'] = $needed['ID'];
			$newNeededProducts[$key]['PRODUCT'] = $needed['PRODUCT'];
			$newNeededProducts[$key]['LINES'] = implode(',', $needed['LINES']);
		}
		
		$neededProducts = $newNeededProducts;
		
		$neededProductsProvider = new CArrayDataProvider($neededProducts, array(
			'keyField'=>'ID',
		));

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Order']))
		{
			$model->loadFromArray($_POST['Order']);
			if($model->save())
				$this->redirect(array('view','id'=>$model->ID));
		}

		$this->render('create',array(
			'model'=>$model,
			'vendors'=>$vendors,
			'products'=>$products,
			'neededProductsProvider'=>$neededProductsProvider,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id, $vendor=null)
	{
		if($vendor === null){
			$this->redirect(array('order/update', 'id'=>$vendor, 'vendor'=>Vendor::model()->find()->ID));
		}
		
		$model=$this->loadModel($id);
		$vendors = Vendor::model()->findAll();
		$products = Product::model()->findAllByAttributes(array('VENDOR_ID'=>$vendor));
		$neededProducts = array();		
		$jobLines = JobLine::model()->findAllByAttributes(array('PRODUCT_ORDER_ID'=>null));
		foreach($jobLines as $line){
			$id = $line->product->ID;
			if($line->product->VENDOR_ID == $vendor){
				$neededProducts[$id]['ID'] = $id;
				$neededProducts[$id]['PRODUCT'] = $line->product;
				$neededProducts[$id]['LINES'][] = $line->ID;
			}
		}
		
		$newNeededProducts = array();
		foreach($neededProducts as $key=>$needed){
			$newNeededProducts[$key]['ID'] = $needed['ID'];
			$newNeededProducts[$key]['PRODUCT'] = $needed['PRODUCT'];
			$newNeededProducts[$key]['LINES'] = implode(',', $needed['LINES']);
		}
		
		$neededProducts = $newNeededProducts;
		
		$neededProductsProvider = new CArrayDataProvider($neededProducts, array(
			'keyField'=>'ID',
		));

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Order']))
		{
			$model->loadFromArray($_POST['Order']);
			if($model->save())
				$this->redirect(array('view','id'=>$model->ID));
		}

		$this->render('update',array(
			'model'=>$model,
			'vendors'=>$vendors,
			'products'=>$products,
			'neededProductsProvider'=>$neededProductsProvider,
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
	
	public function actionIndex(){
		$createdProvider = new CActiveDataProvider('Order', array(
			'criteria'=>array('condition'=>'STATUS = '. Order::CREATED),
			'pagination'=>false,
		));
		$orderedProvider = new CActiveDataProvider('Order', array(
			'criteria'=>array('condition'=>'STATUS = '. Order::ORDERED),
			'pagination'=>false,
		));
		$arrivedRecords = Order::model()->findAll(array(
			'condition'=>'STATUS = '. Order::ARRIVED,
		));
		$minDate = strtotime('-1 week');
		$records = array();
		foreach($arrivedRecords as $order){
			 $orderArrived = strtotime($order->arrived);
			 if($orderArrived > $minDate){
			 	$records[] = $order;
			 }
		}
		$arrivedProvider = new CArrayDataProvider($records, array(
			'pagination'=>false,
			'keyField'=>'ID',
		));
		
		$this->render('index', array(
			'createdProvider'=>$createdProvider,
			'orderedProvider'=>$orderedProvider,
			'arrivedProvider'=>$arrivedProvider, 
		));
	}
	
	public function actionCheckin($id, $view){
		$model = $this->loadModel($id);
		try {
			$model->checkin();
			Yii::app()->user->setFlash('success', 'The order has been checked in successfuly!');
		} catch(Exception $e){
			Yii::app()->user->setFlash('failure', $e->message);
		}
		$this->redirect(array('order/'.$view, 'id'=>$id));
	}
	
	public function actionPlace($id, $view){
		$model = $this->loadModel($id);
		if($model->canPlace) {
			$model->place();
			Yii::app()->user->setFlash('success', 'The order was placed successfully!');
			$this->refresh();
		} else {
			$this->redirect(array('order/update', 'id'=>$id));
		}		
	}
	
	public function actionNewLine($id = null){
		$namePrefix = $_POST['namePrefix'];
		$count = $_POST['count'];
		$status = $_POST['status'];
		$lines = $_POST['lines'];
		$products = Product::model()->findAll();
		$products = CHtml::listData($products, 'ID', 'summary');
		$model = new ProductOrder;
		if($id){
			$product = Product::model()->findByPk((int) $id);
			$model->QUANTITY_ORDERED = $product->AVAILABLE * -1;
			$model->PRODUCT_ID = $id;	
		}
		
		$this->renderPartial('//productOrder/_orderForm', array(
			'products'=>$products,
			'namePrefix'=>$namePrefix . '[' . $count . ']',
			'model'=>$model,
			'orderStatus'=>$status,
			'lines'=>$lines,
		));
	}
	
	public function actionDeleteLine(){
		$model = ProductOrder::model()->findByPk((int) $_POST['id']);
		if($model){
			if(!$model->delete()){
				throw new CException('Could not delete the job line.');
			}
		}
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Order('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Order']))
			$model->attributes=$_GET['Order'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Order::model()->findByPk((int)$id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='order-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
