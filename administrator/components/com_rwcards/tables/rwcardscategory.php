<?php
/**
* RWCards table class
* 
* @author Ralf Weber  <ralf@weberr.de>
* @version 3.0
* @copyright Copyright (c) 2007, LoadBrain
* @link http://www.weberr.de/
*
* @license GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');


class TableRWCardsCategory  extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;
	/** @var string*/
	var $category_kategorien_name = '';
	/** @var string */
	var $category_description = '';
	/** @var int */
	var $checked_out		= 0;
	/** @var date */
	var $checked_out_time	= 0;
	/** @var int */
	var $published			= 1;	
	/** @var int */
	var $ordering			= null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db) {
		parent::__construct('#__rwcards_category', 'id', $db);
	}
}
?>
