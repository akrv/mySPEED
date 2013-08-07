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

class app_module_detailModelapp_module_detail extends JModel
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
	  				
	}
	function getData()
	{		
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}
		//echo "<pre>";print_r($this->_data);exit;
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
		$reset = JRequest :: getInt('reset','','post');
		
		//$where = "";
		
		$orderby	= $this->_buildContentOrderBy();		
		$query = ' SELECT a.* '
			. ' FROM '.$this->_table_prefix.'app_module AS a'
			.$orderby;
		//echo $query;
		return $query;
	}
	function _buildContentOrderBy()
	{
		global  $context;
		
		$mainframe = JFactory::getApplication();
		
		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'a.ordering' );
		//echo $filter_order;
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );
		//echo $filter_order_Dir;		
					
		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir.', a.id ';			
		 		
		return $orderby;
	}
	function store($data)
	{
		
		require_once(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_ijoomer'.DS.'tables'.DS.'app_module.php');
		
		$row =& JTable::getInstance('app_module');   
		$row->load($data['id']);
		
		$this->write_configuration($data);
		
		return true;
	}
	function write_configuration($d)
	{
		//echo "<pre>";print_r($d);
		$db =& JFactory::getDBO();
		
		if($d['pidi']!= "" )
		{
			$d['pidi'] = implode(",",$d['pidi']);
		}
		if($d['pida']!= "")	
		{
			$d['pida'] = implode(",",$d['pida']);
		}
		if($d['pidb']!= "" )
		{	
			$d['pidb'] = implode(",",$d['pidb']);
		}
		$sql = "UPDATE #__ijoomer_app_module SET  name = '".$d['name']."',title = '".$d['title']."',published = '".$d['published']."',access = '".$d['access']."',screens_i = '".$d['screens_i']."',pidi = '".$d['pidi']."',screens_a = '".$d['screens_a']."',pida = '".$d['pida']."',screens_b = '".$d['screens_b']."',pidb = '".$d['pidb']."' WHERE id = '".$d['id']."' ";
			$db->setQuery($sql);
			$db->query();	

		return true;
	}
}
?>