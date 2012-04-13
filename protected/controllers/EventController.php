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
			array('allow',
				'actions'=>array(),
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
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionSchedule()
	{
		$criteria = new CDbCriteria;
		$criteria->join = 'INNER JOIN `job` ON 	`job`.`ID` = `t`.`OBJECT_ID`';
		$criteria->addInCondition('`job`.`STATUS`', array(Job::CREATED, Job::INVOICED, Job::PAID, Job::SCHEDULED, Job::ORDERED, Job::COUNTED, Job::PRINTED));
		$unscheduled = EventLog::model()->findAllByAttributes(array(
			'USER_ASSIGNED'=>null,
			'OBJECT_TYPE'=>'Job',		
			'EVENT_ID'=>EventLog::JOB_PRINT,	
		), $criteria);
		$employees = User::listUsersWithRole(User::DEFAULT_ROLE);
		$resultEmps = array();
		foreach($employees as $emp){
			$schedule = array();
			for($i = 0; $i < 4; $i++){
				$schedule[] = $this->findWeekSchedule($emp->ID, $i);
			}
			$resultEmps[$emp->FIRST] = $this->renderPartial('_employee', array(
				'calendarData'=>$schedule,
				'employee'=>$emp,
			), true);
		}
		$this->render('schedule', array(
			'unscheduled'=>$unscheduled,
			'employees'=>$resultEmps,
		));
	}
	
	/**
	 * Assigns an event to a user to be completed on a given day.
	 */
	public function actionAssign(){
		if(Yii::app()->request->isPostRequest){
			$event_id = $_POST['id'];
			$emp_id = $_POST['emp_id'];
			$date = $_POST['date'];
			$calendar_id = $_POST['calendar_id'];
			
			$model = EventLog::model()->findByPk((int) $event_id);
			$model->USER_ASSIGNED = $emp_id;
			$model->DATE = $date;
			if($model->save()){
				$employee = User::model()->findByPk((int) $emp_id);
				$schedule = array();
				for($i = 0; $i < 4; $i++){
					$schedule[] = $this->findWeekSchedule($employee->ID, $i);
				}
				$this->renderPartial('_employee', array(
					'calendarData'=>$schedule,
					'employee'=>$employee,
					'calendar_id'=>$calendar_id,
				));
			} else {
				throw new CException('Could not assign the event.');
			}
		}
	}
	
	/**
	 * Unassigned an event from a user.
	 */
	public function actionUnassign(){
		if(Yii::app()->request->isPostRequest){
			$event_id = $_POST['id'];
			
			$model = EventLog::model()->findByPk((int) $event_id);
			$model->USER_ASSIGNED = null;
			$model->DATE = null;
			$model->save();
		}
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
		
		$currentWeek = $this->resultToCalendarData($jobsThisWeek);
		if(count($currentWeek) == 0){
			$currentWeek[date('l')] = array(
				'items'=>array(),
				'date'=>time() + $weekOffset * $secondsPerWeek,
			);
		}
		return $currentWeek;
	}
}
