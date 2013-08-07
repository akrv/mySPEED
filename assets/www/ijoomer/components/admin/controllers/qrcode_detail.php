<?php

/**
 * @package joomadvertisement
 * @version 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * Joomla! is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );
//DEVNOTE: import CONTROLLER object class
jimport ( 'joomla.application.component.controller' );
/**
 * joomadvertisement_detail  Controller
 *
 * @package		Joomla
 * @subpackage	joomadvertisement
 * @since 1.5
 */
class qrcode_detailController extends JController {
	/**
	 * Custom Constructor
	 */
	function __construct($default = array()) {
		parent::__construct ( $default );
		
		// Register Extra tasks
		$this->registerTask ( 'add', 'edit' );
	
	}
	
	/** function edit
	 *
	 * Create a new item or edit existing item 
	 * 
	 * 1) set a custom VIEW layout to 'form'  
	 * so expecting path is : [componentpath]/views/[$controller->_name]/'form.php';			
	 * 2) show the view
	 * 3) get(create) MODEL and checkout item
	 */
	function edit() {
		JRequest::setVar ( 'view', 'qrcode_detail' );
		JRequest::setVar ( 'layout', 'form' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		
		parent::display ();
		
		// give me  the joomadvertisement
		$model = $this->getModel ( 'qrcode_detail' );
		$model->checkout ();
	}
	
	/** function save
	 *
	 * Save the selected item specified by id
	 * and set Redirection to the list of items	
	 * 		
	 * @param int id - keyvalue of the item
	 * @return set Redirection
	 */
	function save() {
		$post = JRequest::get ( 'post' );
		jimport ( 'joomla.filesystem.file' );
		$filename = $random = time () . '.jpg';
		$src = $post ['output_img_qr_src'];
		$dest = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ijoomer' . DS . 'qrimages' . DS . $filename;
		copy ( $src, $dest );
		/*if (!copy ( $src, $dest ))
		{
			$dest = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ijoomer' . DS . 'qrimages/';
			chmod($dest,777);
			$filename = $random = time () . '.jpg';
			$src = $post ['output_img_qr_src'];
			$dest = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ijoomer' . DS . 'qrimages' . DS . $filename;
			copy ( $src, $dest );	
		}*/
		$post ['qr_code_image'] = $filename;
		$post ['published'] = 1;
		
		$db = JFactory::getDBO ();
		$query = "insert into #__ijoomer_qrcode_detail(`url`,`qrcode_image`,`published`) value('" . trim ( $post ['link_url'] ) . "','" . $post ['qr_code_image'] . "','1')";
		$db->setQuery ( $query );
		$db->Query ();
		if ($db->getErrorMsg ()) {
			$msg = JText::_ ( 'ERROR_IMAGE' );
		} else {
			$msg = JText::_ ( 'SAVED_IMAGE' );
		}
		
		/*$model = $this->getModel('qrcode_detail');
	
		if ($model->store($post)) {
			
		} else {
			
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();*/
		
		$this->setRedirect ( 'index.php?option=com_ijoomer&view=qrcode', $msg );
	}
	
	/** function remove
	 *
	 * Delete all items specified by array cid
	 * and set Redirection to the list of items	
	 * 		
	 * @param array cid - array of id
	 * @return set Redirection
	 */
	function remove() {
		global $mainframe;
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'Select an item to delete' ) );
		}
		
		$model = $this->getModel ( 'qrcode_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		
		$this->setRedirect ( 'index.php?option=com_ijoomer&view=qrcode' );
	}
	
	/** function publish
	 *
	 * Publish all items specified by array cid
	 * and set Redirection to the list of items	
	 * 		
	 * @param array cid - array of id
	 * @return set Redirection
	 */
	function publish() {
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'Select an item to publish' ) );
		}
		
		$model = $this->getModel ( 'qrcode_detail' );
		if (! $model->publish ( $cid, 1 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		
		$this->setRedirect ( 'index.php?option=com_ijoomer&view=qrcode' );
	}
	
	/** function unpublish
	 *
	 * Unpublish all items specified by array cid
	 * and set Redirection to the list of items
	 * 			
	 * @param array cid - array of id
	 * @return set Redirection
	 */
	function unpublish() {
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'Select an item to unpublish' ) );
		}
		
		$model = $this->getModel ( 'qrcode_detail' );
		if (! $model->publish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		
		$this->setRedirect ( 'index.php?option=com_ijoomer&view=qrcode' );
	}
	
	/** function cancel
	 *
	 * Check in the selected detail 
	 * and set Redirection to the list of items	
	 * 		
	 * @return set Redirection
	 */
	function cancel() {
		// Checkin the detail
		$model = $this->getModel ( 'qrcode_detail' );
		$model->checkin ();
		$this->setRedirect ( 'index.php?option=com_ijoomer&view=qrcode' );
	}
	
	function download() 
	{
		$file_name_original = JRequest::getVar ( 'src', '', 'Request' );
		$file_name = JURI::base () . "components/com_ijoomer/qrimages/" . $file_name_original;
		
		header ( 'Content-disposition: attachment; filename=' . $file_name_original );
		header ( 'Content-type:image/jpg' );
		readfile ( $file_name );
		exit();
	}
}
