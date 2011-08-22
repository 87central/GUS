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
		<?php echo $form->label($model,'COST'); ?>
		<?php echo $form->textField($model,'COST',array('size'=>2,'maxlength'=>2)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STATUS'); ?>
		<?php echo $form->textField($model,'STATUS'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'STYLE'); ?>
		<?php echo $form->textField($model,'STYLE'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'COLOR'); ?>
		<?php echo $form->textField($model,'COLOR'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'SIZE'); ?>
		<?php echo $form->textField($model,'SIZE'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'AVAILABLE'); ?>
		<?php echo $form->textField($model,'AVAILABLE'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->