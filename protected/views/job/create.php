<?php
$this->breadcrumbs=array(
	'Jobs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Job', 'url'=>array('index')),
	array('label'=>'Manage Job', 'url'=>array('admin')),
);
?>

<h1>New Job</h1>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'customerList'=>$customerList,
	'newCustomer'=>$newCustomer,
	'leaders'=>$leaders,
	'printers'=>$printers,
	'styles'=>$styles,
	'colors'=>$colors,
	'sizes'=>$sizes,
	'print'=>$print,
)); ?>