<?php Yii::app()->clientScript->registerCssFile($this->styleDirectory . 'user_form.css');?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'EMAIL'); ?>
		<?php echo $form->textField($model,'EMAIL',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'EMAIL'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'PASSWORD'); ?>
		<?php echo $form->passwordField($model,'PASSWORD',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'PASSWORD'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'FIRST'); ?>
		<?php echo $form->textField($model,'FIRST',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'FIRST'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'LAST'); ?>
		<?php echo $form->textField($model,'LAST',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'LAST'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'PHONE'); ?>
		<?php echo $form->textField($model,'PHONE',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'PHONE'); ?>
	</div>

	<div class="row roles">		
		<?php echo $form->checkBox($model, 'isAdmin');?>
		<?php echo $form->labelEx($model,'isAdmin'); ?>
	</div>
	<div class="row roles">
		<?php echo $form->checkBox($model, 'isLead');?>
		<?php echo $form->labelEx($model,'isLead'); ?>
	</div>
	<div class="row roles">
		<?php echo $form->checkBox($model, 'isPrinter');?>
		<?php echo $form->labelEx($model,'isPrinter'); ?>
	</div>
	<div class="row roles">
		<?php echo $form->checkBox($model, 'isCustomer');?>
		<?php echo $form->labelEx($model, 'isCustomer');?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->