<?php
/**
 * Plugin Name:       JelloPoint Custom Blocks
 * Description:       JelloPoint Elementor widget om specifieke Custom Posts (bijv. Standaard Teksten) als blok te tonen, volledig te stylen in Elementor.
 * Version:           1.0.0
 * Author:            JelloPoint
 * Text Domain:       jellopoint-custom-blocks
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'JP_CB_PLUGIN_FILE', __FILE__ );
define( 'JP_CB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'JP_CB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Autoload plugin core.
 */
require_once JP_CB_PLUGIN_DIR . 'includes/class-plugin.php';

add_action( 'plugins_loaded', function () {
	// Check of Elementor actief is.
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', function () {
			?>
			<div class="notice notice-warning">
				<p><?php esc_html_e( 'JelloPoint Custom Blocks requires Elementor to be installed and activated.', 'jellopoint-custom-blocks' ); ?></p>
			</div>
			<?php
		} );
		return;
	}

	// Start de plugin.
	\JelloPoint\CustomBlocks\Plugin::instance();
} );
