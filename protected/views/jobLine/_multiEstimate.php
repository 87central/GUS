<?php /*needs vars for namePrefix and startIndex (the index from which to start numbering lines)*/?>

<?php $div = CHtml::getIdByName($namePrefix . $startIndex . 'item');
$line = $products['model'];?>

<div class="jobLines" id="<?php echo $div;?>">	
	<?php 
	$approved = $products['approved'];
	$saved = $products['saved'];
	?>
	<div id="line_style">
		Style <?php echo CHtml::radioButtonList('standard-style', null, $products['standardStyles'], array(
			'id'=>'standard_style'.$div,
			'class'=>'standard_style',
		));?>
		
		<?php		 
		Yii::app()->clientScript->registerScript('standard-style-select', "" .
				"\$('.standard_style').live('change', function(){
					var count = $('.jobLines').children('.jobLine').children('.item_qty').size();
					\$.getJSON(
						'".CHtml::normalizeUrl(array('product/allowedOptions'))."'," .
						"{
							itemID: \$(this).val()," .
							"namePrefix: '".$namePrefix."'," .
							"count: count,
						}," .
						"function(data){
							var colors = data.colors;" .
							"var sizes = data.sizes;" .
							"var cost = data.productCost;" .
							"var colorOptions = $('<select></select>')" .
								".attr('name', 'color-select')" .
								".attr('class', 'color-select');" .
							"for(var color in colors){
								colorOptions.append($('<option></option>').val(colors[color].ID).html(colors[color].TEXT));
							}" .
							"\$('#".$div."').children('#line_style').children('.color-select').replaceWith(colorOptions);" .
							"\$('#".$div."').children('.jobLine').children('.hidden_cost').val(cost);" .
							"\$('#".$div."').children('.jobLine').addClass('hidden-size').children('.score_part').attr('disabled', true).val(0);" .
							"for(var size in sizes){
								\$('#".$div."').children('.".$div."' + sizes[size].ID)" .
								".removeClass('hidden-size')" .
								".children('.score_part').removeAttr('disabled');
							}" .
							"\$('#$div').children('.hidden-style').val(\$(this).val());
						}); \$(this).val() ? \$(this).siblings('.item-select').hide() : \$(this).siblings('.item-select').show();"./*we get away setting disabled to the radio button value because the "value" of the custom radio button evaluates to false.*/
				"});", 
		CClientScript::POS_END);?>
		
		<?php Yii::app()->clientScript->registerScript('standard-style-default', "" .
				"$('.standard_style').first().attr('checked', 'checked').change();", 
		CClientScript::POS_END)?>
		
		&nbsp;<?php $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
			'sourceUrl'=>array('product/findProduct', 'response'=>'juijson'),
			'name'=>CHtml::getIdByName($namePrefix).$startIndex.'style',
			'htmlOptions'=>array(
				'class'=>'item-select',
				'disabled'=>$approved,
				'value'=>$products['style'],
			),
			'options'=>array(
				'select'=>"js:function(event, ui){" .
					"var count = $('.jobLines').children('.jobLine').children('.item_qty').size();
					\$.getJSON(
						'".CHtml::normalizeUrl(array('product/allowedOptions'))."'," .
						"{
							itemID: ui.item.id," .
							"namePrefix: '".$namePrefix."'," .
							"count: count,
						}," .
						"function(data){
							var colors = data.colors;" .
							"var sizes = data.sizes;" .
							"var cost = data.productCost;" .
							"var colorOptions = $('<select></select>')" .
								".attr('name', 'color-select')" .
								".attr('class', 'color-select');" .
							"for(var color in colors){
								colorOptions.append($('<option></option>').val(colors[color].ID).html(colors[color].TEXT));
							}" .
							"\$('#".$div."').children('#line_style').children('.color-select').replaceWith(colorOptions);" .
							"\$('#".$div."').children('.jobLine').children('.hidden_cost').val(cost);" .
							"onGarmentCostUpdate($('#$div').find('.product-cost'), cost, $('#$div').find('.unit_price'), $('#$div').find('.hidden-price'), $('#$div').find('.garment_part'));" .
							"\$('#".$div."').children('.jobLine').addClass('hidden-size').children('.score_part').attr('disabled', true).val(0);" .
							"for(var size in sizes){
								\$('#".$div."').children('.".$div."' + sizes[size].ID)" .
								".removeClass('hidden-size')" .
								".children('.score_part').removeAttr('disabled');
							}" .
							"\$('#$div').children('.hidden-style').val(\$(this).val());
						});
				}"
			),
		));
		
		Yii::app()->clientScript->registerScript('set-style'.$startIndex, "" .
				"\$('#".CHtml::getIdByName($namePrefix.$startIndex.'style')."').val('".$products['style']."');" , 
		CClientScript::POS_READY);?>
		
		<?php echo CHtml::hiddenField('hidden-style', $products['style'], array(
			'class'=>'hidden-style',
			'id'=>CHtml::getIdByName($namePrefix.$startIndex.'style-hidden'),
		));?> 
		
		<?php $colorSelect = CHtml::getIdByName($namePrefix . $startIndex . 'colors');?> 	
		Color <?php echo CHtml::dropDownList('colors', $products['currentColor'], $products['availableColors'], array(
			'id'=>$colorSelect, 
			'disabled'=>(count($products['availableColors']) == 0) || $approved, //only disable if there aren't any colors available. 
			'class'=>'color-select',
		));?>		
	</div>	
	<?php 
		$priceSelect = CHtml::getIdByName($namePrefix . $startIndex . 'price');
		$garmentEstimate = $line->product ? $line->product->COST : 0;
		$unitEstimate = $line->garmentCount ? ($garmentEstimate + $estimate / $line->garmentCount) : 0;
		if($line->PRICE === null){
			$line->PRICE = $unitEstimate;
		}
	?>
	<div class="price-select-container"> <!-- Don't remove this container. Needed for some JS stuff.-->
		Unit Price <?php echo CHtml::activeTextField($line, 'PRICE', array(
			'id'=>$priceSelect,
			'disabled'=>true,
			'class'=>'unit_price',
			'name'=>$namePrefix."[$startIndex]".'[PRICE]',
		));?>
		<?php /*when the link is clicked, we want to hide the link and set the value of the input field 
		to the value of the hidden field within the link*/
		?>
		<a href="#" <?php echo ($line->PRICE != $unitEstimate) ? 'style="display: hidden;"' : '';?> onclick="$(this).parent().children('#<?php echo $priceSelect;?>').val($(this).children('.hidden-price').val()).keyup(); $(this).hide(); return false;">
			<span><?php echo CHtml::encode($formatter->formatCurrency($unitEstimate));?></span>
			<?php echo CHtml::hiddenField(CHtml::getIdByName($namePrefix.$startIndex.'hidden-price'), $unitEstimate);?>
		</a>
		<?php echo CHtml::hiddenField(CHtml::getIdByName($namePrefix.$startIndex.'total-price'), $line->total - $line->extraLargeFee, array(
			'class'=>'part garment_part',
		));?>
	</div>
	
	<?php echo CHtml::hiddenField('product-cost', $line->product ? $line->product->COST : 0, array('class'=>'product-cost'));?>
	
	<?php
	$index = 0;
	foreach($products['lines'] as $dataLine){
		foreach($dataLine as $key=>$dataLineValue){
			if($key == 'productLine') $productLine = $dataLineValue;
			if($key == 'line') $sizeLine = $dataLineValue;
		}	//beats me as to why I needed to do this. For some reason, dataLine thought it was a JobLine instance.
		$this->renderPartial('//jobLineSize/_estimate', array(
			'product'=>$productLine,
			'line'=>$sizeLine,
			'lineHiddenPrefix'=>$namePrefix.$startIndex.'sizes'.$index,
			'linePrefix'=>$namePrefix.'['.$startIndex.']'.'[sizes]',
			'index'=>$index,
			'eachDiv'=>CHtml::getIdByName($namePrefix.'['.$startIndex.']'.'[sizes]'.'item'),
			'div'=>$div,
			'approved'=>$approved,
			'onQuantityUpdate'=>"updateLineTotal('".CHtml::normalizeUrl(array('job/garmentCost'))."', $('#$priceSelect'), $('#$priceSelect').parent().children('a'), $('#$priceSelect').parent().children('.garment_part'), $('#$priceSelect').parentsUntil('.jobLines').parent().find('.product-cost'));",
			'formatter'=>$formatter,
		));
		$index++;
	}
	?>	
	<?php echo CHtml::hiddenField('prefix', $namePrefix, array(
		'class'=>'namePrefix',
	));?>
	<?php echo CHtml::hiddenField('startIndex', $startIndex, array(
		'class'=>'startIndex',
	));?>	

	<?php echo CHtml::button('Delete', array(
		'class'=>'line_delete',
	));?>
</div>

<?php Yii::app()->clientScript->registerScript('line-delete', "" .
		"$('.line_delete').live('click', function(event){
			var div = $(event.target).parent();" .
			"$(div).remove();
		})",
CClientScript::POS_END);?>