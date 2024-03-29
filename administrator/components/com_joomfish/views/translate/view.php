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
 * $Id: view.php 1344 2009-06-18 11:50:09Z akede $
 * @package joomfish
 * @subpackage Views
 *
*/
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

JLoader::import( 'views.default.view',JOOMFISH_ADMINPATH);

/**
 * View class for translation overview
 *
 * @static
 * @package		Joom!Fish
 * @subpackage	translation
 * @since 2.0
 */
class TranslateViewTranslate extends JoomfishViewDefault
{
	/**
	 * Setting up special general attributes within this view
	 * These attributes are independed of the specifc view
	 */
	function _initialize($layout="overview") {
		// get list of active languages
		$langOptions[] = JHTML::_('select.option',  '-1', JText::_('Select Language') );
		// Get data from the model
		$langActive = $this->get('Languages');		// all languages even non active once
		$defaultLang = $this->get('DefaultLanguage');
		$params = JComponentHelper::getParams('com_joomfish');
		$showDefaultLanguageAdmin = $params->get("showDefaultLanguageAdmin", true);

		if ( count($langActive)>0 ) {
			foreach( $langActive as $language )
			{
				if($language->code != $defaultLang || $showDefaultLanguageAdmin) {
					$langOptions[] = JHTML::_('select.option',  $language->id, $language->name );
				}
			}
		}
		if ($layout == "overview" || $layout == "default"){
			$langlist = JHTML::_('select.genericlist', $langOptions, 'select_language_id', 'class="inputbox" size="1" onchange="if(document.getElementById(\'catid\').value.length>0) document.adminForm.submit();"', 'value', 'text', $this->select_language_id );
		}
		else {
			$confirm="";
			if ($this->actContentObject->language_id!=0){
				$confirm="onchange=\"confirmChangeLanguage('".$this->actContentObject->language."','".$this->actContentObject->language_id."')\"";
			}

			$langlist = JHTML::_('select.genericlist', $langOptions, 'language_id', 'class="inputbox" size="1" '.$confirm, 'value', 'text', $this->actContentObject->language_id );
		}
		$this->assignRef('langlist'   , $langlist);
	}
	/**
	 * Control Panel display function
	 *
	 * @param template $tpl
	 */
	function display($tpl = null)
	{
		$document =& JFactory::getDocument();
		$document->setTitle(JText::_('JOOMFISH_TITLE') . ' :: ' .JText::_('TITLE_TRANSLATION'));

		// Set  page title
		JToolBarHelper::title( JText::_( 'TITLE_TRANSLATION' ), 'jftranslations' );

		$layout = $this->getLayout();

		$this->_initialize($layout);
		if (method_exists($this,$layout)){
			$this->$layout($tpl);
		} else {
			$this->overview($tpl);
		}

		JHTML::_('behavior.tooltip');
		parent::display($tpl);
	}


	function overview($tpl = null)
	{
		// browser title
		$document =& JFactory::getDocument();
		$document->setTitle(JText::_('JOOMFISH_TITLE') . ' :: ' .JText::_('TRANSLATE'));

		// set page title
		JToolBarHelper::title( JText::_( 'TRANSLATE' ), 'translation' );

		// Set toolbar items for the page
		JToolBarHelper::publish("translate.publish");
		JToolBarHelper::unpublish("translate.unpublish");
		JToolBarHelper::editList("translate.edit");
		JToolBarHelper::deleteList("ARE YOU SURE YOU WANT TO DELETE THIS TRANSLATION", "translate.remove");
		JToolBarHelper::custom( 'cpanel.show', 'joomfish', 'joomfish', 'CONTROL PANEL', false );
		JToolBarHelper::help( 'screen.translate.overview', true);

		JSubMenuHelper::addEntry(JText::_('Control Panel'), 'index2.php?option=com_joomfish', false);
		JSubMenuHelper::addEntry(JText::_('Translation'), 'index2.php?option=com_joomfish&amp;task=translate.overview', true);
		JSubMenuHelper::addEntry(JText::_('Orphans'), 'index2.php?option=com_joomfish&amp;task=translate.orphans');
		JSubMenuHelper::addEntry(JText::_('Manage Translations'), 'index2.php?option=com_joomfish&amp;task=manage.overview', false);
		JSubMenuHelper::addEntry(JText::_('Statistics'), 'index2.php?option=com_joomfish&amp;task=statistics.overview', false);
		JSubMenuHelper::addEntry(JText::_('Language Configuration'), 'index2.php?option=com_joomfish&amp;task=languages.show', false);
		JSubMenuHelper::addEntry(JText::_('Content elements'), 'index2.php?option=com_joomfish&amp;task=elements.show', false);
		JSubMenuHelper::addEntry(JText::_('HELP AND HOWTO'), 'index2.php?option=com_joomfish&amp;task=help.show', false);
	}

