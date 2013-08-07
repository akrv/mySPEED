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

class application_managerModelapplication_manager extends JModel
{
	var $_data = null;
	var $_table_prefix = null;
	var $_table = null;
	var $_list = array();

	function __construct()
	{
		parent::__construct();
		global $context; 
		$mainframe = JFactory::getApplication();
		$task=JRequest::getVar("task");
		$context='com_ijoomer'.$task;
	  	$this->_table_prefix = '#__ijoomer_';

	  	if($task==""){
			$limit				= $mainframe->getUserStateFromRequest( $context.'global.list.limit',	'limit',$mainframe->getCfg( 'list_limit' ),	'int' );
			$limitstart			= $mainframe->getUserStateFromRequest( $context.'limitstart','limitstart',0,'int' );
			
			$this->setState('limit', $limit);
			$this->setState('limitstart', $limitstart);
	  	}
	  	if($task=="anroid"){
			$alimit				= $mainframe->getUserStateFromRequest( $context.'global.list.limit',	'limit',$mainframe->getCfg( 'list_limit' ),	'int' );
			$alimitstart			= $mainframe->getUserStateFromRequest( $context.'limitstart','limitstart',0,'int' );
			
			$this->setState('alimit', $alimit);
			$this->setState('alimitstart', $alimitstart);
	  	}	

	  	if($task=="bb"){
			$alimit				= $mainframe->getUserStateFromRequest( $context.'global.list.limit',	'limit',$mainframe->getCfg( 'list_limit' ),	'int' );
			$alimitstart			= $mainframe->getUserStateFromRequest( $context.'limitstart','limitstart',0,'int' );
			
			$this->setState('blimit', $alimit);
			$this->setState('blimitstart', $alimitstart);
	  	}	
		
		
		
	}
	function getData()
	{		
		$db = & JFactory::getDBO();
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			//$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
			$this->_data = $this->_getList($query);
		}
		
		$rows = $this->_data; 
		$children = array();
		foreach ($rows as $v )
		{
			
			if($v->parent==""){
				$v->parent=0;
			}else{
				$query="select id from #__ijoomer_plist where plist_value='".$v->parent."' and type='I'"; 
				$db->setQuery($query);
				$v->parent=$db->loadResult(); 
			}
			
			
			$pt = $v->parent;
			$list = @$children[$pt] ? $children[$pt] : array();
			array_push( $list, $v ); 
			$children[$pt] = $list;
		}
		
			$list_items=array();
			$this->tree(0,"",$children,array(),999,0);
			$list1=array();
			foreach ($this->_list as $item)
				{
					$list_items[] = $item;
				}
		$total = count( $list_items );		
		jimport('joomla.html.pagination');
		$this->_pagination = new JPagination( $total,  $this->getState('limitstart'), $this->getState('limit') );
		// slice out elements based on limits
		$list_items = array_slice( $list_items, $this->_pagination->limitstart, $this->_pagination->limit );	
			
		
		return $list_items;
	}
	
