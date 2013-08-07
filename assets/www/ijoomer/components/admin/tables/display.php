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
 
class JTabledisplay extends JTable
{
	var $id = null;
	var $device_id 	 = null;
	var $plist_value = null;
	var $tab_icon = null;
	var $tab_icon2 = null;
	var $show_tab = null;
	var $list_icon = null;
	var $list_icon2 = null;
	var $show_list = null;
			
	
	function __construct(&$db) {
		parent::__construct('#__ijoomer_display','id',$db);
	}
	 
}
?>