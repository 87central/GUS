<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'product-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'VENDOR_ITEM_ID');?>
		<?php echo $form->textField($model, 'VENDOR_ITEM_ID', array('size'=>30));?>
		<?php echo $form->error($model, 'VENDOR_ITEM_ID');?>
	</div>
	
	<div class="row">
		<?php $vendors = CHtml::listData($vendorList, 'ID', 'NAME');?>
		<?php echo $form->labelEx($model, 'VENDOR_ID');?>
		<?php echo $form->dropDownList($model, 'VENDOR_ID', $vendors);?>
		<?php echo $form->error($model, 'VENDOR_ID');?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'COST'); ?>
		<?php echo $form->textField($model,'COST',array('size'=>6,'maxlength'=>6)); ?>
		<?php echo $form->error($model,'COST'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'STYLE'); ?>
		<?php echo $form->dropDownList($model,'STYLE', $styleList); ?>
		<?php echo $form->error($model,'STYLE'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'COLORS'); ?>
		<?php //echo $form->dropDownList($model,'COLOR', $colorList); ?>
		<?php echo $form->checkBoxList($model, 'COLORS', $colorList);?>
		<?php echo $form->error($model,'COLORS'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'SIZES'); ?>
		<?php //echo $form->dropDownList($model,'SIZES', $sizeList); ?>
		<?php echo $form->checkBoxList($model, 'SIZES', $sizeList);?>
		<?php echo $form->error($model,'SIZES'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->