/*	function tree($id,$indent,&$children,$list,$maxlevel=999,$level=0){
		if (isset($children[$id]) && $level <= $maxlevel)
        {
        	foreach ($children[$id] as $v)
            {
            	$id = $v->id;
            	
                $pre    = '<sup>|_</sup>&nbsp;';
                $spacer = '.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                            
                if ($v->parent == 0) {
                	$txt    = $v->plist_name;
                } else {
                   $txt    =  $pre . $v->plist_name; 
                }
                $pt = $v->parent;
                $ttmp=$v;
                $ttmp->treename="$indent$txt";
                $ttmp->children = count(@$children[$id]);
                $this->_list[]=$ttmp;
                $this->tree($id,$indent.$spacer,$children,$list,$maxlevel,$level+1);
            }
        }
 	}*/
	
	function tree($id,$indent,&$children,$list,$maxlevel=999,$level=0){
		if (isset($children[$id]) && $level <= $maxlevel)
        {
        	foreach ($children[$id] as $v)
            {
            	$id = $v->id;
            	
                $pre    = '<sup>|_</sup>&nbsp;';
                $spacer = '.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                            
                if ($v->parent == 0) {
                	$txt    = $v->plist_name;
                } else {
                   $txt    =  $pre . $v->plist_name; 
                }
                $pt = $v->parent;
                $this->_list[$id]=$v;
                $this->_list[$id]->treename="$indent$txt";
                $this->_list[$id]->children = count(@$children[$id]);
                $this->tree($id,$indent.$spacer,$children,$list,$maxlevel,$level+1);
            }
        }
 	}
	
	function &getPagination()
	{
		if ($this->_pagination == null) {
			$this->getData();
		}
		return $this->_pagination;
	}
	
	function _buildQuery()
	{	    
		$reset = JRequest :: getInt('reset','','post');
		
		$orderby	= $this->_buildContentOrderBy();		
		$query = ' SELECT * '
			. ' FROM '.$this->_table_prefix.'plist where `type`="I" and published=1'
			.$orderby;
		return $query;
	}
	
	function _buildContentOrderBy()
	{
		global  $context;
		
		$mainframe = JFactory::getApplication();
		
		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'ordering' );
		//echo $filter_order;
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );
		//echo $filter_order_Dir;		
					
		//$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir.', id ';			
		$orderby 	= ' ORDER BY parent, ordering';
		 		
		return $orderby;
	}
	function publish($cid = array(),$publish = 1)
	{ 		
		if (count( $cid ))
		{
			$cids = implode( ',', $cid );
			$query = 'UPDATE '.$this->_table_prefix.'application_manager'
					  . ' SET published = ' . intval( $publish )
					  . ' WHERE  app_id IN ( '.$cids.' )';
					  //echo $query;exit;
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}
	function move($direction,$app_id,$type)
	{
		require_once(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_ijoomer'.DS.'tables'.DS.'application_manager.php');
		// Instantiate an article table object
		$row =& JTable::getInstance('plist');
		
		if (!$row->load($app_id)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		if (!$row->move( $direction, ' published > 0 and type="'.$type.'" and parent="'.$row->parent.'" ' )) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		return true;
	}
	
	function setOrder($items, $type){
		
		$total		= count( $items );
		
		require_once(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_ijoomer'.DS.'tables'.DS.'application_manager.php');
		// Instantiate an article table object
		$row =& JTable::getInstance('plist');
		
		$groupings	= array();

		$order		= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($order);

		// update ordering values
		for( $i=0; $i < $total; $i++ ) {
			$row->load( $items[$i] );
			// track parents
			$groupings[] = $row->parent;
			if ($row->ordering != $order[$i]) {
				$row->ordering = $order[$i];
				if (!$row->store()) {
					$this->setError($row->getError());
					return false;
				}
			} // if
		} // for

		// execute updateOrder for each parent group
		$groupings = array_unique( $groupings ); 
		foreach ($groupings as $group){
			$row->reorder('type = '.$this->_db->Quote($type).' AND parent = '.$this->_db->Quote($group).' AND published >0');
		}

	
		return true;
	}
	
	function getanroidlist(){
			
		$db = & JFactory::getDBO();	
		$query="select * from ".$this->_table_prefix."plist where `type`='A' and published=1 order by ordering";
		
		$data = $this->_getList($query);
		
		$rows = $data; 
		$children = array();
		foreach ($rows as $v )
		{
			
			if($v->parent==""){
				$v->parent=0;
			}else{
				$query="select id from #__ijoomer_plist where plist_value='".$v->parent."' and type='A'"; 
				$db->setQuery($query);
				$v->parent=$db->loadResult(); 
			}
			
			
			$pt = $v->parent;
			$list = @$children[$pt] ? $children[$pt] : array();
			array_push( $list, $v ); 
			$children[$pt] = $list;
		}
			$list_items=array();
			$this->tree(0,"",$children,array(),999,0);
			$list1=array();
			foreach ($this->_list as $item)
				{
					$list_items[] = $item;
				}
		$total = count( $list_items );		
		jimport('joomla.html.pagination');
		$this->_apagination = new JPagination( $total,  $this->getState('alimitstart'), $this->getState('alimit') );
		// slice out elements based on limits
		$list_items = array_slice( $list_items, $this->_apagination->limitstart, $this->_apagination->limit );	
			
		
		return $list_items;
	}
	
	function getaTotal()
	{
		
		if ($this->_apagination == null) {
			$this->getanroidlist();
		}
		return $this->_apagination;
	}
			
	function getaPagination()
	{
		
		if ($this->_apagination == null) {
			$this->getanroidlist();
		}
		return $this->_apagination;
	}
	
	function getbblist(){
		$db = & JFactory::getDBO();		
		$query="select * from ".$this->_table_prefix."plist where `type`='B' and published=1 order by ordering";
		
		$data = $this->_getList($query);
		
		$rows = $data; 
		$children = array();
		foreach ($rows as $v )
		{
			
			/*if($v->parent==""){
				$v->parent=0;
				$pt = $v->parent;
				$list = @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v ); 
				$children[$pt] = $list;	
			}else{
				$query="select id from #__ijoomer_plist where plist_value='".$v->parent."' and type='B' order by ordering"; 
				$db->setQuery($query);
				$r=$db->loadObjectlist();
				
				$tv=$v;
				for($x=0;$x<count($r);$x++){
					$temp=new stdClass();
					$temp=&$tv;
					$tv->id = $v->id;
					$tv->plist_name = $v->plist_name;
					$tv->plist_value = $v->plist_value;
					$tv->plist_title = $v->plist_title;
					$tv->description = $v->description;
					$tv->parent = $r[$x]->id;
					$tv->type = $v->type;
					$tv->published = $v->published;
					$tv->ordering = $v->ordering;
					
					$pt = $r[$x]->id;
					$list = @$children[$pt] ? $children[$pt] : array();
					array_push( $list, $temp ); 
					$children[$pt] = $list;	
            		
				} 
			
			}*/
			
			
			
			if($v->parent==""){
				$v->parent=0;
			}else{
				$query="select id from #__ijoomer_plist where plist_value='".$v->parent."' and type='B'"; 
				$db->setQuery($query);
				$v->parent=$db->loadResult(); 
			}
			
			
			$pt = $v->parent;
			$list = @$children[$pt] ? $children[$pt] : array();
			array_push( $list, $v ); 
			$children[$pt] = $list;
		
			
		} 
			$list_items=array();
			$this->tree(0,"",$children,array(),999,0);
			$list1=array(); 
			foreach ($this->_list as $item)
				{
					$list_items[] = $item;
				}
		$total = count( $list_items );		
		jimport('joomla.html.pagination');
		$this->_bpagination = new JPagination( $total,  $this->getState('blimitstart'), $this->getState('blimit') );
		// slice out elements based on limits
		$list_items = array_slice( $list_items, $this->_bpagination->limitstart, $this->_bpagination->limit );	
			
		
		return $list_items;
	}
	
	function getbTotal()
	{
		
			$query="select * from ".$this->_table_prefix."plist where `type`='B'";
			$total = $this->_getListCount($query);
		

		return $total;
	}
			
	function getbbPagination()
	{
		if ($this->_bpagination == null) {
			$this->getbblist();
		}
		return $this->_bpagination;
	}
	
	function getDetails($id){
		$query="select * from ".$this->_table_prefix."plist where id=".$id;
		$data = $this->_getList($query);
		return $data;
	}
	
	function getDeviceDetails($type,$value){
		$query="select d.id as did, d.*,i.id as display_id,i.* from ".$this->_table_prefix."devices as d LEFT JOIN ".$this->_table_prefix."display as i ON (i.device_id=d.id AND i.plist_value='".$value."') where d.type='".$type."'";
		$data = $this->_getList($query);
		return $data;
	}
	
	function getDeviceDetail($id){
		$query="select * from ".$this->_table_prefix."devices where id=".$id;
		$data = $this->_getList($query);
		return $data;
	}
	
	function save_plist($row,$device,$id){
			
		$tab = JRequest::getVar('tab', null, 'files', 'array'); 
		//$tab2 = JRequest::getVar('tab2', null, 'files', 'array');
		$list = JRequest::getVar('list', null, 'files', 'array');
		$list2 = JRequest::getVar('list2', null, 'files', 'array');
		
		$tab_chk		= JRequest::getVar( 'tab_chk',0, 'REQUEST');
		$list_chk		= JRequest::getVar( 'list_chk',0, 'REQUEST');
		
		jimport('joomla.filesystem.file');
		
		if($row[0]->type=="I"){
			$folder="iphone";
		}
		if($row[0]->type=="A"){
			$folder="android";
		}
		if($row[0]->type=="B"){
			$folder="bb";
		}
		$message=array();
		$storage = JPATH_ROOT . DS . 'images' . DS . 'com_ijoomer'. DS .$folder;
		
		if($tab["name"]!=""){ 
			$tsize = explode("x",$device[0]->tab_icon_size); 
			$tab_size = $this->getSize($tab['tmp_name']);
			$tab_name="";
			if($tab_size->width==$tsize[0] && $tab_size->height==$tsize[1]){
				$tab_type = $this->getExtension($tab['type']);
				$tab_name=$row[0]->plist_value."_".$device[0]->id."_icon".$tab_type; 
			   if (!JFile::upload($tab['tmp_name'], $storage. DS .$tab_name) ) {
					$message[]=JText::_("COM_IJOOMER_PROBLEM_UPLOAD_TAB");
					$tab_name="";
			   }
			   if(file_exists($storage. DS .$tab_name) && $tab_name!=""){
			   		chmod($storage. DS .$tab_name,0777);
			   }
			}else{
				$message[]=JText::_("COM_IJOOMER_NOMACH_TAB");
				$tab_name="";
			}
		}
		
		/*if($tab2["name"]!=""){
			$tsize = explode("x",$device[0]->tab_icon_size);
			$tab_size2 = $this->getSize($tab2['tmp_name']);
			$tab_name2="";
			if($tab_size2->width==$tsize[0] && $tab_size2->height==$tsize[1]){
				$tab_type2 = $this->getExtension($tab2['type']);
				$tab_name2=$row[0]->plist_value."_".$device[0]->id."_tab_normal".$tab_type2; 
			   if (!JFile::upload($tab2['tmp_name'], $storage. DS .$tab_name2) ) {
					$message[]=JText::_("COM_IJOOMER_PROBLEM_UPLOAD_TABNORMAL");
					$tab_name2="";
			   }
			   if(file_exists($storage. DS .$tab_name2) && $tab_name2!=""){
			   		chmod($storage. DS .$tab_name2,0777);
			   }
			}else{
				$message[]=JText::_("COM_IJOOMER_NOMACH_TABNORMAL");
				$tab_name2="";
			}
		}*/
		
		if($list["name"]!=""){
			$lsize = explode("x",$device[0]->list_icon_size);
			$list_size = $this->getSize($list['tmp_name']);
			$list_name="";
			if($list_size->width==$lsize[0] && $list_size->height==$lsize[1]){
				$list_type = $this->getExtension($list['type']);
				$list_name=$row[0]->plist_value."_".$device[0]->id."_list_focus".$list_type;
				   if (!JFile::upload($list['tmp_name'], $storage. DS .$list_name) ) {
				   		if($row[0]->type=="I"){
							$message[]=JText::_("COM_IJOOMER_PROBLEM_UPLOAD_LIST_IPHONE");
				   		}else{
				   			$message[]=JText::_("COM_IJOOMER_PROBLEM_UPLOAD_LIST");
				   		}
						$list_name="";
				   } 
			   if(file_exists($storage. DS .$list_name) && $list_name!=""){
			   		chmod($storage. DS .$list_name,0777);
			   }
			}else{
				if($row[0]->type=="I"){
					$message[]=JText::_("COM_IJOOMER_NOMACH_LIST_IPHONE");
				}else{
					$message[]=JText::_("COM_IJOOMER_NOMACH_LIST");
				}
				$list_name="";
			}
		}
		
		if($list2["name"]!=""){ 
			$lsize = explode("x",$device[0]->list_icon_size);
			$list_size2 = $this->getSize($list2['tmp_name']); 
			$list_name2="";
			if($list_size2->width==$lsize[0] && $list_size2->height==$lsize[1]){
				$list_type2 = $this->getExtension($list2['type']);
				$list_name2=$row[0]->plist_value."_".$device[0]->id."_list_normal".$list_type2;
				   if (!JFile::upload($list2['tmp_name'], $storage. DS .$list_name2) ) {
						$message[]=JText::_("COM_IJOOMER_PROBLEM_UPLOAD_LISTNORMAL");
						$list_name2="";
				   }
				     
			   if(file_exists($storage. DS .$list_name2) && $list_name2!=""){
			   		chmod($storage. DS .$list_name2,0777);
			   }
			}else{
				$message[]=JText::_("COM_IJOOMER_NOMACH_LISTNORMAL");
				$list_name="";
			}
		}
		
		
		
		require_once(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_ijoomer'.DS.'tables'.DS.'display.php');
		$row1 =& JTable::getInstance('display');
		
		$post["id"]=$id;
		$post["device_id"]=$device[0]->id;
		$post["plist_value"]=$row[0]->plist_value;
		if($tab_name!=""){
			$post["tab_icon"]=$tab_name;
		}
		if($tab_name2!=""){
			$post["tab_icon2"]=$tab_name2;
		}
		$post["show_tab"]=$tab_chk;
		if($list_name!=""){
			$post["list_icon"]=$list_name;
		}
		if($list_name2!=""){
			$post["list_icon2"]=$list_name2;
		}
		$post["show_list"]=$list_chk;
		
		if (!$row1->bind($post)) {
			return JError::raiseWarning( 500, $row1->getError() );
		}
		
		if (!$row1->store()) {
			JError::raiseError(500, $row1->getError() );
		}
		
		
		
		$msg="";
		if(count($message)){
			
			//$msg=implode(", ",$message).".";
			for($i=0;$i<count($message);$i++){
				$msg.="<li>".$message[$i]."</li>";
			}
				
		}
		
		global $mainframe;
		$mainframe =& JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_ijoomer&view=application_manager&task=edit&cid[]=".$row[0]->id, $msg, $msgType='message');
		
	}
	
	function parentorder(){
		global $mainframe;
		$mainframe =& JFactory::getApplication();
		$pid = JRequest::getVar( 'pid',0, 'REQUEST');
		$ordering = JRequest::getVar('ordering',0,'REQUEST');
		require_once(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_ijoomer'.DS.'tables'.DS.'plist.php');
		$row =& JTable::getInstance('plist');
		
		if (!$row->load($pid)) {
    		return JError::raiseWarning( 500, $row->getError() );
		}
		$row->ordering=$ordering;
		if (!$row->store())
		{
			return JError::raiseWarning( 500, $row->getError() );
		}
		
		$row->reorder('type = '.$this->_db->Quote($row->type).' AND parent = '.$this->_db->Quote($row->parent).' AND published >0');
			
		$msg="Order saved.";
		
		$mainframe->redirect("index.php?option=com_ijoomer&view=application_manager&task=edit&cid[]=".$pid, $msg, $msgType='message');
	}
	
	function getSize( $source )
	{
		$obj		= new stdClass();
		list( $obj->width , $obj->height) = getimagesize( $source );
		return $obj;
	}
	
	 function getExtension( $type )
	{
		$type = JString::strtolower($type);
	
		if( $type == 'image/png' || $type == 'image/x-png' )
		{
			return '.png';
		}
		elseif ( $type == 'image/gif')
		{
			return '.gif';
		}
		
		// We default to use jpeg
		return '.jpg';
	}
		
	function ordering(&$row, $id) 
	{ 
        $db = &JFactory::getDbo();
 
        if ($id)
        {
                $query = 'SELECT ordering AS value, plist_name AS text'
                . ' FROM #__ijoomer_plist'
                . ' WHERE type = '.$db->Quote($row->type)
                . ' AND parent = '.$db->Quote($row->parent)
                . ' AND published > 0'
                . ' ORDER BY ordering';
                $order = JHtml::_('list.genericordering',  $query);
                $ordering=JHTML::_('select.genericlist', $order, 'ordering', 'class="inputbox"', 'value', 'text', $row->ordering);
                
      /*          $ordering = JHtml::_(
                        'select.genericlist',
                        $order,
                        'ordering',
                        array('list.attr' => 'class="inputbox" size="1"', 'list.select' => intval($row->ordering))
                );*/
        }
        else
        {
                $ordering = '<input type="hidden" name="ordering" value="'. $row->ordering .'" />'. JText::_('JCOMMON_NEWITEMSLAST_DESC');
        }
        return $ordering;
	}
	
	
	
}
?>
