<?php

/**
 * Supports the entry of multiple product lines at once. This class essentially represents
 * a single vendor item.
 */
class ProductForm extends CFormModel {
	/**
	 * Holds the array of products associated with the VENDOR_ITEM_ID.
	 */
	private $_products = array();
	private $_itemID;//the current vendor item ID.
	private $_vendorID; //the current vendor ID.
	private $_sizes;
	private $_colors;
	private $_style;
	private $_cost;
	
	public function getVENDOR_ITEM_ID(){
		return $this->_itemID;
	}
	
	public function setVENDOR_ITEM_ID($value){
		$this->updateProducts($value, $this->_vendorID);
		$this->_itemID = $value;
	}
	
	public function getVENDOR_ID(){
		return $this->_vendorID;
	}
	
	public function setVENDOR_ID($value){
		$this->updateProducts($this->_itemID, $value);
		$this->_vendorID = $value;
	}
	
	public function getCOST(){
		if(!$this->_cost){
			$this->_cost = Product::getCost($this->VENDOR_ITEM_ID);
		}
		return $this->_cost;
	}
	
	public function setCOST($value){
		$this->_cost = $value;
		foreach($this->products as $product){
			$product->COST = $value;
		}
	}
	
	public function getSTYLE(){
		if(!$this->_style){
			$this->_style = Product::getStyle($this->VENDOR_ITEM_ID);
		}
		return $this->_style;
	}
	
	public function setSTYLE($value){
		$this->_style = $value;
		foreach($this->products as $product){
			$product->STYLE = $value->ID;
		}
	}
		
	/**
	 * Returns array of lookup IDs.
	 */
	public function getCOLORS(){
		if(!$this->_colors){
			$this->_colors = Product::getAllowedColors($this->VENDOR_ITEM_ID);
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
		$newProducts = array();
		$oldProducts = $this->products;
		
		//normalize the value array.
		foreach($value as $color){
			if(is_object($color)){
				$finalValue[] = $color->ID;
			} else {
				$finalValue[] = $color;
			}
		}
		//loop through the existing products and see if each color
		//is available for every size and style combination. if a "gap"
		//is found, add a new product with the appropriate attribute values.
		//if colors are found that are not in the array, (TODO)
		foreach($this->SIZES as $size){
			foreach($finalValue as $color){
				$key = (string) $this->STYLE->ID . $size . $color;
				
				if(!isset($oldProducts[$key])){
					$newProduct = new Product;
					$newProduct->STYLE = $this->STYLE->ID;
					$newProduct->COST = $this->COST;
					$newProduct->SIZE = $size;
					$newProduct->COLOR = $color;
					$newProduct->VENDOR_ID = $this->VENDOR_ID;
					$newProduct->VENDOR_ITEM_ID = $this->VENDOR_ITEM_ID;
					$newProduct->STATUS = Product::PLACEHOLDER;
					$newProducts[$key] = $newProduct;
				} else {
					$newProducts[$key] = $oldProducts[$key];
				}
			}
		}		
		
		foreach($oldProducts as $toDelete){
			if(!$toDelete->isNewRecord){
				$toDelete->delete();
			}
		}
		
		$this->_products = $newProducts;
		$this->_colors = $finalValue;
	}
	
	public function getSIZES(){
		if(!$this->_sizes){
			$this->_sizes = Product::getAllowedSizes($this->VENDOR_ITEM_ID);
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
		$oldProducts = $this->products;
		
		//normalize the value array.
		foreach($value as $size){
			if(is_object($size)){
				$finalValue[] = $size->ID;
			} else {
				$finalValue[] = $size;
			}
		}
		//loop through the existing products and see if each color
		//is available for every color and style combination. if a "gap"
		//is found, add a new product with the appropriate attribute values.
		//if sizes are found that are not in the array, (TODO)
		foreach($this->COLORS as $color){
			foreach($finalValue as $size){
				$key = (string) $this->STYLE->ID . $size . $color;
				
				if(!isset($oldProducts[$key])){
					$newProduct = new Product;
					$newProduct->STYLE = $this->STYLE->ID;
					$newProduct->COST = $this->COST;
					$newProduct->SIZE = $size;
					$newProduct->COLOR = $color;
					$newProduct->VENDOR_ID = $this->VENDOR_ID;
					$newProduct->VENDOR_ITEM_ID = $this->VENDOR_ITEM_ID;					
					$newProduct->STATUS = Product::PLACEHOLDER;
					$newProducts[$key] = $newProduct;
				} else {
					$newProducts[$key] = $oldProducts[$key];
				}
			}
		}
		
		foreach($oldProducts as $toDelete){
			if(!$toDelete->isNewRecord){
				$toDelete->delete();
			}
		}
		
		$this->_products = $newProducts;
		$this->_sizes = $finalValue;
	}
	
	/**
	 * Gets the array of products currently associated with the vendor item ID.
	 */
	public function getProducts(){
		return $this->_products;
	}
	
	public function getVendorStyle(){
		if(count($this->products) > 0){
			return $this->products[0]->vendorStyle;
		} else {
			return 'New Product';
		}
	}
	
	public function updateProducts($itemID, $vendorID){
		$results = Product::model()->findAllByAttributes(array('VENDOR_ITEM_ID'=>$itemID, 'VENDOR_ID'=>$vendorID));
		$newList = array();
		foreach($results as $result){
			$newList[(string)$result->STYLE . $result->COLOR . $result->SIZE] = $result;
		}
		$this->_products = $results;
	}
	
	public function getIsNewRecord(){
		$newRecord = true;
		foreach($this->products as $product){
			if(!$product->isNewRecord){
				$newRecord = false;
				break;
			}
		}
		return $newRecord;
	}
	
	public function attributeLabels(){
		return array(
			'VENDOR_ITEM_ID'=>'Item ID',
			'VENDOR_ID'=>'Vendor',
			'COST'=>'Cost',
			'STYLE'=>'Style',
			'COLORS'=>'Colors',
			'SIZES'=>'Sizes',
		);
	}
	
	public function rules(){
		return array(
			array('VENDOR_ITEM_ID, VENDOR_ID, COST, STYLE, COLORS, SIZES', 'safe'),
		);
	}
}