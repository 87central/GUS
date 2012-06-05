<?php 
/*QBInventoryLine acts as an abstract base class for behaviors which export a certain record type to a QuickBooks IIF INVITEM format.*/
abstract class QBInventoryLine extends CActiveRecordBehavior {
	/**
	Creates an array with all fields in the INVITEM record type of QuickBooks IIF.
	@return array The array, with all elements set to null.
	*/
	private function createInvItem(){
		return array(
			'INVITEM'=>'INVITEM',
			'NAME'=>null,
			'TIMESTAMP'=>null,
			'REFNUM'=>null,
			'INVITEMTYPE'=>null,
			'DESC'=>null,
			'PURCHASEDESC'=>null,
			'ACCNT'=>null,
			'ASSETACCNT'=>null,
			'COGSACCNT'=>null,
			'PRICE'=>null,
			'COST'=>null,
			'TAXABLE'=>null,
			'PAYMETH'=>null,
			'TAXVEND'=>null,
			'TAXDIST'=>null,
			'TOPRINT'=>null,
			'PREFVEND'=>null,
			'REORDERPOINT'=>null,
			'EXTRA'=>null,
			'CUSTFLD1'=>null,
			'CUSTFLD2'=>null,
			'CUSTFLD3'=>null,
			'CUSTFLD4'=>null,
			'CUSTFLD5'=>null,
			'DEP_TYPE'=>null,
			'ISPASSEDTHRU'=>null,
		);
	}

	/**
	Initializes the inventory item array with values that are common to all record types.
	The following values are initialized, while the remaining elements are set to null:
	ACCNT, ASSETACCNT, COGSACCNT, TAXABLE(Y), TOPRINT (Y), ISPASSEDTHRU (Y)
	@return array The INVITEM array.
	*/
	protected function initInvItem(){
		$params = $this->createInvItem();
		$params['ACCNT'] = null; //need a setting for this
		$params['ASSETACCNT'] = null; //might need a setting for this
		$params['COGSACCNT'] = null; //and this
		$params['TAXABLE'] = 'Y',
		$params['TOPRINT'] = 'Y',
		$params['ISPASSEDTHRU'] = 'Y',
		return $params;
	}

	/**
	@return array An array containing all INVITEM records associated with the decorated class.
	*/
	public abstract function getRecords();	
}