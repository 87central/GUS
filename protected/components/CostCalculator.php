<?php
/**
 * Provides a simple way to calculate the price for garments in a job.
 */ 
class CostCalculator extends CComponent {
	/**
	 * Calculates the total for the garment section of a job.
	 * @param int $garments The number of garments involved.
	 * @param int $frontPasses The number of passes over the front of the garment.
	 * @param int $backPasses The number of passes over the back of the garment.
	 * @param int $sleevePasses The number of passes over the sleeves of the garment.
	 * @param float $surcharge Any amount that should be added to (or removed) from the price of each garment.
	 * @return float The <i>total</i> price of the garment section. 
	 */
	public static function calculateTotal($garments, $frontPasses, $backPasses, $sleevePasses, $surcharge){
		$result = 0;
		switch($frontPasses + $backPasses + $sleevePasses){
			case 1 : $thresholds = array(3.50, 4.50, 6.50, 9, 12);
					 break;
			case 2 : $thresholds = array(4.20, 5.30, 7.50, 12, 14);
					 break;
			default : $thresholds = array(5.25, 7.30, 10, 14, 16);
					  break;
		}
		$cutoffs = array(200, 100, 50, 24, 12); //must be backwards, otherwise the last item would almost always be greater.
		for($i = 0; $i < count($cutoffs); $i++){
			if($garments < $cutoffs[$i]){
				$result = $thresholds[$i];
			}
		}
		$result += $surcharge;
		$result *= $garments;
		return $result;
	}
}