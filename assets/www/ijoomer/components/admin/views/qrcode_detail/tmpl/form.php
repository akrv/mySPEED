<?php

/**
 * @version $Id: component.php 5173 2006-09-25 18:12:39Z Jinx $
 * @package Joomla
 * @subpackage Config
 * @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 * 
 * php echo $lang->getName();  
 */
defined('_JEXEC') or die('Restricted access');

//DEVNOTE: import html tooltips
JHTML::_('behavior.tooltip');
?>
<link rel="stylesheet" type="text/css" href="style.css">
	<script src="components/com_ijoomer/QR_Code/jquery.js"></script>
	<!-- App files -->
	<link rel="stylesheet" type="text/css" href="components/com_ijoomer/QR_Code/qrcode_app/css/style.css">
	<script src="components/com_ijoomer/QR_Code/qrcode_app/js/json2.js"></script>
	<script src="components/com_ijoomer/QR_Code/qrcode_app/js/core.js"></script>
	<script src="components/com_ijoomer/QR_Code/qrcode_app/js/qrcode.js"></script>
	<script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<!-- script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		
		
		var form = document.adminForm;
		var oldposition ='<?php //echo $this->detail->position; ?>';
		var oldimg ='<?php //echo $this->detail->advertise_image;?>';
		var newposition = document.getElementById('position').value;
		var newimg      = document.getElementById('advertise_image').value;
		
		if (pressbutton == 'cancel') 
		{
			submitform( pressbutton );
			return;
		}
		if(oldposition != newposition)
		{
			if(oldposition == '1' && newposition != '2' && newimg == '')
			{
				alert("Please Upload new image");
				return false; 
			}
			if(oldposition == '2' && newposition != '1' && newimg == '')
			{
				alert("Please Upload new image");
				return false;
			}
			if((oldposition == '3' && newposition != '4') && newimg == '')
			{
				alert("Please Upload new image");
				return false;
			}
			if(oldposition == '4' && newposition != '3' && newimg == '')
			{
				alert("Please Upload new image");
				return false;
			}
		}
		// do field validation
		if (form.title.value == "")
		{			
			alert( "<?php //echo JText::_( 'TITLE_MSG', true ); ?>" ); return false;
		}
		//alert(oldimg);
		if(!oldimg && newimg=="") 
		{
			alert( "<?php //echo JText::_( 'BLANK_IMG', true ); ?>" );
		}
		if(newposition == '0')
		{
			alert( "<?php //echo JText::_( 'POSITION', true ); ?>" );
		}
		else 
		{
			submitform( pressbutton );
		}
	}
	
	

</script-->
<script language="javascript" type="text/javascript">
var _gaq = _gaq || [];

	_gaq.push(['_setAccount', 'UA-16484923-2']);

	_gaq.push(['_trackPageview']);

	(function() {

		var ga = document.createElement('script');
		ga.type = 'text/javascript';
		ga.async = true;

		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';

		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(ga, s);

	})();
	
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
					var url = document.getElementById('output_img_qr').src;
					document.getElementById('output_img_qr_src').setAttribute('value', url);
					if (pressbutton == 'cancel') 
					{
						submitform( pressbutton );
						return;
					}
					else 
					{
						submitform( pressbutton );
					}
				}
</script>	
<style type="text/css">
	table.paramlist td.paramlist_key {
		width: 92px;
		text-align: left;
		height: 30px;
	}
</style>

<form method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<!-- div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'DETAILS' ); ?></legend>

		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="title" title="<?php echo JText::_( 'ENTER_URL_EXPLAIN' ); ?>">
					<?php //echo JText::_( 'ENTER_URL' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="title" id="title" size="32" maxlength="250" value="<?php echo $this->detail->title;?>" />
			</td>
		</tr>
	</table>
	</fieldset>
