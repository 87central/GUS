<?php $id = $employee->ID . '-container';
$options = array( //options common to all four calendars in the view.
	'droppable'=>true,
	'sortable'=>true,
	'itemCss'=>'calendar_item',
	'dayCss'=>'sortable',
	'itemView'=>'//job/_eventDetail',
	'headerView'=>'//job/_dayHeader',
	'containerCss'=>'calendar_container',
);?>
<div class="emp-tab-content" id="<?php echo $id;?>">
	<?php $onDropBegin = "function(item, day, date){
			var event_id = $(item).find(':hidden').val();" .
			"var id = '".$employee->ID."';" .
			"var allCalendars = (day).parent().parent().children();" .
			"var calendar_id = new Array(4);" .
			"allCalendars.each(function(index){
				calendar_id[index] = $(this).attr('id');
			});" .
			"\$.ajax({
				url: '".CHtml::normalizeUrl(array('event/assign'))."'," .
				"data: {
					id: event_id," .
					"emp_id: id," .
					"date: date," .
					"calendar_id: calendar_id,
				}," .
				"type: 'POST'," .
				"success: function(data){
					\$('#".$id."').replaceWith(data);" .
					"\$(item).remove();" .
					"";
	$onDropEnd = "
				}
			})
		}";?>
	<?php 
	$calendars = array();
	$initializers = "";
	for($i = 0; $i < 4; $i++){
		$calendars[] = $this->createWidget('application.widgets.CalendarWidget', array_merge($options, array(
			'data'=>$calendarData[$i],
			'id'=>isset($calendar_id) ? $calendar_id[$i] : null,
		)));
		$initializers .= $calendars[$i]->initializeFunction . ';';
	}
	$initializers = $onDropBegin . $initializers . $onDropEnd;
	for($i = 0; $i < 4; $i++){
		$calendars[$i]->onDrop = $initializers;
		$calendars[$i]->run();
	}
	?>
	<?php /*$calendar = $this->beginWidget('application.widgets.CalendarWidget', array(
		'droppable'=>true,
		'sortable'=>true,
		'itemCss'=>'calendar_item',
		'dayCss'=>'sortable',
		'itemView'=>'//job/_eventDetail',
		'headerView'=>'//job/_dayHeader',
		'containerCss'=>'calendar_container',
		'data'=>$calendarData,
	));
	
	$onDrop = "function(item, day, date){
			var event_id = $(item).find(':hidden').val();" .
			"var id = '".$employee->ID."';" .
			"\$.ajax({
				url: '".CHtml::normalizeUrl(array('event/assign'))."'," .
				"data: {
					id: event_id," .
					"emp_id: id," .
					"date: date," .
					"calendar_id: (day).parent().attr('id'),
				}," .
				"type: 'POST'," .
				"success: function(data){
					\$('#".$id."').replaceWith(data);" .
					"\$(item).remove();" .
					"".$calendar->initializeFunction.";
				}
			})
		}";
	$calendar->onDrop = $onDrop;
	$this->endWidget();*/
	?>
	<?php Yii::app()->clientScript->registerScript($id . '-data', "" .
			"\$('#".$id."').data('id', '".$employee->ID."');", 
	CClientScript::POS_END);?>
</div>