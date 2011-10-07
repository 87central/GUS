<?php
$this->pageTitle = Yii::app()->user->name . ' - ' . 'Schedule';
?>

<h1>SCHEDULE</h1>

<div class="separator"></div>

<h2>UNSCHEDULED JOBS</h2>
<div id="unscheduled">
	<?php foreach($unscheduled as $event){?>
		<?php $this->beginWidget('zii.widgets.jui.CJuiDraggable', array(
			'htmlOptions'=>array(
				'class'=>'calendar_item',
			),
		));?>
		
		<div class="unscheduled">
			<?php $this->renderPartial('//job/_eventDetail', array(
				'item'=>$event,
			));?>
		</div>
		
		<?php $this->endWidget();?>
	<?php }?>
</div>

<?php 
$tabs = array();
foreach($employees as $emp){
	$tabs[$emp->FIRST] = array('event/employeeSchedule', 'employee_id'=>$emp->ID);
}
$this->widget('zii.widgets.jui.CJuiTabs', array(
	'tabs'=>$tabs,
));?>
