<?php
/*Wraps job to a TRNS record in QuickBooks IIF.*/
class QBTransaction_Job extends QBTransaction {
	protected function createRecord(){
		$record = $this->initItem();
		$record['TRNSTYPE'] = 'INVOICE';
		$record['DATE'] = date('n/j/Y', strtotime($this->owner->printDate));
		$record['ACCNT'] = QBConstants::TRNS_ACCNT; //need a setting
		$record['NAME'] = $this->owner->CUSTOMER->summary;
		$record['AMOUNT'] = $this->owner->total * (1 + $this->owner->additionalFees[Job::FEE_TAX_RATE]['VALUE'] / 100);
		$record['DOCNUM'] = 'GUS-J-' . $this->owner->ID;
		$record['CLEAR'] = 'N';
		$record['TOPRINT'] = 'N';
		$record['DUEDATE'] = date('n/j/Y', strtotime($this->owner->printDate));
		$record['PAID'] = 'N';
		$record['INVTITLE'] = $this->owner->NAME;
		$record['NAMEISTAXABLE'] = 'Y';
		return $record;
	}
}