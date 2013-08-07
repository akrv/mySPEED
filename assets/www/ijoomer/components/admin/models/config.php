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
	
	function getConfig(){
		$db = JFactory :: getDBO();
		$query="select * from #__ijoomer_config";
		$db->setQuery($query);
		$rows=$db->loadObjectlist();
		$cfg=array();	
		for($i=0;$i<count($rows);$i++){
			$cfg[$rows[$i]->config_name]=$rows[$i]->config_value;
		}		
		return $cfg;
	}
	
	function getProfile(){
 		
 		$db = JFactory :: getDBO();
 		
 		$profile = array();
 		$profile[]->value = 'No';
		$help = new Adminhelper();
 		

 		$profile[]->value = 'joomla';

		
		$component = $help->getComponent("com_community");
		$plugin = $help->getPlugin("com_community");
		
		if($component == 1 && $plugin == 1)
		{
			$profile[]->value = 'community';
		}

		
		$component = $help->getComponent("com_kunena");
		$plugin = $help->getPlugin("com_kunena");
		
		if($component == 1 && $plugin == 1)
		{
			$profile[]->value = 'kunena';
		}
		return $profile;	
	}
	function store($data){
		$db = JFactory :: getDBO();
		$my_config_array = array(
								"GC_LOGIN_REQUIRED" => $data['GC_LOGIN_REQUIRED'],
								"GC_REGISTRATION" => $data['GC_REGISTRATION'],
								"GC_REMEMBER_PASSWORD" => $data['GC_REMEMBER_PASSWORD'],
								"GC_RESET_PASSWORD" => $data['GC_RESET_PASSWORD'],
								"GC_SELECT_THEME" => $data['GC_SELECT_THEME'],
		                        "GC_DEFAULT_THEME" => $data['GC_DEFAULT_THEME'],
								"GC_DEFAULT_VIEW" => $data['GC_DEFAULT_VIEW'],
								"PNC_ENABLE" => $data['PNC_ENABLE'],
								"PNC_SEND_NOTIFICATION_WHEN" => $data['PNC_SEND_NOTIFICATION_WHEN'],
								"PNC_SEND_NOTIFICATION_ONLINE_FRIEND" => $data['PNC_SEND_NOTIFICATION_ONLINE_FRIEND'],
								"PNC_SEND_NOTIFICATION_INBOX_MESSAGE" => $data['PNC_SEND_NOTIFICATION_INBOX_MESSAGE'],
								"PNC_SEND_NOTIFICATION_FRIEND_REQUEST" => $data['PNC_SEND_NOTIFICATION_FRIEND_REQUEST'],
								"PNC_IPHONE_DEPLOYMENT_MODE" => $data['PNC_IPHONE_DEPLOYMENT_MODE'],
								"PNC_IPHONE_ENABLE_PUSH_SOUND" => $data['PNC_IPHONE_ENABLE_PUSH_SOUND'],
								"PNC_IPHONE_UPLOAD_FILE" => $data['PNC_IPHONE_UPLOAD_FILE'],
								"PNC_ANDROID_ENABLE_PUSH_SOUND" => $data['PNC_ANDROID_ENABLE_PUSH_SOUND'],
								"PNC_ANDROID_REGID" => $data['PNC_ANDROID_REGID'],
								"PNC_ANDROID_USERNAME" => $data['PNC_ANDROID_USERNAME'],
								"PNC_ANDROID_PASSWORD" => $data['PNC_ANDROID_PASSWORD']);
	 	foreach( $my_config_array as $key => $value ) {
	 		  $query="update #__ijoomer_config set `config_value`='".$value."' where `config_name`='".$key."'";
        	  $db->setQuery($query);
        	  $db->Query();
      	}	 
      	return true;	
	}
	function store_backup($data)
	{
		
        $configcfg  =	JPATH_COMPONENT_SITE.DS.'config.cfg.php'; 
     
		$my_config_array = array(
								"GC_LOGIN_REQUIRED" => $data['GC_LOGIN_REQUIRED'],
								"GC_REGISTRATION" => $data['GC_REGISTRATION'],
								"GC_REMEMBER_PASSWORD" => $data['GC_REMEMBER_PASSWORD'],
								"GC_RESET_PASSWORD" => $data['GC_RESET_PASSWORD'],
								"GC_SELECT_THEME" => $data['GC_SELECT_THEME'],
		                        "GC_DEFAULT_THEME" => $data['GC_DEFAULT_THEME']);
		
	  $config = "<?php\n";      
      foreach( $my_config_array as $key => $value ) {
          $config .= "define ('$key', '$value');\n";
      }
      $config .= "?>";
	
	  
	 

	      if ($fp = fopen($configcfg, "w+")) {
	          fputs($fp, $config, strlen($config));
	          fclose ($fp);
	          return true;
	     }
	     else {
	        $d["error"] = JTEXT::_('ERR_CONFIGFILE')." ".$d['cfgfile'];
	        return false;
	     }
	}
}

?>
