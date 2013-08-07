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
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');
$profile = $this->profile;


?>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
<div class="col50">
<fieldset>
<legend><?php echo JText::_('GENERAL')?></legend>
<table style="text-align: left;" class="paramlist admintable">
<tr>
        <td class="paramlist_key" width="40%">
        	<span class="hasTip" title="<?php echo JText::_( 'CHECK_SESSION_LBL' ); ?>::<?php echo JText::_('IF_YES_IS_SELECTED_USER_HAS_TO_LOGIN_TO_USE_APPLICATION'); ?>">
				<?php echo JText::_( 'CHECK_SESSION_LBL' ); ?>
			</span></td>
            <td>
            	<?php echo JHTML::_('select.booleanlist','GC_LOGIN_REQUIRED','',$this->row["GC_LOGIN_REQUIRED"]); ?>
            </td>
        </tr>
        <tr>
        	<td class="paramlist_key" width="40%">
        	<span class="hasTip" title="<?php echo JText::_( 'REGISTRATION' ); ?>::<?php echo JText::_('SELECT_REGISTRATION_TYPE'); ?>">
				<?php echo JText::_( 'REGISTRATION' ); ?>
			</span>
            <td>
            	<?php echo JHTML :: _('select.radiolist',$profile,'GC_REGISTRATION','','value','value',$this->row["GC_REGISTRATION"]); ?>
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
        <!-- 
        <tr>
        	<td class="paramlist_key" width="40%">
        	<span class="hasTip" title="<?php //echo JText::_( 'REMEMBER_PASSWORD' ); ?>::<?php //echo JText::_('SET_YES_TO_REMEMBER_PASSWORD'); ?>">
				<?php //echo JText::_( 'REMEMBER_PASSWORD' ); ?>
			</span>
            <td>
            	<?php //echo JHTML :: _('select.booleanlist','GC_REMEMBER_PASSWORD','',$this->row["GC_REMEMBER_PASSWORD"]); ?>
            </td>
        </tr>
         <tr>
        	<td class="paramlist_key" width="40%">
        	<span class="hasTip" title="<?php //echo JText::_( 'RESET_PASSWORD' ); ?>::<?php //echo JText::_('SET_YES_TO_RESET_PASSWORD'); ?>">
				<?php //echo JText::_( 'RESET_PASSWORD' ); ?>
			</span>
            <td>
            	<?php //echo JHTML :: _('select.booleanlist','GC_RESET_PASSWORD','',$this->row["GC_RESET_PASSWORD"]); ?>
            </td>
        </tr>
        -->
        <tr>
        	<td class="paramlist_key" width="40%">
        	<span class="hasTip" title="<?php echo JText::_( 'DEFAULT_VIEW' ); ?>::<?php echo JText::_('DEFAULT_VIEW_EXPLAIN'); ?>">
				<?php echo JText::_( 'DEFAULT_VIEW' ); ?>
			</span>
           <td>
            	<select name="GC_DEFAULT_VIEW" id="GC_DEFAULT_VIEW" class="inputbox" >
            		<option value="tab"  <?php echo $this->row["GC_DEFAULT_VIEW"]=="tab" ? "selected='selected'" : "" ?> ><?php echo JText::_("COM_IJOOMER_TAB_VIEW"); ?></option>
            		<option value="icon" <?php echo $this->row["GC_DEFAULT_VIEW"]=="icon" ? "selected='selected'" : "" ?> ><?php echo JText::_("COM_IJOOMER_ICON_VIEW"); ?></option>
               	</select>
		  </td>
        </tr>
        <tr>
        	<td class="paramlist_key" width="40%">
        	<span class="hasTip" title="<?php echo JText::_( 'SELECT_THEME' ); ?>::<?php echo JText::_('SET_YES_TO_SELECT_THEME'); ?>">
				<?php echo JText::_( 'SELECT_THEME' ); ?>
			</span>
            <td>
            	<?php echo JHTML :: _('select.booleanlist','GC_SELECT_THEME','',$this->row["GC_SELECT_THEME"]); ?>
            </td>
        </tr>
        <tr>
        	<td class="paramlist_key" width="40%">
        	<span class="hasTip" title="<?php echo JText::_( 'DEFAULT_THEME' ); ?>::<?php echo JText::_('DEFAULT_APP_THEME_EXPLAIN'); ?>">
				<?php echo JText::_( 'DEFAULT_THEME' ); ?>
			</span>
           <td>
            	<select name="GC_DEFAULT_THEME" id="GC_DEFAULT_THEME" class="inputbox" >
            		<option value="Red" <?php echo $this->row["GC_DEFAULT_THEME"]=="Red" ? "selected='selected'" : "" ?> ><?php echo JText::_("RED"); ?></option>
            		<option value="Green" <?php echo $this->row["GC_DEFAULT_THEME"]=="Green" ? "selected='selected'" : "" ?> ><?php echo JText::_("GREEN"); ?></option>
            		<option value="Blue" <?php echo $this->row["GC_DEFAULT_THEME"]=="Blue" ? "selected='selected'" : "" ?> ><?php echo JText::_("BLUE"); ?></option>
            		<option value="Black" <?php echo $this->row["GC_DEFAULT_THEME"]=="Black" ? "selected='selected'" : "" ?> ><?php echo JText::_("BLACK"); ?></option>
            		<option value="White" <?php echo $this->row["GC_DEFAULT_THEME"]=="White" ? "selected='selected'" : "" ?> ><?php echo JText::_("WHITE"); ?></option>
            	</select>
		  </td>
        </tr>
        
	</table>
	</fieldset>
