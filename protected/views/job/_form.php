<?php
Yii::app()->clientScript->registerCoreScript('jquery'); 
Yii::app()->clientScript->registerScript('add-job', "function addLine(sender, namePrefix){
	var count = $(sender).parent().children('.jobLines').children('.jobLine').children('.part').size();" .
	"$.ajax({
		url: '".CHtml::normalizeUrl(array('job/newLine'))."'," .
		"type: 'POST'," .
		"data: {
			namePrefix: namePrefix," .
			"count: count,
		}," .
		"success: function(data){
			$(sender).before(data);" .
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
						"var products = data.products;" .
						"var cost = data.productCost;" .
						"\$('#' + div_id).children('.jobLine').children('.line-product').val(null);" .
						"var colorOptions = $('<select></select>')" .
							"\n.attr('name', 'color-select')" .
							".attr('class', 'color-select')" .
							".change(function() {" .
								"for(var size in sizes){
									\$('#' + div_id).children('.' + div_id + sizes[size].ID).children('.line-product').val(products[\$(colorOptions).val()][sizes[size].ID].ID);
								}" .
							"});" .
						"for(var color in colors){
							colorOptions.append($('<option></option>').val(colors[color].ID).html(colors[color].TEXT));
						}" .
						"\$('#' + div_id).children('.color-select').replaceWith(colorOptions);\n" .
						"\$('#' + div_id).children('.jobLine').addClass('hidden-size').children('.score_part').attr('disabled', true).val(0);" .
						"\$('#' + div_id).children('.jobLine').children('.hidden_cost').val(cost);" .
						"for(var size in sizes){" .
							"var firstColor = null;" .
							"for(var color in colors){
								firstColor = color;" .
								"break;
							}
							\$('#' + div_id).children('.' + div_id + sizes[size].ID)" .
							".removeClass('hidden-size')" .
							".children('.line-product').val(products[colors[firstColor].ID][sizes[size].ID].ID)" .
							".parent().children('.score_part').removeAttr('disabled');
						}
					});
				}," .
				"'source': '".CHtml::normalizeUrl(array('product/findProduct', 'response'=>'juijson'))."'
			});
		},
	});
}", CClientScript::POS_BEGIN);

Yii::app()->clientScript->registerScript('calculate-total', "" .
		"function calculateTotal(garments, front, back, sleeve, dest){
			var result = 0;" .
			"$.getJSON('".CHtml::normalizeUrl(array('job/garmentCost'))."'," .
			"{
				garments: garments," .
				"front: front," .
				"back: back," .
				"sleeve: sleeve,
			}," .
			"function(data){
				\$(dest).val(data.result).change();
			});
		}", 
