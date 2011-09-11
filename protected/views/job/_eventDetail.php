<?php 
	$job = $item->getObject();
	$passes = 0;
	foreach($job->jobLines as $jobLine){
		$print = $jobLine->print;
		$passes = $passes + $print->PASS;
	}
?>
<?php if($job->RUSH){?>
	<span class="warning">RUSH</span>&nbsp;
<?php } ?>
<?php echo CHtml::encode($job->DESCRIPTION);?>&nbsp;(<?php echo $passes;?>)