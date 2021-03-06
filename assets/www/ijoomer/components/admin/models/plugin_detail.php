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
 
jimport( 'joomla.installer.installer' );
jimport('joomla.installer.helper');
jimport('joomla.filesystem.file');

class plugin_detailModelplugin_detail extends JModel
{
	var $_id = null;
	var $_data = null;
	var $_table_prefix = null;
	var $_copydata	=	null;

	function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__ijoomer_';		
	  
		$array = JRequest::getVar('cid',  0, '', 'array');
		
		$this->setId((int)$array[0]);
		
	}
	function setId($id)
	{
		$this->_id		= $id;
		$this->_data	= null;
	}

	function &getData()
	{
		if ($this->_loadData())
		{
			
		}else  $this->_initData();

	   	return $this->_data;
	}
	
	
	
	function _loadData()
	{
		if (empty($this->_data))
		{
		 	$query = 'SELECT * FROM '.$this->_table_prefix.'plugins WHERE plugin_id = '. $this->_id;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;
		}
		return true;
	}

	
	function _initData()
	{
		if (empty($this->_data))
		{
			$detail = new stdClass();
			$detail->plugin_id			= 0;
			$detail->plugin_name		= null;
			$detail->plugin_classname	= null;
	 		$detail->published			= 1;
	 		$detail->plugin_option	    = null;	
			$this->_data		 		= $detail;
			return (boolean) $this->_data;
		}
		return true;
	}
	
	function &getmyConfig()
	{
		$db = JFactory :: getDBO();	
		$query="select * from #__ijoomer_".$this->_data->plugin_classname."_config";
		$db->setQuery($query);
		$rows=$db->loadObjectlist();
		$cfg=array();
			
		for($i=0;$i<count($rows);$i++){
			$cfg[$rows[$i]->config_name]=$rows[$i]->config_value;
		}
		//echo "<pre>";print_r($cfg);exit;		
		return $cfg;		
	}
	
  	function store($data)
	{
		
		$row =& $this->getTable();
		$row->load($data['plugin_id']);
	  /*
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
	 	
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
	  	*/
		$plugin_path = JPATH_COMPONENT_SITE.DS.'plugins';
      
        $pluginfile =	$plugin_path.DS.$row->plugin_classname.DS.$row->plugin_classname.'.php';
        
        $plugincfg  =	$plugin_path.DS.$row->plugin_classname.DS.$row->plugin_classname.'.cfg.php';
     
        include_once ($pluginfile);
        
        $class_obj = new $row->plugin_classname;
           
        //if(file_exists($plugincfg))
        {
       
        	if(method_exists($class_obj,'write_configuration')){
        		
        		$data['cfgfile'] = $plugincfg;
        		
        		$class_obj->write_configuration($data);
        	}
        }
		
		return true;
	}
	function delete($cid = array())
	{
		if (count( $cid ))
		{
			$cids = implode( ',', $cid );
			
			// $query = 'DELETE FROM '.$this->_table_prefix.'plugins WHERE plugin_id  IN ( '.$cids.' )';
// 			$this->_db->setQuery( $query );
// 			if(!$this->_db->query()) {
// 				$this->setError($this->_db->getErrorMsg());
// 				return false;
// 			}
		}

		return true;
	}

	function publish($cid = array(), $publish = 1)
	{		
		if (count( $cid ))
		{
			$cids = implode( ',', $cid );
			$query = 'UPDATE '.$this->_table_prefix.'plugins'
					  . ' SET published = ' . intval( $publish )
					  . ' WHERE  plugin_id 	 IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}
	function uninstall($eid=array())
	{
		$mainframe = JFactory::getApplication();

		// Initialize variables
		$failed = array ();

		/*
		 * Ensure eid is an array of extension ids in the form id => client_id
		 * TODO: If it isn't an array do we want to set an error and fail?
		 */
		if (!is_array($eid)) {
			$eid = array($eid => 0);
		}

		// Get a database connector
		$db =& JFactory::getDBO();

		// Get an installer object for the extension type
		jimport('joomla.installer.installer');
		$installer = & JInstaller::getInstance();
 
		// Uninstall the chosen extensions
		foreach ($eid as $id => $clientId)
		{
			$id		= trim( $id );
			$result	= $installer->uninstall('plugins', $id, $clientId );

			// Build an array of extensions that failed to uninstall
			if ($result === false) {
				$failed[] = $id;
			}
		}

		if (count($failed)) {
			// There was an error in uninstalling the package
			$msg = JText::sprintf('COM_IJOOMER_UNINSTALLEXT_ERROR', JText::_($this->_type), JText::_('ERROR'));
			$result = false;
		} else {
			// Package uninstalled sucessfully
			$msg = JText::sprintf('COM_IJOOMER_UNINSTALLEXT', JText::_($this->_type), JText::_('SUCCESS'));
			$result = true;
		}

		$mainframe->enqueueMessage($msg);
		$this->setState('action', 'remove');
		$this->setState('name', $installer->get('name'));
		$this->setState('message', $installer->message);
		$this->setState('extension.message', $installer->get('extension.message'));
        
		return $result;
	}
	function install()
	{
		$mainframe = JFactory::getApplication();
      
		$this->setState('action', 'install');
 
	    $package = $this->_getPackageFromUpload();
		 
	    if($package['type']!='plugins') {
	  		 
		 JError::raiseWarning('SOME_ERROR_CODE', JText::_('INVALID_PACKAGE'));
		 return false;
	      
	    }
	      
		 
		$installer =& JInstaller::getInstance();

		 
		if (!$installer->install($package['dir'])) {
			 
			$msg = JText::sprintf('COM_IJOOMER_INSTALLEXT_ERROR', JText::_($package['type']), JText::_('ERROR'));
			$result = false;
		} else {
			 
			$msg = JText::sprintf('COM_IJOOMER_INSTALLEXT', JText::_($package['type']), JText::_('SUCCESS'));
			$result = true;
		}

		 
		$mainframe->enqueueMessage($msg);
		$this->setState('name', $installer->get('name'));
		$this->setState('result', $result);
		$this->setState('message', $installer->message);
		$this->setState('extension.message', $installer->get('extension.message'));

		// Cleanup the install files
		if (!is_file($package['packagefile'])) {
			$config =& JFactory::getConfig();
			$package['packagefile'] = $config->getValue('config.tmp_path').DS.$package['packagefile'];
		}

		JInstallerHelper::cleanupInstall($package['packagefile'], $package['extractdir']);

		return $result;
	}
	/**
	 * @param string The class name for the installer
	 */
	function _getPackageFromUpload()
	{  
		// Get the uploaded file information
		$userfile = JRequest::getVar('install_plugin', null, 'files', 'array' );
		 

		// Make sure that file uploads are enabled in php
		if (!(bool) ini_get('file_uploads')) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('WARNINSTALLFILE'));
			return false;
		}

		// Make sure that zlib is loaded so that the package can be unpacked
		if (!extension_loaded('zlib')) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('WARNINSTALLZLIB'));
			return false;
		}

		// If there is no uploaded file, we have a problem...
		if (!is_array($userfile) ) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('NO_FILE_SELECTED'));
			return false;
		}

		// Check if there was a problem uploading the file.
		if ( $userfile['error'] || $userfile['size'] < 1 )
		{
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('WARNINSTALLUPLOADERROR'));
			return false;
		}

		// Build the appropriate paths
		$config =& JFactory::getConfig();
		$tmp_dest 	= $config->getValue('config.tmp_path').DS.$userfile['name'];
		$tmp_src	= $userfile['tmp_name'];

		// Move uploaded file
		jimport('joomla.filesystem.file');
		$uploaded = JFile::upload($tmp_src, $tmp_dest);

		// Unpack the downloaded package file
		$package = JInstallerHelper::unpack($tmp_dest);
		 
		return $package;
	}
 
}
class JInstaller extends JObject
{
	/**
	 * Array of paths needed by the installer
	 * @var array
	 */
	var $_paths = array();

