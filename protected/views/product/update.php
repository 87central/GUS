<?php
$this->breadcrumbs=array(
	'Products'=>array('index'),
	$model->ID=>array('view','id'=>$model->ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Product', 'url'=>array('index')),
	array('label'=>'Create Product', 'url'=>array('create')),
	array('label'=>'View Product', 'url'=>array('view', 'id'=>$model->ID)),
	array('label'=>'Manage Product', 'url'=>array('admin')),
);
?>

<h1>Update Product <?php echo $model->ID; ?></h1>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'statusList'=>$statusList,
	'colorList'=>$colorList,
	'styleList'=>$styleList,
	'sizeList'=>$sizeList,
	'vendorList'=>$vendorList,
)); ?>