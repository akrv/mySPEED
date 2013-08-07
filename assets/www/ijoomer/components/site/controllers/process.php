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

jimport( 'joomla.application.component.controller' );

jimport('joomla.error.log');
 
class processController extends JController
{
	function __construct( $default = array())
	{
		parent::__construct( $default );
	}
	function display()
	{
		$app = &JFactory::getApplication();
		$app->ijoomer_debug->startTime= microtime(true)*1000;
		$model = $this->getModel('process');
		$arr = $model->process(); 
		$app->ijoomer_debug->endProcessTime = microtime(true)*1000;
		$app->ijoomer_debug->processTimeDiff = $app->ijoomer_debug->endProcessTime - $app->ijoomer_debug->startTime;
		$app->ijoomer_debug->requestUrl=$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		$model->generateXML($arr);
	}
	
}
	
?>