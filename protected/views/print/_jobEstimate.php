<div id="print" class="form">

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo CHtml::errorSummary($model); ?>
		
	<div class="row passes">
		<?php echo CHtml::activeLabelEx($model,'FRONT_PASS'); ?>
		<?php echo CHtml::activeDropDownList($model,'FRONT_PASS', $passes, array('class'=>'pass_part front_pass')); ?>
		<?php echo CHtml::error($model,'FRONT_PASS'); ?>
	</div>
	
	<div class="row passes">
		<?php echo CHtml::activeLabelEx($model,'BACK_PASS'); ?>
		<?php echo CHtml::activeDropDownList($model,'BACK_PASS', $passes, array('class'=>'pass_part back_pass')); ?>
		<?php echo CHtml::error($model,'BACK_PASS'); ?>
	</div>
	
	<div class="row passes">
		<?php echo CHtml::activeLabelEx($model,'SLEEVE_PASS'); ?>
		<?php echo CHtml::activeDropDownList($model,'SLEEVE_PASS', $passes, array('class'=>'pass_part sleeve_pass')); ?>
		<?php echo CHtml::error($model,'SLEEVE_PASS'); ?>
	</div>
	
	<?php echo CHtml::hiddenField('score_pass',$model->pass, array('class'=>'score_pass')); ?>

	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'COST'); ?>
		<?php echo CHtml::activeTextField($model,'COST',array('size'=>6,'maxlength'=>6, 'class'=>'part')); ?>
		<?php echo CHtml::error($model,'COST'); ?>
	</div>

</div><!-- form -->

<?php Yii::app()->clientScript->registerScript('pass-update', "" .
		"$('.pass_part').live('change keyup', function(event){
			var passes = 0;" .
			"$('.pass_part').each(function(){
				passes += 1 * $(this).val();
			});" .
			"$('.score_pass').val(passes).change();
		});", 
CClientScript::POS_END);?>