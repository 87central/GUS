<?php
/*QBTransactionLine_InvoiceLine wraps an invoice line and provides records for export to quickbooks*/
class QBTransactionLine_InvoiceLine extends QBTransactionLine {
	protected function createRecords(){
		$account = null;
		switch($this->owner->ITEM_TYPE_ID){
			case InvoiceLine::GENERAL : $account = QBConstants::TRNS_ACCNT; 
										break;
			case InvoiceLine::PRINTING : $account = QBConstants::PRINTING_ACCNT; 
										 break;
			case InvoiceLine::ARTWORK : $account = QBConstants::ART_ACCNT; 
										break;
			case InvoiceLine::SETUP : $account = QBConstants::SETUP_ACCNT; 
									  break;
			case InvoiceLine::RUSH : $account = QBConstants::RUSH_ACCNT; 
									 break;
		}
		$params = $this->initItem();
		$params['SPLID'] = $this->owner->ID;
		$params['TRNSTYPE'] = 'INVOICE';
		$params['DATE'] = date('n/j/Y', strtotime($this->owner->INVOICE->DATE)); //may need to format this
		$params['NAME'] = $this->owner->INVOICE->CUSTOMER->summary;
		$params['AMOUNT'] = $this->owner->AMOUNT;
		$params['DOCNUM'] = 'GUS-I-' . $this->owner->INVOICE_ID;
		$params['CLEAR'] = 'N';
		$params['PRICE'] = $this->owner->RATE;
		$params['QNTY'] = $this->owner->QUANTITY;
		$params['INVITEM'] = $this->owner->DESCRIPTION;
		$params['TAXABLE'] = 'Y';
		$params['ACCNT'] = $account;
		return array($params);		
	}
}