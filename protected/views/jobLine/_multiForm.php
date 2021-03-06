<?php /*needs vars for namePrefix and startIndex (the index from which to start numbering lines)*/?>

<?php $div = CHtml::getIdByName($namePrefix . $startIndex . 'item');
$line = $products['model'];
$garmentCost = CHtml::getIdByName($namePrefix . $startIndex . 'garment-cost');?>

<div class="jobLines" id="<?php echo $div;?>">
	<?php echo CHtml::errorSummary($line); ?>	
	<?php 
	$approved = $products['approved'];
	$saved = $products['saved'];
	?>
	Style <?php $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
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
						"var product = data.product;" .
						"var cost = data.productCost;" .
						"var colorOptions = $('<select></select>')" .
							".attr('name', 'color-select')" .
							".attr('class', 'color-select');" .
						"for(var color in colors){
							colorOptions.append($('<option></option>').val(colors[color].ID).html(colors[color].TEXT));
						}" .
						"colorOptions.attr('name', \$('#$div').children('.color-select').attr('name'));" .
						"\$('#".$div."').children('.color-select').replaceWith(colorOptions);" .
						"\$('#".$div."').children('.jobLine').children('.hidden_cost').val(cost);" .
						"onGarmentCostUpdate($('#$div').find('.product-cost'), cost, $('#$div').find('.unit_price'), $('#$div').find('.hidden-price'), $('#$div').find('.garment_part'));" .
						"\$('#".$div."').children('.jobLine').addClass('hidden-size').children('.score_part').attr('disabled', true).val(0);" .
						"for(var size in sizes){
							\$('#".$div."').children('.".$div."' + sizes[size].ID)" .
							".removeClass('hidden-size')" .
							".children('.score_part').removeAttr('disabled');
						}" .
						"\$('#$div').children('.hidden-style').val(ui.item.id);
					});
			}"
		),
	));
	
	Yii::app()->clientScript->registerScript('set-style'.$startIndex, "" .
			"\$('#".CHtml::getIdByName($namePrefix.$startIndex.'style')."').val('".$products['style']."');" , 
	CClientScript::POS_READY);?>
	
	<?php echo CHtml::activeHiddenField($line, 'PRODUCT_ID', array(
		'class'=>'hidden-style',
		'name'=>$namePrefix . "[$startIndex]" . '[PRODUCT_ID]',
	))?>
	
	<?php echo CHtml::activeHiddenField($line, 'ID', array(
		'name'=>$namePrefix . "[$startIndex]" . '[ID]',
	))?>
	
	<?php $colorSelect = CHtml::getIdByName($namePrefix . $startIndex . 'colors');?> 	
	Color <?php echo CHtml::activeDropDownList($line, 'PRODUCT_COLOR', $products['availableColors'], array(
		'id'=>$colorSelect, 
		'disabled'=>(count($products['availableColors']) == 0) || $approved, //only disable if there aren't any colors available. 
		'class'=>'color-select',
		'name'=>$namePrefix . "[$startIndex]" . '[PRODUCT_COLOR]',
	));?>
	
	<?php Yii::app()->clientScript->registerScript('initial-color-data' . $startIndex, "" .
			"$('#".$colorSelect."').data('products', ".($products['product'] ? $products['product'] : 'null').").data('sizes', ".$products['sizes'].");", 
	CClientScript::POS_END);?>
	
	<?php /*need an update function for recalculating totals, field for unit price (editable), total price (hidden), calculated price (link)*/?>
	<?php 
		$priceSelect = CHtml::getIdByName($namePrefix . $startIndex . 'price');
		$garmentEstimate = $line->product ? $line->product->COST : 0;
		$unitEstimate = $line->garmentCount ? ($garmentEstimate + $estimate / $line->garmentCount) : 0;
		if($line->PRICE === null){
			$line->PRICE = $unitEstimate;
		}
	?>
	<div class="price-select-container"> <!-- Don't remove this container. Needed for some JS stuff.-->
		Price Select <?php echo CHtml::activeTextField($line, 'PRICE', array(
			'id'=>$priceSelect,
			'disabled'=>$approved,
			'class'=>'unit_price',
			'name'=>$namePrefix."[$startIndex]".'[PRICE]',
			'onkeyup'=>"recalculateTotal(this, $(this).parent().children('a'), $(this).parent().children('.garment_part'));",
		));?>
		<?php /*when the link is clicked, we want to hide the link and set the value of the input field 
		to the value of the hidden field within the link*/?>
		<a class="estimate-price" href="#" <?php echo ($line->PRICE != $unitEstimate) ? 'style="display: none;"' : '';?> onclick="$(this).parent().children('#<?php echo $priceSelect;?>').val($(this).children('.hidden-price').val()).keyup(); $(this).hide(); return false;">
			<span><?php echo CHtml::encode($formatter->formatCurrency($unitEstimate));?></span>
			<?php echo CHtml::hiddenField(CHtml::getIdByName($namePrefix.$startIndex.'hidden-price'), $unitEstimate, array('class'=>'hidden-price hidden-value'));?>
		</a>
		<?php echo CHtml::hiddenField(CHtml::getIdByName($namePrefix.$startIndex.'total-price'), $line->total - $line->extraLargeFee, array(
			'class'=>'part garment_part',
		));?>
	</div>
	<?php echo CHtml::hiddenField('product-cost', $line->product ? $line->product->COST : 0, array('class'=>'product-cost'));?>
	<div class="clear"></div>
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
			$this->renderPartial('//jobLineSize/_form', array(
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
		$productLine = false;
		$sizeLine = false;
	}
	?>	
	<?php echo CHtml::hiddenField('prefix', $namePrefix, array(
		'class'=>'namePrefix',
	));?>
	<?php echo CHtml::hiddenField('startIndex', $startIndex, array(
		'class'=>'startIndex',
	));?>
	<div class="clear"></div>
	<?php if($saved){?>
		<?php if(!$approved){?>
			<?php echo CHtml::button('Approve', array(
				'class'=>'line_approve',
			));?>		
		<?php } else {?>
			<?php echo CHtml::button('Unapprove', array(
				'class'=>'line_unapprove',				
			));?>
		<?php }?>
	<?php }?>
	
	<?php if(!$approved){?>
		<?php echo CHtml::button('Delete', array(
			'class'=>'line_delete',
		));?>
	<?php }?>
	<div class="line-end"></div>
</div>

<?php Yii::app()->clientScript->registerScript('line-approve', "" .
		"$('.line_approve').live('click', function(event){
			var parent = $(event.target).parent();" .
				"var idList = new Array();" .
				"parent.find('.line_id').each(function(index){
					idList.push(1 * $(this).val());
				});" .
				"$.post(
				'".CHtml::normalizeUrl(array('job/approveLine'))."'," .
				"{
					namePrefix: $(event.target).prev().prev().val()," .
					"startIndex: $(event.target).prev().val()," .
					"idList: idList,
				}," .
				"function(data){" .
					"\nvar id = $(data).attr('id');
					\nparent.replaceWith(data);" .
					"parent = $('#' + id);" .
					"parent.children('.item-select').val(parent.children('.hidden-style').val());					
				}
			);
		})", 
