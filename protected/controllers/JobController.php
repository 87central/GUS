<?php

class JobController extends Controller
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
				'actions'=>array('create', 'update', 'deleteLine', 'approveLine', 'newLine', 'view', 'list', 'index'),
				'users'=>array('@'),
				'expression'=>"Yii::app()->user->getState('isDefaultRole');",
			),
			array('allow',
				'actions'=>array('create', 'update', 'deleteLine', 'approveLine', 'newLine', 'view', 'list', 'index'),
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
	
	public function actionNewLine(){
		$namePrefix = $_POST['namePrefix'];
		$count = $_POST['count'];
		
		$styleList = $this->loadList('Style');
		$sizeList = $this->loadList('Size');
		$colorList = $this->loadList('Color');
		$this->renderPartial('//jobLine/_form', array(
			'styles'=>$styleList,
			'sizes'=>$sizeList,
			'colors'=>$colorList,
			'namePrefix'=>$namePrefix . '[' . $count . ']',
			'model'=>new JobLine,
		));
	}
	
	public function actionApproveLine(){
		$model = JobLine::model()->findByPk((int) $_POST['id']);
		$namePrefix = $_POST['namePrefix'];
		if($model){
			if($model->approve()){
				$view = '//jobLine/_view';
				$vars = array('namePrefix'=>$namePrefix, 'model'=>$model, 'formatter'=>new Formatter);
				if(Yii::app()->user->getState('isAdmin') == true){
					$view = '//jobLine/_form';
					$vars['styles'] = $this->loadList('Style');
					$vars['colors'] = $this->loadList('Color');
					$vars['sizes'] = $this->loadList('Size');
				}
				$this->renderPartial($view, $vars);	
			} else {
				throw new CException('Could not approve the job line.');
			}		
		} else {
			throw new CException('Could not approve the job line.');
		}
	}
	
	public function actionUnapproveLine(){
		$model = JobLine::model()->findByPk((int) $_POST['id']);
		$namePrefix = $_POST['namePrefix'];
		if($model){
			if($model->unapprove()){
				$view = '//jobLine/_form';
				$vars = array(
					'namePrefix'=>$namePrefix, 
					'model'=>$model,
					'styles'=>$this->loadList('Style'),
					'sizes'=>$this->loadList('Size'),
					'colors'=>$this->loadList('Color'),
				);
				$this->renderPartial($view, $vars);	
			} else {
				throw new CException('Could not unapprove the job line.');
			}
		} else {
			throw new CException('Could not unapprove the job line.');
		}
	}
	
	public function actionDeleteLine(){
		$model = JobLine::model()->findByPk((int) $_POST['id']);
		if($model){
			if(!$model->delete()){
				throw new CException('Could not delete the job line.');
			}
		}
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Job;
		$customer = new Customer;
		$existingCustomers = Customer::model()->findAll();
		$leaders = User::listUsersWithRole(User::LEAD_ROLE);
		$printers = User::listUsersWithRole(User::DEFAULT_ROLE);
		$styles = Lookup::model()->findAllByAttributes(array('TYPE'=>'Style'));
		$sizes = Lookup::model()->findAllByAttributes(array('TYPE'=>'Size'));
		$colors = Lookup::model()->findAllByAttributes(array('TYPE'=>'Color'));
		$passes = array(0, 1, 2, 3, 4, 5, 6); //as instructed by Ben, number of passes
		//should be limited to a few numbers.
		$print = new PrintJob;
		

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Job']))
		{
			$model->loadFromArray($_POST['Job']);
			$customer->attributes = $_POST['Customer'];
			$print->attributes = $_POST['PrintJob'];
			
			$saved = true;
			if($saved){
				$printFile = $_FILES['PrintJob_Art'];
				$print->createArtFile($printFile);
				$saved = $saved && $print->save();
			} 
			if($saved) {
				$saved = $saved && $customer->save();
			}
			if($saved){
				$model->CUSTOMER_ID = $customer->ID;
				$model->PRINT_ID = $print->ID;
				$model->printDate = $model->dueDate;
				$saved = $saved && $model->save();
			}
			if($saved){
				//if saved, redirect
				Yii::app()->user->setFlash('success', 'The job was created successfully!');
				$this->redirect(array('update', 'id'=>$model->ID));
			} else {
				//otherwise, delete everything
				if(!$model->isNewRecord) {$model->delete();}
				if(!$customer->isNewRecord) {$customer->delete();}
				if(!$print->isNewRecord) {$print->delete();}				
			}
		}	
		

		$this->render('create',array(
			'model'=>$model,
			'customerList'=>$existingCustomers,
			'newCustomer'=>$customer,
			'print'=>$print,
			'leaders'=>$leaders,
			'printers'=>$printers,
			'styles'=>$styles,
			'colors'=>$colors,
			'sizes'=>$sizes,
			'passes'=>$passes,
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
		$print = $model->printJob;
		$existingCustomers = Customer::model()->findAll();
		$leaders = User::listUsersWithRole(User::LEAD_ROLE);
		$printers = User::listUsersWithRole(User::DEFAULT_ROLE);
		$styles = Lookup::model()->findAllByAttributes(array('TYPE'=>'Style'));
		$sizes = Lookup::model()->findAllByAttributes(array('TYPE'=>'Size'));
		$colors = Lookup::model()->findAllByAttributes(array('TYPE'=>'Color'));
		$passes = array(0, 1, 2, 3, 4, 5, 6); //as instructed by Ben, number of passes
		//should be limited to a few numbers.

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Job']))
		{
			$model->loadFromArray($_POST['Job']);
			$customer->attributes = $_POST['Customer'];
			$print->attributes = $_POST['PrintJob'];
			
			$saved = true;
			if($saved){
				$printFile = $_FILES['PrintJob_Art'];
				$print->createArtFile($printFile);
				$saved = $saved && $print->save();
			} 
			if($saved) {
				$saved = $saved && $customer->save();
			}
			if($saved){
				$model->CUSTOMER_ID = $customer->ID;
				$model->PRINT_ID = $print->ID;
				$saved = $saved && $model->save();
			}
			if($saved){
				//if saved, redirect
				Yii::app()->user->setFlash('success', 'The job was saved successfully!');
				$this->redirect(array('update', 'id'=>$model->ID));
			}
		}
		
		if($print->ART != null){
			$artLink = CHtml::normalizeUrl(array('job/art', 'id'=>$model->ID));
		} else {
			$artLink = null;
		}

		echo $artLink;
		$this->render('update',array(
			'model'=>$model,
			'customerList'=>$existingCustomers,
			'newCustomer'=>$customer,
			'print'=>$print,
			'leaders'=>$leaders,
			'printers'=>$printers,
			'styles'=>$styles,
			'colors'=>$colors,
			'sizes'=>$sizes,
			'artLink'=>$artLink,
			'passes'=>$passes,
		));
		
	}
	
	/**
	 * Let's the user download the art associated with a job.
	 */
	public function actionArt($id){
		$model = $this->loadModel($id);
		if($model){
			$file = $model->printJob->ART;
			if($file){
				$name = basename($file);
				//code below obtained from http://iamcam.wordpress.com/2007/03/20/clean-file-names-using-php-preg_replace/
				$replace="_";
				$pattern="/([[:alnum:]_\.-]*)/";
				$name=str_replace(str_split(preg_replace($pattern,$replace,$name)),$replace,$name);
				//end snippet
				
				Yii::app()->request->sendFile($name, file_get_contents($file));
			}
		}
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
		$lastSunday = strtotime('last sunday', time());
		$secondsPerWeek = 24*60*60*7;
		$nextSaturday = $lastSunday + $secondsPerWeek - 1;
		$jobsThisWeek = EventLog::model()->findAllByAttributes(array(
			'USER_ASSIGNED'=>Yii::app()->user->id,
			'OBJECT_TYPE'=>'Job',				
			'EVENT_ID'=>EventLog::JOB_PRINT,	
		), '`DATE` BETWEEN FROM_UNIXTIME(' . $lastSunday . ') AND FROM_UNIXTIME(' . $nextSaturday . ')');
		$jobs = array();
		foreach($jobsThisWeek as $event){
			$jobs[] = $event->assocObject;
		}
		$dataProvider = new CArrayDataProvider($jobs, array(
			'keyField'=>'ID',
		));
		
		$lastSunday = $lastSunday + $secondsPerWeek;
		$nextSaturday = $nextSaturday + $secondsPerWeek;
		$jobsNextWeek = EventLog::model()->findAllByAttributes(array(
			'USER_ASSIGNED'=>Yii::app()->user->id,
			'OBJECT_TYPE'=>'Job',		
			'EVENT_ID'=>EventLog::JOB_PRINT,
		), '`DATE` BETWEEN FROM_UNIXTIME(' . $lastSunday . ') AND FROM_UNIXTIME(' . $nextSaturday . ')');
		
		$currentWeek = $this->resultToCalendarData($jobsThisWeek);
		$nextWeek = $this->resultToCalendarData($jobsNextWeek);
		$this->render('dashboard',array(
			'dataProvider'=>$dataProvider,
			'currentData'=>$currentWeek,
			'nextData'=>$nextWeek,
		));
	}
	
	public function actionList(){
		$dataProvider = new CActiveDataProvider('Job', array(
			'pagination'=>false,
		));
		$this->render('list', array(
			'dataProvider'=>$dataProvider,
		));
	}
	
	private function resultToCalendarData($result){
		$calendarData = array();
		foreach($result as $event){
			$eventDate = strtotime($event->DATE);
			$dayName = date('l', $eventDate);
			$calendarData[$dayName]['date'] = $eventDate;
			$calendarData[$dayName]['items'][] = $event;
		}
		return $calendarData;
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Job('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Job']))
			$model->attributes=$_GET['Job'];

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
		$model=Job::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	public function loadList($type){
		return CHtml::listData(Lookup::listItems($type), 'ID', 'TEXT');
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='job-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
