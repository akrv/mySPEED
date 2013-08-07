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
		if (pressbutton)
   		 {form.task.value=pressbutton;}
		if (pressbutton == 'publish' || pressbutton == 'unpublish' || pressbutton == 'close')
		{
			form.view.value = 'app_module';
		}
	  form.submit();
	}
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<table width="100%">
	<tr>
	<td width="50%" valign="top">
	<!--  <legend>Module Assignment</legend>-->
	<table class="adminlist" name="module" cellspacing="1">
	<thead>
		<tr>
			<th width="5">
				<?php echo JText::_( 'NUM' ); ?>
			</th>
			<th width="5%">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->app_module); ?>);" />
			</th >
			 <th class="title">
				<?php  echo JText::_('Name'); ?>				
			</th>	
			<th class="title">
				<?php echo JText::_('Type'); ?>				
			</th>
			<th width="15%">
			<?php echo JText::_('Order'); ?>
			<?php if ($this->ordering) echo JHTML::_('grid.order',  $this->app_module); ?>
			</th>
			<th width="15%" nowrap="nowrap">
				<?php echo JText::_('Published'); ?>	
			</th>
		</tr>
		<tfoot>
			<tr>
				<td colspan="15">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
			</tfoot>
		</thead>
		<tbody>
		<?php
	
	for ($i=0, $n=count($this->app_module); $i < $n; $i++)
	{
	
		$row = $this->app_module[$i];

		$link = JRoute::_ ( 'index.php?option=com_ijoomer&view=app_module_detail&task=edit&cid[]=' . $row->id ); 
		$published 	= JHTML::_('grid.published', $row, $i ); 
		?>
		<tr>
			<td align ="center">
				<?php echo $this->pagination->getRowOffset($i);?>  
			</td>
			<td class="order">
				<?php echo JHTML::_('grid.id', $i, $row->id ); ?>
			</td>
			<td>
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit Module' );?>::<?php echo htmlspecialchars($row->name); ?>">
					<a href="<?php echo $link; ?>">
						<?php echo htmlspecialchars($row->name); ?></a>
				</span>
			</td>	
			<td>
				<?php echo $row->type; ?>
			</td>
			<td class="order" nowrap="nowrap">
				<span><?php echo $this->pagination->orderUpIcon( $i, $row->id || $row->id == @$this->app_module[$i-1]->id, 'orderup', 'Move Up', $this->ordering );?></span>
				<span><?php echo $this->pagination->orderDownIcon( $i, $n,$row->id || $row->id == @$this->app_module[$i+1]->id,'orderdown', 'Move Down', $this->ordering);?></span>
				<?php $disabled = $this->ordering ?  '' : 'disabled="disabled"'; ?>
				<input type="text" name="order[]" size="8" value="<?php echo $row->ordering; ?>" <?php echo $disabled; ?> class="text_area" style="text-align: center" /> 
			</td>		
			<td align="center">
				<?php echo $published;?>
			</td>
		</tr>
			<?php
		}
		?>
	</tbody>	
	</table>	
</td>	
</tr>	
</table>			
<div class="clr"></div>
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="app_module" />
<input type="hidden" name="boxchecked" value="" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<input type="hidden" name="option" value="com_ijoomer" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>