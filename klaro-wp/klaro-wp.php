<?php
/**
 * Plugin Name: Klaro - Open-source Cookie Management Platform for WordPress
 * Plugin URI: https://github.com/gabrielepiccinnu/klaro-wp
 * Description: A lightweight integration of Klaro! CMP for WordPress.
 * Version: 1.0.0
 * Author: Gabriele Piccinnu
 * License: GPL-3.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: klaro-wp
 */

defined('ABSPATH') || exit;

// Include functions
require_once plugin_dir_path(__FILE__) . 'includes/functions.php';

// Include admin settings page
if (is_admin()) {
    require_once plugin_dir_path(__FILE__) . 'admin/settings-page.php';
}