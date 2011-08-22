<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'job-form',
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
		<?php echo $form->labelEx($model,'LEADER_ID'); ?>
		<?php echo $form->textField($model,'LEADER_ID'); ?>
		<?php echo $form->error($model,'LEADER_ID'); ?>
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

	<div class="row">
		<?php echo $form->labelEx($model,'RUSH'); ?>
		<?php echo $form->textField($model,'RUSH'); ?>
		<?php echo $form->error($model,'RUSH'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'SET_UP_FEE'); ?>
		<?php echo $form->textField($model,'SET_UP_FEE',array('size'=>2,'maxlength'=>2)); ?>
		<?php echo $form->error($model,'SET_UP_FEE'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'SCORE'); ?>
		<?php echo $form->textField($model,'SCORE'); ?>
		<?php echo $form->error($model,'SCORE'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'QUOTE'); ?>
		<?php echo $form->textField($model,'QUOTE',array('size'=>2,'maxlength'=>2)); ?>
		<?php echo $form->error($model,'QUOTE'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->