<?php
namespace JelloPoint\CustomBlocks\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * JelloPoint Custom Block widget.
 *
 * Toont de titel + content van een gekozen post uit het CPT 'standaard_teksten',
 * met volledige styling-controls in Elementor.
 */
class Custom_Block extends Widget_Base {

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'jellopoint_custom_block';
	}

	/**
	 * Widget titel in Elementor.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'JelloPoint Custom Block', 'jellopoint-custom-blocks' );
	}

	/**
	 * Widget icoon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-text';
	}

	/**
	 * Categorie.
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'jellopoint-widgets' ];
	}

	/**
	 * Keywords.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'jellopoint', 'custom', 'block', 'tekst', 'standaard', 'content' ];
	}

	/**
	 * Registreer controls.
	 */
	protected function _register_controls() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		$this->start_controls_section(
			'section_source',
			[
				'label' => __( 'Bron', 'jellopoint-custom-blocks' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'post_id',
			[
				'label'       => __( 'Standaard Tekst', 'jellopoint-custom-blocks' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => $this->get_standaard_teksten_options(),
				'multiple'    => false,
				'label_block' => true,
				'description' => __( 'Kies welke Standaard Tekst je in dit blok wilt tonen.', 'jellopoint-custom-blocks' ),
			]
		);

		$this->add_control(
			'show_title',
			[
				'label'        => __( 'Toon Titel', 'jellopoint-custom-blocks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Ja', 'jellopoint-custom-blocks' ),
				'label_off'    => __( 'Nee', 'jellopoint-custom-blocks' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_content',
			[
				'label'        => __( 'Toon Tekst', 'jellopoint-custom-blocks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Ja', 'jellopoint-custom-blocks' ),
				'label_off'    => __( 'Nee', 'jellopoint-custom-blocks' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->end_controls_section();

		/**
		 * STYLING – Titel
		 */
		$this->start_controls_section(
			'section_style_title',
			[
				'label'     => __( 'Titel', 'jellopoint-custom-blocks' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Kleur', 'jellopoint-custom-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jp-block__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => __( 'Typografie', 'jellopoint-custom-blocks' ),
				'selector' => '{{WRAPPER}} .jp-block__title',
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label'      => __( 'Margin', 'jellopoint-custom-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .jp-block__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * STYLING – Content
		 */
		$this->start_controls_section(
			'section_style_content',
			[
				'label'     => __( 'Tekst', 'jellopoint-custom-blocks' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_content' => 'yes',
				],
			]
		);

		$this->add_control(
			'content_color',
			[
				'label'     => __( 'Kleur', 'jellopoint-custom-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jp-block__content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'label'    => __( 'Typografie', 'jellopoint-custom-blocks' ),
				'selector' => '{{WRAPPER}} .jp-block__content',
			]
		);

		$this->add_responsive_control(
			'content_margin',
			[
				'label'      => __( 'Margin', 'jellopoint-custom-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .jp-block__content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Opties voor de selectlijst met Standaard Teksten.
	 *
	 * @return array
	 */
	protected function get_standaard_teksten_options() {
		$options = [];

		$args = [
			'post_type'      => 'standaard_teksten',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'fields'         => 'ids',
		];

		$post_ids = get_posts( $args );

		if ( ! empty( $post_ids ) && ! is_wp_error( $post_ids ) ) {
			foreach ( $post_ids as $post_id ) {
				$title = get_the_title( $post_id );
				if ( '' === $title ) {
					$title = sprintf( __( '(geen titel) – ID %d', 'jellopoint-custom-blocks' ), $post_id );
				}
				$options[ $post_id ] = sprintf( '%s (ID %d)', $title, $post_id );
			}
		}

		return $options;
	}

	/**
	 * Render de output in de frontend.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$post_id = isset( $settings['post_id'] ) ? (int) $settings['post_id'] : 0;

		if ( ! $post_id ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="jp-block jp-block--notice">';
				echo esc_html__( 'Selecteer een Standaard Tekst in de widget instellingen.', 'jellopoint-custom-blocks' );
				echo '</div>';
			}
			return;
		}

		$post = get_post( $post_id );

		if ( ! $post || 'standaard_teksten' !== $post->post_type ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="jp-block jp-block--notice">';
				echo esc_html__( 'De geselecteerde post bestaat niet (meer) of is geen Standaard Tekst.', 'jellopoint-custom-blocks' );
				echo '</div>';
			}
			return;
		}

		$show_title   = ( isset( $settings['show_title'] ) && 'yes' === $settings['show_title'] );
		$show_content = ( isset( $settings['show_content'] ) && 'yes' === $settings['show_content'] );

		$title        = get_the_title( $post_id );
		$raw_content  = get_post_field( 'post_content', $post_id );
		$content_html = '';

		if ( $raw_content ) {
			// Geen the_content filters – alleen shortcodes + automatische paragrafen.
			$content_html = wpautop( do_shortcode( $raw_content ) );
		}
		?>
		<div class="jp-block">
			<?php if ( $show_title && $title ) : ?>
				<div class="jp-block__title">
					<?php echo esc_html( $title ); ?>
				</div>
			<?php endif; ?>

			<?php if ( $show_content && $content_html ) : ?>
				<div class="jp-block__content">
					<?php
					// Content mag HTML bevatten (paragrafen, links, lijstjes).
					echo $content_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
