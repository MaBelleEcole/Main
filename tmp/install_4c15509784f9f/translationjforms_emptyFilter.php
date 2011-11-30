<?php
/**
* Empty filter
*
* @version		$Id: translationjforms_emptyFilter.php 31 2008-12-12 07:39:37Z dr_drsh $
* @package		Joomla
* @subpackage	JForms
* @copyright	Copyright (C) 2008 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// Don't allow direct linking
defined( 'JPATH_BASE' ) or die( 'Direct Access to this location is not allowed.' );

class translationjforms_emptyFilter extends translationFilter
{
	function translationjforms_emptyFilter ($contentElement){
		$this->filterNullValue=-1;
		$this->filterType="jforms_empty";
		$this->filterField = $contentElement->getFilter("jforms_empty");
		parent::translationFilter($contentElement);
		
	}
	
	function _createFilter(){
		$db =& JFactory::getDBO();
		if (!$this->filterField ) return "";
		// always hide empty form options
		$filter = 'c.'.$this->filterField.' !=""';
		return $filter;
	}

	function _createfilterHTML(){
		return "";
	}

}
