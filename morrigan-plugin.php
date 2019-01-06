<?php
/**
 * @package  morriganPlugin
 */
/*
Plugin Name: Morrigan Plugin
Plugin URI: http://morrigan.com/plugin
Description: This is my first attempt on writing a custom Plugin for this amazing tutorial series.
Version: 1.0.0
Author: Liene Tunne
Author URI: http://morrigan.com
License: GPLv2 or later
Text Domain: morrigan-plugin
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2015 Automattic, Inc.
*/

// If this file is called firectly, abort!!!
defined( 'ABSPATH' ) or die( 'Hey, what are you doing here? You silly human!' );

// Require once the Composer Autoload
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

/**
 * The code that runs during plugin activation
 */


function activate_morrigan_plugin() {
	Inc\Base\Activate::activate();
}
register_activation_hook( __FILE__, 'activate_morrigan_plugin' );

/**
 * The code that runs during plugin deactivation
 */
function deactivate_morrigan_plugin() {
	Inc\Base\Deactivate::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_morrigan_plugin' );

$current_theme = wp_get_theme();
$current_theme_name = $current_theme->Name;
if($current_theme_name == 'Morrigan'){
	/**
	 * Initialize all the core classes of the plugin
	 */
	if ( class_exists( 'Inc\\Init' ) ) {
		Inc\Init::register_services();
	}
}else {
	add_action( 'admin_notices', 'morrigan_admin_notice', 99 );
	function morrigan_admin_notice() {
	?>
		<div class="notice-warning notice">
			<p><?php printf( __( 'In order to use the <strong>Morrigan plugin</strong> you have to also install / activate the %1$sMorrigan Theme%2$s', 'morrigan' ), '<a href="https://wordpress.org/themes/shapely/" target="_blank">', '</a>' ); ?></p>
		</div>
		<?php
	}
}
