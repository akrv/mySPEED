<?php
require 'coloring.php';
class download_img {
	private $d_img;
	/*
	 * Constructor
	 */
	function __construct($url, $coloring, $format) {
		$this -> url = $url;
		$this -> format = $format;
		/*
		 * Load image into memory
		 */
		if($coloring['enabled'] === true) {
			$this -> load_img($url, $coloring['foreground'], $coloring['background']);
		} else {
			$this -> load_img($url, '000000', 'FFFFFF');
		}
		/*
		 * Convert image
		 */
		$this -> download($format);

	}

	/*
	 * Load image
	 */
	private function load_img($url, $foreground, $background) {
		$qrcode = new QrcodeColor($url, $foreground, $background);
		$this -> d_img = $qrcode -> return_img();
	}

	/*
	 * Convert img
	 */
	private function download($format) {
		switch ($format) {
			case 'png' :
				header('Content-type:image/png');
				header("Content-Disposition: attachment; filename=qrcode.png");
				imagepng($this -> d_img);
				break;
			case 'jpg' :
				header('Content-type:image/jpeg');
				header("Content-Disposition: attachment; filename=qrcode.jpg");
				imagejpeg($this -> d_img, '', 100);
				break;
			case 'gif' :
				header('Content-type:image/gif');
				header("Content-Disposition: attachment; filename=qrcode.gif");
				imagepng($this -> d_img);
				break;
		}
	}

}

/*
 * Get the request parameters
 */
$url = urldecode($_GET['d_url']);
$coloring = $_GET['coloring'];
if($coloring === 'true') {
	$coloring = array('enabled' => true, 'foreground' => $_GET['f_color'], 'background' => $_GET['b_color']);
} else {
	$colroing = array('enabled' => false);
}
$format = $_GET['format'];
if($url) {
	$img = new download_img($url, $coloring, $format);
}
?>