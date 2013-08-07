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
jimport('joomla.version');

class Adminhelper
{
	protected $db;
	
	function __construct(){
		$this->db =& JFactory::getDBO();
	}
	
	function getComponent($option){

		$version = new JVersion();
		
		if($version->RELEASE > 1.5){	
			$query = "SELECT e.extension_id AS 'id', e.element AS 'option', e.params, e.enabled FROM #__extensions as e
						WHERE e.type = 'component' AND e.element = '".$option."' ";
		}else{
			$query = "SELECT c.* FROM #__components as c WHERE c.option = '".$option."' ";
		}
		
		$this->db->setQuery($query);
		$components = $this->db->loadObject();
		
		if(count($components)>0 && $components->enabled == 1){
			return true;
		}
		return false;
	}
	
	function getPlugin($option){
		
		$query = "SELECT count(*) FROM #__ijoomer_plugins WHERE plugin_option = '".$option."' ";
		$this->db->setQuery( $query );
		$plugins = $this->db->loadResult();
		
		if($plugins){
			return 1;
		}
		return 0;
	}
	
	function getJomSocialVersion(){

		JHTML::_('behavior.tooltip', '.hasTip');

		$parser		=& JFactory::getXMLParser('Simple');
		$xml		= JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_community' . DS . 'community.xml';

		if(file_exists($xml)){
			$parser->loadFile( $xml );

			$doc		=& $parser->document;
		
			$element	=& $doc->getElementByPath( 'version' );
			$version	= $element->data();
		
			$cv = explode('.',$version);
			$cversion = $cv[0].$cv[1];
		
			return $cversion;
		}
		return true;	
	}
	//function to define global config
	function getglobalconfig(){
		$query="select * from #__ijoomer_config";
		$this->db->setQuery($query);
		$rows=$this->db->loadObjectlist();
		//$cfg=array();
		
		foreach($rows as $row){
			define($row->config_name,$row->config_value);
		}
			
		/*for($i=0,$max=count($rows);$i<$max;$i++){
			define($rows[$i]->config_name,$rows[$i]->config_value);
		}*/
		
		return true;
	}
	
}
?>
