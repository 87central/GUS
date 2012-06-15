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