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
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
	}
</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'INSTALL_NEW_PLUGIN' ); ?></legend>

		<table class="admintable" width="100%">
		 
		 <tr>
			 <td><input type="file" name="install_plugin" size="75"> <input type="submit" value="Install"></td>
			 		 </tr>		
	  </table>
	</fieldset>
</div> 
<div class="clr"></div>

<input type="hidden" name="task" value="install" />
<input type="hidden" name="view" value="plugin_detail" />
</form>

