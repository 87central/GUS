<?php
$this->breadcrumbs=array(
	'Customers',
);

$this->menu=array(
	array('label'=>'Create Customer', 'url'=>array('create')),
	array('label'=>'Manage Customer', 'url'=>array('admin')),
);
?>

<h1>Customers</h1>
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
	'dataProvider'=>$dataProvider,
	'columns'=>array(
		array(
			'class'=>'CLinkColumn',
			'header'=>'Name',
			'labelExpression'=>"\$data->FIRST.' '.\$data->LAST",
			'urlExpression'=>"array('customer/update', 'id'=>\$data->ID)",
		),
		'COMPANY', 
		'EMAIL',
		'PHONE',
		array(
			'header'=>'Jobs',
			'value'=>"listJobs(\$data);",
			'type'=>'raw',
		),
		'TERMS',
		'NOTES',
	),
));?>
