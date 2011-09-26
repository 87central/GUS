<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'print-job-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'PASS'); ?>
		<?php echo $form->passwordField($model,'PASS'); ?>
		<?php echo $form->error($model,'PASS'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ART'); ?>
		<?php echo $form->textField($model,'ART',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'ART'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'COST'); ?>
		<?php echo $form->textField($model,'COST',array('size'=>2,'maxlength'=>2)); ?>
		<?php echo $form->error($model,'COST'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'APPROVAL_DATE'); ?>
		<?php echo $form->textField($model,'APPROVAL_DATE'); ?>
		<?php echo $form->error($model,'APPROVAL_DATE'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'APPROVAL_USER'); ?>
		<?php echo $form->textField($model,'APPROVAL_USER'); ?>
		<?php echo $form->error($model,'APPROVAL_USER'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->