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

jimport('joomla.application.component.controller');
 
class application_managerController extends JController
{
	function __construct( $default = array())
	{
		parent::__construct( $default );
		$direction = JRequest::getCmd('task');		
	}
	
	function display() 
	{
		parent::display();
	}
	function cancel()
	{
		$this->setRedirect( 'index.php?option=com_ijoomer' );
	}
	function publish() 
	{	
		global $option;//echo "<pre>";print_r($option);
		$option = JRequest::getVar ('option');
		$post = JRequest::get('post');
		$view = $post['view'];
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		print_r($cid);
		if (! is_array ( $cid ) || count ( $cid ) < 1) 
		{
			
			JError::raiseError ( 500, JText::_ ( 'SELECT_PLUGIN_TO_PUBLISH' ) );
		}
		
		$model = $this->getModel ( 'application_manager' );
		if (!$model->publish ( $cid, 1 )) 
		{
			
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		
		$this->setRedirect ( 'index.php?option='.$option.'&view='.$view );
	}
	function unpublish() 
	{
		
		global $option;
		$option = JRequest::getVar ('option');
		$post = JRequest::get('post');
		$view = $post['view'];
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT_PLUGIN_TO_UNPUBLISH' ) );
		}
		
		$model = $this->getModel ( 'application_manager' );
		if (! $model->publish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		
		$this->setRedirect ( 'index.php?option='.$option.'&view='.$view );
	}
	
	
	function saveOrder()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$type		= JRequest::getVar( 'type', 'I', 'post' );
		JArrayHelper::toInteger($cid);

		$model =& $this->getModel( 'application_manager' );
		if ($model->setOrder($cid,$type)) {
			$msg = JText::_( 'New ordering saved' );
		} else {
			$msg = $model->getError();
		}
		
		$task="";
		if($type=="A"){
			$task="&task=anroid";	
		}
		if($type=="B"){
			$task="&task=bb";	
		}
		
		$this->setRedirect('index.php?option=com_ijoomer&view=application_manager'.$task, $msg);	
		
	}
	
	
	function saveOrder123()
	{
		
		//global $mainframe;
		$mainframe = JFactory::getApplication();
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		// Initialize variables
		$db			= & JFactory::getDBO();

		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$type		= JRequest::getVar( 'type', 'I', 'post' );
		$order		= JRequest::getVar( 'order', array (0), 'post', 'array' );
		$redirect	= JRequest::getVar( 'redirect', 0, 'post', 'int' );
		$total		= count($cid);
		$conditions	= array ();

		JArrayHelper::toInteger($cid, array(0));
		JArrayHelper::toInteger($order, array(0));
		
		require_once(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_ijoomer'.DS.'tables'.DS.'plist.php');
		
		$row =& JTable::getInstance('plist');   
		
		// Update the ordering for items in the cid array
		for ($i = 0; $i < $total; $i ++)
		{ 
			$row->load((int)$cid[$i] ); 
			if ($row->ordering != $order[$i]) {
				$row->ordering = $order[$i];
				
				if (!$row->store()) {
					JError::raiseError( 500, $db->getErrorMsg() );
					return false;
				}
				// remember to updateOrder this group
				$condition[] = $row->parent;
				$found = false;
				foreach ($conditions as $cond)
					if ($cond[1] == $condition) {
						$found = true;
						break;
					}
				if (!$found)
					$conditions[] = array ($row->id, $condition);
			}
		}
		
		// execute updateOrder for each group
		
		$x="type ='".$type."'";
		foreach ($conditions as $cond)
		//	$row->load($cond[0]);
		{
			$row->reorder($x." and parent='".$cond."' and published>0");
		}

		$cache = & JFactory::getCache('com_ijoomer');
		$cache->clean();

		$msg = JText::_('New ordering saved');
		
		$task="";
		if($type=="A"){
			$task="&task=anroid";	
		}
		if($type=="B"){
			$task="&task=bb";	
		}
		
		$mainframe->redirect('index.php?option=com_ijoomer&view=application_manager'.$task, $msg);	
		
	}
	
	
	function orderup()
	{  	$array = JRequest::getVar('cid', array(0), '', 'array');
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$type		= JRequest::getVar( 'type', 'I', 'post' );
		
		$model = $this->getModel('application_manager');
		$model->move(-1,(int)$array[0],$type);
		
		$task="";
		if($type=="A"){
			$task="&task=anroid";	
		}
		if($type=="B"){
			$task="&task=bb";	
		}

		$this->setRedirect( 'index.php?option=com_ijoomer&view=application_manager'.$task);
	}

	function orderdown()
	{	$array = JRequest::getVar('cid', array(0), '', 'array');
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$type		= JRequest::getVar( 'type', 'I', 'post' );

		$model = $this->getModel('application_manager');
		$model->move(1,(int)$array[0],$type);
		
		$task="";
		if($type=="A"){
			$task="&task=anroid";	
		}
		if($type=="B"){
			$task="&task=bb";	
		}

		$this->setRedirect( 'index.php?option=com_ijoomer&view=application_manager'.$task);
	}
	
	function anroid(){
		
		$model = $this->getModel('application_manager','application_managerModel');
		$rows = $model->getanroidlist();
		$pagination = $model->getaPagination();
		
		$view = $this->getView('application_manager','html');
		$view->setLayout('anroid');
		$view->showanroid($rows,$pagination);		 
	}
	
	function bb(){
		
		$model = $this->getModel('application_manager','application_managerModel');
		$rows = $model->getbblist();
		$pagination = $model->getbbPagination();
		
		$view = $this->getView('application_manager','html');
		$view->setLayout('bb');
		$view->showbb($rows,$pagination);		 
	}
	
	function edit(){
		$cid		= JRequest::getVar( 'cid', array(0), 'REQUEST', 'array' );
		$id = $cid[0];
		
		$model = $this->getModel('application_manager','application_managerModel');
		$row = $model->getDetails($id);
		$devices = $model->getDeviceDetails($row[0]->type,$row[0]->plist_value);
		$ordering = $model->ordering($row[0],$row[0]->id);
		$view = $this->getView('application_manager','html');
		$view->setLayout('edit');
		$view->edit_form($row,$devices,$ordering);
	}
	
	function parentorder(){
			$model = $this->getModel('application_manager','application_managerModel');
			$model->parentorder();
	}
	
	function save_display(){
		$did		= JRequest::getVar( 'did',0, 'REQUEST');
		$pid		= JRequest::getVar( 'pid',0, 'REQUEST');
		$id		= JRequest::getVar( 'id',0, 'REQUEST');
					
		if($pid > 0 && $did > 0){
			$model = $this->getModel('application_manager','application_managerModel');
			$row = $model->getDetails($pid);
			$device = $model->getDeviceDetail($did);
			$model->save_plist($row,$device,$id);
		}
	}
	
	function cancel2(){ 
		$type		= JRequest::getVar( 'type',"I", 'REQUEST');
		$task="";
		if($type=="A"){
			$task="&task=anroid";
		}
		if($type=="B"){
			$task="&task=bb";
		}
		$uri="index.php?option=com_ijoomer&view=application_manager".$task;
		$this->setRedirect($uri);
	}
	
}
	
?>