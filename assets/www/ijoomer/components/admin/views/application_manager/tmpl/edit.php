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
 * 
 */

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
jimport('joomla.html.pane');


		if($this->row[0]->type=="I"){
			$folder="iphone";
		}
		if($this->row[0]->type=="A"){
			$folder="android";
		}
		if($this->row[0]->type=="B"){
			$folder="bb";
		}
		$folder="../images/com_ijoomer/".$folder;
?>

<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) { 
		var form = document.adminForm;
			form.task.value=pressbutton;
			submitform( pressbutton );
			return;
	}
	function check_j(fid){ 
				
	}
</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="application_manager" />
	<input type="hidden" name="type" value="<?php echo $this->row[0]->type; ?>" />
	<input type="hidden" name="pid" value="<?php echo $this->row[0]->id; ?>" />
	
	<fieldset>
		<legend><?php echo JText::_( 'Ordering' ); ?></legend>
		<table width="100%">
			<tr>
				<td>
					<?php echo $this->ordering; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="Save" value="Save" onclick="submitbutton('parentorder');" />
				</td>
			</tr>
		</table>
	</fieldset>
	
</form>

 <table width="100%">
  <?php for($i=0;$i<count($this->devices);$i=$i+2){ ?>
     <tr>
    	<td width="50%" valign="top">
    		<?php 
    			if(isset($this->devices[$i])){
    			 ?>
    			 	<fieldset>
    				<legend><?php
    				if($this->devices[$i]->devices!="COM_IJOOMER_ANY_DEVICE"){
    					 echo JText::_("COM_IJOOMER_FOR_DEVICES")." (".str_replace(",","/",$this->devices[$i]->devices).")" ;
    				}else{
    					 echo JText::_($this->devices[$i]->devices);
    				}
    				 ?></legend>
    				<form action="index.php?option=com_ijoomer&view=application_manager" name="admin_form<?php echo $this->devices[$i]->did; ?>" id="admin_form<?php echo $this->devices[$i]->did; ?>" enctype="multipart/form-data" method="post" >
    				<input type="hidden" name="id" value="<?php echo $this->devices[$i]->display_id; ?>" />
    				<input type="hidden" name="did" value="<?php echo $this->devices[$i]->did; ?>" />
    				<input type="hidden" name="pid" value="<?php echo $this->row[0]->id; ?>" />
    			    <input type="hidden" name="task" value="save_display">
    			    <input type="hidden" name="option" value="com_ijoomer">
    				<input type="hidden" name="view" value="application_manager">
    					<table style="text-align: left;" class="paramlist admintable">
    						<tr>
    							<th colspan="2"><?php echo JText::_( 'COM_IJOOMER_ICON_VIEW' ); //echo JText::_( 'COM_IJOOMER_TAB_VIEW' ); ?></th>
    						</tr>
		    		   		<tr>
					        	<td class="paramlist_key" width="30%">
						        	<span class="hasTip" title="<?php echo JText::_( 'COM_IJOOMER_DESIRED_ICON_SIZE_LBL' ); ?>::<?php echo JText::_('COM_IJOOMER_DESIRED_ICON_SIZE_EXPLAIN'); ?>">
										<?php echo JText::_( 'COM_IJOOMER_DESIRED_ICON_SIZE' ); ?>
									</span>
					            <td>
					            		<?php echo $this->devices[$i]->tab_icon_size; ?>
					            </td>
				            </tr>
				           <tr>
				            	<td class="paramlist_key" width="30%">
				            		<?php echo JText::_( 'COM_IJOOMER_CURRENT_IMAGE' ); ?>
				            	</td>
				            	<td>
				            		<?php 
				            			if($this->devices[$i]->tab_icon==""){
				            				echo JText::_( 'COM_IJOOMER_CURRENT_IMAGE_NOTSET' );
				            			}else{
				            				echo '<img src="'.$folder.'/'.$this->devices[$i]->tab_icon.'" border="0" />';	
				            			}
				            		?>
				            	</td>
				            </tr>
							<tr>
				            	<td class="paramlist_key" width="30%">
				            		<?php echo JText::_( 'COM_IJOOMER_UPLOAD_NEW' ); ?>
				            	</td>
				            	<td> <input type="file" name="tab" id="tab" /> </td>
				            </tr>
				           
				            <tr>
				            	<td class="paramlist_key" width="30%">
				            		<?php echo JText::_( 'COM_IJOOMER_SHOW_ON_DEVICE' ); ?>
				            	</td>
				            	<td> 
				            		<?php if($this->devices[$i]->show_tab==1){ ?>
				            			<input type="checkbox" name="tab_chk" id="tab_chk" value="1" checked="checked">
				            		<?php }else{ ?>
				            			<input type="checkbox" name="tab_chk" id="tab_chk" value="1">
				            		<?php } ?>
				            	 </td>
				            </tr>
				            
				            <tr>
    							<th colspan="2"><?php echo JText::_( 'COM_IJOOMER_TAB_VIEW' ); //echo JText::_( 'COM_IJOOMER_LIST_VIEW' ); ?></th>
    						</tr>
		    		   		<tr>
				        	<td class="paramlist_key" width="30%">
				        	<span class="hasTip" title="<?php echo JText::_( 'COM_IJOOMER_DESIRED_ICON_SIZE_LBL' ); ?>::<?php echo JText::_('COM_IJOOMER_DESIRED_ICON_SIZE_EXPLAIN'); ?>">
								<?php echo JText::_( 'COM_IJOOMER_DESIRED_ICON_SIZE' ); ?>
							</span>
				            <td>
				            	<?php echo $this->devices[$i]->list_icon_size; ?>
				            </td>
				        	</tr>
				        	 <?php if($this->row[0]->type!="I"){ ?>
				        	 <tr>
				            	<td class="paramlist_key" width="30%">
				            		<?php echo JText::_( 'COM_IJOOMER_CURRENT_IMAGE_ONFOCUS' ); ?>
				            	</td>
				            	<td>
				            		<?php 
				            			if($this->devices[$i]->list_icon==""){
				            				echo JText::_( 'COM_IJOOMER_CURRENT_IMAGE_NOTSET' );
				            			}else{
				            				echo '<img src="'.$folder.'/'.$this->devices[$i]->list_icon.'" border="0" />';	
				            			}
				            		?>
				            	</td>
				            </tr>
				            <tr>
				            	<td class="paramlist_key" width="30%">
				            		<?php echo JText::_( 'COM_IJOOMER_ONFOCUS_UPLOAD_NEW' ); ?>
				            	</td>
				            	<td> <input type="file" name="list" id="list" /> </td>
				            </tr>
				            <tr>
				            	<td class="paramlist_key" width="30%">
				            		<?php echo JText::_( 'COM_IJOOMER_CURRENT_IMAGE_NORMAL' ); ?>
				            	</td>
				            	<td>
				            		<?php 
				            			if($this->devices[$i]->list_icon==""){
				            				echo JText::_( 'COM_IJOOMER_CURRENT_IMAGE_NOTSET' );
				            			}else{
				            				echo '<img src="'.$folder.'/'.$this->devices[$i]->list_icon2.'" border="0" />';	
				            			}
				            		?>
				            	</td>
				            </tr>
				            <tr>
				            	<td class="paramlist_key" width="30%">
				            		<?php echo JText::_( 'COM_IJOOMER_NORMAL_UPLOAD_NEW' ); ?>
				            	</td>
				            	<td> <input type="file" name="list2" id="list2" /> </td>
				            </tr>
				        	 <?php }else{ ?>
				        	<tr>
				            	<td class="paramlist_key" width="30%">
				            		<?php echo JText::_( 'COM_IJOOMER_CURRENT_IMAGE' ); ?>
				            	</td>
				            	<td>
				            		<?php 
				            			if($this->devices[$i]->list_icon==""){
				            				echo JText::_( 'COM_IJOOMER_CURRENT_IMAGE_NOTSET' );
				            			}else{
				            				echo '<img src="'.$folder.'/'.$this->devices[$i]->list_icon.'" border="0" />';	
				            			}
				            		?>
				            	</td>
				            </tr>
				            <tr>
				            	<td class="paramlist_key" width="30%">
				            		<?php echo JText::_( 'COM_IJOOMER_UPLOAD_NEW' ); ?>
				            	</td>
				            	<td> <input type="file" name="list" id="list" /> </td>
				            </tr>
				            <?php } ?>
				            <tr>
				            	<td class="paramlist_key" width="30%">
				            		<?php echo JText::_( 'COM_IJOOMER_SHOW_ON_DEVICE' ); ?>
				            	</td>
				            	<td> 
				            		<?php if($this->devices[$i]->show_list==1){ ?>
				            			<input type="checkbox" name="list_chk" id="list_chk" value="1" checked="checked">
				            		<?php }else{ ?>
				            			<input type="checkbox" name="list_chk" id="list_chk" value="1">
				            		<?php } ?>
				            	 </td>
				            </tr>
				            <tr>
				            	<td colspan="2" align="right">
				            		<input type="submit" value="Upload" name="submit"  />
				            	</td>
				            </tr>
			        	</table>
			        </form>
    				</fieldset>
    			 <?php 	
    			}
    		?>
    	</td>
    	<td width="50%" valign="top">
    		<?php 
 				if(isset($this->devices[$i+1])){
    			?>
    				<fieldset>
    				<legend><?php 
 					if($this->devices[$i+1]->devices!="COM_IJOOMER_ANY_DEVICE"){
    					 echo JText::_("COM_IJOOMER_FOR_DEVICES")." (".str_replace(",","/",$this->devices[$i+1]->devices).")" ;
    				}else{
    					 echo JText::_($this->devices[$i+1]->devices);
    				}
    				?></legend>
    				<form action="index.php?option=com_ijoomer&view=application_manager" name="admin_form<?php echo $this->devices[$i+1]->did; ?>" id="admin_form<?php echo $this->devices[$i+1]->did; ?>" enctype="multipart/form-data"  method="post" >
    				<input type="hidden" name="id" value="<?php echo $this->devices[$i+1]->display_id; ?>" />
    				<input type="hidden" name="did" value="<?php echo $this->devices[$i+1]->did; ?>" />
    				<input type="hidden" name="pid" value="<?php echo $this->row[0]->id; ?>" />
    				<input type="hidden" name="task" value="save_display">
    				<input type="hidden" name="option" value="com_ijoomer">
    				<input type="hidden" name="view" value="application_manager">
    				<table style="text-align: left;" class="paramlist admintable">
    						<tr>
    							<th colspan="2"><?php echo JText::_( 'COM_IJOOMER_ICON_VIEW' ); //echo JText::_( 'COM_IJOOMER_TAB_VIEW' ); ?></th>
    						</tr>
		    		   		<tr>
					        	<td class="paramlist_key" width="30%">
						        	<span class="hasTip" title="<?php echo JText::_( 'COM_IJOOMER_DESIRED_ICON_SIZE_LBL' ); ?>::<?php echo JText::_('COM_IJOOMER_DESIRED_ICON_SIZE_EXPLAIN'); ?>">
										<?php echo JText::_( 'COM_IJOOMER_DESIRED_ICON_SIZE' ); ?>
									</span>
					            <td>
					            		<?php echo $this->devices[$i+1]->tab_icon_size; ?>
					            </td>
				            </tr>
				           
				           	<tr>
				            	<td class="paramlist_key" width="30%">
				            		<?php echo JText::_( 'COM_IJOOMER_CURRENT_IMAGE' ); ?>
				            	</td>
				            	<td>
				            		<?php 
				            			if($this->devices[$i+1]->tab_icon==""){
				            				echo JText::_( 'COM_IJOOMER_CURRENT_IMAGE_NOTSET' );
				            			}else{
				            				echo '<img src="'.$folder.'/'.$this->devices[$i+1]->tab_icon.'" border="0" />';	
				            			}
				            		?>
				            	</td>
				            </tr>
							<tr>
				            	<td class="paramlist_key" width="30%">
				            		<?php echo JText::_( 'COM_IJOOMER_UPLOAD_NEW' ); ?>
				            	</td>
				            	<td> <input type="file" name="tab" id="tab" /> </td>
				            </tr>
				            
				         
				            <tr>
				            	<td class="paramlist_key" width="30%">
				            		<?php echo JText::_( 'COM_IJOOMER_SHOW_ON_DEVICE' ); ?>
				            	</td>
				            	<td> 
				            		<?php if($this->devices[$i+1]->show_tab==1){ ?>
				            			<input type="checkbox" name="tab_chk" id="tab_chk" value="1" checked="checked">
				            		<?php }else{ ?>
				            			<input type="checkbox" name="tab_chk" id="tab_chk" value="1">
				            		<?php } ?>
				            	 </td>
				            </tr>
				            
				            <tr>
    							<th colspan="2"><?php echo JText::_( 'COM_IJOOMER_TAB_VIEW' ); //echo JText::_( 'COM_IJOOMER_LIST_VIEW' ); ?></th>
    						</tr>
		    		   		<tr>
				        	<td class="paramlist_key" width="30%">
				        	<span class="hasTip" title="<?php echo JText::_( 'COM_IJOOMER_DESIRED_ICON_SIZE_LBL' ); ?>::<?php echo JText::_('COM_IJOOMER_DESIRED_ICON_SIZE_EXPLAIN'); ?>">
								<?php echo JText::_( 'COM_IJOOMER_DESIRED_ICON_SIZE' ); ?>
							</span>
				            <td>
				            	<?php echo $this->devices[$i+1]->list_icon_size; ?>
				            </td>
				        	</tr>
				        	 <?php if($this->row[0]->type!="I"){ ?>
				        	  <tr>
				            	<td class="paramlist_key" width="30%">
				            		<?php echo JText::_( 'COM_IJOOMER_CURRENT_IMAGE_ONFOCUS' ); ?>
				            	</td>
				            	<td>
				            		<?php 
				            			if($this->devices[$i+1]->list_icon==""){
				            				echo JText::_( 'COM_IJOOMER_CURRENT_IMAGE_NOTSET' );
				            			}else{
				            				echo '<img src="'.$folder.'/'.$this->devices[$i+1]->list_icon.'" border="0" />';	
				            			}
				            		?>
				            	</td>
				            </tr>
				            <tr>
				            	<td class="paramlist_key" width="30%">
				            		<?php echo JText::_( 'COM_IJOOMER_ONFOCUS_UPLOAD_NEW' ); ?>
				            	</td>
				            	<td> <input type="file" name="list" id="list" /> </td>
				            </tr>
				            
				             <tr>
				            	<td class="paramlist_key" width="30%">
				            		<?php echo JText::_( 'COM_IJOOMER_CURRENT_IMAGE_NORMAL' ); ?>
				            	</td>
				            	<td>
				            		<?php 
				            			if($this->devices[$i+1]->list_icon==""){
				            				echo JText::_( 'COM_IJOOMER_CURRENT_IMAGE_NOTSET' );
				            			}else{
				            				echo '<img src="'.$folder.'/'.$this->devices[$i+1]->list_icon2.'" border="0" />';	
				            			}
				            		?>
				            	</td>
				            </tr>
				            <tr>
				            	<td class="paramlist_key" width="30%">
				            		<?php echo JText::_( 'COM_IJOOMER_NORMAL_UPLOAD_NEW' ); ?>
				            	</td>
				            	<td> <input type="file" name="list2" id="list2" /> </td>
				            </tr>
				        	 <?php }else{ ?>
				        	<tr>
				            	<td class="paramlist_key" width="30%">
				            		<?php echo JText::_( 'COM_IJOOMER_CURRENT_IMAGE' ); ?>
				            	</td>
				            	<td>
				            		<?php 
				            			if($this->devices[$i+1]->list_icon==""){
				            				echo JText::_( 'COM_IJOOMER_CURRENT_IMAGE_NOTSET' );
				            			}else{
				            				echo '<img src="'.$folder.'/'.$this->devices[$i+1]->list_icon.'" border="0" />';	
				            			}
				            		?>
				            	</td>
				            </tr>
				            <tr>
				            	<td class="paramlist_key" width="30%">
				            		<?php echo JText::_( 'COM_IJOOMER_UPLOAD_NEW' ); ?>
				            	</td>
				            	<td> <input type="file" name="list" id="list" /> </td>
				            </tr>
				            <?php } ?>
				            <tr>
				            	<td class="paramlist_key" width="30%">
				            		<?php echo JText::_( 'COM_IJOOMER_SHOW_ON_DEVICE' ); ?>
				            	</td>
				            	<td> 
					            	<?php if($this->devices[$i+1]->show_list==1){ ?>
					            			<input type="checkbox" name="list_chk" id="list_chk" value="1" checked="checked">
					            	<?php }else{ ?>
					            			<input type="checkbox" name="list_chk" id="list_chk" value="1">
					            	<?php } ?>
				            	 </td>
				            </tr>
				            <tr>
				            	<td colspan="2" align="right">
				            		<input type="submit" value="Upload" name="submit"  />
				            	</td>
				            </tr>
			        	</table>
			        	</form>
    				</fieldset>
    			<?php 	
    			}
    		?>
    	</td>
    </tr>
 <?php } ?>
  </table>