	function edit($tpl = null)
	{
		// browser title
		$document =& JFactory::getDocument();
		$document->setTitle(JText::_('JOOMFISH_TITLE') . ' :: ' .JText::_('Translate'));

		// set page title
		JToolBarHelper::title( JText::_( 'Translate' ), 'translation' );

		// Set toolbar items for the page
		if (JRequest::getVar("catid","")=="content"){
			//JToolBarHelper::preview('index.php?option=com_joomfish',true);
			$bar = & JToolBar::getInstance('toolbar');
			// Add a special preview button by hand
			$live_site = JURI::base();
			$bar->appendButton( 'Popup', 'preview', 'Preview', JRoute::_("index.php?option=com_joomfish&task=translate.preview&tmpl=component"), "800","550");
		}
		JToolBarHelper::save("translate.save");
		JToolBarHelper::apply("translate.apply");
		JToolBarHelper::cancel("translate.overview");
		JToolBarHelper::help( 'screen.translate.edit', true);

		JRequest::setVar('hidemainmenu',1);
	}

	function orphans($tpl = null)
	{
		// browser title
		$document =& JFactory::getDocument();
		$document->setTitle(JText::_('JOOMFISH_TITLE') . ' :: ' .JText::_('CLEANUP ORPHANS'));

		// set page title
		JToolBarHelper::title( JText::_( 'CLEANUP ORPHANS' ), 'orphan' );

		// Set toolbar items for the page
		JToolBarHelper::deleteList(JText::_("ARE YOU SURE YOU WANT TO DELETE THIS TRANSLATION"), "translate.removeorphan");
		JToolBarHelper::custom( 'cpanel.show', 'joomfish', 'joomfish', 'CONTROL PANEL', false );
		JToolBarHelper::help( 'screen.translate.orphans', true);

		JSubMenuHelper::addEntry(JText::_('Control Panel'), 'index2.php?option=com_joomfish', false);
		JSubMenuHelper::addEntry(JText::_('Translation'), 'index2.php?option=com_joomfish&amp;task=translate.overview', false);
		JSubMenuHelper::addEntry(JText::_('Orphans'), 'index2.php?option=com_joomfish&amp;task=translate.orphans', true);
		JSubMenuHelper::addEntry(JText::_('Manage Translations'), 'index2.php?option=com_joomfish&amp;task=manage.overview', false);
		JSubMenuHelper::addEntry(JText::_('Statistics'), 'index2.php?option=com_joomfish&amp;task=statistics.overview', false);
		JSubMenuHelper::addEntry(JText::_('Language Configuration'), 'index2.php?option=com_joomfish&amp;task=languages.show', false);
		JSubMenuHelper::addEntry(JText::_('Content elements'), 'index2.php?option=com_joomfish&amp;task=elements.show', false);
		JSubMenuHelper::addEntry(JText::_('HELP AND HOWTO'), 'index2.php?option=com_joomfish&amp;task=help.show', false);
	}

	function orphandetail($tpl = null)
	{
		// browser title
		$document =& JFactory::getDocument();
		$document->setTitle(JText::_('JOOMFISH_TITLE') . ' :: ' .JText::_('CLEANUP ORPHANS'));

		// set page title
		JToolBarHelper::title( JText::_( 'CLEANUP ORPHANS' ), 'orphan' );

		// Set toolbar items for the page
		//JToolBarHelper::deleteList(JText::_("ARE YOU SURE YOU WANT TO DELETE THIS TRANSLATION"), "translate.removeorphan");
		JToolBarHelper::back();
		JToolBarHelper::custom( 'cpanel.show', 'joomfish', 'joomfish', 'CONTROL PANEL', false );
		JToolBarHelper::help( 'screen.translate.orphans', true);

		// hide the sub menu
		// This won't work
		$submenu = & JModuleHelper::getModule("submenu");
		$submenu->content = "\n";

		JRequest::setVar('hidemainmenu',1);
	}

	function preview($tpl = null)
	{
		// hide the sub menu
		$this->_hideSubmenu();
		parent::display($tpl);

	}
}
