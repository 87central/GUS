<?php
Yii::app()->clientScript->registerCoreScript('jquery'); 
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/assets/garmentOps.js');
$onAddGarment = "addGarment(\$('#garment_style').val(), \$('#garment_color').val(), \$('#garment_size').val(), \$('#garment_file').val(), \$('#garment_passes').val(), 2);"
?>

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
		<?php //echo $form->labelEx($model, 'formattedDueDate'); ?>
		<?php /*$this->widget('zii.widgets.jui.CJuiDatePicker', array(
			'name'=>'Job[formattedDueDate]',
			'model'=>$model,
			'attribute'=>'formattedDueDate',
			'options'=>array(
				'showAnim'=>'fold',
				'dateFormat'=>'DD, MM d, yy',
			),
		));*/?>
		<?php //echo $form->error($model, 'formattedDueDate'); ?>
	</div>
	
	<div class="separator"></div>
	
	<?php 
		$this->renderPartial('//customer/_jobForm', array(
			'customerList'=>$customerList,
			'newCustomer'=>$newCustomer,
			'newCustomerUser'=>$newCustomerUser,
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
		'model'=> $model->printJob === null ? new PrintJob : $model->printJob,
		'job'=>$model, 
	));?>
	<div class="separator"></div>
	
	<?php 
		$sizeList = CHtml::listData($sizes, 'ID', 'TEXT');
		$styleList = CHtml::listData($styles, 'ID', 'TEXT');
		$colorList = CHtml::listData($colors, 'ID', 'TEXT');
	?>
	
	<div id="lines" class="row">
		<?php echo CHtml::hiddenField('garment_count', 0, array(
			'id'=>'garment_count',
		));?>
		<div class="row garments">
			Style: <?php echo CHtml::dropDownList('garment_style', null, $styleList, array(
				'id'=>'garment_style',
			));?>
			Size: <?php echo CHtml::dropDownList('garment_size', null, $sizeList, array(
				'id'=>'garment_size',
			));?>
			Color: <?php echo CHtml::dropDownList('garment_color', null, $colorList, array(
				'id'=>'garment_color',
			));?>
		</div>
		<?php echo CHtml::button('Add Garment', array(
			'onclick'=>$onAddGarment,
		));?>
	</div>
	
	<div class="separator"></div>

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

	<div class="row">
		<?php echo $form->labelEx($model,'RUSH'); ?>
		<?php echo $form->checkBox($model,'RUSH'); ?>
		<?php echo $form->error($model,'RUSH'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'SET_UP_FEE'); ?>
		<?php echo $form->textField($model,'SET_UP_FEE',array('size'=>6,'maxlength'=>6)); ?>
		<?php echo $form->error($model,'SET_UP_FEE'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'SCORE'); ?>
		<?php echo $form->textField($model,'SCORE'); ?>
		<?php echo $form->error($model,'SCORE'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'QUOTE'); ?>
		<?php echo $form->textField($model,'QUOTE',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'QUOTE'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->