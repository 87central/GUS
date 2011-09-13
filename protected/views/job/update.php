<?php
$this->breadcrumbs=array(
	'Jobs'=>array('index'),
	$model->ID=>array('view','id'=>$model->ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Job', 'url'=>array('index')),
	array('label'=>'Create Job', 'url'=>array('create')),
	array('label'=>'View Job', 'url'=>array('view', 'id'=>$model->ID)),
	array('label'=>'Manage Job', 'url'=>array('admin')),
);
?>

<h1>Update Job <?php echo $model->ID; ?></h1>

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