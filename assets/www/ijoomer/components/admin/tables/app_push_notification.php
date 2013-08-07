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

//jimport('joomla.application.component.model');
 
//class JTableApp_module extends JTable
class JTableapp_push_notification extends JTable
{
	var $id = null;
	var $device_type = null;
	var $send_to_user = null;
	var $send_to_all = null;
	var $notification_text = null;
	var $current_tmsp = null;
		
	function __construct(&$db) 
	{
		parent::__construct('#__ijoomer_push_notification','id',$db);
	}
	
	 
}
?>