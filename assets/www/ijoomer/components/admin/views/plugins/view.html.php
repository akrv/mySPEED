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

class pluginsVIEWplugins extends JView
{
	function display($tpl = null)
	{
		global $context;
		
		$mainframe = JFactory::getApplication();
		
		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('PLUGINS') );

		$uri 		=& JFactory::getURI();
		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'content_id' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );
		
		JToolBarHelper::title(   JText::_( 'PLUGIN_MANAGEMENT' ), 'plugins_48' );
		JToolBarHelper::addNewX();
	  	//Code for add submenu for joomla version 1.6 and 1.7
		jimport('joomla.version');
		$version = new JVersion();
		if($version->RELEASE > 1.5){	
			$vName ='plugins';                
			JSubMenuHelper::addEntry( JText::_('PLUGINS'), 'index.php?option=com_ijoomer&view=plugins', $vName == 'plugins' );
			JSubMenuHelper::addEntry( JText::_('GLOBAL_CONFIGURATION'), 'index.php?option=com_ijoomer&view=config', $vName == 'config' );
			JSubMenuHelper::addEntry( JText::_('APPLICATION_SCREEN_MANAGER'), 'index.php?option=com_ijoomer&view=application_manager', $vName == 'application_manager' );
			JSubMenuHelper::addEntry( JText::_('APPLICATION_MODULES'), 'index.php?option=com_ijoomer&view=app_module', $vName == 'app_module' );
			JSubMenuHelper::addEntry( JText::_('QR_CODE'), 'index.php?option=com_ijoomer&view=qrcode', $vName == 'qrcode' );
			JSubMenuHelper::addEntry( JText::_('PUSH_NOTIFICATION'), 'index.php?option=com_ijoomer&view=app_push_notification', $vName == 'app_push_notification' );
		}
		
		$model = $this->getModel('plugins');
		
		$lists = array();
		$plugins				= & $this->get( 'Data');
		$pagination				= & $this->get( 'Pagination');
		
		$lists['order'] 		= $filter_order;  
		$lists['order_Dir'] 	= $filter_order_Dir;
		
		$this->assignRef('lists',		$lists);
		$this->assignRef('plugins',		$plugins);
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('request_url',	$uri->toString());
		
		parent::display($tpl);
	}
	
}
?>