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

class app_moduleModelapp_module extends JModel
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
	  				
		/*$limit		= $mainframe->getUserStateFromRequest( $context.'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
		$limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0 );*/
	  	
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
		$reset = JRequest :: getInt('reset','','post');
		
		//$where = "";
		
		$orderby	= $this->_buildContentOrderBy();		
		$query = ' SELECT a.*,p.plugin_id '
			. ' FROM '.$this->_table_prefix.'app_module AS a, #__ijoomer_plugins AS p where p.plugin_classname=a.plugin_classname'
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
	function publish($cid = array(),$publish = 1)
	{ 		
		if (count( $cid ))
		{
			$cids = implode( ',', $cid );
			$query = 'UPDATE '.$this->_table_prefix.'app_module'
					  . ' SET published = ' . intval( $publish )
					  . ' WHERE  id IN ( '.$cids.' )';
					  //echo $query;exit;
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}
	function move($direction,$id)
	{//echo $direction;echo 'move';exit;
		require_once(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_ijoomer'.DS.'tables'.DS.'app_module.php');
		// Instantiate an article table object
		$row =& JTable::getInstance('app_module');
		
		if (!$row->load($app_id)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		if (!$row->move( $direction, 'published >= 0' )) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		return true;
	}
	
}
?>