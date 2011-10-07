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
	));?>
	Price Each: <?php echo CHtml::activeTextField($model, 'PRICE', array(
		'name'=>$namePrefix . '[PRICE]',
	))?>
	<?php echo CHtml::activeHiddenField($model, 'ID', array(
		'name'=>$namePrefix . '[ID]',
	));?>
</div>