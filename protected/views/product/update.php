<?php
/*$this->breadcrumbs=array(
	'Products'=>array('index'),
	$model->vendorStyle=>array('update','v'=>$model->VENDOR_ID, 'i'=>$model->VENDOR_ITEM_ID),
	'Update',
);*/

$this->menu=array(
	array('label'=>'List Product', 'url'=>array('index')),
	array('label'=>'Create Product', 'url'=>array('create')),
	//array('label'=>'View Product', 'url'=>array('view', 'id'=>$model->ID)),
	array('label'=>'Manage Product', 'url'=>array('admin')),
);
?>

<h1><?php echo $model->vendorStyle; ?></h1>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'statusList'=>$statusList,
	'colorList'=>$colorList,
	'sizeList'=>$sizeList,
	'vendorList'=>$vendorList,
)); ?>