<?php

class LookupController extends Controller
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
				'users'=>array('@'),
				'expression'=>"Yii::app()->user->getState('isDefaultRole');",
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

	public function actionIndex(){
		$data = array();
		foreach($_GET as $type=>$value){
			if($type != 'r'){
				$data[$type] = Lookup::listItems($type);
			}
		}
		$this->render('index', array(
			'data'=>$data,
		));
	}
	
	public function actionAdd($type){
		$text = $_POST['text'];
		if($text){
			$model = new Lookup;
			$model->TEXT = $text;
			$model->TYPE = $type;
			$model->save();
		}
		$this->renderPartial('_list', array(
			'type'=>$type,
			'items'=>Lookup::listItems($type),
		));
	}
	
	public function actionRemove(){
		$id = $_POST['id'];
		$model = Lookup::model()->findByPk((int) $id);
		$throw = true;
		if($model){
			$model->DELETED = true;
			if($model->save()){
				$throw = false;
			}
		}
		if($throw){
			throw new CException('No model was deleted');
		}
	}
}
