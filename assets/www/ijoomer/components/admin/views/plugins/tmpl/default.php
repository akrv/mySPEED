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
defined ( '_JEXEC' ) or die ( 'Restricted access' );

JHTML::_ ( 'behavior.tooltip' );
jimport ( 'joomla.html.pane' );
$pane = @JPane::getInstance ( 'sliders' );

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
			form.view.value = 'plugin_detail';
		}
	  form.submit();
	}
</script>

<form action="<?php
echo JRoute::_ ( $this->request_url )?>" method="post"
	name="adminForm" id="adminForm">
<div class="editcell">
<table width="100%" cellspacing="2" cellpadding="2">
	<tr>
		<td width="50%" valign="top">
		<table cellpadding="15" cellspacing="10" width="100%">
	<?php
	for($i = 0, $n = count ( $this->plugins ); $i < $n; $i += 3) {
		?>
		<tr>
			<?php
		for($j = 0; $j < 3; $j ++) {
			if (isset ( $this->plugins [$i + $j] )) {
				$row = & $this->plugins [$i + $j];
				//$row->id = $row->id;
				$link = JRoute::_ ( 'index.php?option=com_ijoomer&view=plugin_detail&task=edit&cid[]=' . $row->plugin_id );
				if(file_exists("components/com_ijoomer/assets/images/".$row->plugin_classname.".png")){
					$plg_img="components/com_ijoomer/assets/images/".$row->plugin_classname.".png";
				}else{
					$app = & JFactory::getApplication();
					$template = $app->getTemplate();
					$plg_img=JURI::base()."components".DS."com_ijoomer".DS."assets".DS."images".DS."default.png";
				}
				
				?>
			<td align="center" width="33%">
				<a href=<?php echo $link?>>
					<img src="<?php echo $plg_img?>" alt="<?php echo $row->plugin_name;?>" />
				</a><br />
				<?php if(basename($plg_img)=="default.png"){?>
				<span style="font-size:10px;"  title="<?php
				echo JText::_ ( 'PLUGIN_EDIT' );
				?>::<?php
				echo $row->plugin_name;
				?>">
					<a href="<?php
				echo $link;
				?>" ><?php
				echo $row->plugin_name;
				?></a>
				</span>
				<?php }?>
			</td>
		<?php
			} else
				echo "<td>&nbsp;</td>";
		}
		?>
		</tr>
		<?php
	}
	?>
	</table>
		</td>
		<td valign="top" width="100%">
		<table>
			<tr>
				<td>
		 <?php
			$title = JText::_ ( 'GENERAL_NOTE' );
			echo $pane->startPane ( 'start-pane' );
			echo $pane->startPanel ( $title, 'GENERAL_NOTE' );
			?>
			<div class="divnote">
			<p style="margin-bottom: 0cm;"><strong>How to Test?</strong></p>
<p ><strong>Follow the under mentioned steps:</strong></p>
<ol>
<li>
<p style="margin-bottom: 0cm; font-weight: normal;">Download 	iJoomer app from appstore Click here</p>
</li>
<li>
<p style="margin-bottom: 0cm; font-weight: normal;">Open the app 	and tap on “Settings” button.</p>
</li>
<li>
<p ><span style="font-weight: normal;">In 	there, write the url where iJoomer Component is installed and 	plug-ins are published. E.g. </span><span style="color: #0000ff;"><span style="text-decoration: underline;"><a href="http://www.yourdomain.com/"><span style="font-weight: normal;">www.yourdomain.com</span></a></span></span></p>
</li>
<li>
<p style="margin-bottom: 0cm; font-weight: normal;">App is ready 	to test, login with your user credentials which are already there on 	your domain.</p>
</li>
</ol>
<p ><strong>How do I get my own app?</strong></p>
<ol>
<li>
<p style="margin-bottom: 0cm; font-weight: normal;">If you have 	subscribed to the iJoomer, you can send in your artworks for your 	own branding and you will get the app build ready to test from our 	technical team.</p>
</li>
<li>
<p style="margin-bottom: 0cm; font-weight: normal;">On approval 	from your test, we will submit the build on your behalf to appstore 	(we taking pain out of submission process, so you don’t have to 	worry).</p>
</li>
<li>
<p style="margin-bottom: 0cm; font-weight: normal;">Once approved 	form the appstore, your app is live, it’s just 1 – 2 – 3. 	Isn’t it easy?</p>
</li>
</ol>
<p ><strong>Plug-ins Supported by </strong><span style="color: #0000ff;"><span style="text-decoration: underline;"><a href="http://www.ijoomer.com/" target="_blank"><strong>iJoomer.com for iPhone app<br /><br /></strong></a></span></span><strong>The following plug-ins needs to be added via iJoomer component (Plug-ins -&gt; New)</strong></p>
<p ><span style="color: #0000ff;"><span style="text-decoration: underline;"><a href="http://www.ijoomer.com/download/jomsocial_2.0.zip" target="_blank">Jom Social 2.0 Plug-in<br /></a><a href="http://www.ijoomer.com/download/jomsocial_2.2.zip" target="_blank">Jom Social 2.2 Plug-in<br /></a><a href="http://www.ijoomer.com/download/plg_myblog.zip" target="_blank">My Blog Plug-in<br /></a><a href="http://www.ijoomer.com/download/plg_jreview.zip" target="_blank">jReview Plug-in<br /></a><a href="http://www.ijoomer.com/download/plg_poll.zip" target="_blank">Joomla Poll Plug-in<br /></a><a href="http://www.ijoomer.com/download/plg_jevent.zip" target="_blank">jEvent Plug-in<br /></a><a href="http://www.ijoomer.com/download/plg_jbolo.zip" target="_blank">jBolo chat Plug-in<br /></a></span></span></p>
<p ><strong>For Push Notification &amp; Online friends, Joomla plug-ins should be installed which can be downloaded from the following link.</strong></p>
<p ><span style="color: #0000ff;"><span style="text-decoration: underline;"><a href="http://www.ijoomer.com/download/plg_push_request.zip" target="_blank">Jom Social Friend Request/Message Receive Plug-in</a><br /><a href="http://www.ijoomer.com/download/plg_push_online.zip" target="_blank">Friend Online Plug-in</a></span></span></p>
<p ><br />If you require any technical support just trail on to the forums at <span style="color: #0000ff;"><span style="text-decoration: underline;"><a href="http://www.ijoomer.com/Forum/">http://www.ijoomer.com/Forum/</a></span></span></p>
<p >For any additional support or inquiry, you can drop us an email at <span style="color: #0000ff;"><span style="text-decoration: underline;"><a href="mailto:support@ijoomer.com">support@ijoomer.com</a></span></span></p>
<p ><strong>Getting involved</strong></p>
<p >If you have got any suggestions or feedback, please do share it with us at <span style="color: #0000ff;"><span style="text-decoration: underline;"><a href="mailto:support@ijoomer.com">support@ijoomer.com</a></span></span>. Have fun using iJoomer!</p>
</p>
				</div>
		<?php 
		echo $pane->endPanel();
		 echo $pane->endPane();
		?>
		</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
</div>
<div class="clr"></div>
<input type="hidden" name="task" value="" /> <input type="hidden"
	name="view" value="plugins" /> <input type="hidden" name="boxchecked"
	value="" /> <input type="hidden" name="option" value="com_ijoomer" /> <input
	type="hidden" name="filter_order"
	value="<?php echo $this->lists['order']; ?>" /> <input type="hidden"
	name="filter_order_Dir"
	value="<?php echo $this->lists['order_Dir']; ?>" /></form>