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

jimport('joomla.application.component.model');
jimport('joomla.error.log');

class processModelprocess extends JModel
{
	
	var $session_pass;
	var $ijoomer_user_id;
	
	function __construct()
	{
		parent::__construct(); 
		$mainframe =& JFactory::getApplication();
 		$db = & JFactory::getDBO();
		$sessionid =JRequest::getVar('sessionid','');
		
		$query= "SELECT userid FROM #__session WHERE session_id ='".$sessionid."' AND guest='0'  ORDER BY time DESC limit 0, 1"; 
		$db->setQuery($query);
		$result = $db->loadResult();
		
		$my =& JFactory::getUser($result);
		
		
		if($my->id > 0){
			$this->session_pass = 1;
			$this->ijoomer_user_id = $my->id;
			$mainframe->setUserState( "com_ijoomer.ijoomer_user_id", $this->ijoomer_user_id );
			$_SESSION["ijoomer_user_id"] = $this->ijoomer_user_id;
		}else{
			$this->session_pass = 0;
			$this->ijoomer_user_id = "";
			$mainframe->setUserState( "com_ijoomer.ijoomer_user_id", "");
			unset($_SESSION["ijoomer_user_id"]);
		}
		
		/*$query= "SELECT userid FROM #__session WHERE session_id ='".$sessionid."' AND guest='0'  ORDER BY time DESC limit 0, 1"; 
		$db->setQuery($query);
		$result = $db->loadObjectlist(); 
		if(count($result)>0){
			$this->session_pass = 1;
			$this->ijoomer_user_id = $result[0]->userid;
			$mainframe->setUserState( "com_ijoomer.ijoomer_user_id", $this->ijoomer_user_id );
			//$_SESSION["ijoomer_user_id"] = $this->ijoomer_user_id;
		}else{
			$this->session_pass = 0;
			$this->ijoomer_user_id = "";
			$mainframe->setUserState( "com_ijoomer.ijoomer_user_id", "");
			//unset($_SESSION["ijoomer_user_id"]);
		}*/
	}
	
	function checkcomponent($plug){
		$db = & JFactory::getDBO();
		$query="select plugin_option from #__ijoomer_plugins where plugin_classname='".$plug."'";
		$db->setQuery($query);
		$option = $db->loadResult();
		if($option==""){
			return 0;
		}else{
			$help = new Adminhelper();
			$component = $help->getComponent($option);
			if($component == 0){
				return 2;
			}
		}
		return 1;
	}
	
	function checkSession()
	{
		$task = JRequest::getVar('ptask','display');
		
		$allow_task = array('login','register','get_profile_types','all_album_list','photo_paging','video_cat_list','all_video_paging');
		
				
		if($this->session_pass == 1 || in_array($task,$allow_task))
		{
			return true;
		}
		else
		{
			$xmlcnt["code"] = 3;
			$app = &JFactory::getApplication();
			$app->ijoomer_debug->endProcessTime = microtime(true)*1000;
			$app->ijoomer_debug->processTimeDiff = $app->ijoomer_debug->endProcessTime - $app->ijoomer_debug->startTime;
			$app->ijoomer_debug->requestUrl=$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
			$this->generateXML($xmlcnt); 
		}
	}
	
