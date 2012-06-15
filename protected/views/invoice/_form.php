<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'invoice-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'CUSTOMER_ID'); ?>
		<?php echo $form->textField($model,'CUSTOMER_ID'); ?>
		<?php echo $form->error($model,'CUSTOMER_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'USER_ID'); ?>
		<?php echo $form->textField($model,'USER_ID'); ?>
		<?php echo $form->error($model,'USER_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'TITLE'); ?>
		<?php echo $form->textField($model,'TITLE',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'TITLE'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'DATE'); ?>
		<?php echo $form->textField($model,'DATE'); ?>
		<?php echo $form->error($model,'DATE'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'TERMS'); ?>
		<?php echo $form->textArea($model,'TERMS',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'TERMS'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'TAX_RATE'); ?>
		<?php echo $form->textField($model,'TAX_RATE',array('size'=>3,'maxlength'=>3)); ?>
		<?php echo $form->error($model,'TAX_RATE'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'TIMESTAMP'); ?>
		<?php echo $form->textField($model,'TIMESTAMP'); ?>
		<?php echo $form->error($model,'TIMESTAMP'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->