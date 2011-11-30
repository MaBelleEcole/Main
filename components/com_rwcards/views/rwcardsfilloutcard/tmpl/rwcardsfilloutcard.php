<?php 
/**
* RWCards Template for FillOutCards
* 
* @author Ralf Weber  <ralf@weberr.de>
* @version 3.1
* @copyright Copyright (c) 2007, LoadBrain
* @link http://www.weberr.de/
*
* @license GNU/GPL
*/
// no direct access
defined('_JEXEC') or die('Restricted access');
global $mainframe;	
//session_destroy();
$document = &JFactory::getDocument();
JHTML::_('behavior.mootools');

if ( $this->params->get("rwCardsFormValidator",'mooToolsFV' )  == 'mooToolsFV' ) {
	$document->addScript(JURI::base() . 'components/com_rwcards/js/mootools-1.2.5.1-more.js');
	?>
<script type="text/javascript">//<![CDATA[
window.addEvent('domready', function()
{
   myFormValidator = new Form.Validator.Inline($('rwCardsForm'), {
      stopOnFailure: true,
      useTitles: true,
      errorPrefix: "",
      
           
      onFormValidate: function(passed, form, event) {
         if (passed) {
			<?php if (!$this->captchaError){?>
            form.submit();
			<?php } ?>
         }
      },

      onElementValidate: function(passed, element, validator, is_warn) {
       
          if (element.get('name') == 'captchaCode' && !passed) {
              alert('<?php echo RWCARDS_FORM_CAPTURE_ERROR_MESSAGE; ?>');
           }   
      }
   });

   Form.Validator.add('required', {
	    errorMsg: '<?php echo JText::_('RWCARDS_FORM_ERROR'); ?>',
	    test: function(element){
	        if (element.value.length == 0) return false;
	        else return true;
	    }
	});

   Form.Validator.add('validate-email', {
	    errorMsg: '<?php echo JText::_('RWCARDS_FORM_ERROR_EMAIL'); ?>',
	    test: function(element){
	    	return element.get('value').test(/\@/,'i');

	    }
	});	

   

});	
//]]></script>
<?php
}

$document->addStyleSheet( JURI::base() . 'components/com_rwcards/css/rwcards.filloutform.css', 'text/css', null, array( 'id' => 'StyleSheet2' ) );
require_once (JPATH_COMPONENT.DS.'captcha'.DS. 'class.captcha.php');

$sess = @$_SESSION['rwcardsSession'];

$session_code = ''; // initialise

