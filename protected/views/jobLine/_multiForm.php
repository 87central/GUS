<?php /*needs vars for namePrefix and startIndex (the index from which to start numbering lines)*/?>

<?php $div = CHtml::getIdByName($namePrefix . $startIndex . 'item');?>

<div class="jobLines" id="<?php echo $div;?>">	
	<?php 
	$approved = $products['approved'];
	$saved = $products['saved'];
	?>
	Style <?php $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
		'sourceUrl'=>array('product/findProduct', 'response'=>'juijson'),
		'name'=>$namePrefix.$startIndex.'style',
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
						"var style = data.style;" .
						"\$('#".$div."').children('.jobLine').children('.line-style').val(style.ID);" .
						"var colorOptions = $('<select></select>')" .
							".attr('name', 'color-select')" .
							".attr('class', 'color-select')" .
							".change(function() {
								\$('#".$div."').children('.jobLine').children('.line-color').val(\$(colorOptions).val());" .
							"});" .
						"for(var color in colors){
							colorOptions.append($('<option></option>').val(colors[color].ID).html(colors[color].TEXT));
						}" .
						"\$('#".$div."').children('.color-select').replaceWith(colorOptions);" .
						"\$('#".$div."').children('.jobLine').children('.score_part').attr('disabled', true).val(0);" .
						"for(var size in sizes){
							\$('#".$div."').children('.".$div."' + sizes[size].ID).children('.score_part').removeAttr('disabled');
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
	
	Color <?php echo CHtml::dropDownList('colors', $products['currentColor'], $products['availableColors'], array(
		'id'=>CHtml::getIdByName($namePrefix . 'colors'), 
		'disabled'=>(count($products['availableColors']) == 0) || $approved, //only disable if there aren't any colors available. 
		'class'=>'color-select',
		'onchange'=>"\$('#".$div."').children('.jobLine').children('.line-color').val(\$(this).val());",
	));?>
	
	<?php
	foreach($products['lines'] as $dataLine){
		$product = $dataLine['product'];
		$line = $dataLine['line'];
		$lineHiddenPrefix = $namePrefix . $startIndex;
		$linePrefix = $namePrefix . '['.$startIndex++.']';
		$eachDiv = CHtml::getIdByName($linePrefix.'item');
		?>
		<div class="jobLine <?php echo $div.$product->SIZE;?>" id="<?php echo $eachDiv;?>">
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
				'value'=>0, //temporary
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
	}
	?>	
	<?php echo CHtml::hiddenField('prefix', $namePrefix, array(
		'class'=>'namePrefix',
	));?>
	<?php echo CHtml::hiddenField('startIndex', $startIndex, array(
		'class'=>'startIndex',
	));?>
	
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
								"var style = data.style;" .
								"\$('#' + div_id).children('.jobLine').children('.line-style').val(style.ID);" .
								"var colorOptions = $('<select></select>')" .
									"\n.attr('name', 'color-select')" .
									".attr('class', 'color-select')" .
									".change(function() {
										\$('#' + div_id).children('.jobLine').children('.line-color').val(\$(colorOptions).val());" .
									"});" .
								"for(var color in colors){
									colorOptions.append($('<option></option>').val(colors[color].ID).html(colors[color].TEXT));
								}" .
								"\$('#' + div_id).children('.color-select').replaceWith(colorOptions);" .
								"\$('#' + div_id).children('.jobLine').children('.score_part').attr('disabled', true).val(0);" .
								"for(var size in sizes){
									\$('#' + div_id).children('.' + div_id + sizes[size].ID).children('.score_part').removeAttr('disabled');
								}
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