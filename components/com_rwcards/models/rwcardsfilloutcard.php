<?php
/**
* RWCards Model for FillOutCards
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

jimport( 'joomla.application.component.model' );
require_once (JPATH_COMPONENT.DS.'captcha'.DS. 'class.captcha.php');
/**
 * RWCards Model
 *
 */
class RWCardsModelRWCardsFillOutCard extends JModel
{
	/**
	 * RWCards data array
	 *
	 * @var array
	 */
	var $_data;
	var $rwcards_id;
	/**
	 * Gets the data
	 * @return array The data to be displayed to the user
	 */
	function getData()
	{
		$db =& JFactory::getDBO();
		$rwcards_id = JRequest::getVar('id', 0, 'request', 'int');
		$sessionId = JRequest::getVar('sessionId', '', 'request', 'string');
		

		$rwcards_id = $db->getEscaped( $rwcards_id );
		$sessionId = $db->getEscaped( $sessionId );
		
		// New Card or someone answers
		if ( $sessionId != "")
		{
			$query = "select #__rwcardsdata.*, #__rwcards.* from #__rwcardsdata, #__rwcards where #__rwcards.id = '" . $rwcards_id . "' and #__rwcardsdata.sessionId = '" . $sessionId ."'";
		}
		else
		{
        	$query = "select #__rwcards.* from #__rwcards where #__rwcards.id = '" . $rwcards_id . "'";
		}
		
		$this->_data = $this->_getList( $query );
		
		return $this->_data;
	}

	function getCheckCaptcha()
	{
		// check to see if user already completed one, and don't make them do it again.
		if ( array_key_exists( 'captcha_success', $_SESSION ) and $_SESSION['captcha_success'] ) {
			return true;
		}
		
		$session_code = JRequest::getVar('sessionCode', '', 'request', 'string');
		$captchaCode =  JRequest::getVar('captchaCode', '', 'request', 'string');
        
		if ( empty( $session_code ) ) 
	 	{ 
	 		$session_code = md5(round(rand(0,40000))); 
		} 
		else 
		{ 
			$session_code = $session_code; // FIXME - should this get from the session?
		}	
	
  		$my_captcha = new captcha( $session_code, './components/com_rwcards/captcha/__temp__' ); 

		if ( !$my_captcha->verify( $captchaCode ) )
		{
			//echo "no captcha"; exit;
			return false;
		}
		else
		{
			//echo "yes captcha";
			$_SESSION['captcha_success'] = true;
			return true;
		}
		
	}
}
