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

function com_install()
{
	$db = JFactory::getDBO();
	
	$q = "SHOW COLUMNS FROM #__users";
	$db->setQuery($q);
	$cols = $db->loadObjectList('Field');

	if (is_array($cols)) {
		if (!array_key_exists('device_token', $cols)) {
			$q = "ALTER IGNORE TABLE #__users ADD `device_token` VARCHAR( 255 ) NOT NULL";
			$db->setQuery($q);
			$db->query();
		}
	}
	
	include_once(dirname(__FILE__).'/install/install.script.php');
	
	com_ijoomer_install_script::setconfig();
	com_ijoomer_install_script::setplist();
	com_ijoomer_install_script::setdevices();
	com_ijoomer_install_script::setModules();
	com_ijoomer_install_script::setfolders();
	
}
?>