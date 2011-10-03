<?php 
	$job = $item->getObject();
?>
<?php if($job->RUSH){?>
	<span class="warning">RUSH</span>&nbsp;
<?php } ?>
<?php echo CHtml::encode($job->DESCRIPTION);?>&nbsp;(<?php echo $job->totalPasses;?>)