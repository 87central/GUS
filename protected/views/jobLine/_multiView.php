<?php /*needs vars for namePrefix and startIndex (the index from which to start numbering lines)*/?>

<?php $div = CHtml::getIdByName($namePrefix . $startIndex . 'item');?>
<?php $readonly = isset($readonly) && $readonly;?>
<div class="grid_3 jobGroup">
<div class="jobLines" id="<?php echo $div;?>">
	<span class="title bold">Style</span> <span class="item-select-approved"><?php echo $products['style'];?></span>
	<br>
	<span class="title bold">Color</span> <span class="color-select-approved"><?php $formatter = new Formatter; echo $formatter->formatLookup($products['currentColor']);?></span>
	<br>
	<span class="title bold">Price Each</span> <span class="price-select-approved"><?php echo $formatter->formatCurrency($products['model']->PRICE);?></span>
	
	<?php 
	$index = 0;
	foreach($products['lines'] as $dataLine){
		$continue = false;
		foreach($dataLine as $key=>$dataLineValue){
			if($key == 'productLine') $productLine = $dataLineValue;
			if($key == 'line') $sizeLine = $dataLineValue;
			$continue = $productLine && $sizeLine;
		}	//beats me as to why I needed to do this. For some reason, dataLine thought it was a JobLine instance.
		if($continue){
			$this->renderPartial('//jobLineSize/_view', array(
				'product'=>$productLine,
				'line'=>$sizeLine,
				'lineHiddenPrefix'=>$namePrefix.$startIndex.'sizes'.$index,
				'linePrefix'=>$namePrefix.'['.$startIndex.']'.'[sizes]',
				'index'=>$index,
				'eachDiv'=>CHtml::getIdByName($namePrefix.'['.$startIndex.']'.'[sizes]'.'item'),
				'div'=>$div,
			));
			$index++;
		}
		$productLine = false;
		$sizeLine = false;
	}?>	
	
	<?php echo CHtml::hiddenField('prefix', $namePrefix, array(
		'class'=>'namePrefix',
	));?>
	<?php echo CHtml::hiddenField('startIndex', $startIndex, array(
		'class'=>'startIndex',
	));?>
</div>
</div>