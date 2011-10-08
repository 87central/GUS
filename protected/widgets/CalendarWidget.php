<?php
class CalendarWidget extends CWidget {
	/**
	 * Name of the view which will be used to render each item contained in
	 * each day. When rendering, this view will be supplied with an $item variable
	 * containing the item to render. Note that this content will be rendered inside
	 * an element with the itemCss class.
	 */
	public $itemView;
	/**
	 * Name of the view which will be used to render the header portion of each
	 * day. This will be supplied with a $name, $date, and $items variable when
	 * rendering. Note that this content will be rendered inside an element with the
	 * headerCss class.
	 */
	public $headerView;
	/**
	 * The data for all days in this widget. Valid days are Sunday-Saturday.
	 * All days are optional, though omitting a day will not prevent it from being displayed.
	 * The data for each day shall be an array of items to display (key "items") and a 
	 * date value containing the date to render (key "date").
	 */
	public $data;
	/**
	 * True if draggable items can be dropped on days, otherwise false. Draggable items must have
	 * the $itemCss class associated with them to be dropped.
	 */
	public $droppable;
	/**
	 * The javascript function to be called when an item is dropped onto a day.
	 * The function will receive arguments for the draggable that was dropped, a
	 * jquery object for the day on which the draggable was dropped, and the date
	 * on which the draggable was dropped, in the format mm/dd/yyyy. 
	 */
	public $onDrop = null;
	/**
	 * True if calendar items can be sorted, otherwise false. The dayCss attribute
	 * must also be set in order for this to work.
	 */
	public $sortable;
	/**
	 * The css class to be assigned to the container for each day.
	 */
	public $dayCss;
	/**
	 * The css class to be assigned to the wrapper of each item.
	 */
	public $itemCss;
	/**
	 * The css class to be assigned to the wrapper of each header.
	 */
	public $headerCss;
	/**
	 * The css class to be assigned to the container for the current day. This
	 * will be assigned in addition to $dayCss
	 */
	public $todayCss;
	
	/**
	 * The css class to be assigned to a day container over which the mouse is currently 
	 * hovering.
	 */
	public $hoverCss;
	
	/**
	 * The css class to be assigned to the entire widget.
	 */
	public $containerCss;
	
	/**
	 * The list of scripts which need to be run in order to faciliate dropping
	 * and sorting.
	 */
	private $scripts = array();
	
	/**
	 * Gets the name of the JavaScript function (with parens) which needs to be called
	 * to initalize the front-end functionality of the calendar.
	 */
	public function getInitializeFunction(){
		return $this->id . 'calendarInit()';
	}
	 
