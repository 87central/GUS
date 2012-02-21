<?php
$this->breadcrumbs=array(
	'Jobs'=>array('index'),
	$model->ID,
);

$this->menu=array(
	array('label'=>'List Job', 'url'=>array('index')),
	array('label'=>'Create Job', 'url'=>array('create')),
	array('label'=>'Update Job', 'url'=>array('update', 'id'=>$model->ID)),
	array('label'=>'Delete Job', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Job', 'url'=>array('admin')),
);
?>

<h1><?php echo $model->NAME; ?></h1>

<div id="content">
	<div class="row">
		<?php echo CHtml::activeLabelEx($model, 'formattedPickUpDate'); ?>
		<?php echo CHtml::encode($model->formattedPickUpDate);?>
	</div>
	
	<div class="separator"></div>
	
	<?php 
		$this->renderPartial('//customer/_jobView', array(
			'model'=>$customer,
			'formatter'=>$formatter,
		));
	?>
	
	<div class="separator"></div>
	
	<div class="row">
		<?php echo CHtml::activeLabelEx($model, 'LEADER_ID');?>
		<?php echo CHtml::encode($model->LEADER->FIRST);?>
	</div>
	
	<div class="row">
		<?php echo CHtml::activeLabelEx($model, 'PRINTER_ID');?>
		<?php echo CHtml::encode($model->PRINTER->FIRST);?>
	</div>
	
	<div class="separator"></div>
	<?php $this->renderPartial('//print/_jobView', array(
		'model'=>$print,
		'artLink'=>isset($artLink) ? $artLink : null,
		'mockupLink'=>isset($mockupLink) ? $mockupLink : null,
		'formatter'=>$formatter,
	));?>
	
	<div class="separator"></div>
	
	<div id="lines" class="row">
		<?php
		$index = 0;
		foreach($lineData as $lines){
			$this->renderPartial('//jobLine/_multiView', array(
				'namePrefix'=>CHtml::activeName($model, 'jobLines'),
				'startIndex'=>$index,
				'products'=>$lines,
				'readonly'=>true,
				'formatter'=>$formatter,
			));
			$index += count($lines);
		}?>
	</div>
	
	<div class="separator"></div>

	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'RUSH'); ?>
		<?php echo $model->RUSH ? 'Yes' : 'No';?>
	</div>

	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'SET_UP_FEE'); ?>
		<?php echo CHtml::encode($formatter->formatCurrency($model->SET_UP_FEE));?>
	</div>
	
	<?php foreach($model->additionalFees as $key=>$fee){?>
		<div class="row">
			<?php echo CHtml::activeLabelEx($model, 'additionalFees['.$key.']', array(
				'label'=>$fee['TEXT'],
			));?>
			<?php echo CHtml::encode($fee['VALUE']);?>
		</div>
	<?php }?>
	
	<div class="row auto_quote">	
		<h5>Auto Quote</h5>		
		<?php echo CHtml::label('Sub Total', 'auto_total');?>
		<?php echo CHtml::encode($formatter->formatCurrency($model->total));?>
		<?php echo CHtml::label('Sub Total Per Garment', 'auto_total_each');?>	
		<?php echo CHtml::encode($formatter->formatCurrency($model->garmentPrice));?>
		<?php $taxRate = $model->additionalFees[Job::FEE_TAX_RATE]['VALUE'] / 100;?>
		<?php echo CHtml::label('Total Tax', 'auto_tax');?>		
		<?php echo CHtml::encode($formatter->formatCurrency($model->total * $taxRate));?>
		<?php echo CHtml::label('Total Tax Per Garment', 'auto_tax_each');?>
		<?php echo CHtml::encode($formatter->formatCurrency($model->garmentPrice * $taxRate));?>
		<?php echo CHtml::label('Grand Total', 'auto_grand');?>
		<?php echo CHtml::encode($formatter->formatCurrency($model->total * (1 + $taxRate)));?>
		<?php echo CHtml::label('Grand Total Per Garment', 'auto_grand_each');?>
		<?php echo CHtml::encode($formatter->formatCurrency($model->garmentPrice * (1 + $taxRate)));?>
	</div>
	
	<div class="row">
		<?php echo CHtml::activeLabelEx($model, 'SCORE');?>
		<?php echo CHtml::encode($formatter->formatNumber($model->score));?>
	</div>
	
	<div class="row">
		<?php $garmentCount = $model->garmentCount;?>
		<?php echo CHtml::label('Garment Count', 'garment_qty');?>
		<?php echo CHtml::encode($formatter->formatNumber($garmentCount));?>
	</div>
	
	<div class="row">
		<?php echo CHtml::label('Total Per Garment', 'item_total');?>
		<?php echo CHtml::encode($formatter->formatCurrency(($garmentCount == 0) ? 0 : $model->QUOTE / $garmentCount));?>
	</div>

	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'QUOTE'); ?>
		<?php echo CHtml::encode($formatter->formatCurrency($model->QUOTE));?>		
	</div>

	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'NOTES'); ?>
		<?php echo $formatter->formatNtext($model->NOTES);?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::button('Edit', array(
			'onclick'=>"js:window.location.href='".CHtml::normalizeUrl(array('job/update', 'id'=>$model->ID))."';"
		));?>
	</div>


</div><!-- content -->
