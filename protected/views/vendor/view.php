<?php
$this->breadcrumbs=array(
	'Vendors'=>array('index'),
	$model->NAME,
);

$this->menu=array(
	array('label'=>'List Vendor', 'url'=>array('index')),
	array('label'=>'Create Vendor', 'url'=>array('create')),
	array('label'=>'Update Vendor', 'url'=>array('update', 'id'=>$model->ID)),
	array('label'=>'Delete Vendor', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Vendor', 'url'=>array('admin')),
);
?>

<h1>View Vendor #<?php echo $model->ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ID',
		'NAME',
		'EMAIL',
		'PHONE',
	),
)); ?>
