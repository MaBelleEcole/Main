<?php
/**
* RWCards View entry for RWCards
* 
* @author Ralf Weber  <ralf@weberr.de>
* @version 3.0
* @copyright Copyright (c) 2007, LoadBrain
* @link http://www.weberr.de/
*
* @license GNU/GPL
*/

jimport( 'joomla.application.component.view');

class RWcardsViewrwcards extends JView
{
	function display($tpl = null)
	{
		$reWritetoSender = false;
		$task = JRequest::getVar('task', '', 'request', 'string');
		$sessionId = JRequest::getVar('sessionId', '', 'request', 'string');
		
		$params =& JComponentHelper::getParams( 'com_rwcards' );
		$suffix = '@' . $params->get("thumbnail_suffix", 'rwcards' );
		$this->assignRef( 'suffix', $suffix );		
		$this->assignRef( 'params', $params );		

		// If clicked on rewrite to sender, do not delete session data!
		if ($task != "rwcardsReWriteCard")
		{
			$_SESSION['rwcardsSession'] = "";
		}
		else
		{
			$reWritetoSender = true;
			$this->assignRef( 'reWritetoSender', $reWritetoSender );
			$this->assignRef( 'sessionId', $sessionId );
		}
		
		$data = $this->get( 'Data' );
		$this->assignRef( 'rwcards', $data );

		$categoryData = $this->get( 'CategoryData' );
		$this->assignRef( 'categoryData', $categoryData );

		parent::display($tpl);
	}
}
?>
