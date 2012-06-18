<?php

class InvoiceController extends Controller
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
				'actions'=>array(),
				'users'=>array('*'),
			),
			array('allow',
				'actions'=>array('view', 'create', 'update', 'deleteLine', 'delete', 'index', 'loadList'),
				'users'=>array('@'),
				'expression'=>"Yii::app()->user->getState('isDefaultRole');",
			),
			array('allow',
				'actions'=>array('view', 'create', 'update', 'deleteLine', 'delete', 'index', 'loadList'),
				'users'=>array('@'),
				'expression'=>"Yii::app()->user->getState('isLead');",
			),
			array('allow',
				'actions'=>array('view'),
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
	public function actionView($id, $type='view')
	{
		$model = $this->loadModel($id);
		$formatter = new Formatter;
		$view = 'view';
		$dataProvider = new CActiveDataProvider('InvoiceLine', array(
			'criteria'=>array(
				'condition'=>'INVOICE_ID = ' . $id,
			),
			'pagination'=>false,
		));
		
		$params = array('model'=>$model, 'formatter'=>$formatter, 'dataProvider'=>$dataProvider);
		if($type == "iif"){
			$view = 'view_quickbooks';
			$name = $model->ID . '_quick_invoice.iif';
			$mime = 'text/iif';
			$model->attachBehavior('quickbooks', 'application.behaviors.Invoice.QBInitializer');						
			Yii::app()->request->sendFile($name, $this->renderPartial($view, $params, true), $mime);	
		} else {
			$this->render($view, $params);
		}
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Invoice;
		$customer = new Customer;
		$itemTypeList = CHtml::listData(Lookup::listItems('InvoiceItemType'), 'ID', 'TEXT');

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Invoice']))
		{
			$model->loadFromArray($_POST['Invoice']);
			$model->DATE = DateConverter::toDatabaseTime(strtotime($model->DATE));
			$customerWasNew = true;
			if(isset($_POST['Customer']['ID']) && $_POST['Customer']['ID'] != null){
				$customer = Customer::model()->findByPk((int) $_POST['Customer']['ID']);
				$customerWasNew = false;
			} else {
				unset($_POST['Customer']['ID']);
			}
			unset($_POST['Customer']['summary']);
			$customer->attributes = $_POST['Customer'];
			if($customer->save()){
				$model->CUSTOMER_ID = $customer->ID;
			}
			if($model->save()){				
				$this->redirect(array('view','id'=>$model->ID));
			}			
		}

		$this->render('create',array(
			'model'=>$model,
			'newCustomer'=>$customer,
			'customerList'=>Customer::model()->findAll(),
			'itemTypeList'=>$itemTypeList,
			'formatter'=>new Formatter,
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
		$customer = $model->CUSTOMER;
		$itemTypeList = CHtml::listData(Lookup::listItems('InvoiceItemType'), 'ID', 'TEXT');

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Invoice']))
		{			
			$model->loadFromArray($_POST['Invoice']);
			$model->DATE = DateConverter::toDatabaseTime(strtotime($model->DATE));
			$customerWasNew = true;
			if(isset($_POST['Customer']['ID']) && $_POST['Customer']['ID'] != null){
				$customer = Customer::model()->findByPk((int) $_POST['Customer']['ID']);
				$customerWasNew = false;
			} else {
				unset($_POST['Customer']['ID']);
			}
			unset($_POST['Customer']['summary']);
			$customer->attributes = $_POST['Customer'];
			if($customer->save()){
				$model->CUSTOMER_ID = $customer->ID;
			}
			if($model->save()){				
				$this->redirect(array('view','id'=>$model->ID));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'newCustomer'=>$customer,
			'customerList'=>Customer::model()->findAll(),
			'itemTypeList'=>$itemTypeList,
			'formatter'=>new Formatter,
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
	
	public function actionDeleteLine(){
		$model = InvoiceLine::model()->findByPk((int) $_POST['id']);
		if($model){
			if(!$model->delete()){
				throw new CException('Could not delete the invoice line.');
			}
		}
	}
	
	/**
	 * Loads the contents of a job listing tab.
	 * @param string $list The type of list to load. Valid values are "current", "canceled", and "completed"
	 */
	public function actionLoadList($list){
		switch($list){
			case 'current' : $filter = array(Invoice::CREATED, Invoice::SENT); break;
			case 'canceled' : $filter = Invoice::CANCELLED; break;
			case 'completed' : $filter = Invoice::COMPLETED; break;
			default : $filter = null; break;
		}
		$invoices = Invoice::listInvoicesByStatus($filter);
		$dataProvider = new CArrayDataProvider($invoices, array(
			'keyField'=>'ID',
			'pagination'=>false,
		));
		
		$this->renderPartial('_listSection', array(
			'dataProvider'=>$dataProvider,
			'statuses'=> CHtml::listData(Lookup::listItems('InvoiceStatus'), 'ID', 'TEXT'),
			'formatter'=>new Formatter,
		));
	}
	
	public function actionStatus($id){
		$model = $this->loadModel($id);
		$model->STATUS_ID = $_POST['status'];
		$model->save();
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$currentInvoices = Invoice::listInvoicesByStatus(array(Invoice::CREATED, Invoice::SENT));
		$currentDataProvider = new CArrayDataProvider($currentInvoices, array(
			'keyField'=>'ID',
			'pagination'=>false,
		));
		
		$statuses = CHtml::listData(Lookup::listItems('InvoiceStatus'), 'ID', 'TEXT');
		
		$this->render('index', array(
			'currentDataProvider'=>$currentDataProvider,
			'statuses'=>$statuses,
			'formatter'=>new Formatter,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Invoice::model()->findByPk((int)$id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='invoice-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
