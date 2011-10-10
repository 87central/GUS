<?php /*vars for JS calculations*/?>
<?php $total = '#'.CHtml::getIdByName($namePrefix);?>
<?php $qty = '#'.CHtml::getIdByName($namePrefix . '[QUANTITY]');?>
<?php $price = '#'.CHtml::getIdByName($namePrefix . '[PRICE]');?>
<?php $div = CHtml::getIdByName($namePrefix . 'item');?>

<div class="jobLine" id="<?php echo $div;?>">
	Style: <?php echo CHtml::activeDropDownList($model, 'style', $styles, array(
		'name'=>$namePrefix . '[style]',
	));?>
	Size: <?php echo CHtml::activeDropDownList($model, 'size', $sizes, array(
		'name'=>$namePrefix . '[size]',
	));?>
	Color: <?php echo CHtml::activeDropDownList($model, 'color', $colors, array(
		'name'=>$namePrefix . '[color]',
	));?>
	Quantity: <?php echo CHtml::activeTextField($model, 'QUANTITY', array(
		'name'=>$namePrefix . '[QUANTITY]',
		'onkeyup'=>"$('".$total."').val((1 * $('".$qty."').val()) * $('".$price."').val()).change();",
		'class'=>'score_part',
		'size'=>5,
	));?>
	Price Each: <?php echo CHtml::activeTextField($model, 'PRICE', array(
		'name'=>$namePrefix . '[PRICE]',
		'onkeyup'=>"$('".$total."').val((1 * $('".$qty."').val()) * $('".$price."').val()).change();",
		'size'=>5,
	))?>
	<?php echo CHtml::activeHiddenField($model, 'ID', array(
		'name'=>$namePrefix . '[ID]',
	));?>
	<?php echo CHtml::hiddenField('total', $model->total, array(
		'class'=>'part',
		'id'=>CHtml::getIdByName($namePrefix),
		'readonly'=>'readonly',
	));?>
	<?php echo CHtml::hiddenField('prefix', $namePrefix, array(
		'class'=>'namePrefix',
	));?>
	
	<?php if(!$model->isApproved){?>
		<?php echo CHtml::button('Approve', array(
			'class'=>'line_approve',
		));?>		
	<?php } else {?>
		<?php echo CHtml::button('Unapprove', array(
			'class'=>'line_unapprove',
		));?>
	<?php }?>
	
	<?php echo CHtml::button('Delete', array(
		'class'=>'line_delete',
	));?>
</div>

<?php Yii::app()->clientScript->registerScript('line-approve', "" .
		"$('.line_approve').live('click', function(event){
			var div = $(event.target).parent();" .
			"$.ajax({
				'url': '".CHtml::normalizeUrl(array('job/approveLine'))."'," .
				"'type': 'POST'," .
				"'data': {
					'id': '".$model->ID."'," .
					"'namePrefix': $(div).children('.namePrefix').val(),
				}," .
				"'success': function(data){
					$(div).replaceWith(data);
				},
			});
		})", 
CClientScript::POS_END);?>
<?php Yii::app()->clientScript->registerScript('line-unapprove', "" .
		"$('.line_unapprove').live('click', function(event){
			var div = $(event.target).parent();" .
			"$.ajax({
				'url': '".CHtml::normalizeUrl(array('job/unapproveLine'))."'," .
				"'type': 'POST'," .
				"'data': {
					'id': '".$model->ID."'," .
					"'namePrefix': $(div).children('.namePrefix').val(),
				}," .
				"'success': function(data){
					$(div).replaceWith(data);
				},
			});
		})", 
CClientScript::POS_END);?>
<?php Yii::app()->clientScript->registerScript('line-delete', "" .
		"$('.line_delete').live('click', function(event){
			var div = $(event.target).parent();" .
			"$.ajax({
				'url': '".CHtml::normalizeUrl(array('job/deleteLine'))."'," .
				"'type': 'POST'," .
				"'data': {
					'id': '".$model->ID."'," .
					"'namePrefix': $(div).children('.namePrefix').val(),
				}," .
				"'success': function(data){
					$(div).remove();
				},
			});
		})", 
CClientScript::POS_END);?>