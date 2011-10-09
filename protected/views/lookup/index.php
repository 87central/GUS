<?php Yii::app()->clientScript->registerCssFile($this->styleDirectory . 'lookup_index.css');?>

<?php foreach($data as $type=>$items){
	$this->renderPartial('_list', array(
		'type'=>$type,
		'items'=>$items,
	));
}?>