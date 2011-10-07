<div class="orderLine">
	<?php echo CHtml::activeLabelEx($model, 'PRODUCT_ID');?>
	<?php echo CHtml::activeDropDownList($model, 'PRODUCT_ID', $products, array(
		'name'=>$namePrefix . '[PRODUCT_ID]',
	));?>
	
	<?php if($orderStatus == Order::CREATED || $orderStatus == Order::ORDERED
			 || $orderStatus == null){?>
		<?php echo CHtml::activeLabelEx($model, 'QUANTITY_ORDERED');?>
		<?php echo CHtml::activeTextField($model, 'QUANTITY_ORDERED', array(
			'name'=>$namePrefix . '[QUANTITY_ORDERED]',
		));?>
		<?php echo CHtml::error($model, 'QUANTITY_ORDERED');?>
	<?php } else {?>
		<?php echo CHtml::activeLabelEx($model, 'QUANTITY_ORDERED');?>
		<?php echo $model->QUANTITY_ORDERED;?>
		
		<?php echo CHtml::activeLabelEx($model, 'QUANTITY_ARRIVED');?>
		<?php echo CHtml::activeTextField($model, 'QUANTITY_ARRIVED', array(
			'name'=>$namePrefix . '[QUANTITY_ARRIVED]',
		));?>
		<?php echo CHtml::error($model, 'QUANTITY_ARRIVED');?>
	<?php }?>
	
	<?php echo CHtml::activeLabelEx($model, 'COST');?>
	<?php echo CHtml::activeTextField($model, 'COST', array(
		'name'=>$namePrefix . '[COST]',
	));?>
	
	<?php echo CHtml::activeHiddenField($model, 'ID', array(
		'name'=>$namePrefix . '[ID]',
	));?>
</div>