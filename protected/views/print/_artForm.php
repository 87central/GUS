<div class="row">
	<?php echo CHtml::errorSummary($model); ?>

	<?php echo CHtml::activeTextField($model,'DESCRIPTION', array(
		'placeholder'=>'Description',
		'name'=>$namePrefix . '[DESCRIPTION]'
	)); ?>
	<?php echo CHtml::activeFileField($model,'FILE', array(
		'name'=>$namePrefix . '[FILE]', 
		'value'=>'')); 
	?>
	&nbsp;<?php echo ($artLink ? CHtml::link('Download Here', $artLink) : '');?>
	<?php echo CHtml::activeHiddenField($model, 'PRINT_ID', array(
		'name'=>$namePrefix . '[PRINT_ID]',
		'value'=>$print_id,
	));?>
	<?php echo CHtml::activeHiddenField($model, 'USER_ID', array(
		'name'=>$namePrefix . '[USER_ID]',
		'value'=>isset($model->USER_ID) ? $model->USER_ID : Yii::app()->user->id,
	));?>
	<?php echo CHtml::activeHiddenField($model, 'FILE_TYPE', array(
		'name'=>$namePrefix . '[FILE_TYPE]',
		'value'=>$fileType,
	));?>
	<?php echo CHtml::activeHiddenField($model, 'ID', array(
		'name'=>$namePrefix . '[ID]',
		'class'=>'art_id',
	));?>
	<?php echo CHtml::error($model,'FILE'); ?>
	<?php echo CHtml::error($model, 'DESCRIPTION');?>
	
	<?php if(Yii::app()->user->getState('isAdmin') || $model->isNewRecord || Yii::app()->user->id == $model->USER_ID || !isset($model->USER_ID)){?>
		<?php echo CHtml::button('Delete', array(
			'class'=>'art_delete',
		));?>
	<?php }?>
</div>