<?php

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');
$db = &JFactory::getDBO();
$sql = "select username FROM #__users ";
$db->setQuery($sql);
$res_user = $db->loadResultArray();
?>


<script type="text/javascript" src="<?php echo JURI::root()?>administrator/components/com_ijoomer/assets/js/jquery.js"></script>
<script type='text/javascript' src="<?php echo JURI::root()?>administrator/components/com_ijoomer/assets/js/jquery.autocomplete.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo JURI::root()?>administrator/components/com_ijoomer/assets/css/jquery.autocomplete.css" />
<script>
$().ready(function() {
	
	//$.noConflict();
	$('input[value="customs"]').click(function() 
	{
		$('#userid').show(1000);
	});
	$('input[value="1"]').click(function() 
	{
		$('#userid').hide(100);
	});
	$('#customs').click(function() 
	{
		$('#disp_btn').show()
	});
	
	var months = [
				<?php for($i=0;$i<count($res_user);$i++) { ?>
				'<?php echo $res_user[$i]; ?>', 
				<?php } ?>
		         ];
    
	$("#send_to_username").autocomplete(months, {
		
		minChars: 0,
		max: 12,
		autoFill: true,
		mustMatch: true,
		matchContains: false,
		scrollHeight: 220,
		formatItem: function(data, i, total) {
			
			return data[0];
		}
	});
	
});
</script>

<script language="javascript" type="text/javascript">

	function changeVal() 
	{
		
		if(document.adminForm.send_to_username.value == "")
		{
			alert("Please select User Name");
		} else {
			if(document.adminForm.send_to_user.value == "")
			{
				document.adminForm.send_to_user.value = document.adminForm.send_to_username.value;		
		
			} else {
				if(document.adminForm.send_to_user.value.indexOf(document.adminForm.send_to_username.value)!=-1)
				{
					alert("User already Exists");
				}
				else
				{
					document.adminForm.send_to_user.value += ", "+document.adminForm.send_to_username.value;
				}
			}
		}
		//document.adminForm.send_to_user.value += document.adminForm.send_to_username.value+', ';
		document.adminForm.send_to_username.value = "";
		
	   		   	
	}


	function submitbutton(pressbutton) 
	{ 
		var form = document.adminForm;
		
		if ((pressbutton=='add')||(pressbutton=='edit')||(pressbutton=='publish')||(pressbutton=='unpublish') || pressbutton=='remove' || pressbutton=='save'  )
		{
					form.view.value="app_push_notification_detail";
		}	
		submitform( pressbutton );
	
	}
</script>	
	

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<table  class ="adminlist" width="100%">
	<tbody>
		<tr>
			<td class="title" width="10%" > <?php echo JText::_('APP_PUSH_NOTIFICATION_SELECT_DEVICE_TYPE')." :";?> </td>
			<td > <input type='Radio' Name='device_type' value='iphone' />
					<?PHP echo JText::_('APP_PUSH_NOTIFICATION_SELECT_DEVICE_TYPE_IPHONE'); ?>

				<input type= 'Radio' Name='device_type' value='android'  />
					<?PHP echo JText::_('APP_PUSH_NOTIFICATION_SELECT_DEVICE_TYPE_ENDROID'); ?>
				
				<input type='Radio' Name ='device_type' value='both'  />
					<?PHP echo JText::_('APP_PUSH_NOTIFICATION_SELECT_DEVICE_TYPE_BOTH'); ?>
			</td>
		</tr>
		
		<tr>
			<td class="title" width="10%" > <?php echo JText::_('APP_PUSH_NOTIFICATION_SEND_NOTIFICATION_TO')." :";?> </td>
			<td>
				 <input type='radio' name="send_to_all" id="send_to_all" value='1' />
				 	 <?PHP echo JText::_('APP_PUSH_NOTIFICATION_SEND_NOTIFICATION_TO_ALL_USERS'); ?>

				<?php //if ($this->items->send_to_all=='0') { echo 'checked'; } ?>
				<Input type='radio' name="send_to_all" value='customs' id="customs"  />
					 <?PHP echo JText::_('APP_PUSH_NOTIFICATION_SEND_NOTIFICATION_TO_CUSTOMS'); ?>
					 <div style="display:none" id="userid"> 
					 		<input type="text" name="send_to_username" id="send_to_username" value=""  /> &nbsp;&nbsp; 
					 		<input type="button" name="add_uid"  id="add_uid" value="Add User" onClick="changeVal()" /> &nbsp;&nbsp; 
					 		<input type="text" name="send_to_user" id="send_to_user" value="<?php echo $this->items->send_to_user; ?>"  />
					 		
					 </div>
			</td>
		</tr>
		
		<tr>
			<td class="title" width="10%" > <?php echo JText::_('APP_PUSH_NOTIFICATION_NOTIFICATION_TEXT')." :";?> </td>
			<td>
				 <textarea rows="5" cols="15" name="notification_text" id="notification_text"></textarea>
			</td>
		</tr>
		</tbody>	
	</table>	
</td>	
</tr>	
</table>			
<div class="clr"></div>
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="app_push_notification_detail" />
<input type="hidden" name="id" value="<?php echo $this->items->id; ?>" />

</form>