<?php
/*QBInventoryLine_JobLine wraps a job line and provides fields for export to quickbooks in two properties. The xl property contains fields appropriate to extra-large sizes, and the standard property contains fields appropriate to standard sizes.*/
class QBInventoryLine_JobLine extends QBInventoryLine {
	private $_xl;
	private $_standard;
	
	private function createInvLine($text, $price){
		$params = parent::createLine('Printing_' . $this->owner->ID, $text, $price, 'INVENTORY');
		$params['CUSTFLD1'] = $this->owner->color->TEXT;
		$params['CUSTFLD2'] = $this->owner->job->printJob->FRONT_PASS;
		$params['CUSTFLD3'] = $this->owner->job->printJob->BACK_PASS;
		$params['CUSTFLD4'] = $this->owner->job->printJob->SLEEVE_PASS;
		$params['CUSTFLD5'] = $this->owner->product->vendorStyle;
		return $params;
	}

	/**
	Gets the base text which can be used in the description of each inventory item.
	*/
	private function getBaseText(){
		return $this->owner->job->printJob->FRONT_PASS . ' Front/ ' . $this->owner->job->printJob->BACK_PASS . ' Back/ ' . $this->owner->job->printJob->SLEEVE_PASS . ' Sleeve on ' . $this->owner->product->vendorStyle . ' - ' . $this->owner->color->TEXT . ' ';
	}

	/**
	Gets the INVITEM record for the extra-large sizes of the given job line.
	*/
	public function getXl(){
		if($this->_xl === null){			
			$text = $this->baseText . 'Extra Large';			
			$unit_cost = 0;
			foreach ($this->owner->sizeLines as $sizeLine) {
				$fee = $sizeLine->isExtraLarge;
				if($fee){
					$unit_cost = $this->owner->PRICE * 1 + $fee;
					break;
				}	
			}			
			$this->_xl = $this->createInvLine($text, $unit_cost);
		}
		return $this->_xl;
	}

	/**
	Gets the INVITEM record for the standard sizes of the given job line.
	*/
	public function getStandard(){
		if($this->_standard === null){
			$text = $this->baseText . 'Standard';
			$this->_standard = $this->createInvLine($text, $this->owner->PRICE * 1);	
		}
		return $this->_standard;
	}	

	protected function createRecords(){
		return array($this->standard, $this->xl);
	}
}