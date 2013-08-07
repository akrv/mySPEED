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

class JInstallerplugins extends JObject
{
 
	function __construct(&$parent)
	{
		$this->parent =& $parent;
		$this->tbl_prefix = '#__ijoomer_';
	}

 
	function install()
	{
		// Get a database connector object
		$db =& $this->parent->getDBO();

		// Get the extension manifest object
		$manifest =& $this->parent->getManifest();
		$this->manifest =& $manifest->document;
		
		/**
		 * ---------------------------------------------------------------------------------------------
		 * Manifest Document Setup Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// Set the extensions name
		$name =& $this->manifest->getElementByPath('name');
		if(JOOMLA15)
		{
			$name = JFilterInput::clean($name->data(), 'string');
		}
		else
		{
			$filter =& JFilterInput::getInstance();
			$name = $filter->clean($name->data(), 'string');	
		}
			
		$this->set('name', $name);
		
 

		// Get the component description
		$description = & $this->manifest->getElementByPath('description');
		if (is_a($description, 'JSimpleXMLElement')) {
			$this->parent->set('message', $description->data());
		} else {
			$this->parent->set('message', '' );
		}

		/*
		 * Backward Compatability
		 * @todo Deprecate in future version
		 */
		$type = $this->manifest->attributes('type');

		// Set the installation path
		$pname = "";
		
		$element =& $this->manifest->getElementByPath('files');
		//collect images to $images variable and remove the entry from the files element
		
		
		if (is_a($element, 'JSimpleXMLElement') && count($element->children())) {
			
			$files =& $element->children(); 
			
			foreach ($files as $file) {
			//	echo $file->attributes(strtolower($type));
				if ($file->attributes(strtolower($type))) {
					$pname = $file->attributes(strtolower($type));
					
					break;
				}
			}
			
			$tm=0;
			foreach ($element->_children as $key=>$value){
				if($value->_name=="image"){
					$images[$tm]=$value->_data;
					$tm++;
				}
			}
		}
		//echo "<pre>";print_r($pname);exit;
		$plugin_classname = $this->manifest->getElementByPath('plugin_classname');
	 	if (is_a($plugin_classname, 'JSimpleXMLElement')) {
			$this->set('plugin_classname', $plugin_classname->data());
			$plugin_classname = $plugin_classname->data();
		} else {
			$this->set('plugin_classname', '' );
		}
		
		//shivani's code
		
		$plugin_option = $this->manifest->getElementByPath('plugin_option');
		
		if (is_a($plugin_option, 'JSimpleXMLElement')) {
			$this->set('plugin_option', $plugin_option->data());
			$plugin_option = $plugin_option->data();
		} else {
			$this->set('plugin_option', '' );
		}
			
		if (!empty ($pname) && !empty($plugin_classname)) {  
			$this->parent->setPath('extension_root',JPATH_COMPONENT_SITE.DS.'plugins');
		} else {
			$this->parent->abort(JText::_('PLUGIN').' '.JText::_('INSTALL').': '.JText::_('NO_PLUGIN_FILE_OR_CLASS_NAME_SPECIFIED'));
			return false;
		}

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Filesystem Processing Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// If the plugin directory does not exist, lets create it
		$created = false;
		
		if (!file_exists($this->parent->getPath('extension_root'))) {
			if (!$created = JFolder::create($this->parent->getPath('extension_root'))) {
				$this->parent->abort(JText::_('PLUGIN').' '.JText::_('INSTALL').': '.JText::_('FAILED_TO_CREATE_DIRECTORY').': "'.$this->parent->getPath('extension_root').'"');
				return false;
			}
		}

		/*
		 * If we created the plugin directory and will want to remove it if we
		 * have to roll back the installation, lets add it to the installation
		 * step stack
		 */
		if ($created) {
			$this->parent->pushStep(array ('type' => 'folder', 'path' => $this->parent->getPath('extension_root')));
		}

		// Copy all necessary files
		
		if ($this->parent->parseFiles($element, -1,$pname) === false) {
			// Install failed, roll back changes
			$this->parent->abort();
			return false;
		}
		//echo "<pre>";print_r(JPATH_COMPONENT_SITE);exit;
		if(count($images)){
			foreach ($images as $image){
				$sorc=JPATH_COMPONENT_SITE.DS."plugins".DS.$pname.DS.$image;
				$dest=JPATH_COMPONENT_ADMINISTRATOR.DS."assets".DS."images".DS.$image;
				if(file_exists($sorc)){
					copy($sorc,$dest);
					rename($sorc,$dest);
				}
			}		
		}

	  /**
		 * ---------------------------------------------------------------------------------------------
		 * Database Processing Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// Check to see if a plugin by the same name is already installed
		 $query = 'SELECT `plugin_id`' .
				' FROM `'.$this->tbl_prefix.'plugins`' .
				' WHERE plugin_classname = '.$db->Quote($plugin_classname) 
				 ;
				 
		$db->setQuery($query);
		if (!$db->Query()) {
			// Install failed, roll back changes
			$this->parent->abort(JText::_('PLUGIN').' '.JText::_('INSTALL').': '.$db->stderr(true));
			return false;
		}
		$plugin_id = $db->loadResult();

		// Was there a module already installed with the same name?
		if ($plugin_id) {

			if (!$this->parent->getOverwrite())
			{
				// Install failed, roll back changes
				$this->parent->abort(JText::_('PLUGIN').' '.JText::_('INSTALL').': '.JText::_('PLUGIN').' "'.$pname.'" '.JText::_('ALREADY_EXISTS'));
				return false;
			}
			
			//update config table 
			
			//create config table
			$query="CREATE TABLE IF NOT EXISTS `#__ijoomer_".$this->get('plugin_classname')."_config` (
			  `config_id` int(11) NOT NULL AUTO_INCREMENT,
			  `config_name` varchar(255) NOT NULL,
			  `config_value` varchar(255) NOT NULL,
			  PRIMARY KEY (`config_id`)
			)";
			$db->setQuery($query);
			$db->Query();
			
			$pconfig =& $this->manifest->getElementByPath('config');
				
			 if (is_a($pconfig, 'JSimpleXMLElement') && count($pconfig->children())) {
				$cfgs =& $pconfig->children(); 
				
				foreach ($cfgs as $cfg) {
					if($cfg->_name=="cfg"){
						$query="select count(*) from `#__ijoomer_".$this->get('plugin_classname')."_config` where `config_name`='".$cfg->_data."'";
						$db->setQuery($query);
						if(!$db->loadResult()){
							$query="insert into #__ijoomer_".$this->get('plugin_classname')."_config(`config_id`,`config_name`,`config_value`) value(NULL,'".$cfg->_data."','".$cfg->attributes("value")."')";
							$db->setQuery($query);
							$db->query();		
						}
					}
					
				}
			 }
			 
			if($this->get('plugin_classname') == "jomsocial")
			{
				$sql = "UPDATE #__ijoomer_config SET config_value = '1' WHERE config_name = 'GC_LOGIN_REQUIRED' ";
				$db->setQuery($sql);
				$db->query();
				
				$sql = "UPDATE #__ijoomer_config SET config_value = 'community' WHERE config_name = 'GC_REGISTRATION' ";
				$db->setQuery($sql);
				$db->query();	
			}

		} else {
			//$row =& JTable::getTable('plugin_detail'); 
			$row =& JTable::getInstance('plugin_detail', 'Table');
	  		$row->plugin_name = $this->get('name');
			$row->plugin_classname = $this->get('plugin_classname');
			$row->plugin_option = $this->get('plugin_option');
			$row->published = 1;
			
		   if (!$row->store()) {
				// Install failed, roll back changes
			 $this->parent->abort(JText::_('PLUGIN').' '.JText::_('INSTALL').': '.$db->stderr(true));
			 	return false;
			 }
			 
			//create config table
			$query="CREATE TABLE IF NOT EXISTS `#__ijoomer_".$row->plugin_classname."_config` (
			  `config_id` int(11) NOT NULL AUTO_INCREMENT,
			  `config_name` varchar(255) NOT NULL,
			  `config_value` varchar(255) NOT NULL,
			  PRIMARY KEY (`config_id`)
			)";
			$db->setQuery($query);
			if(!$db->query()) 
			{
				$this->setError($db->getErrorMsg());
				return false;
			}else{
				
				$pconfig =& $this->manifest->getElementByPath('config');

			if (is_a($pconfig, 'JSimpleXMLElement') && count($pconfig->children())) {
				$cfgs =& $pconfig->children(); 
				foreach ($cfgs as $cfg) {
					if($cfg->_name=="cfg"){
						$query="insert into #__ijoomer_".$row->plugin_classname."_config(`config_id`,`config_name`,`config_value`) value(NULL,'".$cfg->_data."','".$cfg->attributes("value")."')";
						$db->setQuery($query);
						$db->query();		
					}
					
				}
			 }
				
			} 
			 
			if($row->plugin_classname == "jomsocial")
			{
				$sql = "UPDATE #__ijoomer_config SET config_value = '1' WHERE config_name = 'GC_LOGIN_REQUIRED' ";
				$db->setQuery($sql);
				$db->query();
				
				$sql = "UPDATE #__ijoomer_config SET config_value = 'community' WHERE config_name = 'GC_REGISTRATION' ";
				$db->setQuery($sql);
				$db->query();	
			}
			 

			 	$this->parent->pushStep(array ('type' => 'plugin', 'plugin_id' => $row->plugin_id));
		}

 
		if (!$this->parent->copyManifest(-1)) {
			// Install failed, rollback changes
			$this->parent->abort(JText::_('PLUGIN').' '.JText::_('INSTALL').': '.JText::_('COULD_NOT_COPY_SETUP_FILE'));
			return false;
		}
		return true;
	}
 
	function uninstall($id, $clientId )
	{  
		// Initialize variables
		$row	= null;
		$retval = true;
		$db		=& $this->parent->getDBO();

		$row =& JTable::getInstance('plugin_detail', 'Table'); 
		if ( !$row->load((int) $clientId) ) {
			JError::raiseWarning(100, JText::_('ERRORUNKOWNEXTENSION'));
			return false;
		}
 
		// Set the plugin root path
		$this->parent->setPath('extension_root',JPATH_COMPONENT_SITE.DS.'plugins');

		 $manifestFile = JPATH_COMPONENT_SITE.DS.'plugins'.DS.$row->plugin_classname.'.xml';
		if (file_exists($manifestFile))
		{
			$xml =& JFactory::getXMLParser('Simple');

			// If we cannot load the xml file return null
			if (!$xml->loadFile($manifestFile)) {
				JError::raiseWarning(100, JText::_('PLUGIN').' '.JText::_('UNINSTALL').': '.JText::_('COULD_NOT_LOAD_MANIFEST_FILE'));
				return false;
			}

			 
			$root =& $xml->document;
			if ($root->name() != 'install' && $root->name() != 'mosinstall') {
				JError::raiseWarning(100, JText::_('PLUGIN').' '.JText::_('UNINSTALL').': '.JText::_('INVALID_MANIFIEST_FILE'));
				return false;
			}

			 
			JFile::delete($manifestFile);
 
		} else {
			JError::raiseWarning(100, JText::_('PLUGIN').' '.JText::_('UNINSTALL').': '.JText::_('MANIFEST_FILE_INVALID_OR_FILE_NOT_FOUND'));
			//JError::raiseWarning(100, 'Plugin Uninstall: Manifest File invalid or not found');
			//return false;
		}

		// Now we will no longer need the plugin object, so lets delete it
		$row->delete($row->plugin_id);
		

		// If the folder is empty, let's delete it
		$files = JFolder::files($this->parent->getPath('extension_root').DS.$row->plugin_classname);
		
	 
	//	 $this->parent->getPath('extension_root').DS.$row->plugin; 
		//if (!count($files)) {
			JFolder::delete($this->parent->getPath('extension_root').DS.$row->plugin_classname);
		//}
		unset ($row);
		return $retval;
	}
 
	function _rollback_plugin($arg)
	{
		// Get database connector object
		$db =& $this->parent->getDBO();

		// Remove the entry from the #__plugins table
		$query = 'DELETE' .
				' FROM `#__'.TABLE_PREFIX.'_plugins`' .
				' WHERE plugin_id ='.(int)$arg['plugin_id '];
		$db->setQuery($query);
		return ($db->query() !== false);
	}
}