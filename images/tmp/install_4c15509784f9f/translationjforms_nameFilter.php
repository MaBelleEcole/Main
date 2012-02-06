<?php
/**
* Form name filter
*
* @version		$Id: translationjforms_nameFilter.php 24 2008-11-01 13:52:45Z dr_drsh $
* @package		Joomla
* @subpackage	JForms
* @copyright	Copyright (C) 2008 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// Don't allow direct linking
defined( 'JPATH_BASE' ) or die( 'Direct Access to this location is not allowed.' );

class translationjforms_nameFilter extends translationFilter
{
	function translationjforms_nameFilter ($contentElement){
		$this->filterNullValue=-1;
		$this->filterType="jforms_name";
		$this->filterField = $contentElement->getFilter("jforms_name");
		parent::translationFilter($contentElement);
	}

	function _createfilterHTML(){
		$db =& JFactory::getDBO();

		if (!$this->filterField) return "";
		$formnameOptions=array();
		$formnameOptions[] = JHTML::_('select.option', '-1', JText::_('All forms') );

		$sql = "SELECT DISTINCT f.id, f.title FROM #__jforms_forms as f, #__".$this->tableName." as c
			WHERE c.".$this->filterField."=f.id ORDER BY f.title";
		$db->setQuery($sql);
		$cats = $db->loadObjectList();
		
		$catcount=0;
		foreach($cats as $cat){
			$formnameOptions[] = JHTML::_('select.option', $cat->id,$cat->title);
			$catcount++;
		}
		$formnameList=array();
		$formnameList["title"]= JText::_('Which form');
		$formnameList["html"] = JHTML::_('select.genericlist',  $formnameOptions, 'jforms_name_filter_value', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $this->filter_value );

		return $formnameList;
	}

}