<div class="orderLine">
	<?php echo CHtml::activeLabelEx($model, 'PRODUCT_ID');?>
	<?php echo CHtml::activeDropDownList($model, 'PRODUCT_ID', $products, array(
		'name'=>$namePrefix . '[PRODUCT_ID]',
	));?>
	
	<?php if($orderStatus == null || $orderStatus == Order::CREATED || Yii::app()->user->getState('isAdmin')){
		echo CHtml::activeLabelEx($model, 'QUANTITY_ORDERED');
		echo CHtml::activeTextField($model, 'QUANTITY_ORDERED', array(
			'name'=>$namePrefix . '[QUANTITY_ORDERED]',
		));
		echo CHtml::error($model, 'QUANTITY_ORDERED');
	} else {
		echo CHtml::activeLabelEx($model, 'QUANTITY_ORDERED');
		echo $model->QUANTITY_ORDERED;
	}
	if($orderStatus != null && $orderStatus != Order::CREATED && !Yii::app()->user->getState('isAdmin')){
		echo CHtml::activeLabelEx($model, 'QUANTITY_ARRIVED');
		echo CHtml::activeTextField($model, 'QUANTITY_ARRIVED', array(
			'name'=>$namePrefix . '[QUANTITY_ARRIVED]',
		));
		echo CHtml::error($model, 'QUANTITY_ARRIVED');
	}?>
	
	<?php echo CHtml::activeLabelEx($model, 'COST');?>
	<?php echo CHtml::activeTextField($model, 'COST', array(
		'name'=>$namePrefix . '[COST]',
	));?>
	
	<?php echo CHtml::button('Remove Line', array(
		'class'=>'line_remove',
	));?>
	
	<?php echo CHtml::activeHiddenField($model, 'ID', array(
		'name'=>$namePrefix . '[ID]',
		'class'=>'line_id',
	));?>
</div>
<?php Yii::app()->clientScript->registerScript('line-delete', "" .
		"$('.line_remove').live('click', function(event){
			var div = $(event.target).parent();" .
			"$.ajax({
				url: '".CHtml::normalizeUrl(array('order/deleteLine'))."'," .
				"type: 'POST'," .
				"data: {
					id: $(div).children('.line_id').val(),
				}," .
				"success: function(){
					$(div).remove();
				},
			});
		});",
CClientScript::POS_END);?>