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

jimport('joomla.application.component.model');

class app_push_notificationModelapp_push_notification extends JModel
{
	var $_data = null;
	var $_table_prefix = null;
	var $_table = null;

	function __construct()
	{
		parent::__construct();
		global $context; 
		$mainframe = JFactory::getApplication();
		$context='id';
	  	$this->_table_prefix = '#__ijoomer_';
	  				
		  	
	  	$limit				= $mainframe->getUserStateFromRequest( 'global.list.limit',	'limit',$mainframe->getCfg( 'list_limit' ),	'int' );
		$limitstart			= $mainframe->getUserStateFromRequest( 'com_ijoomer.limitstart','limitstart',0,'int' );
		
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}
	
	function getData()
	{		
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
			//$this->_data = $this->_getList($query);
		}
		return $this->_data;
	}
	function getTotal()
	{
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}
	function getPagination()
	{
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}
		
		return $this->_pagination;
	}
	
	function _buildQuery()
	{	    
				
		$query		= "SELECT push.* FROM ".$this->_table_prefix."push_notification AS push order by id";
		return $query;
	}
	
	
}