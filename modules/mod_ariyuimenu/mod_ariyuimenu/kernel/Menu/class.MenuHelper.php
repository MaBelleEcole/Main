<?php
/*
 * ARI Framework Lite
 *
 * @package		ARI Framework Lite
 * @version		1.0.0
 * @author		ARI Soft
 * @copyright	Copyright (c) 2009 www.ari-soft.com. All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
 * 
 */

defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

AriKernel::import('Joomla.JoomlaUtils');

class AriJoomlaMenuHelper
{
	function getHierarchialMenu($menuType, $isRegister = false)
	{
		$db =& AriJoomlaUtils::getDBO();

		$query = sprintf('SELECT M.*' .
			' FROM #__menu AS M' .
			' WHERE menutype = %s AND published = 1 AND access < %d' .
			' ORDER BY parent, ordering',
			$db->Quote($menuType),
			$isRegister ? 3 : 1);
		$db->setQuery($query);
		$menu = $db->loadObjectList('id');
		
		if ($db->getErrorNum())
		{
			return null;
		}
		
		if (!is_array($menu) || count($menu) < 1)
		{
			return null;
		}
		
		foreach ($menu as $id => $menuItem)
		{
			$parentId = $menuItem->parent; 
			if ($parentId && isset($menu[$parentId]))
			{
				$parent =& $menu[$parentId];
				if (!isset($parent->children))
				{
					$parent->children = array();
				}
				
				$parent->children[] =& $menu[$id];
			}
		}

		return $menu;
	}
	
	function findParentMenuId($menu, $childId)
	{
		if (!is_array($menu) || empty($childId) || !isset($menu[$childId])) return 0;

		$menuItem = $menu[$childId];
		while ($menuItem->parent)
		{
			if (!isset($menu[$menuItem->parent])) return 0;
			
			$menuItem = $menu[$menuItem->parent];
		}

		return $menuItem->id;
	}
	
	function getMenuItemIndex($menu, $menuId)
	{
		$index = -1;
		if (!is_array($menu) || empty($menuId) || !isset($menu[$menuId])) return $index;
		
		$parentId = $menu[$menuId]->parent;
		
		$i = 0;
		foreach ($menu as $id => $menuItem)
		{
			if ($id == $menuId)
			{
				$index = $i;
				break;
			}
			
			if ($menuItem->parent == $parentId) ++$i;
		}
	
		return $index;
	}

	function getItemId($query)
	{
		$matches = array();
		preg_match('/Itemid=([0-9]+)/', $query, $matches);

		return isset($matches[1]) ? $matches[1] : 0;
	}
}
?>