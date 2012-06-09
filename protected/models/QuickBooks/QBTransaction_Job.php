<?php
/*Wraps job to a TRNS record in QuickBooks IIF.*/
require_once(YiiBase::getPathOfAlias('application.models.QuickBooks').DIRECTORY_SEPARATOR.'QBTransaction.php');
class QBTransaction_Job extends QBTransaction {
	protected function createRecord(){
		$record = $this->initItem();
		$record['TRNSTYPE'] = 'INVOICE';
		$record['DATE'] = $this->owner->dueDate;
		$record['ACCNT'] = null; //need a setting
		$record['NAME'] = $this->owner->CUSTOMER->summary;
		$record['AMOUNT'] = $this->owner->total * (1 + $this->owner->additionalFees[Job::FEE_TAX_RATE]['VALUE']);
		$record['DOCNUM'] = 'GUS-J-' . $this->owner->ID;
		$record['CLEAR'] = 'N';
		$record['TOPRINT'] = 'N';
		$record['DUEDATE'] = $this->owner->dueDate;
		$record['PAID'] = 'N';
		$record['INVTITLE'] = $this->owner->NAME;
		$record['NAMEISTAXABLE'] = 'Y';
		return $record;
	}
}