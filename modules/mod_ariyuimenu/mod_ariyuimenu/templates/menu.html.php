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

$menuId = $params['menuId'];
$menuPrefix = $params['menuPrefix'];
$menu = $params['menu'];
$level = $params['level'];
$parentId = $params['parentId'];
$isRegistered = $params['isRegistered'];
$isSpecial = $params['isSpecial'];
$selectedId = AriUtils2::getParam($params, 'selectedId', 0);
$menuDirection = AriUtils2::getParam($params, 'menuDirection', 'horizontal');
$isVertical = ($menuDirection != 'horizontal');
$flatMenu = array_values($menu);

$cssClass = ($level || $isVertical) ? 'yuimenu' : 'yuimenubar yuimenubarnav';
$hrefCssClass = ($level || $isVertical) ? 'yuimenuitemlabel' : 'yuimenubaritemlabel';
$menuItemClass = ($level || $isVertical) ? 'yuimenuitem' : 'yuimenubaritem';
$menuItemDisabledClass = ($level || $isVertical) ? 'yuimenuitem-disabled' : 'yuimenubaritem-disabled';
$hrefDisabledCssClass = ($level || $isVertical) ? 'yuimenuitemlabel-disabled' : 'yuimenubaritemlabel-disabled';
$selectedCssClass = ($level || $isVertical) ? 'yuimenuitem-selected' : 'yuimenubaritem-selected';
$hrefSelectedCssClass = ($level || $isVertical) ? 'yuimenuitemlabel-selected' : 'yuimenubaritemlabel-selected';

if ($params['remainActive'])
{
	$selectedCssClass .= ($level || $isVertical) ? ' yuimenuitem-active' : ' yuimenubaritem-active';
	$hrefSelectedCssClass .= ($level || $isVertical) ? ' yuimenuitemlabel-active' : ' yuimenubaritemlabel-active'; 
}
?>

<?php
if ($menu):
?>
	<div id="<?php echo $menuId; ?>" class="<?php echo $cssClass; ?>">
		<div class="bd">
			<ul<?php if (!$level): ?> class="first-of-type"<?php endif; ?>>
				<?php
					$i = 0;
					$j = -1;
					foreach ($menu as $menuItem):
						++$j;
						$hasChilds = isset($menuItem->children) && count($menuItem->children) > 0;
						if ($menuItem->parent != $parentId) continue;

						if ($menuItem->type == 'separator' && !$hasChilds):
							$nextMenuItem = isset($flatMenu[$j + 1]) ? $flatMenu[$j + 1] : null;
							if (($nextMenuItem && $nextMenuItem->parent == $parentId) && ($level || $isVertical)):
				?>
			</ul>
			<ul>
				<?php
							endif;
							continue;
						endif;

						$menuParams = new JParameter($menuItem->params);
						$link = $menuItem->link;
						$menuType = $menuItem->type;
						if ($menuItem->type == 'menulink')
						{
							$aliasId = AriJoomlaMenuHelper::getItemId($link);
							if ($aliasId > 0 && isset($menu[$aliasId]))
							{
								$aliasMenuItem =& $menu[$aliasId];
								$menuType = $aliasMenuItem->type;
								$link = $aliasMenuItem->link;
							}
						}
						
						if ($link && $menuType == 'component')
						{ 
							$link .= (strpos($link, '?') !== false) ? '&' : '?';
							$link .= 'Itemid=' . $menuItem->id;
						}
						
						if (strcasecmp(substr($link, 0, 4), 'http'))
						{
							$secure = $menuParams->def('secure', 0);
							$link = JRoute::_($link, true, $secure);
						} 

						$isSelected = ($menuItem->id == $selectedId);
						$isDisabled = ((!$isRegistered && $menuItem->access > 0) || 
							(!$isSpecial && $menuItem->access == 2));
						$id = $menuItem->id;
						$subMenu = $hasChilds ? $menuItem->children : null;
						$aAttr = array(
							'class' => $hrefCssClass . ($isDisabled ? ' ' . $hrefDisabledCssClass : '') . ($isSelected ? ' ' . $hrefSelectedCssClass : ''));
						if (!$isDisabled && $link)
						{ 
							$link = AriJoomlaUtils::getLink($link, false, false);
							switch ($menuItem->browserNav)
							{
								case 1:
									$aAttr['target'] = '_blank';
									break;
									
								case 2:
									$aAttr['onclick'] = "window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,');return false;";
									break;
							}
						}
						else 
						{ 
							$link = 'javascript:void(0);';
						}
							
						$aAttr['href'] = $link;
				?>
					<li class="<?php echo $menuItemClass . ($isDisabled ? ' ' . $menuItemDisabledClass : '') . ($isSelected ? ' ' . $selectedCssClass : '') . (!$level && !$i ? ' first-of-type' : ''); ?>"><a<?php echo AriHtmlHelper::getAttrStr($aAttr); ?>><?php echo $menuItem->name; ?></a>
					<?php
						if (!$isDisabled && $subMenu):
							AriTemplate::display(__FILE__,
								array('menuId' => $menuPrefix . $id,
									'menuPrefix' => $menuPrefix,
									'menu' => $subMenu,
									'level' => $level + 1,
									'parentId' => $id,
									'isRegistered' => $isRegistered,
									'isSpecial' => $isSpecial,
									'remainActive' => $params['remainActive']));
						endif;
					?>
					</li>
				<?php
						++$i;
					endforeach;
				?>
			</ul>
		</div>
	</div>
<?php
endif; 
?>