	/**
	 * The installation manifest XML object
	 * @var object
	 */
	var $_manifest = null;

	/**
	 * True if existing files can be overwritten
	 * @var boolean
	 */
	var $_overwrite = false;

	/**
	 * A database connector object
	 * @var object
	 */
	var $_db = null;

	/**
	 * Associative array of package installer handlers
	 * @var array
	 */
	var $_adapters = array();

	/**
	 * Stack of installation steps
	 * 	- Used for installation rollback
	 * @var array
	 */
	var $_stepStack = array();

	/**
	 * The output from the install/uninstall scripts
	 * @var string
	 */
	var $message = null;

	/**
	 * Constructor
	 *
	 * @access protected
	 */
	function __construct()
	{
		$this->_db =& JFactory::getDBO();
	}

	 
	function &getInstance()
	{
		static $instance;

		if (!isset ($instance)) {
			$instance = new JInstaller();
		}
		return $instance;
	}

 
	function getOverwrite()
	{
		return $this->_overwrite;
	}

 
	function setOverwrite($state=false)
	{
		$tmp = $this->_overwrite;
		if ($state) {
			$this->_overwrite = true;
		} else {
			$this->_overwrite = false;
		}
		return $tmp;
	}

 
	function &getDBO()
	{
		return $this->_db;
	}

 
	function &getManifest()
	{
		if (!is_object($this->_manifest)) {
			$this->_findManifest();
		}
		return $this->_manifest;
	}

