<?php
/*QBInventoryLine_InvoiceLine wraps an inventory line and provides fields for export to quickbooks.*/
class QBInventoryLine_InvoiceLine extends QBInventoryLine {
	protected function createRecords(){
		$id = $this->owner->ID;
		$text = $this->owner->DESCRIPTION;
		$price = ($this->owner->QUANTITY != 0) ? $this->owner->AMOUNT / $this->owner->QUANTITY : $this->owner->AMOUNT;
		$category = null;
		$account = null;
		
		switch($this->owner->ITEM_TYPE_ID){
			case InvoiceLine::GENERAL : $category = 'OTHC'; 
										$account = QBConstants::TRNS_ACCNT; 
										break;
			case InvoiceLine::PRINTING : $category = 'INVENTORY';
										 $account = QBConstants::PRINTING_ACCNT; 
										 break;
			case InvoiceLine::ARTWORK : $category = 'SERV'; 
										$account = QBConstants::ART_ACCNT; 
										break;
			case InvoiceLine::SETUP : $category = 'SERV'; 
									  $account = QBConstants::SETUP_ACCNT; 
									  break;
			case InvoiceLine::RUSH : $category = 'OTHC'; 
									 $account = QBConstants::RUSH_ACCNT; 
									 break;
		} 
		return array($this->createLine($id, $text, $price, $category, $account));
	}
}