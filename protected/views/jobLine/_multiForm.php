<?php /*needs vars for namePrefix and startIndex (the index from which to start numbering lines)*/?>

<?php $div = CHtml::getIdByName($namePrefix . $startIndex . 'item');?>

<div class="jobLines" id="<?php echo $div;?>">	
	<?php 
	$approved = true;
	$saved = true;
	
	foreach($products['lines'] as $dataLine){
		$line = $dataLine['line'];
		$approved = $approved && $line->isApproved;
		$saved = $saved && !$line->isNewRecord;
	}?>
	Style <?php $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
		'sourceUrl'=>array('product/findProduct', 'response'=>'juijson'),
		'name'=>$namePrefix.$startIndex.'style',
		'htmlOptions'=>array(
			'class'=>'item-select',
			'disabled'=>$approved && !Yii::app()->user->getState('isAdmin'),
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
	
	Yii::app()->clientScript->registerScript('set-style', "" .
			"\$('#".CHtml::getIdByName($namePrefix.$startIndex.'style')."').val('".$products['style']."');" , 
	CClientScript::POS_READY);?> 
	
	Color <?php echo CHtml::dropDownList('colors', $products['currentColor'], $products['availableColors'], array(
		'id'=>CHtml::getIdByName($namePrefix . 'colors'), 
		'disabled'=>count($products['availableColors']) == 0, //only disable if there aren't any colors available. 
		'class'=>'color-select',
		'onchange'=>"\$('#".$div."').children('.jobLine').children('.line-color').val(\$(this).val());",
	));?>
	
	<?php 
	$approved = true;
	$saved = true;
	foreach($products['lines'] as $dataLine){
		$product = $dataLine['product'];
		$line = $dataLine['line'];
		$approved = $approved && $line->isApproved;
		$saved = $saved && !$line->isNewRecord;
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
				'class'=>'score_part',
				'size'=>5,
				'disabled'=>($product->ID == null), //only disable if the product doesn't seem to exist.
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
	
	<?php echo CHtml::button('Delete', array(
		'class'=>'line_delete',
	));?>
</div>

<?php Yii::app()->clientScript->registerScript('line-approve', "" .
		"$('.line_approve').live('click', function(event){
			var div = $(event.target).parent();" .
			"$(div).children('.jobLine').each(function(){
				var childDiv = this;" .
				"$.ajax({
					'url': '".CHtml::normalizeUrl(array('job/approveLine'))."'," .
					"'type': 'POST'," .
					"'data': {
						'id': $(childDiv).children('.line_id').val()," .
						"'namePrefix': $(childDiv).children('.linePrefix').val(),
					}," .
					"'success': function(data){
						$(childDiv).replaceWith(data);
					},
				});
			});
		})", 
CClientScript::POS_END);?>
<?php Yii::app()->clientScript->registerScript('line-unapprove', "" .
		"$('.line_unapprove').live('click', function(event){
			var div = $(event.target).parent();" .
			"$(div).children('.jobLine').each(function(){
				var childDiv = this;" .
				"$.ajax({
					'url': '".CHtml::normalizeUrl(array('job/unapproveLine'))."'," .
					"'type': 'POST'," .
					"'data': {
						'id': $(childDiv).children('.line_id').val()," .
						"'namePrefix': $(childDiv).children('.linePrefix').val(),
					}," .
					"'success': function(data){
						$(childDiv).replaceWith(data);
					},
				});
			});
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