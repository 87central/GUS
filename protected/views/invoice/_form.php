<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'invoice-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array(
		'onsubmit'=>"$('#line_prototype').remove(); return true;"
	),
)); ?>
	
	<?php Yii::app()->clientScript->registerScript('add-line', "" .
			"function addLine(lineCountField, linePrototype, token){
				var lineCount = $(lineCountField).val();" .
				"var regExp = new RegExp(token, 'g');" .
				"var insertBefore = $('#lines .tax');" .
				"var insertItem = $(linePrototype).clone();" .
				"insertItem.html(insertItem.html().replace(regExp, lineCount));" .
				"$(insertBefore).before(insertItem.children());" .
				"$(lineCountField).val(lineCount * 1 + 1);
			}", 
	CClientScript::POS_HEAD);?>

	<!--<p class="note">Fields with <span class="required">*</span> are required.</p>-->

	<?php echo $form->errorSummary($model); ?>
	
	<?php echo $form->hiddenField($model,'USER_ID', array('value'=>Yii::app()->user->id)); ?>
		
	
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
		<!--<?php echo $form->labelEx($model,'TITLE'); ?>-->
		<?php echo $form->textField($model,'TITLE',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'TITLE'); ?>
	</div>
	<div id="customer">
		<?php 
			$this->renderPartial('//customer/_jobForm', array(
				'customerList'=>$customerList,
				'newCustomer'=>$newCustomer,
			));
		?>	
	</div>
	
	<table id="date_invoice">
		<tr class="header">
			<th>Date</th>		
			<th>Invoice No.</th>
		</tr>
		<tr>
			<td>
				<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
					'name'=>'Invoice[DATE]',
					'model'=>$model,
					'attribute'=>'DATE',
					'options'=>array(
						'showAnim'=>'fold',
						'dateFormat'=>'DD, MM d, yy',
					),
				));?>
				<?php echo $form->error($model, 'DATE'); ?>
			</td>		
			<td><?php echo $model->isNewRecord ? 'New Invoice' : $model->ID;?></td>
		</tr>
	</table>
	
	<table id="terms">		
		<tr class="header">
			<th><?php echo $form->labelEx($model,'TERMS'); ?></th>
		</tr>
		<tr>
			<td><?php echo $form->textArea($model,'TERMS',array('rows'=>6, 'cols'=>50)); ?></td>
		</tr>
		<?php if($model->hasErrors('TERMS')){
			echo '<tr>';
			echo $form->error($model,'TERMS');
			echo '</td>';
		}?>
	</table>
	
	<table id="lines">
		<tr class="header">
			<?php $lineModel = InvoiceLine::model();?>
			<th><?php echo $lineModel->getAttributeLabel('ITEM_TYPE_ID');?></th>
			<th><?php echo $lineModel->getAttributeLabel('DESCRIPTION');?></th>
			<th><?php echo $lineModel->getAttributeLabel('QUANTITY');?></th>
			<th><?php echo $lineModel->getAttributeLabel('RATE');?></th>
			<th><?php echo $lineModel->getAttributeLabel('AMOUNT');?></th>
		</tr>
		<?php if($model->isNewRecord){
			$this->renderPartial('/invoiceLine/_form', array(
				'model'=>new InvoiceLine,
				'namePrefix'=>'Invoice[lines]',
				'count'=>0,
				'form'=>$form,
				'itemTypeList'=>$itemTypeList,
			));
		} else {
			$index = 0;
			foreach($model->lines as $line){
				$this->renderPartial('/invoiceLine/_form', array(
					'model'=>$line,
					'namePrefix'=>'Invoice[lines]',
					'count'=>$index,
					'form'=>$form,
					'itemTypeList'=>$itemTypeList,
				));
				$index++;
			}
		}?>
		<tr class="item_row tax">
			<td></td>
			<td><?php echo CHtml::encode($model->getAttributeLabel('TAX_RATE'));?></td>
			<td></td>
			<td><?php echo $form->textField($model, 'TAX_RATE');?></td>
			<td><?php echo CHtml::textField('tax_rate', $model->total * $model->TAX_RATE / 100, array(
				'class'=>'tax_rate',
			));?></td>
		</tr>
	</table>

	<table id="line_prototype" style="display: none;">
		<?php $this->renderPartial('/invoiceLine/_form', array(
			'model'=>new InvoiceLine,
			'namePrefix'=>'Invoice[lines]',
			'count'=>'%%%COUNT%%%',
			'form'=>$form,
			'itemTypeList'=>$itemTypeList,
		));?>
	</table><!-- don't remove this element!-->

	<div class="row buttons">
		<?php echo CHtml::hiddenField('line_count', $model->isNewRecord ? 1 : $index, array(
			'id'=>'line_count',
		));?>
		<?php echo CHtml::button('Add Line', array(
			'onclick'=>"addLine('#line_count', $('#line_prototype').children(), '%%%COUNT%%%');"
		));?>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save');?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->