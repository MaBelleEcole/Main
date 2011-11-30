<?php
/**
* RWCards View for SendCards
* 
* @author Ralf Weber  <ralf@weberr.de>
* @version 3.0
* @copyright Copyright (c) 2007, LoadBrain
* @link http://www.weberr.de/
*
* @license GNU/GPL
*/

jimport( 'joomla.application.component.view');


class RWCardsViewRWCardsSendCard extends JView
{
	
	function display($tpl = null)
	{
		$task =  JRequest::getVar('task', '', 'request', 'string');		
		
		$app = &JFactory::getApplication();
		$params =& $app->getParams('com_rwcards');
		$this->assignRef( 'params', $params );
		
		switch($task)
		{
			case "viewCard":
				$this->viewCard( $tpl );
				break;
			
			default:
				$data = $this->get( 'SaveSenderData' );
				$this->assignRef( 'rwcards', $data );
				$Itemid = JRequest::getVar('Itemid', 0, 'request', 'int');
				$this->assignRef( 'Itemid', $Itemid );
				$task = JRequest::getVar('task', 0, 'request', 'int');
				$this->assignRef( 'task', $task );
				
				$data = $this->get( 'Data' );
				$this->assignRef( 'rwcards', $data );
				
				$viewCardOnly = false;
				$this->assignRef( 'viewCardOnly', $viewCardOnly );
		        
				$viewing = false;
				$this->assignRef( 'viewing', $viewing );
		
				parent::display($tpl);
			break;
		}
	}

	function viewCard( $tpl )
	{
		global $mainframe;	
		
		$Itemid = JRequest::getVar('Itemid', 0, 'request', 'int');
		$this->assignRef( 'Itemid', $Itemid );	
        $viewCardOnly = JRequest::getVar('read', '', 'request', 'string');
		$this->assignRef( 'viewCardOnly', $viewCardOnly );
		$data = $this->get( 'ViewCardsData' );
		$this->assignRef( 'rwcards', $data );
		
		// if there's no data, we can't view it.
		$hasData = ( is_array( $data ) and count( $data ) and array_key_exists( '0', $data ) );
		$this->assignRef( 'hasData', $hasData );
		
		$viewing = true;
		$this->assignRef( 'viewing', $viewing );
		
		parent::display($tpl);
	}
}
?>
