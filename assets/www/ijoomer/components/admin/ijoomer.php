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
	
    $controller = JRequest::getVar('view','plugins' );
	
    require_once (JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');
	require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ijoomer'.DS.'helpers'.DS.'helper.php');
	
	
    $document = & JFactory :: getDocument();
    $document->addStyleSheet('components'.DS.'com_ijoomer'.DS.'assets'.DS.'css'.DS.'ijoomer.css');
    $classname  = $controller.'controller';
    
    if(file_exists(JPATH_COMPONENT_SITE.DS.'config.cfg.php'))
    	include_once (JPATH_COMPONENT_SITE.DS.'config.cfg.php');
    
	if(!defined('JOOMLA15'))
	{
		jimport('joomla.version');
		$version = new JVersion();
	    if($version->RELEASE=='1.5')
	    	define('JOOMLA15',1);
	    else
	    	define('JOOMLA15',0);
	}
    	
    $controller = new $classname( array('default_task' => 'display') );

    $controller->execute( JRequest::getVar('task' ));

    $controller->redirect();

?>