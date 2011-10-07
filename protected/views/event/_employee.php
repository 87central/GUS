<?php $id = $employee->ID . '-container';?>
<div class="emp-tab-content" id="<?php echo $id;?>">
	<?php $this->widget('application.widgets.CalendarWidget', array(
		'droppable'=>true,
		'sortable'=>true,
		'itemCss'=>'calendar_item',
		'dayCss'=>'sortable',
		'itemView'=>'//job/_eventDetail',
		'headerView'=>'//job/_dayHeader',
		'data'=>$calendarData,
		'onDrop'=>"function(item, day, date){
			var event_id = $(item).data('event_id');" .
			"var id = '".$employee->ID."';" .
			"\$.ajax({
				url: '".CHtml::normalizeUrl(array('event/assign'))."'," .
				"data: {
					id: event_id," .
					"emp_id: id," .
					"date: date,
				}," .
				"type: 'POST'," .
				"success: function(data){
					\$('#".$id."').replaceWith(data);" .
					"\$(item).remove();
				}
			})
		}"
	));?>
	<?php Yii::app()->clientScript->registerScript($id . '-data', "" .
			"\$('#".$id."').data('id', '".$employee->ID."');", 
	CClientScript::POS_END);?>
</div>