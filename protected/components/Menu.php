<?php
/**
 * Extension of CMenu to allow for setting of the CSS class of an active link. 
 * Created for good "meshing" with JQuery navbar layout.
 */
class Menu extends CMenu {
	private $_activeLinkCssClass;
	private $_linkOptions;
	public function getActiveLinkCssClass(){
		return $this->_activeLinkCssClass;
	}
	public function setActiveLinkCssClass($value){
		$this->_activeLinkCssClass = $value;
	}
	public function getLinkOptions(){
		return $this->_linkOptions;
	}
	public function setLinkOptions($value){
		$this->_linkOptions = $value;
	}
	
	protected function normalizeItems($items, $route, &$active){
		$normalizedItems = parent::normalizeItems($items, $route, $active);		
		foreach($normalizedItems as $i=>$item){
			if(isset($normalizedItems[$i]['active']) && $normalizedItems[$i]['active'] === true){
				if(isset($normalizedItems[$i]['linkOptions'])){
					if(isset($this->activeLinkCssClass)){
						if(isset($item['linkOptions']['class'])){
							$normalizedItems[$i]['linkOptions']['class'] .= ' ' . $this->activeLinkCssClass;
						} else {
							$normalizedItems[$i]['linkOptions']['class'] = ' ' . $this->activeLinkCssClass; 
						}
					}
				} else {
					if(isset($this->linkOptions)){
						$normalizedItems[$i]['linkOptions'] = $this->linkOptions;	
					} else {
						$normalizedItems[$i]['linkOptions'] = array();
					}
					if(isset($this->activeLinkCssClass) && $active){						
						$normalizedItems[$i]['linkOptions']['class'] = $this->activeLinkCssClass;
					}
				}
			}
			if(!isset($normalizedItems[$i]['linkOptions'])){
				$normalizedItems[$i]['linkOptions'] = $this->linkOptions;
			}
		}
		return array_values($normalizedItems);
	}
}