	/**
	 * Get an installer path by name
	 *
	 * @access	public
	 * @param	string	$name		Path name
	 * @param	string	$default	Default value
	 * @return	string	Path
	 * @since	1.5
	 */
	function getPath($name, $default=null)
	{
		return (!empty($this->_paths[$name])) ? $this->_paths[$name] : $default;
	}

	/**
	 * Sets an installer path by name
	 *
	 * @access	public
	 * @param	string	$name	Path name
	 * @param	string	$value	Path
	 * @return	void
	 * @since	1.5
	 */
	function setPath($name, $value)
	{
		$this->_paths[$name] = $value;
	}

	/**
	 * Pushes a step onto the installer stack for rolling back steps
	 *
	 * @access	public
	 * @param	array	$step	Installer step
	 * @return	void
	 * @since	1.5
	 */
	function pushStep($step)
	{
		$this->_stepStack[] = $step;
	}

	/**
	 * Set an installer adapter by name
	 *
	 * @access	public
	 * @param	string	$name		Adapter name
	 * @param	object	$adapter	Installer adapter object
	 * @return	boolean True if successful
	 * @since	1.5
	 */
	function setAdapter($name, $adapter = null)
	{
		if (!is_object($adapter))
		{
			// Try to load the adapter object
			require_once(dirname(__FILE__).DS.'adapters'.DS.'plugins.php');
			$class = 'JInstaller'.ucfirst($name);
			if (!class_exists($class)) {
				return false;
			}
			$adapter = new $class($this);
			$adapter->parent =& $this;
		}
		$this->_adapters[$name] =& $adapter;
		return true;
	}

	/**
	 * Installation abort method
	 *
	 * @access	public
	 * @param	string	$msg	Abort message from the installer
	 * @param	string	$type	Package type if defined
	 * @return	boolean	True if successful
	 * @since	1.5
	 */
	function abort($msg=null, $type=null)
	{
		// Initialize variables
		$retval = true;
		$step = array_pop($this->_stepStack);

		// Raise abort warning
		if ($msg) {
			JError::raiseWarning(100, $msg);
		}

		while ($step != null)
		{
			switch ($step['type'])
			{
				case 'file' :
					// remove the file
					$stepval = JFile::delete($step['path']);
					break;

				case 'folder' :
					// remove the folder
					$stepval = JFolder::delete($step['path']);
					break;

				case 'query' :
					// placeholder in case this is necessary in the future
					break;

				default :
					if ($type && is_object($this->_adapters[$type])) {
						// Build the name of the custom rollback method for the type
						$method = '_rollback_'.$step['type'];
						// Custom rollback method handler
						if (method_exists($this->_adapters[$type], $method)) {
							$stepval = $this->_adapters[$type]->$method($step);
						}
					}
					break;
			}

			// Only set the return value if it is false
			if ($stepval === false) {
				$retval = false;
			}

			// Get the next step and continue
			$step = array_pop($this->_stepStack);
		}

		return $retval;
	}

