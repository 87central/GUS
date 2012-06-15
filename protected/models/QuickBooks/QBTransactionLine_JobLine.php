<?php
/*QBTransactionLine_JobLine wraps a job line and provides records for export to quickbooks*/
class QBTransactionLine_JobLine extends QBTransactionLine {
	protected function getBaseText(){
		return $this->owner->job->printJob->FRONT_PASS . ' Front/ ' . $this->owner->job->printJob->BACK_PASS . ' Back/ ' . $this->owner->job->printJob->SLEEVE_PASS . ' Sleeve on ' . $this->owner->product->vendorStyle . ' - ' . $this->owner->color->TEXT . ' ';
	}

	private function createExtraLarge(){
		$text = $this->baseText . 'Extra Large';
		$unit_cost = 0;
		$quantity = 0;
		$price = 0;
		foreach ($this->owner->sizes as $sizeLine) {
			$fee = $sizeLine->isExtraLarge;
			if($fee){
				$unit_cost = $this->owner->PRICE * 1 + $fee;
				$quantity += $sizeLine->QUANTITY;
				$price += $unit_cost * $sizeLine->QUANTITY;
			}
		}			
		$params = $this->initItem();
		$params['SPLID'] = $this->owner->ID . '2'; //2 for extra large, 1 for standard
		$params['TRNSTYPE'] = 'INVOICE';
		$params['DATE'] = date('n/j/Y', strtotime($this->owner->job->printDate)); //may need to format this
		$params['NAME'] = $this->owner->job->CUSTOMER->summary;
		$params['AMOUNT'] = $price;
		$params['DOCNUM'] = 'GUS-J-' . $this->owner->JOB_ID;
		$params['CLEAR'] = 'N';
		$params['PRICE'] = $unit_cost;
		$params['QNTY'] = $quantity;
		$params['INVITEM'] = $text;
		$params['TAXABLE'] = 'Y';
		$params['ACCNT'] = QBConstants::PRINTING_ACCNT;
		return $params;		
	}

	private function createStandard(){
		$text = $this->baseText . 'Standard';
		$unit_cost = 0;
		$quantity = 0;
		$price = 0;
		foreach ($this->owner->sizes as $sizeLine) {
			$fee = $sizeLine->isExtraLarge;
			if($fee === false){
				$unit_cost = $this->owner->PRICE * 1 + $fee;
				$quantity += $sizeLine->QUANTITY;
				$price += $unit_cost * $sizeLine->QUANTITY;
			}
		}			
		$params = $this->initItem();
		$params['SPLID'] = $this->owner->ID . '1'; //2 for extra large, 1 for standard
		$params['TRNSTYPE'] = 'INVOICE';
		$params['DATE'] = date('n/j/Y', strtotime($this->owner->job->printDate)); //may need to format this
		$params['NAME'] = $this->owner->job->CUSTOMER->summary;
		$params['AMOUNT'] = $price;
		$params['DOCNUM'] = 'GUS-J-' . $this->owner->JOB_ID;
		$params['CLEAR'] = 'N';
		$params['PRICE'] = $unit_cost;
		$params['QNTY'] = $quantity;
		$params['INVITEM'] = $text;
		$params['TAXABLE'] = 'Y';
		$params['ACCNT'] = QBConstants::PRINTING_ACCNT;
		return $params;		
	}

	protected function createRecords(){
		return array($this->createStandard(), $this->createExtraLarge());
	}
}