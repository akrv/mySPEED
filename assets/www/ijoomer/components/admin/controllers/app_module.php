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
 
class app_moduleController extends JController
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
		
		global $option;
		$option = JRequest::getVar ('option');
		$post = JRequest::get('post');
		$view = $post['view'];
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) 
		{
			
			JError::raiseError ( 500, JText::_ ( 'SELECT_MODULE_TO_PUBLISH' ) );
		}
		
		$model = $this->getModel ( 'app_module' );
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
			JError::raiseError ( 500, JText::_ ( 'SELECT_MODULE_TO_UNPUBLISH' ) );
		}
		
		$model = $this->getModel ( 'app_module' );
		if (! $model->publish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		
		$this->setRedirect ( 'index.php?option='.$option.'&view='.$view );
	}
	function saveOrder()
	{
		
		//global $mainframe;
		$mainframe = JFactory::getApplication();
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		// Initialize variables
		$db			= & JFactory::getDBO();

		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$order		= JRequest::getVar( 'order', array (0), 'post', 'array' );
		$redirect	= JRequest::getVar( 'redirect', 0, 'post', 'int' );
		//$rettask	= JRequest::getVar( 'returntask', '', 'post', 'cmd' );
		$total		= count($cid);
		$conditions	= array ();

		JArrayHelper::toInteger($cid, array(0));
		JArrayHelper::toInteger($order, array(0));
		
		require_once(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_ijoomer'.DS.'tables'.DS.'app_module.php');
		
		$row =& JTable::getInstance('app_module');   
		
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
				$condition = 'id = '.(int) $row->catid.'';
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
		foreach ($conditions as $cond)
			$row->load($cond[0]);
		{
			$row->reorder($cond[1]);
		}

		$cache = & JFactory::getCache('com_ijoomer');
		$cache->clean();

		$msg = JText::_('New ordering saved');
		
		$mainframe->redirect('index.php?option=com_ijoomer&view=app_module', $msg);	
		
	}

	function orderup()
	{  	$array = JRequest::getVar('cid', array(0), '', 'array');
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$model = $this->getModel('app_module');
		$model->move(-1,(int)$array[0]);

		$this->setRedirect( 'index.php?option=com_ijoomer&view=app_module');
	}

	function orderdown()
	{	$array = JRequest::getVar('cid', array(0), '', 'array');
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$model = $this->getModel('app_module');
		$model->move(1,(int)$array[0]);

		$this->setRedirect( 'index.php?option=com_ijoomer&view=app_module');
	}
	
}
	
?>