<?php
/*
Plugin Name: Mos Image Alter tag management
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 1.0
Author: Md. Mostak Shahid
Author URI: http://URI_Of_The_Plugin_Author
License: GPL2
.
Any other notes about the plugin go here
.
*/
//This function will put image name into alt field when Upload

require_once ( plugin_dir_path( __FILE__ ) . 'mos-image-alt-functions.php' );
require_once ( plugin_dir_path( __FILE__ ) . 'mos-image-alt-metabox.php' );
require_once ( plugin_dir_path( __FILE__ ) . 'mos-image-alt-settings.php' );