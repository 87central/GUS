<?php /*vars for JS calculations*/?>
<?php $total = '#'.CHtml::getIdByName($namePrefix);?>
<?php $qty = '#'.CHtml::getIdByName($namePrefix . '[QUANTITY]');?>
<?php $price = '#'.CHtml::getIdByName($namePrefix . '[PRICE]');?>

<div class="jobLine">
	Style: <?php echo CHtml::activeDropDownList($model, 'style', $styles, array(
		'name'=>$namePrefix . '[style]',
	));?>
	Size: <?php echo CHtml::activeDropDownList($model, 'size', $sizes, array(
		'name'=>$namePrefix . '[size]',
	));?>
	Color: <?php echo CHtml::activeDropDownList($model, 'color', $colors, array(
		'name'=>$namePrefix . '[color]',
	));?>
	Quantity: <?php echo CHtml::activeTextField($model, 'QUANTITY', array(
		'name'=>$namePrefix . '[QUANTITY]',
		'onkeyup'=>"$('".$total."').val((1 * $('".$qty."').val()) * $('".$price."').val()).change();",
		'class'=>'score_part',
	));?>
	Price Each: <?php echo CHtml::activeTextField($model, 'PRICE', array(
		'name'=>$namePrefix . '[PRICE]',
		'onkeyup'=>"$('".$total."').val((1 * $('".$qty."').val()) * $('".$price."').val()).change();"
	))?>
	<?php echo CHtml::activeHiddenField($model, 'ID', array(
		'name'=>$namePrefix . '[ID]',
	));?>
	<?php echo CHtml::hiddenField('total', $model->total, array(
		'class'=>'part',
		'id'=>CHtml::getIdByName($namePrefix),
		'readonly'=>'readonly',
	));?>
</div>