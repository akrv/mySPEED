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
 
class app_module_detailController extends JController
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
		$this->setRedirect( 'index.php?option=com_ijoomer&view=app_module' );
	}
	function edit() {
		JRequest::setVar ( 'view', 'app_module_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		parent::display ();
	
	}
	function save()
	{
		
		$post = JRequest::get ( 'post' );
		//echo "<pre>";print_r($post);
		$task = JRequest::getVar ( 'task');
				
		$option = JRequest::getVar ('option');
		
		$model = $this->getModel ( 'app_module_detail' );
		 
		if ($model->store ( $post )) {
			
			$msg = JText::_ ( 'CONFIG_SAVED' );
		
		} else {
			
			$msg = JText::_ ( 'ERROR_SAVING_CONFIG' );
		}
		if($task=='apply')
			$this->setRedirect ( 'index.php?option=' . $option . '&view=app_module_detail&task=edit&cid[]='.$post['id'], $msg );
		else
			$this->setRedirect ( 'index.php?option=' . $option . '&view=app_module', $msg );
	}
	function apply()
	{
		$this->save();	
	}
}
	
?>