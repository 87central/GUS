<?php $id = 'job_evt_'.$job->ID.$item->ID;?>
<div id="<?php echo $id;?>">
	<?php 
		$job = $item->getAssocObject();
	?>
	<?php if($job->RUSH){?>
		<span class="warning">RUSH</span>&nbsp;
	<?php } ?>
	<?php echo CHtml::encode($job->NAME);?>&nbsp;(<?php echo $job->score;?>)
	<?php echo CHtml::activeHiddenField($item, 'ID');?>
	<?php Yii::app()->clientScript->registerCss($id, "#$id{height:".($job->score / 480 *  10)."em}");/*480 is number of minutes in 8 hours*/?>
</div>