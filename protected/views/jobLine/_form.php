<?php 
$sizeList = CHtml::listData($sizes, 'ID', 'TEXT');
$styleList = CHtml::listData($styles, 'ID', 'TEXT');
$colorList = CHtml::listData($colors, 'ID', 'TEXT');
?>
Style: <?php echo CHtml::activeDropDownList($model, 'STYLE', $styleList, array(
	'name'=>$namePrefix . '[STYLE]',
));?>
Size: <?php echo CHtml::activeDropDownList($model, 'SIZE', $sizeList, array(
	'name'=>$namePrefix . '[SIZE]',
));?>
Color: <?php echo CHtml::activeDropDownList($model, 'COLOR', $colorList, array(
	'name'=>$namePrefix . '[COLOR]',
));?>
Quantity: <?php echo CHtml::activeTextField($model, 'QUANTITY', array(
	'name'=>$namePrefix . '[QUANTITY]',
));?>
<?php echo CHtml::activeHiddenField($model, 'ID', array(
	'name'=>$namePrefix . '[ID]',
));?>