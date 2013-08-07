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

jimport( 'joomla.application.component.controller' );
 
class configController extends JController
{
	function __construct( $default = array())
	{
		parent::__construct( $default );
	}
	function config()
	{
		$model = $this->getModel('config');
		$arr = $model->process();
		$model->generateXML($arr);
	}
	
	function screens2(){	
		
		$model = $this->getModel('config');
		$app_list=$model->getAppManagerList();
		$xmlcnt["code"]=1;
		foreach($app_list as $key=>$value){
			$xmlcnt[$key]["tab"]=$value["screen_value"];
		} 
		$model1 = $this->getModel('process','processModel');
		//echo "<pre>";print_r($model1);exit;
		$model1->generateXML($xmlcnt);  
	}
	
	function screens()
	{
		$db =& JFactory::getDBO();
		$type = JRequest::getVar("device",null,"REQUEST");
		$model = JRequest::getVar("model",null,"REQUEST");
		//$xmlcnt["code"] = 1;
		
		if($type == 'A')
		{
			$query="select count(*) from #__ijoomer_plist WHERE type = 'A' ";
			$db->setQuery($query);
			$rows=$db->loadResult();
					
			if($rows == 0)
			{
				if(!isset($HTTP_RAW_POST_DATA))
				{
					$HTTP_RAW_POST_DATA = file_get_contents("php://input");
				}
		
				if(empty($HTTP_RAW_POST_DATA))
				{
					//$xmlcnt["code"] = 2;
				}
				
				$doc = new DOMDocument();
				$doc->loadXML( $HTTP_RAW_POST_DATA );
				
				$data = $doc->getElementsByTagName( "data" );
				header("Content-type: text/html");
	
			
				foreach($data as $xmldata)
				{
					$type = $xmldata->getElementsByTagName("type");
					$type =	$type->item(0)->nodeValue;
					$view = $xmldata->getElementsByTagName("view");
					
					foreach ($view as $listview)
					{
						$key = $listview->getElementsByTagName("key");
						$key = $key->item(0)->nodeValue;
		
						$string = $listview->getElementsByTagName("string");
						$string = $string->item(0)->nodeValue;
						
						$parent = $listview->getElementsByTagName("parent");
						$parent = $parent->item(0)->nodeValue;
						
						$title = $listview->getElementsByTagName("title");
						$title = $title->item(0)->nodeValue;
		
						if($parent==""){
							
							$query="select count(*) from #__ijoomer_plist where type=".$db->Quote($type)." and parent=''";
							$db->setQuery($query);
							$cnts=$db->loadResult();
							
							if($cnts==0){
								$ordering=1;
							}else{
								$ordering=$cnts+1;
							}
							
							$query="insert into #__ijoomer_plist(`plist_name`,`plist_value`,`plist_title`,`type`,`published`,`ordering`) value(".$db->Quote($string).",".$db->Quote($key).",".$db->Quote($title).",".$db->Quote($type).",1,".$db->Quote($ordering).")";
							$db->setQuery($query);
							$db->Query();
						}else{
							$pids=array();	
							$pids=explode(",",$parent);
							//$diff="";
							for($i=0;$i<count($pids);$i++){
								$query="select count(*) from #__ijoomer_plist where type=".$db->Quote($type)." and parent=".$db->Quote($pids[$i])."";
								$db->setQuery($query);
								$cnts=$db->loadResult();
		
								if($cnts==0){
									$ordering=1;
								}else{
									$ordering=$cnts+1;
								}
								/*if($i>0){
									$diff .="@@@@@";
								}*/
								$query="insert into #__ijoomer_plist(`plist_name`,`plist_value`,`plist_title`,`parent`,`type`,`published`,`ordering`) value(".$db->Quote($string).",".$db->Quote($key).",".$db->Quote($title).",".$db->Quote($pids[$i]).",".$db->Quote($type).",1,".$db->Quote($ordering).")";
								$db->setQuery($query);
								$db->Query();		
							}
						}
						//$sql = "update #__ijoomer_plist set `published`=1 where `type`='".$type."' and `plist_value`='".$key."'";
						//$db->setQuery($sql);
						//$db->query();
					}
					
				}
			}
		}
		
		if($type == 'I')
		{
			$query="select count(*) from #__ijoomer_plist WHERE type = 'I' ";
			$db->setQuery($query);
			$rows=$db->loadResult();
			
			if($rows == 0)
			{
				if(!isset($HTTP_RAW_POST_DATA))
				{
					$HTTP_RAW_POST_DATA = file_get_contents("php://input");
				}
	
				if(empty($HTTP_RAW_POST_DATA))
				{
					//$xmlcnt["code"] = 2;
				}
			
				$doc = new DOMDocument();
				$doc->loadXML( $HTTP_RAW_POST_DATA );
			
				$data = $doc->getElementsByTagName( "data" );
				header("Content-type: text/html");

		
				foreach($data as $xmldata)
				{
					$type = $xmldata->getElementsByTagName("type");
					$type =	$type->item(0)->nodeValue;
					$view = $xmldata->getElementsByTagName("view");
				
					foreach ($view as $listview)
					{
						$key = $listview->getElementsByTagName("key");
						$key = $key->item(0)->nodeValue;
	
						$string = $listview->getElementsByTagName("string");
						$string = $string->item(0)->nodeValue;
					
						$parent = $listview->getElementsByTagName("parent");
						$parent = $parent->item(0)->nodeValue;
					
						$title = $listview->getElementsByTagName("title");
						$title = $title->item(0)->nodeValue;
	
						if($parent==""){
						
							$query="select count(*) from #__ijoomer_plist where type=".$db->Quote($type)." and parent=''";
							$db->setQuery($query);
							$cnts=$db->loadResult();
						
							if($cnts==0){
								$ordering=1;
							}else{
								$ordering=$cnts+1;
							}
						
							$query="insert into #__ijoomer_plist(`plist_name`,`plist_value`,`plist_title`,`type`,`published`,`ordering`) value(".$db->Quote($string).",".$db->Quote($key).",".$db->Quote($title).",".$db->Quote($type).",1,".$db->Quote($ordering).")";
							$db->setQuery($query);
							$db->Query();
						}else{
							$pids=array();	
							$pids=explode(",",$parent);
							//$diff="";
							for($i=0;$i<count($pids);$i++){
								$query="select count(*) from #__ijoomer_plist where type=".$db->Quote($type)." and parent=".$db->Quote($pids[$i])."";
								$db->setQuery($query);
								$cnts=$db->loadResult();
	
								if($cnts==0){
									$ordering=1;
								}else{
									$ordering=$cnts+1;
								}
								/*if($i>0){
									$diff .="@@@@@";
								}*/
								$query="insert into #__ijoomer_plist(`plist_name`,`plist_value`,`plist_title`,`parent`,`type`,`published`,`ordering`) value(".$db->Quote($string).",".$db->Quote($key).",".$db->Quote($title).",".$db->Quote($pids[$i]).",".$db->Quote($type).",1,".$db->Quote($ordering).")";
								$db->setQuery($query);
								$db->Query();		
							}
						}
						//$sql = "update #__ijoomer_plist set `published`=1 where `type`='".$type."' and `plist_value`='".$key."'";
						//$db->setQuery($sql);
						//$db->query();
					}
				
				}
			}
		}		
		/*$model1 = $this->getModel('process','processModel');
		$model1->generateXML($xmlcnt);*/
			
		
		$model = $this->getModel('config');
		$app_list=$this->getAppManagerList();
	}
	
