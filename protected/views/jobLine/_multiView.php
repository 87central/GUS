<?php /*needs vars for namePrefix and startIndex (the index from which to start numbering lines)*/?>

<?php $div = CHtml::getIdByName($namePrefix . $startIndex . 'item');?>
<?php $readonly = isset($readonly) && $readonly;?>

<div class="jobLines" id="<?php echo $div;?>">
	Style <span class="item-select-approved"><?php echo $products['style'];?></span>
	
	Color <span class="color-select-approved"><?php $formatter = new Formatter; echo $formatter->formatLookup($products['currentColor']);?></span>
	
	<?php foreach($products['lines'] as $dataLine){
		$product = $dataLine['product'];
		$line = $dataLine['line'];
		$lineHiddenPrefix = $namePrefix . $startIndex;
		$linePrefix = $namePrefix . '['.$startIndex++.']';
		$eachDiv = CHtml::getIdByName($linePrefix.'item');
		?>
		<div class="jobLine <?php echo ($product->ID == null) ? 'hidden-size' : '';?> <?php echo $div.$product->SIZE;?>" id="<?php echo $eachDiv;?>">
			<?php echo CHtml::label($product->size->TEXT, CHtml::getIdByName($linePrefix . '[QUANTITY]'));?>
			<?php echo CHtml::activeHiddenField($line, 'QUANTITY', array(
				'name'=>$linePrefix . '[QUANTITY]',
				'class'=>'score_part item_qty',
			));?>
			<?php echo CHtml::encode($line->QUANTITY);?>
			
			<?php echo CHtml::activeHiddenField($line, 'PRICE', array(
				'name'=>$linePrefix . '[PRICE]',
				'class'=>'hidden_cost',
			));?>
			
			<?php echo CHtml::activeHiddenField($line, 'total', array(
				'class'=>'part',
				'readonly'=>'readonly',
				'id'=>CHtml::getIdByName($lineHiddenPrefix . 'total'),
			));?>
			
			<?php echo CHtml::activeHiddenField($line, 'ID', array(
				'name'=>$linePrefix . '[ID]',
				'class'=>'line_id',
			));?>
			
			<?php echo CHtml::activeHiddenField($line, 'color', array(
				'name'=>$linePrefix . '[color]',
				'class'=>'line-color',
			));?>
			
			<?php echo CHtml::activeHiddenField($line, 'size', array(
				'name'=>$linePrefix . '[size]',
				'class'=>'line-size',
				'value'=>$product->SIZE,				
			));?>
			
			<?php echo CHtml::activeHiddenField($line, 'style', array(
				'name'=>$linePrefix . '[style]',
				'class'=>'line-style',
			));?>
			
			<?php echo CHtml::hiddenField('linePrefix', $linePrefix, array(
				'class'=>'linePrefix',
				'id'=>CHtml::getIdByName($lineHiddenPrefix . 'linePrefix'),
			));?>
		</div>
		
		<?php
	}?>	
	
	<?php echo CHtml::hiddenField('prefix', $namePrefix, array(
		'class'=>'namePrefix',
	));?>
	<?php echo CHtml::hiddenField('startIndex', $startIndex, array(
		'class'=>'startIndex',
	));?>
</div>