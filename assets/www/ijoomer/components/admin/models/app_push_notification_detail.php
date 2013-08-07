<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');

class app_push_notification_detailModelapp_push_notification_detail extends JModel
{
	var $_data = null;
	var $_table_prefix = null;
	var $_table = null;

	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
		global $context; 
		$mainframe = JFactory::getApplication();
		$context='id';
	  	$this->_table_prefix = '#__ijoomer_';
	}
	
	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
		
	}
//--------------------------- START Load The Data from database -------------------------
	function &getData()
	{
		// Load the data
		if (empty( $this->_data )) {
			
			$query  = " SELECT push.id, push.device_type, push.send_to_user,push.send_to_all, push.notification_text "; 
			$query .= " FROM ".$this->_table_prefix."push_notification as push WHERE push.id= '".$this->_id."' ";
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->device_type = null;
			$this->_data->send_to_user = null;
			$this->_data->send_to_all  = null;
			$this->_data->notification_text = null;
		}
		return $this->_data;
	}
	
//--------------------------- START of SAVE button Functionality -------------------------	
	function store()
	{
		//$android_device = "APA91bHopXa-Wj0u3PXvAizTrncwg40gir4yE_RaNDPt_2ugpJ7g_QcZum3zI_4VP2FMMB4hPiK6--ZR3_gapm9AjQ7HKnt04oRmYTXNNd9G7Evm1BlAs8_ORIwVqVALXOQMl2V2kDvu3HazwCNuLSUCru7ryGN7Cg";
		//$iphone_device = "2bc3122dcf32d1b8cec408c5478d54fdd3b2b90ccf3b482b174b7a6b9299e96a";
		//echo $count_device = strlen($iphone_device); exit;  //iphone =64 and android=
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ijoomer'.DS.'tables');
		$row = JTable::getInstance('app_push_notification');
		$data = JRequest::get('post');
		
		// Add 2-1-2011  
		$value_cfg = $this->getConfig();
		//echo "<pre/>"; print_r($value_cfg); 
		//echo "<br/>";
		if (array_key_exists('PNC_ENABLE', $value_cfg)) 
		{
    		//echo $value_cfg['PNC_ENABLE'];
    		if($value_cfg['PNC_ENABLE'] == 1)
    		{
		    		if($data['send_to_all'] == 1)
					{
						$query_alluser = " SELECT device_token from #__users order by id ";
						$this->_db->setQuery($query_alluser);
						$res_alluser = $this->_db->loadobjectList();
						if($res_alluser)
						{
							if($data['device_type'] == 'iphone')
							{		
									for($l=0; $l<count($res_alluser); $l++)
									{
										//echo "check config"; exit;
										//echo $res_alluser[$l]->device_token;exit;
										$this->send_push_notification($res_alluser[$l]->device_token, $data['notification_text'], $badge = 1,$type='');
									}
							} else if($data['device_type'] == 'android')
							{
								for($a=0; $a<count($res_alluser); $a++)
								{
									//PNC_ANDROID_USERNAME, PNC_ANDROID_PASSWORD
									if (array_key_exists('PNC_ANDROID_USERNAME', $value_cfg)) 
									{
										if(!empty($value_cfg['PNC_ANDROID_USERNAME']) && !empty($value_cfg['PNC_ANDROID_PASSWORD']))
										{
											$authcode = $this->googleAuthenticate($value_cfg['PNC_ANDROID_USERNAME'], $value_cfg['PNC_ANDROID_PASSWORD'],  $service="ac2dm");
											//$authcode = $this->googleAuthenticate($value_cfg['PNC_ANDROID_USERNAME'], $value_cfg['PNC_ANDROID_PASSWORD'], $source="imart", $service="ac2dm");
											//$this->sendMessageToPhone($authCode, $deviceRegistrationId, $msgType, $messageText)
											$this->sendMessageToPhone($authCode, $res_alluser[$l]->device_token, $msgType=0, $data['notification_text']);
											
										}
									}		
								}
							}
						}
				}
    		} 
		} 
		
		
		/*if($data['send_to_all'] == 1)
		{
			$query_alluser = " SELECT device_token from #__users order by id ";
			$this->_db->setQuery($query_alluser);
			$res_alluser = $this->_db->loadobjectList();
			if($res_alluser)
			{
				if($data['device_type'] == 'iphone')
				{		
						for($l=0; $l<count($res_alluser); $l++)
						{
							//echo $res_alluser[$l]->device_token;exit;
							$this->send_push_notification($res_alluser[$l]->device_token, $data['notification_text'], $badge = 1,$type='');
						}
				}
			}
		}*/
		
		if($data['send_to_user'])
		{
			$explode_sendtouser = explode(", ",$data['send_to_user']);
			
			$sendtouser = null;
			for($i=0; $i<count($explode_sendtouser); $i++)
			{
				$query_uid = " SELECT id from #__users WHERE username= '".$explode_sendtouser[$i]."' ";
				$this->_db->setQuery($query_uid);
				$res_uid = $this->_db->loadResult();
				if($res_uid)
				{	//echo $sendtouser;  
					if(empty($sendtouser))
					{
						$sendtouser[$i] = $res_uid;
					} else {
						$sendtouser[$i] = ", ".$res_uid;
					}
				}
				
			}
			$comma_separated = implode("", $sendtouser);
			
			// Used for send push notification
			$push_device = null;
			if (array_key_exists('PNC_ENABLE', $value_cfg)) 
			{ 
				if($value_cfg['PNC_ENABLE'] == 1)
    			{
					for($j=0; $j<count($explode_sendtouser); $j++)
					{
						$query_device = " SELECT device_token from #__users WHERE username= '".$explode_sendtouser[$j]."' ";
						$this->_db->setQuery($query_device);
						$res_device = $this->_db->loadResult();
						if($res_device)
						{
							if($data['device_type'] == 'iphone')
							{
								
								//$this->send_push_notification('2bc3122dcf32d1b8cec408c5478d54fdd3b2b90ccf3b482b174b7a6b9299e96a', $data['notification_text'],$badge = 1,$type='');
								$this->send_push_notification($res_device, $data['notification_text'], $badge = 1,$type='');
							} else if($data['device_type'] == 'android') 
							{
								$authcode = $this->googleAuthenticate($value_cfg['PNC_ANDROID_USERNAME'], $value_cfg['PNC_ANDROID_PASSWORD'], $service="ac2dm");
								//$this->sendMessageToPhone($authCode, $deviceRegistrationId, $msgType, $messageText)
								//echo $authcode; exit;
								$android_response = $this->sendMessageToPhone($authcode, $res_device, $msgType="0", $data['notification_text'],$whentype='online');
								
							}
						}
					}
				}
			}
			
				
		}
		if(isset($comma_separated))
		{
			$data['send_to_user'] = $comma_separated;
		}
		// Bind the form fields to the hello table
		if (!$row->bind($data)) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		// Make sure the record is valid
		if (!$row->check()) 
		{ 
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		// Store the web link table to the database
		if (!$row->store()) 
		{
			$this->setError( $row->getErrorMsg() );
			return false;
		}
		// 2bc3122dcf32d1b8cec408c5478d54fdd3b2b90ccf3b482b174b7a6b9299e96a
		//$this->send_push_notification('2bc3122dcf32d1b8cec408c5478d54fdd3b2b90ccf3b482b174b7a6b9299e96a', $data['notification_text'],$badge = 1,$type='');
		return true;
	}
	
	
// ========================================	Push Notification =======================================

	function send_push_notification($device_token, $message='',$badge = 1,$type='')
	{
		$value_cfg = $this->getConfig();
		if(array_key_exists('PNC_IPHONE_DEPLOYMENT_MODE', $value_cfg))
    	{
    		//echo $value_cfg['PNC_IPHONE_DEPLOYMENT_MODE']; echo "<br/>";
    		if($value_cfg['PNC_IPHONE_DEPLOYMENT_MODE'] == 'sandbox')
    		{
    			$server = 'ssl://gateway.sandbox.push.apple.com:2195';
    		} else {
    			$server = 'ssl://gateway.push.apple.com:2195'; 
    		}
    		
    	}
    	
    	/*if(array_key_exists('PNC_IPHONE_UPLOAD_FILE',$value_cfg))
    	{
    		if(!empty($value_cfg['PNC_IPHONE_UPLOAD_FILE']))
    		{
    			$urlCertFilePath = JPATH_SITE.DS.'components'.DS.'com_ijoomer'.DS.'certificates'.DS.$value_cfg['PNC_IPHONE_UPLOAD_FILE'];
    			//echo $urlCertFilePath; 
    			if(file_exists($urlCertFilePath))
    			{
    				//echo "certificate file exists"; 
    				$keyCertFilePath = JPATH_SITE.DS.'components'.DS.'com_ijoomer'.DS.'certificates'.DS.$value_cfg['PNC_IPHONE_UPLOAD_FILE'];
    			} else {
    				//echo "certificate file not exists"; 
    				$keyCertFilePath = '';
    			}
    			
    		} else {
    				//echo "certificate file not found"; 
    				$keyCertFilePath = '';
    			}
    	}*/
    	//exit;
    	
    	//$server = 'ssl://gateway.push.apple.com:2195'; 
		//if(PUSH_SERVER=='1')
		//$server = 'ssl://gateway.sandbox.push.apple.com:2195';
		
		//$keyCertFilePath = CERTIFICATE_PATH;
		//smb://192.168.5.10/html/virtuemart_ijoomer/components/com_ijoomer/certificates
		
		$keyCertFilePath = JPATH_SITE.DS.'components'.DS.'com_ijoomer'.DS.'certificates'.DS.'certificates.pem';
		
		$sound = 'default';
		// Construct the notification payload
		$badge = (int) $badge;
		$body = array();
		$body['aps'] = array('alert' => $message);
		if ($badge)
		$body['aps']['badge'] = $badge;
		if ($sound)
		$body['aps']['sound'] = $sound;
		if($type!='')
		$body['aps']['type'] = $type;
		
		/* End of Configurable Items */
		
		$ctx = stream_context_create();
		//echo "<pre/>"; print_r($ctx);
		stream_context_set_option($ctx, 'ssl', 'local_cert', $keyCertFilePath);
		
		// assume the private key passphase was removed.
		// exit;stream_context_set_option($ctx, 'ssl', 'passphrase', $pass);
		
		//$fp = stream_socket_client($server, $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
		$fp = stream_socket_client($server, $error, $errorString, 60, STREAM_CLIENT_CONNECT, $ctx);
		// fo production change the server to ssl://gateway.push.apple.com:219
		
		if (!$fp)
		{
			//global mainframe;
			print "Failed to connect ".$error." ".$errorString;
			//echo "<br/>test"; exit;
			return;
		}
		
		//$payload = '{"aps": {"badge": 1, "alert": "Hello from iJoomer!", "sound": "cow","type":"online"}}';//json_encode($body); 
		$payload = json_encode($body); 
		$msg = chr(0) . pack("n",32) . pack('H*', str_replace(' ', '', $device_token)) . pack("n",strlen($payload)) . $payload;
		fwrite($fp, $msg);
		fclose($fp);
		//echo "test successfully"; exit;  
	}
// ============================================ Get Config Value ========================================

	function getConfig()
	{
		$db = JFactory :: getDBO();
		$query= " select * from #__ijoomer_config ";
		$db->setQuery($query);
		$rows=$db->loadObjectlist();
		$cfg=array();	
		for($i=0;$i<count($rows);$i++){
			$cfg[$rows[$i]->config_name]=$rows[$i]->config_value;
		}		
		return $cfg;
	}
// =========================================== Android Push Notification ===============================

	function googleAuthenticate($username, $password,  $service) 
	{    
	    
		//echo $username; echo "<br/>"; echo $password;  exit;
		//echo "<br/>"; echo $source; echo "<br/>"; echo $service; 
	    //session_start();
	    //echo "test1"; 
	    /*if( isset($_SESSION['google_auth_id']) && $_SESSION['google_auth_id'] != null)
	    {
	    	
	    	//echo $_SESSION['google_auth_id'];
	    	//die("DDDD");
	        //return $_SESSION['google_auth_id'];
	    }*/
		
	    // get an authorization token
	    $ch = curl_init();
	    if(!$ch){
	    	
	        return false;
	    }
	    
		curl_setopt($ch, CURLOPT_URL, "https://www.google.com/accounts/ClientLogin");
	    //curl_setopt($ch, CURLOPT_URL, "https://www.google.com/accounts/ClientLogin");
	    
		//$post_fields = "accountType=" . urlencode('HOSTED_OR_GOOGLE')
	    /*$post_fields = "accountType=" . urlencode('HOSTED_OR_GOOGLE')
	        . "&Email=" . urlencode($username)
	        . "&Passwd=" . urlencode($password)
	        . "&source=" . urlencode($source)
	        . "&service=" . urlencode($service);*/
	    $post_fields = array ( "Email" => $username, "Passwd" => $password, "accountType"=>"GOOGLE", "service" => "ac2dm" );
	  //$post_fields = array ( "Email" => $username, "Passwd" => $password, "accountType"=>"GOOGLE", "source" => $source, "service" => "ac2dm" );
	   //print_r($post_fields); echo "DDD";exit;
	    curl_setopt($ch, CURLOPT_HEADER, true);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);    
	    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	
	    // for debugging the request
	    //curl_setopt($ch, CURLINFO_HEADER_OUT, true); // for debugging the request
	
	    $response = curl_exec($ch);
		//var_dump(curl_getinfo($ch)); //for debugging the request
	    //var_dump($response); echo "<br/>";
	
	    curl_close($ch);
		//echo $response; exit;
	    if (strpos($response, '200 OK') === false) {
	        return false;
	    }
	
	    // find the auth code
	    preg_match("/(Auth=)([\w|-]+)/", $response, $matches);
	
	    if (!$matches[2]) {
	        return false;
	    }
		
	    $_SESSION['google_auth_id'] = $matches[2];
	    return $matches[2];
	}
	
	function sendMessageToPhone($authCode, $deviceRegistrationId, $msgType, $messageText,$whentype) 
	{
		//echo $authCode; echo "<br/><br/>";
		//echo $deviceRegistrationId; exit; 
		//echo "<br/>"; echo $msgType; echo "<br/>"; echo $messageText; 
		
		//$device_id = "1"; // Used in case there is multiple messages being sent
		if(!empty($authCode) && !empty($deviceRegistrationId) && !empty($messageText))
		{
			
	        $headers = array('Authorization: GoogleLogin auth=' . $authCode);
	        
	        //$data = array(
	          //  'registration_id' => $deviceRegistrationId,
	            //'collapse_key' => $msgType,
	        	//'data.badge' => 1,
	           //'data.message' => $messageText //TODO Add more params with just simple data instead           
	        //);
		$data = array(
	            'registration_id' => $deviceRegistrationId,
	            'collapse_key' =>  $msgType,
		    'data.type' => $whentype,
		    'data.badge' => 1,
	            'data.message' => $messageText //TODO Add more params with just simple data instead           
	        );	
	        $ch = curl_init();
			
	        curl_setopt($ch, CURLOPT_URL, "https://android.apis.google.com/c2dm/send");
	        if ($headers)
	            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	            //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($ch, CURLOPT_POST, true);
	        //curl_setopt($ch, CURLOPT_FAILONERROR, true);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			
	        $response = curl_exec($ch);
	        $error = curl_error( $ch );
	        //echo $error; exit;
	        
	        			
	        curl_close($ch);
	        //echo $response; exit;
	        return $response;
		} else {
			return false;
		}
		//return $response;
    }
	
	
//===================================================================================================	
}
?>
