<?php
/**
 * Joom!Fish - Multi Lingual extention and translation manager for Joomla!
 * Copyright (C) 2003 - 2009 Think Network GmbH, Munich
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
 * $Id: jfnewsfeeds.php 1344 2009-06-18 11:50:09Z akede $
 * @package joomfish
 * @subpackage jfnewsfeeds
 *
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$mainframe->registerEvent( 'onSearch', 'plgSearchJFNewsfeedslinks' );

JPlugin::loadLanguage( 'plg_search_jfnewsfeeds' );

/**
* Contacts Search method
*
* The sql must return the following fields that are used in a common display
* routine: href, title, section, created, text, browsernav
* @param string Target search string
* @param string mathcing option, exact|any|all
* @param string ordering option, newest|oldest|popular|alpha|category
 * @param mixed An array if the search it to be restricted to areas, null if search all
*/
function plgSearchJFNewsfeedslinks( $text, $phrase='', $ordering='', $areas=null )
{
	$db		=& JFactory::getDBO();
	$user	=& JFactory::getUser();

	$registry =& JFactory::getConfig();
	$lang = $registry->getValue("config.jflang");
	
	if (is_array( $areas )) {
		if (!array_intersect( $areas, array_keys( plgSearchNewsfeedAreas() ) )) {
			return array();
		}
	}

	// load plugin params info
 	$plugin =& JPluginHelper::getPlugin('search', 'jfnewsfeeds');
 	$pluginParams = new JParameter( $plugin->params );

	$limit 			= $pluginParams->def( 'search_limit', 50 );
	$activeLang 	= $pluginParams->def( 'active_language_only', 0);

	$text = trim( $text );
	if ($text == '') {
		return array();
	}

	$wheres = array();
	switch ($phrase) {
		case 'exact':
			$text		= $db->Quote( '%'.$db->getEscaped( $text, true ).'%', false );
			$where = "LOWER(jfc.value) LIKE ".$text;
			break;

		case 'all':
		case 'any':
		default:
			$words 	= explode( ' ', $text );
			$wheres = array();
			foreach ($words as $word) {
				$word		= $db->Quote( '%'.$db->getEscaped( $word, true ).'%', false );
				$wheres[] 	= "LOWER(jfc.value) LIKE ".$word;
			}
			$where = '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
			break;
	}

	switch ( $ordering ) {
		case 'alpha':
			$order = 'a.name ASC';
			break;

		case 'category':
			$order = 'b.title ASC, a.name ASC';
			break;

		case 'oldest':
		case 'popular':
		case 'newest':
		default:
			$order = 'a.name ASC';
	}

	$searchNewsfeeds = JText::_( 'Newsfeeds' );

	$query = 'SELECT a.id as contid, b.id as catid,  a.name AS title, "" AS created, a.link AS text,'
	. ' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(\':\', a.id, a.alias) ELSE a.id END as slug, '
	. ' CASE WHEN CHAR_LENGTH(b.alias) THEN CONCAT_WS(\':\', b.id, b.alias) ELSE b.id END as catslug, '
	. ' b.title as btitle, '
	//. ' CONCAT_WS( " / ", '. $db->Quote($searchNewsfeeds) .', b.title )AS section,'
	. ' "1" AS browsernav,'
	. ' jfl.code as jflang, jfl.name as jflname'
	. ' FROM #__newsfeeds AS a'
	. ' INNER JOIN #__categories AS b ON b.id = a.catid'
	. "\n LEFT JOIN #__jf_content as jfc ON reference_id = a.id"
	. "\n LEFT JOIN #__languages as jfl ON jfc.language_id = jfl.id"
	. ' WHERE ( '. $where .' )'
	. ' AND a.published = 1'
	. ' AND b.published = 1'
	. ' AND b.access <= '. (int) $user->get( 'aid' )
		. "\n AND jfc.reference_table = 'newsfeeds'"
		. ( $activeLang ? "\n AND jfl.code = '$lang'" : '')
	. ' GROUP BY a.id'
	. ' ORDER BY '. $order
	;
	$db->setQuery( $query, 0, $limit );
	$rows = $db->loadObjectList();

	foreach($rows as $key => $row) {
		$rows[$key]->section = $db->Quote($searchNewsfeeds)."/".$row->btitle. " - ". $row->jflname;
		$rows[$key]->href = 'index.php?option=com_newsfeeds&view=newsfeed&catid='.$row->catslug.'&id='.$row->slug;
	}

	return $rows;
}
