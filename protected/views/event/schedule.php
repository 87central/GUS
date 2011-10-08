<?php
$this->pageTitle = Yii::app()->user->name . ' - ' . 'Schedule';
Yii::app()->clientScript->registerCssFile($this->styleDirectory . 'event_schedule.css');
Yii::app()->clientScript->registerCoreScript('jquery');
?>

<h1>SCHEDULE</h1>

<div class="separator"></div>

<h2>UNSCHEDULED JOBS</h2>
<?php $this->beginWidget('zii.widgets.jui.CJuiDroppable', array(
	'id'=>'unscheduled',
	'options'=>array(
		'accept'=>'.calendar_item',
		'drop'=>"js:function(event, data){
			$.ajax({
				url: '".CHtml::normalizeUrl(array('event/unassign'))."'," .
				"type: 'POST'," .
				"data: {
					id: data.draggable.find(':hidden').val(),
				},
			});" .
			"$(data.draggable).appendTo('#unscheduled').css('left', '').css('top', '');" .
			"
		}",
		'tolerance'=>'pointer',
	),
));?>
	<?php foreach($unscheduled as $event){?>
		<?php $draggable = $this->beginWidget('zii.widgets.jui.CJuiDraggable', array(
			'htmlOptions'=>array(
				'class'=>'calendar_item',
			),
			'options'=>array(
				'connectToSortable'=>'.sortable',
			),
		));?>
		
		<div class="unscheduled">
			<?php $this->renderPartial('//job/_eventDetail', array(
				'item'=>$event,
			));?>
		</div>
		
		<?php Yii::app()->clientScript->registerScript($draggable->id . '-data', "" .
				"$('#".$draggable->id."').data('event_id', ".$event->ID.");", 
		CClientScript::POS_END); //associating the ID of the event with the draggable for later retrieval?>
		
		<?php $this->endWidget();?>
	<?php }?>
<?php $this->endWidget();?>

<?php 
$this->widget('zii.widgets.jui.CJuiTabs', array(
	'tabs'=>$employees,
));?>
