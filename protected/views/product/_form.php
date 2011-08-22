<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'product-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'COST'); ?>
		<?php echo $form->textField($model,'COST',array('size'=>2,'maxlength'=>2)); ?>
		<?php echo $form->error($model,'COST'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'STATUS'); ?>
		<?php echo $form->textField($model,'STATUS'); ?>
		<?php echo $form->error($model,'STATUS'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'STYLE'); ?>
		<?php echo $form->textField($model,'STYLE'); ?>
		<?php echo $form->error($model,'STYLE'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'COLOR'); ?>
		<?php echo $form->textField($model,'COLOR'); ?>
		<?php echo $form->error($model,'COLOR'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'SIZE'); ?>
		<?php echo $form->textField($model,'SIZE'); ?>
		<?php echo $form->error($model,'SIZE'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'AVAILABLE'); ?>
		<?php echo $form->textField($model,'AVAILABLE'); ?>
		<?php echo $form->error($model,'AVAILABLE'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->