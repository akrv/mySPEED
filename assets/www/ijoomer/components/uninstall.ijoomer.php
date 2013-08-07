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

function com_uninstall() 
{
		$db = JFactory::getDBO();

		//Delete plugin config	
		$query="select * from #__ijoomer_plugins";
		$db->setQuery($query);
		$rows=$db->loadObjectlist();
			
		for($i=0;$i<count($rows);$i++){
			$query="DROP TABLE `#__ijoomer_".$rows[$i]->plugin_classname."_config`";
			$db->setQuery($query);
			$db->Query();
		}
}

?>