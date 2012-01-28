<div id="customer_content">
	<div class="row">
		<?php echo CHtml::activeLabelEx($model, 'FIRST');?>
		<?php echo CHtml::encode($model->FIRST);?>
	</div>
	
	<div class="row">
		<?php echo CHtml::activeLabelEx($model, 'LAST');?>
		<?php echo CHtml::encode($model->LAST);?>
	</div>
	
	<div class="row">
		<?php echo CHtml::activeLabelEx($model, 'EMAIL'); ?>
		<?php echo $formatter->formatEmail($model->EMAIL);?>
	</div>

	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'COMPANY'); ?>
		<?php echo CHtml::encode($model->COMPANY);?>
	</div>
	
	<div class="row">
		<?php echo CHtml::activeLabelEx($model, 'PHONE');?>
		<?php echo CHtml::encode($model->PHONE);?>
	</div>

</div><!-- customer_content -->