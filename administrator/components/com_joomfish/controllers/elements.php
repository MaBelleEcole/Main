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
 * $Id: elements.php 1344 2009-06-18 11:50:09Z akede $
 * @package joomfish
 * @subpackage elements
 *
*/

defined( 'JPATH_BASE' ) or die( 'Direct Access to this location is not allowed.' );

jimport('joomla.application.component.controller');

JLoader::import( 'helpers.controllerHelper',JOOMFISH_ADMINPATH);

/**
 * The JoomFish Tasker manages the general tasks within the Joom!Fish admin interface
 *
 */
class ElementsController extends JController   {

	/** @var string		current used task */
	var $task=null;

	/** @var string		action within the task */
	var $act=null;

	/** @var array		int or array with the choosen list id */
	var $cid=null;

	/** @var string		file code */
	var $fileCode = null;

	/**
	 * @var object	reference to the Joom!Fish manager
	 * @access private
	 */
	var $_joomfishManager=null;

	/**
	 * PHP 4 constructor for the tasker
	 *
	 * @return joomfishTasker
	 */
	function __construct( ){
		parent::__construct();
		$this->registerDefaultTask( 'show' );

		$this->act =  JRequest::getVar( 'act', '' );
		$this->task =  JRequest::getVar( 'task', '' );
		$this->cid =  JRequest::getVar( 'cid', array(0) );
		if (!is_array( $this->cid )) {
			$this->cid = array(0);
		}
		$this->fileCode =  JRequest::getVar( 'fileCode', '' );
		$this->_joomfishManager =& JoomFishManager::getInstance();

		$this->registerTask( 'show', 'showCElementConfig' );
		$this->registerTask( 'detail', 'showElementConfiguration' );
		$this->registerTask( 'remove', 'removeContentElement' );
		$this->registerTask( 'remove_install', 'removeContentElement' );
		$this->registerTask( 'installer', 'showContentElementsInstaller' );
		$this->registerTask( 'uploadfile', 'installContentElement' );

		// Populate data used by controller
		global $mainframe;
		$this->_catid = $mainframe->getUserStateFromRequest('selected_catid', 'catid', '');
		$this->_select_language_id = $mainframe->getUserStateFromRequest('selected_lang','select_language_id', '-1');		
		$this->_language_id =  JRequest::getVar( 'language_id', $this->_select_language_id );
		$this->_select_language_id = ($this->_select_language_id == -1 && $this->_language_id != -1) ? $this->_language_id : $this->_select_language_id;
		
		// Populate common data used by view
		// get the view
		$this->view = & $this->getView("elements");

		// Assign data for view 
		$this->view->assignRef('catid'   , $this->_catid);
		$this->view->assignRef('select_language_id',  $this->_select_language_id);
		$this->view->assignRef('task', $this->task);
		$this->view->assignRef('act', $this->act);
	}

	// DONE
	function showCElementConfig() {
		$db =& JFactory::getDBO();

		JoomfishControllerHelper::_setupContentElementCache();

		$this->showElementOverview();
	}

	/**
	 * Installs the uploaded file
	 *
	 */
	function installContentElement() {
		if (@is_uploaded_file($_FILES["userfile"]["tmp_name"])) {
			JLoader::import( 'helpers.jfinstaller',JOOMFISH_ADMINPATH);
			$installer = new jfInstaller();
			if ( $installer->install( $_FILES["userfile"] )){
				$msg = JText::_('Fileupload successful');
			}
			else {
				JError::raiseError('SOME_ERROR_CODE', JText::_('Fileupload not successful'));
			}
		} else {
			JError::raiseError('SOME_ERROR_CODE', JText::_('Fileupload not successful'));
		}
		$this->setRedirect('index.php?option=com_joomfish&task=elements.installer', $msg);;	
	}

	/**
	 * method to remove all selected content element files
	 */
	function removeContentElement() {
		// Check for request forgeries
		//JRequest::checkToken() or die( 'Invalid Token' );
		
		if( $this->_deleteContentElement($this->cid[0]) ) {
			$msg = JText::sprintf('ELEMENTFILE DELETED', $this->cid[0]);
		}
		if($this->_task == 'remove_install') {
			$this->setRedirect('index.php?option=com_joomfish&task=elements.installer', $msg);;	
		} else {
			$this->setRedirect('index.php?option=com_joomfish&task=elements.show', $msg);;	
					}
	}

	/**
	 * Method deletes one content element file
	 * @param filename
	 */
	// DONE
	function _deleteContentElement( $filename = null ) {

		$elementfolder = JOOMFISH_ADMINPATH .DS. 'contentelements/';
		$filename .= '.xml';
		jimport('joomla.filesystem.file');
		return JFile::delete($elementfolder . $filename);
	}

	/** Presentation of the content element list
	 */
	//DONE
	function showElementOverview() {
		$db =& JFactory::getDBO();
		global  $mainframe;

		$limit = $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', 10 );
		$limitstart = $mainframe->getUserStateFromRequest( "view{com_joomfish}limitstart", 'limitstart', 0 );
		$total=count($this->_joomfishManager->getContentElements());

		require_once( JPATH_SITE . "/administrator/includes/pageNavigation.php");
		$pageNav = new mosPageNav( $total, $limitstart, $limit );

		// get the view
		$view = & $this->getView("elements");

		// Set the layout
		$view->setLayout('default');

		// Assign data for view - should really do this as I go along
		$view->assignRef('pageNav'   , $pageNav);
		$view->assignRef('joomfishManager'   , $this->_joomfishManager);
		$view->display();
		//HTML_joomfish::showElementOverview( $this->_joomfishManager, $pageNav );
	}

	/** Detailinformation about one specific content element */
	// DONE - should move more from the view to here or the model!
	function showElementConfiguration( ) {
		$cid =  JRequest::getVar( 'cid', array(0) );
		if (count($cid)>0){
			$id = $cid[0];
		}
		// get the view
		$view = & $this->getView("elements");

		// Set the layout
		$view->setLayout('edit');

		// Assign data for view - should really do this as I go along
		$view->assignRef('id'   , $id);
		$view->assignRef('joomfishManager'   , $this->_joomfishManager);
		$view->display();
		//HTML_joomfish::showElementConfiguration( $this->_joomfishManager, $id );
	}

	/**
	 * Method to install content element files
	 *
	 */
	//DONE
	function showContentElementsInstaller() {
		$cElements = $this->_joomfishManager->getContentElements(true);
		// get the view
		$view = & $this->getView("elements");

		// Set the layout
		$view->setLayout('installer');

		// Assign data for view - should really do this as I go along
		$view->assignRef('cElements'   , $cElements);
		$view->display();
		//HTML_joomfish::showContentElementInstaller( $cElements, $this->view->message );
	}

	
	
}
