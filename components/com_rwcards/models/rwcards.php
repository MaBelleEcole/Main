<?php
/**
 * RWCards Model for Entry RWCards
 *
 * @author Ralf Weber  <ralf@weberr.de>
 * @version 3.0
 * @copyright Copyright (c) 2009, LoadBrain
 * @link http://www.weberr.de/
 *
 * @license GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die ();

jimport('joomla.application.component.model');

/**
 * RWCards Model
 *
 */
class rwcardsModelrwcards extends JModel
{
	/**
	 * RWCards data array
	 *
	 * @var array
	 */
	var $_data;
	var $_categoryData;

	/**
	 * Gets the data
	 * @return array The data to be displayed to the user
	 */
	function getData()
	{
		$db = & JFactory::getDBO();

		// First all categories;
		$query = "select * from #__rwcards_category where #__rwcards_category.published = '1' order by ordering";
		$this->_categoryData = $this->_getList($query);

		// Get all Cards for each category to build a slideshow with them
		$i = 0;
		foreach ($this->_categoryData as $val)
		{
			$query = "SELECT #__rwcards.*, #__rwcards_category.id, #__rwcards_category.category_kategorien_name, #__rwcards_category.category_description "
				. " FROM #__rwcards left join #__rwcards_category on #__rwcards_category.id = '".$val->id."' "
				. " WHERE (#__rwcards.category_id = '".$val->id."' and #__rwcards.published  = '1') order by #__rwcards.ordering";
			$db->setQuery($query);
			$this->_data[$i++] = $this->_getList($query);
		}

		// now generate info for placing over the thumbnails, based on the parameters.
		$params =& JComponentHelper::getParams( 'com_rwcards' );

		$a_prefix	= $params->get( 'titleauthor_prefix' );
		$d_prefix	= $params->get( 'description_prefix' );
		$type		= $params->get( 'thumbnail_data', 'a-d' );
		
		foreach ($this->_data as $key1=>$value1 ) {
			foreach ($value1 as $key2=>$value2 ) {
			
				switch( $type ) {
					case 'a-d':
						$this->_data[$key1][$key2]->thumb_title = $a_prefix . $value2->autor;
						$this->_data[$key1][$key2]->thumb_desc = $d_prefix . nl2br( $value2->description );
						break;
					case 'd-a':
						$this->_data[$key1][$key2]->thumb_title = $d_prefix . nl2br( $value2->description );
						$this->_data[$key1][$key2]->thumb_desc = $a_prefix . $value2->autor;
						break;
					case 'a':
						$this->_data[$key1][$key2]->thumb_title = '';
						$this->_data[$key1][$key2]->thumb_desc = $a_prefix . $value2->autor;
						break;
					case 'd':
						$this->_data[$key1][$key2]->thumb_title = '';
						$this->_data[$key1][$key2]->thumb_desc = $d_prefix . nl2br( $value2->description );
						break;
					case 'none':
					default:
						$this->_data[$key1][$key2]->thumb_title = '';
						$this->_data[$key1][$key2]->thumb_desc = '';
				}
			}
		}
		
		return $this->_data;
	}
	
	function getCategoryData() {
		return $this->_categoryData;
	}
}
