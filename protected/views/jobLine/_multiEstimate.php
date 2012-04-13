<?php /*needs vars for namePrefix and startIndex (the index from which to start numbering lines)*/?>

<?php $div = CHtml::getIdByName($namePrefix . $startIndex . 'item');?>

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
		$selectId =  
		Yii::app()->clientScript->registerScript('standard-style-select', "" .
				"\$('.standard_style').live('change', function(){
					var count = $('.jobLines').children('.jobLine').children('.part').size();
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
							"var products = data.products;" .
							"var cost = data.productCost;" .
							"\$('#".$div."').children('.jobLine').children('.line-product').val(null);" .
							"var colorOptions = $('<select></select>')" .
								".attr('name', 'color-select')" .
								".attr('class', 'color-select')" .
								".change(function() {" .
									"for(var size in sizes){
										\$('#".$div."').children('.".$div."' + sizes[size].ID).children('.line-product').val(products[\$(colorOptions).val()][sizes[size].ID].ID);
									}" .
								"});" .
							"for(var color in colors){
								colorOptions.append($('<option></option>').val(colors[color].ID).html(colors[color].TEXT));
							}" .
							"\$('#".$div."').children('#line_style').children('.color-select').replaceWith(colorOptions);" .
							"\$('#".$div."').children('.jobLine').children('.hidden_cost').val(cost);" .
							"\$('#".$div."').children('.jobLine').addClass('hidden-size').children('.score_part').attr('disabled', true).val(0);" .
							"for(var size in sizes){" .
								"var firstColor = null;" .
								"for(var color in colors){
									firstColor = color;" .
									"break;
								}
								\$('#".$div."').children('.".$div."' + sizes[size].ID)" .
								".removeClass('hidden-size')" .
								".children('.line-product').val(products[colors[firstColor].ID][sizes[size].ID].ID)" .
								".parent().children('.score_part').removeAttr('disabled');
							}
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
					"var count = $('.jobLines').children('.jobLine').children('.part').size();
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
							"var products = data.products;" .
							"var cost = data.productCost;" .
							"\$('#".$div."').children('.jobLine').children('.line-product').val(null);" .
							"var colorOptions = $('<select></select>')" .
								".attr('name', 'color-select')" .
								".attr('class', 'color-select')" .
								".change(function() {" .
									"for(var size in sizes){
										\$('#".$div."').children('.".$div."' + sizes[size].ID).children('.line-product').val(products[\$(colorOptions).val()][sizes[size].ID].ID);
									}" .
								"});" .
							"for(var color in colors){
								colorOptions.append($('<option></option>').val(colors[color].ID).html(colors[color].TEXT));
							}" .
							"\$('#".$div."').children('#line_style').children('.color-select').replaceWith(colorOptions);" .
							"\$('#".$div."').children('.jobLine').children('.hidden_cost').val(cost);" .
							"\$('#".$div."').children('.jobLine').addClass('hidden-size').children('.score_part').attr('disabled', true).val(0);" .
							"for(var size in sizes){" .
								"var firstColor = null;" .
								"for(var color in colors){
									firstColor = color;" .
									"break;
								}
								\$('#".$div."').children('.".$div."' + sizes[size].ID)" .
								".removeClass('hidden-size')" .
								".children('.line-product').val(products[colors[firstColor].ID][sizes[size].ID].ID)" .
								".parent().children('.score_part').removeAttr('disabled');
							}
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
		<?php Yii::app()->clientScript->registerScript('initial-color-data' . $startIndex, "" .
				"$('#".$colorSelect."').data('products', ".$products['products'].").data('sizes', ".$products['sizes'].");", 
		CClientScript::POS_END);?>
		<?php Yii::app()->clientScript->registerScript('initial-color-select' . $startIndex, "" .
				"$('#".$colorSelect."').change(function(){
					var sizes = $(this).data('sizes');" .
					"var products = $(this).data('products');" .
					"for(var size in sizes){
						\$('#".$div."').children('.".$div."' + sizes[size].ID).children('.line-product').val(products[\$(this).val()][sizes[size].ID].ID);
					}
				});",
		CClientScript::POS_END);?>
	</div>	
	
	<?php
	foreach($products['lines'] as $dataLine){
		$product = $dataLine['product'];
		$line = $dataLine['line'];
		$lineHiddenPrefix = $namePrefix . $startIndex;
		$linePrefix = $namePrefix . '['.$startIndex++.']';
		$eachDiv = CHtml::getIdByName($linePrefix.'item');
		?>
		<div class="jobLine <?php echo ($product->ID == null) ? 'hidden-size' : '';?> <?php echo $div.$product->SIZE;?>" id="<?php echo $eachDiv;?>">
			<?php /*vars for JS calculations*/?>
			<?php $total = '#'.CHtml::getIdByName($lineHiddenPrefix . 'total');?>
			<?php $qty = '#'.CHtml::getIdByName($linePrefix . '[QUANTITY]');?>
			<?php $price = '#'.CHtml::getIdByName($linePrefix . '[PRICE]');?>

			<?php echo CHtml::label($product->size->TEXT, CHtml::getIdByName($linePrefix . '[QUANTITY]'));?>
			<?php echo CHtml::activeTextField($line, 'QUANTITY', array(
				'name'=>$linePrefix . '[QUANTITY]',
				'onkeyup'=>"$('".$total."').val((1 * $('".$qty."').val()) * $('".$price."').val()).change();",
				'class'=>'score_part item_qty',
				'size'=>5,
				'disabled'=>($product->ID == null) || $approved, //only disable if the product doesn't seem to exist.
			));?>
			
			<?php echo CHtml::activeHiddenField($line, 'PRICE', array(
				'name'=>$linePrefix . '[PRICE]',
				'onchange'=>"$('".$total."').val((1 * $('".$qty."').val()) * $('".$price."').val()).change();",
				'class'=>'hidden_cost',
				'value'=>$product->COST, 
			));?>
			<?php /*the "PRICE" of a job line, then, is actually the cost to buy
			the garment from the manufacturer.*/?>
			
			<?php echo CHtml::activeHiddenField($line, 'total', array(
				'class'=>'part',
				'readonly'=>'readonly',
				'id'=>CHtml::getIdByName($lineHiddenPrefix . 'total'),
			));?>
			
			<?php echo CHtml::activeHiddenField($line, 'ID', array(
				'name'=>$linePrefix . '[ID]',
				'class'=>'line_id',
			));?>
			
			<?php echo CHtml::activeHiddenField($line, 'PRODUCT_ID', array(
				'name'=>$linePrefix . '[PRODUCT_ID]',
				'class'=>'line-product',
				'value'=>$product->ID,
			));?>
			
			<?php echo CHtml::hiddenField('linePrefix', $linePrefix, array(
				'class'=>'linePrefix',
				'id'=>CHtml::getIdByName($lineHiddenPrefix . 'linePrefix'),
			));?>
		</div>
		<?php
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