<?php defined('_JEXEC') or die('Restricted access'); 

//Ordering allowed ?
$ordering = ($this->lists['order'] == 'ordering');

//onsubmit="return submitform();"

//DEVNOTE: import html tooltips
JHTML::_('behavior.tooltip');

?>



<script language="javascript" type="text/javascript">
<?php
if (JOOMLA15) {
	?>
	function submitbutton(pressbutton) 
	<?php
} else {
	?>
	Joomla.submitbutton = function(pressbutton)
	<?php
}
?>	
	{
		var form = document.adminForm;
		if (pressbutton)
   		 {form.task.value=pressbutton;}
		if (pressbutton == 'edit' || pressbutton == 'add' || pressbutton == 'publish' || pressbutton == 'unpublish' || pressbutton == 'remove'
		) {
			form.view.value = 'qrcode_detail';
		}
	  form.submit();
	}
</script>


<form action="<?php echo $this->request_url; ?>" method="post" name="adminForm" >
<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="5">
				<?php echo JText::_( 'NUM' ); ?>
			</th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
			</th>
			<th class="title">
				<?php echo JHTML::_('grid.sort', JText::_('URL'), 'h.url', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				
			</th>
			<th class="title">
				<?php echo JHTML::_('grid.sort', JText::_('QR_CODE'), 'h.qrcode', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				
			</th>
			<th class="title">
				<?php echo JHTML::_('grid.sort', JText::_('DOWNLOAD'), 'h.download', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				
			</th>
		</tr>
	</thead>	
	<?php
	$k = 0;
	
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];

		$link 	= JRoute::_( 'index.php?option=com_joomadvertisement&controller=joomadvertisement_detail&task=edit&cid[]='. $row->id );

		$checked 	= JHTML::_('grid.checkedout',   $row, $i );
		$published 	= JHTML::_('grid.published', $row, $i );		

		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td>
				<span class="editlinktip hasTip" title="<?php echo $row->url; ?>::
                <a href="<?php echo $link; ?>" title="<?php echo JText::_( 'Edit joomadvertisement' ); ?>">
				<?php echo $row->url; ?></a>
                </span>
				<?php
				//}
				?>
			</td>
			<td>
			<?php $img_path = JURI::base()."components/com_ijoomer/qrimages/".$row->qrcode_image;
			?>
			<img src="<?php echo $img_path; ?>" width="100" />
			</td>
			<td>
			<a href="index.php?option=com_ijoomer&view=qrcode_detail&task=download&src=<?php echo $row->qrcode_image; ?>" > Download </a>
			</td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
<tfoot>
		<td colspan="9">
			<?php echo $this->pagination->getListFooter(); ?>
		</td>
	</tfoot>
	</table>
</div>

<input type="hidden" name="task" value="" /> <input type="hidden"
	name="view" value="plugins" /> <input type="hidden" name="boxchecked"
	value="" /> <input type="hidden" name="option" value="com_ijoomer" /> <input
	type="hidden" name="filter_order"
	value="<?php echo $this->lists['order']; ?>" /> <input type="hidden"
	name="filter_order_Dir"
	value="<?php echo $this->lists['order_Dir']; ?>" /></form>
</form>
