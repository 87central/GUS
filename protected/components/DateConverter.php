<?php
class DateConverter extends CComponent {
	private static $DB_DATE = 'Y-m-d';
	private static $DB_TIME24 = 'H:i:s';
	private static $USR_DATE = 'l, F d, Y';
	private static $USR_TIME12 = 'h:i A';
	private static $USR_TIME24 = 'H:i';
	
	/**
	 * Converts a unix timestamp to a format which can be displayed to the user.
	 * @param long $unixTime The time to convert.
	 * @param boolean $includeTime True to include the time with the date, otherwise false.
	 * @param boolean $twelveHour True to use twelve-hour time.
	 * @return string The date time string.
	 */
	public static function toUserTime($unixTime, $includeTime = false, $twelveHour = false){
		$format = DateConverter::$USR_DATE;
		if($includeTime){
			if($twelveHour){ 
				$format = $format . ' ' .DateConverter::$USR_TIME12;
			} else {
				$format = $format . ' ' .DateConverter::$USR_TIME24;
			}
		}
		return date($format, $unixTime);
	}
	
	/**
	 * Converts a unix timestamp to a format which can sent to the database.
	 * @param long $unixTime The time to convert.
	 * @param boolean $includeTime True to include the time with the date, otherwise false.
	 * @return string The date time string.
	 */
	public static function toDatabaseTime($unixTime, $includeTime = false){
		$format = DateConverter::$DB_DATE;
		if($includeTime){
			$format = $format . ' ' .DateConverter::$DB_TIME24;
		}
		return date($format, $unixTime);
	}
}