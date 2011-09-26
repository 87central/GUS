<?php
$this->breadcrumbs=array(
	'Print Jobs',
);

$this->menu=array(
	array('label'=>'Create PrintJob', 'url'=>array('create')),
	array('label'=>'Manage PrintJob', 'url'=>array('admin')),
);
?>

<h1>Print Jobs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
