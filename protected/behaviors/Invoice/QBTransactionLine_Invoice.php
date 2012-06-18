<?php
/*QBTransactionLine_Invoice wraps a invoice and provides records for export to QuickBooks SPLs in a records property.*/
class QBTransactionLine_Invoice extends QBTransactionLine {
	protected function createLine($id, $amount, $price, $quantity, $invitem, $accnt, $taxable='Y'){
		$params = $this->initItem();
		$params['SPLID'] = $id; //rush, artcharge, setup fee, additionals, sales tax
		$params['TRNSTYPE'] = 'INVOICE';
		$params['DATE'] = date('n/j/Y', strtotime($this->owner->DATE)); //may need to format this
		$params['NAME'] = $this->owner->CUSTOMER->summary;
		$params['AMOUNT'] = $amount;
		$params['DOCNUM'] = 'GUS-I-' . $this->owner->ID;
		$params['CLEAR'] = 'N';
		$params['PRICE'] = $price;
		$params['QNTY'] = $quantity;
		$params['INVITEM'] = $invitem;
		$params['TAXABLE'] = $taxable;
		$params['ACCNT'] = $accnt;
		return $params;		
	}

	protected function createSalesTax(){		
		return $this->createLine(
			'0',
			$this->owner->total * $this->owner->additionalFees[Job::FEE_TAX_RATE]['VALUE'] / 100,
			null,
			null,
			'Sales Tax',			
			QBConstants::TAX_ACCNT,
			'N'
		);
	}

	protected function createRecords(){
		$lines = array();
		$lines[] = $this->createSalesTax();
		return $lines;
	}
}