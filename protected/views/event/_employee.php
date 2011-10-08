<?php $id = $employee->ID . '-container';?>
<div class="emp-tab-content" id="<?php echo $id;?>">
	<?php $calendar = $this->beginWidget('application.widgets.CalendarWidget', array(
		'droppable'=>true,
		'sortable'=>true,
		'itemCss'=>'calendar_item',
		'dayCss'=>'sortable',
		'itemView'=>'//job/_eventDetail',
		'headerView'=>'//job/_dayHeader',
		'containerCss'=>'calendar_container',
		'data'=>$calendarData,
		'id'=>$calendar_id,//(isset($calendar_id) ? $calendar_id : null),
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
	$this->endWidget();
	?>
	<?php Yii::app()->clientScript->registerScript($id . '-data', "" .
			"\$('#".$id."').data('id', '".$employee->ID."');", 
	CClientScript::POS_END);?>
</div>