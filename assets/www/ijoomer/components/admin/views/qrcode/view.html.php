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
 
class qrcodeViewqrcode extends JView
{
	/**
	 * Custom Constructor
	 */
	function __construct( $config = array())
	{
	 /** set up global variable for sorting etc.
	  * $context is used in VIEW abd in MODEL
	  **/	  
	 
 	 global $context;
	 $context = 'qrcode.list.';
 
 	 parent::__construct( $config );
	}
 
	/**
	 * Display the view
	 * take data from MODEL and put them into	
	 * reference variables
	 * 
	 * Go to MODEL, execute Method getData and
	 * result save into reference variable $items	 	 	 
	 * $items		= & $this->get( 'Data');
	 * - getData gets the country list from DB	 
	 *	  
	 * variable filter_order specifies what is the order by column
	 * variable filter_order_Dir sepcifies if the ordering is [ascending,descending]	 	 	 	  
	 */
    
	function display($tpl = null)
	{
	 	//DEVNOTE: we need these 2 globals
	 	$mainframe = JFactory::getApplication();			 
    	global $context;
		
		//DEVNOTE: set document title
		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('QR_CODE') );
   
   
    	//DEVNOTE: Set ToolBar title
    	JToolBarHelper::title(   JText::_( 'QR_CODE_MANAGER' ), 'generic.png' );
    
   	 	//DEVNOTE: Set toolbar items for the page
 		JToolBarHelper::addNewX();
 		//JToolBarHelper::editListX();		
		JToolBarHelper::deleteList();
		//JToolBarHelper::publishList();
		//JToolBarHelper::unpublishList();
		//DEVNOTE :preferences, $height='150', $width='570', $alt = 'Preferences', $path = '')

		//JToolBarHelper::preferences('com_joomadvertisement', '250');		
		//JToolBarHelper::help( 'screen.joomadvertisement.edit' );  

		jimport('joomla.version');
		$version = new JVersion();
		if($version->RELEASE > 1.5)
		{	
			$vName ='plugins';                
			JSubMenuHelper::addEntry( JText::_('PLUGINS'), 'index.php?option=com_ijoomer&view=plugins', $vName == 'plugins' );
			JSubMenuHelper::addEntry( JText::_('GLOBAL_CONFIGURATION'), 'index.php?option=com_ijoomer&view=config', $vName == 'config' );
			JSubMenuHelper::addEntry( JText::_('APPLICATION_SCREEN_MANAGER'), 'index.php?option=com_ijoomer&view=application_manager', $vName == 'application_manager' );
			JSubMenuHelper::addEntry( JText::_('APPLICATION_MODULES'), 'index.php?option=com_ijoomer&view=app_module', $vName == 'app_module' );
			JSubMenuHelper::addEntry( JText::_('QR_CODE'), 'index.php?option=com_ijoomer&view=qrcode', $vName == 'qrcode' );
			JSubMenuHelper::addEntry( JText::_('PUSH_NOTIFICATION'), 'index.php?option=com_ijoomer&view=app_push_notification', $vName == 'app_push_notification' );
		}
		
    	//DEVNOTE: Get URL
		$uri	=& JFactory::getURI();
		
		//DEVNOTE:give me ordering from request
		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'ordering' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );		
	
		
		//DEVNOTE:remember the actual order and column  
	  	$lists['order'] 	= $filter_order;  
		$lists['order_Dir'] = $filter_order_Dir;

  	
		//DEVNOTE:Get data from the model
		$items			= & $this->get( 'Data');
		$total			= & $this->get( 'Total');
		$pagination = & $this->get( 'Pagination' );
		
    	//DEVNOTE:save a reference into view	
   	 	$this->assignRef('user',		JFactory::getUser());	
    	$this->assignRef('lists',		$lists);    
  		$this->assignRef('items',		$items); 		
    	$this->assignRef('pagination',	$pagination);
    	$this->assignRef('request_url',	$uri->toString());

		//DEVNOTE:call parent display
    parent::display($tpl);
  }
}
?>
