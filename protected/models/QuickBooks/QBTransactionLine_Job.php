
<?php
/*QBTransactionLine_Job wraps a job  and provides records for export to QuickBooks SPLs in a records property.*/
require_once(YiiBase::getPathOfAlias('application.models.QuickBooks').DIRECTORY_SEPARATOR.'QBTransactionLine.php');
class QBTransactionLine_Job extends QBTransactionLine {
	protected function createLine($id, $amount, $price, $quantity, $invitem, $taxable='Y'){
		$params = $this->initItem();
		$params['SPLID'] = $id; //rush, artcharge, setup fee, additionals, sales tax
		$params['TRNSTYPE'] = 'INVOICE';
		$params['DATE'] = $this->owner->dueDate; //may need to format this
		$params['NAME'] = $this->owner->CUSTOMER->summary;
		$params['AMOUNT'] = $amount;
		$params['DOCNUM'] = 'GUS-J-' . $this->owner->ID;
		$params['CLEAR'] = 'N';
		$params['PRICE'] = $price;
		$params['QNTY'] = $quantity;
		$params['INVITEM'] = $invitem;
		$params['TAXABLE'] = $taxable;
		return $params;		
	}

	protected function createRush(){				
		return $this->createLine(
			'1',
			$this->owner->RUSH,
			null,
			null,
			CHtml::encode($this->owner->getAttributeLabel('RUSH'))
		);
	}

	protected function createArtCharge(){
		return $this->createLine(
			'2',
			$this->owner->printJob->COST,//art charge
			40,
			$this->owner->printJob->COST / 40,
			'Artwork Charge'
		);
	}

	protected function createSetupFee(){		
		return $this->createLine(
			'3',
			$this->owner->SET_UP_FEE,
			30,
			$this->owner->SET_UP_FEE / 30,
			'Setup Time'
		);
	}

	protected function createSalesTax(){		
		return $this->createLine(
			'4',
			$this->owner->total * $this->owner->additionalFees[Job::FEE_TAX_RATE]['VALUE'] / 100,
			null,
			null,
			'Sales Tax',
			'N'
		);
	}

	protected function createAdditional($additional, $index){
		return $this->createLine(
			$index + 5, //5 = the number of standard fields we have
			$additional['VALUE'],
			null,
			null,
			'Additional_'.$index
		);
	}

	protected function createRecords(){
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