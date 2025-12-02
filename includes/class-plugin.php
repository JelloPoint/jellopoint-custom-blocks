<?php
namespace JelloPoint\CustomBlocks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin {

	/**
	 * Singleton instance.
	 *
	 * @var Plugin
	 */
	private static $instance;

	/**
	 * Haal instance op.
	 *
	 * @return Plugin
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Plugin constructor.
	 */
	private function __construct() {
		// Elementor categorie voor JelloPoint widgets.
		add_action( 'elementor/elements/categories_registered', [ $this, 'register_elementor_category' ] );

		// Elementor widgets registreren.
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
	}

	/**
	 * Registreer JelloPoint Elementor categorie als hij nog niet bestaat.
	 *
	 * @param \Elementor\Elements_Manager $elements_manager
	 */
	public function register_elementor_category( $elements_manager ) {
		$category_slug = 'jellopoint-widgets';

		$categories = $elements_manager->get_categories();

		if ( ! isset( $categories[ $category_slug ] ) ) {
			$elements_manager->add_category(
				$category_slug,
				[
					'title' => __( 'JelloPoint Widgets', 'jellopoint-custom-blocks' ),
					'icon'  => 'fa fa-plug',
				]
			);
		}
	}

	/**
	 * Registreer de JelloPoint Custom Block widget.
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager
	 */
	public function register_widgets( $widgets_manager ) {
		require_once JP_CB_PLUGIN_DIR . 'includes/widgets/class-custom-block.php';

		$widgets_manager->register( new Widgets\Custom_Block() );
	}
}
