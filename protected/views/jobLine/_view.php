<div class="jobLine">
	Style: <?php echo $formatter->formatLookup($model->style);?>
	<?php echo CHtml::activeHiddenField($model, 'style', array(
		'name'=>$namePrefix . '[style]',
	));?>
	
	Size: <?php echo $formatter->formatLookup($model->size);?>
	<?php echo CHtml::activeHiddenField($model, 'size', array(
		'name'=>$namePrefix . '[size]',
	));?>
	
	Color: <?php echo $formatter->formatLookup($model->color);?>
	<?php echo CHtml::activeHiddenField($model, 'color', array(
		'name'=>$namePrefix . '[color]',
	));?>
	
	Quantity: <?php echo $model->QUANTITY;?>
	<?php echo CHtml::activeHiddenField($model, 'QUANTITY', array(
		'name'=>$namePrefix . '[QUANTITY]',
	));?>	
	
	Price Each: <?php echo $model->PRICE;?>
	<?php echo CHtml::activeHiddenField($model, 'PRICE', array(
		'name'=>$namePrefix . '[PRICE]',
	));?>
	
	<?php echo CHtml::activeHiddenField($model, 'ID', array(
		'name'=>$namePrefix . '[ID]',
	));?>
	<?php echo CHtml::hiddenField('total', $model->total, array(
		'class'=>'part',
	));?>
</div>