	function push_plist()
	{

		$db =& JFactory::getDBO();
		
		$type = JRequest::getVar("device",null,"REQUEST");
		
		$xmlcnt["code"] = 1;
		
		if(!isset($HTTP_RAW_POST_DATA))
		{
			$HTTP_RAW_POST_DATA = file_get_contents("php://input");
		}

		if(empty($HTTP_RAW_POST_DATA))
		{
			$xmlcnt["code"] = 2;
		}
		
		$doc = new DOMDocument();
		$doc->loadXML( $HTTP_RAW_POST_DATA );
		
		
		$data = $doc->getElementsByTagName( "data" );
		header("Content-type: text/html");

		
		foreach($data as $xmldata)
		{
			$type = $xmldata->getElementsByTagName("type");
			$type =	$type->item(0)->nodeValue;
			$view = $xmldata->getElementsByTagName("view");
			
			foreach ($view as $listview)
			{
				$key = $listview->getElementsByTagName("key");
				$key = $key->item(0)->nodeValue;

				$string = $listview->getElementsByTagName("string");
				$string = $string->item(0)->nodeValue;
				
				$parent = $listview->getElementsByTagName("parent");
				$parent = $parent->item(0)->nodeValue;
				
				$title = $listview->getElementsByTagName("title");
				$title = $title->item(0)->nodeValue;

				if($parent==""){
					
					$query="select count(*) from #__ijoomer_plist where type=".$db->Quote($type)." and parent=''";
					$db->setQuery($query);
					$cnts=$db->loadResult();
					
					if($cnts==0){
						$ordering=1;
					}else{
						$ordering=$cnts+1;
					}
					
					$query="insert into #__ijoomer_plist(`plist_name`,`plist_value`,`plist_title`,`type`,`published`,`ordering`) value(".$db->Quote($string).",".$db->Quote($key).",".$db->Quote($title).",".$db->Quote($type).",1,".$db->Quote($ordering).")";
					$db->setQuery($query);
					$db->Query();
				}else{
					$pids=array();	
					$pids=explode(",",$parent);
					//$diff="";
					for($i=0;$i<count($pids);$i++){
						$query="select count(*) from #__ijoomer_plist where type=".$db->Quote($type)." and parent=".$db->Quote($pids[$i])."";
						$db->setQuery($query);
						$cnts=$db->loadResult();

						if($cnts==0){
							$ordering=1;
						}else{
							$ordering=$cnts+1;
						}
						
						/*if($i>0){
							$diff .="@@@@@";
						}*/
							
						$query="insert into #__ijoomer_plist(`plist_name`,`plist_value`,`plist_title`,`parent`,`type`,`published`,`ordering`) value(".$db->Quote($string).",".$db->Quote($key).",".$db->Quote($title).",".$db->Quote($pids[$i]).",".$db->Quote($type).",1,".$db->Quote($ordering).")";
						$db->setQuery($query);
						$db->Query();		
					}
				}
				//$sql = "update #__ijoomer_plist set `published`=1 where `type`='".$type."' and `plist_value`='".$key."'";
				//$db->setQuery($sql);
				//$db->query();
				
			}
			
		}
		
		$model1 = $this->getModel('process','processModel');
		$model1->generateXML($xmlcnt);
			
		return $xmlcnt;
	}
	
