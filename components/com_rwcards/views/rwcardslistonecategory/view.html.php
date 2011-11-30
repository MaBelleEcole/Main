<?php
/**
* RWCards View for ListOneCategory
* 
* @author Ralf Weber  <ralf@weberr.de>
* @version 3.0
* @copyright Copyright (c) 2007, LoadBrain
* @link http://www.weberr.de/
*
* @license GNU/GPL
*/
jimport( 'joomla.application.component.view');


class RWCardsViewRWCardsListOneCategory extends JView
{
	function display($tpl = null)
	{
		$app = &JFactory::getApplication();
		$params =& $app->getParams('com_rwcards');
		$this->assignRef( 'params', $params );
		
		$category_id = JRequest::getVar('category_id', 0, 'request', 'int');
		
		if ( $category_id == 0 ) $category_id = $params->get( 'category_id', 0 );
		
		// if it's still zero, look for the the 1st valid one...
		
		if ( $category_id == 0 ) {
			$category_id = $this->get( 'FirstValidCategory' );
		}
		
		$this->assignRef( 'category_id', $category_id);
		$data = $this->get( 'Data' );
		$this->assignRef( 'rwcards', $data );
		$categories = $this->get( 'Categories' );
		$this->assignRef( 'categories', $categories );
		
		$categoryIds = $this->get( 'CategoryIds' );
		$this->assignRef( 'categoryIds', $categoryIds );
		
		// If clicked on rewrite to sender, do not delete session data!
		$reWritetoSender = JRequest::getVar('reWritetoSender', '', 'request', 'string');
		$this->assignRef( 'reWritetoSender', $reWritetoSender );
		$sessionId = JRequest::getVar('sessionId', '', 'request', 'string');
		$this->assignRef( 'sessionId', $sessionId);
		$this->limitstart = JRequest::getVar('limitstart', 0, 'request', 'int');
		$this->assignRef( 'limitstart', $limitstart);
		      //var_dump($_REQUEST);
		parent::display($tpl);
	}
}
?>
                                                                                                