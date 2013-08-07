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

defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.application.component.controller' );
 
define('WARNSAME',"There is already a file called '%s'.");
define('INSTALLEXT','Install %s %s');
class plugin_detailController extends JController {
	function __construct($default = array()) {
		parent::__construct ( $default );
	}
	
	function install(){
	
		$model = $this->getModel ( 'plugin_detail' );
		
		$model->install();
		
		
		JRequest::setVar ( 'view', 'plugin_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		parent::display ();
	}
	function add(){ 
		$this->edit();
	}
	
	function edit() {
		JRequest::setVar ( 'view', 'plugin_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		parent::display ();
	
	}
	function apply() {
		$this->save();
	}
	function save() {
		$post = JRequest::get ( 'post' );
		$task = JRequest::getVar ( 'task');
				
		$option = JRequest::getVar ('option');
		
		

		$model = $this->getModel ( 'plugin_detail' );
			 	
		if ($model->store ( $post )) {
			
			$msg = JText::_ ( 'CONFIG_SAVED' );
		
		} else {
			
			$msg = JText::_ ( 'ERROR_SAVING_CONFIG' );
		}
		if($task=='apply')
			$this->setRedirect ( 'index.php?option=' . $option . '&view=plugin_detail&task=edit&cid[]='.$post['plugin_id'], $msg );
		else
			$this->setRedirect ( 'index.php?option=' . $option . '&view=plugins', $msg );
	}
	function remove() {
		
		$option = JRequest::getVar ('option');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		$model = $this->getModel ( 'plugin_detail' );
		
		$model->uninstall($cid);
		
		$this->setRedirect ( 'index.php?option='.$option.'&view=plugins' );
	}
	function publish() {
		
		$option = JRequest::getVar ('option');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT_PLUGIN_TO_PUBLISH' ) );
		}
		
		$model = $this->getModel ( 'plugin_detail' );
		if (! $model->publish ( $cid, 1 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		
		$this->setRedirect ( 'index.php?option='.$option.'&view=plugins' );
	}
	function unpublish() {
		
		$option = JRequest::getVar ('option');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT_PLUGIN_TO_UNPUBLISH' ) );
		}
		
		$model = $this->getModel ( 'plugin_detail' );
		if (! $model->publish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		
		$this->setRedirect ( 'index.php?option='.$option.'&view=plugins' );
	}
	function cancel() {
		
		$option = JRequest::getVar ('option');
		
		$this->setRedirect ( 'index.php?option='.$option.'&view=plugins' );
	} 

}
