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

jimport( 'joomla.application.component.view' );

class plugin_detailVIEWplugin_detail extends JView
{
	function display($tpl = null)
	{
		
		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('PLUGIN') );

		$uri 		=& JFactory::getURI();
		
		$this->setLayout('default');
		$task = JRequest :: getVar('task');
		$cid = JRequest::getVar('cid',  0, '', 'array');
		if($task=='add' || $cid[0] == 0)
		{
			$text = JText::_( 'NEW' );
			JToolBarHelper::cancel( 'cancel', 'Close' );
			$tpl= 'install';
			$preText = "";
			$imgPath = 'plugins';
		}
		else
		{
			
			$text = JText::_( 'CONFIG' );
			JToolBarHelper::save();
	 		JToolBarHelper::apply();
			JToolBarHelper::cancel();
			
			
			$lists = array();

			$detail	=& $this->get('data');
			
			$cfg	=& $this->get('myConfig');
			//echo "<pre>";print_r($cfg);exit;
			
			
			$plugin_path = JPATH_COMPONENT_SITE.DS.'plugins';
			
			$preText = $detail->plugin_name;
			
			$imgPath = $detail->plugin_classname;
			
			$pluginxml  =	$plugin_path.DS.$detail->plugin_classname.'.xml';
	
    	    $pluginfile =	$plugin_path.DS.$detail->plugin_classname.DS.$detail->plugin_classname.'.php';
        
  	  	    $plugincfg  =	$plugin_path.DS.$detail->plugin_classname.DS.$detail->plugin_classname.'.cfg.php';
  	  	    
  	  	    $language =& JFactory::getLanguage();
			$language->load($detail->plugin_classname ,$plugin_path.DS.$detail->plugin_classname, $language->getTag(), true);
		
	
   		     if(is_file($pluginfile))
   	  		   	include_once ($pluginfile);
        	
     	    $class_obj = new $detail->plugin_classname;

        
    	    /*if(file_exists($plugincfg)){

				if(!is_writable($plugincfg)){

					echo "<font color='red'>".$plugincfg.' is not writable</font>';
				}

   		     include_once ($plugincfg);

      		}*/
			
			$version = 'N/A';
 	   		$xml = & JFactory::getXMLParser('Simple');

			if ($xml->loadFile($pluginxml))
			{
				$version =  $xml->document->version[0]->_data;
			}
 	      
      	 	$lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $detail->published );
      	 	
      	 	$this->addTemplatePath(JPATH_COMPONENT_SITE.DS."plugins".DS.$detail->plugin_classname.DS."tmpl");
        
      		$this->assignRef('class_obj', $class_obj);
        	
      		
      		$this->assignRef('cfg',		$cfg);
  			$this->assignRef('version',		$version);
      	 	$this->assignRef('helper',		$helper);
			$this->assignRef('lists',		$lists);
			$this->assignRef('detail',		$detail);
	
		}
		
		$imgPath48 = $imgPath."_48";  
		
		 
		$stylelink = ".icon-48-".$imgPath."_48 {";
		$stylelink .= "background-image: url('components/com_ijoomer/assets/images/".$imgPath."_48.png')";
		$stylelink .= "}"; 
		
		$document->addStyleDeclaration($stylelink);
		JToolBarHelper::title(   $preText." ".JText::_( 'PLUGIN' ).': <small><small>[ ' . $text.' ]</small></small>', $imgPath48 );
	 	$this->assignRef('request_url',	$uri->toString());
		parent::display($tpl);
	}
}
?>