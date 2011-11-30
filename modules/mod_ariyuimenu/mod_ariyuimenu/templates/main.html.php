<?php
/*
 * ARI YUI menu Joomla! module
 *
 * @package		ARI YUI Menu Joomla! module.
 * @version		1.0.0
 * @author		ARI Soft
 * @copyright	Copyright (c) 2009 www.ari-soft.com. All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
 * 
 */

defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

AriKernel::import('Web.JSON.JSONHelper');

$moduleParams = $params['moduleParams'];
$moduleId = $params['moduleId'];
$menu = $params['menu']; 
$isRegistered = $params['isRegistered'];
$isSpecial = $params['isSpecial'];
$selectedId = $params['selectedId'];
$selectedIndex = $params['selectedIndex'];
$menuDirection = $moduleParams->get('direction', 'horizontal');
$width = $moduleParams->get('width', '');
$isVertical = ($menuDirection != 'horizontal');
$safeMode = (bool)$moduleParams->get('safeMode', true);
$remainActive = (bool)$moduleParams->get('remainActive', false);
?>
<!-- ARI YUI Menu Joomla! module. Copyright (c) 2009 - 2010 ARI Soft, www.ari-soft.com -->
<?php
if ($menu):

$defMenuConfig = array(
	'zIndex' => 0,
	'classname' => '',
	'hidedelay' => 0,
	'maxheight' => 0,
	'minscrollheight' => 90,
	'scrollincrement' => 1,
	'showdelay' => 250
	);

$config = array('lazyLoad' => true, 'autosubmenudisplay' => true, 'position' => 'static');
foreach ($defMenuConfig as $key => $defValue)
{
	$value = AriUtils2::parseValueBySample($moduleParams->get($key, $defValue), $defValue);
	if ($value != $defValue) $config[$key] = $value;
}

$submenuAlign = $moduleParams->get('submenualignment');
if (!empty($submenuAlign))
{
	$submenuAlign = explode(',', $submenuAlign);
	$submenuAlign = array_map('trim', $submenuAlign);
	
	$config['submenualignment'] = $submenuAlign;	
}
	
$menuId = 'ariyui' . $moduleId;

$siteUrl = AriJoomlaUtils::getSiteUrl() . '/modules/' . (AriJoomlaUtils::isJoomla15() ? 'mod_ariyuimenu/' : '') . 'mod_ariyuimenu/';
$jsUrl = $siteUrl . 'js/';

$lang	=& JFactory::getLanguage();
if ($safeMode)
	AriDocumentHelper::includeCssFile($siteUrl . 'css_loader.php?menuId=' . $menuId . ($lang->isRTL() ? '&dir=rtl' : ''));
else 
	AriDocumentHelper::includeCssFile($jsUrl . 'assets/menu/sam/menu.css');

AriDocumentHelper::includeJsFile($jsUrl . 'yui.combo.js');
AriDocumentHelper::includeCustomHeadTag(
	'<script type="text/javascript">try { document.execCommand("BackgroundImageCache", false, true); } catch(e) {};</script>');
AriDocumentHelper::includeCustomHeadTag(
	sprintf('<style>#%1$s A{ font-size: %2$s !important; font-weight: %3$s !important; text-transform: %4$s !important; }</style>',
		'ariyuimenu_' . $menuId,
		$moduleParams->get('fontSize', '11px'),
		$moduleParams->get('fontWeight', 'normal'),
		$moduleParams->get('textTransform', 'none')));
AriDocumentHelper::includeCustomHeadTag(
	sprintf('<script type="text/javascript">YAHOO.util.Event.onContentReady("ariyuimenu_' . $menuId . '", function () { var oMenu = new YAHOO.widget.%3$s("ariyuimenu_' . $menuId . '", %1$s); oMenu.render(); oMenu.show(); if (%2$d > -1) oMenu.getItem(%2$d).cfg.setProperty("selected", true); });</script>',
		AriJSONHelper::encode($config),
		$selectedIndex,
		$isVertical ? 'Menu' : 'MenuBar'));
?>
<div class="yui-skin-sam" id="<?php echo $menuId; ?>"<?php if ($width): ?> style="width: <?php echo $width; ?>;"<?php endif; ?>>	
	<?php
		AriTemplate::display(dirname(__FILE__) . '/menu.html.php',
			array('menuId' => 'ariyuimenu_' . $menuId,
				'menuPrefix' => 'ariyuimenu_' . $menuId . '_',
				'menu' => $menu,
				'level' => 0,
				'parentId' => 0,
				'isRegistered' => $isRegistered,
				'isSpecial' => $isSpecial,
				'selectedId' => $selectedId,
				'menuDirection' => $menuDirection,
				'remainActive' => $remainActive));
	?>
</div>
<?php
endif;
?>