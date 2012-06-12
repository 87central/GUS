<div id="content">
	<<div class="row">
		<<div id="hugebig" class="grid_5 alpha">
			<span class="title bold">JOB</span> <?php echo $model->NAME; ?>
		</div>

		<<div class="grid_5 omega">
			<span class="title bold">DUE </span><?php echo CHtml::encode($model->formattedPickUpDate);?>

			<div class="buttons right">
				<?php echo CHtml::button('Edit', array(
					'onclick'=>"js:window.location.href='".CHtml::normalizeUrl(array('job/update', 'id'=>$model->ID))."';"
				));?>
				
				<?php echo CHtml::button('View Invoice', array(
					'onclick'=>"js:window.location.href='".CHtml::normalizeUrl(array('job/invoice', 'id'=>$model->ID))."';"
				));?>
				
				<?php if(Yii::app()->user->getState('isAdmin')){
					echo CHtml::button('Export to QuickBooks', array(
						'onclick'=>"js:window.location.href='".CHtml::normalizeUrl(array('job/invoice', 'id'=>$model->ID, 'view'=>'iif'))."';"
					));
				}?>
			</div>

			<div class="row">
				<span class="title bold"><?php echo CHtml::activeLabelEx($model, 'PRINTER_ID');?> / <?php echo CHtml::activeLabelEx($model, 'LEADER_ID');?></span>
				<?php echo CHtml::encode($model->PRINTER->FIRST);?> / <?php echo CHtml::encode($model->LEADER->FIRST);?>
			</div>
		</div>

		<div class="clear"></div>
	</div>	
	
	<div class="separator-dark"></div>
	
	<?php 
		$this->renderPartial('//customer/_jobView', array(
			'model'=>$customer,
			'formatter'=>$formatter,
		));
	?>
	
	<div class="separator"></div>	
	
	<?php $this->renderPartial('//print/_jobView', array(
		'model'=>$print,
		'artLink'=>isset($artLink) ? $artLink : null,
		'mockupLink'=>isset($mockupLink) ? $mockupLink : null,
		'formatter'=>$formatter,
	));?>
	
	<br>
	
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
		<div class="clear"></div>
	</div>
	
	<br>

	<div class="row">
		<span class="title bold total"><?php $garmentCount = $model->garmentCount;?>
		<?php echo CHtml::label('Garment Count', 'garment_qty');?></span>
		<?php echo CHtml::encode($formatter->formatNumber($garmentCount));?>
	</div>

	<div class="auto_quote">
		<br>
		<div class="row">
			<span class="title bold"><?php echo CHtml::activeLabelEx($model,'RUSH'); ?></span>
			<?php echo CHtml::encode($formatter->formatCurrency($model->RUSH));?>
		</div>

		<div class="row">
			<span class="title bold"><?php echo CHtml::activeLabelEx($model,'SET_UP_FEE'); ?></span>
			<?php echo CHtml::encode($formatter->formatCurrency($model->SET_UP_FEE));?>
		</div>

		<?php foreach($model->additionalFees as $key=>$fee){?>
			<div class="row">
				<span class="title bold"><?php echo CHtml::activeLabelEx($model, 'additionalFees['.$key.']', array(
					'label'=>$fee['TEXT'],
				));?></span>
				<?php echo CHtml::encode($fee['VALUE']);?>
			</div>
		<?php }?>

		<div class="grid_6 alpha">	
			<h4>Auto Quote</h4>	
			<div class="grid_3 alpha">
				<div class="row">
					<span class="title bold total"><?php echo CHtml::label('Sub Total', 'auto_total');?></span>
					<span class="right-price"><?php echo CHtml::encode($formatter->formatCurrency($model->total));?></span>
				</div>
				<div class="row">
					<span class="title bold total"><?php $taxRate = $model->additionalFees[Job::FEE_TAX_RATE]['VALUE'] / 100;?>
					<?php echo CHtml::label('Total Tax', 'auto_tax');?>	</span>	
					<span class="right-price"><?php echo CHtml::encode($formatter->formatCurrency($model->total * $taxRate));?></span>
				</div>
				<div class="row grandtotal">
					<span class="title bold grand-pad"><?php echo CHtml::label('Grand Total', 'auto_grand');?></span>
					<span class="right-price"><?php echo CHtml::encode($formatter->formatCurrency($model->total * (1 + $taxRate)));?></span>
				</div>
			</div>
			<div class="grid_3 omega">
				<div class="row">
					<span class="title bold total"><?php echo CHtml::label('Sub Total Per Garment', 'auto_total_each');?>	</span>
					<span class="right-price"><?php echo CHtml::encode($formatter->formatCurrency($model->garmentPrice));?></span>
				</div>
				<div class="row">
					<span class="title bold total"><?php echo CHtml::label('Total Tax Per Garment', 'auto_tax_each');?></span>
					<span class="right-price"><?php echo CHtml::encode($formatter->formatCurrency($model->garmentPrice * $taxRate));?></span>
				</div>
				<div class="row grandtotal">
					<span class="title bold grand-pad"><?php echo CHtml::label('Grand Total Per Garment', 'auto_grand_each');?></span>
					<span class="right-price"><?php echo CHtml::encode($formatter->formatCurrency($model->garmentPrice * (1 + $taxRate)));?></span>
				</div>
			</div>
		</div>

		<div class="grid_4 omega">
			<h4>Quoted</h4>
		
			<div class="row">
				<span class="title bold total"><?php echo CHtml::label('Total Per Garment', 'item_total');?></span>
				<span class="right-price"><?php echo CHtml::encode($formatter->formatCurrency(($garmentCount == 0) ? 0 : $model->QUOTE / $garmentCount));?></span>
			</div>

			<div class="row <?php $quoted = CHtml::encode($formatter->formatCurrency($model->QUOTE)); if($quoted != '$0.00'){ echo 'RED'; } else {echo 'chicken'; } ?>">
				<span class="title bold total grand-pad"><?php echo CHtml::activeLabelEx($model,'QUOTE'); ?></span>
				<span class="right-price"><?php echo $quoted; ?></span>		
			</div>
	
		</div>

		<div class="clear"></div>
	</div><!-- <div class="auto_quote">-->

	<br><br>

	<div id="notes" class="row">
		<span class="title bold"><?php echo CHtml::activeLabelEx($model,'NOTES'); ?></span>
		<?php echo $formatter->formatNtext($model->NOTES);?>
	</div>

	<br><br>

	<div class="row buttons">
		<?php echo CHtml::button('Edit', array(
			'onclick'=>"js:window.location.href='".CHtml::normalizeUrl(array('job/update', 'id'=>$model->ID))."';"
		));?>
		
		<?php echo CHtml::button('View Invoice', array(
			'onclick'=>"js:window.location.href='".CHtml::normalizeUrl(array('job/invoice', 'id'=>$model->ID))."';"
		));?>
		
		<?php if(Yii::app()->user->getState('isAdmin')){
			echo CHtml::button('Export to QuickBooks', array(
				'onclick'=>"js:window.location.href='".CHtml::normalizeUrl(array('job/invoice', 'id'=>$model->ID, 'view'=>'iif'))."';"
			));
		}?>				
	</div>


</div><!-- content -->