if ( ! $this->checkingCaptcha and !$this->dataok ) {
	// and here we exit... no point in continuing.
?>

ERROR - card does not exist!

<?php	
} else {

	if( @$this->captchaError or @count($this->rwCardsError) > 0) {
		(!$this->isCaptcha) ? array_push($this->rwCardsError, JText::_('RWCARDS_FORM_CAPTURE_ERROR_MESSAGE')) : "";
		
		if ( $this->params->get("rwCardsFormValidator",'mooToolsFV' )  == 'mooToolsFV') {
		?>
	
<script type="text/javascript">//<![CDATA[
window.addEvent('domready', function()
{
	$('captchaCode').setProperty('value', '<?php echo  join('\r\n', $this->rwCardsError );  ?>').addClass('rwCardsErrorCaptchaError');
	$('captchaCode').addEvent('click', function(){
    $(this).setProperty('value', '').setStyle('background-color', '#FFFFFF');
});


	
});	
//]]>
</script>

<?php
		} // rwCardsFormValidator
		else if ( $this->params->get("rwCardsFormValidator",'mooToolsFV' ) == 'joomla' ){ // standard Joomla alert
			JError::raiseWarning('ERROR_CODE1', str_replace( "\'", "'", join('<br />', $this->rwCardsError) ) ); 
		}
		else if ( $this->params->get("rwCardsFormValidator",'mooToolsFV' ) == 'js' ){ // standard Joomla alert
			?>
<script type="text/javascript">//<![CDATA[
window.addEvent('domready', function()
{
	alert( '<?php echo  join('\r\n', $this->rwCardsError );  ?>')
	
});	
//]]>
</script>
			<?php
		}
		
	}
?>


<h1><?php echo JText::_('ECARD_PLEASE_FILL_OUT'); ?></h1>

<!-- begin change the form by A.Dalebout -->
<form name="rwCardsForm" id="rwCardsForm" method="post" action="<?php echo JURI::current(); ?>">
<input type="hidden" name="option" value="com_rwcards" />
<input type="hidden" name="controller" value="rwcardsfilloutcard" />
<input type="hidden" name="task" value="CheckCaptcha" />
<input type="hidden" name="Itemid" value="<?php echo JRequest::getCmd('Itemid'); ?>" />
<input type="hidden" name="id" value="<?php echo JRequest::getInt('id'); ?>" />
<input type="hidden" name="category_id" value="<?php echo JRequest::getInt('category_id', 0); ?>" />
<!-- finish change the form by A.Dalebout -->

<?php
// $sess holds the various messages, addresses etc from the session.
// $this->rwcards[0] is supposed to hold the card info from the database,
// and gets consulted when/if the session is blank.
?>
<div class="rwcardsImage">
	<p style="text-align:center;"><img src="<?php echo JURI::base(); ?>images/stories/cards/<?php echo(isset($sess['picture'])) ? @$sess['picture'] : $this->rwcards[0]->picture; ?>" hspace="2" vspace="2" alt="" /></p>
</div>
<div class="rwcardsfull">
	<div class="rwcardsLeftForm">
		
		<div id="rwNameFrom">
			<div><label for="rwCardsFormNameFrom"><?php echo JText::_('RWCARDS_NAME_FROM');?></label></div>
			<input id="rwCardsFormNameFrom" name="rwCardsFormNameFrom" value="<?php echo htmlentities((isset($sess['rwCardsFormNameFrom'])) ? @$sess['rwCardsFormNameFrom'] : @$this->rwcards[0]->nameTo, ENT_QUOTES, 'UTF-8' ); ?>" type="text" class="required rwcards_inputbox" />
		</div>
		
		<div id="rwEmailFrom">
			<div><label for="rwCardsFormEmailFrom"><?php echo JText::_('RWCARDS_EMAIL_FROM');?></label></div>
			<input id="rwCardsFormEmailFrom" name="rwCardsFormEmailFrom" value="<?php echo htmlentities((isset($sess['rwCardsFormEmailFrom'])) ? @$sess['rwCardsFormEmailFrom'] : @$this->rwcards[0]->emailTo, ENT_QUOTES, 'UTF-8' ); ?>" type="text" class="validate-email rwcards_inputbox" />
    	</div>

		<div id="rwMessage">
			<div><label for="rwCardsFormMessage"><?php echo JText::_('RWCARDS_MESSAGE');?></label></div>
			<textarea id="rwCardsFormMessage" name="rwCardsFormMessage" class="required rwcards_inputbox"><?php if (isset( $sess['rwCardsFormMessage'] )) echo htmlentities($sess['rwCardsFormMessage'], ENT_QUOTES, 'UTF-8' ); ?></textarea>
		</div>

	</div>
    <div class="rwcardsRightForm">
		<?php
		if ( isset($sess['rwCardsFormEmailTo']) )
		{
			for ($i = 0; $i < count(@$sess['rwCardsFormEmailTo']); $i++)
			{
			?>
				<div class="rwcardsReceiver">
					<div><label for="rwCardsFormNameTo[<?php echo $i; ?>]"><?php echo JText::_('RWCARDS_NAME_TO');?></label></div>
					<input id="rwCardsFormNameTo[<?php echo $i; ?>]" name="rwCardsFormNameTo[]" value="<?php echo htmlentities( @$sess['rwCardsFormNameTo'][$i], ENT_QUOTES, 'UTF-8' ); ?>" type="text" class="required rwcards_inputbox" />

					<div><label for="rwCardsFormEmailTo[<?php echo $i; ?>]"><?php echo JText::_('RWCARDS_EMAIL_TO');?></label></div>
					<input id="rwCardsFormEmailTo[<?php echo $i; ?>]" name="rwCardsFormEmailTo[]" value="<?php echo htmlentities( @$sess['rwCardsFormEmailTo'][$i], ENT_QUOTES, 'UTF-8' ); ?>" type="text" class="validate-email rwcards_inputbox" />
				</div>
		<?php
			}
		}
		else
		{
		?>
			<div class="rwcardsReceiver">
				<div><label for="rwCardsFormNameTo[<?php echo $i; ?>]"><?php echo JText::_('RWCARDS_NAME_TO');?></label></div>
				<input id="rwCardsFormNameTo[<?php echo $i; ?>]" name="rwCardsFormNameTo[]" value="<?php echo htmlentities( @$this->rwcards[0]->nameFrom, ENT_QUOTES, 'UTF-8' ); ?>" type="text" class="required rwcards_inputbox" />

				<div><label for="rwCardsFormEmailTo[<?php echo $i; ?>]"><?php echo JText::_('RWCARDS_EMAIL_TO');?></label></div>
				<input id="rwCardsFormEmailTo[<?php echo $i; ?>]" name="rwCardsFormEmailTo[]" value="<?php echo htmlentities( @$this->rwcards[0]->emailFrom, ENT_QUOTES, 'UTF-8' ); ?>" type="text" class="validate-email rwcards_inputbox" />
			</div>
		<?php
		}
		?>

		<div id="moreReceivers"><?php echo JText::_('RWCARDS_ADD_RECEIVER');?></div>
		<div id="lessReceivers" style="visibility:hidden;"><?php echo JText::_('RWCARDS_REMOVE_RECEIVER');?></div>
		
		<?php
		// we generate a session code whether or not there's a captcha, as the session
		// code gets re-used as confirmation for the recipients.
		if ( empty( $sess['sessionCode'] ) ) 
		{ 
			$session_code = md5( round( rand( 0, 40000 ) ) ); 
		} 
		else 
		{ 
			$session_code = @$sess['sessionCode']; 
		}	

		$captcha_success =  array_key_exists( 'captcha_success', $_SESSION ) && $_SESSION['captcha_success']; 
		
		if ( $this->params->get("captcha",1) && !$captcha_success )
		{
			$my_captcha = new captcha( $session_code, './components/com_rwcards/captcha/__temp__' );
			$pic_url = $my_captcha->get_pic( 4 );
			
		?>
		<div id="rwcardsCaptcha">
			<div id="captcha_caption">
				<?php echo JText::_('RWCARDS_FORM_CAPTURE'); ?>
			</div>
			<div id="rwcardsCaptchaImage">
				<img src="<?php echo JURI::base(); ?>/components/com_rwcards/captcha/captcha_image.php?img=<?php echo $pic_url;  ?>" alt="" />
			</div>
			<div id="rwcardsCaptchaInputLabel">
				<label for="captchaCode"><?php echo JText::_('RWCARDS_FORM_CAPTURE_CODE'); ?></label>
			</div>
			<div id="rwcardsCaptchaInput">
				<input id="captchaCode" name="captchaCode"  type="text" value="" class="rwcards_inputbox" />
			</div>
<!--
			<div id="rwcardsCaptchaHints">
				<?php echo JText::_('RWCARDS_FORM_CAPTCHA_HINT1') . "<br />" . JText::_('RWCARDS_FORM_CAPTCHA_HINT2');?>
			</div>
-->
		</div>
		<?php
		}
		?>
    </div>
	<div class="rwcardsClr"></div>
	<div class="rwcards_buttons_bottom">
		<input type="button" id="rwCardsBack" value="<?php echo JText::_('RWCARDS_BACK');?>" class="rwcards_button" /> 
		<input type="submit" name="submit" id="rwCardsPreviewCard" value="<?php echo JText::_('RWCARDS_PREVIEW_CARD'); ?>" class="rwcards_button" />
		<input type="submit" name="submit" id="rwCardsSendCard" value="<?php echo JText::_('RWCARDS_SEND_CARD');?>" class="rwcards_button" />
	</div>
</div>

	<input type="hidden" name="picture" value="<?php echo $this->rwcards[0]->picture; ?>" />
	<input type="hidden" name="sessionCode" id="sessionCode" value="<?php echo $session_code; ?>" />
	
</form>
<div class="rwcardsClr"></div>

<script type="text/javascript">//<![CDATA[
	if ($$('.rwcardsReceiver').length >= 2) {
		$('lessReceivers').setStyle('visibility', 'visible');
	}

	$('moreReceivers').addEvent('click', function() {
		$$('.rwcardsReceiver')[0].clone().injectAfter( $$('.rwcardsReceiver')[$$('.rwcardsReceiver').length - 1 ]);
		$('lessReceivers').setStyle('visibility', 'visible');
	});

	$('lessReceivers').addEvent('click', function() {
		if ($$('.rwcardsReceiver').length <= 2) {
			$('lessReceivers').setStyle('visibility', 'hidden');
		}
		// $('rwcardsReceiver').remove()
		$$('.rwcardsReceiver')[$$('.rwcardsReceiver').length - 1 ].remove()
	});
//]]></script>
<?php

} // end of if statement for whether or not there was an error.

?>
<script type="text/javascript">//<![CDATA[
	$('rwCardsBack').addEvent('click', function() {
		document.location.href='<?php echo str_replace( '&amp;', '&', JRoute::_( 'index.php?option=com_rwcards&controller=rwcardslistonecategory&Itemid=' . JRequest::getCmd('Itemid') . '&category_id=' . JRequest::getInt( 'category_id' ) . '&sessionId=' . $this->sessionId ) ); ?>';
	});
//]]></script>
