<?php
/**
* RWCards View for FillOutCards
* 
* @author Ralf Weber  <ralf@weberr.de>
* @version 3.0
* @copyright Copyright (c) 2007, LoadBrain
* @link http://www.weberr.de/
*
* @license GNU/GPL
*/

jimport( 'joomla.application.component.view');


class RWCardsViewRWCardsFillOutCard extends JView
{
	function display($tpl = null)
	{
		$db =& JFactory::getDBO();

		$task =  JRequest::getVar('task', '', 'request', 'string');
		// If clicked on rewrite to sender, do not delete session data!
		$reWritetoSender = JRequest::getVar('reWritetoSender', '', 'request', 'string');
		$this->assignRef( 'reWritetoSender', $reWritetoSender );
		$sessionId = JRequest::getVar('sessionId', '', 'request', 'string');
		$this->assignRef( 'sessionId', $sessionId);
		// Is Captcha activated in config?
		$app = &JFactory::getApplication();
		$params =& $app->getParams('com_rwcards');
		$this->assignRef( 'params', $params);
				
		$checkingCaptcha = false;


		switch($task)
		{
            case "CheckCaptcha":
				$checkingCaptcha = true;
				$this->assignRef( 'checkingCaptcha', $checkingCaptcha );
				
				$this->CheckCaptcha( $tpl );
				break;
		
			default:
				$this->assignRef( 'checkingCaptcha', $checkingCaptcha );
				
				$data = $this->get( 'Data' );
				$this->assignRef( 'rwcards', $data );

				$dataok = ( is_array( $data ) and count( $data ) );		
				$this->assignRef( 'dataok', $dataok );

				$Itemid = JRequest::getCmd('Itemid');
				$this->assignRef( 'Itemid', $Itemid);
				$category_id = JRequest::getVar('category_id', 0, 'request', 'int');
				$this->assignRef( 'category_id', $category_id);
				$sessionCode = JRequest::getVar('sessionCode', 0, 'request', 'int');
				$this->assignRef( 'sessionCode', $sessionCode);
				parent::display($tpl);
				break;
		}
	}

	function CheckCaptcha( $tpl )
	{
		global $mainframe;
				
		foreach ( $_POST as $key=>$value ) {
			$_SESSION['rwcardsSession'][$key] = JRequest::getVar( $key, null, "post");
		}

		$rwCardsError = $this->checkRWCardsForm();
		$rwCardsFieldsError = $this->getFieldsErrors();
		
		$app = &JFactory::getApplication();
		$params =& $app->getParams('com_rwcards');
		
		// If Captcha activated in config, then check it else set it to true
		if ( $params->get( "captcha", true ))
		{
			$isCaptcha = $this->get( 'CheckCaptcha' );
		}
		else
		{
			$isCaptcha = true;
		}
		

		if ($isCaptcha && count($rwCardsError) == 0)
		{
			$submit = JRequest::getVar('submit', '', 'request', 'string');
			$Itemid = JRequest::getCmd('Itemid');
			$id = JRequest::getVar('id', 0, 'request', 'int');
			if ( $submit == JTEXT::_('RWCARDS_PREVIEW_CARD') )
			{
				$mainframe->redirect( str_replace('&amp;','&', JRoute::_( 'index.php?option=com_rwcards&controller=rwcardsprelookcard&task=previewrwcard&Itemid=' . $Itemid . '&id=' . $id )));
			}
			else
			{
				$mainframe->redirect( str_replace('&amp;','&', JRoute::_( 'index.php?option=com_rwcards&controller=rwcardsprelookcard&task=sendrwcard&Itemid=' . $Itemid . '&id=' . $id )));
			}
		}
		else
		{
			$data = $this->get( 'Data' );
			$this->assignRef( 'rwcards', $data );
			$captchaError = true;	
			$this->assignRef( 'captchaError', $captchaError);
			$this->assignRef( 'isCaptcha', $isCaptcha);
			$this->assignRef( 'rwCardsError', $rwCardsError);
			$this->assignRef( 'rwCardsFieldsError', $rwCardsFieldsError);
			parent::display($tpl);
		}
	}
	
