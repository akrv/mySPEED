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
jimport('joomla.version');
class iusermain
{
	var $date_now;
	var $jomHelper;
	var $jversion;
	function __construct()
    {  
        $this->date_now	= JFactory::getDate();
        $this->jversion = new JVersion();
    }
	function login()
	{
		$mainframe = JFactory::getApplication();
			
		$xmlcnt = array();
		if (!isset($HTTP_RAW_POST_DATA))
		{
   			$HTTP_RAW_POST_DATA = file_get_contents("php://input");
   		} 
   		if(empty($HTTP_RAW_POST_DATA))
   		{
   			$xmlcnt['code'] = "2";
   			return $xmlcnt;
   		}
   		$data = array();
   		
   		$doc = new DOMDocument();
		$doc->loadXML( $HTTP_RAW_POST_DATA);
		$login_data = $doc->getElementsByTagName( "data" );
		
		foreach( $login_data as $login )
		{
			$user_nm = $login->getElementsByTagName( "username" );
			$data['user_nm'] = $user_nm->item(0)->nodeValue;
			
			$password = $login->getElementsByTagName( "password" );
			$password = $password->item(0)->nodeValue;
			
			$lat = $login->getElementsByTagName( "lat" );
			$data['latitude'] = $lat->item(0)->nodeValue;
			
			$long = $login->getElementsByTagName( "long" );
			$data['longitude']  = $long->item(0)->nodeValue;	
			
			$device_token = $login->getElementsByTagName( "device_token" );
			$data['device_token']  = $device_token->item(0)->nodeValue;

			$android_device_token = $login->getElementsByTagName( "android_device_token" );
			$data['android_device_token']  = $android_device_token->item(0)->nodeValue;	
			
			$bb_device_token = $login->getElementsByTagName( "bb_device_token" );
			$data['bb_device_token']  = $bb_device_token->item(0)->nodeValue;
		}
		
		$credentials = array();
		$credentials['username'] = $data['user_nm'];
		$credentials['password'] = $password;
		$error = $mainframe->login($credentials);
		if($error == '1')
		{
			$xmlcnt = $this->login_xml($data);
		}
		else
		{
			$xmlcnt['code'] = "2";
		}		
		return $xmlcnt;
	}
	
	// backup 16-4-2012 Maykal Patel
	
	/*function login()
	{
		$mainframe = JFactory::getApplication();
			
		$xmlcnt = array();
		if (!isset($HTTP_RAW_POST_DATA))
		{
   			$HTTP_RAW_POST_DATA = file_get_contents("php://input");
   		} 
   		if(empty($HTTP_RAW_POST_DATA))
   		{
   			$xmlcnt['code'] = "2";
   			return $xmlcnt;
   		}
   		$data = array();
   		
   		$doc = new DOMDocument();
		$doc->loadXML( $HTTP_RAW_POST_DATA);
		$login_data = $doc->getElementsByTagName( "data" );
		
		foreach( $login_data as $login )
		{
			$user_nm = $login->getElementsByTagName( "username" );
			$data['user_nm'] = $user_nm->item(0)->nodeValue;
			
			$password = $login->getElementsByTagName( "password" );
			$password = $password->item(0)->nodeValue;
			
			$lat = $login->getElementsByTagName( "lat" );
			$data['latitude'] = $lat->item(0)->nodeValue;
			
			$long = $login->getElementsByTagName( "long" );
			$data['longitude']  = $long->item(0)->nodeValue;	
			
			$device_token = $login->getElementsByTagName( "device_token" );
			$data['device_token']  = $device_token->item(0)->nodeValue;			
		}
		
		$credentials = array();
		$credentials['username'] = $data['user_nm'];
		$credentials['password'] = $password;
		$error = $mainframe->login($credentials);
		if($error == '1')
		{
			$xmlcnt = $this->login_xml($data);
		}
		else
		{
			$xmlcnt['code'] = "2";
		}		
		return $xmlcnt;
	}*/
	
