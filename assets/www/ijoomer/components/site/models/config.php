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

class configModelconfig extends JModel
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getAppManagerList2(){
		
		$db = & JFactory::getDBO();
		$query= "SELECT * ".
				"FROM #__ijoomer_application_manager ".
				"WHERE published=1 order by ordering ASC";
		$db->setQuery($query);
		$app_list = $db->loadAssocList();
		
		$help = new Adminhelper();
		
	
		foreach ($app_list as $key=>$value){

			$component = $help->getComponent($value["plugin_option"]);
			$plugin = $help->getPlugin($value['plugin_option']);
			
			if($component == 0 || $plugin == 0){
				unset($app_list[$key]);
			}	
		}
		return $app_list;
	}
	
	function getAppManagerList(){
		
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
					
			/*	$query="select * from #__ijoomer_plist where type='".$type."' and parent='".$rows[$i]->plist_value."' order by ordering";
				$db->setQuery($query);
				$childs=$db->loadObjectlist();

				for($d=0;$d<count($childs);$d++){
					$query="select * from #__ijoomer_display where device_id=".$device_id." and plist_value='".$childs[$d]->plist_value."'"; 
					$db->setQuery($query);
					$display=$db->loadObjectlist();
					
					$xmlcnt["menulist"][$i]["parent"]["children"][$d]["child"]["plist_value"]=$childs[$d]->plist_value;
					$xmlcnt["menulist"][$i]["parent"]["children"][$d]["child"]["title"]=$childs[$d]->plist_title;
					
				if(count($display) > 0){
						if($display[0]->show_tab==1){
							$xmlcnt["menulist"][$i]["parent"]["children"][$d]["child"]["icon"]=$folder.$display[0]->tab_icon;
						}
							
						if($display[0]->show_list==1){
							$xmlcnt["menulist"][$i]["parent"]["children"][$d]["child"]["tab"]=$folder.$display[0]->list_icon2;
							if($type!="I"){
								$xmlcnt["menulist"][$i]["parent"]["children"][$d]["child"]["tab_onfocus"]=$folder.$display[0]->list_icon2;
							}
								
						}
						
						
						
						
					}
					
				}*/
					
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
			
			/*for($i=0;$i<count($rows);$i++){
				$xmlcnt["configuration"]["global_config"][$i]["config"]["name"]=$rows[$i]->config_name;	
				$xmlcnt["configuration"]["global_config"][$i]["config"]["value"]=$rows[$i]->config_value;
			}*/
			
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
				
				/*$query="select * from `#__ijoomer_".$rows[$i]->plugin_classname."_config`";
				$db->setQuery($query);
				$data=$db->loadObjectlist(); 
				for($d=0;$d<count($data);$d++){
					$xmlcnt["configuration"]["plugin_config"][$rows[$i]->plugin_classname][$d]["config"]["name"]=$data[$d]->config_name;	
					$xmlcnt["configuration"]["plugin_config"][$rows[$i]->plugin_classname][$d]["config"]["value"]=$data[$d]->config_value;
				}*/
			}
			
		}else{
			$xmlcnt["code"]=2;
		}
		
		return $xmlcnt;
	}
	
	function getchildimages($type,$parent,$device_id,$folder){
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
					$xmlcnt[$d]["child"]["title"]=$childs[$d]->plist_title;
					
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

	function getAppManagerList_old(){
		
		$db = & JFactory::getDBO();
		$type = JRequest::getVar("device",null,"REQUEST");
		$model = JRequest::getVar("model",null,"REQUEST");
		
		$xmlcnt=array();
		
		if($type!=null){
			$xmlcnt["code"]=1;
			
			$query="select * from #__ijoomer_plist where type='".$type."' order by ordering";
			$db->setQuery($query);
			$rows=$db->loadObjectlist();
			
			if($type=="B"){
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
				
					$xmlcnt["tabs"][$i]["tab"]["plist_value"]=$rows[$i]->plist_value;
					
					$query="select * from #__ijoomer_display where device_id=".$device_id." and plist_value='".$rows[$i]->plist_value."'"; 
					$db->setQuery($query);
					$display=$db->loadObjectlist();
				
					
					if(count($display) > 0){
						if($type=="I"){
							$xmlcnt["tabs"][$i]["tab"]["tab_focus"]=$folder.$display[0]->tab_icon;
						}else{
							$xmlcnt["tabs"][$i]["tab"]["tab_focus"]=$folder.$display[0]->tab_icon;
							$xmlcnt["tabs"][$i]["tab"]["tab_normal"]=$folder.$display[0]->tab_icon2;	
						}
						
						$xmlcnt["tabs"][$i]["tab"]["show_tab"]=$display[0]->show_tab;
						$xmlcnt["tabs"][$i]["tab"]["list"]=$folder.$display[0]->list_icon;
						$xmlcnt["tabs"][$i]["tab"]["show_list"]=$display[0]->show_list;
					}
					
			}
			
			//global configuration
			$query="select * from #__ijoomer_config";
			$db->setQuery($query);
			$rows=$db->loadObjectlist();
			for($i=0;$i<count($rows);$i++){
				$xmlcnt["configuration"]["global_config"][$i]["config"]["name"]=$rows[$i]->config_name;	
				$xmlcnt["configuration"]["global_config"][$i]["config"]["value"]=$rows[$i]->config_value;
			}
			
			//plugin configuration
			$query="select * from #__ijoomer_plugins";
			$db->setQuery($query);
			$rows=$db->loadObjectlist();
			
			for($i=0;$i<count($rows);$i++){
				$query="select * from `#__ijoomer_".$rows[$i]->plugin_classname."_config`";
				$db->setQuery($query);
				$data=$db->loadObjectlist(); 
				for($d=0;$d<count($data);$d++){
					$xmlcnt["configuration"]["plugin_config"][$rows[$i]->plugin_classname][$d]["config"]["name"]=$data[$d]->config_name;	
					$xmlcnt["configuration"]["plugin_config"][$rows[$i]->plugin_classname][$d]["config"]["value"]=$data[$d]->config_value;
				}
			}
			
		}else{
			$xmlcnt["code"]=2;
		}
		
		return $xmlcnt;
	}
	

}
?>