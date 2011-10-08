<div id="print" class="form">

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo CHtml::errorSummary($model); ?>

	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'PASS'); ?>
		<?php echo CHtml::activeTextField($model,'PASS', array('class'=>'score_pass')); ?>
		<?php echo CHtml::error($model,'PASS'); ?>
	</div>

	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'ART'); ?>
		<?php echo CHtml::activeTextField($model,'ART',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo CHtml::error($model,'ART'); ?>
	</div>

	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'COST'); ?>
		<?php echo CHtml::activeTextField($model,'COST',array('size'=>6,'maxlength'=>6, 'class'=>'part')); ?>
		<?php echo CHtml::error($model,'COST'); ?>
	</div>

</div><!-- form -->