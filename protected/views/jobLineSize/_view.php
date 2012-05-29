<div class="jobLineView <?php echo ($line->JOB_LINE_ID == null) ? 'hidden-size' : '';?> <?php echo $div.$product->SIZE;?>" id="<?php echo $eachDiv;?>">
	<span class="size-label"><?php echo CHtml::label($product->size->TEXT, CHtml::getIdByName($linePrefix . '[QUANTITY]'));?></span>
	<span class="size-content"><?php echo CHtml::encode($line->QUANTITY);?></span>
</div>