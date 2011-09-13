<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'customer-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($newCustomer); ?>
	
	<div class="row">
		<?php $customerSelections = CHtml::listData($customerList, 'ID', 'summary');?>
		<?php echo 'Existing Customer: ';?>
		<?php echo $form->dropDownList($newCustomer, 'ID', $customerSelections);?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($newCustomer, 'FIRST');?>
		<?php echo $form->textField($newCustomer, 'FIRST');?>
		<?php echo $form->error($newCustomer, 'FIRST');?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($newCustomer, 'LAST');?>
		<?php echo $form->textField($newCustomer, 'LAST');?>
		<?php echo $form->error($newCustomer, 'LAST');?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($newCustomer, 'EMAIL'); ?>
		<?php echo $form->textField($newCustomer, 'EMAIL'); ?>
		<?php echo $form->error($newCustomer, 'EMAIL'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($newCustomer,'COMPANY'); ?>
		<?php echo $form->textField($newCustomer,'COMPANY',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($newCustomer,'COMPANY'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($newCustomer, 'PHONE');?>
		<?php echo $form->textField($newCustomer, 'PHONE');?>
		<?php echo $form->error($newCustomer, 'PHONE');?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->