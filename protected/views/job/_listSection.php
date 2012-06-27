<?php 
//it doesn't make sense to me either, but the yii framework doesn't let me add extra variables
//to expressions in the grid view. so this is what I have to do...
class StatusProvider {
	public static $statuses;
	
	public static function statusSelector($model){
		return CHtml::activeDropDownList($model, 'STATUS', StatusProvider::$statuses, array(
			'onchange'=>"statusChanged(".Job::COMPLETED.", ".Job::CANCELED.", '".CHtml::normalizeUrl(array('job/status', 'id'=>$model->ID))."', this);"
		));
	}
}

StatusProvider::$statuses = $statuses;

$this->widget('zii.widgets.grid.CGridView', array( 
	'dataProvider'=>$dataProvider,
	'formatter'=>new Formatter,
	'columns'=>array(
		array(
			'class'=>'CLinkColumn',
			'header'=>'Jobs',
			'labelExpression'=>"((\$data->RUSH != 0) ? '<span class=\"warning\">RUSH</span>&nbsp;' : '') . \$data->NAME;",
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
			'value'=>"(strtotime(\$data->printDate) <= 0) ? '(None)' : date('l (n/j)', strtotime(\$data->printDate));",
		),
		'totalPasses::Passes',
	)
));