CClientScript::POS_END);?>
<?php Yii::app()->clientScript->registerScript('line-unapprove', "" .
		"$('.line_unapprove').live('click', function(event){
			var parent = $(event.target).parent();" .
				"var idList = new Array();" .
				"parent.find('.line_id').each(function(index){
					idList.push(1 * $(this).val());
				});" .
				"$.post(
				'".CHtml::normalizeUrl(array('job/unapproveLine'))."'," .
				"{
					namePrefix: $(event.target).prev().prev().val()," .
					"startIndex: $(event.target).prev().val()," .
					"idList: idList,
				}," .
				"function(data){
					parent.replaceWith(data);" .
					"var div_id = \$(data).attr('id');" .
					"\$('#' + div_id).children('.item-select').autocomplete({
						'select': function(event, ui){
							\$.getJSON(
							'".CHtml::normalizeUrl(array('product/allowedOptions'))."'," .
							"{
								itemID: ui.item.id," .
								"namePrefix: namePrefix," .
								"count: count,
							}," .
							"function(data){
								var colors = data.colors;" .
								"var sizes = data.sizes;" .
								"var product = data.product;" .
								"var cost = data.productCost;" .
								"var colorOptions = $('<select></select>')" .
									"\n.attr('name', 'color-select')" .
									".attr('class', 'color-select');" .
								"for(var color in colors){
									colorOptions.append($('<option></option>').val(colors[color].ID).html(colors[color].TEXT));
								}" .
								"colorOptions.attr('name', \$('#' + div_id).children('.color-select').attr('name'));" .
								"\$('#' + div_id).children('.color-select').replaceWith(colorOptions);" .
								"\$('#' + div_id).children('.jobLine').addClass('hidden-size').children('.score_part').attr('disabled', true).val(0);" .
								"\$('#' + div_id).children('.jobLine').children('.hidden_cost').val(cost);" .
								"onGarmentCostUpdate($('#' + div_id).find('.product-cost'), cost, $('#' + div_id).find('.unit_price'), $('#' + div_id).find('.hidden-price'), $('#' + div_id).find('.garment_part'));" .
								"for(var size in sizes){
									\$('#' + div_id).children('.' + div_id + sizes[size].ID)" .
									".removeClass('hidden-size')" .
									".parent().children('.score_part').removeAttr('disabled');
								}" .
								"\$('#$div').children('.hidden-style').val(ui.item.id);
							});
						}," .
						"'source': '".CHtml::normalizeUrl(array('product/findProduct', 'response'=>'juijson'))."'
					});" .
					"\$('#' + div_id).children('.item-select').val(\$('#' + div_id).children('.hidden-style').val());
				}
			);
		})", 
CClientScript::POS_END);?>
<?php Yii::app()->clientScript->registerScript('line-delete', "" .
		"$('.line_delete').live('click', function(event){
			var div = $(event.target).parent();" .
			"$(div).children('.jobLine').each(function(){
				var childDiv = this;" .
				"$.ajax({
					'url': '".CHtml::normalizeUrl(array('job/deleteLine'))."'," .
					"'type': 'POST'," .
					"'data': {
						'id': $(childDiv).children('.line_id').val()," .
						"'namePrefix': $(childDiv).children('.linePrefix').val(),
					},
				});
			});" .
			"$(div).remove();
		})",
CClientScript::POS_END);?>