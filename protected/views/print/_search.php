<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'ID'); ?>
		<?php echo $form->textField($model,'ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PASS'); ?>
		<?php echo $form->passwordField($model,'PASS'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ART'); ?>
		<?php echo $form->textField($model,'ART',array('size'=>60,'maxlength'=>200)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'COST'); ?>
		<?php echo $form->textField($model,'COST',array('size'=>2,'maxlength'=>2)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'APPROVAL_DATE'); ?>
		<?php echo $form->textField($model,'APPROVAL_DATE'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'APPROVAL_USER'); ?>
		<?php echo $form->textField($model,'APPROVAL_USER'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->