	function fblogin()
	{
		$opt = GC_REGISTRATION;
		$cfile = JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' .DS. 'core.php';
		//$AVAIL_EXTS = explode(",",AVAIL_EXTS);
		
		$xmlcnt = array();
		
		$mainframe = JFactory::getApplication();
		jimport('joomla.user.helper');
		$db =& JFactory::getDBO();
		
		if (!isset($HTTP_RAW_POST_DATA))
		{
   		  	$HTTP_RAW_POST_DATA = file_get_contents("php://input");
   		}
   		
		if(empty($HTTP_RAW_POST_DATA))
   		{
   			$xmlcnt['code'] = "2";
   			return $xmlcnt;
   		}
   		
		$doc = new DOMDocument();
		$doc->loadXML( $HTTP_RAW_POST_DATA );
	
		$xmlrowdata = $doc->getElementsByTagName( "data" );

		$data = array();
		
		foreach( $xmlrowdata as $xmldata )
		{
			$relname = $xmldata->getElementsByTagName( "name" );
			$data['relname'] = isset($relname->item(0)->nodeValue) ? $relname->item(0)->nodeValue : '' ;
			
			$username = $xmldata->getElementsByTagName( "username" );
			$data['user_nm'] = isset($username->item(0)->nodeValue) ? $username->item(0)->nodeValue : '';
			
			$email = $xmldata->getElementsByTagName( "email" );
			$data['email'] = isset($email->item(0)->nodeValue) ? $email->item(0)->nodeValue : '';
		
			$lat = $xmldata->getElementsByTagName( "lat" );
			$data['latitude'] = isset($lat->item(0)->nodeValue) ? $lat->item(0)->nodeValue : '';
			
			$long = $xmldata->getElementsByTagName( "long" );
			$data['longitude']  = isset($long->item(0)->nodeValue) ? $long->item(0)->nodeValue : '';	
			
			$pic_big = $xmldata->getElementsByTagName( "pic_big" );
			$data['pic_big']  = isset($pic_big->item(0)->nodeValue) ? $pic_big->item(0)->nodeValue : '';
			
			$password = $xmldata->getElementsByTagName( "password" );
			$password_set = isset($password->item(0)->nodeValue) ? $password->item(0)->nodeValue :'';
		
			$reg_opt_d = $xmldata->getElementsByTagName( "reg_opt" );
			$reg_opt = isset($reg_opt_d->item(0)->nodeValue) ? $reg_opt_d->item(0)->nodeValue : '';
			
			$fbid = $xmldata->getElementsByTagName( "fbid" );
			$fbid = isset($fbid->item(0)->nodeValue) ? $fbid->item(0)->nodeValue : '' ;
			
			$device_token = $xmldata->getElementsByTagName( "device_token" );
			$data['device_token']  = isset($device_token->item(0)->nodeValue) ? $device_token->item(0)->nodeValue : '';			
			
		}
		$time = time();
		
		if($reg_opt==0)
		{
			// first check if fbuser in db logged in
			$query = "SELECT u.id,u.username FROM #__users AS u,#__community_connect_users AS cu WHERE u.id = cu.userid  
					AND cu.`connectid`='".$password_set."'";
			$db->setQuery($query);
			$userinfo = $db->loadObject();
			if(isset($userinfo->id) && $userinfo->id > 0)
			{
				$salt  = JUserHelper::genRandomPassword(32);
				
				$crypt = JUserHelper::getCryptedPassword($password_set.$time, $salt);
				$data['password'] = $crypt.':'.$salt;
								
				$query = "UPDATE #__users SET `password`='".$data['password']."' WHERE `id`='".$userinfo->id."'";
				$db->setQuery($query);
				$db->Query();
				
				$usersipass['username'] = $userinfo->username;    
				$usersipass['password'] = $password_set.$time;
				$error = $mainframe->login($usersipass);
				if($error == '1')
				{
					$xmlcnt = $this->login_xml($data);
					return $xmlcnt;
				}
				else
				{	
					$xmlcnt['code'] = 2;
				}
			}
			else 
			{
				$xmlcnt["code"] = "7";		
				return $xmlcnt;
			}
		}
		else if($reg_opt==1)	//registration option 1 is already user
		{
			$credentials = array();
			$credentials['username'] = $data['user_nm'];
			$credentials['password'] = $password_set;
			
			$error = $mainframe->login($credentials);
			
			if($error == '1' && $fbid!="")
			{
				// connect fb user to site user...
				$user = & JFactory::getUser();
				if($opt == 'community' && file_exists($cfile))
				{
					require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' .DS. 'core.php');
					
					$ins_query = "INSERT INTO #__community_connect_users SET userid='".$user->id."',connectid='".$fbid."',type='facebook'";
					$db->setQuery($ins_query);
					$db->Query();
					
					$salt  = JUserHelper::genRandomPassword(32);
				
					$crypt = JUserHelper::getCryptedPassword($password_set.$time, $salt);
					$data['password'] = $crypt.':'.$salt;
								
					$query = "UPDATE #__users SET `password`='".$data['password']."' WHERE `id`='".$user->id."'";
					$db->setQuery($query);
					$db->Query();
				
					$config		=& CFactory::getConfig();
					// store user image...
					CFactory::load( 'libraries' , 'facebook' );
					$facebook		= new CFacebook();
					// edited by Salim (Date: 08-09-2011)
					$data['pic_big'] = str_replace('profile.cc.fbcdn','profile.ak.fbcdn',$data['pic_big']);
					$data['pic_big'] = str_replace('hprofile-cc-','hprofile-ak-',$data['pic_big']);
					
					$facebook->mapAvatar( $data['pic_big'] , $user->id , $config->get('fbwatermark') );
				}	
				$xmlcnt = $this->login_xml($data);
			}
			else 
			{	$xmlcnt['code'] = 2; }
		}
		else 
		{
			$query = "SELECT u.id FROM #__users AS u WHERE u.`email`='".$data['email']."'";
			$db->setQuery($query);
			$uid = $db->loadResult();
			
			// if user exists with email address send email id already exists
			if($uid>0)
			{
				$query = "SELECT u.id FROM #__users AS u,#__community_connect_users AS cu WHERE u.id = cu.userid AND u.`email`='".$data['email']."' 
					AND cu.`connectid`='".$password_set."'";
				$db->setQuery($query);
				$uid = $db->loadResult();
				if(empty($uid))
				{
					$xmlcnt["code"] = "4";	
					return $xmlcnt;
				}
			}

			$query = "SELECT id FROM #__users WHERE `username`='".$data['user_nm']."'";
			$db->setQuery($query);
			$uid = $db->loadResult();
			if($uid>0)
			{
				$xmlcnt["code"] = "5";	
				return $xmlcnt;
			}
			else 
			{
				jimport('joomla.user.helper');
				// if joomla 1.5 
				if(JOOMLA15)
				{
					// Get required system objects
					$user 		= clone(JFactory::getUser());
					$usersConfig = &JComponentHelper::getParams( 'com_users' );
					// Initialize new usertype setting
					$newUsertype = $usersConfig->get( 'new_usertype' );
					if (!$newUsertype) {
						$newUsertype = 'Registered';
					}
					$salt  = JUserHelper::genRandomPassword(32);
					
					$crypt = JUserHelper::getCryptedPassword($password_set.$time, $salt);
					$data['password'] = $crypt.':'.$salt;
					
					$authorize	=& JFactory::getACL();	
					$user->set('id', 0);
					$user->set('usertype', $newUsertype);
					$user->set('gid', $authorize->get_group_id( '', $newUsertype, 'ARO' ));
					$user->set('registerDate', $this->date_now->toMySQL());
					$user->set('name',$data['relname']);
					$user->set('username',$data['user_nm']);
					$user->set('password',$data['password']);
					$user->set('password2',$data['password']);
					$user->set('email',$data['email']);
					
					// 	If there was an error with registration, set the message and display form
					if ( !$user->save() )
					{
						$xmlcnt["code"] = "2";
						return $xmlcnt;
					}
					$aclval = $user->get('id');
				}
				else 	// if joomla 1.6 
				{
					// Initialise the table with JUser.
					$user = new JUser;
					//include_once(JPATH_ROOT."/components/com_users/models/registration.php");
					$data['name'] = trim(str_replace("\n","",$data['relname']));
					$data['username'] = trim(str_replace("\n","",$data['user_nm']));
					$data['password1'] = trim(str_replace("\n","",$password_set.$time));
					$data['password2'] = trim(str_replace("\n","",$password_set.$time));
					$data['email1'] = trim(str_replace("\n","",$data['email']));
					$data['email2'] = trim(str_replace("\n","",$data['email']));
					
					//$usermodel =new UsersModelRegistration();
					//$aclval = $usermodel->register($data);
					
					$user->bind($data);
					$user->save();
					$aclval = $user->id;
					
					// store usegroup for user...
					$sql = "INSERT INTO #__user_usergroup_map SET group_id='2',user_id='".$aclval."'";
					$db->setQuery($sql);
					$db->Query();
				}
				if($opt == 'community' && file_exists($cfile))
				{
					require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' .DS. 'core.php');
					
					$ins_query = "INSERT INTO #__community_connect_users SET userid='".$aclval."',connectid='".$password_set."',type='facebook'";
					$db->setQuery($ins_query);
					$db->Query();
					$config		=& CFactory::getConfig();
					// store user image...
					CFactory::load( 'libraries' , 'facebook' );
					$facebook		= new CFacebook();
					
					// edited by Salim (Date: 08-09-2011)
					$data['pic_big'] = str_replace('profile.cc.fbcdn','profile.ak.fbcdn',$data['pic_big']);
					$data['pic_big'] = str_replace('hprofile-cc-','hprofile-ak-',$data['pic_big']);
					
					$facebook->mapAvatar( $data['pic_big'] , $aclval , $config->get('fbwatermark') );
				}	
			
				if($opt == 'kunena')
				{
					$ins_query = "INSERT INTO #__kunena_users SET userid='".$aclval."'";
					$db->setQuery($ins_query);
					$db->Query();
				}

				// update password again...
				$salt  = JUserHelper::genRandomPassword(32);
				
				$crypt = JUserHelper::getCryptedPassword($password_set.$time, $salt);
				$data['password'] = $crypt.':'.$salt;
								
				$query = "UPDATE #__users SET `password`='".$data['password']."' WHERE `id`='".$aclval."'";
				$db->setQuery($query);
				$db->Query();
				
				$usersipass['username'] = trim(str_replace("\n","",$data['user_nm']));
				$usersipass['password'] = trim(str_replace("\n","",$password_set.$time));
				$error = $mainframe->login($usersipass);
				if($error == '1')
				{
					$xmlcnt = $this->login_xml($data);
					return $xmlcnt;
				}
				else
				{	
					$xmlcnt['code'] = 2;
				}
			}
		}
		return $xmlcnt;
	}
	
	
	function login_xml($data=array())
	{
		$xmlcnt = array();
		$db =& JFactory::getDBO();
		$user = & JFactory::getUser();
		
		$query = "SELECT userid FROM  `#__ijoomer_users` WHERE `userid`='".$user->id."'";
		$db->setQuery($query);
		$uid = $db->loadResult();
		$set = '';
		if(empty($uid)){
			$set .= 'pushFriendOnline'.'='.'1'.'\n';
			$set .= 'pushInboxMessage'.'='.'1'.'\n';
			$set .= 'pushFriendRequest'.'='.'1'.'\n';
			$query = "INSERT INTO `#__ijoomer_users` SET `userid`='".$user->id."',`jomsocial_params`='".$set."' ";
			$db->setQuery($query);
			$db->query();
		}
		
		if(isset($data['device_token']))
		{
			$sql2="UPDATE #__users SET `device_token` = '' WHERE `device_token`='".$data['device_token']."'";
			$db->setQuery($sql2);
			$db->query();
			
			$sql2="UPDATE #__users SET `device_token` = '".$data['device_token']."' WHERE `id`=".$user->id;
			$db->setQuery($sql2);
			$db->query();
			
			$sql2="UPDATE #__ijoomer_users SET `device_token` = '' WHERE `device_token`='".$data['device_token']."'";
			$db->setQuery($sql2);
			$db->query();
			
			$sql2="UPDATE #__ijoomer_users SET `device_token` = '".$data['device_token']."' WHERE `userid`=".$user->id;
			$db->setQuery($sql2);
			$db->query();
			
		}
		/*if(isset($data['device_token']))
		{
			$sql2="UPDATE #__users SET `device_token` = '' WHERE `device_token`='".$data['device_token']."'";
			$db->setQuery($sql2);
			$db->query();
			
			$sql2="UPDATE #__users SET `device_token` = '".$data['device_token']."' WHERE `id`=".$user->id;
			$db->setQuery($sql2);
			$db->query();
		}*/
		
		if(isset($data['android_device_token']))
		{
			
			$sql2="UPDATE #__users SET `device_token` = '' WHERE `device_token`='".$data['android_device_token']."'";
			$db->setQuery($sql2);
			$db->query();
			
			$sql2="UPDATE #__users SET `device_token` = '".$data['android_device_token']."' WHERE `id`=".$user->id;
			$db->setQuery($sql2);
			$db->query();
			
			$sql2="UPDATE #__ijoomer_users SET `android_device_token` = '' WHERE `android_device_token`='".$data['android_device_token']."'";
			$db->setQuery($sql2);
			$db->query();
			
			$sql2="UPDATE #__ijoomer_users SET `android_device_token` = '".$data['android_device_token']."' WHERE `userid`=".$user->id;
			$db->setQuery($sql2);
			$db->query();
		}
		
		if(isset($data['bb_device_token']))
		{

			$sql2="UPDATE #__users SET `device_token` = '' WHERE `device_token`='".$data['bb_device_token']."'";
			$db->setQuery($sql2);
			$db->query();
			
			$sql2="UPDATE #__users SET `device_token` = '".$data['bb_device_token']."' WHERE `id`=".$user->id;
			$db->setQuery($sql2);
			$db->query();
			
			$sql2="UPDATE #__ijoomer_users SET `bb_device_token` = '' WHERE `bb_device_token`='".$data['bb_device_token']."'";
			$db->setQuery($sql2);
			$db->query();
			
			$sql2="UPDATE #__ijoomer_users SET `bb_device_token` = '".$data['bb_device_token']."' WHERE `userid`=".$user->id;
			$db->setQuery($sql2);
			$db->query();
		}
		
		$sql1= "SELECT session_id FROM #__session WHERE userid ='".$user->id."' ORDER BY time DESC";
		$db->setQuery($sql1);
		$secret_key = $db->loadResult();
		
		if($this->jversion->RELEASE > 1.5)
		{
			$query = "SELECT e.extension_id AS 'id', e.element AS 'option', e.params, e.enabled FROM #__extensions as e
						WHERE e.type = 'component' AND e.element = 'com_kunena' ";
		}else{
			$query = "SELECT c.* FROM #__components as c WHERE c.option = 'com_kunena' AND c.parent = '0' ";
		}
		//echo $query;		
		$db->setQuery($query);
		$components = $db->loadObject();
		
		$qry = "SELECT plugin_name FROM #__ijoomer_plugins WHERE plugin_option = 'com_kunena' AND published = '1' ";
		//echo $qry;
		$db->setQuery( $qry );
		$plugins = $db->loadResult();
		
		if((count($components)>0 && $components->enabled == 1) || $plugins)
		{
			/*$kconfig	=KunenaFactory::getConfig();
			if($kconfig->integration_login == 'auto' || $kconfig->integration_login == 'jomsocial'){*/
				$sql = "SELECT userid FROM #__kunena_users WHERE userid = '".$user->id."' ";
				//echo $sql;
				$db->setQuery($sql);
				$userid = $db->loadResult();
				
				if(empty($userid))
				{
					$sql = "INSERT INTO #__kunena_users (`userid`,`view`,`signature`,`moderator`,`banned`,`ordering`,`posts`,`avatar`,`karma`,`karma_time`,`group_id`,`uhits`,`personalText`,`gender`,`birthdate`,`location`,`ICQ`,`AIM`,`YIM`,`MSN`,`SKYPE`,`TWITTER`,`FACEBOOK`,`GTALK`,`MYSPACE`,`LINKEDIN`,`DELICIOUS`,`FRIENDFEED`,`DIGG`,`BLOGSPOT`,`FLICKR`,`BEBO`,`websitename`,`websiteurl`,`rank`,`hideEmail`,`showOnline`) 
					VALUES ('".$user->id."','flat',null,'0',null,'0','0',null,'0','0','1','0',null,'0','0001-01-01',null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,'0','1','1')";
					//echo $sql;
					$db->setQuery($sql);
					$db->query();
				}
			/*}*/
		}
		
		$xmlcnt['code'] = "1";
		$xmlcnt['userid'] = $user->id;
		$xmlcnt['sessionid'] = $secret_key;
		
		$opt = GC_REGISTRATION;
		$cfile = JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' .DS. 'core.php';
		
		if($opt == 'No' || $opt == 'no')
		{
			$xmlcnt["invalid_data"] = 'component not found';
			return $xmlcnt;
		}
		if($opt == 'community' && file_exists($cfile)  )
		{
			// update jomsocial latitude & longitude 
			$query = "UPDATE #__community_users SET latitude='".$data['latitude']."',longitude='".$data['longitude']."' WHERE userid='".$user->id."'";
			$db->setQuery($query);
			$db->Query();
		}
		if($opt =='community' && file_exists($cfile) )
		{
			require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' .DS. 'core.php');
			$this->jomHelper = new jomHelper();
			$user_detail = CFactory :: getUser($user->id);
       		$img=$user_detail->_avatar;
			$name=$this->jomHelper->getName($user_detail);
			$status=$user_detail->_status;
			$count=$user_detail->_view;
			if($img!="")
			{
				$xmlcnt["avatar"] = JURI::base().$img;
			}	
			else
			{
				$xmlcnt["avatar"] =  JURI::base()."components/com_community/assets/user.png";
			}	
			$xmlcnt["user"] = $name;
			$xmlcnt["status"] =$status;
			$xmlcnt["viewcount"] = $count;
	
			$query = "SELECT id FROM #__plugins WHERE element='push_online' AND published=1";
			$db->setQuery($query);
			$pids = 0;
			$pids = $db->loadResult();
			if($pids==0)
			{
				// send push notification to friends 
				if(PUSH_FRIEND_ONLINE)
				{
					
					$query = 'SELECT b.device_token,b.android_device_token,b.bb_device_token,b.userid '
	            		.' FROM #__community_connection as a, #__ijoomer_users as b'
	            		.' WHERE a.`connect_from`='.$db->Quote($user->id)
	            		.' AND a.`status`=1 '
	            		.' AND a.`connect_to`=b.`userid` '
	            		.' ORDER BY a.`connection_id` DESC ';
	            		
					/*$query = 'SELECT b.device_token,b.id '
	            		.' FROM #__community_connection as a, #__users as b'
	            		.' WHERE a.`connect_from`='.$db->Quote($user->id)
	            		.' AND a.`status`=1 '
	            		.' AND a.`connect_to`=b.`id` '
	            		.' ORDER BY a.`connection_id` DESC ';*/
	
					$db->setQuery($query);
					$device_tokens=$db->loadObjectlist();
					foreach($device_tokens as $device_token)
					{
						$res = $this->jomHelper->getNotificationParams($device_token->userid);
						if($res['pushFriendOnline']===1)
						{
							$message = $name.' '.JText::_('IS NOW ONLINE');
							//$this->jomHelper->send_push_notification($device_token->device_token,$message);
							if($device_token->device_token!="")
							{								
								$this->jomHelper->send_push_notification($device_token->device_token,$message);
							}
							
							if($device_token->android_device_token!="")
							{
								$username = PUSH_ANDROID_EMAILID;
								$password = PUSH_ANDROID_PASSWORD;
							    $service  = 'ac2dm';
							
								$authcode = $this->jomHelper->googleAuthenticate($username, $password, $service);
								if(!empty($authcode))
								{
										$this->jomHelper->sendMessageToAndroid($authcode, $device_token->android_device_token, $msgType='0',$message,$totMsg='',$whentype='online');
																			
								}
							}
							
							if($device_token->bb_device_token!="")
							{
								//Put Blackberry Push Notification Code
							}
						}
					}
				}
			}
		}
		elseif($opt == 'kunena')
		{
			$sql= "SELECT us.*, ku.* FROM #__kunena_users as ku ".
			"LEFT JOIN #__users as us ON us.id=ku.userid ".
			"WHERE ku.userid=".$user->id;
			$db->setQuery($sql);
			$user_detail = $db->loadObject();
			if($user_detail->avatar=="" || $user_detail->avatar==NULL)
				$user_detail->avatar = "nophoto.jpg";
			$xmlcnt["avatar"] = JURI::base()."/media/kunena/avatars/gallery/".$user_detail->avatar;
			$xmlcnt["user"] = $user_detail->username;
		}
		elseif($opt == 'joomla')
		{
			$sql = "SELECT * FROM #__users WHERE id='".$user->id."'";
			$db->setQuery($sql);
			$user_detail = $db->loadObject();
			$xmlcnt["user"] = $user_detail->username;
		}
		return $xmlcnt;
	}
	
