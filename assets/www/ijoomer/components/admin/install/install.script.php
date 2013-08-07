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

class com_ijoomer_install_script{
	
	function setconfig(){
		$db = JFactory::getDBO();
		include_once(dirname(__FILE__).DS.'config.php');
		
		for($i=0;$i<count($cdata);$i++){
			$query="select count(config_id) from #__ijoomer_config where `config_name`='".trim($cdata[$i]["config_name"])."'";
			$db->setQuery($query);
			$cnt = $db->loadResult();
			if($cnt==0){
				$query="insert into #__ijoomer_config(`config_id`,`config_name`,`config_value`) value(NULL,'".trim($cdata[$i]["config_name"])."','".$cdata[$i]["config_value"]."')";
				$db->setQuery($query);
				$db->Query();
			}
			
		}
	}
	
	function setplist(){
		/*$db = JFactory::getDBO();
		include_once(dirname(__FILE__).DS.'plist.php');
		
		for($i=0;$i<count($pdata);$i++){
			$query="select count(id) from #__ijoomer_plist where `plist_value`='".$pdata[$i]["plist_value"]."' and `type`='".$pdata[$i]["type"]."'";
			$db->setQuery($query);
			$cnt = $db->loadResult();
			if($cnt==0){
				$query="insert into #__ijoomer_plist(`id`,`plist_name`,`plist_value`,`description`,`type`,`published`) value(NULL,'".$pdata[$i]["plist_name"]."','".$pdata[$i]["plist_value"]."','".$pdata[$i]["description"]."','".$pdata[$i]["type"]."',0)";
				$db->setQuery($query);
				$db->Query();
			}
			
		}*/
	}
	
	function setdevices(){
		$db = JFactory::getDBO();
		include_once(dirname(__FILE__).DS.'devices.php');
		for($i=0;$i<count($devices);$i++){
			$query="select id from #__ijoomer_devices where `type`='".$devices[$i]["type"]."' and `tab_icon_size`='".$devices[$i]["tab_icon_size"]."' and `list_icon_size`='".$devices[$i]["list_icon_size"]."'";
			$db->setQuery($query);
			$id = $db->loadResult();
			if($id > 0){
				$query="update #__ijoomer_devices set devices='".$devices[$i]["devices"]."' where `id`=".$id;
				$db->setQuery($query);
				$db->Query();
			}else{
				$query="insert into #__ijoomer_devices(`id`,`type`,`tab_icon_size`,`list_icon_size`,`devices`) value(NULL,'".$devices[$i]["type"]."','".$devices[$i]["tab_icon_size"]."','".$devices[$i]["list_icon_size"]."','".$devices[$i]["devices"]."')";
				$db->setQuery($query);
				$db->Query();
			}
			
		}
	}
	
	function setModules(){ 
		$db = JFactory::getDBO();
		include_once(dirname(__FILE__).DS.'modules.php');
		
		for($i=0;$i<count($mdata);$i++){
			$query="select count(id) from #__ijoomer_app_module where `plugin_classname`='".$mdata[$i]["plugin_classname"]."' and `module_code`='".$mdata[$i]["module_code"]."'";
			$db->setQuery($query); 
			$cnt = $db->loadResult(); 
			if($cnt==0){ 
				$query="insert into #__ijoomer_app_module(`id`,`name`,`type`,`plugin_classname`,`module_code`,`published`,`title`,`access`,`description`) value(NULL,'".$mdata[$i]["name"]."','".$mdata[$i]["type"]."','".$mdata[$i]["plugin_classname"]."','".$mdata[$i]["module_code"]."','".$mdata[$i]["published"]."','".$mdata[$i]["title"]."','".$mdata[$i]["access"]."','".$mdata[$i]["description"]."')";
				$db->setQuery($query); 
				$db->Query(); 
			} 
			
		} 
	} 
	
