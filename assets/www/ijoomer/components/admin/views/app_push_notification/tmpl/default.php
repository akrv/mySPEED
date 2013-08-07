</script>
<?php

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');

?>
<script language="javascript" type="text/javascript">
<?php
if (JOOMLA15) {
		?>
		function submitbutton(pressbutton) 
		<?php
		} 
		else {
		?>
			Joomla.submitbutton = function(pressbutton)
		<?php
		}
		?>	
		{
		var form = document.adminForm;
		//alert(pressbutton); 
		if (pressbutton)
   		 {form.task.value=pressbutton;}
		if (pressbutton == 'publish' || pressbutton == 'unpublish' || pressbutton == 'close' )
		{
			form.view.value = 'app_push_notification';
		} else if(pressbutton == 'add' ) {
			form.view.value = 'app_push_notification_detail';
		}
	  form.submit();
	}
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<table class="adminlist" name="module" cellspacing="1" width="100%">
	<!--  <legend>Module Assignment</legend>-->
	<!--<table class="adminlist" name="module" cellspacing="1">
	--><thead>
		<tr>
			<th width="10%">
				<?php echo JText::_( 'APP_PUSH_NOTIFICATION_ID_TITLE' ); ?>
			</th>
			<th width="10%">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->app_push_data); ?>);" />
			</th >
			 <th class="title">
				<?php  echo JText::_('APP_PUSH_NOTIFICATION_DEVICE_NAME_TITLE'); ?>				
			</th>	
			<th class="title">
				<?php echo JText::_('APP_PUSH_NOTIFICATION_SEND_TO_USER_TITLE'); ?>				
			</th>
			<th class="title">
				<?php echo JText::_('APP_PUSH_NOTIFICATION_SEND_TO_ALL_TITLE'); ?>				
			</th>
			<!--<th width="15%">
			<?php //echo JText::_('Order'); ?>
			<?php //if ($this->ordering) echo JHTML::_('grid.order',  $this->app_push_notification); ?>
			</th>
			-->
			<th class="title" >
				<?php echo JText::_('APP_PUSH_NOTIFICATION_NOTIFICATION_TEXT_TITLE'); ?>	
			</th>
		</tr>
	</thead>	
	<tbody>
	<?php
	
	for ($i=0; $i<count($this->app_push_data); $i++)
	{
		
		$row = $this->app_push_data[$i];

		//$link = JRoute::_ ( 'index.php?option=com_ijoomer&view=app_push_notification_detail&task=edit&cid[]=' . $row->id ); 
		//$published 	= JHTML::_('grid.published', $row, $i ); 
		?>
		<tr>
			<td align ="center" class="text_area" style="text-align: center" >
				<?php echo $row->id; ?>  
			</td>
			<td class="order" class="text_area" style="text-align: center">
				<?php echo JHTML::_('grid.id', $i, $row->id ); ?>
			</td>
			
			<td class="text_area" style="text-align: center" >
					<?php echo $row->device_type; ?></a>
			</td>	
			<td class="text_area" style="text-align: center" >
				<?php //echo $row->send_to_user; ?>
				<?php 
					if($row->send_to_user) {
						//$msg_uid = $row->send_to_user;
						$explode_uname = explode(",",$row->send_to_user);
						$sendtouser = null;
						for($j=0; $j<count($explode_uname); $j++)
						{	
							$db = &JFactory::getDBO();
							$query_uname = " SELECT username from #__users WHERE id='".$explode_uname[$j]."' ";
							$db->setQuery($query_uname);
							$res_uname = $db->loadResult();
							if($res_uname)
							{	
								if(empty($sendtouser))
								{
									$sendtouser[$j] = $res_uname;
								} else {
									$sendtouser[$j] = ", ".$res_uname;
								}
							}
						}
						
						$msg_uid = implode("", $sendtouser); 
						
					} else {
						$msg_uid = "-";
					}
					echo $msg_uid;		 
				?>
			</td>
			<td class="text_area" style="text-align: center" >
				<?php 
					
					if($row->send_to_all == "1") 
					{
						$msg = "Yes";
					} else {
						$msg = "No";
					}	
					echo $msg; 
				?>
			</td>
			<td class="text_area" style="text-align: center" >
				<?php echo $row->notification_text; ?>
			</td>
			</tr>
			<?php 
		}
		?>
		<tfoot>
			<tr>
				<td colspan="15">
					<?php echo $this->page->getListFooter(); ?>
				</td>
			</tr>
			</tfoot>
	</tbody>	
		
</tr>	
</table>			
<div class="clr"></div>
<input type="hidden" name="option" value="com_ijoomer" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="app_push_notification" /> 
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<input type="hidden" name="option" value="com_ijoomer" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>