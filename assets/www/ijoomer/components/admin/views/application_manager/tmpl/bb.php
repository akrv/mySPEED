 <script type="text/javascript">
		window.addEvent('domready', function(){ var JTooltips = new Tips($$('.component'), { maxTitleChars: 50, fixed: false}); });
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
		if (pressbutton){
			
		form.task.value=pressbutton;
			
		}
		if (pressbutton == 'publish' || pressbutton == 'unpublish' || pressbutton == 'close')
		{
			form.view.value = 'application_manager';
		}
	  form.submit();
	}
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data"  >
<?php 
echo $this->pane->startPane( 'pane' );
echo $this->pane->startPanel( '<a href="index.php?option=com_ijoomer&view=application_manager" >'.JText::_( 'COM_IJOOMER_IPHONE_TITLE' ).'</a>', 'panel1' );
?><div></div><?php 
echo $this->pane->endPanel();
echo $this->pane->startPanel('<a href="index.php?option=com_ijoomer&view=application_manager&task=anroid">'.JText::_( 'COM_IJOOMER_ANROID_TITLE' ).'</a>', 'panel2' );
echo $this->pane->endPanel();
echo $this->pane->startPanel(JText::_( 'COM_IJOOMER_BB_TITLE' ), 'panel3' );
?>
<div class="editcell">
	<table class="adminlist" cellspacing="1" name="screen">
	<thead>
		<tr>
			<th width="5">
				<?php echo JText::_( 'NUM' ); ?>
			</th>
			<th width="5%">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->application_manager ); ?>);" />
			</th >
			 <th class="title">
				<?php  echo JText::_('SCREEN_NAME'); ?>				
			</th>	
			 <th class="title">
				<?php  echo JText::_('CLASS_NAME'); ?>				
			</th>	
			<th class="title">
				<?php echo JText::_('DESCRIPTION'); ?>				
			</th>
			<th width="8%">
			<?php echo JText::_('ORDER'); ?>
			
			<?php if ($this->ordering) echo JHTML::_('grid.order',  $this->rows); ?>
			
			</th>
			<tfoot>
			<tr>
				<td colspan="15">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
			</tfoot>
		</tr>
	</thead>
	<tbody>
	<?php
	
	$help = new Adminhelper();
	$k = 0;
	for ($i=0, $n=count($this->rows); $i < $n; $i++)
	{
	
		$row = $this->rows[$i];
		$link="index.php?option=com_ijoomer&view=application_manager&task=edit&cid[]=".$row->id;
		
	?>
		<tr class="<?php echo "row$k"; ?>">
			<td align ="center">
				<?php echo $this->pagination->getRowOffset($i);?>  
			</td>
			<td class="order">
				<?php echo JHTML::_('grid.id', $i, $row->id ); ?>
			</td>
			<td>
				<a href="<?php echo $link; ?>" title="<?php echo JText::_('EDIT_PLIST_TITLE'); ?>	"><?php echo $row->treename; ?></a>
			</td>	
			<td>
				<?php echo $row->plist_value; ?>
			</td>	
			<td>
				<?php echo $row->description; ?>
			</td>
			<td class="order" nowrap="nowrap">
				<span><?php echo $this->pagination->orderUpIcon( $i, $row->parent == 0 || $row->parent == @$this->rows[$i-1]->parent, 'orderup', 'Move Up', $this->ordering );?></span>
				<span><?php echo $this->pagination->orderDownIcon( $i, $n,$row->parent == 0 || $row->parent == @$this->rows[$i+1]->parent,'orderdown', 'Move Down', $this->ordering);?></span>
				<?php $disabled = $this->ordering ?  '' : 'disabled="disabled"'; ?>
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php echo $disabled; ?> class="text_area" style="text-align: center" /> 
			</td>			
		</tr>
			<?php
		$k = 1 - $k;
		}
		?>
	</table>
	</tbody>
</div>

<div class="clr"></div>
<input type="hidden" name="task" value="bb" />
<input type="hidden" name="view" value="application_manager" />
<input type="hidden" name="boxchecked" value="" />
<input type="hidden" name="filter_order1" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir1" value="<?php echo $this->lists['order_Dir']; ?>" />
<input type="hidden" name="option" value="com_ijoomer" />
<input type="hidden" name="type" value="B" />

<?php echo JHTML::_( 'form.token' ); 
echo $this->pane->endPanel();
echo $this->pane->endPane();

?>

</form>