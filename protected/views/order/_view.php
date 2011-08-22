<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->ID), array('view', 'id'=>$data->ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('EXTERNAL_ID')); ?>:</b>
	<?php echo CHtml::encode($data->EXTERNAL_ID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('VENDOR_ID')); ?>:</b>
	<?php echo CHtml::encode($data->VENDOR_ID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('DATE')); ?>:</b>
	<?php echo CHtml::encode($data->DATE); ?>
	<br />


</div>