CClientScript::POS_BEGIN);?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'job-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array(
		'enctype'=>'multipart/form-data',
	),
)); ?>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'NAME');?>
		<?php echo $form->textField($model, 'NAME');?>
		<?php echo $form->error($model, 'NAME');?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'formattedPickUpDate'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
			'name'=>'Job[formattedPickUpDate]',
			'model'=>$model,
			'attribute'=>'formattedPickUpDate',
			'options'=>array(
				'showAnim'=>'fold',
				'dateFormat'=>'DD, MM d, yy',
			),
		));?>
		<?php echo $form->error($model, 'formattedPickUpDate'); ?>
	</div>
	
	<div class="separator"></div>
	
	<?php 
		$this->renderPartial('//customer/_jobForm', array(
			'customerList'=>$customerList,
			'newCustomer'=>$newCustomer,
		));
	?>
	
	<div class="separator"></div>
	
	<?php $printerList = CHtml::listData($printers, 'ID', 'FIRST');?>
	<?php $leaderList = CHtml::listData($leaders, 'ID', 'FIRST');?>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'LEADER_ID');?>
		<?php echo $form->dropDownList($model, 'LEADER_ID', $leaderList); ?>
		<?php echo $form->error($model, 'LEADER_ID');?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'PRINTER_ID');?>
		<?php echo $form->dropDownList($model, 'PRINTER_ID', $printerList);?>
		<?php echo $form->error($model, 'PRINTER_ID');?>
	</div>
	
	<div class="separator"></div>
	<?php $this->renderPartial('//print/_jobForm', array(
		'model'=> $print,
		'job'=>$model,
		'fileTypes'=>$fileTypes,
		'passes'=>$passes, 
	));?>
	<div class="separator"></div>
	
	<?php 
		$sizeList = CHtml::listData($sizes, 'ID', 'TEXT');
		$styleList = CHtml::listData($styles, 'ID', 'TEXT');
		$colorList = CHtml::listData($colors, 'ID', 'TEXT');
	?>
	
	<div id="lines" class="row">
		<?php
		$index = 0;
		foreach($lineData as $lines){
			$this->renderPartial('//jobLine/_multiForm', array(
				'namePrefix'=>CHtml::activeName($model, 'jobLines'),
				'startIndex'=>$index,
				'products'=>$lines,
			));
			$index += count($lines);
		}?>
		<?php echo CHtml::button('Add Garment', array(
			'onclick'=>"addLine(this, '".CHtml::activeName($model, 'jobLines')."');",
		));?>
	</div>
	
	<div class="separator"></div>

	<div class="row">
		<?php echo $form->labelEx($model,'RUSH'); ?>
		<?php echo $form->textField($model,'RUSH', array('class'=>'part')); ?>
		<?php echo $form->error($model,'RUSH'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'SET_UP_FEE'); ?>
		<?php echo $form->textField($model,'SET_UP_FEE',array('size'=>6,'maxlength'=>6, 'class'=>'part')); ?>
		<?php echo $form->error($model,'SET_UP_FEE'); ?>
	</div>
	
	<?php foreach($model->additionalFees as $key=>$fee){?>
		<?php echo $form->labelEx($model, 'additionalFees['.$key.']', array(
			'label'=>$fee['TEXT'],
		));?>
		<?php echo $form->textField($model, 'additionalFees['.$key.']', array(
			'value'=>$fee['VALUE'],
			'size'=>6,
			'maxlength'=>6,
			'class'=>($fee['CONSTRAINTS']['part'] !== false) ? 'part' : '',
		));?>
	<?php }?>
	
	<div class="row auto_quote">
		<h5>Auto Quote</h5>		
		<?php echo CHtml::label('Sub Total', 'auto_total');?>
		<?php echo CHtml::textField('auto_total', $model->total, array('readonly'=>'readonly', 'id'=>'auto_total'));?>
		<?php echo CHtml::label('Sub Total Per Garment', 'auto_total_each');?>
		<?php echo CHtml::textField('auto_total_each', $model->garmentPrice, array('readonly'=>'readonly', 'id'=>'auto_total_each'));?>
		<?php $taxRate = $model->additionalFees[Job::FEE_TAX_RATE]['VALUE'] / 100;
		$taxRateField = CHtml::getIdByName('Job[additionalFees]['.Job::FEE_TAX_RATE.']');?>
		<?php echo CHtml::label('Total Tax', 'auto_tax');?>
		<?php echo CHtml::textField('auto_tax', $model->total * $taxRate, array('readonly'=>'readonly', 'id'=>'auto_tax'));?>
		<?php echo CHtml::label('Total Tax Per Garment', 'auto_tax_each');?>
		<?php echo CHtml::textField('auto_tax_each', $model->garmentPrice * $taxRate, array('readonly'=>'readonly', 'id'=>'auto_tax_each'));?>
		<?php echo CHtml::label('Grand Total', 'auto_grand');?>
		<?php echo CHtml::textField('auto_grand', $model->total * (1 + $taxRate), array('readonly'=>'readonly', 'id'=>'auto_grand'));?>
		<?php echo CHtml::label('Grand Total Per Garment', 'auto_grand_each');?>
		<?php echo CHtml::textField('auto_grand_each', $model->garmentPrice * (1 + $taxRate), array('readonly'=>'readonly', 'id'=>'auto_grand_each'));?>		
		<p id="qty_warning" class="note" style="display: none;">The quote estimator only supports price quotation for up to two hundred (200) garments.</p>
		<?php echo CHtml::hiddenField('garment_total', $model->garmentTotal - $model->garmentCost, array('id'=>'garment_total', 'class'=>'part'));?>
		<?php Yii::app()->clientScript->registerScript('auto-garment-totaler', "" .
				"$('.item_qty, .sleeve_pass, .front_pass, .back_pass').live('change keyup', function(){
					var qty = 0;" .
					"$('.item_qty').each(function(index){
						qty += (1 * $(this).val());
					});" .
					"if(qty > 200){
						$('#auto_total, #auto_total_each, #auto_tax, #auto_tax_each, #auto_grand, #auto_grand_each').val(0).attr('disabled', 'disabled');" .
						"$('#qty_warning').show();
					} else {
						$('#auto_total, #auto_total_each, #auto_tax, #auto_tax_each, #auto_grand, #auto_grand_each').removeAttr('disabled');" .
						"$('#qty_warning').hide();
					}" .
					"$('#garment_qty').val(qty).change();" .
					"calculateTotal(qty, $('.front_pass').val(), $('.back_pass').val(), $('.sleeve_pass').val(), $('#garment_total'));
				})", 
		CClientScript::POS_END);
		
		Yii::app()->clientScript->registerScript('auto-totaler', "" .
				"$('.part, #$taxRateField').live('change keyup', function(){
					var total = 0;" .
					"var tax = (1 * $('#$taxRateField').val()) / 100;" .
					"var totalEach = 0;" .
					"$('.part').each(function(index){
						total += (1 * $(this).val());
					});" .
					"$('#auto_total').val(parseFloat(total).toFixed(2));" .
					"$('#auto_tax').val(parseFloat(total * tax).toFixed(2));" .
					"$('#auto_grand').val(parseFloat(total * (1 + tax)).toFixed(2));" .
					"" .
					"var qty = 0;" .
					"$('.item_qty').each(function(index){
						qty += (1 * $(this).val());
					});" .
					"totalEach = (qty == 0) ? 0 : total / qty;" .
					"$('#auto_total_each').val(parseFloat(totalEach).toFixed(2));" .
					"$('#auto_tax_each').val(parseFloat(totalEach * tax).toFixed(2));" .
					"$('#auto_grand_each').val(parseFloat(totalEach * (1 + tax)).toFixed(2));" .
					"if(qty > 200){
						$('#auto_total, #auto_total_each, #auto_tax, #auto_tax_each, #auto_grand, #auto_grand_each').val(0).attr('disabled', 'disabled');" .
						"$('#qty_warning').show();
					} else {
						$('#auto_total, #auto_total_each, #auto_tax, #auto_tax_each, #auto_grand, #auto_grand_each').removeAttr('disabled');" .
						"$('#qty_warning').hide();
					}
				});", 
		CClientScript::POS_END);?>
	</div>
	
	<div class="row">
		<?php echo CHtml::hiddenField('score_base', 30, array('class'=>'score_base'));?>
		<?php echo $form->labelEx($model, 'SCORE');?>
		<?php echo CHtml::textField('score', $model->score, array(
			'id'=>'score',
			'readonly'=>'readonly',
		));?>
		<?php Yii::app()->clientScript->registerScript('auto-score', "" .
				"$('.score_part, .score_pass').live('change keyup', function(){
					var base = 1 * $('.score_base').val();" .
					"var passes = 1 * $('.score_pass').val();" .
					"var qty = 0;" .
					"$('.score_part').each(function(index){
						qty += 1 * $(this).val();
					});" .
					"$('#score').val(base + (passes * qty));
				});", 
		CClientScript::POS_END);?>
	</div>
	
	<div class="row">
		<?php $garmentCount = $model->garmentCount;?>
		<?php echo CHtml::label('Garment Count', 'garment_qty');?>
		<?php echo CHtml::textField('garment_qty', $garmentCount, array(
			'id'=>'garment_qty',
			'readonly'=>'readonly',
			'onchange'=>"js:\$('#".CHtml::getActiveId($model, 'QUOTE')."').val($(this).val() * $('#item_total').val());",
			'onkeyup'=>"js:\$('#".CHtml::getActiveId($model, 'QUOTE')."').val($(this).val() * $('#item_total').val());"
		));?>
	</div>
	
	<div class="row">
		<?php echo CHtml::label('Total Per Garment', 'item_total');?>
		<?php echo CHtml::textField('item_total', ($garmentCount == 0) ? 0 : $model->QUOTE / $garmentCount, array(
			'id'=>'item_total',
			'onchange'=>"\$('#".CHtml::getActiveId($model, 'QUOTE')."').val($(this).val() * $('#garment_qty').val());",
			'onkeyup'=>"\$('#".CHtml::getActiveId($model, 'QUOTE')."').val($(this).val() * $('#garment_qty').val());"
		));?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'QUOTE'); ?>
		<?php echo $form->textField($model,'QUOTE',array(
			'size'=>7,
			'maxlength'=>7,
			'onchange'=>"\$('#item_total').val($(this).val() / $('#garment_qty').val());",
			'onkeyup'=>"\$('#item_total').val($(this).val() / $('#garment_qty').val());"
		)); ?>
		<?php echo $form->error($model,'QUOTE'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NOTES'); ?>
		<?php echo $form->textArea($model,'NOTES',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'NOTES'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->