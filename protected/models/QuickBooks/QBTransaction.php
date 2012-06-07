<?php
/*QBTransactionLine_Job wraps a job  and provides a recordsfor export to QuickBooks TRNS in a record property.*/
class QBTransaction extends CActiveRecordBehavior {
	private $item = null;

	/**
	Creates an array with all fields in the TRNS record type of QuickBooks IIF.
	@return array The array, with all elements set to null.
	*/
	private function createItem(){
		return array(
			'TRNS'=>'TRNS',
			'TRNSID'=>null,
			'TIMESTAMP'=>null,
			'TRNSTYPE'=>null,
			'DATE'=>null,
			'ACCNT'=>null,
			'NAME'=>null,
			'CLASS'=>null,
			'AMOUNT'=>null,
			'DOCNUM'=>null,
			'MEMO'=>null,
			'CLEAR'=>null,
			'TOPRINT'=>null,
			'ADDR1'=>null,
			'ADDR2'=>null,
			'ADDR3'=>null,
			'ADDR4'=>null,
			'ADDR5'=>null,
			'DUEDATE'=>null,
			'TERMS'=>null,
			'PAID'=>null,
			'PAYMETH'=>null,
			'SHIPVIA'=>null,
			'SHIPDATE'=>null,
			'REP'=>null,
			'FOB'=>null,
			'PONUM'=>null,
			'INVTITLE'=>null,
			'INVMEMO'=>null,
			'SADDR1'=>null,
			'SADDR2'=>null,
			'SADDR3'=>null,
			'SADDR4'=>null,
			'SADDR5'=>null,
			'NAMEISTAXABLE'=>null,			
		);
	}

	/**
	Initializes the transaction line array with values that are common to all record types.
	The following values are initialized, while the remaining elements are set to null.
	@return array The TRNS array.
	*/
	protected function initItem(){		
		return $this->createItem();
	}

	/**
	@return array An array containing all TRNS records associated with the decorated class.
	*/
	public function getRecord(){
		if($item === null){
			$item = $this->createRecord();
		}
		return $item;
	}

	/**
	Constructs the array of TRNS records associated with the decorated class. This function need not handle caching.
	@return array An array of TRNS records, with field names as returned by initItem.
	*/
	protected abstract function createRecord();
}