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
	 	$minDate = PHP_INT_MAX;
	 	$secondsPerDay = 24 * 60 * 60;
	 	$baseDate = $secondsPerDay * 3; //epoch was a Thursday
	 	for($i = 0; $i < 7; $i++){
	 		$date = $baseDate + $secondsPerDay * $i;
	 		$dayName = $this->getWeekdayName($date);
	 		if(!isset($this->data[$dayName])){
	 			$this->data[$dayName] = array('items'=>array(),
	 										  'date'=>$date,
	 			);
	 		} else {
	 			$day = $this->data[$dayName];
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
	 			$this->data[$dayName]['date'] = $date;
	 		}
	 	}
	}
	
	/**
	 * Renders this widget.
	 */
	public function run(){
		if(!isset($this->containerCss)){
			$options = array('id'=>$this->id);
		} else {
			$options = array('id'=>$this->id, 'class'=>$this->containerCss);
		}
		
		if($this->droppable){
			$this->droppableScript($this->id, $this->itemCss);
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
			echo CHtml::openTag('div', $options);
			
			$this->renderDay($dayName, $info['items'], $info['date']);
			
			echo CHtml::closeTag('div');
		}
		
		echo CHtml::closeTag('div');
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
		$options = $this->createOptions(array(), $id);
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
	
	private function droppableScript($id, $selector){
		$script = "" .
				"$(function(){
					" .
					"$(#" . $id . ").droppable({accept: '." . $selector . "'});
				})";
		Yii::app()->clientScript->registerScript('droppable', $script, CClientScript::POS_BEGIN);
	}
}