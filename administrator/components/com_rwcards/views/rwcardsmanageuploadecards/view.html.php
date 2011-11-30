<?php
/**
* RWCards View class
* 
* @author Ralf Weber  <ralf@weberr.de>
* @version 3.0
* @copyright Copyright (c) 2007, LoadBrain
* @link http://www.weberr.de/
*
* @license GNU/GPL
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );


class RWCardsViewRWCardsManageUploadEcards extends JView
{
	/**
	 * display method of Hello view
	 * @return void
	 **/
	function display($tpl = null)
	{
		$task =  JRequest::getVar('task');
		$this->getData( $tpl );
	}

	function getData( $tpl )
	{
		$rwcardsImages =& $this->get('Images');
                
		JToolBarHelper::title(   JText::_( 'UPLOAD ECARDS' ), 'rwcards' );
		JToolBarHelper::preferences('com_rwcards', '400');

		JHTML::_('stylesheet', 'rwcards.css', 'administrator/components/com_rwcards/css/');
                
   		$this->assignRef('rwcardsImages', $rwcardsImages);

		$params =& JComponentHelper::getParams( 'com_rwcards' );
   		$this->assignRef('params', $params);

		parent::display($tpl);
		
	}	
}
