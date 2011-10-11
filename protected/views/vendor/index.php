<?php
$this->breadcrumbs=array(
	'Vendors',
);

$this->menu=array(
	array('label'=>'Create Vendor', 'url'=>array('create')),
	array('label'=>'Manage Vendor', 'url'=>array('admin')),
);
?>

<h1>Vendors</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
	'columns'=>array(
		'nameAbbreviation::ID',
		array(
			'class'=>'CLinkColumn',
			'urlExpression'=>"array('vendor/update', 'id'=>\$data->ID)",
			'labelExpression'=>"\$data->NAME",	
			'header'=>'Name',		
		),
		'WEBSITE:url:Vendor Website',
		'CONTACT_NAME',
		'PHONE',
	),
));?>
