<?php
/**
 * Joom!Fish - Multi Lingual extention and translation manager for Joomla!
 * Copyright (C) 2003-2009 Think Network GmbH, Munich
 *
 * All rights reserved.  The Joom!Fish project is a set of extentions for
 * the content management system Joomla!. It enables Joomla!
 * to manage multi lingual sites especially in all dynamic information
 * which are stored in the database.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307,USA.
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * -----------------------------------------------------------------------------
 * $Id: mod_jflanguageselection.php 1396 2009-08-20 09:25:19Z geraint $
 * @package joomfish
 * @subpackage mod_jflanguageselection
 *
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

$db =& JFactory::getDBO();
if (!is_a($db,"JFDatabase")){
	echo JText::_("Joomfish System Plugin not enabled");
	return;
}
$db->_profile("langmod",true);

// Include the helper functions only once
JLoader::import('helper', dirname( __FILE__ ), 'jfmodule');
JLoader::register('JoomFishVersion', JOOMFISH_ADMINPATH .DS. 'version.php' );
$type 		= trim( $params->get( 'type', 'rawimages' ));
$layout = JModuleHelper::getLayoutPath('mod_jflanguageselection',$type);

$inc_jf_css	= intval( $params->get( 'inc_jf_css', 1 ));
$type 		= trim( $params->get( 'type', 'dropdown' ));
$show_active= intval( $params->get( 'show_active', 1 ) );
$spacer		= trim( $params->get( 'spacer', '&nbsp;' ) );

jimport('joomla.filesystem.file');

$jfManager = JoomFishManager::getInstance();
$langActive = $jfManager->getActiveLanguages(true);

// setup Joomfish plugins
$dispatcher	   =& JDispatcher::getInstance();
JPluginHelper::importPlugin('joomfish');
$dispatcher->trigger('onAfterModuleActiveLanguages', array (&$langActive));

$outString = '';
if( !isset( $langActive ) || count($langActive)==0) {
	// No active languages => nothing to show :-(
	return;
}

// check for unauthorised access to inactive language
$curLanguage = JFactory::getLanguage();
if (!array_key_exists($curLanguage->getTag(),$langActive)){
	reset($langActive);
	//$currentlang = current($langActive);
	//global $mainframe;
	//$mainframe->redirect(JRoute::_("index.php?lang=".$currentlang->iso));
	$registry =& JFactory::getConfig();
	$deflang = $registry->getValue("config.defaultlang");
	global $mainframe;
	$mainframe->redirect(JRoute::_("index.php?lang=".$deflang));
	JError::raiseError('0', JText::_('NOT AUTHORISED').' '.$curLanguage->getTag());
	exit();
}

$db->_profile("langmod");
$db->_profile("langlayout",true);
require($layout);
$db->_profile("langlayout");
$version = new JoomFishVersion();
?>
<!--Joom!fish <?php echo $version->getVersion();?>-->
<!-- <?php echo $version->getCopyright();?> Think Network, released under the GPL. -->
<!-- More information: at http://www.joomfish.net -->
