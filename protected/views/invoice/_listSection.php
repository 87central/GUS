<?php 
//it doesn't make sense to me either, but the yii framework doesn't let me add extra variables
//to expressions in the grid view. so this is what I have to do...
class StatusProvider {
	public static $statuses;
	
	public static function statusSelector($model){
		return CHtml::activeDropDownList($model, 'STATUS_ID', StatusProvider::$statuses, array(
			'onchange'=>"statusChanged(".Invoice::COMPLETED.", ".Invoice::CANCELLED.", '".CHtml::normalizeUrl(array('invoice/status', 'id'=>$model->ID))."', this);"
		));
	}
}

StatusProvider::$statuses = $statuses;

$this->widget('zii.widgets.grid.CGridView', array( 
	'dataProvider'=>$dataProvider,
	'formatter'=>$formatter,
	'columns'=>array(
		array(
			'class'=>'CLinkColumn',
			'header'=>'Invoices',
			'labelExpression'=>"\$data->TITLE;",
			'urlExpression'=>"CHtml::normalizeUrl(array('invoice/update', 'id'=>\$data->ID));",
		),
		array(
			'header'=>'Status',
			'type'=>'raw',
			'value'=>"StatusProvider::statusSelector(\$data)",
		),
		array(
			'header'=>'Date',
			'name'=>'DATE',
			'value'=>"(strtotime(\$data->DATE) <= 0) ? '(None)' : date('l (n/j)', strtotime(\$data->DATE));",
		),
		'total:currency:Sub Total',
	)
));
