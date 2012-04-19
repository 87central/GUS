<?php Yii::app()->clientScript->registerCssFile($this->styleDirectory . 'job_invoice.css');?>
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
<div id="customer">
	<?php echo CHtml::encode($model->CUSTOMER->summary);?>
</div>

<table id="date_invoice">
	<tr class="header">
		<th>Date</th>
		<th>Invoice No.</th>
		<th>Job No.</th>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td><?php echo $model->ID;?></td>
	</tr>
</table>

<table id="terms">
	<tr class="header">
		<th>Terms</th>
	</tr>
</table>

<?php /*just calculate the "base" cost of garments up here.*/
$base = CostCalculator::calculateTotal($model->garmentCount, $model->printJob->FRONT_PASS, $model->printJob->BACK_PASS, $model->printJob->SLEEVE_PASS, 0);
$setup = null;
if($model->garmentCount < 24){
	$base = ($base - 30) / $model->garmentCount;
	$setup = 30;
} else {
	$base = $base / $model->garmentCount;
	$setup = 0;
}
?>
<table id="items">
	<tr class="header">
		<th>Item</th>
		<th>Description</th>
		<th>Quantity</th>
		<th>Rate</th>
		<th>Amount</th>
	</tr>
	<?php /*group lines by style, color, and price*/
	$finalLines = array();
	foreach($model->jobLines as $line){
		if($line->QUANTITY != 0){
			$key = $line->product->vendorStyle.$line->product->color->TEXT.($line->total/$line->QUANTITY).$model->printJob->FRONT_PASS.$model->printJob->BACK_PASS.$model->printJob->SLEEVE_PASS;
			if(isset($finalLines[$key])){
				$value = $finalLines[$key];
			} else {
				$value = array(
					'text'=>$model->printJob->FRONT_PASS . ' Front/ ' . $model->printJob->BACK_PASS . ' Back/ ' . $model->printJob->SLEEVE_PASS . ' Sleeve on ' . $line->product->vendorStyle . ' - ' . $line->product->color->TEXT . ' ' . ($line->isExtraLarge ? 'Extra Large' : 'Standard'),
					'quantity'=>0,
					'unit_cost'=>$line->total / $line->QUANTITY + $base,
					'total'=>0,
				);
			}
			$value['quantity'] += $line->QUANTITY;
			$value['total'] += $line->total + $base * $line->QUANTITY;
			$finalLines[$key] = $value;
		}
	}?>
	<?php /*foreach($model->jobLines as $line){?>
		<tr class="item_row">
			<td>Printing</td>
			<td><?php echo CHtml::encode($model->printJob->FRONT_PASS . ' Front/ ' . $model->printJob->BACK_PASS . ' Back/ ' . $model->printJob->SLEEVE_PASS . ' Sleeve on ' . $line->product->vendorStyle . ' - ' . $line->product->color->TEXT);?></td>
			<td><?php echo CHtml::encode($formatter->formatNumber($line->QUANTITY));?></td>
			<td><?php echo CHtml::encode($formatter->formatCurrency((($line->total / $line->QUANTITY) + $base)));?></td>
			<td><?php echo CHtml::encode($formatter->formatCurrency($line->total + (($base) * $line->QUANTITY)));?></td>
		</tr>
	<?php }*/?>
	<?php foreach($finalLines as $line){?>
		<tr class="item_row">
			<td>Printing</td>
			<td><?php echo CHtml::encode($line['text']);?></td>
			<td><?php echo CHtml::encode($formatter->formatNumber($line['quantity']));?></td>
			<td><?php echo CHtml::encode($formatter->formatCurrency($line['unit_cost']));?></td>
			<td><?php echo CHtml::encode($formatter->formatCurrency($line['total']));?></td>
		</tr>
	<?php }?>
	<tr class="item_row">
		<td>Artwork Charge</td>
		<td>Fee for design work</td>
		<td><?php echo $formatter->formatNumber($model->printJob->COST / 40);?></td>
		<td><?php echo $formatter->formatCurrency(40);?></td>
		<td><?php echo $formatter->formatCurrency($model->printJob->COST);?></td>
	</tr>
	<tr class="item_row">
		<td>Setup Time</td>
		<td>Fee for setup (waived for larger orders)</td>
		<td><?php echo $formatter->formatNumber($setup / 30);?></td>
		<td><?php echo $formatter->formatCurrency(30);?></td>
		<td><?php echo $formatter->formatCurrency($setup);?></td>
	</tr>
	<?php foreach($model->additionalFees as $fee){?>
		<?php if($fee['CONSTRAINTS']['part'] !== false){?>
			<tr class="item_row">
				<td>Add'l Charge</td>
				<td><?php echo $fee['TEXT'];?></td>
				<td></td>
				<td></td>
				<td><?php echo $formatter->formatCurrency($fee['VALUE']);?></td>
			</tr>
		<?php }?>
	<?php }?>
	<tr class="item_row">
		<td></td>
		<td>Sales Tax</td>
		<td></td>
		<td><?php echo Yii::app()->numberFormatter->formatPercentage($model->additionalFees[Job::FEE_TAX_RATE]['VALUE'] / 100);?></td>
		<td><?php echo $formatter->formatCurrency($model->total * $model->additionalFees[Job::FEE_TAX_RATE]['VALUE'] / 100);?></td>
	</tr>
</table>

<div id="footnotes">
*Make checks payable to 8|7 Central.  Cash and all major credit cards also accepted.<br/>
*All orders have been counted. Any order discrepancies must be addressed at time 
of pick-up
</div>

<div id="total">
	<span class="total_label">TOTAL</span><span class="total_amt"><?php echo CHtml::encode($formatter->formatCurrency($model->total * (1 + $model->additionalFees[Job::FEE_TAX_RATE]['VALUE'] / 100)));?></span>
</div>