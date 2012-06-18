<?php
/*When attached to a job, attaches the appropriate behaviors to the job and associated job lines for export to quickbooks.*/
YiiBase::import('application.behaviors.QuickBooks.*');
YiiBase::import('application.behaviors.Invoice.*');
YiiBase::import('application.behaviors.InvoiceLine.*');
class QBInitializer extends CActiveRecordBehavior {
	public function attach($owner){
		parent::attach($owner);
		$owner->attachBehavior('transaction', 'application.behaviors.Invoice.QBTransaction_Invoice');
		$owner->attachBehavior('transactionLines', 'application.behaviors.Invoice.QBTransactionLine_Invoice');
		$owner->attachBehavior('inventoryLines', 'application.behaviors.Invoice.QBInventoryLine_Invoice');
		foreach($owner->lines as $line){
			$line->attachBehavior('transactionLines', 'application.behaviors.InvoiceLine.QBTransactionLine_InvoiceLine');
			$line->attachBehavior('inventoryLines', 'application.behaviors.InvoiceLine.QBInventoryLine_InvoiceLine');
		}
	}

	public function detach($owner){
		parent::detach($owner);
		$owner->detachBehavior('transaction');
		$owner->detachBehavior('transactionLines');
		$owner->detachBehavior('inventoryLines');
		foreach($owner->lines as $line){
			$line->detachBehavior('transactionLines');
			$line->detachBehavior('inventoryLines');
		}	
	}
}