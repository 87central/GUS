<?php
$this->breadcrumbs=array(
	'Products',
);

$this->menu=array(
	array('label'=>'Create Product', 'url'=>array('create')),
	array('label'=>'Manage Product', 'url'=>array('admin')),
);
?>

<h1>Products</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
	'formatter'=>new Formatter,
	'columns'=>array(
		array(
			'class'=>'CLinkColumn',
			'header'=>'ID',
			'urlExpression'=>"array('product/update', 'id'=>\$data->ID)",
			'labelExpression'=>"\$data->ID",
		),
		'STYLE:lookup',
		'SIZE:lookup',
		'COLOR:lookup',
		'AVAILABLE',
	),
));?>
