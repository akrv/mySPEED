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


class application_managerVIEWapplication_manager extends JView
{
	function display($tpl = null)
	{
			
		global $context;
		
		$mainframe = JFactory::getApplication();
		jimport('joomla.html.pane');
		$pane =& JPane::getInstance('tabs', array('startOffset'=>0)); 
		
		
		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('APPLICATION_MANAGER') );

		$uri 		=& JFactory::getURI();
		
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		'ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'ASC',			'word' );
		
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;
		
		JToolBarHelper::title(   JText::_( 'APPLICATION_MANAGER' ), 'application-manager_48' );
		
	//	JToolBarHelper::publishList();
	//	JToolBarHelper::unpublishList();
		JToolBarHelper::cancel( 'cancel', 'Close' );
		
		
		jimport('joomla.version');
		$version = new JVersion();
		if($version->RELEASE > 1.5)
		{	
			$vName ='application_manager';                
			JSubMenuHelper::addEntry( JText::_('PLUGINS'), 'index.php?option=com_ijoomer&view=plugins', $vName == 'plugins' );
			JSubMenuHelper::addEntry( JText::_('GLOBAL_CONFIGURATION'), 'index.php?option=com_ijoomer&view=config', $vName == 'config' );
			JSubMenuHelper::addEntry( JText::_('APPLICATION_SCREEN_MANAGER'), 'index.php?option=com_ijoomer&view=application_manager', $vName == 'application_manager' );
			JSubMenuHelper::addEntry( JText::_('APPLICATION_MODULES'), 'index.php?option=com_ijoomer&view=app_module', $vName == 'app_module' );
			JSubMenuHelper::addEntry( JText::_('QR_CODE'), 'index.php?option=com_ijoomer&view=qrcode', $vName == 'qrcode' );
			JSubMenuHelper::addEntry( JText::_('PUSH_NOTIFICATION'), 'index.php?option=com_ijoomer&view=app_push_notification', $vName == 'app_push_notification' );
		}
		
		$model = $this->getModel('application_manager');
		
		
		$application_manager	= & $this->get( 'Data');
		$pagination				= & $this->get( 'Pagination');
		//$lists		= &$this->_getViewLists();
		
		$adata = $model->getanroidlist();
		$apage = $model->getaPagination();
		
		$ordering = $lists['order'];
		
		$this->assignRef('pane',$pane);
		$this->assignRef('page',$pagination);
		$this->assignRef('lists',$lists);
		$this->assignRef('ordering', $ordering);
		$this->assignRef('application_manager',$application_manager);
		$this->assignRef('pagination',$pagination);
		
		$this->assignRef('adata',$adata);
		$this->assignRef('apage',$apage);
		
		
		$this->assignRef('request_url',$uri->toString());
		
