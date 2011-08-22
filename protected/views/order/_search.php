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
		<?php echo $form->label($model,'EXTERNAL_ID'); ?>
		<?php echo $form->textField($model,'EXTERNAL_ID',array('size'=>60,'maxlength'=>60)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'VENDOR_ID'); ?>
		<?php echo $form->textField($model,'VENDOR_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'DATE'); ?>
		<?php echo $form->textField($model,'DATE'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->