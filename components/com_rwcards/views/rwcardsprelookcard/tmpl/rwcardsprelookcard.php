<?php
/**
* RWCards Template for PreviewCards
* 
* @author Ralf Weber  <ralf@weberr.de>
* @version 3.0
* @copyright Copyright (c) 2007, LoadBrain
* @link http://www.weberr.de/
*
* @license GNU/GPL
*/
 // no direct access
defined('_JEXEC') or die('Restricted access');

// Load the moo.fx scripts
$document = &JFactory::getDocument();
JHTML::_('behavior.mootools');
$document->addStyleSheet( JURI::base() . 'components/com_rwcards/css/rwcards.previewandsend.css', 'text/css', null, array( 'id' => 'StyleSheet' ) );
$sess = @$_SESSION['rwcardsSession'];
$separate_front_back = $this->params->get('separate_front_back', true );
$set_height = $this->params->get('set_height', true );
$set_width = $this->params->get('set_width', true );

?>   
<h1><?php echo JText::_('RWCARDS_PREVIEW_CARD'); ?></h1>

<?php if ( $separate_front_back ) { ?> 

<p style="text-align:center;">
	<span id="showFrontCard" class="rwcardsLink">
		<?php echo JText::_('RWCARDS_SHOW_FRONT_CARD'); ?></span>&nbsp;&nbsp;<span id="showBackCard" class="rwcardsLink"><?php echo JText::_('RWCARDS_SHOW_BACK_CARD'); ?>
	</span>
</p>

<?php } ?>

<div id="rwcardsViewWrapper" <?php if ( $set_height ) { ?>style="height:<?php echo $this->rwcards['pageheight']; ?>px;<?php } ?> ; text-align:center;">
	<div id="frontCard" <?php echo $separate_front_back ? 'style="display:none;"' : '' ?>>
		<img src='<?php echo JURI::base(); ?>images/stories/cards/<?php echo @$sess['picture']; ?>'
			alt='<?php echo @$sess['picture']; ?>'
			hspace='2'
			vspace='2'
			border='0'
			class="theCard">
	</div>
	<div id="backCard" style="<?php echo $separate_front_back ? 'display:none;' : ''; ?>
		<?php if ( $set_width ) { ?>width:<?php echo $this->rwcards['pagewidth'];?>px;<?php } ?>
		<?php if ( $set_height ) { ?>height:<?php echo $this->rwcards['pageheight']; ?>px;<?php } ?>">
		<div class="rwcardsfull">
			<div class="rwcardsLeftForm">
				<div class="rw_date"><?php echo date("d.m.Y"); ?></div>
				<p class="rw_from">
					<span class="rw_label"><?php echo  JText::_('RWCARDS_FROM'); ?></span>
					<br />
					<span class="rw_from_name"><?php echo htmlentities( @$sess['rwCardsFormNameFrom'], ENT_QUOTES, 'UTF-8' ); ?></span>
					<br />
					<span class="rw_from_email"><?php echo htmlentities( @$sess['rwCardsFormEmailFrom'], ENT_QUOTES, 'UTF-8' ); ?></span>
				</p>
				<p class="rw_message">
					<span class="rw_label"><?php echo JText::_('RWCARDS_MESSAGE');?></span>
					<br />
					<span class="rw_text"><?php echo nl2br( htmlentities( @$sess['rwCardsFormMessage'], ENT_QUOTES, 'UTF-8' ) ); ?></span>
				</p>
			</div>
			<div class="rwcardsRightForm">
				<p class="rw_postmark"><img src='<?php echo JURI::base(); ?>/components/com_rwcards/images/postmark.gif' /><img src='<?php echo JURI::base(); ?>/components/com_rwcards/images/stamp.jpg' /></p>
				<p class="rw_to">
					<span class="rw_label"><?php echo JText::_('RWCARDS_TO');?></span>
				</p>

				<div id="rwcardsReceiver">
				<?php
				if ( count(@$sess['rwCardsFormEmailTo']) > 0 )
				{
					for ($i = 0; $i < count(@$sess['rwCardsFormEmailTo']); $i++)
					{
					?>
					<p class="rw_receiver">
						<span class="rw_to_name"><?php echo htmlentities( @$sess['rwCardsFormNameTo'][$i], ENT_QUOTES, 'UTF-8' ); ?></span>
						<br />
						<span class="rw_to_email"><?php echo htmlentities( @$sess['rwCardsFormEmailTo'][$i], ENT_QUOTES, 'UTF-8' ); ?></span>
						<br />
					</p>
				<?php
					}
				}
				?>
				</div>
			</div>
			<div class="rwcardsClr"></div>
		</div>
	</div>
</div>

<div class="rwcards_buttons_bottom" id="rwcards_buttons_bottom">
<form method="post" onSubmit="javascript:return false;">
	<input type="submit" name="submit" id="rwcardsGoBack" value="<?php echo JText::_('RWCARDS_GO_BACK'); ?>" class="rwcards_button" />
	<input type="submit" name="submit" id="rwcardsSend" value="<?php echo JText::_('RWCARDS_SEND_CARD');?>" class="rwcards_button" />		
	<input type="hidden" name="sessionCode" id="sessionCode" value="<?php echo @$sess['sessionCode']; ?>" />
</form>    
</div>

<script type="text/javascript">//<![CDATA[
window.addEvent('domready', function()
{
<?php
	if ( $separate_front_back ) {
?>
	$('frontCard').setStyle('display', 'inline');
	
	// Click on ShowFrontCard
	$('showFrontCard').addEvent('click', function()
	{
		$('backCard').setStyle('display', 'none');
		$('frontCard').setStyle('display', 'inline');
	});
	
	// Click on ShowBackCard
	$('showBackCard').addEvent('click', function()
	{
		$('frontCard').setStyle('display', 'none');
		$('backCard').setStyle('display', 'inline');
	});
<?php } ?>
	
	$('rwcardsGoBack').addEvent('click', function() {
		document.location.href='<?php echo str_replace('&amp;', '&', JRoute::_( 'index.php?option=com_rwcards&controller=rwcardsfilloutcard&Itemid=' . JRequest::getCmd('Itemid') . '&id=' . $this->id ) ); ?>'
	});
	
	$('rwcardsSend').addEvent('click', function() {
		document.location.href='<?php echo str_replace('&amp;', '&', JRoute::_( 'index.php?option=com_rwcards&controller=rwcardsprelookcard&task=sendrwcard&Itemid=' . JRequest::getCmd('Itemid') . '&id=' . $this->id ) ); ?>'
	});
});	
//]]></script>

<div class="rwcardsClr"></div>
