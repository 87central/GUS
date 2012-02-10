<?php
$this->pageTitle = Yii::app()->user->name . ' - ' . 'Dashboard';
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . '/css/job_dashboard.css');
?>
<!--table goes here-->
<?php 
$this->widget('zii.widgets.grid.CGridView', array( 
	'dataProvider'=>$dataProvider,
	'formatter'=>new CFormatter,
	'columns'=>array(
		array(
			'name'=>'pickUpDate',
			'value'=>"date('l', strtotime(\$data->pickUpDate));",
			'header'=>'Pick-Up',
		),
		array(
			'class'=>'CLinkColumn',
			'header'=>'Open Jobs',
			'labelExpression'=>"\$data->RUSH ? '<span class=\"warning\">RUSH</span>&nbsp;' : '' . \$data->NAME;",
			'urlExpression'=>"CHtml::normalizeUrl(array('job/view', 'id'=>\$data->ID));",
		),
		array(
			'header'=>'Status',
			'value'=>'',
		),
		array(
			'header'=>'Print',
			'name'=>'printDate',
			'value'=>"date('l', strtotime(\$data->printDate));",
		),
		array(
			'header'=>'Due',
			'name'=>'dueDate',
			'value'=>"date('n/j', strtotime(\$data->dueDate));"
		),
		'totalPasses',
		array(
			'header'=>'Art',
			'value'=>"CHtml::image(Yii::app()->request->baseUrl . '/images/' . (\$data->hasArt ? 'checked.png' : 'unchecked.png'));",
			'type'=>'raw',
		),
		array(
			'header'=>'Sizes',
			'value'=>"CHtml::image(Yii::app()->request->baseUrl . '/images/' . (\$data->hasSizes ? 'checked.png' : 'unchecked.png'));",
			'type'=>'raw',
		)
	)
));
?>
<?php 
$this->widget('application.components.Menu', array(
	'items'=>array(
		array('label'=>'+ New Job', 'url'=>array('job/create')),
		array('label'=>'All Jobs', 'url'=>array('job/list')),
		array('label'=>'Past Jobs', 'url'=>array('job/archive')),
	),
	'id'=>'job_menu',
));
?>
<h6>Calendar</h6>
<?php for($i = 0; $i < 4; $i++){?>
	<div id="cal<?php echo $i;?>" class="cal_container">
		<?php $this->widget('application.widgets.CalendarWidget', array(
			'droppable'=>false,
			'itemView'=>'//job/_eventDetail',
			'headerView'=>'//job/_dayHeader',
			'data'=>$calendarData[$i],
		));?>
	</div>
<?php }?>