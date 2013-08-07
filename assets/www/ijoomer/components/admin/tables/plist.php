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
 
class JTableplist extends JTable
{
	var $id = null;
	var $plist_name 	 = null;
	var $plist_value = null;
	var $description = null;
	var $type = null;
	var $published = null;
	var $ordering = null;
			
	
	function __construct(&$db) {
		parent::__construct('#__ijoomer_plist','id',$db);
	}
	 
}
?>