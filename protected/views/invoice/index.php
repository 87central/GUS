<?php
Yii::app()->clientScript->registerCssFile($this->styleDirectory . 'invoice_index.css');
?>

<script type="text/javascript">
function statusChanged(completedStatus, canceledStatus, updateUrl, selector){
	var status = $(selector).val(); 
	$.ajax({
		url: updateUrl,
		data: {
			status: status,
		},
		type: 'POST',
		success: function(data){
			var index = 0;	
			switch(1 * status){
				case canceledStatus : index = 2; break;
				case completedStatus : index = 1; break;
				default : index = 0; break;
			}
			var tabControl = $(selector).parentsUntil('.ui-tabs').parent();
			var currentIndex = tabControl.tabs('option', 'selected');
			$(tabControl).tabs('load', index);
			$(tabControl).tabs('load', currentIndex);
		}
	});
}
</script>

<?php 
$this->widget('zii.widgets.jui.CJuiTabs', array(
	'tabs'=>array(
		'Current Invoices'=>array('ajax'=>array('invoice/loadList', 'list'=>'current'),
			'content'=>$this->renderPartial('_listSection', array(
				'statuses'=>$statuses,
				'dataProvider'=>$currentDataProvider,
				'formatter'=>$formatter,
			), true),
		),
		'Completed Invoices'=>array('ajax'=>array('invoice/loadList', 'list'=>'completed')),
		'Canceled Invoices'=>array('ajax'=>array('invoice/loadList', 'list'=>'canceled')),
	),
	'options'=>array(
		'ajaxOptions'=>array(
			'cache'=>false,
		),
	),
));
?>
