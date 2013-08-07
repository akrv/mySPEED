<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

//echo "out view"; exit;
class app_push_notification_detailViewapp_push_notification_detail extends JView
{
	
	//---------------- Display function of the ADD, EDIT --------------------//
	function display($tpl = null)
	{
		$items=& $this->get('Data');
		//echo "<pre/>"; print_r($items); exit;
		$uri = & JFactory::getURI();
			
		$isNew	= ($items->id < 1);

		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );

		JToolBarHelper::title(   JText::_( 'Push Notification' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::save();
		
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		
		
		$this->assignRef('items', $items);
		$this->assignRef('request_url',	$uri->toString());
		
		parent::display($tpl);
	}
}