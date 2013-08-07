 <script type="text/javascript">
 function allselections_i() {
		var e_i = document.getElementById('pidi');
		
			e_i.disabled = true;
			var i = 0;
			var n = e_i.options.length;
			for (i = 0; i < n; i++) {
				e_i.options[i].disabled = true;
				e_i.options[i].selected = true;
			}
			
}
 function allselections_a() {
		
		var e_a = document.getElementById('pida');
		
			e_a.disabled = true;
			var i = 0;
			var n = e_a.options.length;
			for (i = 0; i < n; i++) {
				e_a.options[i].disabled = true;
				e_a.options[i].selected = true;
			}
				
}
function allselections_b() {
		
		var e_b = document.getElementById('pidb');

			e_b.disabled = true;
			var i = 0;
			var n = e_b.options.length;
			for (i = 0; i < n; i++) {
				e_b.options[i].disabled = true;
				e_b.options[i].selected = true;
			}	
}
 
function disableselections_i() {
	var e_i = document.getElementById('pidi');
			e_i.disabled = true;
		var i = 0;
		var n = e_i.options.length;
		for (i = 0; i < n; i++) {
			e_i.options[i].disabled = true;
			e_i.options[i].selected = false;
		}
		
}
function disableselections_a() {
	
	var e_a = document.getElementById('pida');
	
			e_a.disabled = true;
		var i = 0;
		var n = e_a.options.length;
		for (i = 0; i < n; i++) {
			e_a.options[i].disabled = true;
			e_a.options[i].selected = false;
		}	
}
function disableselections_b() {
	
	var e_b = document.getElementById('pidb');

		e_b.disabled = true;
		var i = 0;
		var n = e_b.options.length;
		for (i = 0; i < n; i++) {
			e_b.options[i].disabled = true;
			e_b.options[i].selected = false;
		}
		
}
function enableselections_i() {
	var e_i = document.getElementById('pidi');
		
			e_i.disabled = false;
			var i = 0;
			var n = e_i.options.length;
		for (i = 0; i < n; i++) {
			e_i.options[i].disabled = false;
		}	
}
function enableselections_a() {
	
	var e_a = document.getElementById('pida');
	
			e_a.disabled = false;
			var i = 0;
			var n = e_a.options.length;
		for (i = 0; i < n; i++) {
			e_a.options[i].disabled = false;
		}
		
}
function enableselections_b() {
	
	var e_b = document.getElementById('pidb');
	
		e_b.disabled = false;
			var i = 0;
			var n = e_b.options.length;
		for (i = 0; i < n; i++) {
			e_b.options[i].disabled = false;
		}
		
}
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
				if (pressbutton == 'save' || pressbutton == 'apply')
				{		
					form.view.value = 'app_module_detail';
				}
			  form.submit();
}
</script>
<?php
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');
?>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<table width="100%">
	<?php 
		for($i=0,$n=count($this->module_detail);$i<$n;$i++)
		{
			$row = $this->module_detail[$i];
			//echo "<pre>";print_r($row);exit;
		}
		
	?> 
	<tr>
	<td width="50%" valign="top">
	<fieldset>
	<legend>Details</legend>
	<table style="text-align: left;" class="paramlist admintable">
		<tr>
			<td valign="top" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'APP_TYPE' ); ?>::<?php echo JText::_('APP_TYPE_EXPLAIN'); ?>">
						<?php echo JText::_( 'APP_TYPE' ); ?>
					</span>
			</td>
			<td>
					<strong>
							<?php echo JText::_($row->type); ?>
					</strong>
			</td>
		</tr>
		<tr>
			<td class="paramlist_key" width="40%">
					<span class="hasTip" title="<?php echo JText::_( 'APP_NAME' ); ?>::<?php echo JText::_('APP_NAME_EXPLAIN'); ?>">
						<?php echo JText::_( 'APP_NAME' ); ?>
					</span>	
			</td>
			<td>
		    		<input id="name" class="text_area" type="text" value="<?php echo $row->name;?>" size="35" name="name">
		    </td>
		<tr>	
		<tr>
			<td class="paramlist_key" width="40%">
					<span class="hasTip" title="<?php echo JText::_( 'APP_TITLE' ); ?>::<?php echo JText::_('APP_TITLE_EXPLAIN'); ?>">
						<?php echo JText::_( 'APP_TITLE' ); ?>
					</span>	
			</td>
			<td>
		    		<?php echo JHTML :: _('select.booleanlist','title','',$row->title); ?>
		    </td>
		<tr>
			<td class="paramlist_key" width="40%">
					 	<span class="hasTip" title="<?php echo JText::_( 'APP_ENABLE' ); ?>::<?php echo JText::_('APP_ENABLE_EXPLAIN'); ?>">
							<?php echo JText::_( 'APP_ENABLE' ); ?>
						</span>
		    		</td>
		    		
					<td>
		    			<?php echo JHTML :: _('select.booleanlist','published','',$row->published); ?>
		    		</td>
		</tr>
		<tr>
			<td class="paramlist_key" width="40%">
					<span class="hasTip" title="<?php echo JText::_( 'APP_ORDER' ); ?>::<?php echo JText::_('APP_ORDER_EXPLAIN'); ?>">
						<?php echo JText::_( 'APP_ORDER' ); ?>
					</span>	
			</td>
				<td>	
					  <select name="ordering" id ="ordering" class="inputbox" >
							<option value="<?php echo $row->ordering ?>"><?php echo $row->ordering.'::'.$row->name ?></option>
					</select>
				</td>
		</tr>
		<tr>
			<td class="paramlist_key" width="40%">
					<span class="hasTip" title="<?php echo JText::_( 'APP_ACCESS' ); ?>::<?php echo JText::_('APP_ACCESS_EXPLAIN'); ?>">
						<?php echo JText::_( 'APP_ACCESS' ); ?>
					</span>	
			</td>
				<td>
					  <select name="access" id ="access" class="inputbox" multiple = "multiple">
					  
					  <?php
					  		foreach($this->group as $grp)
					  		{?>
					  			<option value="<?php echo $grp->id?>"  <?php if($row->access == $grp->id) {echo "selected='selected'";}?> ><?php echo $grp->name?></option>
					  		<?php }
					  ?>
							
					</select>
				</td>
		</tr>
		<tr>
			<td class="paramlist_key" width="40%">
					<span class="hasTip" title="<?php echo JText::_( 'APP_DESC' ); ?>::<?php echo JText::_('APP_DESC_EXPLAIN'); ?>">
						<?php echo JText::_( 'APP_DESC' ); ?>
					</span>	
			</td>	
				<td><?php echo JText::_($row->description); ?></td>
		</tr>
		</table>
		</fieldset>
		
		<fieldset>
		<legend>Menu Assignment</legend>
		<table style="text-align: left;" class="paramlist admintable">
    	<thead>
    		<tr>
    			<th></th>
    			<th align="center">Iphone</th>
    			<th align="center">Android</th>
    			<th align="center">Blackberry</th>
    		</tr>
    	</thead>
		<tr>
			<td class="paramlist_key" width="40%">
					<span class="hasTip" title="<?php echo JText::_( 'APP_SCREEN' ); ?>::<?php echo JText::_('APP_SCREEN_EXPLAIN'); ?>">
						<?php echo JText::_( 'APP_SCREEN' ); ?>
					</span>	
			</td>
			
			<td>		
				<?php
				if($row->screens_i == 'all'){?>
				<label for="screens-all"><input id="screens_i" type="radio" checked="checked" onclick="allselections_i();" value="all" name="screens_i">All</label>
				
				<label for="screens-none"><input id="screens_i" type="radio" onclick="disableselections_i();" value="none" name="screens_i">None</label>
			
				<label for="screens-select"><input id="screens_i" type="radio" onclick="enableselections_i();" value="select" name="screens_i">Select Screen(s)</label>
			<?php }else if($row->screens_i == 'none'){?>
				<label for="screens-all"><input id="screens_i" type="radio" onclick="allselections_i();" value="all" name="screens_i">All</label>
				
				<label for="screens-none"><input id="screens_i" type="radio" checked="checked" onclick="disableselections_i();" value="none" name="screens_i">None</label>
			
				<label for="screens-select"><input id="screens_i" type="radio" onclick="enableselections_i();" value="select" name="screens_i">Select Screen(s)</label>
			<?php }else{?>
				<label for="screens-all"><input id="screens_i" type="radio" onclick="allselections_i();" value="all" name="screens_i">All</label>
				
				<label for="screens-none"><input id="screens_i" type="radio" onclick="disableselections_i();" value="none" name="screens_i">None</label>
			
				<label for="screens-select"><input id="screens_i" type="radio" checked="checked" onclick="enableselections_i();" value="select" name="screens_i">Select Screen(s)</label>
			<?php }?>
			</td>
			<!--  <td class="paramlist_key" width="40%">
					<span class="hasTip" title="<?php //echo JText::_( 'APP_SCREEN' ); ?>::<?php //echo JText::_('APP_SCREEN_EXPLAIN'); ?>">
						<?php //echo JText::_( 'APP_SCREEN' ); ?>
					</span>	
			</td>-->
			<td>		
				<?php
				if($row->screens_a == 'all'){?>
				<label for="screens-all"><input id="screens_a" type="radio" checked="checked" onclick="allselections_a();" value="all" name="screens_a">All</label>
				
				<label for="screens-none"><input id="screens_a" type="radio" onclick="disableselections_a();" value="none" name="screens_a">None</label>
			
				<label for="screens-select"><input id="screens_a" type="radio" onclick="enableselections_a();" value="select" name="screens_a">Select Screen(s)</label>
			<?php }else if($row->screens_a == 'none'){?>
				<label for="screens-all"><input id="screens_a" type="radio" onclick="allselections_a();" value="all" name="screens_a">All</label>
				
				<label for="screens-none"><input id="screens_a" type="radio" checked="checked" onclick="disableselections_a();" value="none" name="screens_a">None</label>
			
				<label for="screens-select"><input id="screens_a" type="radio" onclick="enableselections_a();" value="select" name="screens_a">Select Screen(s)</label>
			<?php }else{?>
				<label for="screens-all"><input id="screens_a" type="radio" onclick="allselections_a();" value="all" name="screens_a">All</label>
				
				<label for="screens-none"><input id="screens_a" type="radio" onclick="disableselections_a();" value="none" name="screens_a">None</label>
			
				<label for="screens-select"><input id="screens_a" type="radio" checked="checked" onclick="enableselections_a();" value="select" name="screens_a">Select Screen(s)</label>
			<?php }?>
			</td>
			<!--  <td class="paramlist_key" width="40%">
					<span class="hasTip" title="<?php //echo JText::_( 'APP_SCREEN' ); ?>::<?php //echo JText::_('APP_SCREEN_EXPLAIN'); ?>">
						<?php //echo JText::_( 'APP_SCREEN' ); ?>
					</span>	
			</td>-->
			<td>		
				<?php
				if($row->screens_b == 'all'){?>
				<label for="screens-all"><input id="screens_b" type="radio" checked="checked" onclick="allselections_b();" value="all" name="screens_b">All</label>
				
				<label for="screens-none"><input id="screens_b" type="radio" onclick="disableselections_b();" value="none" name="screens_b">None</label>
			
				<label for="screens-select"><input id="screens_b" type="radio" onclick="enableselections_b();" value="select" name="screens_b">Select Screen(s)</label>
			<?php }else if($row->screens_b == 'none'){?>
				<label for="screens-all"><input id="screens_b" type="radio" onclick="allselections_b();" value="all" name="screens_b">All</label>
				
				<label for="screens-none"><input id="screens_b" type="radio" checked="checked" onclick="disableselections_b();" value="none" name="screens_b">None</label>
			
				<label for="screens-select"><input id="screens_b" type="radio" onclick="enableselections_b();" value="select" name="screens_b">Select Screen(s)</label>
			<?php }else{?>
				<label for="screens-all"><input id="screens_b" type="radio" onclick="allselections_b();" value="all" name="screens_b">All</label>
				
				<label for="screens-none"><input id="screens_b" type="radio" onclick="disableselections_b();" value="none" name="screens_b">None</label>
			
				<label for="screens-select"><input id="screens_b" type="radio" checked="checked" onclick="enableselections_b();" value="select" name="screens_b">Select Screen(s)</label>
			<?php }?>
			</td>
		</tr>
		<tr>
			<td class="paramlist_key" width="40%">
					<span class="hasTip" title="<?php echo JText::_( 'APP_SCREEN_LIST' ); ?>::<?php echo JText::_('APP_SCREEN_LIST_EXPLAIN'); ?>">
						<?php echo JText::_( 'APP_SCREEN_LIST' ); ?>
					</span>	
			</td>
			<td width="25%">
				<select id="pidi" class="inputbox" multiple="multiple" name="pidi[]" style="width:180px;height:150px;">
				<?php 
						$resi = $row->pidi;
						$resArrayi = explode(',',$resi);
						foreach ($this->screen as $ke)
						{	if($ke->type == 'I')	
							{?>
								<?php if(in_array($ke->id, $resArrayi)) { $tmp = "selected "; } else { $tmp = ''; } ?>
									<option value="<?php echo $ke->id?>" <?php echo $tmp; ?>><?php echo $ke->plist_name;?></option>
								<?php
							}	 
						} 
				?>
			</select>
		</td>
		<!--  <td class="paramlist_key" width="40%">
					<span class="hasTip" title="<?php //echo JText::_( 'APP_SCREEN_LIST' ); ?>::<?php //echo JText::_('APP_SCREEN_LIST_EXPLAIN'); ?>">
						<?php //echo JText::_( 'APP_SCREEN_LIST' ); ?>
					</span>	
			</td>-->
			<td width="25%">
				<select id="pida" class="inputbox" multiple="multiple" name="pida[]" style="width:180px;height:150px;">
				<?php 
						$resa = $row->pida;
						$resArraya = explode(',',$resa);
						foreach ($this->screen as $ke)
						{	if($ke->type == 'A')	
							{?>
								<?php if(in_array($ke->id, $resArraya)) { $tmp = "selected "; } else { $tmp = ''; } ?>
									<option value="<?php echo $ke->id?>" <?php echo $tmp; ?>><?php echo $ke->plist_name;?></option>
								<?php
							}	 
						} 
				?>
			</select>
		</td>
		<!--  <td class="paramlist_key" width="40%">
					<span class="hasTip" title="<?php //echo JText::_( 'APP_SCREEN_LIST' ); ?>::<?php //echo JText::_('APP_SCREEN_LIST_EXPLAIN'); ?>">
						<?php //echo JText::_( 'APP_SCREEN_LIST' ); ?>
					</span>	
			</td>-->
			<td width="25%">
				<select id="pidb" class="inputbox" multiple="multiple" name="pidb[]" style="width:180px;height:150px;">
				<?php 
						$resb = $row->pidb;
						$resArrayb = explode(',',$resb);
						foreach ($this->screen as $ke)
						{	if($ke->type == 'B')	
							{?>
								<?php if(in_array($ke->id, $resArrayb)) { $tmp = "selected = 'selected' "; } else { $tmp = ''; } ?>
									<option value="<?php echo $ke->id?>" <?php echo $tmp; ?>><?php echo $ke->plist_name;?></option>
								<?php
							}	 
						} 
				?>
			</select>
		</td>
		</tr>
	</table>
		<?php if ($row->screens_i == 'all') { ?>
		<script type="text/javascript">allselections_i();</script>
		<?php } elseif ($row->screens_i == 'none') { ?>
		<script type="text/javascript">disableselections_i();</script>
		<?php } else { ?>
		<?php } ?>
		<?php if ($row->screens_a == 'all') { ?>
		<script type="text/javascript">allselections_a();</script>
		<?php } elseif ($row->screens_a == 'none') { ?>
		<script type="text/javascript">disableselections_a();</script>
		<?php } else { ?>
		<?php } ?>
		<?php if ($row->screens_b == 'all') { ?>
		<script type="text/javascript">allselections_b();</script>
		<?php } elseif ($row->screens_b == 'none') { ?>
		<script type="text/javascript">disableselections_b();</script>
		<?php } else { ?>
		<?php } ?>
	</fieldset>
</td>	
</tr>	
</table>		
<div class="clr"></div>
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="app_module_detail" />
<input type="hidden" name="id" value="<?php echo $row->id;?>"/>
<input type="hidden" name="option" value="com_ijoomer" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>