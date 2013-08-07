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


class app_moduleVIEWapp_module extends JView
{
	function display($tpl = null)
	{
		global $context;
		
		$mainframe = JFactory::getApplication();
		
		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('APPLICATION_MODULES') );

		$uri 		=& JFactory::getURI();
		
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		'a.ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'ASC',			'word' );
		
		JToolBarHelper::title(   JText::_( 'App Module' ), 'application-manager_48' );
		
		//Code for add submenu for joomla version 1.6 and 1.7
		jimport('joomla.version');
		$version = new JVersion();
		if($version->RELEASE > 1.5)
		{	
			$vName ='plugins';                
			JSubMenuHelper::addEntry( JText::_('PLUGINS'), 'index.php?option=com_ijoomer&view=plugins', $vName == 'plugins' );
			JSubMenuHelper::addEntry( JText::_('GLOBAL_CONFIGURATION'), 'index.php?option=com_ijoomer&view=config', $vName == 'config' );
			JSubMenuHelper::addEntry( JText::_('APPLICATION_SCREEN_MANAGER'), 'index.php?option=com_ijoomer&view=application_manager', $vName == 'application_manager' );
			JSubMenuHelper::addEntry( JText::_('APPLICATION_MODULES'), 'index.php?option=com_ijoomer&view=app_module', $vName == 'app_module' );
			JSubMenuHelper::addEntry( JText::_('QR_CODE'), 'index.php?option=com_ijoomer&view=qrcode', $vName == 'qrcode' );
			JSubMenuHelper::addEntry( JText::_('PUSH_NOTIFICATION'), 'index.php?option=com_ijoomer&view=app_push_notification', $vName == 'app_push_notification' );
		}
		
		
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::cancel( 'cancel', 'Close' );
				
		$model = $this->getModel('app_module');
		
		
		$app_module	= & $this->get( 'Data');
		$pagination				= & $this->get( 'Pagination');
		$lists		= &$this->_getViewLists();
		
		
		$ordering = ($lists['order'] == 'a.ordering');
		
		$this->assignRef('page',$pagination);
		$this->assignRef('lists',$lists);
		
		$this->assignRef('ordering', $ordering);
		$this->assignRef('app_module',$app_module);
		$this->assignRef('pagination',$pagination);
		$this->assignRef('request_url',$uri->toString());
		
		parent::display($tpl);
	}


function &_getViewLists()
	{
		//global $mainframe;
		$mainframe = JFactory::getApplication();
		$db		=& JFactory::getDBO();

		$filter_order		= $mainframe->getUserStateFromRequest( "com_ijoomer.filter_order",		'filter_order',		'a.ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( "com_ijoomer.filter_order_Dir",	'filter_order_Dir',	'ASC',			'word' );
		
		// ensure $filter_order has a good value
		if (!in_array($filter_order, array('a.id','a.name','a.type','a.published', 'a.title','a.enabled','a.access','a.description','a.screens_i','a.screens_a','a.screens_b','a.pidi','a.pida','a.pidb'))) {
			$filter_order = 'a.ordering';
		}

		if (!in_array(strtoupper($filter_order_Dir), array('ASC', 'DESC', ''))) {
			$filter_order_Dir = 'ASC';
		}

		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;

		return $lists;
	}

}
?>