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
		<?php echo $form->label($model,'CUSTOMER_ID'); ?>
		<?php echo $form->textField($model,'CUSTOMER_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'LEADER_ID'); ?>
		<?php echo $form->textField($model,'LEADER_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'DESCRIPTION'); ?>
		<?php echo $form->textArea($model,'DESCRIPTION',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'NOTES'); ?>
		<?php echo $form->textArea($model,'NOTES',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ISSUES'); ?>
		<?php echo $form->textArea($model,'ISSUES',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'RUSH'); ?>
		<?php echo $form->textField($model,'RUSH'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'SET_UP_FEE'); ?>
		<?php echo $form->textField($model,'SET_UP_FEE',array('size'=>2,'maxlength'=>2)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'SCORE'); ?>
		<?php echo $form->textField($model,'SCORE'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'QUOTE'); ?>
		<?php echo $form->textField($model,'QUOTE',array('size'=>2,'maxlength'=>2)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->