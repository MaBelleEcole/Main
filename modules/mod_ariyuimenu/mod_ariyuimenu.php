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

defined('_JEXEC') or die('Restricted access');

require_once dirname(__FILE__) . '/mod_ariyuimenu/kernel/class.AriKernel.php';

global $Itemid;

AriKernel::import('Utils.Utils2');
AriKernel::import('Joomla.JoomlaUtils');
AriKernel::import('Menu.MenuHelper');
AriKernel::import('Document.DocumentHelper');
AriKernel::import('Template.Template');
AriKernel::import('Web.HtmlHelper');
 
$isRegistered = AriJoomlaUtils::isRegistered();
$user =& JFactory::getUser();
$isSpecial = $isRegistered && ($user->get('usertype') != 'Registered');
$menuType = $params->get('menutype', 'mainmenu');
$showHiddenItems = AriUtils2::parseValueBySample($params->get('showHiddenItems', false), false);
$highlightCurrentItem = AriUtils2::parseValueBySample($params->get('highlightCurrentItem', true), true);

$menu = AriJoomlaMenuHelper::getHierarchialMenu($menuType, ($isRegistered || $showHiddenItems));
$selectedId = $highlightCurrentItem
	? AriJoomlaMenuHelper::findParentMenuId($menu, $Itemid)
	: 0;
$selectedIndex = $selectedId > 0
	? AriJoomlaMenuHelper::getMenuItemIndex($menu, $selectedId)
	: -1;
$cssStyles = $params->get('style');

$document =& JFactory::getDocument();
if ($cssStyles)
	$document->addStyleDeclaration(str_replace(array('{$id}'), array('ariyui' . $module->id), $cssStyles));

AriTemplate::display(dirname(__FILE__) . '/mod_ariyuimenu/templates/main.html.php',
	array('menu' => $menu, 
		'moduleParams' => $params, 
		'isRegistered' => $isRegistered,
		'isSpecial' => $isSpecial, 
		'selectedId' => $selectedId,
		'selectedIndex' => $selectedIndex,
		'moduleId' => $module->id));
?>