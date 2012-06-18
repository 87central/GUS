<?php /*requires name prefix, count, model, form, type list*/?>
<tr class="item_row">
	<?php echo $form->hiddenField($model, 'ID', array(
		'name'=>$namePrefix . '['.$count.'][ID]',
	));?>
	<td><?php echo $form->dropDownList($model, 'ITEM_TYPE_ID', $itemTypeList, array(
		'name'=>$namePrefix . '['.$count.'][ITEM_TYPE_ID]',		
		'class'=>'item_type',
	));?></td>
	<td><?php echo $form->textField($model, 'DESCRIPTION', array(
		'name'=>$namePrefix . '['.$count.'][DESCRIPTION]',
		'class'=>'description',
	));?></td>
	<td><?php echo $form->textField($model, 'QUANTITY', array(
		'name'=>$namePrefix . '['.$count.'][QUANTITY]',
		'class'=>'quantity',
	));?></td>
	<td><?php echo $form->textField($model, 'RATE', array(
		'name'=>$namePrefix . '['.$count.'][RATE]',
		'class'=>'rate',
	));?></td>
	<td><?php echo $form->textField($model, 'AMOUNT', array(
		'name'=>$namePrefix . '['.$count.'][AMOUNT]',
		'class'=>'part', 
	));?></td>
</tr>

<?php Yii::app()->clientScript->registerScript('line-total-update', "" .
		"$('.item_row td .quantity, .item_row td .rate').live('change', function(){
			var parent = $(this).parent().parent();" .
			"var quantityField = parent.children('td').children('.quantity');" .
			"var rateField = parent.children('td').children('.rate');" .
			"var amountField = parent.children('td').children('.part');" .
			"amountField.val(rateField.val() * 1 * quantityField.val() * 1).change();
		});", 
CClientScript::POS_END)?>

<?php Yii::app()->clientScript->registerScript('line-quantity-update', "" .
		"$('.item_row td .part').live('change', function(){
			var parent = $(this).parent().parent();" .
			"var quantityField = parent.children('td').children('.quantity');" .
			"var rateField = parent.children('td').children('.rate');" .
			"var amountField = parent.children('td').children('.part');" .
			"if(rateField.val() * 1 != 0){
				quantityField.val(amountField.val() * 1 / rateField.val() * 1);
			}
		});", 
CClientScript::POS_END)?>