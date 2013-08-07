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
class iuser
{
	var $classname = "iuser";
	
	function write_configuration( &$d ) {
		
		$my_config_array = array(
								"DEFAULT_PROFILE" => $d['DEFAULT_PROFILE'],
								"AVAIL_EXTS" => $d['AVAIL_EXTS']
                          		);
      $config = "<?php\n";      
      foreach( $my_config_array as $key => $value ) {
        $config .= "define ('$key', '$value');\n";
      }

      $config .= "?>";

      if ($fp = fopen($d['cfgfile'], "w")) {
          fputs($fp, $config, strlen($config));
          fclose ($fp);

          return true;
     }
     else {
        $d["error"] = JTEXT::_('ERR_CONFIGFILE')." ".$d['cfgfile'];
        return false;
     }
   }


   function show_configuration() {
   	
   	$profile = $this->getProfile();
   	
    ?>
		<table style="text-align: left;" class="paramlist admintable">
		<tr>
        	<td class="paramlist_key" width="40%">
        	<span class="hasTip" title="<?php echo JText::_( 'DEFAULT_PROFILE_LBL' ); ?>::<?php echo JText::_('DEFAULT_PROFILE_EXPLAIN'); ?>">
				<?php echo JText::_( 'DEFAULT_PROFILE_LBL' ); ?>
			</span></td>
            <td>
            	<?php echo JHTML::_('select.radiolist',$profile,'DEFAULT_PROFILE','','value','value',DEFAULT_PROFILE); ?>
            	<?php 
            	$AVAIL_EXTS = array();
            		foreach($profile as $key)
            		{
            			 $AVAIL_EXTS[] = $key->value;
            		}
            	?>
            	<input type="hidden" name="AVAIL_EXTS" value="<?php echo implode(",",$AVAIL_EXTS);  ?>" />
            </td>
        </tr>
	</table>
	<?php }
	
	function getProfile(){
		jimport('joomla.version');
		$version = new JVersion();
 		
 		$db = JFactory :: getDBO();
 		
 		$profile = array();
 		$profile[]->value = 'default';
 		
		if($version->RELEASE=='1.5')
		{
			$query = "SELECT id FROM #__components WHERE `option`='com_community' AND `enabled`='1'";
		}
		else
		{
			$query = "SELECT extension_id AS id FROM #__extensions WHERE `element`='com_community' AND `enabled`='1'";
		}
		$db->setQuery($query);
		
		if($db->loadResult())
		{
			$profile[]->value = 'community';
		}

		if($version->RELEASE=='1.5')
		{
			$query = "SELECT id FROM #__components WHERE `option`='com_kunena' AND `enabled`='1'";
		}
		else
		{
			$query = "SELECT extension_id AS id FROM #__extensions WHERE `element`='com_kunena' AND `enabled`='1'";
		}
		$db->setQuery($query);
		
		if($db->loadResult())
		{	$profile[]->value = 'kunena';
		}
		
		return $profile;	
	}
}
?>