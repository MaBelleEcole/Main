<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

function modChrome_slider($module, &$params, &$attribs)
{
	jimport('joomla.html.pane');
	// Initialize variables
	$sliders = & JPane::getInstance('sliders');
	$sliders->startPanel( JText::_( $module->title ), 'module' . $module->id );
	echo $module->content;
	$sliders->endPanel();
}

/*
 * Module chrome that allows for rounded corners by wrapping in nested div tags
 */
function modChrome_jom_rounded($module, &$params, &$attribs)
{ ?>
		<div class="module<?php echo $params->get('moduleclass_sfx'); ?>">
		<?php if ($module->showtitle != 0) { ?>
			<div id="h3-m"><div id="h3-r"><div id="h3-l"><h3><?php echo $module->title; ?></h3></div></div></div>
			<?php }else { ?>
			<div id="tr"><div id="tl"></div></div>
		<?php } ?>
		<div id="mid">
			<div class="inside">
				<?php echo $module->content; ?>
			</div>
		</div>
		<div id="br"><div id="bl"></div></div>
		</div>
		<div class="clear">&nbsp;</div>
	<?php
}
function modChrome_jom_xhtml($module, &$params, &$attribs)
{ ?>
		<div class="moduletable<?php echo $params->get('moduleclass_sfx'); ?>">
		<?php if ($module->showtitle != 0) { ?>
			<h3><?php echo $module->title; ?></h3>
		<?php } ?>
			<div class="inside">
				<?php echo $module->content; ?>
			</div>
		</div>	
		<div class="clear">&nbsp;</div>
	<?php
}
?>