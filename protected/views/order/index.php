<?php
function listJobs($model){
	if(count($model->jobs) == 0){
		return 'No Jobs';
	}
	ob_start();
	
	foreach($model->jobs as $job){
		echo CHtml::link($job->NAME, array('job/update', 'id'=>$job->ID));
		echo '<br/>';
	}
	$result = ob_get_contents();
	ob_end_clean();
	return $result;
} 
?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$createdProvider,
	'columns'=>array(
		array(
			'class'=>'CLinkColumn',
			'labelExpression'=>"\$data->name",
			'urlExpression'=>"array('order/view', 'id'=>\$data->ID)",
			'header'=>'New Orders',
		),
		'DATE::Creation Date',
		array(
			'header'=>'Jobs',
			'value'=>"listJobs(\$data);",
			'type'=>'raw',
		),
		array(
			'class'=>'CLinkColumn',
			'label'=>'Place Order',
			'urlExpression'=>"array('order/place', 'id'=>\$data->ID, 'view'=>'index')",
		)
	),
));?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$orderedProvider,
	'columns'=>array(
		array(
			'class'=>'CLinkColumn',
			'labelExpression'=>"\$data->name",
			'urlExpression'=>"array('order/view', 'id'=>\$data->ID)",
			'header'=>'Check In',
		),
		'placed::Date Ordered',
		array(
			'header'=>'Jobs',
			'value'=>"listJobs(\$data);",
			'type'=>'raw',
		),
		array(
			'class'=>'CLinkColumn',
			'label'=>'Check In',
			'urlExpression'=>"array('order/checkin', 'id'=>\$data->ID, 'view'=>'index')",
		),
		array(
			'class'=>'CLinkColumn',
			'label'=>'Short',
			'urlExpression'=>"array('order/update', 'id'=>\$data->ID)",
		),
	)
));?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$arrivedProvider,
	'columns'=>array(
		array(
			'class'=>'CLinkColumn',
			'labelExpression'=>"\$data->name",
			'urlExpression'=>"array('order/view', 'id'=>\$data->ID)",
			'header'=>'Check In',
		),
		'placed::Date Ordered',
		'arrived::Date Received',
		array(
			'header'=>'Jobs',
			'value'=>"listJobs(\$data);",
			'type'=>'raw',
		),
		array(
			'class'=>'CLinkColumn',
			'label'=>'Short',
			'urlExpression'=>"array('order/update', 'id'=>\$data->ID)",
		),
	)
));?>