<?php
/*When attached to a job, attaches the appropriate behaviors to the job and associated job lines for export to quickbooks.*/
YiiBase::import('application.models.QuickBooks.*');
class QBInitializer extends CActiveRecordBehavior {
	public function attach($owner){
		parent::attach($owner);
		$owner->attachBehavior('transaction', 'application.models.QuickBooks.QBTransaction_Job');
		$owner->attachBehavior('transactionLines', 'application.models.QuickBooks.QBTransactionLine_Job');
		$owner->attachBehavior('inventoryLines', 'application.models.QuickBooks.QBInventoryLine_Job');
		foreach($owner->jobLines as $line){
			$line->attachBehavior('transactionLines', 'application.models.QuickBooks.QBTransactionLine_JobLine');
			$line->attachBehavior('inventoryLines', 'application.models.QuickBooks.QBInventoryLine_JobLine');
		}
	}

	public function detach($owner){
		parent::detach($owner);
		$owner->detachBehavior('transaction');
		$owner->detachBehavior('transactionLines');
		$owner->detachBehavior('inventoryLines');
		foreach($owner->jobLines as $line){
			$line->detachBehavior('transactionLines');
			$line->detachBehavior('inventoryLines');
		}	
	}
}