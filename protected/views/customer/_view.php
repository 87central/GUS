<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->ID), array('view', 'id'=>$data->ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('USER_ID')); ?>:</b>
	<?php echo CHtml::encode($data->USER_ID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('COMPANY')); ?>:</b>
	<?php echo CHtml::encode($data->COMPANY); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NOTES')); ?>:</b>
	<?php echo CHtml::encode($data->NOTES); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('TERMS')); ?>:</b>
	<?php echo CHtml::encode($data->TERMS); ?>
	<br />


</div>