	function checkRWCardsForm()
	{
		jimport('joomla.mail.helper'); 
		$rwCardsError = array();
		
		$nameFrom = JRequest::getVar('rwCardsFormNameFrom', '', 'session', 'string');
		if ( $nameFrom == "" ) array_push($rwCardsError, JText::_('RWCARDS_FORM_ERROR_NAME_FROM'));

		$message = JRequest::getVar('rwCardsFormMessage', '', 'session', 'string');
		if ( $message== "" ) array_push($rwCardsError, JText::_('RWCARDS_FORM_ERROR_MESSAGE'));

		$emailFrom = JRequest::getVar('rwCardsFormEmailFrom', '', 'session', 'string');
		if ( !in_array(JText::_('RWCARDS_FORM_ERROR_EMAIL'), $rwCardsError) )
		{
			$emailFrom = (JMailHelper::isEmailAddress($emailFrom)) ? true : "";
			if ($emailFrom == "") array_push($rwCardsError, JText::_('RWCARDS_FORM_ERROR_EMAIL'));
		}
		
		for ($i = 0; $i < count($_SESSION['rwcardsSession']['rwCardsFormNameTo']); $i++)
		{
			$nameTo[$i] = $_SESSION['rwcardsSession']['rwCardsFormNameTo'][$i];
			if ( !in_array(JText::_('RWCARDS_FORM_ERROR_NAME_TO'), $rwCardsError) )
			{
				$nameTo[$i] = $_SESSION['rwcardsSession']['rwCardsFormNameTo'][$i];
				if ($nameTo[$i] == "") array_push($rwCardsError, JText::_('RWCARDS_FORM_ERROR_NAME_TO'));
			}
			$emailTo[$i] = $_SESSION['rwcardsSession']['rwCardsFormEmailTo'][$i];
			if ( !in_array(JText::_('RWCARDS_FORM_ERROR_EMAIL_TO'), $rwCardsError) )
			{
				$emailTo[$i] = (JMailHelper::isEmailAddress($emailTo[$i])) ? true : "";
				if ($emailTo[$i] == "") array_push($rwCardsError, JText::_('RWCARDS_FORM_ERROR_EMAIL_TO'));
			}
		}
		return $rwCardsError;
	}
	
	function getFieldsErrors()
	{
		jimport('joomla.mail.helper'); 

		$rwCardsFieldsError = array();
		
		$message = JRequest::getVar('rwCardsFormMessage', '', 'session', 'string');
		if ($message == "") array_push($rwCardsFieldsError, 'rwCardsFormMessage');
		$nameFrom = JRequest::getVar('rwCardsFormNameFrom', '', 'session', 'string');
		if ($nameFrom == "") array_push($rwCardsFieldsError, 'rwCardsFormNameFrom');				                                                                             
		$emailFrom = JRequest::getVar('rwCardsFormEmailFrom', '', 'session', 'string');
		$emailFrom = (JMailHelper::isEmailAddress($emailFrom)) ? true : "";
		if ($emailFrom == "") array_push($rwCardsFieldsError, 'rwCardsFormEmailFrom');

		for ($i = 0; $i < count($_SESSION['rwcardsSession']['rwCardsFormNameTo']); $i++)
		{
			$nameTo[$i] = $_SESSION['rwcardsSession']['rwCardsFormNameTo'][$i];
			if ($nameTo[$i] == "") array_push($rwCardsFieldsError, ('rwCardsFormNameTo[' . $i . ']'));
			$emailTo[$i] = $_SESSION['rwcardsSession']['rwCardsFormEmailTo'][$i];
			$emailTo[$i] = (JMailHelper::isEmailAddress($emailTo[$i])) ? true : "";
			if ($emailTo[$i] == "") array_push($rwCardsFieldsError, ('rwCardsFormEmailTo[' . $i . ']'));
		}

		return $rwCardsFieldsError;
	}
}
?>
