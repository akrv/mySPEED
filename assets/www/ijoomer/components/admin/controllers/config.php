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
	function cancel()
	{
		$this->setRedirect( 'index.php?option=com_ijoomer' );
	}
	function display() {
		parent::display();
	}
	function save(){
		$model =  $this->getModel('config');
		$post = JRequest::get('post');
		$opt=$post["GC_REGISTRATION"];
		
		jimport('joomla.version');
		$version = new JVersion();
		
		if($opt=="community"){
			$op="com_community";
		}
		
		if($opt=="joomla"){
			if($version->RELEASE == '1.6' || $version->RELEASE == '1.7' ){
				$op="com_users";
			}else{
				$op="com_user";
			}
		}
		
		if($opt=="kunena"){
			$op="com_kunena";
		}
		if($opt!="No" and $opt!="no"){
			$ss=JComponentHelper::isEnabled($op, true);
			if($ss->id){
				$ss=1;
			}
			
			if($ss){
				
				if ($model->store($post)) 
				{
					$msg = JText::_('CONFIG_SAVED');
				} 
				else 
				{
					$msg = JText::_('ERROR_SAVING_CONFIG');
				}
			}else{
				$msg = JText::_ ( 'COMPONENT_NOT_FOUND_/_NOT_ENABLED' );
			}
		}else{
			if ($model->store ( $post )) {
				$msg = JText::_ ( 'CONFIG_SAVED' );
			} else {
				$msg = JText::_ ( 'ERROR_SAVING_CONFIG' );
			}
		}
		$this->setRedirect ( 'index.php?option=com_ijoomer&view=config', $msg );
	}
}
	
?>