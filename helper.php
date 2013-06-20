<?php
/**
* Polaroids Module
* 2011 Benjamin Booth
* @bkbooth11
* bkbooth@facebook.com
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');

class modPolaroidsHelper
{
	// Get the images
	function getImagesList($images_folder, $images_number = 0) {
		$images_dir = JPATH_SITE.DS.$images_folder;
		$images_list = array();
		if ($handle = opendir($images_dir)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != ".." && is_file($images_dir.DS.$file) && getimagesize($images_dir.DS.$file)) {
					array_push($images_list, $file);
				}
			}
			closedir($handle);
		}
		//sort($images_list);
		shuffle($images_list);
		if ($images_number > 0) {
			return array_slice($images_list, 0, $images_number);	
		} else {
			return $images_list;
		}
	} // function getImagesList
	
	// Create a resized version of $input image
	function createResizedImage($input, $max_width, $max_height, $grayscale) {
		// load the input image into a temp image
		$img_temp = imagecreatefromfile($input);
		
		// Gray scale the image?
		if ($grayscale) {
			imagefilter($img_temp, IMG_FILTER_GRAYSCALE);
		}
		
		// get the desired thumbnail aspect ratio
		$dst_aspect_ratio = $max_width / $max_height;
		// get the source aspect ratio
		$src_aspect_ratio = imagesx($img_temp) / imagesy($img_temp);
		
		if ($src_aspect_ratio >= $dst_aspect_ratio) {
			// source wider than destination, use full height
			$source_height = imagesy($img_temp);
			$source_width = imagesy($img_temp) * $dst_aspect_ratio;
			$source_offset_x = (imagesx($img_temp) - $source_width) / 2;
			$source_offset_y = 0;
		} else {
			// source taller than destination, use full width
			$source_width = imagesx($img_temp);
			$source_height = imagesx($img_temp) / $dst_aspect_ratio;
			$source_offset_x = 0;
			$source_offset_y = (imagesy($img_temp) - $source_height) / 2;
		}
		
		// Create a new empty image of the right size
		$img_res = imagecreatetruecolor($max_width, $max_height);
		// Copy resampled version of the input image into the new image
		imagecopyresampled($img_res, $img_temp,
							0, 0,
							$source_offset_x, $source_offset_y,
							$max_width, $max_height,
							$source_width, $source_height);
		
		return $img_res;
		
		// Create the output image from the resampled image
		//imagejpeg($img_thumb, $output, 90);
		//imagedestroy($img_thumb);
		//imagedestroy($img_gray);
	} // function createResizedImage
	
	function createPolaroidImage($output, $input, $frame, $img_width, $img_height, $frame_width, $frame_height) {
		
		$frame_img = imagecreatefrompng($frame);
		imagecopymerge($frame_img, $input, 10, 10, 0, 0, $img_width, $img_height, 100);
		
		imagesavealpha($frame_img, 1);
		imagepng($frame_img, $output);
		
		imagedestroy($input);
	} // function createPolaroidImage
} // class modPolaroidsHelper

function imagecreatefromfile($imagepath = false) {
	return @imagecreatefromstring(file_get_contents($imagepath));
	/* if (!$imagepath || !$is_readable($imagepath)) {
		return false;
	} else {
		return @imagecreatefromstring(file_get_contents($imagepath));
	} */
} // function imagecreatefromfile
?>