	// backup of 16-4-2012 Maykal Patel
	/*function login_xml($data=array())
	{
		$xmlcnt = array();
		$db =& JFactory::getDBO();
		$user = & JFactory::getUser();
		
		if(isset($data['device_token']))
		{
			$sql2="UPDATE #__users SET `device_token` = '' WHERE `device_token`='".$data['device_token']."'";
			$db->setQuery($sql2);
			$db->query();
			
			$sql2="UPDATE #__users SET `device_token` = '".$data['device_token']."' WHERE `id`=".$user->id;
			$db->setQuery($sql2);
			$db->query();
		}
		
		$sql1= "SELECT session_id FROM #__session WHERE userid ='".$user->id."' ORDER BY time DESC";
		$db->setQuery($sql1);
		$secret_key = $db->loadResult();
		
		$xmlcnt['code'] = "1";
		$xmlcnt['userid'] = $user->id;
		$xmlcnt['sessionid'] = $secret_key;
		
		$opt = GC_REGISTRATION;
		$cfile = JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' .DS. 'core.php';
		
		if($opt == 'No' || $opt == 'no')
		{
			$xmlcnt["invalid_data"] = 'component not found';
			return $xmlcnt;
		}
		if($opt == 'community' && file_exists($cfile)  )
		{
			// update jomsocial latitude & longitude 
			$query = "UPDATE #__community_users SET latitude='".$data['latitude']."',longitude='".$data['longitude']."' WHERE userid='".$user->id."'";
			$db->setQuery($query);
			$db->Query();
		}
		if($opt =='community' && file_exists($cfile) )
		{
			require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' .DS. 'core.php');
			$this->jomHelper = new jomHelper();
			$user_detail = CFactory :: getUser($user->id);
       		$img=$user_detail->_avatar;
			$name=$this->jomHelper->getName($user_detail);
			$status=$user_detail->_status;
			$count=$user_detail->_view;
			if($img!="")
			{
				$xmlcnt["avatar"] = JURI::base().$img;
			}	
			else
			{
				$xmlcnt["avatar"] =  JURI::base()."components/com_community/assets/user.png";
			}	
			$xmlcnt["user"] = $name;
			$xmlcnt["status"] =$status;
			$xmlcnt["viewcount"] = $count;
	
			$query = "SELECT id FROM #__plugins WHERE element='push_online' AND published=1";
			$db->setQuery($query);
			$pids = 0;
			$pids = $db->loadResult();
			if($pids==0)
			{
				// send push notification to friends 
				if(PUSH_FRIEND_ONLINE)
				{
					$query = 'SELECT b.device_token,b.id '
	            		.' FROM #__community_connection as a, #__users as b'
	            		.' WHERE a.`connect_from`='.$db->Quote($user->id)
	            		.' AND a.`status`=1 '
	            		.' AND a.`connect_to`=b.`id` '
	            		.' ORDER BY a.`connection_id` DESC ';
	
					$db->setQuery($query);
					$device_tokens=$db->loadObjectlist();
					foreach($device_tokens as $device_token)
					{
						if($device_token->device_token!="")
						{
							$res = $this->jomHelper->getNotificationParams($device_token->id);
							if($res['pushFriendOnline']===1)
							{
								$message = $name.' '.JText::_('IS NOW ONLINE');
								$this->jomHelper->send_push_notification($device_token->device_token,$message);
							}
						}
					}
				}
			}
		}
		elseif($opt == 'kunena')
		{
			$sql= "SELECT us.*, ku.* FROM #__kunena_users as ku ".
			"LEFT JOIN #__users as us ON us.id=ku.userid ".
			"WHERE ku.userid=".$user->id;
			$db->setQuery($sql);
			$user_detail = $db->loadObject();
			if($user_detail->avatar=="" || $user_detail->avatar==NULL)
				$user_detail->avatar = "nophoto.jpg";
			$xmlcnt["avatar"] = JURI::base()."/media/kunena/avatars/gallery/".$user_detail->avatar;
			$xmlcnt["user"] = $user_detail->username;
		}
		elseif($opt == 'joomla')
		{
			$sql = "SELECT * FROM #__users WHERE id='".$user->id."'";
			$db->setQuery($sql);
			$user_detail = $db->loadObject();
			$xmlcnt["user"] = $user_detail->username;
		}
		return $xmlcnt;
	}*/
	
