<?php
$this->breadcrumbs=array(
	'Invoices'=>array('index'),
	$model->TITLE=>array('view','id'=>$model->ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Invoice', 'url'=>array('index')),
	array('label'=>'Create Invoice', 'url'=>array('create')),
	array('label'=>'View Invoice', 'url'=>array('view', 'id'=>$model->ID)),
	array('label'=>'Manage Invoice', 'url'=>array('admin')),
);
?>

<h1>Update Invoice <?php echo $model->ID; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>