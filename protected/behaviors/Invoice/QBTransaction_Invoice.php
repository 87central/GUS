<?php
/*Wraps invoice to a TRNS record in QuickBooks IIF.*/
class QBTransaction_Invoice extends QBTransaction {
	protected function createRecord(){
		$record = $this->initItem();
		$record['TRNSTYPE'] = 'INVOICE';
		$record['DATE'] = date('n/j/Y', strtotime($this->owner->DATE));
		$record['ACCNT'] = QBConstants::TRNS_ACCNT; //need a setting
		$record['NAME'] = $this->owner->CUSTOMER->summary;
		$record['AMOUNT'] = $this->owner->total * (1 + $this->owner->TAX_RATE / 100);
		$record['DOCNUM'] = 'GUS-I-' . $this->owner->ID;
		$record['CLEAR'] = 'N';
		$record['TOPRINT'] = 'N';
		$record['DUEDATE'] = date('n/j/Y', strtotime($this->owner->DATE));
		$record['PAID'] = 'N';
		$record['INVTITLE'] = $this->owner->TITLE;
		$record['NAMEISTAXABLE'] = 'Y';
		return $record;
	}
}