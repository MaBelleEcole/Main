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
 * $Id: controllerHelper.php 1344 2009-06-18 11:50:09Z akede $
 * @package joomfish
 * @subpackage controllerHelper
 *
*/


defined( 'JPATH_BASE' ) or die( 'Direct Access to this location is not allowed.' );


class  JoomfishControllerHelper  {

	/**
	 * Sets up ContentElement Cache - mainly used for data to determine primary key id for tablenames ( and for
	 * future use to allow tables to be dropped from translation even if contentelements are installed )
	 */
	function _setupContentElementCache()
	{
		$db =& JFactory::getDBO();
		// Make usre table exists otherwise create it.
		$db->setQuery( "CREATE TABLE IF NOT EXISTS `#__jf_tableinfo` ( `id` int(11) NOT NULL auto_increment, `joomlatablename` varchar(100) NOT NULL default '',  `tablepkID`  varchar(100) NOT NULL default '', PRIMARY KEY (`id`)) TYPE=MyISAM");
		$db->query();
		// clear out existing data
		$db->setQuery( "DELETE FROM `#__jf_tableinfo`");
		$db->query();
		$joomfishManager =& JoomFishManager::getInstance();
		$contentElements = $joomfishManager->getContentElements(true);
		$sql = "INSERT INTO `#__jf_tableinfo` (joomlatablename,tablepkID) VALUES ";
		$firstTime = true;
		foreach ($contentElements as $contentElement){
			$tablename = $contentElement->getTableName();
			$refId = $contentElement->getReferenceID();
			$sql .= $firstTime?"":",";
			$sql .= " ('".$tablename."', '".$refId."')";
			$firstTime = false;
		}

		$db->setQuery( $sql);
		$db->query();

	}


	/**
	 * Testing state of the system bot
	 *
	 */
	function _testSystemBotState()
	{
		$db =& JFactory::getDBO();
		$botState = false;

		$db->setQuery( "SELECT * FROM #__plugins WHERE element='jfdatabase'");
		$db->query();
		$plugin = $db->loadObject();
		if ($plugin != null && $plugin->published == "1") {
			$botState = $plugin->id;
		}
		return $botState;
	}

	function _checkDBCacheStructure (){

		JCacheStorageJfdb::setupDB();

		$db = & JFactory::getDBO();
		$sql = "SHOW COLUMNS FROM #__dbcache LIKE 'value'";
		$db->setQuery($sql);
		$data = $db->loadObject();
		if (isset($data) && strtolower($data->Type)!=="mediumblob"){
			$sql = "DROP TABLE #__dbcache";
			$db->setQuery($sql);
			$db->query();

			JCacheStorageJfdb::setupDB();
		}
	}

	function _checkDBStructure (){

		$db = & JFactory::getDBO();
		$sql = "show index from #__jf_content";// where key_name = 'jfContent'";
		$db->setQuery($sql);
		$data = $db->loadObjectList("Key_name");
		if (!isset($data['jfContent'])){
			$sql = "ALTER TABLE `#__jf_content` ADD INDEX `combo` ( `reference_id` , `reference_field` , `reference_table` )" ;
			$db->setQuery($sql);
			$db->query();
			
			$sql = "ALTER TABLE `#__jf_content` ADD INDEX `jfContent` ( `language_id` , `reference_id` , `reference_table` )" ;
			$db->setQuery($sql);
			$db->query();

			$sql = "ALTER TABLE `#__jf_content` ADD INDEX `jfContentLanguage` (`reference_id`, `reference_field`, `reference_table`, `language_id`)" ;
			$db->setQuery($sql);
			$db->query();
			
		}

		$sql = "ALTER TABLE `#__jf_content` CHANGE COLUMN `value` `value` mediumtext NOT NULL " ;
		$db->setQuery($sql);
		@$db->query();
		
		$sql = "ALTER TABLE `#__jf_content` CHANGE COLUMN `original_text` `original_text` mediumtext NOT NULL " ;
		$db->setQuery($sql);
		@$db->query();

		
	}

}
