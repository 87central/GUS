<div id="print_content">
	<div class="row passes">
		<?php echo CHtml::activeLabelEx($model,'FRONT_PASS'); ?>
		<?php echo CHtml::encode($model->FRONT_PASS);?>
	</div>
	
	<div class="row passes">
		<?php echo CHtml::activeLabelEx($model,'BACK_PASS'); ?>
		<?php echo CHtml::encode($model->BACK_PASS);?>
	</div>
	
	<div class="row passes">
		<?php echo CHtml::activeLabelEx($model,'SLEEVE_PASS'); ?>
		<?php echo CHtml::encode($model->SLEEVE_PASS);?>
	</div>

	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'ART'); ?>
		&nbsp;<?php echo ($artLink ? CHtml::link('Download Here', $artLink) : 'No Art Submitted');?>
	</div>
	
	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'MOCK_UP'); ?>
		&nbsp;<?php echo ($mockupLink ? CHtml::link('Download Here', $mockupLink) : 'No Mockup Submitted');?>
	</div>

	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'COST'); ?>
		<?php echo CHtml::encode($formatter->formatCurrency($model->COST));?>
	</div>

</div><!-- print_content -->