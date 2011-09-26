<?php
$this->breadcrumbs=array(
	'Print Jobs'=>array('index'),
	$model->ID,
);

$this->menu=array(
	array('label'=>'List PrintJob', 'url'=>array('index')),
	array('label'=>'Create PrintJob', 'url'=>array('create')),
	array('label'=>'Update PrintJob', 'url'=>array('update', 'id'=>$model->ID)),
	array('label'=>'Delete PrintJob', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage PrintJob', 'url'=>array('admin')),
);
?>

<h1>View PrintJob #<?php echo $model->ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ID',
		'PASS',
		'ART',
		'COST',
		'APPROVAL_DATE',
		'APPROVAL_USER',
	),
)); ?>