	function process()
	{ 
		global $context; 
		$mainframe = JFactory::getApplication();
		
		$plg_name = JRequest::getVar('plg_name');
		$view = JRequest::getVar('pview');
		$task = JRequest::getVar('ptask','display');
		$data = JRequest::getVar('data');
		
		if($plg_name!="iuser"){
			//check for component availability //currently for developer use only
			$check_aval = $this->checkcomponent($plg_name);
			if($check_aval==0){
				$arr['ERROR'] = 'Wrong plugin request';
				return $arr; 
			}
			if($check_aval==2){
				$arr['ERROR'] = 'Wrong component request';
				return $arr; 
			}
		}
		
		$file_name = JPATH_COMPONENT_SITE.DS.'plugins'.DS.$plg_name.DS.$view.".php"; 
		$arr = array();
		if(!file_exists($file_name))
		{
			$arr['ERROR'] = 'Wrong Request';
			return $arr; 
		}
		
		//$cfgfile = JPATH_COMPONENT_SITE.DS.'plugins'.DS.$plg_name.DS.$plg_name.".cfg.php";
		
		include_once(JPATH_COMPONENT_SITE.DS."class".DS."class.image-resize.php");
		include_once(JPATH_COMPONENT_SITE.DS."class".DS."resize.class.php");
		
		//if(file_exists($cfgfile))
		//include_once($cfgfile);
		if($plg_name!="iuser"){
			$this->getconfig($plg_name);
		}else{
			if(GC_REGISTRATION=="community"){
				$this->getconfig("jomsocial");
			}
		}
		
		switch ($plg_name)
		{
			case 'jomsocial' :
				if(JOMSOC_CHECK_SESSION)
				{
					$this->checkSession();
				}
				jimport('joomla.utilities.date');
				require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'views' .DS. 'views.php');
				require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'views' .DS.'inbox' .DS. 'view.html.php');
				require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' .DS. 'core.php');
				require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' .DS. 'template.php');
				require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'helpers' .DS. 'time.php');
				require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'helpers' .DS. 'url.php');
				require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'controllers' . DS . 'controller.php');
				
				$lang =& JFactory::getLanguage();
				$lang->load('com_community');
				$plugin_path = JPATH_COMPONENT_SITE.DS.'plugins';
				$lang->load('jomsocial',$plugin_path.DS.'jomsocial', $lang->getTag(), true);
				if(file_exists(JPATH_COMPONENT_SITE.DS.'plugins'.DS.'jomsocial'.DS."helper.php"))
				{
					require_once(JPATH_COMPONENT_SITE.DS.'plugins'.DS.'jomsocial'.DS."helper.php"); 
				}	
				break;
			case 'poll' :
				if(POLL_CHECK_SESSION)
				{
					$this->checkSession();
				}
				break;
			case 'jreview' :
				if(JREVIEW_CHECK_SESSION)
				{
					$this->checkSession();
				}
				break;
			case 'iuser' :
				if(file_exists(JPATH_COMPONENT_SITE.DS.'plugins'.DS.'jomsocial'.DS."helper.php"))
				{
					require_once(JPATH_COMPONENT_SITE.DS.'plugins'.DS.'jomsocial'.DS."helper.php"); 
				}
				break;
			case 'ichat' :
				break;
			case 'iblog' :
				if(IBLOG_CHECK_SESSION)
				{
					$this->checkSession();
				}
				$lang =& JFactory::getLanguage();
				$lang->load('com_myblog');
				break;
			case 'iforum':
				$lang =& JFactory::getLanguage();
				if(!$lang->load('com_kunena')){
					$lang->load('com_kunena',JPATH_BASE.DS.'components'.DS.'com_kunena');
				}
				break;		
		}	
		include_once $file_name;						
		$class_obj = new $view;
		if(method_exists($class_obj,$task)){
			$arr = $class_obj->$task($data);
		}
		else
		{
			$arr['ERROR'] = 'Wrong Request';
		}
		return $arr;
	}
	
	function getconfig($class_name){
		$db = JFactory :: getDBO();	
		$query="select * from #__ijoomer_".$class_name."_config";
		$db->setQuery($query);
		$rows=$db->loadObjectlist();
		$cfg=array();
			
		for($i=0;$i<count($rows);$i++){
			define($rows[$i]->config_name,$rows[$i]->config_value);
		}
		return true;
	}
	
	function generateXML($arr=array())
	{
		$app = &JFactory::getApplication();
		//echo "<pre>";print_r($app);exit;
		$log = &JLog::getInstance('com_ijoomer.log.php');
		/*
		 * xml generate 
		 */
  		header ("content-type: text/xml");
		$xmlcnt = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		$xmlcnt .="<data>";
		$xmlcnt .= $this->recursiveArr($arr);
		$xmlcnt .="</data>";
		echo $xmlcnt;
		/*
		 * logging into file
		 */
		$app->ijoomer_debug->endXMLGenTime=microtime(true)*1000;
		$app->ijoomer_debug->xmlTimeDiff=$app->ijoomer_debug->endXMLGenTime - $app->ijoomer_debug->endProcessTime;
		$app->ijoomer_debug->commentText= $app->ijoomer_debug->processTimeDiff.":".$app->ijoomer_debug->xmlTimeDiff;
		if($app->ijoomer_debug->processTimeDiff>1000 OR $app->ijoomer_debug->xmlTimeDiff>500){
			$log->addEntry(array('status' => $app->ijoomer_debug->requestUrl,'comment' => $app->ijoomer_debug->commentText));
		}
		
		exit;
	}
	/*function recursiveArr($arr=array())
	{
		$xmlcnt = "";
		foreach($arr as $key=>$value)
		{
			if(is_numeric($key))
				$xmlcnt .= $this->recursiveArr($value);
			else
			{
			$xmlcnt .= "<$key>";
			if(is_array($value))
				$xmlcnt .= $this->recursiveArr($value);
			else
				$xmlcnt .= $value;
			$xmlcnt .= "</$key>\n";
			}
		}
		return $xmlcnt;
	}*/

function recursiveArr($arr=array())
	{
		$device = JRequest::getVar('device');
		$xmlcnt = "";
		foreach($arr as $key=>$value)
		{
			if(is_numeric($key))
				$xmlcnt .= $this->recursiveArr($value);
			else
			{//echo $key.'<br />';
				$xmlcnt .= "<$key>";
				if(is_array($value))
				{
					$xmlcnt .= $this->recursiveArr($value);
				}
				else
				{ 
					if($device == 'aa')
					{
						if($key == 'content' || $key == 'video')
						{
							preg_match_all('/<\img>+[0-9 a-z_?*=\":\-\/\.#\,<>\\n\\r\\t]+<\/img>/smi', $value, $matches_img);
							preg_match_all('/<\bigimg>+[0-9 a-z_?*=\":\-\/\.#\,<>\\n\\r\\t]+<\/bigimg>/smi', $value, $matches_big);
							preg_match_all('/<\video_icon>+[0-9 a-z_?*=\":\-\/\.#\,<>\\n\\r\\t]+<\/video_icon>/smi', $value, $matches_video_icon);
							if(is_array($matches_img) || is_array($matches_big) || is_array($matches_video_icon))
							{
								$xmlcnt .= $value;
							}
							else
							{
								$xmlcnt .= '<![CDATA['.$value.']]>';		
							}
						}
						else
						{
							$xmlcnt .= '<![CDATA['.$value.']]>';
						}
					}
					else 
					{
						$xmlcnt .= $value;
					}
				}	
				$xmlcnt .= "</$key>\n";
			}
		}//exit;
		return $xmlcnt;
	}
}
?>
