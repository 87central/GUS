<?php /*needs vars for namePrefix and startIndex (the index from which to start numbering lines)*/?>

<?php $div = CHtml::getIdByName($namePrefix . 'item');?>

<div class="jobLines" id="<?php echo $div;?>">	
	Style <?php $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
		'sourceUrl'=>array('product/findProduct', 'response'=>'juijson'),
		'htmlOptions'=>array(
			'class'=>'item-select',
		),
		'options'=>array(
			'select'=>"js:function(event, ui){
				\$.getJSON({
					url: '".CHtml::normalizeUrl(array('product/allowedOptions'))."'," .
					"success: function(data){
						var colors = data.colors;" .
						"var sizes = data.sizes;" .
						"var style = data.style;" .
						"\$('#".$div."').children('.jobLine').children('.line-style').val(style.ID);" .
						"var colorOptions = $('<select></select>')" .
						"	.attr('name', 'color-select')" .
						"	.attr('id', '".CHtml::getIdByName($namePrefix.'colors')."')" .
						"	.attr('class', 'color-select')" .
						"	.attr('onchange', 'function(){\$(\\\'#".$div."\\\').children(\\\'.jobLine\\\').children(\\\'.line-color\\\').val(\$(\\\'#".CHtml::getIdByName($namePrefix.'colors')."\\\').val())}');" .
						"for(var color in colors){
							colorOptions.append($('<option></option>').val(color.ID).html(color.TEXT));
						}" .
						"\$('#".$div."').children('.color-select').replaceWith(colorOptions);" .
						"\$('#".$div."').children('.jobLine').children('.score_part').attr('disabled', true).val(0);" .
						"for(var size in sizes){
							\$('#".$div."').children('.".$div."' + size.ID).children('.score_part').removeAttr('disabled');
						}
					}" .
					"data: {
						itemID: ui.item.id,
					}
				});
			}"
		),
	));?> 
	
	Color <?php echo CHtml::dropDownList('colors', null, array(), array('id'=>CHtml::getIdByName($namePrefix . 'colors'), 'disabled'=>true, 'class'=>'color-select'));?>
	
	<?php 
	$approved = true;
	$saved = true;
	foreach($products as $product=>$line){
		$approved = $approved && $line->isApproved;
		$saved = $saved && !$line->isNewRecord;
		$linePrefix = $namePrefix . '['.$startIndex++.']';
		$eachDiv = CHtml::getIdByName($linePrefix.'item');
		?>
		<div class="jobLine <?php $div.$product->SIZE;?>" id="<?php echo $eachDiv;?>">
			<?php /*vars for JS calculations*/?>
			<?php $total = '#'.CHtml::getIdByName($linePrefix);?>
			<?php $qty = '#'.CHtml::getIdByName($linePrefix . '[QUANTITY]');?>
			<?php $price = '#'.CHtml::getIdByName($linePrefix . '[PRICE]');?>

			<?php echo CHtml::label($product->size->TEXT, CHtml::getIdByName($linePrefix . '[QUANTITY]'));?>
			<?php echo CHtml::activeTextField($line, 'QUANTITY', array(
				'name'=>$linePrefix . '[QUANTITY]',
				'onkeyup'=>"$('".$total."').val((1 * $('".$qty."').val()) * $('".$price."').val()).change();",
				'class'=>'score_part',
				'size'=>5,
				'disabled'=>true,
			));?>
			
			<?php echo CHtml::activeHiddenField($line, 'PRICE', array(
				'name'=>$linePrefix . '[PRICE]',
				'onchange'=>"$('".$total."').val((1 * $('".$qty."').val()) * $('".$price."').val()).change();",
				'class'=>'hidden_cost',
			));?>
			
			<?php echo CHtml::activeHiddenField($line, 'total', array(
				'class'=>'part',
				'readonly'=>'readonly',
				'name'=>$linePrefix,
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
			
			<?php echo CHtml::hiddenField($linePrefix.'linePrefix', $linePrefix, array(
				'class'=>'linePrefix',
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