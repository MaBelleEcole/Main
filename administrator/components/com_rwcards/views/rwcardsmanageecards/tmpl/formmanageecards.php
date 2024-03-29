<?php 
/**
* RWCards Template for RWCards
* 
* @author Ralf Weber  <ralf@weberr.de>
* @version 3.0
* @copyright Copyright (c) 2007, LoadBrain
* @link http://www.weberr.de/
*
* @license GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');


	$user =& JFactory::getUser();
	JHTML::_('behavior.tooltip');
	$ordering = ($this->rwcards['lists']['order'] == 'ordering');
	
	$params =& JComponentHelper::getParams( 'com_rwcards' );
	$suffix = '@' . $params->get("thumbnail_suffix", 'rwcards' );
	
?>
<style type="text/css">
table.admintable td.key
{
	background-color: #f6f6f6;
	text-align: left;
	width:200px;
	color: #666;
	font-weight: bold;
	border-bottom: 1px solid #e9e9e9;
	border-right: 1px solid #e9e9e9;
}
</style>

<script language="JavaScript">
function changeCategory()
{
	document.adminForm.submit();
}
</script>

<form action="index.php?option=com_rwcards&controller=manageecards" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<table>
		<tr>
			<td align="left" width="100%">
				<?php echo JText::_( 'Filter' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo htmlentities( $this->rwcards['lists']['search'], ENT_QUOTES, 'UTF-8' );?>" class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
				<button onclick="getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
			<td nowrap="nowrap">
				<?php
				echo $this->rwcards['lists']['categories'];
				?>
			</td>
			<td nowrap="nowrap">
				<?php
				echo $this->rwcards['lists']['state'];
				?>
			</td>
		</tr>
	</table>
	<table class="adminlist">
		<thead>
			<tr>
				<th width="20"  align="center">
					<?php echo JText::_( 'Num' ); ?>
				</th>
				<th width="20"  align="center">
					<input type="checkbox" name="toggle" value=""  onclick="checkAll(<?php echo count( $this->rwcards['rows'] ); ?>);" />
				</th>
				<th nowrap="nowrap" class="title">
					<?php echo JHTML::_('grid.sort',  'ECARD_AUTHOR', 'autor', @$lists['order_Dir'], @$lists['order'] ); ?>
				</th>
				<th nowrap="nowrap" class="title">
					<?php echo JHTML::_('grid.sort',  'ECARD_EMAIL', 'email', @$lists['order_Dir'], @$lists['order'] ); ?>
				</th>
				<th nowrap="nowrap" class="title">
					<?php echo JHTML::_('grid.sort',  'ECARD_DESCRIPTION', 'description', @$lists['order_Dir'], @$lists['order'] ); ?>
				</th>
				<th nowrap="nowrap" class="title">
					<?php echo JHTML::_('grid.sort',  'ECARD_CATEGORY', 'category_id', @$lists['order_Dir'], @$lists['order'] ); ?>
				</th>
				<th nowrap="nowrap" class="title">
					<?php echo JHTML::_('grid.sort',  'ECARD_PICTURE', 'picture', @$lists['order_Dir'], @$lists['order'] ); ?>
				</th>
				<th width="5%" class="title" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort',   'Published', 'published', @$lists['order_Dir'], @$lists['order'] ); ?>
				</th>
				<th nowrap="nowrap" width="8%">
					<?php echo JHTML::_('grid.sort',   'Order by', 'ordering', @$lists['order_Dir'], @$lists['order'] ); ?>
					<?php echo JHTML::_('grid.order',  $this->rwcards['rows'] ); ?>
				</th>	
				<th width="1%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort',   'ID', 'id', @$lists['order_Dir'], @$lists['order'] ); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="10">
					<?php echo $this->rwcards['_pageNav']->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->rwcards['rows'] ); $i < $n; $i++)
	{
		$row = &$this->rwcards['rows'];
		$link =  'index.php?option=com_rwcards&amp;controller=manageecards&amp;section=manageecards&amp;task=edit&amp;cid[]='. $row[$i]->id ;
		
		$published 		= JHTML::_('grid.published', $row[$i], $i );
		$checked 		= JHTML::_('grid.checkedout',   $row[$i], $i );
	
	?>
				<tr class="<?php echo "row$k"; ?>">
				<td align="center">
					<?php echo $this->rwcards['_pageNav']->getRowOffset($i); ?>
				</td>
				<td align="center">
					<?php echo $checked; ?>
				</td>
				<td>
					<?php
					if ( $row[$i]->checked_out && ( $row[$i]->checked_out != $user->get ('id') ) ) {
						echo $row[$i]->autor;
					} else {
						?>
						<a href="<?php echo $link; ?>" title="<?php echo JText::_( 'EDIT ENTRY' ); ?>">
							<?php echo htmlentities( $row[$i]->autor, ENT_QUOTES, 'UTF-8' ); ?></a>
						<?php
					}
					?>
				</td>
				<td>
					<?php echo htmlentities( $row[$i]->email, ENT_QUOTES, 'UTF-8' ); ?>
				</td>
				<td>
					<?php echo nl2br( htmlentities( $row[$i]->description, ENT_QUOTES, 'UTF-8' )); ?>
				</td>
				<td>
					<?php echo htmlentities( $row[$i]->category_kategorien_name, ENT_QUOTES, 'UTF-8' ); ?>
				</td>
				<td><img src="../images/stories/cards/<?php echo strtolower( substr($row[$i]->picture, 0, -4) ) . $suffix . strtolower( substr($row[$i]->picture, strrpos($row[$i]->picture, ".")) ); ?>"
					border="0"
					title="<?php echo nl2br(htmlentities( $row[$i]->description, ENT_QUOTES, 'UTF-8' )); ?>" /></td>
				<td align="center">
					<?php echo $published;?>
				</td>
				<td class="order">
					<span><?php echo $this->rwcards['_pageNav']->orderUpIcon( $i, ( $row[$i]->category_id == @$row[$i-1]->category_id ), 'orderup', 'Move Up', $ordering ); ?></span>
					<span><?php echo $this->rwcards['_pageNav']->orderDownIcon( $i, $n, ( $row[$i]->category_id == @$row[$i+1]->category_id ), 'orderdown', 'Move Down', $ordering ); ?></span>
					<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
					<input type="text" name="order[]" size="5" value="<?php echo $row[$i]->ordering;?>" <?php echo $disabled ?> class="text_area" style="text-align: center" />
				</td>
				<td align="center">
					<?php echo $row[$i]->id; ?>
				</td>
			</tr>
	<?php
	$k = 1 - $k;
	}
	?>
		</tbody>
	</table>
</div>

<input type="hidden" name="option" value="<?php echo $option; ?>" />
<input type="hidden" name="id" value="" />
<input type="hidden" name="task" value="view" />
<input type="hidden" name="controller" value="manageecards" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->rwcards['lists']['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->rwcards['filter_order_Dir']; ?>" />
</form>
