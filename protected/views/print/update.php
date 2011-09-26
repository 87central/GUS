<?php
$this->breadcrumbs=array(
	'Print Jobs'=>array('index'),
	$model->ID=>array('view','id'=>$model->ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List PrintJob', 'url'=>array('index')),
	array('label'=>'Create PrintJob', 'url'=>array('create')),
	array('label'=>'View PrintJob', 'url'=>array('view', 'id'=>$model->ID)),
	array('label'=>'Manage PrintJob', 'url'=>array('admin')),
);
?>

<h1>Update PrintJob <?php echo $model->ID; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>