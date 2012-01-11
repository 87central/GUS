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

<?php function listSizes($model){
	$sizes = Product::getAllowedSizes($model->VENDOR_ITEM_ID);
	if(count($sizes) == 0){
		return 'None';
	} else {
		ob_start();
		
		foreach($sizes as $size){
			echo CHtml::encode($size->TEXT);
			echo '<br/>';
		}
		
		return ob_get_clean();
	}
}

function listColors($model){
	$colors = Product::getAllowedColors($model->VENDOR_ITEM_ID);
	if(count($colors) == 0){
		return 'None';
	} else {
		ob_start();
		
		foreach($colors as $color){
			echo CHtml::encode($color->TEXT);
			echo '<br/>';
		}
		
		return ob_get_clean();
	}
}?>

<?php echo CJSON::encode($dataProvider->data);?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
	'formatter'=>new Formatter,
	'columns'=>array(
		array(
			'class'=>'CLinkColumn',
			'header'=>'Item',
			'urlExpression'=>"array('product/update', 'id'=>\$data->ID)",
			'labelExpression'=>"\$data->vendorStyle",
		),
		array(
			'header'=>'Available Sizes',
			'type'=>'raw',
			'value'=>"listSizes(\$data)",			
		),
		array(
			'header'=>'Available Colors',
			'type'=>'raw',
			'value'=>"listColors(\$data)",
		),
	),
));?>
