<?php
/*QBInventoryLine_Job wraps a job  and provides records for export to QuickBooks INVITEMS in a records property.*/
class QBInventoryLine_Job extends QBInventoryLine {
	/*records which need to be translated:
	rush, art charge, setup time, additional charges, sales tax*/
	protected function createRush(){		
		return $this->createLine(
			CHtml::encode($this->owner->getAttributeLabel('RUSH')), 
			'Fee for accelerated handling', 
			$this->owner->RUSH,
			'OTHC'
		);
	}

	protected function createArtCharge(){
		return $this->createLine(
			'Artwork Charge',
			'Fee for design work',
			40, //hourly rate
			'SERV'
		);
	}

	protected function createSetupFee(){
		return $this->createLine(
			'Setup Time',
			'Fee for setup (waived for larger orders)',
			30, //hourly rate
			'SERV'
		);
	}

	protected function createSalesTax(){
		return $this->createLine(
			'Sales Tax',
			'Sales Tax',
			$this->owner->additionalFees[Job::FEE_TAX_RATE]['VALUE'] / 100,
			'COMPTAX'
		);
	}

	protected function createAdditional($additional, $index){
		return $this->createLine(
			'Additional_'.$index,
			$additional['TEXT'],			
			null,
			'OTHC'
		);
	}

	protected function crateRecords(){
		$lines = array();
		$lines[] = $this->createRush();
		$lines[] = $this->createArtCharge();
		$lines[] = $this->createSetupFee();			
		$index = 0;
		foreach ($this->owner->additionalFees as $fee) {
			if($fee['CONSTRAINTS']['part'] !== false){
				$lines[] = $this->createAdditional($fee, $index);
			}
		}
		$lines[] = $this->createSalesTax();
		return $lines;
	}
}