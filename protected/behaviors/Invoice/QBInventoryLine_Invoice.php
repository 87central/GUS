<?php 
/*QBInventoryLine_Invoice wraps an invoice and provides records for export to QuickBooks INVITEMS in a records property.*/
class QBInventoryLine_Invoice extends QBInventoryLine {
	/*records which need to be translated:
	rush, art charge, setup time, additional charges, sales tax*/
	

	protected function createSalesTax(){
		return $this->createLine(
			'Sales Tax',
			'Sales Tax',
			$this->owner->TAX_RATE / 100,
			'COMPTAX',
			QBConstants::TAX_ACCNT
		);
	}

	protected function createRecords(){
		$lines = array();
		$lines[] = $this->createSalesTax();
		return $lines;
	}
}