	/**
	 * Package installation method
	 *
	 * @access	public
	 * @param	string	$path	Path to package source folder
	 * @return	boolean	True if successful
	 * @since	1.5
	 */
	function install($path=null)
	{   
		if ($path && JFolder::exists($path)) {
			$this->setPath('source', $path);
		} else {
			$this->abort(JText::_('INSTALL_PATH_DOES_NOT_EXIST'));
			return false;
		}

		if (!$this->setupInstall()) {
			$this->abort(JText::_('UNABLE_TO_DETECT_MANIFEST_FILE'));
			return false;
		}

		
		$root		=& $this->_manifest->document;
		$version	= $root->attributes('version');
		$rootName	= $root->name();
		$config		= &JFactory::getConfig();
		
		$type = $root->attributes('type');

		// Needed for legacy reasons ... to be deprecated in next minor release
		if ($type == 'mambot') {
			$type = 'plugin';
		}

		if (is_object($this->_adapters[$type])) {
			return $this->_adapters[$type]->install();
		}
		return false;
	}

	/**
	 * Package update method
	 *
	 * @access	public
	 * @param	string	$path	Path to package source folder
	 * @return	boolean	True if successful
	 * @since	1.5
	 */
	function update($path=null)
	{
		if ($path && JFolder::exists($path)) {
			$this->setPath('source', $path);
		} else {
			$this->abort(JText::_('UPDATE_PATH_DOES_NOT_EXIST'));
		}

		if (!$this->setupInstall()) {
			return $this->abort(JText::_('UNABLE_TO_DETECT_MANIFEST_FILE'));
		}

		/*
		 * LEGACY CHECK
		 */
		$root		=& $this->_manifest->document;
		$version	= $root->attributes('version');
		$rootName	= $root->name();
		$config		= &JFactory::getConfig();
	 
		$type = $root->attributes('type');

		// Needed for legacy reasons ... to be deprecated in next minor release
		if ($type == 'mambot') {
			$type = 'plugin';
		}

		if (is_object($this->_adapters[$type])) {
			return $this->_adapters[$type]->update();
		}
		return false;
	}

	/**
	 * Package uninstallation method
	 *
	 * @access	public
	 * @param	string	$type	Package type
	 * @param	mixed	$identifier	Package identifier for adapter
	 * @param	int		$cid	Application ID
	 * @return	boolean	True if successful
	 * @since	1.5
	 */
	function uninstall($type, $identifier, $cid=0)
	{ 
		if (!isset($this->_adapters[$type]) || !is_object($this->_adapters[$type])) {
			if (!$this->setAdapter($type)) {
				return false;
			}
		}
		if (is_object($this->_adapters[$type])) {
			return $this->_adapters[$type]->uninstall($identifier, $cid);
		}
		return false;
	}

