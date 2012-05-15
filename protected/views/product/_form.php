<?php 
$cs = Yii::app()->clientScript;
$cs->registerCoreScript('jquery');
$cs->registerScript('set-selector-data', "" .
		"\$('#reg').data('max', 7).data('current', 0);" .
		"\$('#unisex').data('max', 7).data('current', 0);" .
		"\$('#girls').data('max', 6).data('current', 0);" .
		"\$('#infant').data('max', 5).data('current', 0);" .
		"\$('#kids').data('max', 6).data('current', 0);" .
		"\$('#youth').data('max', 5).data('current', 0);" .
		"\$('#all-colors').data('max', \$('.color-list .checkBoxList').children(':checkbox').size()).data('current', 0);", 
CClientScript::POS_END);
$cs->registerScript('set-checkbox-data', "" .
		"\$('.size-list .checkBoxList').children('[value=\"34\"], [value=\"35\"], [value=\"36\"], [value=\"37\"], [value=\"38\"], [value=\"39\"], [value=\"40\"]').data('group-id', '#reg');" .
		"\$('.size-list .checkBoxList').children('[value=\"47\"], [value=\"48\"], [value=\"49\"], [value=\"50\"], [value=\"51\"], [value=\"73\"], [value=\"74\"]').data('group-id', '#unisex');" .
		"\$('.size-list .checkBoxList').children('[value=\"75\"], [value=\"76\"], [value=\"77\"], [value=\"78\"], [value=\"79\"], [value=\"80\"]').data('group-id', '#girls');" .
		"\$('.size-list .checkBoxList').children('[value=\"81\"], [value=\"82\"], [value=\"83\"], [value=\"84\"], [value=\"85\"]').data('group-id', '#infant');" .
		"\$('.size-list .checkBoxList').children('[value=\"86\"], [value=\"87\"], [value=\"88\"], [value=\"89\"], [value=\"90\"], [value=\"91\"]').data('group-id', '#kids');" .
		"\$('.size-list .checkBoxList').children('[value=\"96\"], [value=\"97\"], [value=\"98\"], [value=\"99\"], [value=\"100\"]').data('group-id', '#youth');" .
		"\$('.color-list .checkBoxList').children(':checkbox').data('group-id', '#all-colors');", 
CClientScript::POS_END);
$cs->registerScript('initialize-selector-state', "" .
		"\$('.size-list :checked, .color-list :checked').each(function(index){
			var target = \$(\$(this).data('group-id'));" .
			"\$(target).data('current', \$(target).data('current') + 1);" .
			"if(\$(target).data('current') == \$(target).data('max')){
				\$(target).removeClass('selected').addClass('selected');
			} else {
				\$(target).removeClass('selected');
			}
		});",
CClientScript::POS_END);
$cs->registerScript('update-selector-state', "" .
		"\$('.size-list :checkbox, .color-list :checkbox').change(function(event){
			var increment = -1;" .
			"if(\$(this).attr('checked') == 'checked'){
				increment = 1;
			}" .
			"var target = \$(\$(this).data('group-id'));" .
			"\$(target).data('current', \$(target).data('current') + increment);" .
			"if(\$(target).data('current') == \$(target).data('max')){
				\$(target).removeClass('selected').addClass('selected');
			} else {
				\$(target).removeClass('selected');
			}
		});", 
CClientScript::POS_END);
?>

