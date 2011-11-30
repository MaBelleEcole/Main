<?php
/**
* RWCards Template for ListOneCategory
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

$suffix = '@' . $this->params->get("thumbnail_suffix", 'rwcards' );

// Load the moo.fx scripts
$document = &JFactory::getDocument();
JHTML::_('behavior.mootools');
if ( $this->params->get('lightbox_type', 0) == 0 ) {
	$document->addScript(JURI::base() . 'components/com_rwcards/js/slimbox.js');
	$document->addStyleSheet( JURI::base() . 'components/com_rwcards/css/slimbox/slimbox.css', 'text/css', null, array( 'id' => 'StyleSheet' ) );
}

$style = "
#card-heading {
	font-weight:bold;
	margin-bottom: 1em;
}
#card-list {
	margin-bottom: 1.5em;
	text-align:center;
}

#category-box {
	font-weight:bold; float:right;
}
.img-thumb {
	border: 1px solid black; margin:5px;
}
.send-this-img {
	float:left; display:inline; width:165px; margin:0 5px 5px 5px; padding-bottom:10px; text-align: center
}
#limit {
	text-align:center;
}
#limit ul.pagination li {
	float: none !important;
	display: inline;
}
";

$document->addStyleDeclaration($style);

?>

<h1><?php echo $this->params->get('cards_page_heading','View Cards'); ?></h1>

<div id="category-box">
	<?php echo JText::_('ECARD_CHOSEN_CATEGORY') . ": " . $this->categories; ?>
</div>

<?php
if ( count($this->rwcards['rows']) > 0)
{
?>
<div id="card-heading">
	<?php echo JText::_('ECARD_CLICK_ON_CARD_TO_PREVIEW'); ?>
</div>

<div id="card-list">
<table border="0">
<tr>
<?php
$k="";
for ($i=0, $n=count( $this->rwcards['rows'] ); $i < $n; $i++)
{
?>
<td>
	<a href="<?php echo JURI::base(); ?>images/stories/cards/<?php echo $this->rwcards['rows'][$i]->picture;?>"
		rel="<?php echo $this->params->get('lightbox_type', 0) ? $this->params->get('lightbox_rel') : "lightbox[atomium]"; ?>"
		title="<?php echo strip_tags($this->rwcards['rows'][$i]->description); ?>"><img
		src="<?php echo JURI::base(); ?>images/stories/cards/<?php
			echo strtolower(substr($this->rwcards['rows'][$i]->picture, 0, -4) )
			. $suffix
			. strtolower( substr( $this->rwcards['rows'][$i]->picture, strrpos($this->rwcards['rows'][$i]->picture, ".")) );
			 ?>"
		alt="<?php echo strip_tags($this->rwcards['rows'][$i]->description); ?>"
		class="img-thumb" /></a>
	<br />
	<span class="send-this-img">
		<a href="<?php echo JRoute::_('index.php?option=com_rwcards&amp;controller=rwcardsfilloutcard&amp;Itemid=' . JRequest::getCmd('Itemid')
			. '&amp;id=' . intval($this->rwcards['rows'][$i]->id)
			. '&amp;category_id=' . JRequest::getInt('category_id')
			. '&amp;reWritetoSender=' . $this->reWritetoSender
			. '&amp;sessionId=' . $this->sessionId
		); ?>"><?php echo JText::_('ECARD_SEND_THIS_IMAGE'); ?></a>
	</span>
</td>
<?php
	$k++;
	if($k % $this->rwcards['cardsPerLine'] == 0) {
		echo "</tr><tr>";
	}
}
?>
</tr>
</table>
</div>

<div id="limit"><?php echo $this->rwcards['_pageNav']->getPagesLinks(); ?></div>

<div class="rwcardsClr"></div>
<?php
}
else
{
	JError::raiseWarning('ERROR_CODE', JText::_('RWCARDS_NO_CATEGORY_PUBLISHE_OR_CREATED') . " " . JText::_('RWCARDS_NO_PICTURES_PUBLISHE_OR_CREATED')); 
}
?>

<script type="text/javascript">//<![CDATA[

$('category_id').addEvent('change', function()
{
	var chosenCategory = $('category_id').getValue();
	var all_cats = {
	<?php $done = false;
	foreach ( $this->categoryIds as $key=>$categoryId ) {
		if ( $done ) echo ",\n";
		$done = true;
		echo "'$categoryId': '" . str_replace('&amp;', '&',
			JRoute::_( 'index.php?option=com_rwcards&controller=rwcardslistonecategory&Itemid=' . JRequest::getCmd("Itemid")
			. '&category_id=' . $categoryId . '&reWritetoSender=' . @$this->reWritetoSender . '&sessionId=' . @$this->sessionId ) ) . "'";
	}
	echo '};';
	?>
	document.location.href = all_cats[ chosenCategory ];
});
//]]></script>