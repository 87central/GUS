<?php

class EventController extends Controller
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('schedule','employeeSchedule'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionSchedule()
	{
		$unscheduled = EventLog::model()->findAllByAttributes(array(
			'USER_ASSIGNED'=>null,
			'OBJECT_TYPE'=>'Job',		
			'EVENT_ID'=>EventLog::JOB_DUE,	
		));
		$employees = User::listUsersWithRole(User::DEFAULT_ROLE);
		$this->render('schedule', array(
			'unscheduled'=>$unscheduled,
			'employees'=>$employees,
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionEmployeeSchedule($employee_id)
	{
		$lastSunday = strtotime('last sunday', time());
		$secondsPerWeek = 24*60*60*7;
		$nextSaturday = $lastSunday + $secondsPerWeek - 1;
		$jobsThisWeek = EventLog::model()->findAllByAttributes(array(
			'USER_ASSIGNED'=>$employee_id,
			'OBJECT_TYPE'=>'Job',		
			'EVENT_ID'=>EventLog::JOB_DUE,	
		), '`DATE` BETWEEN FROM_UNIXTIME(' . $lastSunday . ') AND FROM_UNIXTIME(' . $nextSaturday . ')');
		
		$currentWeek = $this->resultToCalendarData($jobsThisWeek);
		$this->renderPartial('_employee',array(
			'calendarData'=>$currentWeek,
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
}
