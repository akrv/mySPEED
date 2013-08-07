<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');
 
class app_push_notification_detailController extends JController
{
	function __construct()
	{
		
		parent::__construct();
		//echo "exit cont";exit;
		// Register Extra tasks
		$this->registerTask( 'add', 'edit' );
	}	
		
	function edit() 
	{
		JRequest::setVar ( 'view', 'app_push_notification_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		
		parent::display ();
	
	}
	function save()
	{
		
		$model = $this->getModel('app_push_notification_detail');
		if ($model->store()) 
		{
			$msg = JText::_( 'Record Saved!' );
		} else {
			
			$msg = JText::_( 'Error Saving Records' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_ijoomer&view=app_push_notification';
		$this->setRedirect($link, $msg);
	}
	
	function apply()
	{
		$this->save();	
	}
	
	/*function display_user()
	{
		//$model = $this->getModel('app_push_notification_detail');
		//$model->display_user();
		JRequest::setVar ( 'view', 'app_push_notification_detail' );
		JRequest::setVar ( 'layout', 'autosuggest' );
		parent::display ();
	}*/
// ================================ Send Push Notification ===============================
	
	function send_push_notification()
	{
		$model = $this->getModel('app_push_notification_detail');
		
		$model->send_push_notification($device_token, $message='',$badge = 1,$type='');
		parent::display();
	}
	
// ================================  cancel Functionality ================================	
	function cancel()
	{
		$this->setRedirect( 'index.php?option=com_ijoomer&view=app_push_notification' );
	}
//=================================================================================================
}	
?>