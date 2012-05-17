<?php 
$namePrefix = $linePrefix . "[$index]";
$nameHiddenPrefix = $lineHiddenPrefix . $index;
$total = CHtml::getIdByName($nameHiddenPrefix . 'total');
$qty = CHtml::getIdByName($namePrefix . '[QUANTITY]');
$cost = CHtml::getIdByName($nameHiddenPrefix . '[PRICE]');
$totalJS = '#'.$total;
$qtyJS = '#'.$qty;
$costJS = '#'.$cost;
?>
<div class="jobLine <?php echo ($product->PRODUCT_ID == null) ? 'hidden-size' : '';?> <?php echo $div.$product->SIZE;?>" id="<?php echo $eachDiv;?>">
	<?php echo CHtml::label($product->size->TEXT, CHtml::getIdByName($namePrefix . '[QUANTITY]'));?>
	<?php echo CHtml::activeTextField($line, 'QUANTITY', array(
		'name'=>$namePrefix . '[QUANTITY]',
		'onkeyup'=>"$('".$totalJS."').val((1 * $('".$qtyJS."').val()) * $('".$costJS."').val()).change(); ".$onQuantityUpdate,
		'class'=>'score_part item_qty',
		'size'=>5,
		'disabled'=>($product->PRODUCT_ID == null) || $approved, //only disable if the product doesn't seem to exist.
	));?>
	
	<?php echo CHtml::hiddenField(CHtml::getIdByName($nameHiddenPrefix . '[PRICE]'), $line->unitCost, array(
		'onchange'=>"$('".$totalJS."').val((1 * $('".$qtyJS."').val()) * $('".$costJS."').val()).change();",
		'class'=>'hidden_cost', 
	));?>
	<?php /*the "PRICE" of a job line, then, is actually the cost to buy
	the garment from the manufacturer.*/?>
	
	<?php echo CHtml::activeHiddenField($line, 'total', array(
		'readonly'=>'readonly',
		'id'=>CHtml::getIdByName($nameHiddenPrefix . 'total'),
		'name'=>CHtml::getIdByName($nameHiddenPrefix . 'total'),
	));?>
	
	<?php echo CHtml::activeHiddenField($line, 'JOB_LINE_ID', array(
		'name'=>$namePrefix . '[JOB_LINE_ID]',
		'class'=>'line_id',
	));?>
	
	<?php echo CHtml::activeHiddenField($line, 'SIZE', array(
		'name'=>$namePrefix . '[SIZE]',
		'class'=>'line-size',
	));?>
	
	<?php echo CHtml::hiddenField('namePrefix', $namePrefix, array(
		'class'=>'linePrefix',
		'id'=>CHtml::getIdByName($nameHiddenPrefix . 'linePrefix'),
	));?>
</div>