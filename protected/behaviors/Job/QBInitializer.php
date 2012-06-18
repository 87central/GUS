<?php
/*When attached to a job, attaches the appropriate behaviors to the job and associated job lines for export to quickbooks.*/
YiiBase::import('application.behaviors.QuickBooks.*');
YiiBase::import('application.behaviors.Job.*');
YiiBase::import('application.behaviors.JobLine.*');
class QBInitializer extends CActiveRecordBehavior {
	public function attach($owner){
		parent::attach($owner);
		$owner->attachBehavior('transaction', 'application.behaviors.Job.QBTransaction_Job');
		$owner->attachBehavior('transactionLines', 'application.behaviors.Job.QBTransactionLine_Job');
		$owner->attachBehavior('inventoryLines', 'application.behaviors.Job.QBInventoryLine_Job');
		foreach($owner->jobLines as $line){
			$line->attachBehavior('transactionLines', 'application.behaviors.JobLine.QBTransactionLine_JobLine');
			$line->attachBehavior('inventoryLines', 'application.behaviors.JobLine.QBInventoryLine_JobLine');
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