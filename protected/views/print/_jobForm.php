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
		<?php echo $form->textField($model,'COST',array('size'=>6,'maxlength'=>6)); ?>
		<?php echo $form->error($model,'COST'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->