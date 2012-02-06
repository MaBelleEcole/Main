<?php
// @version $Id: default.php 11215 2008-10-26 02:25:51Z ian $
defined('_JEXEC') or die('Restricted access');
?>

<form action="index.php"  method="post" class="search<?php echo $params->get('moduleclass_sfx'); ?>">
	<?php
		    $output = '<input name="searchword" maxlength="20" class="inputbox'.$moduleclass_sfx.'" type="text" size="'.$width.'" value="'.$text.'"  onblur="if(this.value==\'\') this.value=\''.$text.'\';" onfocus="if(this.value==\''.$text.'\') this.value=\'\';" />';
			echo $output;
    ?>
	<input type="hidden" name="option" value="com_search" />
	<input type="hidden" name="task"   value="search" />
</form>
