<?php
class Formatter extends CFormatter {	
	public function formatCurrency($value){
		return Yii::app()->numberFormatter->formatCurrency($value, 'USD');
	}
	
	public function formatLookup($value){
		return Lookup::getText($value);
	}
}