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


class app_push_notificationVIEWapp_push_notification extends JView
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
		
		JToolBarHelper::title(   JText::_( 'Push Notification' ), 'application-manager_48' );
		
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
		JToolBarHelper::addNew( 'add', 'New' );
				
		$model = $this->getModel('app_push_notification');
		
		
		$app_push_data	= & $this->get( 'Data');
		
		$pagination	= & $this->get('Pagination');
		$this->assignRef('page',$pagination);
		$this->assignRef('app_push_data',$app_push_data);
		$this->assignRef('request_url',$uri->toString());
		
		//$parent_addview = &JRequest::getVar('task');
		//echo $parent_addview; exit;
		/*if($parent_addview == "add")
		{
			echo "add"; exit;
			JRequest::setvar('view','app_push_notification_detail');
			JRequest::setVar('layout','default');
			JRequest::setVar('task','add');
			parent::display();
		} else {
			//echo "display"; exit;
			//echo $tpl; 
			parent::display($tpl);
		}*/
		parent::display($tpl);
		
	}

}
?>