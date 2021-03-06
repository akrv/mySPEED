function Qrcode(param) {
	/**
	 * Default Settings
	 */
	var defaults = {
		type: 'text',
		value: '',
		size: {
			'height':230,
			'width':230
		},
		encoding: 'UTF-8',
		correction: 'L'
	};

	/**
	 * Override Default Settings
	 */
	for (attr in param) {
		defaults[attr] = param[attr];
	}
	/**
	 * Set the Object Arguments
	 */
	this.arg = defaults;

	/**
	 * Generate the URL
	 */
	this.generate_url();
}

/**
 * Generate the QrCode URL and save it to the url variable
 */
Qrcode.prototype.generate_url = function() {
	var url = 'http://chart.apis.google.com/chart?';
	// Google Chart Type
	url += this.construct_param('cht', 'qr');
	// Google Chart Size
	url += this.construct_param('chs', this.arg.size.height + 'x' + this.arg.size.width);
	// Google Chart Encoding
	url += this.construct_param('choe', this.arg.encoding);
	// Google Chart Error Correction
	url += this.construct_param('chld', this.arg.correction);
	// Google Chart QrCode query
	url += this.construct_param('chl', this.construct_qr(), true);
	// Set the Object URL
	this.url = url;
};
/**
 * Construct an HTTP parameter
 * @param string $key The parameter key
 * @param string $value The parameter value
 * @param boolean $last Set to true if the last parameter
 */
Qrcode.prototype.construct_param = function(key, value, last) {
	if(last === true) {
		return key + '=' + value;
	} else {
		return key + '=' + value + '&';
	}
};
/**
 * Create the QrCode query with specific type using the associated parameters
 * @return string The constructed chl parameter
 */
Qrcode.prototype.construct_qr = function() {
	var chl = null;
	if (this.arg.type === 'text') {
		chl = this.arg.value;
	} else if (this.arg.type === 'link') {
		chl = this.arg.value;
	} else if (this.arg.type === 'email') {
		chl = 'mailto:'+this.arg.value.email+':'+this.arg.value.body;
	} else if (this.arg.type === 'phone') {
		chl = 'tel:'+this.arg.value;
	} else if (this.arg.type === 'sms') {
		chl = 'smsto:'+this.arg.value.number+':'+this.arg.value.message;
	} else if (this.arg.type === 'mecard') {
		chl = 'MECARD:N:'+this.arg.value.name+';';
		chl += 'TEL:'+this.arg.value.number+';';
		chl += 'EMAIL:'+this.arg.value.email+';';
		chl += 'ADR:'+this.arg.value.address+';';
		chl += 'NOTE:'+this.arg.value.memo+';;';
	} else if (this.arg.type === 'bizcard') {
		chl = 'BIZCARD:N:'+this.arg.value.first_name+';';
		chl += 'X:'+this.arg.value.last_name+';';
		chl += 'C:'+this.arg.value.company+';';
		chl += 'T:'+this.arg.value.job+';';
		chl += 'A:'+this.arg.value.address+';';
		chl += 'B:'+this.arg.value.phone+';';
		chl += 'E:'+this.arg.value.email+';';
		chl += 'U:'+this.arg.value.website+';;';
	} else if (this.arg.type === 'paypal') {
		chl = 'https://www.paypal.com/cgi-bin/webscr?cmd=_xclick';
		chl += '&business='+this.arg.value.email;
		chl += '&item_name='+this.arg.value.description;
		chl += '&amount='+this.arg.value.amount;
		chl += '&cy_code='+this.arg.value.currency;
		chl += '&quantity='+this.arg.value.quantity;
		chl += '&item_number'+this.arg.value.item_number;
	} else if (this.arg.type === 'location') {
		chl = 'geo:'+this.arg.value.latitude+','+this.arg.value.longitude;
	} else if (this.arg.type === 'wifi') {
		chl = 'WIFI:S:'+this.arg.value.SSID+';';
		chl += 'T:'+this.arg.value.network_type+';';
		chl += 'P:'+this.arg.value.password+';';
	} else if (this.arg.type === 'bookmark') {
		chl = 'MEBKM:TITLE:'+this.arg.value.title+';';
		chl += 'URL:'+this.arg.value.url+';';
	} else {
		chl = this.arg.value;
	}
	return chl;
};
/**
 * Return the Class Generated QrCode URL
 */
Qrcode.prototype.toString = function() {
	return this.url;
};
/**
 * Return the IMG HTML of the Generated QrCode URL
 */
Qrcode.prototype.to_image = function() {
	var img = document.createElement('IMG');
	img.src= this.url;
	return img;
};
/**
 * Insert the IMG into an element with ID
 */
Qrcode.prototype.insert_to = function(id) {
	document.getElementById(id).appendChild(this.to_image());
};