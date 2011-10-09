<div id="<?php echo $type.'_container'?>" class="item_container">
	<h3><?php echo CHtml::encode($type);?></h3>
	<div class="items">
		<?php foreach($items as $lookup){?>
			<span id="<?php echo $lookup->ID;?>">
				<?php echo CHtml::button('Remove', array(
					'class'=>'remove_button',
				));?>&nbsp;
				<?php echo CHtml::encode($lookup->TEXT);?>
			</span>
			<br/>
		<?php }?>
	</div>
	<?php echo CHtml::textField('new_text', '', array('class'=>'new_text'));?>
	<?php echo CHtml::button('Add', array(
		'class'=>'add_new',
	));?>
</div>

<?php Yii::app()->clientScript->registerCoreScript('jquery');?>
<?php Yii::app()->clientScript->registerScript($type.'-add', "" .
		"$('#".$type."_container').find('.add_new').live('click', function(event){
			$.ajax({
				url: '".CHtml::normalizeUrl(array('lookup/add', 'type'=>$type))."'," .
				"type: 'POST'," .
				"data: {
					text: $('#".$type."_container').find('.new_text').val(),
				}," .
				"success: function(data){
					$('#".$type."_container').replaceWith(data);
				},
			});
		})", 
CClientScript::POS_END); ?>

<?php Yii::app()->clientScript->registerScript($type.'-remove', "" .
		"$('#".$type."_container').find('.remove_button').live('click', function(event){
			$.ajax({
				url: '".CHtml::normalizeUrl(array('lookup/remove'))."'," .
				"type: 'POST'," .
				"data: {
					id: $(event.target).parent().attr('id'),
				}," .
				"success: function(data){
					var parent = $(event.target).parent();" .
					"var breakLine = parent.next('br');" .
					"parent.remove();" .
					"breakLine.remove();
				},
			});
		})", 
CClientScript::POS_END); ?>