</div>

<div class="col50" >
	<fieldset>
	<legend><?php echo JText::_('APP_PUSH_NOTIFICATION_CONFIG_TITLE')?></legend>
	<table style="text-align: left;" class="paramlist admintable" >
	<tr>
			<td class="paramlist_key" width="40%" > 
				<?php echo JText::_('APP_PUSH_NOTIFICATION_CONFIG_ENABLE_PUSH_NOTIFICATION'); ?> 
			</td>
			<td>
				<?php echo JHTML::_('select.booleanlist','PNC_ENABLE','',$this->row["PNC_ENABLE"]); ?>
			</td>
	</tr>
	<tr>
			<td class="paramlist_key" width="40%" > 
				<?php echo JText::_('APP_PUSH_NOTIFICATION_CONFIG_SEND_NOTIFICATION_WHEN'); ?> 
			</td>
			<td>
				<select name="PNC_SEND_NOTIFICATION_WHEN" id="PNC_SEND_NOTIFICATION_WHEN" class="inputbox" >
            		<option value="FRIEND_ONLINE" <?php echo $this->row["PNC_SEND_NOTIFICATION_WHEN"]=="FRIEND_ONLINE" ? "selected='selected'" : "" ?> ><?php echo JText::_('APP_PUSH_NOTIFICATION_CONFIG_FRIEND_ONLINE_NOTIFICATION'); ?> </option>
            		<option value="INBOX_MESSAGE" <?php echo $this->row["PNC_SEND_NOTIFICATION_WHEN"]=="INBOX_MESSAGE" ? "selected='selected'" : "" ?> ><?php echo JText::_('APP_PUSH_NOTIFICATION_CONFIG_INBOX_MESSAGE_NOTIFICATION'); ?></option>
            		<option value="FRIEND_REQUEST" <?php echo $this->row["PNC_SEND_NOTIFICATION_WHEN"]=="FRIEND_REQUEST" ? "selected='selected'" : "" ?> ><?php echo JText::_('APP_PUSH_NOTIFICATION_CONFIG_FRIEND_REQUEST_NOTIFICATION'); ?></option>
            		
            	</select>
			</td>
			
	</tr>
	
	<!--<tr>
			<td class="paramlist_key" width="40%" >
					<?php //echo JText::_('APP_PUSH_NOTIFICATION_CONFIG_FRIEND_ONLINE_NOTIFICATION'); ?>
			</td>
			<td>
				<?php //echo JHTML::_('select.booleanlist','PNC_SEND_NOTIFICATION_ONLINE_FRIEND','',$this->row["PNC_SEND_NOTIFICATION_ONLINE_FRIEND"]); ?>
			</td>
			
	</tr>
	<tr>
			<td class="paramlist_key" width="40%" >
					<?php //echo JText::_('APP_PUSH_NOTIFICATION_CONFIG_INBOX_MESSAGE_NOTIFICATION'); ?>
			</td>
			<td>
					<?php //echo JHTML::_('select.booleanlist','PNC_SEND_NOTIFICATION_INBOX_MESSAGE','',$this->row["PNC_SEND_NOTIFICATION_INBOX_MESSAGE"]); ?>
			</td>
			
	</tr>
	<tr>
			<td class="paramlist_key" width="40%" >
					<?php //echo JText::_('APP_PUSH_NOTIFICATION_CONFIG_FRIEND_REQUEST_NOTIFICATION'); ?>
			</td>
			<td>
					<?php //echo JHTML::_('select.booleanlist','PNC_SEND_NOTIFICATION_FRIEND_REQUEST','',$this->row["PNC_SEND_NOTIFICATION_FRIEND_REQUEST"]); ?>
			</td>
			
	</tr>
	--><tr>
			<td></td>
	</tr>
	<tr>
			<td> 
				<?php echo JText::_('APP_PUSH_NOTIFICATION_CONFIG_IPHONE_SETTINGS'); ?> 
			</td>
	</tr>
	
	<tr>
			<td class="paramlist_key" width="40%" > 
				<?php echo JText::_('APP_PUSH_NOTIFICATION_CONFIG_IPHONE_DEPLOYMENT_MODE'); ?> 
			</td>
			<td> <input type='Radio' Name='PNC_IPHONE_DEPLOYMENT_MODE' value='live' />
					<?PHP echo JText::_('APP_PUSH_NOTIFICATION_CONFIG_IPHONE_LIVE_MODE'); ?>
					
				<input type='Radio' Name='PNC_IPHONE_DEPLOYMENT_MODE' value='sandbox' checked/>
					<?PHP echo JText::_('APP_PUSH_NOTIFICATION_CONFIG_IPHONE_SANDBOX_MODE'); ?>
			</td>
	</tr>
	<tr>
			<td class="paramlist_key" width="40%" > 
				<?php echo JText::_('APP_PUSH_NOTIFICATION_CONFIG_IPHONE_ENABLE_PUSH_SOUND'); ?> 
			</td>
			<td>
				<?php echo JHTML::_('select.booleanlist','PNC_IPHONE_ENABLE_PUSH_SOUND','',$this->row["PNC_IPHONE_ENABLE_PUSH_SOUND"]); ?>
			</td>
	</tr>
	<tr>
			<td class="paramlist_key" width="40%" > 
				<?php echo JText::_('APP_PUSH_NOTIFICATION_CONFIG_IPHONE_ENABLE_UPLOAD_CERTIFICATE'); ?> 
			</td>
			<td>
				<input type="file" name="PNC_IPHONE_UPLOAD_FILE"  id="PNC_IPHONE_UPLOAD_FILE" value=""/>
			</td>
	</tr>
	<tr>
			<td></td>
	</tr>
	<tr>
			<td> 
				<?php echo JText::_('APP_PUSH_NOTIFICATION_CONFIG_ANDROID_SETTINGS'); ?> 
			</td>
	</tr>
	<tr>
			<td class="paramlist_key" width="40%" > 
				<?php echo JText::_('APP_PUSH_NOTIFICATION_CONFIG_ANDROID_ENABLE_PUSH_SOUND'); ?> 
			</td>
			<td>
				<?php echo JHTML::_('select.booleanlist','PNC_ANDROID_ENABLE_PUSH_SOUND','',$this->row["PNC_ANDROID_ENABLE_PUSH_SOUND"]); ?>
			</td>
	</tr>
	<tr>
			<td class="paramlist_key" width="40%" > 
				<?php echo JText::_('APP_PUSH_NOTIFICATION_CONFIG_ANDROID_REGISTRATION_ID'); ?> 
			</td>
			<td>
				<input type="text" name="PNC_ANDROID_REGID" id="PNC_ANDROID_REGID" value="<?php echo $this->row["PNC_ANDROID_REGID"]; ?>" />
			</td>
	</tr>
	<tr>
			<td class="paramlist_key" width="40%" > 
				<?php echo JText::_('APP_PUSH_NOTIFICATION_CONFIG_ANDROID_USERNAME'); ?> 
			</td>
			<td>
				<input type="text" name="PNC_ANDROID_USERNAME" id="PNC_ANDROID_USERNAME" value="<?php echo $this->row["PNC_ANDROID_USERNAME"]; ?>" />
			</td>
	</tr>
	<tr>
			<td class="paramlist_key" width="40%" > 
				<?php echo JText::_('APP_PUSH_NOTIFICATION_CONFIG_ANDROID_Password'); ?> 
			</td>
			<td>
				<input type="password" name="PNC_ANDROID_PASSWORD" id="PNC_ANDROID_PASSWORD" value="<?php echo $this->row["PNC_ANDROID_PASSWORD"]; ?>" />
			</td>
	</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="config" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>