	// registration function....
    function registration()
	{
		$mainframe = JFactory::getApplication();
		$authorize	=& JFactory::getACL();
		$reqPost = JRequest::get('request');
		
		$opt = GC_REGISTRATION;
		
		if($opt == 'No' || $opt == 'no')
		{
			$xmlcnt["invalid_data"] = JText::_('REGISTER_NOT_ALLOW');
			return $xmlcnt;
		}
		
		foreach($reqPost as $key => $value)
		{
			$post[$key] =  str_replace("\n","",trim($value));
		}
		$db=& JFactory::getDBO();
		$sql="SELECT id FROM `#__users` WHERE username='".str_replace("\n","",trim($post['username']))."'";
		$db->setQuery($sql);
	 	$userexists = $db->loadResult();
		
	 	if($userexists > 0)
		{
			$xmlcnt["code"] = "5";	
			return $xmlcnt; 
		}
		
		$sql="SELECT id FROM `#__users` WHERE email='".str_replace("\n","",trim($post['email']))."'";
		$db->setQuery($sql);
		$emailexists = $db->loadResult();
		
		if($emailexists > 0)
		{
			$xmlcnt["code"] = "4";	
			return $xmlcnt; 
		}
		
		$cfile = JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' .DS. 'core.php';
		$Full_flag = JRequest::getInt('full',0);
		if($Full_flag!= 1 && $opt == 'community' && file_exists($cfile))
		{
			require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' .DS. 'core.php');
			$db =& JFactory::getDBO();
			$profiletype =  JRequest::getInt( 'type');
			$sql= "SELECT field_id FROM #__community_profiles_fields AS cpf ".
				"WHERE cpf.parent =".$profiletype;
			$db->setQuery($sql);
			$fields_ids = $db->loadResultArray();
			
			$fields_cond = '';
			if(count($fields_ids)>0)
			{
				$fields_cond = " AND cf.id IN('".implode("','",$fields_ids)."') ";
			}
			
			if($profiletype>0)
			{
				$sql  = " SELECT cp.id,cp.name,cf.fieldcode,cf.options,cpf.parent,cpf.field_id,cf.id as id,cf.type,cf.name,cf.required,cf.registration,cf.published,cf.tips ";
	 			$sql .=	" FROM `#__community_profiles` AS cp,`#__community_profiles_fields` AS cpf,`#__community_fields` AS cf WHERE"; 				
	 			$sql .= " cp.id=cpf.parent and cp.id=".$profiletype." AND cf.registration=1 AND cf.published=1 AND cpf.field_id=cf.id ";
	 			$sql .= " order by cf.`ordering`,cpf.field_id " ;//echo $sql;exit;
			}
			else 
			{
				$sql=" SELECT * FROM `#__community_fields` WHERE published=1 and registration=1 order by `ordering`";//echo $sql;exit;
			}
			$db->setQuery($sql);
			$fields = $db->loadObjectList();
				
			$inc=-1;
			if(count($fields)>0)
			{
				$xmlcnt["code"] = "1";
				$xmlcnt["full"] = "1";
				foreach($fields as $field)
				{
					$id=$field->id;
					$name=$field->name;
					$required=$field->required;
					$type=$field->type;
					$code=$field->fieldcode;
					$value=$field->tips;
					
					if($field->type == 'group')
					{
						$inc++;
						$xmlcnt["fields"][$inc]["group"]["group_name"] = $name;
						$incj = 0;
					}
					else
					{
						$xmlcnt["fields"][$inc]["group"][$incj]["field"]["id"] = $id;
						$xmlcnt["fields"][$inc]["group"][$incj]["field"]["name"] = $name;
						$xmlcnt["fields"][$inc]["group"][$incj]["field"]["fieldcode"] = $code;
						$xmlcnt["fields"][$inc]["group"][$incj]["field"]["required"] = $required;
						$xmlcnt["fields"][$inc]["group"][$incj]["field"]["value"] = "";
						
						if($type == 'birthdate')
						{
							$type = "date";
						}
						if($type == 'checkbox' || $type == 'list')
						{
							$type = "multipleselect";
						}
						if($type == 'singleselect' || $type == 'radio' || $type == 'country')
						{
							$type = 'select';
						}
						if($type == 'email' || $type == 'url')
						{
							$type = 'text';
						}
						
						$xmlcnt["fields"][$inc]["group"][$incj]["field"]["type"] = $type;
						if(isset($field->options))
						{
							$option = explode("\n",$field->options);
							$i=0;
							foreach($option as $val)
							{
								$xmlcnt["fields"][$inc]["group"]["$incj"]["field"]["options"][$i]["value"] =$val;
								$i++;
							}
						}
						$incj++;
					}
				}
				return $xmlcnt;
			}
		}
		jimport('joomla.version');
		$version = new JVersion();	
			
		if($version->RELEASE=='1.5')// if joomla 1.5
		{
			jimport('joomla.user.helper');
			$lang =& JFactory::getLanguage();
			$extension = 'com_user';
			$base_dir = JPATH_SITE;
			$language_tag = 'en-GB';
			$lang->load($extension, $base_dir, $language_tag, true);
			// Get required system objects
			$user 		= clone(JFactory::getUser());
			$usersConfig = &JComponentHelper::getParams( 'com_users' );
			// Initialize new usertype setting
			$newUsertype = $usersConfig->get( 'new_usertype' );
			if (!$newUsertype) {
				$newUsertype = 'Registered';
			}
			$salt  = JUserHelper::genRandomPassword(32);
			$crypt = JUserHelper::getCryptedPassword($post['password'], $salt);
			$password_pass = $crypt.':'.$salt;
			// Set some initial user values
			$user->set('id', 0);
			$user->set('usertype', $newUsertype);
			$user->set('gid', $authorize->get_group_id( '', $newUsertype, 'ARO' ));
			$user->set('registerDate', $this->date_now->toMySQL());
			$user->set('name',$post['name']);
			$user->set('username',$post['username']);
			$user->set('password',$password_pass);
			$user->set('password2',$password_pass);
			$user->set('email',$post['email']);
			
			// If user activation is turned on, we need to set the activation information
			$useractivation = $usersConfig->get( 'useractivation' );
			if ($useractivation == '1')
			{
				
				$user->set('activation', JUtility::getHash( JUserHelper::genRandomPassword()) );
				$user->set('block', '1');
			}

			// If there was an error with registration, set the message and display form
			if ( !$user->save() )
			{
				$xmlcnt["code"] = "2";
				return $xmlcnt;
			}
			$aclval = $user->get('id');
		}
		elseif($version->RELEASE == 1.6) // if joomla 1.6 
		{ 	
			$lang =& JFactory::getLanguage();
			$extension = 'com_users';
			$base_dir = JPATH_SITE;
			$language_tag = 'en-GB';
			$lang->load($extension, $base_dir, $language_tag, true);
			// Initialise the table with JUser.
			$user = new JUser;
			include_once(JPATH_ROOT."/components/com_users/models/registration.php");
			$data['name'] = trim(str_replace("\n","",$post['name']));
			$data['username'] = trim(str_replace("\n","",$post['username']));
			$data['password1'] = trim(str_replace("\n","",$post['password']));
			$data['password2'] = trim(str_replace("\n","",$post['password']));
			$data['email1'] = trim(str_replace("\n","",$post['email']));
			$data['email2'] = trim(str_replace("\n","",$post['email']));
			
			$usermodel =new UsersModelRegistration();
			
			if (!$usermodel->register($data))
			{
				$xmlcnt["code"] = "2";
				return $xmlcnt;
			}
			$sql = "select max(id) FROM #__users";
			$db->setQuery($sql);
			$aclval = $db->loadResult();
		}
		else //if joomla 1.7 
		{
			//echo "joomla17";exit;				
			$lang =& JFactory::getLanguage();
			$extension = 'com_users';
			$base_dir = JPATH_SITE;
			$language_tag = 'en-GB';
			$lang->load($extension, $base_dir, $language_tag, true);
			// Initialise the table with JUser.
			$user = new JUser;
			include_once(JPATH_ROOT."/components/com_users/models/registration.php");
			$data['name'] = trim(str_replace("\n","",$post['name']));
			$data['username'] = trim(str_replace("\n","",$post['username']));
			$data['password1'] = trim(str_replace("\n","",$post['password']));
			$data['email1'] = trim(str_replace("\n","",$post['email']));
			
			$usermodel =new UsersModelRegistration();
			
			if (!$usermodel->register($data))
			{
				$xmlcnt["code"] = "2";
				return $xmlcnt;
			}
			$sql = "select max(id) FROM #__users";
			$db->setQuery($sql);
			$aclval = $db->loadResult();
		}
		
		if($opt == 'community' && file_exists($cfile))
		{
			$cversion =JOMSOCIAL_VERSION;
			require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'helpers' .DS. 'image.php');
			
			jimport('joomla.filesystem.file');
			jimport('joomla.utilities.utility');
		
			CFactory::load( 'helpers' , 'image' );
				
			$my			= CFactory::getUser($aclval);
				
			$mainframe =& JFactory::getApplication();
							
			$file		= JRequest::getVar( 'image' , '' , 'FILES' , 'array' );
			$userid		= $my->id;
				
			$config			= CFactory::getConfig();
			$uploadLimit	= (double) $config->get('maxuploadsize');
			$uploadLimit	= ( $uploadLimit * 1024 * 1024 );
		
		
			if($cversion == 1.6 || $cversion == 1.8)
			{
				// @rule: Limit image size based on the maximum upload allowed.
				if( filesize( $file['tmp_name'] ) > $uploadLimit && $uploadLimit != 0 )
				{
					$xmlcnt["invalid_data"] = JText::_('COM_COMMUNITY_VIDEOS_IMAGE_FILE_SIZE_EXCEEDED');
					return $xmlcnt;
				}
				//if( !CImageHelper::isValidType( $file['type'] ) )
				if( !cValidImageType( $file['type'] ) )
				{
					$xmlcnt["invalid_data"] =JText::_('COM_COMMUNITY_IMAGE_FILE_NOT_SUPPORTED');
					return $xmlcnt;
	           	}
	           	
				//if( !CImageHelper::isValid($file['tmp_name'] ) )
				if( !cValidImage($file['tmp_name'] ) )
				{
					$xmlcnt["invalid_data"] = JText::_('COM_COMMUNITY_IMAGE_FILE_NOT_SUPPORTED');
					return $xmlcnt;
				}
				else
				{
					$imageSize		= cImageGetSize( $file['tmp_name'] );
					// @todo: configurable width?
					$imageMaxWidth	= 160;
					
					$lang =& JFactory::getLanguage();
					$lang->load('com_community');
					
					// Get a hash for the file name.
					$profileType = isset($post['type']) ? $post['type'] : 0;
					
					$fileName		= JUtility::getHash( $file['tmp_name'] . time() );
					$hashFileName	= JString::substr( $fileName , 0 , 24 );
					
					$storage = JPATH_ROOT . DS . 'images' . DS . 'avatar';
					$storageImage	= $storage . DS . $hashFileName . cImageTypeToExt( $file['type'] );
					$storageThumbnail = $storage . DS . 'thumb_' . $hashFileName . cImageTypeToExt( $file['type'] );
					$image	= 'images/avatar/' . $hashFileName . cImageTypeToExt( $file['type'] );
					$thumbnail = 'images/avatar/' . 'thumb_' . $hashFileName . cImageTypeToExt( $file['type'] );
					
					$userModel = CFactory::getModel( 'user' );
					
					// Generate full image
					if(!cImageResizePropotional( $file['tmp_name'] , $storageImage , $file['type'] , $imageMaxWidth ) )
					{
						$xmlcnt["code"] = "2";
						return $xmlcnt;
					}
					
					// Generate thumbnail
					if(!cImageCreateThumb( $file['tmp_name'] , $storageThumbnail , $file['type'] ))
					{
						$xmlcnt["code"] = "2";
						return $xmlcnt;
					}	
										
					$userModel->setImage( $userid , $image , 'avatar' );
					$userModel->setImage( $userid , $thumbnail , 'thumb' );
					
					// Update the user object so that the profile picture gets updated.
					$my->set( '_avatar' , $image );
					$my->set( '_thumb'	, $thumbnail );
				}	
				if(isset($post['type']) && ($post['type']) > 0) 
				{	
					$sql= " SELECT cf.fieldcode,cfd.field_id AS id FROM #__community_profiles_fields as cfd LEFT 							JOIN #__community_fields as cf ON cf.id = cfd.field_id "
					     ." WHERE cf.type != 'group' and cfd.parent=".$post['type'];
				}
				else
				{
					$sql=" SELECT fieldcode,id FROM `#__community_fields` WHERE published=1 and registration=1";
				}
				$db->setQuery($sql);
				$fields = $db->loadObjectList();
				
				foreach($fields as $field)
				{
					$fcode=$field->fieldcode;
					$fid=$field->id;
					$fvalue = isset($post[$fcode]) ? $post[$fcode] : "";
					$query="INSERT INTO #__community_fields_values SET
					user_id='".$userid."',
					field_id='".$fid."',
					value='".addslashes($fvalue)."'";
					$db->setQuery($query);
					$db->query();
				}
			}
			else
			{ 	
				// @rule: Limit image size based on the maximum upload allowed.
				if( filesize( $file['tmp_name'] ) > $uploadLimit && $uploadLimit != 0 )
				{
					$xmlcnt["invalid_data"] = JText::_('COM_COMMUNITY_VIDEOS_IMAGE_FILE_SIZE_EXCEEDED');
					return $xmlcnt;
				}
				if( !CImageHelper::isValidType( $file['type'] ) )
				{
					$xmlcnt["invalid_data"] = JText::_('COM_COMMUNITY_IMAGE_FILE_NOT_SUPPORTED');
					return $xmlcnt;
	           	}
	           	if( !CImageHelper::isValid($file['tmp_name'] ) )
				{
					$xmlcnt["invalid_data"] = JText::_('COM_COMMUNITY_IMAGE_FILE_NOT_SUPPORTED');
					return $xmlcnt;
				}
				else
				{
					$config			= CFactory::getConfig();
					$imageMaxWidth	= 160;
					$profileType	= isset($post['type']) ? $post['type'] : 0;
					$fileName		= JUtility::getHash( $file['tmp_name'] . time() );
					$hashFileName	= JString::substr( $fileName , 0 , 24 );
					$multiprofile	=& JTable::getInstance( 'MultiProfile' , 'CTable' );
					$multiprofile->load( $profileType );
					$useWatermark	= $profileType != COMMUNITY_DEFAULT_PROFILE && $config->get('profile_multiprofile') && !empty( $multiprofile->watermark ) ? true : false;
					$storage			= JPATH_ROOT . DS . $config->getString('imagefolder') . DS . 'avatar';
					$storageImage		= $storage . DS . $hashFileName . CImageHelper::getExtension( $file['type'] );
					$storageThumbnail	= $storage . DS . 'thumb_' . $hashFileName . CImageHelper::getExtension( $file['type'] ); 
					$image				= $config->getString('imagefolder') . '/avatar/' . $hashFileName . CImageHelper::getExtension( $file['type'] );
					$thumbnail			= $config->getString('imagefolder') . '/avatar/' . 'thumb_' . $hashFileName . CImageHelper::getExtension( $file['type'] );
					$userModel			= CFactory::getModel( 'user' );
					
					// Only resize when the width exceeds the max.
					if( !CImageHelper::resizeProportional( $file['tmp_name'] , $storageImage , $file['type'] , $imageMaxWidth ) )
					{
						$xmlcnt["invalid_data"] =  JText::sprintf('COM_COMMUNITY_ERROR_MOVING_UPLOADED_FILE' , $storageImage); 
						return $xmlcnt;
					}
					// Generate thumbnail
					if(!CImageHelper::createThumb( $file['tmp_name'] , $storageThumbnail , $file['type'] ))
					{
						$xmlcnt["invalid_data"] =  JText::sprintf('COM_COMMUNITY_ERROR_MOVING_UPLOADED_FILE' , $storageImage); 
						return $xmlcnt;
					}			
					if( $useWatermark )
					{
						// @rule: Before adding the watermark, we should copy the user's original image so that when the admin tries to reset the avatar,
						// it will be able to grab the original picture.
						JFile::copy( $storageImage , JPATH_ROOT . DS . 'images' . DS . 'watermarks' . DS . 'original' . DS . md5( $my->id . '_avatar' ) . CImageHelper::getExtension( $file['type'] ) );
						JFile::copy( $storageThumbnail , JPATH_ROOT . DS . 'images' . DS . 'watermarks' . DS . 'original' . DS . md5( $my->id . '_thumb' ) . CImageHelper::getExtension( $file['type'] ) );
					
						$watermarkPath	= JPATH_ROOT . DS . JString::str_ireplace('/' , DS , $multiprofile->watermark);
					
						list( $watermarkWidth , $watermarkHeight )	= getimagesize( $watermarkPath );
						list( $avatarWidth , $avatarHeight ) 		= getimagesize( $storageImage );
						list( $thumbWidth , $thumbHeight ) 		= getimagesize( $storageThumbnail );

						$watermarkImage		= $storageImage;
						$watermarkThumbnail	= $storageThumbnail;						
					
						// Avatar Properties
						$avatarPosition	= CImageHelper::getPositions( $multiprofile->watermark_location , $avatarWidth , $avatarHeight , $watermarkWidth , $watermarkHeight );

						// The original image file will be removed from the system once it generates a new watermark image.
						CImageHelper::addWatermark( $storageImage , $watermarkImage , 'image/jpg' , $watermarkPath , $avatarPosition->x , $avatarPosition->y );

						//Thumbnail Properties
						$thumbPosition	= CImageHelper::getPositions( $multiprofile->watermark_location , $thumbWidth , $thumbHeight , $watermarkWidth , $watermarkHeight );
					
						// The original thumbnail file will be removed from the system once it generates a new watermark image.
						CImageHelper::addWatermark( $storageThumbnail , $watermarkThumbnail , 'image/jpg' , $watermarkPath , $thumbPosition->x , $thumbPosition->y );

						$my->set( '_watermark_hash' , $multiprofile->watermark_hash );
						$my->save();
					}
					$userModel->setImage( $userid , $image , 'avatar' );
					$userModel->setImage( $userid , $thumbnail , 'thumb' );
					
					// Update the user object so that the profile picture gets updated.
					$my->set( '_avatar' , $image );
					$my->set( '_thumb'	, $thumbnail );
				}	
				if(isset($post['type']) && ($post['type']) > 0) 
				{	
					$sql= " SELECT cf.fieldcode,cfd.field_id AS id FROM #__community_profiles_fields as cfd LEFT JOIN #__community_fields as cf ON cf.id = cfd.field_id "
					     ." WHERE cf.type != 'group' and cfd.parent=".$post['type'];
				}
				else
				{
					$sql=" SELECT fieldcode,id FROM `#__community_fields` WHERE published=1 and registration=1";
				}
				$db->setQuery($sql);
				$fields = $db->loadObjectList();
				
				foreach($fields as $field)
				{
					$fcode=$field->fieldcode;
					$fid=$field->id;
					$fvalue = isset($post[$fcode]) ? $post[$fcode] : "";
					$query="INSERT INTO #__community_fields_values SET
					user_id='".$userid."',
					field_id='".$fid."',
					value='".addslashes($fvalue)."'";
					$db->setQuery($query);
					$db->query();
				}
			}		
		}
		