	function show_list()
	{
		$db =& JFactory::getDBO();
		
		$type = JRequest::getVar("type","I");	
		
		$xmlcnt['code'] = 1;
		
		$sql = "SELECT count(*) FROM #__ijoomer_plist where type=".$db->Quote($type).""; 
		$db->setQuery($sql);
		$res = $db->loadResult();
		
		if($res==0)
		{
			$xmlcnt['response'] = 0;
		}
		else 
		{
			$xmlcnt['response'] = 1;
		}
		$model1 = $this->getModel('process','processModel');
		$model1->generateXML($xmlcnt);
		
		return $xmlcnt;
	}
	
	function getAppManagerList()
	{
		$db = & JFactory::getDBO();
		$type = JRequest::getVar("device",null,"REQUEST");
		$model = JRequest::getVar("model",null,"REQUEST");
		
		$xmlcnt=array();
		
		if($type!=null){
			$xmlcnt["code"]=1;
			
			$query="select * from #__ijoomer_plist where type='".$type."' and parent='' order by ordering";
			$db->setQuery($query);
			$rows=$db->loadObjectlist();
			
			if($type=="B" || $type=="A"){
				$query="select id from #__ijoomer_devices where type='".$type."' and FIND_IN_SET('".$model."',devices)";
			}else{
				$query="select id from #__ijoomer_devices where type='".$type."'";
			}
			$db->setQuery($query);
			$device_id=$db->loadResult();
			
			if($type=="I"){
				$folder="iphone";
			}
			if($type=="A"){
				$folder="android";
			}
			if($type=="B"){
				$folder="bb";
			}
			
			$folder = JURI::base()."images/com_ijoomer/".$folder."/";
			
			
			
			for($i=0;$i<count($rows);$i++){
				
					$xmlcnt["menulist"][$i]["parent"]["menu_key"]=$rows[$i]->plist_value;
					if($type!="I"){
						$xmlcnt["menulist"][$i]["parent"]["title"]=$rows[$i]->plist_title;
					}
					$query="select * from #__ijoomer_display where device_id=".$device_id." and plist_value='".$rows[$i]->plist_value."'"; 
					$db->setQuery($query);
					$display=$db->loadObjectlist();
				
					
					if(count($display) > 0 && $type!="I"){
						
						if($display[0]->show_tab==1){
							$xmlcnt["menulist"][$i]["parent"]["icon"]=$folder.$display[0]->tab_icon;
						}
						
						if($display[0]->show_list==1){
							$xmlcnt["menulist"][$i]["parent"]["tab"]=$folder.$display[0]->list_icon2;
							$xmlcnt["menulist"][$i]["parent"]["tab_onfocus"]=$folder.$display[0]->list_icon;
						}
						
					}
				$xmlcnt["menulist"][$i]["parent"]["children"]=$this->getchildimages($type,$rows[$i]->plist_value,$device_id,$folder);
			}
			//global configuration
			$query="select * from #__ijoomer_config";
			$db->setQuery($query);
			$rows=$db->loadObjectlist();
			
			$cfg=array();
				for($d=0;$d<count($rows);$d++){
					$cfg[$rows[$d]->config_name]=$rows[$d]->config_value;	
				}
				$xmlcnt["configuration"]["global_config"]["login_required"]=$cfg["GC_LOGIN_REQUIRED"];
				$xmlcnt["configuration"]["global_config"]["default_registration"]=$cfg["GC_REGISTRATION"];
				$xmlcnt["configuration"]["global_config"]["remember_password"]=$cfg["GC_REMEMBER_PASSWORD"];
				$xmlcnt["configuration"]["global_config"]["reset_password"]=$cfg["GC_RESET_PASSWORD"];
				$xmlcnt["configuration"]["global_config"]["default_view"]=$cfg["GC_DEFAULT_VIEW"];
				$xmlcnt["configuration"]["global_config"]["use_theme"]=$cfg["GC_SELECT_THEME"];
				$xmlcnt["configuration"]["global_config"]["default_theme"]=$cfg["GC_DEFAULT_THEME"];
			
			
			
			$plugin_path = JPATH_COMPONENT_SITE.DS.'plugins';
			//plugin configuration
			$query="select * from #__ijoomer_plugins";
			$db->setQuery($query);
			$rows=$db->loadObjectlist();
			
			for($i=0;$i<count($rows);$i++){
				$pluginfile = $plugin_path.DS.$rows[$i]->plugin_classname.DS.$rows[$i]->plugin_classname.'.php';
				
				if(file_exists($pluginfile)){
	   	  		   	include_once($pluginfile);
	   	  		   	$class_obj = new $rows[$i]->plugin_classname;
					if(method_exists($class_obj,"getconfig")){
						$xmlcnt["configuration"]["plugin_config"][$rows[$i]->plugin_classname]=$class_obj->getconfig();
					}
	   	  		}
			}
		}else{
			$xmlcnt["code"]=2;
		}
		
		$model1 = $this->getModel('process','processModel');
		$model1->generateXML($xmlcnt);
		return $xmlcnt;
	}
	