	function setfolders(){
	
		$main_folder=JPATH_ROOT.DS."images".DS."com_ijoomer";
		
		if(!is_dir($main_folder)){
			mkdir($main_folder, 0777);
			copy(dirname(__FILE__).DS.'index.html',$main_folder.DS."index.html");
		}
		
		$android_folder=JPATH_ROOT.DS."images".DS."com_ijoomer".DS."android";
		if(!is_dir($android_folder)){
			mkdir($android_folder, 0777);
			copy(dirname(__FILE__).DS.'index.html',$android_folder.DS."index.html");
		}
		
		$iphone_folder=JPATH_ROOT.DS."images".DS."com_ijoomer".DS."iphone";
		if(!is_dir($iphone_folder)){
			mkdir($iphone_folder, 0777);
			copy(dirname(__FILE__).DS.'index.html',$iphone_folder.DS."index.html");
		}
		
		$bb_folder=JPATH_ROOT.DS."images".DS."com_ijoomer".DS."bb";
		if(!is_dir($bb_folder)){
			mkdir($bb_folder, 0777);
			copy(dirname(__FILE__).DS.'index.html',$bb_folder.DS."index.html");
		}
		
	}
	
/*	function setplugins(){
		$source=dirname(__FILE__).DS.'plugins';
		$dest=JPATH_COMPONENT_SITE.DS."plugins";
		
		if(!is_dir($dest)){
			mkdir($dest, 0755);
			copy($source.DS."iuser.xml",$dest.DS."iuser.xml");
			chmod("0655",$dest.DS."iuser.xml");
			
			copy($source.DS."index.html",$dest.DS."index.html");
			chmod("0655",$dest.DS."index.html");
			
			mkdir($dest.DS."iuser", 0755);
			
			copy($source.DS."iuser".DS."index.html",$dest.DS."iuser".DS."index.html");
			chmod("0655",$dest.DS."iuser".DS."index.html");
			
			copy($source.DS."iuser".DS."config.php",$dest.DS."iuser".DS."config.php");
			chmod("0655",$dest.DS."iuser".DS."config.php");
			
			copy($source.DS."iuser".DS."iuser.cfg.php",$dest.DS."iuser".DS."iuser.cfg.php");
			chmod("0655",$dest.DS."iuser".DS."iuser.cfg.php");
			
			copy($source.DS."iuser".DS."iuser.php",$dest.DS."iuser".DS."iuser.php");
			chmod("0655",$dest.DS."iuser".DS."iuser.php");
			
			copy($source.DS."iuser".DS."iusermain.php",$dest.DS."iuser".DS."iusermain.php");
			chmod("0655",$dest.DS."iuser".DS."iusermain.php");
			
		}else{
			if(is_dir($dest.DS."iuser")){
				chmod("0777",$dest.DS."iuser.xml");
				unlink($dest.DS."iuser.xml");
				copy($source.DS."iuser.xml",$dest.DS."iuser.xml");
				chmod("0655",$dest.DS."iuser.xml");
				
				chmod("0777",$dest.DS."iuser".DS."config.php");
				unlink($dest.DS."iuser".DS."config.php");
				copy($source.DS."iuser".DS."config.php",$dest.DS."iuser".DS."config.php");
				chmod("0655",$dest.DS."iuser".DS."config.php");
				
				chmod("0777",$dest.DS."iuser".DS."iuser.cfg.php");
				unlink($dest.DS."iuser".DS."iuser.cfg.php");
				copy($source.DS."iuser".DS."iuser.cfg.php",$dest.DS."iuser".DS."iuser.cfg.php");
				chmod("0655",$dest.DS."iuser".DS."iuser.cfg.php");
				
				chmod("0777",$dest.DS."iuser".DS."iuser.php");
				unlink($dest.DS."iuser".DS."iuser.php");
				copy($source.DS."iuser".DS."iuser.php",$dest.DS."iuser".DS."iuser.php");
				chmod("0655",$dest.DS."iuser".DS."iuser.php");
				
				chmod("0777",$dest.DS."iuser".DS."iusermain.php");
				unlink($dest.DS."iuser".DS."iusermain.php");
				copy($source.DS."iuser".DS."iusermain.php",$dest.DS."iuser".DS."iusermain.php");
				chmod("0655",$dest.DS."iuser".DS."iusermain.php");
				
			}
		}
		
	}*/
	
}

?>