<div class="jobLine <?php echo ($product->PRODUCT_ID == null) ? 'hidden-size' : '';?> <?php echo $div.$product->SIZE;?>" id="<?php echo $eachDiv;?>">
	<?php echo CHtml::label($product->size->TEXT, CHtml::getIdByName($linePrefix . '[QUANTITY]'));?>
	<?php echo CHtml::encode($line->QUANTITY);?>
</div>