<script type="text/javascript">
	function regRun(){
		//34-40
		var checkboxes = $('.size-list .checkBoxList').children('[value="34"], [value="35"], [value="36"], [value="37"], [value="38"], [value="39"], [value="40"]');
		var target = $('#reg');
		if($(target).hasClass('selected')){
			$(checkboxes).removeAttr('checked');
			$(target).removeClass('selected').data('current', 0);						
		} else {
			$(checkboxes).attr('checked', 'checked');
			$(target).addClass('selected').data('current', $(target).data('max'));
		}
	}
	function unisexRun(){
		//47-51, 73, 74
		var checkboxes = $('.size-list .checkBoxList').children('[value="47"], [value="48"], [value="49"], [value="50"], [value="51"], [value="73"], [value="74"]');
		var target = $('#unisex');
		if($(target).hasClass('selected')){
			$(checkboxes).removeAttr('checked');
			$(target).removeClass('selected').data('current', 0);
		} else {
			$(checkboxes).attr('checked', 'checked');
			$(target).addClass('selected').data('current', $(target).data('max'));
		} 
	}
	function girlsRun(){
		//75-80
		var checkboxes = $('.size-list .checkBoxList').children('[value="75"], [value="76"], [value="77"], [value="78"], [value="79"], [value="80"]');
		var target = $('#girls');
		if($(target).hasClass('selected')){
			$(checkboxes).removeAttr('checked');
			$(target).removeClass('selected').data('current', 0);
		} else {
			$(checkboxes).attr('checked', 'checked');
			$(target).addClass('selected').data('current', $(target).data('max'));
		}
	}
	function infantRun(){
		//81-85
		var checkboxes = $('.size-list .checkBoxList').children('[value="81"], [value="82"], [value="83"], [value="84"], [value="85"]');
		var target = $('#infant');
		if($(target).hasClass('selected')){
			$(checkboxes).removeAttr('checked');
			$(target).removeClass('selected').data('current', 0);
		} else {
			$(checkboxes).attr('checked', 'checked');
			$(target).addClass('selected').data('current', $(target).data('max'));
		}
	}	
	function kidsRun(){
		//86-91
		var checkboxes = $('.size-list .checkBoxList').children('[value="86"], [value="87"], [value="88"], [value="89"], [value="90"], [value="91"]');
		var target = $('#kids');
		if($(target).hasClass('selected')){
			$(checkboxes).removeAttr('checked');
			$(target).removeClass('selected').data('current', 0);
		} else {
			$(checkboxes).attr('checked', 'checked');
			$(target).addClass('selected').data('current', $(target).data('max'));
		}
	}
	function youthRun(){
		//96-100
		var checkboxes = $('.size-list .checkBoxList').children('[value="96"], [value="97"], [value="98"], [value="99"], [value="100"]');
		var target = $('#youth');
		if($(target).hasClass('selected')){
			$(checkboxes).removeAttr('checked');
			$(target).removeClass('selected').data('current', 0);
			
		} else {
			$(checkboxes).attr('checked', 'checked');
			$(target).addClass('selected').data('current', $(target).data('max'));
		}
	}
	function selectAllColors(){
		var checkboxes = $('.color-list .checkBoxList').children(':checkbox');
		var target = $('#all-colors');
		if($(target).hasClass('selected')){
			$(checkboxes).removeAttr('checked');
			$(target).removeClass('selected').data('current', 0);			
		} else {
			$(checkboxes).attr('checked', 'checked');
			$(target).addClass('selected').data('current', $(target).data('max'));
		}
	}
	
</script>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'product-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'VENDOR_ITEM_ID');?>
		<?php echo $form->textField($model, 'VENDOR_ITEM_ID', array('size'=>30));?>
		<?php echo $form->error($model, 'VENDOR_ITEM_ID');?>
	</div>
	
	<div class="row">
		<?php $vendors = CHtml::listData($vendorList, 'ID', 'NAME');?>
		<?php echo $form->labelEx($model, 'VENDOR_ID');?>
		<?php echo $form->dropDownList($model, 'VENDOR_ID', $vendors);?>
		<?php echo $form->error($model, 'VENDOR_ID');?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'COST'); ?>
		<?php echo $form->textField($model,'COST',array('size'=>6,'maxlength'=>6)); ?>
		<?php echo $form->error($model,'COST'); ?>
	</div>

	<div class="row seperator color-list">
		<?php echo $form->labelEx($model,'COLORS'); ?>
		<?php //echo $form->dropDownList($model,'COLOR', $colorList); ?>
		<?php echo $form->checkBoxList($model, 'COLORS', $colorList);?>
		<?php echo $form->error($model,'COLORS'); ?>
	</div>	
	<div class="clear"></div>
		<ul class="selectors">
			<li id="all-colors"><a href="javascript:selectAllColors()">ALL COLORS</a></li>
		</ul>
	<div class="clear"></div>
	<div class="row seperator size-list">
		<?php echo $form->labelEx($model,'SIZES'); ?>
		<?php //echo $form->dropDownList($model,'SIZES', $sizeList); ?>
		<?php echo $form->checkBoxList($model, 'SIZES', $sizeList);?>
		<?php echo $form->error($model,'SIZES'); ?>
	</div>
	<div class="clear"></div>
		<ul class="selectors">
			<li id="reg"><a href="javascript:regRun()">REGULAR SIZE RUN</a></li>
			<li id="unisex"><a href="javascript:unisexRun()">UNISEX SIZE RUN</a></li>
			<li id="girls"><a href="javascript:girlsRun()">GIRLS SIZE RUN</a></li>
			<li id="infant"><a href="javascript:infantRun()">INFANT SIZE RUN</a></li>
			<li id="kids"><a href="javascript:kidsRun()">KIDS SIZE RUN</a></li>
			<li id="youth"><a href="javascript:youthRun()">YOUTH SIZE RUN</a></li>
		</ul>
	<div class="clear"></div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->