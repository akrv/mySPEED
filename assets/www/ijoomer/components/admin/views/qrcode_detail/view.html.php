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
defined( '_JEXEC' ) or die( 'Restricted access' );

//DEVNOTE: import VIEW object class
jimport( 'joomla.application.component.view' );


/**
 [controller]View[controller]
 */
class qrcode_detailVIEWqrcode_detail extends JView
{
	/**
	 * Display the view
	 */
	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();
		global $option;	
		
    	//DEVNOTE: Set ToolBar title
    	JToolBarHelper::title(   JText::_( 'QR_CODE_MANAGER_DETAIL' ), 'generic.png' );

		//DEVNOTE: Get URL, User,Model
		$uri 		=& JFactory::getURI();
		$user 	=& JFactory::getUser();
		$model	=& $this->getModel();

		$this->setLayout('form');
		$lists = array();

		jimport('joomla.version');
		$version = new JVersion();
		if($version->RELEASE == 1.6  || $version->RELEASE == 1.7 )
		{	
			$vName ='qrcode';                
			JSubMenuHelper::addEntry( JText::_('PLUGINS'), 'index.php?option=com_ijoomer&view=plugins', $vName == 'plugins' );
			JSubMenuHelper::addEntry( JText::_('GLOBAL_CONFIGURATION'), 'index.php?option=com_ijoomer&view=config', $vName == 'config' );
			JSubMenuHelper::addEntry( JText::_('APPLICATION_SCREEN_MANAGER'), 'index.php?option=com_ijoomer&view=application_manager', $vName == 'application_manager' );
			JSubMenuHelper::addEntry( JText::_('APPLICATION_MODULES'), 'index.php?option=com_ijoomer&view=app_module', $vName == 'app_module' );
			JSubMenuHelper::addEntry( JText::_('QR_CODE'), 'index.php?option=com_ijoomer&view=qrcode', $vName == 'qrcode' );
		}
		//get the joomadvertisement
		$detail	=& $this->get('data');
		
		//DEVNOTE: the new record ?  Edit or Create?
		$isNew		= ($detail->id < 1);

		// fail if checked out not by 'me'
		if ($model->isCheckedOut( $user->get('id') )) {
			$msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_( 'THE DETAIL' ), $detail->title );
			$mainframe->redirect( 'index.php?option='. $option, $msg );
		}
		
		
		
		// Set toolbar items for the page
		$text = $isNew ? JText::_( 'NEW' ) : JText::_( 'EDIT' );
		JToolBarHelper::title(   JText::_( 'QR_CODE' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		

		// Edit or Create?
		if (!$isNew)
		{}
		else
		{
			// initialise new record
			$detail->published = 1;
		}
		
		
		
		// build the html select list
		$lists['published'] 		= JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $detail->published );

		//clean joomadvertisement data
		jimport('joomla.filter.filteroutput');	
		JFilterOutput::objectHTMLSafe( $detail, ENT_QUOTES, 'description' );
		
		
		$this->assignRef('lists',			$lists);
		$this->assignRef('detail',		$detail);
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}
 }

?>
