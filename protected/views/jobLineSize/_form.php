<?php 
$namePrefix = $linePrefix . "[$index]";
$nameHiddenPrefix = $lineHiddenPrefix . $index;
$total = CHtml::getIdByName($nameHiddenPrefix . 'total');
$qty = CHtml::getIdByName($namePrefix . '[QUANTITY]');
$cost = CHtml::getIdByName($nameHiddenPrefix . '[PRICE]');
$xl = CHtml::getIdByName($nameHiddenPrefix . '[isExtraLarge]');
$xlTotal = CHtml::getIdByName($nameHiddenPrefix . 'isExtraLargeTotal');
$totalJS = '#'.$total;
$qtyJS = '#'.$qty;
$costJS = '#'.$cost;
$xlJS = '#'.$xl;
$xlTotalJS = '#' . $xlTotal;
?>
<div class="jobLine <?php echo ($line->JOB_LINE_ID == null) ? 'hidden-size' : '';?> <?php echo $div.$product->SIZE;?>" id="<?php echo $eachDiv;?>">
	<?php echo CHtml::errorSummary($line); ?>
	<?php echo CHtml::label($product->size->TEXT, CHtml::getIdByName($namePrefix . '[QUANTITY]'));?>
	<?php echo CHtml::activeTextField($line, 'QUANTITY', array(
		'name'=>$namePrefix . '[QUANTITY]',
		'onkeyup'=>"$('".$totalJS."').val((1 * $('".$qtyJS."').val()) * $('".$costJS."').val()).change(); $('$xlTotalJS').val($('$qtyJS').val() * 1 * $('$xlJS').val()).change(); ".$onQuantityUpdate,
		'class'=>'score_part item_qty',
		'size'=>5,
		'disabled'=>($line->JOB_LINE_ID == null) || $approved, //only disable if the product doesn't seem to exist.
	));?>
	
	<?php $xlFee = $line->isExtraLarge;
	if($xlFee){?>
		<p class="note">* A <?php echo $formatter->formatCurrency($xlFee);?> per garment fee will be added to the total for this size.</p>
		<?php echo CHtml::hiddenField($xl, $xlFee, array(
			'id'=>$xl,
		));?>
		<?php echo CHtml::hiddenField($xlTotal, $xlFee * $line->QUANTITY, array(
			'id'=>$xlTotal,
			'class'=>'part',
		));?>
	<?php }?>
	
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