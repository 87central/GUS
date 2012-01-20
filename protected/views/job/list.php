<?php 
Yii::app()->clientScript->registerCoreScript('jquery');

//it doesn't make sense to me either, but the yii framework doesn't let me add extra variables
//to expressions in the grid view. so this is what I have to do...
class StatusProvider {
	public static $statuses;
	
	public static function statusSelector($model){
		return CHtml::activeDropDownList($model, 'STATUS', StatusProvider::$statuses, array(
			'onchange'=>"js:$.ajax({
				url: '".CHtml::normalizeUrl(array('job/status', 'id'=>$model->ID))."'," .
				"data: {
					status: $(this).val(),
				}," .
				"type: 'POST',
			});"
		));
	}
}

StatusProvider::$statuses = $statuses;

$this->widget('zii.widgets.grid.CGridView', array( 
	'dataProvider'=>$dataProvider,
	'formatter'=>new Formatter,
	'columns'=>array(
		array(
			'name'=>'pickUpDate',
			'value'=>"date('l', strtotime(\$data->pickUpDate));",
			'header'=>'Pick-Up',
		),
		array(
			'class'=>'CLinkColumn',
			'header'=>'Open Jobs',
			'labelExpression'=>"\$data->RUSH ? '<span class=\"warning\">RUSH</span>&nbsp;' : '' . \$data->NAME;",
			'urlExpression'=>"CHtml::normalizeUrl(array('job/view', 'id'=>\$data->ID));",
		),
		array(
			'header'=>'Status',
			'type'=>'raw',
			'value'=>"StatusProvider::statusSelector(\$data)",
		),
		array(
			'header'=>'Print',
			'name'=>'printDate',
			'value'=>"date('l', strtotime(\$data->printDate));",
		),
		array(
			'header'=>'Due',
			'name'=>'dueDate',
			'value'=>"date('n/j', strtotime(\$data->dueDate));"
		),
		'totalPasses',
		array(
			'header'=>'Art',
			'value'=>"CHtml::image(Yii::app()->request->baseUrl . '/images/' . (\$data->hasArt ? 'checked.png' : 'unchecked.png'));",
			'type'=>'raw',
		),
		array(
			'header'=>'Sizes',
			'value'=>"CHtml::image(Yii::app()->request->baseUrl . '/images/' . (\$data->hasSizes ? 'checked.png' : 'unchecked.png'));",
			'type'=>'raw',
		)
	)
));
?>