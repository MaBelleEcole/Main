<?php
/**
 * RWCards Template for RWCards Entry page
 *
 * @author Ralf Weber  <ralf@weberr.de>
 * @cleanup code by A. Dalebout <siam@kiwuweb.de>
 * @version 3.1
 * @copyright Copyright (c) 2010, LoadBrain
 * @link http://www.weberr.de/
 * @link http://www.kiwuweb.de/
 * @license GNU/GPL
 */

	 // no direct access
	defined('_JEXEC') or die('Restricted access');
	global $mainframe;
	// Load the moo.fx scripts
	$document = &JFactory::getDocument();


	// start build additional Stylesheet by A. Dalebout
	$style = '';
	if ( count($this->rwcards[0]) > 0) {
	    for ($i=0; $i < count($this->rwcards); $i++) {
	        $style .="
#myGallery_rwcards_".$i."
{
	width: " . $this->params->get("thumb_box_width", '260px') . " !important;
	height: " . $this->params->get("thumb_box_height", '220px') . " !important;
	border: 1px solid #000;
}

#myGallery_rwcards_".$i." img.thumbnail
{
	display: none;
}
#rwcardsTable
{
	width:100%;
	border:0px;
	padding:1em;
	border:1px solid black;
	margin:2px;
}";
	    }
	  }
	// finish build additional Stylesheet by A. Dalebout

	$document->addStyleDeclaration($style); // send additional Stylesheet to joomlal by A.Dalebout
	JHTML::_('behavior.mootools');
	$document->addScript(JURI::base() . 'components/com_rwcards/js/rwcards.gallery.js');
	$document->addStyleSheet( JURI::base() . 'components/com_rwcards/css/rwcards.slideshow.css', 'text/css', null, array( 'id' => 'StyleSheet' ) );
?>

	<h1><?php echo $this->params->get('cats_page_heading','View Cards'); ?></h1>

<?php
	if ( count($this->rwcards[0]) > 0) {
	    for ($i=0; $i < count($this->rwcards); $i++) {
	?>
<script type="text/javascript">
	function startGallery_rwcards_<?php echo $i; ?>() {
		var myGallery = new gallery($('myGallery_rwcards_<?php echo $i; ?>'), {
			timed: true,
			showArrows: false,
			showCarousel: false
		});
	}
	window.addEvent('domready', startGallery_rwcards_<?php echo $i; ?>);
</script>

<table id="rwcardsTable<?php echo $i;?>" border="0">
<tr>
<td width="150px">
	<div id="rwcards_gallery">
		<div id="myGallery_rwcards_<?php echo $i; ?>">

<?php
			// loop through cards in section
			foreach ($this->rwcards[$i] as $key => $val) {
?>
		<div class="imageElement">
			<h3><?php echo $val->thumb_title; ?></h3>
			<p><?php echo $val->thumb_desc; ?></p>
			<a href="<?php echo JRoute::_('index.php?option=com_rwcards&amp;controller=rwcardslistonecategory&amp;Itemid=' . JRequest::getCmd( "Itemid" )
				. '&amp;category_id=' . $val->category_id
				. '&amp;reWritetoSender=' . @$this->reWritetoSender
				. '&amp;sessionId=' . @$this->sessionId);
			?>" title="<?php echo htmlentities( $val->category_kategorien_name, ENT_QUOTES, 'UTF-8' ); ?>" class="open"></a>
			<img src="<?php echo JURI::base(); ?>images/stories/cards/<?php echo strtolower(substr($val->picture, 0, -4) )
				. $this->suffix . strtolower( substr($val->picture, strrpos($val->picture, ".")) );
			?>" class="full" alt="<?php echo nl2br( htmlentities( $val->category_kategorien_name, ENT_QUOTES, 'UTF-8' )); ?>" />
		</div>

<?php
			}
?>
		</div>
	</div>
</td>

<td valign="top" style="width:200px; padding: 0px 5px;">
	<span style="font-weight: bold; text-decoration:underline;"><?php //  print_r( $this->rwcards[$i]); // sb ?>
		<a href="<?php echo JRoute::_('index.php?option=com_rwcards&amp;controller=rwcardslistonecategory&amp;Itemid=' . JRequest::getCmd( "Itemid" )
			. '&amp;category_id=' . $this->categoryData[$i]->id
			. '&amp;reWritetoSender=' . @$this->reWritetoSender
			. '&amp;sessionId=' . @$this->sessionId );
		?>" class="open"><?php echo htmlentities( $this->categoryData[$i]->category_kategorien_name, ENT_QUOTES, 'UTF-8' ); ?></a>
	</span>
	<br />
	<br />

	<?php echo htmlentities( $this->categoryData[$i]->category_description, ENT_QUOTES, 'UTF-8' ); ?>

	<br /><br /><br /><br />

	<a href="<?php echo JRoute::_('index.php?option=com_rwcards&amp;controller=rwcardslistonecategory&amp;Itemid=' . JRequest::getCmd( "Itemid" )
		. '&amp;category_id=' . $this->categoryData[$i]->id
		. '&amp;reWritetoSender=' . @$this->reWritetoSender
		. '&amp;sessionId=' . @$this->sessionId );
	?>" class="open"><?php echo JText::_('ECARD_SEE_ALL_CARDS'); ?></a>
</td>
</tr>
</table>
<?php
		}
	}
	else {
?>
<table id="rwcardsTable">
<tr>
	<td valign="top">
		<span style="font-weight: bold; color:red; font-size:14px;">
			<?php echo JText::_('RWCARDS_NO_CATEGORY_PUBLISHE_OR_CREATED'); ?><br />
			<?php echo JText::_('RWCARDS_NO_PICTURES_PUBLISHE_OR_CREATED');?>
		</span>
	</td>
</tr>
</table>

<?php
	}
?>
