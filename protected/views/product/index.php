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
	$sizes = $model->allowedSizes;
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
	$colors = $model->allowedColors;
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

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
	'formatter'=>new Formatter,
	'columns'=>array(
		array(
			'class'=>'CLinkColumn',
			'header'=>'Item',
			'urlExpression'=>"array('product/update', 'v'=>\$data->VENDOR_ID, 'i'=>\$data->VENDOR_ITEM_ID)",
			'labelExpression'=>"\$data->vendorStyle",
		),
		/*array(
			'header'=>'Available Sizes',
			'type'=>'raw',
			'value'=>"listSizes(\$data)",			
		),
		array(
			'header'=>'Available Colors',
			'type'=>'raw',
			'value'=>"listColors(\$data)",
		),*/
		array(
			'class'=>'CButtonColumn',
			'viewButtonImageUrl'=>false,
			'viewButtonLabel'=>'',
			'viewButtonUrl'=>'',
			'updateButtonImageUrl'=>false,
			'updateButtonLabel'=>'',
			'updateButtonUrl'=>'',
			'deleteButtonUrl'=>"CHtml::normalizeUrl(array('product/delete', 'v'=>\$data->VENDOR_ID, 'i'=>\$data->VENDOR_ITEM_ID))",
		),
	),
));?>
