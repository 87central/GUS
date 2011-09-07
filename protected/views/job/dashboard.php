<?php
$this->pageTitle = Yii::app()->user->name . ' - ' . 'Dashboard';
?>
Welcome <?php echo Yii::app()->user->name;?>&nbsp;
<span class="note"><?php echo date('l F j');?></span>
<br/>
<strong>Important Messages...</strong>
<br/>
<!--table goes here-->
<?php 
$this->widget('application.components.Menu', array(
	'items'=>array(
		array('label'=>'+ New Job', 'url'=>array('job/create')),
		array('label'=>'All Jobs', 'url'=>array('job/index')),
		array('label'=>'Past Jobs', 'url'=>array('job/index', 'selector'=>'past')),
	),
	'options'=>array(
		'id'=>'job_menu',
	)
));
?>
<div id="current_cal" class="cal_container">
<h6>Calendar - This Week</h6>
<!--calendar goes here-->
</div>
<div id="next_cal" class="cal_container">
<h6>Next Week</h6>
<!--calendar goes here-->
</div>