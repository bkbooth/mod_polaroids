<?php
/**
* Polaroids Module
* 2011 Benjamin Booth
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Add the Google hosted jQuery
JFactory::getDocument()->addScript("https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js");

JFactory::getDocument()->addScript("modules".DS."mod_polaroids".DS."js".DS."jQueryRotateCompressed.2.1.js");
//JFactory::getDocument()->addScript("modules".DS."mod_polaroids".DS."js".DS."polaroids.js");
JFactory::getDocument()->addStyleSheet("modules".DS."mod_polaroids".DS."css".DS."polaroids.css");

//echo "<pre>";
//print_r($images_list);
//echo "</pre>";

?>

<div class="polaroids" style="padding: <?php echo $max_rotation; ?>px 0 <?php echo round($max_rotation / 3); ?>px;">
	<?php $counter = 0; ?>
	<?php foreach ($images_list as $image) : ?>
		<?php
			$counter++;
			$path_info = pathinfo($images_path.DS.$image);
			$base = basename($images_path.DS.$image, $path_info['extension']);
		?>
		<img src="<?php echo $images_url.DS."resized".DS.$base.".png"; ?>"
			width="<?php echo $images_width; ?>"
			height="<?php echo $images_height; ?>"
			alt="<?php echo $image; ?>"
			class="polaroid img-<?php echo $counter; ?>"
			style="position: relative; z-index: <?php echo rand(0, 99); ?>; margin-left: -<?php echo $full_width - $images_width; ?>px" />
	<?php endforeach; ?>
</div>

<script type="text/javascript">
//Use jQuery noConflict mode
var $jp = jQuery.noConflict();
	
// Initialise jQuery
$jp(document).ready(function(){
	<?php for ($i = 1; $i <= $images_number; $i++) : ?>
		$jp('img.polaroid.img-<?php echo $i; ?>').rotate(<?php echo ($i % 2 == 0 ? "" : "-").rand(1, $max_rotation); ?>);
	<?php endfor; ?>
});
</script>
