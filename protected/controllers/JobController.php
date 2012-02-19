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
				'actions'=>array('status', 'create', 'update', 'deleteLine', 'approveLine', 'newLine', 'view', 'list', 'loadList', 'index', 'garmentCost', 'addArt', 'deleteArt'),
				'users'=>array('@'),
				'expression'=>"Yii::app()->user->getState('isDefaultRole');",
			),
			array('allow',
				'actions'=>array('status', 'create', 'update', 'deleteLine', 'approveLine', 'newLine', 'view', 'list', 'loadList', 'index', 'garmentCost', 'addArt', 'deleteArt'),
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
		$model = $this->loadModel($id);
		$sizes = Lookup::listItems('Size');
		$lineData = array();
		$products = array();
		$groupedLines = array();
		foreach($model->jobLines as $line){
			$groupedLines[(string) $line->product->STYLE][(string) $line->product->COLOR][(string) $line->product->SIZE] = $line;
		}
		
		foreach($groupedLines as $style=>$styleGroup){
			foreach($styleGroup as $color=>$colorGroup){
				$approved = false;
				foreach($sizes as $size){ //iterating through sizes because we want ALL of them
					if(isset($colorGroup[(string) $size->ID])){
						$line = $colorGroup[(string) $size->ID];						
						$products[] = array(
							'product'=>$line->product,
							'line'=>$line,
						);
						if($line->isApproved){
							$approved = true;
						}
						$latestProduct = $line->product;
					} else {
						$product = new Product;
						$product->SIZE = $size->ID;
						$product->STYLE = $style;
						$product->COLOR = $color;
						$products[] = array(
							'product'=>$product,
							'line'=>new JobLine,
						);
					}
				}
				if(count($products) > 0){
					$products['lines'] = $products;
					$products['style'] = $latestProduct->vendorStyle; //we'll always have a latestProduct, otherwise we wouldn't enter this loop
					$products['availableColors'] = CHtml::listData(Product::getAllowedColors($latestProduct->VENDOR_ITEM_ID), 'ID', 'TEXT');
					$products['currentColor'] = $color;
					$products['approved'] = $approved;
					$products['saved'] = true; //we're guaranteed that some of the lines in this group are persistent
					$lineData[] = $products;
					$products = array();
				}
			}
		}
		
		if($print->ART != null){
			$artLink = CHtml::normalizeUrl(array('job/art', 'id'=>$model->ID));
		} else {
			$artLink = null;
		}
		
		if($print->MOCK_UP != null){
			$mockupLink = CHtml::normalizeUrl(array('job/mockUp', 'id'=>$model->ID));			
		} else {
			$mockupLink = null;
		}
		
		$this->render('view',array(
			'model'=>$this->loadModel($id),
			'customer'=>$model->CUSTOMER,
			'print'=>$model->printJob,
			'lineData'=>$lineData,
			'artLink'=>$artLink,
			'mockupLink'=>$mockupLink,
			'formatter'=>new Formatter,
		));
	}
	
	public function actionNewLine(){
		$namePrefix = $_POST['namePrefix'];
		$count = $_POST['count'];
		
		$sizes = Lookup::listItems('Size');
		$products = array();	
		foreach($sizes as $size){
			$product = new Product;
			$product->SIZE = $size->ID;
			$products[] = array(
				'product'=>$product,
				'line'=>new JobLine,
			);	
		}
		
		$products['lines'] = $products;
		$products['style'] = '';
		$products['availableColors'] = array();
		$products['currentColor'] = null;
		$products['approved'] = false;
		$products['saved'] = false;
		
		$this->renderPartial('//jobLine/_multiForm', array(
			'namePrefix'=>$namePrefix,
			'startIndex'=>$count,
			'products'=>$products,
		));
	}
	
	public function actionApproveLine(){
		$namePrefix = $_POST['namePrefix'];
		$startIndex = $_POST['startIndex'];
		$idList = $_POST['idList'];
		$models = JobLine::model()->findAllByPk($idList);
		$sizes = Lookup::model()->findAllByAttributes(array('TYPE'=>'Size'));
		$products = array();
		$groupedLines = array();
		foreach($models as $model){			
			if($model){
				$model->approve();
				$groupedLines[(string) $model->product->STYLE][(string) $model->product->COLOR][(string) $model->product->SIZE] = $model;
			}
		}
		
		foreach($groupedLines as $style=>$styleGroup){
			foreach($styleGroup as $color=>$colorGroup){
				$approved = false;
				foreach($sizes as $size){ //iterating through sizes because we want ALL of them
					if(isset($colorGroup[(string) $size->ID])){
						$line = $colorGroup[(string) $size->ID];						
						$products[] = array(
							'product'=>$line->product,
							'line'=>$line,
						);
						if($line->isApproved){
							$approved = true;
						}
						$latestProduct = $line->product;
					} else {
						$product = new Product;
						$product->SIZE = $size->ID;
						$product->STYLE = $style;
						$product->COLOR = $color;
						$products[] = array(
							'product'=>$product,
							'line'=>new JobLine,
						);
					}
				}
				if(count($products) > 0){
					$products['lines'] = $products;
					$products['style'] = $latestProduct->vendorStyle; //we'll always have a latestProduct, otherwise we wouldn't enter this loop
					$products['availableColors'] = CHtml::listData(Product::getAllowedColors($latestProduct->VENDOR_ITEM_ID), 'ID', 'TEXT');
					$products['currentColor'] = $color;
					$products['approved'] = $approved;
					$products['saved'] = true; //we're guaranteed that some of the lines in this group are persistent
				}
			}
		}
		if($approved && !Yii::app()->user->getState('isAdmin')){
			$view = '//jobLine/_multiView';
		} else {
			$view = '//jobLine/_multiForm';
		}
		
		$this->renderPartial($view, array(
			'namePrefix'=>$namePrefix,
			'startIndex'=>$startIndex,
			'products'=>$products,
		));
	}
	
	public function actionUnapproveLine(){
		$namePrefix = $_POST['namePrefix'];
		$startIndex = $_POST['startIndex'];
		$idList = $_POST['idList'];
		$models = JobLine::model()->findAllByPk($idList);
		$sizes = Lookup::model()->findAllByAttributes(array('TYPE'=>'Size'));
		$products = array();
		$groupedLines = array();
		foreach($models as $model){			
			if($model){
				$model->unapprove();
				$groupedLines[(string) $model->product->STYLE][(string) $model->product->COLOR][(string) $model->product->SIZE] = $model;
			}
		}
		
		foreach($groupedLines as $style=>$styleGroup){
			foreach($styleGroup as $color=>$colorGroup){
				$approved = false;
				foreach($sizes as $size){ //iterating through sizes because we want ALL of them
					if(isset($colorGroup[(string) $size->ID])){
						$line = $colorGroup[(string) $size->ID];						
						$products[] = array(
							'product'=>$line->product,
							'line'=>$line,
						);
						if($line->isApproved){
							$approved = true;
						}
						$latestProduct = $line->product;
					} else {
						$product = new Product;
						$product->SIZE = $size->ID;
						$product->STYLE = $style;
						$product->COLOR = $color;
						$products[] = array(
							'product'=>$product,
							'line'=>new JobLine,
						);
					}
				}
				if(count($products) > 0){
					$products['lines'] = $products;
					$products['style'] = $latestProduct->vendorStyle; //we'll always have a latestProduct, otherwise we wouldn't enter this loop
					$products['availableColors'] = CHtml::listData(Product::getAllowedColors($latestProduct->VENDOR_ITEM_ID), 'ID', 'TEXT');
					$products['currentColor'] = $color;
					$products['approved'] = $approved;
					$products['saved'] = true; //we're guaranteed that some of the lines in this group are persistent
				}
			}
		}
		$view = '//jobLine/_multiForm';
		
		$this->renderPartial($view, array(
			'namePrefix'=>$namePrefix,
			'startIndex'=>$startIndex,
			'products'=>$products,
		));
	}
	
	public function actionDeleteLine(){
		$model = JobLine::model()->findByPk((int) $_POST['id']);
		if($model){
			if(!$model->delete()){
				throw new CException('Could not delete the job line.');
			}
		}
	}
	
	public function actionGarmentCost($garments, $front, $back, $sleeve){
		$result = array('result'=>CostCalculator::calculateTotal($garments, $front, $back, $sleeve, 0));
		echo CJSON::encode($result);
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
		
		$lineData = array();
		$products = array();	
		foreach($sizes as $size){
			$product = new Product;
			$product->SIZE = $size->ID;
			$products[] = array(
				'product'=>$product,
				'line'=>new JobLine,
			);	
		}
				
		$products['lines'] = $products;
		$products['style'] = '';
		$products['availableColors'] = array();
		$products['currentColor'] = null;
		$products['approved'] = false;
		$products['saved'] = false;
		$lineData[] = $products;
		
		/*
		 * Now that I've totally forgotten the format, I think it's time to 
		 * document what the format of the "lineData" array is. The parent array,
		 * "lineData" is a list of lists. For each combination of style and color,
		 * there is a list in "lineData". Each child list is composed of children 
		 * with two elements: a "product" element of type Product which
		 * has its "SIZE" property set to the corresponding size from the DB, and
		 * a "line" element of type JobLine which represents the job line itself.
		 * 
		 * Every list in "lineData" should be grouped by color.
		 * 
		 * New change: each item of lineData is now a triplet of "lines", "style", "currentColor", and 
		 * "availableColors". "lines" contains what was originally the item of lineData,
		 * "style" contains text describing the selected vendor style, "availableColors"
		 * contains the colors available for the selected vendor style (if any), already processed
		 * with CHtml::listData, and "currentColor" contains the ID of the color for the group.
		 * "approved" is true if the set of lines has been approved, otherwise false.*/

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Job']))
		{
			$model->loadFromArray($_POST['Job']);
			$customerWasNew = true;
			if(isset($_POST['Customer']['ID'])){
				$customer = Customer::model()->findByPk((int) $_POST['Customer']['ID']);
				$customerWasNew = false;
			}
			unset($_POST['Customer']['summary']);
			$customer->attributes = $_POST['Customer'];
			$print->loadFromArray($_POST['PrintJob'], $_FILES['PrintJob']);
			
			
			$saved = true;
			if($saved){
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
				if(!$customer->isNewRecord && $customerWasNew) {$customer->delete();}
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
			'lineData'=>$lineData,
			'fileTypes'=>Lookup::listItems('ArtFileType'),
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
		
		$lineData = array();
		$products = array();
		$groupedLines = array();
		foreach($model->jobLines as $line){
			$groupedLines[(string) $line->product->vendorStyle][(string) $line->product->COLOR][(string) $line->product->SIZE] = $line;
		}
		
		foreach($groupedLines as $style=>$styleGroup){
			if($style){
				foreach($styleGroup as $color=>$colorGroup){
					$approved = false;
					foreach($sizes as $size){ //iterating through sizes because we want ALL of them
						if(isset($colorGroup[(string) $size->ID])){
							$line = $colorGroup[(string) $size->ID];						
							$products[] = array(
								'product'=>$line->product,
								'line'=>$line,
							);
							if($line->isApproved){
								$approved = true;
							}
							$latestProduct = $line->product;
						} else {
							$product = new Product;
							$product->SIZE = $size->ID;
							$product->STYLE = $style;
							$product->COLOR = $color;
							$products[] = array(
								'product'=>$product,
								'line'=>new JobLine,
							);
						}
					}
					if(count($products) > 0){
						$products['lines'] = $products;
						$products['style'] = $latestProduct->vendorStyle; //we'll always have a latestProduct, otherwise we wouldn't enter this loop
						$products['availableColors'] = CHtml::listData(Product::getAllowedColors($latestProduct->VENDOR_ITEM_ID), 'ID', 'TEXT');
						$products['currentColor'] = $color;
						$products['approved'] = $approved;
						$products['saved'] = true; //we're guaranteed that some of the lines in this group are persistent
						$lineData[] = $products;
						$products = array();
					}
				}
			}
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Job']))
		{
			$model->loadFromArray($_POST['Job']);
			if(isset($_POST['Customer']['ID'])){
				$customer = Customer::model()->findByPk((int) $_POST['Customer']['ID']);
			}
			unset($_POST['Customer']['summary']);
			$customer->attributes = $_POST['Customer'];
			$print->loadFromArray($_POST['PrintJob'], $_FILES);
			
			$saved = true;
			if($saved){
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
			'passes'=>$passes,
			'lineData'=>$lineData,
			'fileTypes'=>Lookup::listItems('ArtFileType'),
		));
		
	}
	
	/**
	 * Let's the user download the art associated with a job.
	 */
	public function actionArt($art_id){
		$model = PrintArt::model()->findByPk((int) $art_id);
		if($model){
			$file = $model->FILE;
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
	 * Adds an art record. This will not create any files, but will simply return
	 * a new form section to be used on the job entry form.
	 */
	public function actionAddArt($namePrefix, $fileCount, $fileType, $print_id = null){
		$this->renderPartial('//print/_artForm', array(
			'model'=>new PrintArt,
			'print_id'=>$print_id,
			'fileType'=>$fileType,
			'namePrefix'=>$namePrefix . '['.++$fileCount.']',
			'fileCount'=>$fileCount,
			'artLink'=>null,
		));
	}
	
	/**
	 * Deletes an art record. This will delete any associated file, as well as the
	 * record in the database.
	 * @param int $id The identifier of the art file record. 
	 */
	public function actionDeleteArt(){
		if(Yii::app()->request->isPostRequest){
			$model = PrintArt::model()->findByPk((int) $_POST['id']);
			if($model){
				$model->delete();
			}
		} else {
			throw new CHttpException('403', 'Not authorized');
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
		$calendarData = array();
		for($i = 0; $i < 4; $i++){
			$calendarData[] = $this->formatWeekSchedule(Yii::app()->user->id, $i);
		}
		$jobs = array();
		$jobsThisWeek = $this->findWeekSchedule(Yii::app()->user->id);
		foreach($jobsThisWeek as $event){
			$jobs[] = $event->assocObject;
		}
		$dataProvider = new CArrayDataProvider($jobs, array(
			'keyField'=>'ID',
		));			
		
		$statuses = CHtml::listData(Lookup::listItems('JobStatus'), 'ID', 'TEXT');
			
		$this->render('dashboard',array(
			'dataProvider'=>$dataProvider,
			'calendarData'=>$calendarData,
			'statuses'=>$statuses,
		));
	}
	
	/**
	 * Gets an employee's schedule, appropriate for a calendar widget. If there is nothing
	 * on the schedule, the data returned will simply contain today's date.
	 * @param string $employee_id The ID of the employee whose schedule should be retrieved.
	 * @param int $weekOffset The number of weeks from the current week to find in the schedule.
	 */
	private function findWeekSchedule($employee_id, $weekOffset = 0){
		$secondsPerWeek = 24*60*60*7;
		$lastSunday = strtotime('last sunday', time());		
		$nextSaturday = $lastSunday + $secondsPerWeek - 1;
		$lastSunday += $weekOffset * $secondsPerWeek;
		$nextSaturday += $weekOffset * $secondsPerWeek;
		$jobsThisWeek = EventLog::model()->findAllByAttributes(array(
			'USER_ASSIGNED'=>$employee_id,
			'OBJECT_TYPE'=>'Job',		
			'EVENT_ID'=>EventLog::JOB_PRINT,	
		), '`DATE` BETWEEN FROM_UNIXTIME(' . $lastSunday . ') AND FROM_UNIXTIME(' . $nextSaturday . ')');
		
		return $jobsThisWeek;
	}
	
	private function formatWeekSchedule($employee_id, $weekOffset = 0){
		$currentWeek = $this->findWeekSchedule($employee_id, $weekOffset);
		$secondsPerWeek = 24*60*60*7;
		$currentWeek = $this->resultToCalendarData($currentWeek);
		if(count($currentWeek) == 0){
			$currentWeek[date('l')] = array(
				'items'=>array(),
				'date'=>time() + $weekOffset * $secondsPerWeek,
			);
		}
		return $currentWeek;
	}
	
	/**
	 * Loads the contents of a job listing tab.
	 * @param string $list The type of list to load. Valid values are "current", "canceled", and "completed"
	 */
	public function actionLoadList($list){
		switch($list){
			case 'current' : $filter = array(Job::CREATED, JOB::SCHEDULED, Job::INVOICED, Job::PAID); break;
			case 'canceled' : $filter = Job::CANCELED; break;
			case 'completed' : $filter = Job::COMPLETED; break;
			default : $filter = null; break;
		}
		$jobs = Job::listJobsByStatus($filter);
		$dataProvider = new CArrayDataProvider($jobs, array(
			'keyField'=>'ID',
			'pagination'=>false,
		));
		
		$this->renderPartial('_listSection', array(
			'dataProvider'=>$dataProvider,
			'statuses'=> CHtml::listData(Lookup::listItems('JobStatus'), 'ID', 'TEXT'),
		));
	}
	
	public function actionList(){		
		$currentJobs = Job::listJobsByStatus(array(Job::CREATED, JOB::SCHEDULED, Job::INVOICED, Job::PAID));
		$currentDataProvider = new CArrayDataProvider($currentJobs, array(
			'keyField'=>'ID',
			'pagination'=>false,
		));
		
		$canceledJobs = Job::listJobsByStatus(Job::CANCELED);
		$canceledDataProvider = new CArrayDataProvider($canceledJobs, array(
			'keyField'=>'ID',
			'pagination'=>false,
		));
		
		$completedJobs = Job::listJobsByStatus(Job::COMPLETED);
		$completedDataProvider = new CArrayDataProvider($completedJobs, array(
			'keyField'=>'ID',
			'pagination'=>false,
		));
		
		$statuses = CHtml::listData(Lookup::listItems('JobStatus'), 'ID', 'TEXT');
		
		$this->render('list', array(
			'currentDataProvider'=>$currentDataProvider,
			'canceledDataProvider'=>$canceledDataProvider,
			'completedDataProvider'=>$completedDataProvider,
			'statuses'=>$statuses,
		));
	}
	
	public function actionStatus($id){
		$model = $this->loadModel($id);
		$model->STATUS = $_POST['status'];
		$model->save();
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
