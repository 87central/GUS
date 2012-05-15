<?php

/**
 * Supports the entry of multiple product lines at once. This class essentially represents
 * a single vendor item.
 */
class ProductForm extends CFormModel {
	/**
	 * Holds the array of product lines associated with the product.
	 */
	private $_productLines = array();
	/**
	 * Holds the product associated with this form.
	 */
	private $_product;
	private $_sizes;
	private $_colors;
	
	public function __construct($config = array(), $scenario = ''){
		parent::__construct($scenario);
		if(isset($config['VENDOR_ID']) && isset($config['VENDOR_ITEM_ID'])){
			//find if there is an existing product that matches these criteria
			$product = Product::model()->findByAttributes(array('VENDOR_ITEM_ID'=>$config['VENDOR_ITEM_ID'], 'VENDOR_ID'=>$config['VENDOR_ID']));			
		}
		if(!isset($product)){
			$product = new Product;
			$product->STATUS = Product::PLACEHOLDER;
		}
		$this->_product = $product;
	}
	
	public function getVENDOR_ITEM_ID(){
		return $this->_product->VENDOR_ITEM_ID;
	}
	
	public function setVENDOR_ITEM_ID($value){
		$this->_product->VENDOR_ITEM_ID = $value;
	}
	
	public function getVENDOR_ID(){
		return $this->_product->VENDOR_ID;
	}
	
	public function setVENDOR_ID($value){
		$this->_product->VENDOR_ID = $value;
	}
	
	public function getCOST(){
		return $this->_product->COST;
	}
	
	public function setCOST($value){
		$this->_product->COST = $value;
	}
		
	/**
	 * Returns array of lookup IDs.
	 */
	public function getCOLORS(){
		if(!$this->_colors){			
			$this->_colors = $this->_product->allowedColors;
			$newColors = array();
			foreach($this->_colors as $color){
				$newColors[] = $color->ID;
			}
			$this->_colors = $newColors;
		}
		return $this->_colors;
	}
	
	/**
	 * Can be lookup items or just an array of IDs.
	 */
	public function setCOLORS($value){
		$finalValue = array(); //array of lookup IDs
		
		//normalize the value array.
		foreach($value as $color){
			if(is_object($color)){
				$finalValue[] = $color->ID;
			} else {
				$finalValue[] = $color;
			}
		}
		
		$this->_colors = $finalValue;
	}
	
	public function getSIZES(){
		if(!$this->_sizes){			
			$this->_sizes = $this->_product->allowedSizes;
			$newSizes = array();
			foreach($this->_sizes as $size){
				$newSizes[] = $size->ID;
			}
			$this->_sizes = $newSizes;
		}
		return $this->_sizes;
	}
	
	public function setSIZES($value){
		$finalValue = array(); //array of lookup IDs
		
		//normalize the value array.
		foreach($value as $size){
			if(is_object($size)){
				$finalValue[] = $size->ID;
			} else {
				$finalValue[] = $size;
			}
		}
		
		$this->_sizes = $finalValue;
	}
	
	public function getVendorStyle(){
		return $this->_product->vendorStyle;
	}
	
	public function getIsNewRecord(){		
		return $this->_product->isNewRecord;
	}
	
	public function attributeLabels(){
		return array(
			'VENDOR_ITEM_ID'=>'Item ID',
			'VENDOR_ID'=>'Vendor',
			'COST'=>'Cost',
			'COLORS'=>'Colors',
			'SIZES'=>'Sizes',
		);
	}
	
	public function rules(){
		return array(
			array('VENDOR_ITEM_ID, VENDOR_ID, COST, COLORS, SIZES', 'safe'),
		);
	}
	
	public function save(){
		$result = $this->_product->save();
		if(!$result){
			$this->addErrors($this->_product->errors);
		} else {
			//this might be a pain point if usage ever increases a lot - might delete records
			//which are still needed, etc.
			/*What we need to do is move through all existing product lines for the product,
			 * determine those we want to keep by finding out if the color and sizes is still
			 * selected, add any that are missing, then delete those where the color and size
			 * are no longer selected. This may need to change in the future if we ever need to
			 * keep the product lines around permanently.*/
			$oldProducts = array(); //products that are already here
			$newProducts = array(); //Products that need to be added
			$byeProducts = array(); //products that need to be deleted
			//first pass - build the keyed list of existing products
			//and build the keyed list of products to delete
			foreach($this->_product->lines as $line){
				$key = (string) $line->SIZE . $line->COLOR;
				$oldProducts[$key] = $line;
				$byeProducts[$key] = $line; //assume we're deleting it unless we find it on the next pass
			}			
			//second pass - build the keyed list of products to be added
			foreach($this->SIZES as $size){
				foreach($this->COLORS as $color){
					$key = (string) $size . $color;
					if(isset($oldProducts[$key])){
						unset($byeProducts[$key]);
					} else {
						$newProduct = new ProductLine;
						$newProduct->AVAILABLE = 0;
						$newProduct->PRODUCT_ID = $this->_product->ID;
						$newProduct->COLOR = $color;
						$newProduct->SIZE = $size;
						$newProducts[$key] = $newProduct;
					}
				}
			}
			//third pass - delete the unneeded products out
			foreach($byeProducts as $byeProduct){
				$byeProduct->delete();
			}
			//fourth pass - add the new products in
			foreach($newProducts as $newProduct){
				$newProduct->save();
			}
		}
		return $result;		
	}
}