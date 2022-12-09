<?php
/**
 * Plugin Name: Wordpress Gen2 Plugin
 * Plugin URI: https://github.com/WIPACrepo/wordpress-gen2-plugin
 * Description: This is a plugin for the IceCube-Gen2 website customizations.
 * Version: 1.0.9
 * Author: WIPAC
 *
 * @package wordpress-gen2-plugin
 */

defined( 'ABSPATH' ) || exit;

$dirpath = plugin_dir_path( __FILE__ );

require plugin_dir_path( __FILE__ ) . 'blocks/index.php';
require plugin_dir_path( __FILE__ ) . 'blocks-jsx/index.php';
require plugin_dir_path( __FILE__ ) . 'includes/index.php';
