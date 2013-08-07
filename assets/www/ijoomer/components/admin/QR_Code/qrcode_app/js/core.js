/**
 * @fileOverview QrCode Generator App
 * @author Abid Omar
 * @version 1.2
 * @description
 *          <p>
 *          Qrcode Generator App is a JavaScript application that creates QrCodes
 * 			using the Google chart API. The application supports a wide number of
 * 			Qrcode types and features a PHP solution to Qrcode coloring
 *          </p>
 * 			<h2>The application</h2>
 * 			<p>
 * 			The application is composed of only one class "QrcodeApp". This class
 * 			holds all the methods and properties of the application. The class is
 * 			wrapped inside a self-executing function to protect against jQuery
 * 			conflicts. The application instance is created in the end of the document
 * 			</p>
 */
(function($) {
	/**
	 * QrcodeApp Class
	 *
	 * @description Initialize the QrCode Application
	 * @constructor
	 * @param {Object} Google maps and coloring settings
	 */
	function QrcodeApp(param) {
		var that = this;
		/*
		 * Parameter defaults
		 */
		var defaults = {
			lat:34.637,
			lng:10.68,
			coloring:false,
			download: false
		};
		this.arg = {};
		$.extend(this.arg, defaults, param);
		/*
		 * 1. App elements
		 */
		this.elements();
		/*
		 * 2. App Tabs
		 */
		this.tabs();
		/*
		 * 3. Initialize form
		 */
		this.init();
		/*
		 * 4. AJAX Settings
		 */
		this.request_type = 'POST';
		this.request_url= null;
		this.request_callback = function(data) {
		};
		/*
		 * 5. Google Maps
		 */
		this.maps_settings = {
		};
		$.extend(this.maps_settings, {
			lat:that.arg.lat,
			lng:that.arg.lng
		});
		this.gmaps();
		/*
		 * 6. QrCode Coloring
		 */
		if (this.arg.coloring) {
			this.load_picker();
		}
		/*
		 * 7. QrCode Download
		 */
		if (this.arg.download) {
			this.load_download();
		}
	}

	/*
	 * Initialize Form
	 */
	QrcodeApp.prototype.init = function() {
		var that = this;
		/*
		 * Open First Tab
		 */
		this.tabs.showtab('link');
		this.el.types.collection.each( function() {
			if ($(this).attr('value')==='link') {
				$(this).attr('selected', 'true');
			}
		});
		/*
		 * Display Image
		 */
		this.el.display.hide();
		/*
		 * Setup Form Fields
		 */
		/*
		 * Text
		 */
		this.el.forms.text.text.val('');
		this.el.forms.text.text.keyup( function() {
			that.el.forms.text.counter.text('('+(this.value.length)+')');
		});
		/*
		 * Email
		 */
		this.el.forms.email.body.val('');
		/*
		 * SMS
		 */
		this.el.forms.sms.message.val('');
		/*
		 * Configure
		 */
		this.el.configure.size.height.val('230');
		this.el.configure.size.width.val('230');
		/*
		 * Select URL
		 */
		this.el.link.btn.click( function() {
			that.el.link.input[0].select();
			return false;
		});
		/*
		 * Select Short URL
		 */
		this.el.bitly.btn.click( function() {
			that.el.bitly.input[0].select();
			return false;
		});
		/*
		 * Generate Button
		 */
		this.el.generate.click( function() {
			that.generate_qr();
			return false;
		});
		/*
		 * Set the QrCode Current URL
		 */
		this.qrcode_current = null;
	};
	/*
	 * Load the Color picker
	 */
	QrcodeApp.prototype.load_picker = function() {
		var that = this;
		/*
		 * Show the color portion
		 */
		$('#qr_color').show();
		/*
		 * Added the picker JavaScript and CSS files
		 */
		var js = document.createElement('script');
		js.setAttribute('src', 'components/com_ijoomer/QR_Code/qrcode_app/picker/js/colorpicker.js');
		$('HEAD').append(js);
		var css = document.createElement('link');
		css.setAttribute('href', 'components/com_ijoomer/QR_Code/qrcode_app/picker/css/colorpicker.css');
		css.setAttribute('rel', 'stylesheet');
		$('HEAD').append(css);
		/*
		 * Link the picker to the inputs
		 */
		var f_options = {
			color: '000000',
			onChange: function(hsb, hex, rgb) {
				that.el.color.foreground.val('#'+hex);
			}
		};
		var b_options = {
			color: 'FFFFFF',
			onChange: function(hsb, hex, rgb) {
				that.el.color.background.val('#'+hex);
			}
		};
		this.el.color.foreground.ColorPicker(f_options);
		this.el.color.background.ColorPicker(b_options);
	};
	/*
	 * Elements
	 */
	QrcodeApp.prototype.elements = function () {
		this.el = {};
		/*
		 * Tabs
		 */
		this.el.tabs = {
			container: $('#tabs'),
			collection: $('.tab'),
			current: 'text'
		};
		/*
		 * Types
		 */
		this.el.types = {
			container: $('#types'),
			select: $('SELECT', '#types'),
			collection: $('OPTION' ,'#types')
		};
		/*
		 * Forms
		 */
		this.el.forms = {
			text: {
				text: $('#text_text'),
				counter: $('#text_counter')
			},
			link : $('#link_url'),
			email: {
				email: $('#email_email'),
				body: $('#email_body')
			},
			call : $('#call_number'),
			sms : {
				number: $('#sms_number'),
				message: $('#sms_message')
			},
			mecard : {
				name:$('#mecard_name'),
				number:$('#mecard_number'),
				email:$('#mecard_email'),
				address:$('#mecard_address'),
				memo:$('#mecard_memo')
			},
			bizcard : {
				firstname:$('#bizcard_firstname'),
				lastname:$('#bizcard_lastname'),
				company:$('#bizcard_company'),
				job:$('#bizcard_job'),
				phone:$('#bizcard_phone'),
				address:$('#bizcard_address'),
				email:$('#bizcard_email'),
				website:$('#bizcard_website')
			},
			paypal : {
				email:$('#paypal_email'),
				description:$('#paypal_description'),
				amount:$('#paypal_amount'),
				currency:$('#paypal_currency'),
				quantity:$('#paypal_quantity'),
				item_number:$('#paypal_item_number')
			},
			location: {
				latitude:$('#location_latitude'),
				longitude:$('#location_longitude')
			},
			wifi: {
				SSID: $('#wifi_SSID'),
				network_type: $('#wifi_network_type'),
				password: $('#wifi_password')
			},
			bookmark: {
				title:$('#bookmark_title'),
				url:$('#bookmark_url')
			}

		};
		/*
		 * Configure
		 */
		this.el.configure = {
			size : {
				height: $('#configure_height'),
				width: $('#configure_width')
			},
			encoding: $('#configure_encoding'),
			correction: $('#configure_correction')
		};
		/*
		 * Coloring
		 */
		this.el.color = {
			foreground: $('#foreground_color'),
			background: $('#background_color')
		};
		/*
		 * Image Display
		 */
		this.el.display = $('#output_img_qr','#output_img');
		this.el.loading = $('#output_img_loading', '#output_img');
		/*
		 * Qr Link
		 */
		this.el.link = {
			input: $('#output_url'),
			btn: $('#select_url')
		};
		/*
		 * Short link
		 */
		this.el.bitly = {
			input: $('#short_url'),
			btn: $('#select_short_url')
		};
		/*
		 * Download buttons
		 */
		this.el.download = {
			png: $('#download_png'),
			jpg: $('#download_jpg'),
			gif: $('#download_gif')
		};
		/*
		 * Generate Button
		 */
		this.el.generate = $('#generate_qr');
	};
	/*
	 * Tabs
	 */
	QrcodeApp.prototype.tabs = function() {
		var that = this;
		this.el.types.select.bind('change', function() {
			that.tabs.showtab($(this).val());
		});
		this.tabs.showtab = function (tab) {
			that.el.tabs.collection.hide();
			$('#'+tab, that.el.tabs.container).show();
			if (tab === 'location') {
				google.maps.event.trigger(that.map, 'resize');
			}
			that.el.tabs.current = tab;
		};
	};
	/*
	 * Validation
	 */
	QrcodeApp.prototype.validate = function(tab) {
		var that = this;
		switch(tab) {
			case 'text':
				/*
				 * Validation (Text)
				 */
				that.el.forms.text.text.removeClass('validate_error');
				if (that.el.forms.text.text.val() === '') {
					that.el.forms.text.text.addClass('validate_error');
					return false;
				}
				/*
				 * Return Value
				 */
				return that.el.forms.text.text.val();
				break;
			case 'link':
				/*
				 * Validation (URL)
				 */
				that.el.forms.link.removeClass('validate_error');
				if (!check_url(that.el.forms.link.val())) {
					that.el.forms.link.addClass('validate_error');
					return false;
				}
				/*
				 * Return Value
				 */
				return that.el.forms.link.val();
				break;
			case 'email':
				/*
				 * Validation (Email)
				 */
				that.el.forms.email.email.removeClass('validate_error');
				if (!check_email(that.el.forms.email.email.val())) {
					that.el.forms.email.email.addClass('validate_error');
					return false;
				}
				/*
				 * Validation (Text)
				 */
				that.el.forms.email.body.removeClass('validate_error');
				if (that.el.forms.email.body.val() === '') {
					that.el.forms.email.body.addClass('validate_error');
					return false;
				}
				/*
				 * Return Value
				 */
				return {
					email:that.el.forms.email.email.val(),
					body:that.el.forms.email.body.val()
				}
				break;
			case 'call':
				/*
				 * Validation (Integer)
				 */
				that.el.forms.call.removeClass('validate_error');
				if (isNaN(parseInt(that.el.forms.call.val(),10))) {
					that.el.forms.call.addClass('validate_error');
					return false;
				}
				/*
				 * Return value
				 */
				return that.el.forms.call.val();
				break;
			case 'sms':
				/*
				 * Validation (Integer)
				 */
				that.el.forms.sms.number.removeClass('validate_error');
				if (isNaN(parseInt(that.el.forms.sms.number.val(),10))) {
					that.el.forms.sms.number.addClass('validate_error');
					return false;
				}
				/*
				 * Validation (Message)
				 */
				that.el.forms.sms.message.removeClass('validate_error');
				if (that.el.forms.sms.message.val() === '') {
					that.el.forms.sms.message.addClass('validate_error');
					return false;
				}
				return {
					number:that.el.forms.sms.number.val(),
					message:that.el.forms.sms.message.val()
				}
				break;
			case 'mecard':
				/*
				 * Validation (Text)
				 */
				that.el.forms.mecard.name.removeClass('validate_error');
				that.el.forms.mecard.address.removeClass('validate_error');
				that.el.forms.mecard.memo.removeClass('validate_error');
				if (that.el.forms.mecard.name.val() === '') {
					that.el.forms.mecard.name.addClass('validate_error');
					return false;
				}
				if (that.el.forms.mecard.address.val() === '') {
					that.el.forms.mecard.address.addClass('validate_error');
					return false;
				}
				if (that.el.forms.mecard.memo.val() === '') {
					that.el.forms.mecard.memo.addClass('validate_error');
					return false;
				}
				/*
				 * Validation (Integer)
				 */
				that.el.forms.mecard.number.removeClass('validate_error');
				if (isNaN(parseInt(that.el.forms.mecard.number.val(),10))) {
					that.el.forms.mecard.number.addClass('validate_error');
					return false;
				}
				/*
				 * Validation (Email)
				 */
				that.el.forms.mecard.email.removeClass('validate_error');
				if (!check_email(that.el.forms.mecard.email.val())) {
					that.el.forms.mecard.email.addClass('validate_error');
					return false;
				}
				return {
					name:that.el.forms.mecard.name.val(),
					number:that.el.forms.mecard.number.val(),
					email:that.el.forms.mecard.email.val(),
					address:that.el.forms.mecard.address.val(),
					memo:that.el.forms.mecard.memo.val()
				}
				break;
			case 'bizcard':
				/*
				 * Validation (Text)
				 */
				that.el.forms.bizcard.firstname.removeClass('validate_error');
				that.el.forms.bizcard.lastname.removeClass('validate_error');
				that.el.forms.bizcard.company.removeClass('validate_error');
				that.el.forms.bizcard.job.removeClass('validate_error');
				that.el.forms.bizcard.address.removeClass('validate_error');
				if (that.el.forms.bizcard.firstname.val() === '') {
					that.el.forms.bizcard.firstname.addClass('validate_error');
					return false;
				}
				if (that.el.forms.bizcard.lastname.val() === '') {
					that.el.forms.bizcard.lastname.addClass('validate_error');
					return false;
				}
				if (that.el.forms.bizcard.company.val() === '') {
					that.el.forms.bizcard.company.addClass('validate_error');
					return false;
				}
				if (that.el.forms.bizcard.job.val() === '') {
					that.el.forms.bizcard.job.addClass('validate_error');
					return false;
				}
				if (that.el.forms.bizcard.address.val() === '') {
					that.el.forms.bizcard.address.addClass('validate_error');
					return false;
				}
				/*
				 * Validation (Integer)
				 */
				that.el.forms.bizcard.phone.removeClass('validate_error');
				if (isNaN(parseInt(that.el.forms.bizcard.phone.val(),10))) {
					that.el.forms.bizcard.phone.addClass('validate_error');
					return false;
				}
				/*
				 * Validation (Email)
				 */
				that.el.forms.bizcard.email.removeClass('validate_error');
				if (!check_email(that.el.forms.bizcard.email.val())) {
					that.el.forms.bizcard.email.addClass('validate_error');
					return false;
				}
				/*
				 * Validation (URL)
				 */
				that.el.forms.bizcard.website.removeClass('validate_error');
				if (!check_url(that.el.forms.bizcard.website.val())) {
					that.el.forms.bizcard.website.addClass('validate_error');
					return false;
				}
				/*
				 * Return value
				 */
				return {
					first_name:that.el.forms.bizcard.firstname.val(),
					last_name:that.el.forms.bizcard.lastname.val(),
					company:that.el.forms.bizcard.company.val(),
					job:that.el.forms.bizcard.job.val(),
					phone:that.el.forms.bizcard.phone.val(),
					address:that.el.forms.bizcard.address.val(),
					email:that.el.forms.bizcard.email.val(),
					website:that.el.forms.bizcard.website.val()
				}
				break;
			case 'paypal':
				/*
				 * Validation (Text)
				 */
				that.el.forms.paypal.description.removeClass('validate_error');
				that.el.forms.paypal.currency.removeClass('validate_error');
				that.el.forms.paypal.item_number.removeClass('validate_error');
				if (that.el.forms.paypal.description.val() === '') {
					that.el.forms.paypal.description.addClass('validate_error');
					return false;
				}
				if (that.el.forms.paypal.currency.val() === '') {
					that.el.forms.paypal.currency.addClass('validate_error');
					return false;
				}
				if (that.el.forms.paypal.item_number.val() === '') {
					that.el.forms.paypal.item_number.addClass('validate_error');
					return false;
				}
				/*
				 * Validation (Email)
				 */
				that.el.forms.paypal.email.removeClass('validate_error');
				if (!check_email(that.el.forms.paypal.email.val())) {
					that.el.forms.paypal.email.addClass('validate_error');
					return false;
				}
				/*
				 * Validation (Integer)
				 */
				that.el.forms.paypal.amount.removeClass('validate_error');
				that.el.forms.paypal.quantity.removeClass('validate_error');
				if (isNaN(parseInt(that.el.forms.paypal.amount.val(),10))) {
					that.el.forms.paypal.amount.addClass('validate_error');
					return false;
				}
				if (isNaN(parseInt(that.el.forms.paypal.quantity.val(),10))) {
					that.el.forms.paypal.quantity.addClass('validate_error');
					return false;
				}
				/*
				 * Return value
				 */
				return {
					email:that.el.forms.paypal.email.val(),
					description:that.el.forms.paypal.description.val(),
					amount:that.el.forms.paypal.amount.val(),
					currency:that.el.forms.paypal.currency.val(),
					quantity:that.el.forms.paypal.quantity.val(),
					item_number:that.el.forms.paypal.item_number.val()
				}
				break;
			case 'location':
				/*
				 * Validate (Float)
				 */
				that.el.forms.location.latitude.removeClass('validate_error');
				that.el.forms.location.longitude.removeClass('validate_error');
				if (isNaN(parseFloat(that.el.forms.location.latitude.val(),10))) {
					that.el.forms.location.latitude.addClass('validate_error');
					return false;
				}
				if (isNaN(parseFloat(that.el.forms.location.longitude.val(),10))) {
					that.el.forms.location.longitude.addClass('validate_error');
					return false;
				}
				/*
				 * Return value
				 */
				return {
					latitude: parseFloat(that.el.forms.location.latitude.val()),
					longitude: parseFloat(that.el.forms.location.longitude.val())
				}
				break;
			case 'wifi':
				/*
				 * Validation (Null)
				 */
				that.el.forms.wifi.SSID.removeClass('validate_error');
				that.el.forms.wifi.network_type.removeClass('validate_error');
				that.el.forms.wifi.password.removeClass('validate_error');
				if (that.el.forms.wifi.SSID.val() === '') {
					that.el.forms.wifi.SSID.addClass('validate_error');
					return false;
				}
				if (that.el.forms.wifi.network_type.val() === '') {
					that.el.forms.wifi.network_type.addClass('validate_error');
					return false;
				}
				if (that.el.forms.wifi.password.val() === '') {
					that.el.forms.wifi.password.addClass('validate_error');
					return false;
				}
				/*
				 * Return Value
				 */
				return {
					SSID: that.el.forms.wifi.SSID.val(),
					network_type:  that.el.forms.wifi.network_type.val(),
					password: that.el.forms.wifi.password.val()
				}
				break;
			case 'bookmark':
				/*
				 * Validation (Text)
				 */
				that.el.forms.bookmark.title.removeClass('validate_error');
				if (that.el.forms.bookmark.title.val() === '') {
					that.el.forms.bookmark.title.addClass('validate_error');
					return false;
				}
				/*
				 * Validation (URL)
				 */
				that.el.forms.bookmark.url.removeClass('validate_error');
				if (!check_url(that.el.forms.bookmark.url.val())) {
					that.el.forms.bookmark.url.addClass('validate_error');
					return false;
				}
				return {
					title:that.el.forms.bookmark.title.val(),
					url:that.el.forms.bookmark.url.val()
				}
				break;
			case 'size':
				/*
				 * Validation (Integer)
				 */
				that.el.configure.size.height.removeClass('validate_error');
				if (isNaN(parseInt(that.el.configure.size.height.val(),10))) {
					that.el.configure.size.height.addClass('validate_error');
					return false;
				}
				that.el.configure.size.width.removeClass('validate_error');
				if (isNaN(parseInt(that.el.configure.size.width.val(),10))) {
					that.el.configure.size.width.addClass('validate_error');
					return false;
				}
				return {
					height: parseInt(that.el.configure.size.height.val(),10),
					width: parseInt(that.el.configure.size.width.val(),10)
				}
				break;
			case 'color':
				/*
				 * Validation (Hex)
				 */
				that.el.color.foreground.removeClass('validate_error');
				that.el.color.background.removeClass('validate_error');
				if (!check_hex(that.el.color.foreground.val())) {
					that.el.color.foreground.addClass('validate_error');
					return false;
				}
				if (!check_hex(that.el.color.background.val())) {
					that.el.color.background.addClass('validate_error');
					return false;
				}
				/*
				 * Return value
				 */
				return {
					foreground: that.el.color.foreground.val().substr(1, that.el.color.foreground.val().length-1),
					background: that.el.color.background.val().substr(1, that.el.color.background.val().length-1)
				}
				break;
		}
		// Check email address validity
		function check_email(email) {
			var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if (filter.test(email)) {
				return true;
			}
			return false;
		}

		// Check URL validity
		function check_url(url) {
			var filter = /http:\/\/[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}/;
			if (filter.test(url)) {
				return true;
			}
			return false;
		}

		// Check HEX Color
		function check_hex(color) {
			var filter = /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/;
			if (filter.test(color)) {
				return true;
			}
			return false;
		}

	};
	/*
	 * Generate QrCode
	 */
	QrcodeApp.prototype.generate_qr = function() {
		var that = this;
		/*
		 * Check for input validity
		 */
		if (!this.validate(this.el.tabs.current) || !this.validate('size')) {
			return false;
		}
		/*
		 * Creates QrCode information Object
		 */
		var qr_data = {
			type:that.el.tabs.current,
			value: that.validate(that.el.tabs.current),
			size : {
				height:that.validate('size').height,
				width:that.validate('size').width
			},
			encoding: that.el.configure.encoding.val(),
			correction: that.el.configure.correction.val()
		};
		/*
		 * Creates the QrCode Object
		 */
		var qrcode = new Qrcode(qr_data);
		var qr_url = qrcode.to_image();
		/*
		 * Set the QrCode Current
		 */
		this.qrcode_current = {
			link: qrcode.url,
			data : qr_data
		};
		/*
		 * Set the QrCode URL
		 */
		this.el.link.input.val(qrcode);
		/*
		 * Set the Shorttened URL
		 */
		this.shortten(qrcode);
		/*
		 * Check for QrCode coloring
		 */
		if (this.arg.coloring) {
			if (this.validate('color')) {
				qrcode = 'components/com_ijoomer/QR_Code/qrcode_app/php/coloring.php?url='+encodeURIComponent(qrcode)+'&f_color='+this.validate('color').foreground+'&b_color='+this.validate('color').background;
			} else {
				return false;
			}
		}
		/*
		 * Display Loading
		 */
		this.el.display.hide();
		this.el.loading.show().attr('src','components/com_ijoomer/QR_Code/qrcode_app/img/loading.gif');
		/*
		 * Display QrCode once loaded
		 */
		this.el.display.attr('src', qrcode).load( function() {
			that.el.loading.hide();
			$(this).show();
		});
		/*
		 * Send An AJAX Request
		 */
		if (this.request_url) {
			this.sendRequest();
		}

	};
	/*
	 * Send an AJAX request once the QrCode is created
	 */
	QrcodeApp.prototype.sendRequest = function() {

		var aj_settings = {
			type: this.request_type,
			url: this.request_url,
			data: {
				qr_code:JSON.stringify(this.qrcode_current)
			},
			success: this.request_callback
		};
		$.ajax(aj_settings);
	};
	/*
	 * Google Maps
	 */
	QrcodeApp.prototype.gmaps = function() {
		var that = this;
		/*
		 * Loads the map
		 */
		var latlng = new google.maps.LatLng(this.maps_settings.lat, this.maps_settings.lng);
		var myOptions = {
			zoom: 8,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var map = new google.maps.Map(document.getElementById("map_canvas"),
		myOptions);
		/*
		 * Creates the marker
		 */
		var marker = new google.maps.Marker({
			position : new google.maps.LatLng(this.maps_settings.lat, this.maps_settings.lng),
			clickable : true,
			draggable : true,
			map : map
		});
		/*
		 * Sync between the marker and the fields
		 */
		that.el.forms.location.latitude.val(this.maps_settings.lat);
		that.el.forms.location.longitude.val(this.maps_settings.lng);
		google.maps.event.addListener(marker, 'drag', function() {
			lat = Math.round(marker.position.Na * 1000) / 1000;
			lng = Math.round(marker.position.Oa * 1000) / 1000;
			that.el.forms.location.latitude.val(lat);
			that.el.forms.location.longitude.val(lng);
		});
		/*
		 * Return a reference to the map
		 */
		this.map = map;
	};
	/*
	 * Generate the Short URL (Bit.ly)
	 */
	QrcodeApp.prototype.shortten = function(long_url) {
		/*
		 * Prepare the parameters
		 */
		var that = this,
		login = 'omarabid',
		API_key = 'R_27882d7387028a10abb783750b99357e',
		short_url = long_url;
		long_url = encodeURIComponent(long_url);
		var request_url = 'http://api.bitly.com/v3/shorten?login='+login+'&apiKey='+API_key+'&longUrl='+long_url+'&format=json';
		/*
		 * AJAX Request
		 */
		$.getJSON(request_url, function(data) {
			/*
			 * Get the short URL
			 */
			short_url = data.data.url;
			/*
			 *  Display the short URL
			 */
			that.el.bitly.input.val(short_url);
		}).error( function() {
			/*
			* Internet Explorer support for CrossBrowser Requests
			*/
			// Use Microsoft XDR
			var xdr = new XDomainRequest();
			xdr.open("get", request_url);
			xdr.onload = function() {
				var response = $.parseJSON(this.responseText);
				/*
				 * Get the short URL
				 */
				short_url = response.data.url;
				/*
				 *  Display the short URL
				 */
				that.el.bitly.input.val(short_url);
			};
			xdr.send();
		});
	};
	/*
	 * Download image in the specified format
	 */
	QrcodeApp.prototype.download_img = function(format,e) {
		if (this.qrcode_current) {

			if (this.arg.coloring) {
				e.preventDefault();
				window.location.href = 'components/com_ijoomer/QR_Code/qrcode_app/php/download.php?d_url='+encodeURIComponent(this.qrcode_current.link)+'&coloring=true&f_color='+this.validate('color').foreground+'&b_color='+this.validate('color').background+'&format='+format;
			} else {
				e.preventDefault();
				window.location.href = 'components/com_ijoomer/QR_Code/qrcode_app/php/download.php?d_url='+encodeURIComponent(this.qrcode_current.link)+'&coloring=false&format='+format;
			}
		}
	};
	/*
	 * Loads the download buttons
	 */
	QrcodeApp.prototype.load_download = function() {
		var that = this;
		/*
		 * Download Buttons
		 */
		$('#download').css('display','block');
		$.each(this.el.download, function() {
			$(this).css({
				'opacity':0.8
			});
			$(this).hover( function() {
				$(this).animate({
					'opacity':1
				});
			}, function() {
				$(this).animate({
					'opacity':0.8
				});
			});
		});
		this.el.download.png.click( function(e) {
			that.download_img('png',e);
		});
		this.el.download.jpg.click( function(e) {
			that.download_img('jpg',e);
		});
		this.el.download.gif.click( function(e) {
			that.download_img('gif',e);
		})
	};
	/*
	 * Document Loaded. Creates a new QrCode App.
	 */
	$(document).ready( function() {
		var param = {
			coloring: true,
			download: true
		};
		window.qrcodeapp = new QrcodeApp(param);
		qrcodeapp.request_url='test.php';
	});
})(jQuery);
