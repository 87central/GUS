<div class="row">
	<?php echo CHtml::encode($model->DESCRIPTION); ?>
	&nbsp;<?php echo ($artLink ? CHtml::link('Download Here', $artLink) : 'No File Found');?>
</div>