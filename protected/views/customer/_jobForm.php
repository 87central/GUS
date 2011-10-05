<div id="customer" class="form">

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo CHtml::errorSummary($newCustomer); ?>
	
	<div class="row">
		<?php $customerSelections = CHtml::listData($customerList, 'ID', 'summary');?>
		<?php echo 'Existing Customer: ';?>
		<?php echo CHtml::activeDropDownList($newCustomer, 'ID', $customerSelections, array(
			'onchange'=>"$.ajax({
				url: '".CHtml::normalizeUrl(array('customer/retrieve'))."'," .
				"type: 'POST'," .
				"data: {
					id: $(this).val(),
				}," .
				"success: function(data){
					$('#".CHtml::getActiveId($newCustomer, 'FIRST')."').val(data.FIRST);" .
					"$('#".CHtml::getActiveId($newCustomer, 'LAST')."').val(data.LAST);" .
					"$('#".CHtml::getActiveId($newCustomer, 'EMAIL')."').val(data.EMAIL);" .
					"$('#".CHtml::getActiveId($newCustomer, 'COMPANY')."').val(data.COMPANY);" .
					"$('#".CHtml::getActiveId($newCustomer, 'PHONE')."').val(data.PHONE);
				}," .
				"error: function(){
					$(this).val('');
				}," .
				"dataType: 'json',
			})"
		));?>
	</div>
	
	<div class="row">
		<?php echo CHtml::activeLabelEx($newCustomer, 'FIRST');?>
		<?php echo CHtml::activeTextField($newCustomer, 'FIRST');?>
		<?php echo CHtml::error($newCustomer, 'FIRST');?>
	</div>
	
	<div class="row">
		<?php echo CHtml::activeLabelEx($newCustomer, 'LAST');?>
		<?php echo CHtml::activeTextField($newCustomer, 'LAST');?>
		<?php echo CHtml::error($newCustomer, 'LAST');?>
	</div>
	
	<div class="row">
		<?php echo CHtml::activeLabelEx($newCustomer, 'EMAIL'); ?>
		<?php echo CHtml::activeTextField($newCustomer, 'EMAIL'); ?>
		<?php echo CHtml::error($newCustomer, 'EMAIL'); ?>
	</div>

	<div class="row">
		<?php echo CHtml::activeLabelEx($newCustomer,'COMPANY'); ?>
		<?php echo CHtml::activeTextField($newCustomer,'COMPANY',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo CHtml::error($newCustomer,'COMPANY'); ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::activeLabelEx($newCustomer, 'PHONE');?>
		<?php echo CHtml::activeTextField($newCustomer, 'PHONE');?>
		<?php echo CHtml::error($newCustomer, 'PHONE');?>
	</div>

</div><!-- form -->