</div-->
<div id="main">
		<div id="header" style="display:none;">
			<div id="logo">
				<h1>QrCode Generator App</h1>
			</div>
			<div id="buy">
				<a class="round large button buy" href="http://codecanyon.net/item/qrcode-generator-app/462044?ref=omarabid">
				<span>
					Buy It
					<small>
						$8
					</small>
				</span>
				</a>
			</div>
			<div class="clear">
			</div>
		</div>
		<div id="content">
			<!-- QrCode APP Structure -->
			<div id="qrcode_app">
				<div id="display">
					<h2>QrCode</h2>
					<div id="output_img">
						<img id="output_img_loading" src="components/com_ijoomer/QR_Code/qrcode_app/img/blank.png"/>
						<img id="output_img_qr" src="" style="max-height: 230px; max-width: 230px" name="output_img_qr"/>
						<input id="output_img_qr_src" type="hidden" name="output_img_qr_src" value="" />
					</div>
					<!-- h2>QrCode Link</h2-->
					<div id="output_link" style="display:none;">
						<input id="output_url"/>
						<a href="#" id="select_url" class="css3_btn black">S</a>
					</div>
					<!--  h2>Short URL</h2-->
					<div id="short_link" style="display:none;">
						<input id="short_url"/>
						<a href="#" id="select_short_url" class="css3_btn black">S</a>
					</div>
					<div id="download" style="display:none">
						<h2>Download</h2>
						<ul>
							<li id="download_png">
								<img src="components/com_ijoomer/QR_Code/qrcode_app/img/png.png">
							</li>
							<li id="download_gif">
								<img src="components/com_ijoomer/QR_Code/qrcode_app/img/gif.png">
							</li>
							<li id="download_jpg">
								<img src="components/com_ijoomer/QR_Code/qrcode_app/img/jpg.png">
							</li>
						</ul>
					</div>
				</div>
				<div id="input">
					<h2>Enter Content</h2>
					<div id="types" style="display:none;">
						Type :
						<select>
							<option value="link">Link</option>
						</select>
					</div>
					<div id="tabs">
						<div id="link" class="tab">
							<fieldset>
							<label>
									Website URL
								</label>
								<input id="link_url" name="link_url">
							</fieldset>
						</div>
						<div id="email" class="tab">
							<fieldset>
								<label>
									Email
								</label>
								<input id="email_email">
							</fieldset>
							<fieldset>
								<label>
									Body
								</label>
								<textarea id="email_body">
								</textarea>
							</fieldset>
						</div>
						<div id="call" class="tab">
							<fieldset>
								<label>
									Number
								</label>
								<input id="call_number"/>
							</fieldset>
						</div>
						<div id="sms" class="tab">
							<fieldset>
								<label>
									Number
								</label>
								<input id="sms_number"/>
							</fieldset>
							<fieldset>
								<label>
									Message
								</label>
								<textarea id="sms_message">
								</textarea>
							</fieldset>
						</div>
						<div id="mecard" class="tab">
							<fieldset>
								<label>
									Name
								</label>
								<input id="mecard_name"/>
							</fieldset>
							<fieldset>
								<label>
									Number
								</label>
								<input id="mecard_number"/>
							</fieldset>
							<fieldset>
								<label>
									Email
								</label>
								<input id="mecard_email"/>
							</fieldset>
							<fieldset>
								<label>
									Address
								</label>
								<input id="mecard_address"/>
							</fieldset>
							<fieldset>
								<label>
									Memo
								</label>
								<input id="mecard_memo"/>
							</fieldset>
						</div>
						<div id="bizcard" class="tab">
							<fieldset>
								<label>
									First Name
								</label>
								<input id="bizcard_firstname"/>
							</fieldset>
							<fieldset>
								<label>
									Last Name
								</label>
								<input id="bizcard_lastname"/>
							</fieldset>
							<fieldset>
								<label>
									Company
								</label>
								<input id="bizcard_company"/>
							</fieldset>
							<fieldset>
								<label>
									Job
								</label>
								<input id="bizcard_job"/>
							</fieldset>
							<fieldset>
								<label>
									Phone
								</label>
								<input id="bizcard_phone"/>
							</fieldset>
							<fieldset>
								<label>
									Address
								</label>
								<input id="bizcard_address"/>
							</fieldset>
							<fieldset>
								<label>
									Email
								</label>
								<input id="bizcard_email"/>
							</fieldset>
							<fieldset>
								<label>
									WebSite
								</label>
								<input id="bizcard_website"/>
							</fieldset>
						</div>
						<div id="paypal" class="tab">
							<fieldset>
								<label>
									Email
								</label>
								<input id="paypal_email"/>
							</fieldset>
							<fieldset>
								<label>
									Description
								</label>
								<input id="paypal_description"/>
							</fieldset>
							<fieldset>
								<label>
									Amount
								</label>
								<input id="paypal_amount"/>
							</fieldset>
							<fieldset>
								<label>
									Currency
								</label>
								<input id="paypal_currency"/>
							</fieldset>
							<fieldset>
								<label>
									Quantity
								</label>
								<input id="paypal_quantity"/>
							</fieldset>
							<fieldset>
								<label>
									Item number
								</label>
								<input id="paypal_item_nuvalue="" mber"/>
							</fieldset>
						</div>
						<div id="location" class="tab">
							Latitude
							<input id="location_latitude" style="width:60px;"/>
							<strong>|</strong> Longitude
							<input id="location_longitude"  style="width:60px;"/>
							<div id="map_canvas" style="height:180px; width:280px; overflow-x: hidden; overflow-y: hidden; position: relative;">
							</div>
						</div>
						<div id="wifi" class="tab">
							<fieldset>
								<label>
									SSID
								</label>
								<input id="wifi_SSID"/>
							</fieldset>
							<fieldset>
								<label>
									Network type
								</label>
								<input id="wifi_network_type"/>
							</fieldset>
							<fieldset>
								<label>
									Password
								</label>
								<input id="wifi_password"/>
							</fieldset>
						</div>
						<div id="bookmark" class="tab">
							<fieldset>
								<label>
									Title
								</label>
								<input id="bookmark_title"/>
							</fieldset>
							<fieldset>
								<label>
									URL
								</label>
								<input id="bookmark_url"/>
							</fieldset>
						</div>
					</div>
					<!--  h2>Configure</h2-->
					<div id="configure" style="display:none;">
						<ul>
							<li>
								<label>
									Size
								</label>
								<input id="configure_height" type="text" style="width:40px;"/>
								X
								<input id="configure_width" type="text"  style="width:40px;"/>
							</li>
							<li>
								<label>
									Encoding
								</label>
								<select id="configure_encoding">
									<option value="UTF-8">UTF-8</option>
									<option value="Shift_JIS">Shift_JIS</option>
									<option value="ISO-8859-1">ISO-8859-1</option>
								</select>
							</li>
							<li>
								<label>
									Correction
								</label>
								<select id="configure_correction">
									<option value="L">L</option>
									<option value="M">M</option>
									<option value="Q">Q</option>
									<option value="H">H</option>
								</select>
							</li>
						</ul>
					</div>
					<div id="qr_color">
						<h2></h2>
						<fieldset style="display:none;">
							<label>
								Foreground
							</label>
							<input id="foreground_color" type="text" value="#000000"/>
						</fieldset>
						<fieldset style="display:none;">
							<label>
								Background
							</label>
							<input id="background_color" type="text" value="#FFFFFF"/>
						</fieldset>
					</div>
					<div class="clear">
					</div>
					<div>
						<a href="#" id="generate_qr" class="css3_btn black">Generate</a>
					</div>
				</div>
				<div class="clear">
				</div>
			</div>
		</div>
	</div>
	<div id="footer">
		<p>
			QrCode Generator App (c)
		</p>
	</div>
	
<div class="clr"></div>
<!-- input type="hidden" name="cid[]" value="<?php //echo $this->detail->id; ?>" />
<input type="hidden" name="advertise_image" value="<?php //echo $this->detail->advertise_image; ?>" /-->
<input type="hidden" name="option" value="com_ijoomer" />
<input type="hidden" name="view" value="qrcode_detail" />
<input type="hidden" name="task" value="save" />
</form>


