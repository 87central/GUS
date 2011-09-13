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

<h1>Create Job</h1>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'customerList'=>$customerList,
	'newCustomer'=>$newCustomer,
	'newCustomerUser'=>$newCustomerUser,
	'users'=>$users,
	'styles'=>$styles,
	'colors'=>$colors,
	'sizes'=>$sizes,
)); ?>