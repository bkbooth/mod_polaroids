<?php
/**
* Polaroids Module
* 2011 Benjamin Booth
* @bkbooth11
* bkbooth@facebook.com
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');

// Read in the parameters
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
$images_folder = $params->get('images_folder',		'images/polaroids');
$images_number = $params->get('images_number',		5);
$images_width = $params->get('images_width',		150);
$images_height = $params->get('images_height',		85);
$grayscale = $params->get('grayscale',				1);
$frame_image = $params->get('frame_image',			'polaroid-frame.png');
$full_width = $params->get('full_width',			170);
$full_height = $params->get('full_height',			105);
$max_rotation = $params->get('max_rotation',		5);

$images_path = JPATH_SITE.DS.$images_folder;
$images_url = JURI::base().$images_folder;

if (basename($frame_image) == $frame_image) {
	$frame_path = dirname(__FILE__).DS."images".DS.$frame_image;
	$frame_url = JURI::base()."modules".DS."mod_polaroids".DS."images".DS.$frame_image;
} else {
	$frame_path = JPATH_SITE.DS.$frame_image;
	$frame_url = JURI::base().$frame_image;
}

$images_list = modPolaroidsHelper::getImagesList($images_folder, $images_number);
// Create thumbnails if they don't exist
foreach ($images_list as $image) {
	if (!file_exists(JPATH_SITE.DS.$images_folder.DS."resized".DS.$image)) {
		$resized = modPolaroidsHelper::createResizedImage(
			$images_path.DS.$image,
			$images_width,
			$images_height,
			$grayscale);
		
		// Create the output file name
		$path_info = pathinfo($images_path.DS.$image);
		$base = basename($images_path.DS.$image, $path_info['extension']);
		
		modPolaroidsHelper::createPolaroidImage(
			$images_path.DS."resized".DS.$base.".png",
			$resized,
			$frame_path,
			$images_width,
			$images_height,
			$full_width,
			$full_height);
	}
}

require(JModuleHelper::getLayoutPath('mod_polaroids', $params->get('layout', 'default')));

?>
