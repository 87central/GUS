<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->ID), array('view', 'id'=>$data->ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PASS')); ?>:</b>
	<?php echo CHtml::encode($data->PASS); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ART')); ?>:</b>
	<?php echo CHtml::encode($data->ART); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('COST')); ?>:</b>
	<?php echo CHtml::encode($data->COST); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('APPROVAL_DATE')); ?>:</b>
	<?php echo CHtml::encode($data->APPROVAL_DATE); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('APPROVAL_USER')); ?>:</b>
	<?php echo CHtml::encode($data->APPROVAL_USER); ?>
	<br />


</div>