		// store kunena profile in kunena table...
		if($opt == 'kunena')
		{ 
			$file = JRequest::getVar('image', '', 'files', 'array');
			$ext = JFile::getExt($file['name']);
			$commanpath = JPATH_SITE.DS.'media'.DS.'kunena'.DS.'avatars'.DS;
			$filename = 'users'.DS.'avatar'.$aclval.'.'.$ext;
			$thumbArray = array('resized'.DS.'size36'=>36,'resized'.DS.'size72'=>72,'resized'.DS.'size90'=>90,'resized'.DS.'size144'=>144,'resized'.DS.'size200'=>200);
			
			if(JFile::upload($file['tmp_name'], $commanpath.$filename))
			{
				$image=new SimpleImage();
				foreach($thumbArray AS $path => $size)
				{
					copy($commanpath.$filename,$commanpath.$path.$filename);
					$image->load($commanpath.$path.$filename);
					$width=$image->getWidth();
					$height=$image->getheight();
					if($width>$height)
					{
						$image->resizeToWidth($size);
					}
					else
					{
						$image->resizeToHeight($size);
					}
					$image->save($commanpath.$path.$filename);
					$clearUploads=true;
				}
			}
				
			$ins_query = "INSERT INTO #__kunena_users SET userid='".$aclval."',
						avatar='".$filename."'";
			$db->setQuery($ins_query);
			$db->query();	
		}
		