	/**
	 * Prepare for installation: this method sets the installation directory, finds
	 * and checks the installation file and verifies the installation type
	 *
	 * @access public
	 * @return boolean True on success
	 * @since 1.0
	 */
	function setupInstall()
	{
		// We need to find the installation manifest file
		if (!$this->_findManifest()) {
			return false;
		}

		// Load the adapter(s) for the install manifest
		$root =& $this->_manifest->document;
		$type = $root->attributes('type');

		 

		// Lazy load the adapter
		if (!isset($this->_adapters[$type]) || !is_object($this->_adapters[$type])) {
			if (!$this->setAdapter($type)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Backward compatible Method to parse through a queries element of the
	 * installation manifest file and take appropriate action.
	 *
	 * @access	public
	 * @param	object	$element 	The xml node to process
	 * @return	mixed	Number of queries processed or False on error
	 * @since	1.5
	 */
	function parseQueries($element)
	{
		// Get the database connector object
		$db = & $this->_db;

		if (!is_a($element, 'JSimpleXMLElement') || !count($element->children())) {
			// Either the tag does not exist or has no children therefore we return zero files processed.
			return 0;
		}

		// Get the array of query nodes to process
		$queries = $element->children();
		if (count($queries) == 0) {
			// No queries to process
			return 0;
		}

		// Process each query in the $queries array (children of $tagName).
		foreach ($queries as $query)
		{
			$db->setQuery($query->data());
			if (!$db->query()) {
				JError::raiseWarning(1, 'JInstaller::install: '.JText::_('SQL_ERROR')." ".$db->stderr(true));
				return false;
			}
		}
		return (int) count($queries);
	}

	/**
	 * Method to extract the name of a discreet installation sql file from the installation manifest file.
	 *
	 * @access	public
	 * @param	object	$element 	The xml node to process
	 * @param	string	$version	The database connector to use
	 * @return	mixed	Number of queries processed or False on error
	 * @since	1.5
	 */
	function parseSQLFiles($element)
	{
		// Initialize variables
		$queries = array();
		$db = & $this->_db;
		$dbDriver = strtolower($db->get('name'));
		if ($dbDriver == 'mysqli') {
			$dbDriver = 'mysql';
		}
		$dbCharset = ($db->hasUTF()) ? 'utf8' : '';

		if (!is_a($element, 'JSimpleXMLElement')) {
			// The tag does not exist.
			return 0;
		}

		// Get the array of file nodes to process
		$files = $element->children();
		if (count($files) == 0) {
			// No files to process
			return 0;
		}

		// Get the name of the sql file to process
		$sqlfile = '';
		foreach ($files as $file)
		{
			$fCharset = (strtolower($file->attributes('charset')) == 'utf8') ? 'utf8' : '';
			$fDriver  = strtolower($file->attributes('driver'));
			if ($fDriver == 'mysqli') {
				$fDriver = 'mysql';
			}

			if( $fCharset == $dbCharset && $fDriver == $dbDriver) {
				$sqlfile = $file->data();
				// Check that sql files exists before reading. Otherwise raise error for rollback
				if ( !file_exists( $this->getPath('extension_administrator').DS.$sqlfile ) ) {
					return false;
				}
				$buffer = file_get_contents($this->getPath('extension_administrator').DS.$sqlfile);

				// Graceful exit and rollback if read not successful
				if ( $buffer === false ) {
					return false;
				}

				// Create an array of queries from the sql file
				jimport('joomla.installer.helper');
				$queries = JInstallerHelper::splitSql($buffer);

				if (count($queries) == 0) {
					// No queries to process
					return 0;
				}

				// Process each query in the $queries array (split out of sql file).
				foreach ($queries as $query)
				{
					$query = trim($query);
					if ($query != '' && $query{0} != '#') {
						$db->setQuery($query);
						if (!$db->query()) {
							JError::raiseWarning(1, 'JInstaller::install: '.JText::_('SQL_ERROR')." ".$db->stderr(true));
							return false;
						}
					}
				}
			}
		}

		return (int) count($queries);
	}

	/**
	 * Method to parse through a files element of the installation manifest and take appropriate
	 * action.
	 *
	 * @access	public
	 * @param	object	$element 	The xml node to process
	 * @param	int		$cid		Application ID of application to install to
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function parseFiles($element, $cid=0,$pFolder)
	{
		// Initialize variables
		$copyfiles = array ();
 
		// Get the client info
		jimport('joomla.application.helper');
		$client =& JApplicationHelper::getClientInfo($cid);

		if (!is_a($element, 'JSimpleXMLElement') || !count($element->children())) {
			// Either the tag does not exist or has no children therefore we return zero files processed.
			return 0;
		}

		// Get the array of file nodes to process
		$files = $element->children();
		if (count($files) == 0) {
			// No files to process
			return 0;
		}

		/*
		 * Here we set the folder we are going to remove the files from.
		 */
		if ($client) {
			$pathname = 'extension_'.$client->name;
			$destination = $this->getPath($pathname);
		} else {
			$pathname = 'extension_root';
			$destination = $this->getPath($pathname);
		}

 
		if ($folder = $element->attributes('folder')) {
			$source = $this->getPath('source').DS.$folder;
		} else {
			$source = $this->getPath('source');
		}
	 
		// Process each file in the $files array (children of $tagName).
		foreach ($files as $file)
		{
			$path['src']	= $source.DS.$file->data();
			$path['dest']	= $destination.DS.$pFolder.DS.$file->data();

			// Is this path a file or folder?
			$path['type']	= ( $file->name() == 'folder') ? 'folder' : 'file';
 
			if (basename($path['dest']) != $path['dest']) {
				$newdir = dirname($path['dest']);

				if (!JFolder::create($newdir)) {
					JError::raiseWarning(1, 'JInstaller::install: '.JText::_('FAILED_TO_CREATE_DIRECTORY').' "'.$newdir.'"');
					return false;
				}
			}

			// Add the file to the copyfiles array
			$copyfiles[] = $path;
		}

		return $this->copyFiles($copyfiles);
	}
  
 
	function getParams()
	{
		// Get the manifest document root element
		$root = & $this->_manifest->document;

		// Get the element of the tag names
		$element =& $root->getElementByPath('params');
		if (!is_a($element, 'JSimpleXMLElement') || !count($element->children())) {
			// Either the tag does not exist or has no children therefore we return zero files processed.
			return null;
		}

		// Get the array of parameter nodes to process
		$params = $element->children();
		if (count($params) == 0) {
			// No params to process
			return null;
		}

		// Process each parameter in the $params array.
		$ini = null;
		foreach ($params as $param) {
			if (!$name = $param->attributes('name')) {
				continue;
			}

			if (!$value = $param->attributes('default')) {
				continue;
			}

			$ini .= $name."=".$value."\n";
		}
		return $ini;
	}

 
	function copyFiles($files, $overwrite=null)
	{
	  
		if (is_null($overwrite) || !is_bool($overwrite)) {
			$overwrite = $this->_overwrite;
		}

	 
		if (is_array($files) && count($files) > 0)
		{
			foreach ($files as $file)
			{  
				// Get the source and destination paths
				$filesource	= JPath::clean($file['src']);
				$filedest	= JPath::clean($file['dest']);
				$filetype	= array_key_exists('type', $file) ? $file['type'] : 'file';
				
				$upgrade = array_key_exists('upgrade', $file) ? $file['upgrade'] : 1;

				if (!file_exists($filesource)) {
				 
					JError::raiseWarning(1, 'JInstaller::install: '.JText::sprintf('FILE_DOES_NOT_EXIST', $filesource));
					return false;
				} elseif (file_exists($filedest) && !$overwrite) {

						 
						if ($this->getPath( 'manifest' ) == $filesource) {
							continue;
						}
 
						JError::raiseWarning(1, 'JInstaller::install: '.JText::sprintf('WARNSAME', $filedest));
						return false;
				} else {

					// Copy the folder or file to the new location.
					if ( $filetype == 'folder') {

						if (!(JFolder::copy($filesource, $filedest, null, $overwrite))) {
							JError::raiseWarning(1, 'JInstaller::install: '.JText::sprintf('FAILED_TO_COPY_FOLDER_TO', $filesource, $filedest));
							return false;
						}

						$step = array ('type' => 'folder', 'path' => $filedest);
					} else {
 						if( strstr($filesource,".cfg.") && file_exists($filedest)){
 							continue;	
 						}
						if (!(JFile::copy($filesource, $filedest))) {
							JError::raiseWarning(1, 'JInstaller::install: '.JText::sprintf('FAILED_TO_COPY_FILE_TO', $filesource, $filedest));
							return false;
						}

						$step = array ('type' => 'file', 'path' => $filedest);
					}

					 
					$this->_stepStack[] = $step;
				}
			}
		} else {

			 
			return false;
		}
		return count($files);
	}

 
	function removeFiles($element, $cid=0)
	{
		// Initialize variables
		$removefiles = array ();
		$retval = true;

		// Get the client info
		jimport('joomla.application.helper');
		$client =& JApplicationHelper::getClientInfo($cid);

		if (!is_a($element, 'JSimpleXMLElement') || !count($element->children())) {
	 		return true;
		}

		// Get the array of file nodes to process
		$files = $element->children();
		if (count($files) == 0) {
			// No files to process
			return true;
		}

 
		switch ($element->name())
		{
			case 'media':
				if ($element->attributes('destination')) {
					$folder = $element->attributes('destination');
				} else {
					$folder = '';
				}
				$source = $client->path.DS.'media'.DS.$folder;
				break;

			case 'languages':
				$source = $client->path.DS.'language';
				break;

			default:
				if ($client) {
					$pathname = 'extension_'.$client->name;
					$source = $this->getPath($pathname);
				} else {
					$pathname = 'extension_root';
					$source = $this->getPath($pathname);
				}
				break;
		}

		 
		foreach ($files as $file)
		{
		 
			if ($file->name() == 'language' && $file->attributes('tag') != '') {
				$path = $source.DS.$file->attributes('tag').DS.basename($file->data());

				// If the language folder is not present, then the core pack hasn't been installed... ignore
				if (!JFolder::exists(dirname($path))) {
					continue;
				}
			} else {
				$path = $source.DS.$file->data();
			}
 
			if (is_dir($path)) {
				$val = JFolder::delete($path);
			} else {
				$val = JFile::delete($path);
			}

			if ($val === false) {
				$retval = false;
			}
		}

		return $retval;
	}
  
	function copyManifest($cid=1)
	{
		// Get the client info
		jimport('joomla.application.helper');
		$client =& JApplicationHelper::getClientInfo($cid);

		$path['src'] = $this->getPath('manifest');

		if ($client) {
			$pathname = 'extension_'.$client->name;
			$path['dest']  = $this->getPath($pathname).DS.basename($this->getPath('manifest'));
		} else {
			$pathname = 'extension_root';
			$path['dest']  = $this->getPath($pathname).DS.basename($this->getPath('manifest'));
		}
		return $this->copyFiles(array ($path), true);
	}
 
	function _findManifest()
	{
		// Get an array of all the xml files from teh installation directory
		$xmlfiles = JFolder::files($this->getPath('source'), '.xml$', 1, true);
		// If at least one xml file exists
		if (!empty($xmlfiles)) {
			foreach ($xmlfiles as $file)
			{
				// Is it a valid joomla installation manifest file?
				$manifest = $this->_isManifest($file);
				if (!is_null($manifest)) {

					// If the root method attribute is set to upgrade, allow file overwrite
					$root =& $manifest->document;
					if ($root->attributes('method') == 'upgrade') {
						$this->_overwrite = true;
					}

					// Set the manifest object and path
					$this->_manifest =& $manifest;
					$this->setPath('manifest', $file);
 
					// Set the installation source path to that of the manifest file
					$this->setPath('source', dirname($file));
					return true;
				}
			}

			// None of the xml files found were valid install files
			JError::raiseWarning(1, 'JInstaller::install: '.JText::_('ERRORNOTFINDJOOMLAXMLSETUPFILE'));
			return false;
		} else {
			// No xml files were found in the install folder
			JError::raiseWarning(1, 'JInstaller::install: '.JText::_('ERRORXMLSETUP'));
			return false;
		}
	}
 
	function &_isManifest($file)
	{
		// Initialize variables
		$null	= null;
		$xml	=& JFactory::getXMLParser('Simple');

		// If we cannot load the xml file return null
		if (!$xml->loadFile($file)) {
			// Free up xml parser memory and return null
			unset ($xml);
			return $null;
		}

	 
		$root =& $xml->document;
		if (!is_object($root) || ($root->name() != 'install' && $root->name() != 'mosinstall')) {
			// Free up xml parser memory and return null
			unset ($xml);
			return $null;
		}

		// Valid manifest file return the object
		return $xml;
	}
}
?>