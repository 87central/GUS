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
			});
		},
	});
}", CClientScript::POS_BEGIN);?>

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
		'artLink'=>isset($artLink) ? $artLink : null,
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
		<?php echo $form->checkBox($model,'RUSH'); ?>
		<?php echo $form->error($model,'RUSH'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'SET_UP_FEE'); ?>
		<?php echo $form->textField($model,'SET_UP_FEE',array('size'=>6,'maxlength'=>6, 'class'=>'part')); ?>
		<?php echo $form->error($model,'SET_UP_FEE'); ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::label('Auto Quote Total', 'auto_total');?>
		<?php echo CHtml::textField('auto_total', $model->total, array('readonly'=>'readonly', 'id'=>'auto_total'));?>
		<?php Yii::app()->clientScript->registerScript('auto-totaler', "" .
				"$('.part').live('change keyup', function(){
					var total = 0;" .
					"$('.part').each(function(index){
						total += (1 * $(this).val());
					});" .
					"$('#auto_total').val(total);
				})", 
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
		<?php echo $form->labelEx($model,'QUOTE'); ?>
		<?php echo $form->textField($model,'QUOTE',array('size'=>7,'maxlength'=>7)); ?>
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