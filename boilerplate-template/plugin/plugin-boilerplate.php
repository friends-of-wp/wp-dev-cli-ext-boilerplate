<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              ##PLUGIN_URI##
 * @since             0.0.1
 * @package           ##PLUGIN_NORMALIZED_NAME##
 *
 * @wordpress-plugin
 * Plugin Name:       ##PLUGIN_NAME##
 * Plugin URI:        ##PLUGIN_URI##
 * Description:       ##PLUGIN_DESCRIPTION##
 * Version:           ##PLUGIN_VERSION##
 * Author:            ##AUTHOR_NAME##
 * Author URI:        ##AUTHOR_URI##
 * License:           ##LICENSE##
 * License URI:       ##LICENSE_URI##
 * Text Domain:       WPSecurityScore
 * Domain Path:       /languages
 */

if (!defined('WPINC')) die;

include_once ABSPATH . 'wp-admin/includes/plugin.php';

# INCLUDES

# CONSTANTS

define('##PLUGIN_UNIQUE_NAME_CONST##', '##PLUGIN_NAME##');

# INCLUDE PAGES
