<?php
$this->breadcrumbs=array(
	'Jobs'=>array('index'),
	$model->ID,
);

$this->menu=array(
	array('label'=>'List Job', 'url'=>array('index')),
	array('label'=>'Create Job', 'url'=>array('create')),
	array('label'=>'Update Job', 'url'=>array('update', 'id'=>$model->ID)),
	array('label'=>'Delete Job', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Job', 'url'=>array('admin')),
);
?>

<h1>View Job #<?php echo $model->ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ID',
		'CUSTOMER_ID',
		'LEADER_ID',
		'DESCRIPTION',
		'NOTES',
		'ISSUES',
		'RUSH',
		'SET_UP_FEE',
		'SCORE',
		'QUOTE',
	),
)); ?>
