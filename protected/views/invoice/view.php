<div class="address">
	8|7 Central<br/>
	400 East Locust #7<br/>
	Des Moines, IA 50309<br/>
	<div class="contact">		
		www.eightsevencentral.com<br/>
		515-288-206
	</div>
</div>
<br/>
<br/>
<div id="title">
	<?php echo CHtml::encode($model->TITLE);?>
</div>
<div id="customer">
	<?php echo CHtml::encode($model->CUSTOMER->summary);?>
</div>

<table id="date_invoice">
	<tr class="header">
		<th>Date</th>		
		<th>Invoice No.</th>
	</tr>
	<tr>
		<td><?php echo DateConverter::toUserTime(strtotime($model->DATE));?></td>		
		<td><?php echo $model->ID;?></td>
	</tr>
</table>

<table id="terms">
	<tr class="header">
		<th>Terms</th>
	</tr>
	<tr>
		<td><?php echo $formatter->formatNtext($model->TERMS);?></td>
	</tr>
</table>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
	'formatter'=>$formatter,
	'rowCssClass'=>'item_row',
	'summaryText'=>'',
	'columns'=>array(
		'ITEM_TYPE_ID:lookup',
		'DESCRIPTION',
		'QUANTITY:number',
		'RATE:currency',
		'AMOUNT:currency',
	),
)); /*need to remember tax rate!*/?>

<div id="footnotes">
*Make checks payable to 8|7 Central.  Cash and all major credit cards also accepted.<br/>
*All orders have been counted. Any order discrepancies must be addressed at time 
of pick-up
</div>

<div id="total">
	<span class="total_label">TOTAL</span><span class="total_amt"><?php echo CHtml::encode($formatter->formatCurrency($model->total * (1 + $model->TAX_RATE / 100)));?></span>
</div>
