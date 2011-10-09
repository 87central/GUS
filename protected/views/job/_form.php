<?php
Yii::app()->clientScript->registerCoreScript('jquery'); 
Yii::app()->clientScript->registerScript('add-job', "function addLine(sender, namePrefix){
	$.ajax({
		url: '".CHtml::normalizeUrl(array('job/newLine'))."'," .
		"type: 'POST'," .
		"data: {
			namePrefix: namePrefix," .
			"count: $(sender).parent().children('.jobLine').size(),
		}," .
		"success: function(data){
			$(sender).before(data);
		},
	});
}", CClientScript::POS_BEGIN);?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'job-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'NAME');?>
		<?php echo $form->textField($model, 'NAME');?>
		<?php echo $form->error($model, 'NAME');?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'formattedDueDate'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
			'name'=>'Job[formattedDueDate]',
			'model'=>$model,
			'attribute'=>'formattedDueDate',
			'options'=>array(
				'showAnim'=>'fold',
				'dateFormat'=>'DD, MM d, yy',
			),
		));?>
		<?php echo $form->error($model, 'formattedDueDate'); ?>
	</div>
	
	<div class="separator"></div>
	
	<?php 
		$this->renderPartial('//customer/_jobForm', array(
			'customerList'=>$customerList,
			'newCustomer'=>$newCustomer,
		));
	?>
	
	<div class="separator"></div>
	
	<?php $userList = CHtml::listData($users, 'ID', 'FIRST');?>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'LEADER_ID');?>
		<?php echo $form->dropDownList($model, 'LEADER_ID', $userList); ?>
		<?php echo $form->error($model, 'LEADER_ID');?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'PRINTER_ID');?>
		<?php echo $form->dropDownList($model, 'PRINTER_ID', $userList);?>
		<?php echo $form->error($model, 'PRINTER_ID');?>
	</div>
	
	<div class="separator"></div>
	<?php $this->renderPartial('//print/_jobForm', array(
		'model'=> $print,
		'job'=>$model, 
	));?>
	<div class="separator"></div>
	
	<?php 
		$sizeList = CHtml::listData($sizes, 'ID', 'TEXT');
		$styleList = CHtml::listData($styles, 'ID', 'TEXT');
		$colorList = CHtml::listData($colors, 'ID', 'TEXT');
	?>
	
	<div id="lines" class="row">
		<?php
		if($model->isNewRecord){
			$this->renderPartial('//jobLine/_form', array(
				'sizes'=>$sizeList,
				'colors'=>$colorList,
				'styles'=>$styleList,
				'namePrefix'=>CHtml::activeName($model, 'jobLines') . '[0]',
				'model'=>new JobLine,
				'formatter'=>new Formatter,
			));
		} else {
			$index = 0;
			foreach($model->jobLines as $line){
				$view = !Yii::app()->user->getState('isAdmin') && $line->isApproved ? '//jobLine/_view' : '//jobLine/_form';
				$this->renderPartial($view, array(
					'sizes'=>$sizeList,
					'colors'=>$colorList,
					'styles'=>$styleList,
					'namePrefix'=>CHtml::activeName($model, 'jobLines') . '[' . $index . ']',
					'model'=>$line,
					'formatter'=>new Formatter,
				));
				$index++;
			}
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
		<?php echo $form->labelEx($model,'DESCRIPTION'); ?>
		<?php echo $form->textArea($model,'DESCRIPTION',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'DESCRIPTION'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NOTES'); ?>
		<?php echo $form->textArea($model,'NOTES',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'NOTES'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ISSUES'); ?>
		<?php echo $form->textArea($model,'ISSUES',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'ISSUES'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->