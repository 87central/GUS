<?php
/**
QBTransactionLine acts as an abstract base class for behaviors which export a certain record type to a QuickBooks IIF SPL format.
*/
class QBTransactionLine extends CActiveRecordBehavior {
	private $lines = null;

	/**
	Creates an array with all fields in the SPL record type of QuickBooks IIF.
	@return array The array, with all elements set to null.
	*/
	private function createItem(){
		return array(
			'SPL'=>'SPL',
			'SPLID'=>null,
			'TRNSTYPE'=>null,			
			'DATE'=>null,
			'ACCNT'=>null,
			'NAME'=>null,
			'CLASS'=>null,
			'AMOUNT'=>null,
			'DOCNUM'=>null,
			'MEMO'=>null,
			'CLEAR'=>null,
			'PRICE'=>null,
			'QNTY'=>null,
			'INVITEM'=>null,
			'PAYMETH'=>null,
			'TAXABLE'=>null,
			'REIMBEXP'=>null,
			'EXTRA'=>null,
			'VALDAJ'=>null,			
		)
	}

	/**
	Initializes the transaction line array with values that are common to all record types.
	The following values are initialized, while the remaining elements are set to null:
	ACCNT, TAXABLE(Y), VALDADJ(N)
	@return array The SPL array.
	*/
	protected function initItem(){
		$params = $this->createItem();
		$params['VALDAJ'] = 'N',
		$params['TAXABLE'] = 'Y',		
		$params['ACCNT'] = null, //need a setting for this?
		return $params;
	}

	/**
	@return array An array containing all SPL records associated with the decorated class.
	*/
	public function getRecords(){
		if($lines === null){
			$lines = $this->createRecords();
		}
		return $lines;
	}

	/**
	Constructs the array of SPL records associated with the decorated class. This function need not handle caching.
	@return array An array of SPL records, with field names as returned by initItem.
	*/
	protected abstract function createRecords();
}