<?php
Yii::app()->clientScript->registerCoreScript('jquery'); 
Yii::app()->clientScript->registerScript('add-line', "function addLine(sender, namePrefix, status){
	$.ajax({
		url: '".CHtml::normalizeUrl(array('order/newLine'))."'," .
		"type: 'POST'," .
		"data: {
			namePrefix: namePrefix," .
			"count: $(sender).parent().children('.orderLine').size()," .
			"status: status == null ? '' : status,
		}," .
		"success: function(data){
			$(sender).before(data);
		},
	});
}", CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerScript('add-line-with-product', "function addLineWithProduct(sender, namePrefix, status, product, checkbox, count, lines){
	$.ajax({
		url: '".CHtml::normalizeUrl(array('order/newLine', 'id'=>''))."' + product," .
		"type: 'POST'," .
		"data: {
			namePrefix: namePrefix," .
			"count: count," .
			"status: (status == null ? '' : status)," .
			"lines: lines,
		}," .
		"success: function(data){
			$(sender).before(data);" .
			"$(checkbox).parentsUntil('tr').parent().remove();
		},
	});
}", CClientScript::POS_BEGIN);

?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'order-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php $vendors = CHtml::listData($vendors, 'ID', 'NAME');?>
		<?php echo $form->labelEx($model,'VENDOR_ID'); ?>
		<?php echo $form->dropDownList($model,'VENDOR_ID', $vendors); ?>
		<?php echo $form->error($model,'VENDOR_ID'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'EXTERNAL_ID'); ?>
		<?php echo $form->textField($model,'EXTERNAL_ID',array('size'=>60,'maxlength'=>60)); ?>
		<?php echo $form->error($model,'EXTERNAL_ID'); ?>
	</div>
	
	<div id="lines" class="row">
		<?php $productList = CHtml::listData($products, 'ID', 'summary');?>
		<?php  
		if($model->isNewRecord){
			$this->renderPartial('//productOrder/_orderForm', array(
				'products'=>$productList,
				'namePrefix'=>CHtml::activeName($model, 'lines') . '[0]',
				'model'=>new ProductOrder,
				'orderStatus'=>$model->STATUS,
				'lines'=>null,
			));
		} else {
			$index = 0;
			foreach($model->lines as $line){
				$this->renderPartial('//productOrder/_orderForm', array(
					'products'=>$productList,
					'namePrefix'=>CHtml::activeName($model, 'lines') . '[' . $index . ']',
					'model'=>$line,
					'orderStatus'=>$model->STATUS,
					'lines'=>implode($model->jobLineIDs, ','),
				));
				$index++;
			}
		}?>
		<?php echo CHtml::button('Add Line', array(
			'onclick'=>"addLine(this, '".CHtml::activeName($model, 'lines')."', ".($model->STATUS == null ? 'null' : $model->STATUS).");",
		));?>
	</div>	

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
		<?php if(!$model->isNewRecord){
			if($model->canPlace){
				echo CHtml::link('Place Order', array('order/place', 'id'=>$model->ID, 'view'=>'update'));
			}
			if($model->canCheckin){
				echo CHtml::link('Check In', array('order/checkin', 'id'=>$model->ID, 'view'=>'update'));
			}
		}?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<div class="products">
	<?php 
		$this->widget('zii.widgets.grid.CGridView', array(
			'dataProvider'=>$neededProductsProvider,
			'columns'=>array(
				array(
					'class'=>'CCheckBoxColumn',
					'value'=>"\$data['PRODUCT']->ID",					
				),
				array(
					'header'=>'Product Summary',
					'value'=>"\$data['PRODUCT']->summary",
				),
				array(
					'header'=>'Quantity Needed',
					'value'=>"\$data['PRODUCT']->AVAILABLE * -1", //we will only be getting products with negative inventory
				),
				array(
					'value'=>"CHtml::hiddenField('jobLines', \$data['LINES'])",
					'type'=>'raw',
					'htmlOptions'=>array(
						'style'=>'display: none;',
						'class'=>'jobLines',
					),
					'headerHtmlOptions'=>array(
						'style'=>'display: none;',
					),
					'footerHtmlOptions'=>array(
						'style'=>'display: none;',
					),
				),
			),
			'selectableRows'=>2,
			'summaryText'=>'',
		));
		
		echo CHtml::button('Add Checked Products', array(
			'onclick'=>"var count = $('.orderLine').size();$('.products tbody :checked').each(function(index){
				addLineWithProduct($('#lines').children(':button').first()[0], '".CHtml::activeName($model, 'lines')."', ".($model->STATUS == null ? 'null' : $model->STATUS).", $(this).val(), this, count++, $(this).parent().parent().children('.jobLines').children(':hidden').val());
			});"
		));
	?>
</div>