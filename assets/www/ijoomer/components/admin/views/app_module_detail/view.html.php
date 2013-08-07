<?php
 /**
 * @copyright Copyright (C) 2010 Tailored Solutions. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by Tailored Solutions - ijoomer.com
 *
 * ijoomer can be downloaded from www.ijoomer.com
 * ijoomer is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with ijoomer; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined( '_JEXEC' ) or die( 'Restricted access' );


jimport( 'joomla.application.component.view' );


class app_module_detailVIEWapp_module_detail extends JView
{
	function display($tpl = null)
	{
		global $context;
		
		$mainframe = JFactory::getApplication();
		
		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('App Module') );

		$uri 		=& JFactory::getURI();
		
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		'a.ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'ASC',			'word' );
		
		JToolBarHelper::title(   JText::_( 'App Module' ), 'application-manager_48' );
		
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel( 'cancel', 'Close' );
		
		$model = $this->getModel('app_module_detail');
		
		$module_detail	= & $this->get( 'Data');
		$group = $this->getAccessLevel();
		$screen = $this->getScreen();
		
		$this->assignRef('module_detail',$module_detail);
		$this->assignRef('group',$group);
		$this->assignRef('screen',$screen);
		$this->assignRef('request_url',$uri->toString());
		
		parent::display($tpl);
	}
	
	function getAccessLevel()
	{
			$db		=& JFactory::getDBO();
			
			$sql = "SELECT * FROM #__groups ";
			$db->setQuery($sql);
			$group = $db->loadObjectlist();
			
			return $group;
	}
	function getScreen()
	{
			$db		=& JFactory::getDBO();
			
			$sql = "SELECT * FROM #__ijoomer_plist WHERE published = '1' ";
			$db->setQuery($sql);
			$screen = $db->loadObjectlist();
			
			return $screen;
	}
}
?>