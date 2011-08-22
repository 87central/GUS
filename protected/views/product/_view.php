<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->ID), array('view', 'id'=>$data->ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('COST')); ?>:</b>
	<?php echo CHtml::encode($data->COST); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STATUS')); ?>:</b>
	<?php echo CHtml::encode($data->STATUS); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('STYLE')); ?>:</b>
	<?php echo CHtml::encode($data->STYLE); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('COLOR')); ?>:</b>
	<?php echo CHtml::encode($data->COLOR); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('SIZE')); ?>:</b>
	<?php echo CHtml::encode($data->SIZE); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('AVAILABLE')); ?>:</b>
	<?php echo CHtml::encode($data->AVAILABLE); ?>
	<br />


</div>