<?php
class QrcodeColor {
	private $url;
	private $f_color;
	private $b_color;
	private $img_in;
	private $img_out;
	function __construct($url, $f_color, $b_color) {
		$this->url = str_replace(' ', '%20', $url);
		$this->f_color=$this->convert_hex($f_color);
		$this->b_color=$this->convert_hex($b_color);
		$this->init_img();
		$this->img_scan();
	}
	/*
	 * Initialize the image in memory
	 */
	private function init_img() {
		$this->img_in = imagecreatefromPNG($this->url);
		$this->img_out = ImageCreateTrueColor(imagesx($this->img_in), imagesy($this->img_in));
	}
	/*
	 * Scan Image Pixels
	 */
	private function img_scan() {
		for($x = 0; $x < imagesx($this->img_in); $x++) {
			for($y = 0; $y < imagesy($this->img_in); $y++) {
				$src_pix = imagecolorat($this->img_in, $x, $y);
				$r_arr = $this->detect_color($src_pix);
				imagesetpixel($this->img_out, $x, $y,imagecolorallocate($this->img_out, $r_arr[0], $r_arr[1], $r_arr[2]));
			}
		}
	}
	/*
	 * Return the right color
	 */
	private function detect_color($rgb) {
		// get the first number in RGB
		    $c[0] = ($rgb >> 16) & 0xFF;
			// Check black or white
			if ($c[0] === 0) {
				return $this->f_color;
			} else {
				return $this->b_color;
			}
	}
	/*
	 * Convert HEX to RGB (Array)
	 */
	private function convert_hex($hex) {
	    return array(
	        base_convert(substr($hex, 0, 2), 16, 10),
	        base_convert(substr($hex, 2, 2), 16, 10),
	        base_convert(substr($hex, 4, 2), 16, 10),
	    );
	}
	/*
	 * Display the new image
	 */
	public function display_img() {
		header('Content-type:image/jpeg');
		imagejpeg($this->img_out, '',100);
		imagedestroy($this->img_out);
	}
	/*
	 * Return the image
	 */
	public function return_img() {
		return $this->img_out;
	}
}
/*
 * Get the request parameters
 */
$url = urldecode($_GET['url']);
$f_color = $_GET['f_color'];
$b_color = $_GET['b_color'];
if ($url) {
$qrcode = new QrcodeColor($url, $f_color, $b_color);
$qrcode->display_img();
}

?>