	function getchildimages($type,$parent,$device_id,$folder)
	{
				$db = & JFactory::getDBO();
				$xmlcnt=array();
				
				$query="select * from #__ijoomer_plist where type='".$type."' and parent='".$parent."' order by ordering";
				$db->setQuery($query);
				$childs=$db->loadObjectlist();

				for($d=0;$d<count($childs);$d++){
					$query="select * from #__ijoomer_display where device_id=".$device_id." and plist_value='".$childs[$d]->plist_value."'"; 
					$db->setQuery($query);
					$display=$db->loadObjectlist();
					
					$xmlcnt[$d]["child"]["plist_value"]=$childs[$d]->plist_value;
					$xmlcnt[$d]["child"]["title"]=$childs[$d]->plist_name;
					
					if(count($display) > 0){
						if($display[0]->show_tab==1){
							$xmlcnt[$d]["child"]["icon"]=$folder.$display[0]->tab_icon;
						}
							
						if($display[0]->show_list==1){
							if($type=="I"){
								$xmlcnt[$d]["child"]["tab"]=$folder.$display[0]->list_icon;	
							}else{	
								$xmlcnt[$d]["child"]["tab"]=$folder.$display[0]->list_icon2;
								$xmlcnt[$d]["child"]["tab_onfocus"]=$folder.$display[0]->list_icon2;
							}
								
						}
					}
					$temp=$this->getchildimages($type,$childs[$d]->plist_value,$device_id,$folder);
					if(count($temp)){
						$xmlcnt[$d]["child"]["children"]=$temp;
					}
				}
				return $xmlcnt;
	}
}
	
?>