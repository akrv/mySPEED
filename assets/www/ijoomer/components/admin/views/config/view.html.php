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

class configVIEWconfig extends JView
{
	function display($tpl = null)
	{
		
		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('CONFIGURATION') );

		$uri 		=& JFactory::getURI();
		
		$text = JText::_( 'EDIT' ); 
		JToolBarHelper::title(   JText::_( 'CONFIGURATION' ).': <small><small>[ ' . $text.' ]</small></small>', 'config_48' );
	 	JToolBarHelper::save();
		
		JToolBarHelper::cancel( 'cancel', 'Close' );
		
		//Code for add submenu for joomla version 1.6 and 1.7
		jimport('joomla.version');
		$version = new JVersion();
		if($version->RELEASE > 1.5)
		{	
			$vName ='config';         
			JSubMenuHelper::addEntry( JText::_('PLUGINS'), 'index.php?option=com_ijoomer&view=plugins', $vName == 'plugins' );
			JSubMenuHelper::addEntry( JText::_('GLOBAL_CONFIGURATION'), 'index.php?option=com_ijoomer&view=config', $vName == 'config' );
			JSubMenuHelper::addEntry( JText::_('APPLICATION_SCREEN_MANAGER'), 'index.php?option=com_ijoomer&view=application_manager', $vName == 'application_manager' );
			JSubMenuHelper::addEntry( JText::_('APPLICATION_MODULES'), 'index.php?option=com_ijoomer&view=app_module', $vName == 'app_module' );
			JSubMenuHelper::addEntry( JText::_('QR_CODE'), 'index.php?option=com_ijoomer&view=qrcode', $vName == 'qrcode' );
			JSubMenuHelper::addEntry( JText::_('PUSH_NOTIFICATION'), 'index.php?option=com_ijoomer&view=app_push_notification', $vName == 'app_push_notification' );
		}
				
		$model = $this->getModel('config');
		$profile = $model->getProfile();
		$row = $model->getConfig();
		$lists = array();
		
		//$this->assignRef('lists',		$lists);
		//$this->assignRef('config',		$libcontent_config);
		$this->assignRef('row',		$row);
		$this->assignRef('profile',		$profile);
		$this->assignRef('request_url',	$uri->toString());
		
		parent::display($tpl);
	}
	
}
?>