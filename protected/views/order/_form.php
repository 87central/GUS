<?php
Yii::app()->clientScript->registerCoreScript('jquery'); 
Yii::app()->clientScript->registerScript('add-line', "function addLine(sender, namePrefix, status){
	$.ajax({
		url: '".CHtml::normalizeUrl(array('order/newLine'))."'," .
		"type: 'POST'," .
		"data: {
			namePrefix: namePrefix," .
			"count: $(sender).parent().children('.orderLine').size()," .
			"status: status,
		}," .
		"success: function(data){
			$(sender).before(data);
		},
	});
}", CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerCssFile($this->styleDirectory . 'order_form');
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
			));
		} else {
			$index = 0;
			foreach($model->lines as $line){
				$this->renderPartial('//productOrder/_orderForm', array(
					'products'=>$productList,
					'namePrefix'=>CHtml::activeName($model, 'lines') . '[' . $index . ']',
					'model'=>$line,
					'orderStatus'=>$model->STATUS,
				));
				$index++;
			}
		}?>
		<?php echo CHtml::button('Add Line', array(
			'onclick'=>"addLine(this, '".CHtml::activeName($model, 'lines')."', ".$model->STATUS.");",
		));?>
	</div>	

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
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
					'value'=>"\data->ID",					
				),
				'summary::Product Summary',
				array(
					'header'=>'Quantity Needed',
					'value'=>"\$data->AVAILABLE * -1", //we will only be getting products with negative inventory
				),
			),
		));
		
		echo CHtml::button('Add Checked Products');
	?>
</div>