	/**
	 * Normalizes the items in our data collection. This involves ensuring that all
	 * seven days are in the array, and that each day contains, at the very least,
	 * an empty array of items and a valid date. The date will be in the current week
	 * if no other reference date is available in the dataset. Otherwise, the week will start
	 * with the Sunday before the lowest date in the original dataset and continue to the
	 * next Saturday.
	 */ 
	public function init() {
	 	if($this->data === null){
	 		$this->data = array();
	 	}
	 	$normalizedDate = array();
	 	$minDate = PHP_INT_MAX;
	 	$secondsPerDay = 24 * 60 * 60;
	 	$baseDate = $secondsPerDay * 4; //epoch was a Thursday
	 	for($i = 0; $i < 7; $i++){
	 		$date = $baseDate + $secondsPerDay * $i;
	 		$dayName = $this->getWeekdayName($date);
	 		if(!isset($this->data[$dayName])){
	 			$normalizedData[$dayName] = array('items'=>array(),
	 											  'date'=>$date,
	 			);
	 		} else {
	 			$normalizedData[$dayName] = $this->data[$dayName];
	 			$day = $normalizedData[$dayName];
	 			if(!isset($day['items'])){
	 				$day['items'] = array();
	 			}
	 			if(!isset($day['date'])){
	 				$day['date'] = $date;
	 			} else {
	 				if($day['date'] < $minDate){
	 					$minDate = $day['date'];
	 				}
	 			}
	 		}
	 	}
	 	
	 	if($minDate != PHP_INT_MAX){
	 		$ordinal = $this->getWeekdayOrdinal($minDate);
	 		$baseDate = $minDate - $ordinal * $secondsPerDay;
	 		for($i = 0; $i < 7; $i++){
	 			$date = $baseDate + $secondsPerDay * $i;
	 			$dayName = $this->getWeekdayName($date);
	 			$normalizedData[$dayName]['date'] = $date;
	 		}
	 	}
	 	
	 	$this->data = $normalizedData;
	 	
	 	Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . '/css/calendar.css');
	}
	
	/**
	 * Renders this widget.
	 */
	public function run(){
		if(!isset($this->containerCss)){
			$options = array('id'=>$this->id, 'class'=>'ui-cal');
		} else {
			$options = array('id'=>$this->id, 'class'=>$this->containerCss . ' ui-cal');
		}
		
		echo CHtml::openTag('div', $options);
		
		//render each day. a base style should be associated with each but extended by the dayCss and hoverCss classes.
		//TODO add the base style
		foreach($this->data as $dayName=>$info){
			$classes = array('ui-cal-day', $this->dayCss);
			if(date('Y-m-d') == date('Y-m-d', $info['date'])){
				$classes[] = $this->todayCss;
			}			
			$id = array($this->id, strtolower($dayName)); //$id is array
			$options = $this->createOptions($classes, $id);
			
			//associate the date string for retrieval if something is dropped on a day
			$this->scripts[] = "\$('#".$options['id']."').data('date', '".date('m/d/Y', $info['date'])."');";
			
			echo CHtml::openTag('div', $options);
			
			$this->renderDay($dayName, $info['items'], $info['date']);
			
			echo CHtml::closeTag('div');
		}
		
		echo CHtml::closeTag('div');
		
		$hasScripts = false;
		
		if($this->droppable){
			$this->droppableScript($this->id, $this->itemCss);
			$sortCss = $this->sortable ? $this->dayCss : null;
			$this->draggableScript($this->id, $this->itemCss, $sortCss);
			$hasScripts = true;
		}	
		
		if($this->sortable){
			$this->sortableScript($this->id, $this->dayCss);
			$hasScripts = true;
		}	
		
		if($hasScripts){
			$this->finalizeScripts();
		}
	}
	
	/**
	 * Renders a single day in this widget. 
	 * @param string $name The name of the day to render.
	 * @param array $items The array of items to render.
	 * @param date $date The date to render.
	 */
	protected function renderDay($name, $items, $date){
		$classes = array('ui-cal-header', $this->headerCss);
		$id = array($this->id, strtolower($name), 'header');
		$options = $this->createOptions($classes, $id);
		echo CHtml::openTag('div', $options);
		
		$this->controller->renderPartial($this->headerView, array(
			'name'=>$name,
			'date'=>$date,
			'items'=>$items,
		));
		
		echo CHtml::closeTag('div');
		
		$id = array($this->id, strtolower($name), 'items');
		$classes = array('ui-cal-items');
		$options = $this->createOptions($classes, $id);
		echo CHtml::openTag('div', $options);
		$i = 0;
		foreach($items as $item){
			$this->renderItem($i, $name, $item);
			$i++;
		}
		echo CHtml::closeTag('div');
	}
	
	/**
	 * Renders a single item in this widget.
	 * @param mixed $item The item to render.
	 * @param integer $index The index of the item to render.
	 */
	protected function renderItem($index, $name, $item){
		$id = array($this->id, strtolower($name), 'item', $index);
		$classes = array('ui-cal-item', $this->itemCss);
		$options = $this->createOptions($classes, $id);
		echo CHtml::openTag('div', $options);
		$this->controller->renderPartial($this->itemView, array('item'=>$item));
		echo CHtml::closeTag('div');
	}
	
	/**
	 * Creates an HTML options array.
	 * @param array $classes The CSS classes to apply to the HTML element. Null classes are ignored.
	 * @param array $id An array of strings which, collectively, give a unique ID to the element.
	 * @param array $others Any additional HTML options to pass to the element. 
	 */
	private function createOptions($classes, $id, $others=array()){
		$id = implode('-', $id);
		$usedClasses = array();
		foreach($classes as $class){
			if(isset($class) && $class !== null){
				$usedClasses[] = $class;
			}
		}
		$usedClasses = implode(' ', $usedClasses);
		$options = array(
			'id'=>$id,
			'class'=>$usedClasses,	
		);
		$options = array_merge($options, $others);
		return $options;
	}
	
	/**
	 * Gets the name of a weekday from the given date. Note that sending a weekend date will return "Weekend".
	 */	
	private function getWeekdayName($date){
		$day = $this->getWeekdayOrdinal($date);
		if($day === 0 || $day == 6){
			//$name = 'Weekend';
			$name = date('l', $date);
		} else {
			$name = date('l', $date);
		}
		return $name;
	}
	
	/**
	 * Gets the ordinal of a weekday (0 for Sunday through 6 for Saturday).
	 */	
	private function getWeekdayOrdinal($date){
		return date('w', $date);
	}
	
	private function draggableScript($id, $selector, $sortableCss){
		$secondsPerDay = 60*60*24;
		for($date = 0, $i = 0; $i < 7; $i++, $date+=$secondsPerDay){
			$droppableID = $id . '-' . strtolower($this->getWeekdayName($date)) . '-items';
		
			$script = "$('#".$droppableID."').children('.".$selector."')";
			if($sortableCss){
				$script .= ".draggable({connectToSortable: '.".$sortableCss."'});";
			} else {
				$script .= ".draggable();";
			}
			$this->scripts[] = $script;
		}
	}
	
	private function droppableScript($id, $selector){
		$secondsPerDay = 60*60*24;
		for($date = 0, $i = 0; $i < 7; $i++, $date+=$secondsPerDay){
			$droppableID = $id . '-' . strtolower($this->getWeekdayName($date)) . '-items';
		
			$script = "$('#".$droppableID."').droppable({accept: '." . $selector . "', tolerance: 'pointer'," .
								($this->onDrop == null ? "" : "drop: function(event, data){var exec = ".$this->onDrop."; exec(data.draggable, \$(this).parent(), \$(this).parent().data('date'));}, ") .
								"greedy: true});";
			$this->scripts[] = $script;
		}
	}
	
	private function sortableScript($id, $selector){
		$secondsPerDay = 60*60*24;
		for($date = 0, $i = 0; $i < 7; $i++, $date+=$secondsPerDay){
			$sortableID = $id . '-' . strtolower($this->getWeekdayName($date)) . '-items';
			
			$script = "$('#".$sortableID." .".$selector."').sortable({revert: true});";
			$this->scripts[] = $script;
		}
	}
	
	/**
	 * Does the final registration of all of the scripts.
	 */
	private function finalizeScripts(){
		$finalScript = implode($this->scripts, ';');
		$finalScript = 'function '.$this->initializeFunction."{".$finalScript."}";
		Yii::app()->clientScript->registerScript('calendar-init-fn-'.$this->id, $finalScript, CClientScript::POS_END);
		$finalScript = $this->initializeFunction . ';';
		Yii::app()->clientScript->registerScript('calendar-init-call-'.$this->id, $finalScript, CClientScript::POS_END);
	}
}