		// if joomla 1.5 
		if(JOOMLA15)
		{
			// Send registration confirmation mail
			$password = JRequest::getString('password', '', 'post', JREQUEST_ALLOWRAW);
			$password = preg_replace('/[\x00-\x1F\x7F]/', '', $password); //Disallow control chars in the email
			//$uobj = new UserController;
			$this->_sendMail($user, $password);
		}
		$xmlcnt["code"] = "1";
		return $xmlcnt;
	}
	function _sendMail(&$user, $password)
	{
		global $mainframe;

		$db		=& JFactory::getDBO();

		$name 		= $user->get('name');
		$email 		= $user->get('email');
		$username 	= $user->get('username');

		$usersConfig 	= &JComponentHelper::getParams( 'com_users' );
		$sitename 		= $mainframe->getCfg( 'sitename' );
		$useractivation = $usersConfig->get( 'useractivation' );
		$mailfrom 		= $mainframe->getCfg( 'mailfrom' );
		$fromname 		= $mainframe->getCfg( 'fromname' );
		$siteURL		= JURI::base();

		$subject 	= sprintf ( JText::_( 'Account details for' ), $name, $sitename);
		$subject 	= html_entity_decode($subject, ENT_QUOTES);

		if ( $useractivation == 1 ){
			$lang =& JFactory::getLanguage();
			$lang->load('com_user');
			$message = sprintf ( JText::_( 'SEND_MSG_ACTIVATE' ), $name, $sitename, $siteURL."index.php?option=com_user&task=activate&activation=".$user->get('activation'), $siteURL, $username, $password);
		} else {
			$message = sprintf ( JText::_( 'SEND_MSG' ), $name, $sitename, $siteURL);
		}

		$message = html_entity_decode($message, ENT_QUOTES);

		//get all super administrator
		$query = 'SELECT name, email, sendEmail' .
				' FROM #__users' .
				' WHERE LOWER( usertype ) = "super administrator"';
		$db->setQuery( $query );
		$rows = $db->loadObjectList();

		// Send email to user
		if ( ! $mailfrom  || ! $fromname ) {
			$fromname = $rows[0]->name;
			$mailfrom = $rows[0]->email;
		}

		JUtility::sendMail($mailfrom, $fromname, $email, $subject, $message);

		// Send notification to all administrators
		$subject2 = sprintf ( JText::_( 'Account details for' ), $name, $sitename);
		$subject2 = html_entity_decode($subject2, ENT_QUOTES);

		// get superadministrators id
		foreach ( $rows as $row )
		{
			if ($row->sendEmail)
			{
				$message2 = sprintf ( JText::_( 'SEND_MSG_ADMIN' ), $row->name, $sitename, $name, $email, $username);
				$message2 = html_entity_decode($message2, ENT_QUOTES);
				JUtility::sendMail($mailfrom, $fromname, $row->email, $subject2, $message2);
			}
		}
	}
	function logout()
   	{
		$mainframe = JFactory :: getApplication();
		
		$userid=JRequest::getInt('userid','request');
		
		$error = $mainframe->logout($userid);
		
		if($error == '1')
		{
			$xmlcnt["code"] = "1";
		}
		else
		{
			$xmlcnt["code"] = "2";
		}
		
		return $xmlcnt;
	}
	var $_namespace	= 'com_ijoomer.reset.';
	
	function requestreset() {
		
		$xmlcnt=array();
		
		if (!isset($HTTP_RAW_POST_DATA))
		{
   			$HTTP_RAW_POST_DATA = file_get_contents("php://input");
   		}
   		if(empty($HTTP_RAW_POST_DATA))
   		{
   			$xmlcnt['code'] = "2";
   			return $xmlcnt;
   		}
		
   		$data = array();
   		
   		$doc = new DOMDocument();
		$doc->loadXML( $HTTP_RAW_POST_DATA);
		$reg_data = $doc->getElementsByTagName( "data" );
		
		foreach( $reg_data as $reg )
		{
			$user_nm = $reg->getElementsByTagName( "email" );
			$email = $user_nm->item(0)->nodeValue;
		
		}
		
		jimport('joomla.mail.helper');
		jimport('joomla.user.helper');

		$db = &JFactory::getDBO();

		// Make sure the e-mail address is valid
		if (!JMailHelper::isEmailAddress($email))
		{
			$xmlcnt["code"] = "4";
			return $xmlcnt;
		}

		// Build a query to find the user
		$query	= 'SELECT id FROM #__users'
				. ' WHERE email = '.$db->Quote($email)
				. ' AND block = 0';

		$db->setQuery($query);

		// Check the results
		if (!($id = $db->loadResult()))
		{
			$xmlcnt["code"] = "4";
			return $xmlcnt;
		}

		// Generate a new token
		$token = JUtility::getHash(JUserHelper::genRandomPassword());
		$salt = JUserHelper::getSalt('crypt-md5');
		$hashedToken = md5($token.$salt).':'.$salt;

		$query	= 'UPDATE #__users'
				. ' SET activation = '.$db->Quote($hashedToken)
				. ' WHERE id = '.(int) $id
				. ' AND block = 0';

		$db->setQuery($query);

		// Save the token
		if (!$db->query())
		{
			$xmlcnt["code"] = "2";
			return $xmlcnt;
		}

		// Send the token to the user via e-mail
		if (!$this->_sendConfirmationMail($email, $token))
		{
			$xmlcnt["code"] = "2";
			return $xmlcnt;
		}

		$xmlcnt["code"] = "1";
		return $xmlcnt;
	}
	
	function confirmReset()
	{
		global $mainframe;
		$xmlcnt=array();
		if (!isset($HTTP_RAW_POST_DATA))
		{
   			$HTTP_RAW_POST_DATA = file_get_contents("php://input");
   		}
   		if(empty($HTTP_RAW_POST_DATA))
   		{
   			$xmlcnt['code'] = "2";
   			return $xmlcnt;
   		}
		$data = array();
   		$doc = new DOMDocument();
		$doc->loadXML( $HTTP_RAW_POST_DATA);
		$reg_data = $doc->getElementsByTagName( "data" );
		
		foreach( $reg_data as $reg )
		{
			$token_v = $reg->getElementsByTagName( "token" );
			$token = $token_v->item(0)->nodeValue;
			
			$username_v = $reg->getElementsByTagName( "email" );
			$username = $username_v->item(0)->nodeValue;
		}

		jimport('joomla.user.helper');

		if(strlen($token) != 32) 
		{
			$xmlcnt["code"] = "5";
			return $xmlcnt;
		}

		$db	= &JFactory::getDBO();
		$db->setQuery('SELECT id, activation FROM #__users WHERE block = 0 AND username = '.$db->Quote($username));
		$row = $db->loadObject();
	
		// Verify the token
		if (!($row = $db->loadObject()))
		{
			$xmlcnt["code"] = "4";
			return $xmlcnt;
		}

		$parts	= explode( ':', $row->activation );
		$crypt	= $parts[0];
		if (!isset($parts[1])) 
		{
			$xmlcnt["code"] = "5";
			return $xmlcnt;
		}
		$salt	= $parts[1];
		$testcrypt = JUserHelper::getCryptedPassword($token, $salt);

		// Verify the token
		if (!($crypt == $testcrypt))
		{
			$xmlcnt["code"] = "5";
			return $xmlcnt;
		}

		// Push the token and user id into the session
		$xmlcnt["code"] = "1";
		$xmlcnt["userid"] = $row->id;
		$xmlcnt["crypt"] = $crypt.':'.$salt;
		return $xmlcnt;
	}
	
	function completeReset()
	{
		$xmlcnt=array();
		
		if (!isset($HTTP_RAW_POST_DATA))
		{
   			$HTTP_RAW_POST_DATA = file_get_contents("php://input");
   		}
   		if(empty($HTTP_RAW_POST_DATA))
   		{
   			$xmlcnt['code'] = "2";
   			return $xmlcnt;
   		}
		
   		$data = array();
   		
   		$doc = new DOMDocument();
		$doc->loadXML( $HTTP_RAW_POST_DATA);
		$reg_data = $doc->getElementsByTagName( "data" );
		
		foreach( $reg_data as $reg )
		{
			$token_v = $reg->getElementsByTagName( "crypt" );
			$token = $token_v->item(0)->nodeValue;
			
			$user_id_v = $reg->getElementsByTagName( "userid" );
			$user_id = $user_id_v->item(0)->nodeValue;
			
			$password_v = $reg->getElementsByTagName( "password" );
			$password1 = $password_v->item(0)->nodeValue;
		
		}
		
		jimport('joomla.user.helper');
		global $mainframe;

		// Make sure that we have a pasword
		if ( ! $password1 )
		{
			$xmlcnt["code"] = "2";
			return $xmlcnt;
		}

		// Get the necessary variables
		$db			= &JFactory::getDBO();
		$id			= $user_id;
		$salt		= JUserHelper::genRandomPassword(32);
		$crypt		= JUserHelper::getCryptedPassword($password1, $salt);
		$password	= $crypt.':'.$salt;

		// Get the user object
		$user = new JUser($id);

		// Fire the onBeforeStoreUser trigger
		JPluginHelper::importPlugin('user');
		$dispatcher =& JDispatcher::getInstance();
		$dispatcher->trigger('onBeforeStoreUser', array($user->getProperties(), false));

		// Build the query
		$query 	= 'UPDATE #__users'
				. ' SET password = '.$db->Quote($password)
				. ' , activation = ""'
				. ' WHERE id = '.(int) $id
				. ' AND activation = '.$db->Quote($token)
				. ' AND block = 0';

		$db->setQuery($query);
		
		// Save the password
		if (!$result = $db->query())
		{
			$xmlcnt["code"] = "2";
			return $xmlcnt;
		}

		// Update the user object with the new values.
		$user->password			= $password;
		$user->activation		= '';
		$user->password_clear	= $password1;

		// Fire the onAfterStoreUser trigger
		$dispatcher->trigger('onAfterStoreUser', array($user->getProperties(), false, $result,''));
		
		$xmlcnt["code"] = "1";
		return $xmlcnt;
	}

	function _sendConfirmationMail($email, $token)
	{
		$config		= &JFactory::getConfig();
		$uri		= &JFactory::getURI();
		$url		= JRoute::_('index.php?option=com_user&view=reset&layout=confirm',true,-1);
		$sitename	= $config->getValue('sitename');

		// Set the e-mail parameters
		$lang =& JFactory::getLanguage();
		$lang->load('com_user');
		
		$from		= $config->getValue('mailfrom');
		$fromname	= $config->getValue('fromname');
		$subject	= sprintf(JText::_('PASSWORD_RESET_CONFIRMATION_EMAIL_TITLE'), $sitename);
		$body		= sprintf(JText::_( 'PASSWORD_RESET_CONFIRMATION_EMAIL_TEXT'), $sitename, $token, $url);

		// Send the e-mail
		if (!JUtility::sendMail($from, $fromname, $email, $subject, $body))
		{
			$xmlcnt["code"] = "4";
			return $xmlcnt;
		}
		return true;
	}
}
?>
