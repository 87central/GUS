<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'order-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'EXTERNAL_ID'); ?>
		<?php echo $form->textField($model,'EXTERNAL_ID',array('size'=>60,'maxlength'=>60)); ?>
		<?php echo $form->error($model,'EXTERNAL_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'VENDOR_ID'); ?>
		<?php echo $form->textField($model,'VENDOR_ID'); ?>
		<?php echo $form->error($model,'VENDOR_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'DATE'); ?>
		<?php echo $form->textField($model,'DATE'); ?>
		<?php echo $form->error($model,'DATE'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->