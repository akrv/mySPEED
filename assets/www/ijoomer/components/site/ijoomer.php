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
	defined ('_JEXEC') or die ('Restricted access');
	
	$lang =& JFactory::getLanguage();
	$extension = 'com_ijoomer';
	$base_dir = JPATH_ADMINISTRATOR;

	require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ijoomer'.DS.'helpers'.DS.'helper.php');
	$help = new Adminhelper();
	//function to define global config	
	$help->getglobalconfig();			
	//Define Jomsocial Version
	define('JOMSOCIAL_VERSION',$help->getJomSocialVersion());
	 	
	$language_tag = 'en-GB';
	$lang->load($extension, $base_dir, $language_tag, true);
	
	jimport('joomla.version');
	$version = new JVersion();
	
	if($version->RELEASE=='1.5'){
	if(!defined('JOOMLA15')){
	define('JOOMLA15',1);
	define('JOOMLA15X',0);
	}
	}else{
	if($version->RELEASE=='1.6' || $version->RELEASE=='1.7' || $version->RELEASE=='2.5'){
	define('JOOMLA15X',1);
	define('JOOMLA15',0);
	}
	}
	
    $controller = JRequest::getVar('view','process');

    require_once (JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');
    
    $classname  = $controller.'controller';

    $controller = new $classname( array('default_task' => 'display') );

    $controller->execute( JRequest::getVar('task' ));

    $controller->redirect();

?>