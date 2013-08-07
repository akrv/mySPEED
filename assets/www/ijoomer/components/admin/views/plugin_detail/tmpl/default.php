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
jimport('joomla.html.pane');
?>

<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) { 
		if(document.getElementById("product_list2")){ 	
			if(document.getElementById("product_list2").value)
			{
				var varToBox = document.getElementById("product_list2");
				for(i=0;i<varToBox.length;i++){
					varToBox.options[i].selected=true;
				}
			}
		}
		
		if(document.getElementById("BLOG_COMMENT_TIME"))
		{
			var val = document.getElementById("BLOG_COMMENT_TIME").value;
			
			if( val != "mix") 
			{
				document.getElementById("blogdate").style.display="none";
				document.getElementById("blogtime").style.display="none";
			}else{
				document.getElementById("blogdate").style.display="";
				document.getElementById("blogtime").style.display="";
			}
		}


		var form = document.adminForm;
			submitform( pressbutton );
			return;	
	}
</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
<?php 
 if(method_exists($this->class_obj,'show_configuration')){
	$res = $this->class_obj->show_configuration();
	
 }
?> 
<div class="clr"></div>
<div style="text-align:center"><?php echo JText::_('VERSION')." ".$this->version?></div>
<input type="hidden" name="plugin_id" value="<?php echo $this->detail->plugin_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="plugin_detail" />
</form>