		parent::display($tpl);
	}
	
	function showanroid($rows, $pagination){
		
			
		global $context;
		
		$mainframe = JFactory::getApplication();
		jimport('joomla.html.pane');
		$pane =& JPane::getInstance('tabs', array('startOffset'=>1)); 
		
		
		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('APPLICATION_MANAGER') );

		$uri 		=& JFactory::getURI();
		
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order1',		'filter_order1',		'ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir1',	'filter_order_Dir1',	'ASC',			'word' );
		
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;
		
		JToolBarHelper::title(   JText::_( 'APPLICATION_MANAGER' ), 'application-manager_48' );
		
		//JToolBarHelper::publishList();
		//JToolBarHelper::unpublishList();
		JToolBarHelper::cancel( 'cancel', 'Close' );
		
		
		jimport('joomla.version');
		$version = new JVersion();
		if($version->RELEASE == 1.6  || $version->RELEASE == 1.7 )
		{	
			$vName ='application_manager';                
			JSubMenuHelper::addEntry( JText::_('PLUGINS'), 'index.php?option=com_ijoomer&view=plugins', $vName == 'plugins' );
			JSubMenuHelper::addEntry( JText::_('GLOBAL_CONFIGURATION'), 'index.php?option=com_ijoomer&view=config', $vName == 'config' );
			JSubMenuHelper::addEntry( JText::_('APPLICATION_SCREEN_MANAGER'), 'index.php?option=com_ijoomer&view=application_manager', $vName == 'application_manager' );
			JSubMenuHelper::addEntry( JText::_('APPLICATION_MODULES'), 'index.php?option=com_ijoomer&view=app_module', $vName == 'app_module' );
		}
		
	
		
		
		
			
		
		
		$ordering = ($lists['order'] == 'ordering');
		
		$this->assignRef('pane',$pane);
		$this->assignRef('lists',$lists);
		$this->assignRef('ordering', $ordering);
		$this->assignRef('rows',$rows);
		$this->assignRef('pagination',$pagination);
		$this->assignRef('request_url',$uri->toString());
		
		parent::display();
	}
	
	function showbb($rows, $pagination){
		
					
		global $context;
		
		$mainframe = JFactory::getApplication();
		jimport('joomla.html.pane');
		$pane =& JPane::getInstance('tabs', array('startOffset'=>2)); 
		
		
		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('APPLICATION_MANAGER') );

		$uri 		=& JFactory::getURI();
		
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order2',		'filter_order2',		'ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir2',	'filter_order_Dir2',	'ASC',			'word' );
		
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;
		
		JToolBarHelper::title(   JText::_( 'APPLICATION_MANAGER' ), 'application-manager_48' );
		
		//JToolBarHelper::publishList();
	//	JToolBarHelper::unpublishList();
		JToolBarHelper::cancel( 'cancel', 'Close' );
		
		
		jimport('joomla.version');
		$version = new JVersion();
		if($version->RELEASE == 1.6  || $version->RELEASE == 1.7 )
		{	
			$vName ='application_manager';                
			JSubMenuHelper::addEntry( JText::_('PLUGINS'), 'index.php?option=com_ijoomer&view=plugins', $vName == 'plugins' );
			JSubMenuHelper::addEntry( JText::_('GLOBAL_CONFIGURATION'), 'index.php?option=com_ijoomer&view=config', $vName == 'config' );
			JSubMenuHelper::addEntry( JText::_('APPLICATION_SCREEN_MANAGER'), 'index.php?option=com_ijoomer&view=application_manager', $vName == 'application_manager' );
			JSubMenuHelper::addEntry( JText::_('APPLICATION_MODULES'), 'index.php?option=com_ijoomer&view=app_module', $vName == 'app_module' );
		}
	
		$ordering = ($lists['order'] == 'ordering');
		
		$this->assignRef('pane',$pane);
		$this->assignRef('lists',$lists);
		$this->assignRef('ordering', $ordering);
		$this->assignRef('rows',$rows);
		$this->assignRef('pagination',$pagination);
		$this->assignRef('request_url',$uri->toString());
		
		parent::display();
		
	}


	function &_getViewLists()
	{
		//global $mainframe;
		$mainframe = JFactory::getApplication();
		$db		=& JFactory::getDBO();

		$filter_order		= $mainframe->getUserStateFromRequest( "com_ijoomer.filter_order",		'filter_order',		'ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( "com_ijoomer.filter_order_Dir",	'filter_order_Dir',	'ASC',			'word' );
		
		// ensure $filter_order has a good value
		if (!in_array($filter_order, array('a.app_id','a.screen_name','a,screen_value','a.component', 'a.plugin_name','a.plugin_option','a.plugin_classname',  'a.ordering','a.published'))) {
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
	
	function edit_form($row,$devices,$ordering){
		global $context;
		
		$mainframe = JFactory::getApplication();
		
		$document = & JFactory::getDocument();
		
		JRequest::setVar( 'hidemainmenu', 1 );
		
		$document->setTitle(JText::_('APPLICATION_MANAGER')."-".JText::_('APPLICATION_MANAGER_EDIT') );
		
		JToolBarHelper::title($row[0]->plist_name, 'application-manager_48' );
		JToolBarHelper::cancel( 'cancel2', 'Close' );
		
		$this->assignRef('row',$row);
		$this->assignRef('devices',$devices);
		$this->assignRef('ordering',$ordering);

		
		$uri="index.php?option=com_ijoomer&view=application_manager";
		$this->assignRef('request_url',$uri);
		
		parent::display();
	}
}
?>