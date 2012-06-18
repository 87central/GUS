<h1>Create Invoice</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'newCustomer'=>$newCustomer,
			'customerList'=>$customerList,
			'itemTypeList'=>$itemTypeList,
			'formatter'=>$formatter)); ?>