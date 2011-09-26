<?php
$this->breadcrumbs=array(
	'Print Jobs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List PrintJob', 'url'=>array('index')),
	array('label'=>'Manage PrintJob', 'url'=>array('admin')),
);
?>

<h1>Create PrintJob</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>