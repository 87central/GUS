<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->ID), array('view', 'id'=>$data->ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('CUSTOMER_ID')); ?>:</b>
	<?php echo CHtml::encode($data->CUSTOMER_ID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('LEADER_ID')); ?>:</b>
	<?php echo CHtml::encode($data->LEADER_ID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('DESCRIPTION')); ?>:</b>
	<?php echo CHtml::encode($data->DESCRIPTION); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NOTES')); ?>:</b>
	<?php echo CHtml::encode($data->NOTES); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ISSUES')); ?>:</b>
	<?php echo CHtml::encode($data->ISSUES); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('RUSH')); ?>:</b>
	<?php echo CHtml::encode($data->RUSH); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('SET_UP_FEE')); ?>:</b>
	<?php echo CHtml::encode($data->SET_UP_FEE); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('SCORE')); ?>:</b>
	<?php echo CHtml::encode($data->SCORE); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('QUOTE')); ?>:</b>
	<?php echo CHtml::encode($data->QUOTE); ?>
	<br />

	*/ ?>

</div>