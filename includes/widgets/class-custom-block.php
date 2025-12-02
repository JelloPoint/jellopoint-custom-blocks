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
 * Toont de titel + content van een gekozen post uit beschikbare custom post types
 * (bijv. standaard_teksten), met optionele ACF-tagline en volledige styling-controls.
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
		return [ 'jellopoint', 'custom', 'block', 'tekst', 'standaard', 'content', 'cpt' ];
	}

	/**
	 * Registreer controls.
	 */
	protected function _register_controls() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		/**
		 * BRON
		 */
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
				'label'       => __( 'Post', 'jellopoint-custom-blocks' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => $this->get_post_options_acf_cpts(),
				'multiple'    => false,
				'label_block' => true,
				'description' => __( 'Kies welke post je in dit blok wilt tonen (uit ACF-compatibele custom post types).', 'jellopoint-custom-blocks' ),
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
				'label'        => __( 'Toon Tekst (Content)', 'jellopoint-custom-blocks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Ja', 'jellopoint-custom-blocks' ),
				'label_off'    => __( 'Nee', 'jellopoint-custom-blocks' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		/**
		 * TAGLINE (ACF)
		 */
		$this->add_control(
			'show_tagline',
			[
				'label'        => __( 'Toon Tagline (ACF)', 'jellopoint-custom-blocks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Ja', 'jellopoint-custom-blocks' ),
				'label_off'    => __( 'Nee', 'jellopoint-custom-blocks' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'tagline_field',
			[
				'label'       => __( 'ACF veldnaam voor Tagline', 'jellopoint-custom-blocks' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'tag_line',
				'placeholder' => 'bijv. tag_line',
				'description' => __( 'Naam van het ACF-veld (Text) dat de tagline bevat op de gekozen post.', 'jellopoint-custom-blocks' ),
				'condition'   => [
					'show_tagline' => 'yes',
				],
			]
		);

		$this->add_control(
			'tagline_position',
			[
				'label'     => __( 'Positie Tagline', 'jellopoint-custom-blocks' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'above_title',
				'options'   => [
					'above_title' => __( 'Boven titel', 'jellopoint-custom-blocks' ),
					'below_title' => __( 'Onder titel', 'jellopoint-custom-blocks' ),
				],
				'condition' => [
					'show_tagline' => 'yes',
				],
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
				// Neem ook eventuele nested spans etc. mee:
				'selector' => '{{WRAPPER}} .jp-block__title, {{WRAPPER}} .jp-block__title *',
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
		 * STYLING – Tagline
		 */
		$this->start_controls_section(
			'section_style_tagline',
			[
				'label'     => __( 'Tagline', 'jellopoint-custom-blocks' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_tagline' => 'yes',
				],
			]
		);

		$this->add_control(
			'tagline_color',
			[
				'label'     => __( 'Kleur', 'jellopoint-custom-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jp-block__tagline' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tagline_typography',
				'label'    => __( 'Typografie', 'jellopoint-custom-blocks' ),
				'selector' => '{{WRAPPER}} .jp-block__tagline, {{WRAPPER}} .jp-block__tagline *',
			]
		);

		$this->add_responsive_control(
			'tagline_margin',
			[
				'label'      => __( 'Margin', 'jellopoint-custom-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .jp-block__tagline' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'label'     => __( 'Tekst (Content)', 'jellopoint-custom-blocks' ),
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
				'selector' => '{{WRAPPER}} .jp-block__content, {{WRAPPER}} .jp-block__content *',
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
	 * Lijst met posts uit ACF-gerelateerde custom post types.
	 *
	 * @return array
	 */
	protected function get_post_options_acf_cpts() {
		$options = [];

		// Eerst alle publieke, niet-builtin CPT's ophalen.
		$post_types = get_post_types(
			[
				'public'   => true,
				'show_ui'  => true,
				'_builtin' => false,
			],
			'objects'
		);

		if ( empty( $post_types ) ) {
			return $options;
		}

		// Als ACF aanwezig is, beperken tot ACF-compatibele post types.
		if ( function_exists( 'acf_get_post_types' ) ) {
			$acf_types = acf_get_post_types(
				[
					// Standaard post/page/attachment uitsluiten.
					'exclude' => [ 'post', 'page', 'attachment' ],
				]
			);

			if ( ! empty( $acf_types ) ) {
				$post_types = array_intersect_key( $post_types, array_flip( $acf_types ) );
			}
		}

		if ( empty( $post_types ) ) {
			return $options;
		}

		foreach ( $post_types as $post_type => $pt_obj ) {
			$args = [
				'post_type'      => $post_type,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'fields'         => 'ids',
			];

			$post_ids = get_posts( $args );

			if ( empty( $post_ids ) || is_wp_error( $post_ids ) ) {
				continue;
			}

			$label = ! empty( $pt_obj->labels->singular_name ) ? $pt_obj->labels->singular_name : $post_type;

			foreach ( $post_ids as $post_id ) {
				$title = get_the_title( $post_id );
				if ( '' === $title ) {
					$title = sprintf( __( '(geen titel) – ID %d', 'jellopoint-custom-blocks' ), $post_id );
				}
				$options[ $post_id ] = sprintf( '%s – %s (ID %d)', $label, $title, $post_id );
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
				echo esc_html__( 'Selecteer een post in de widget instellingen.', 'jellopoint-custom-blocks' );
				echo '</div>';
			}
			return;
		}

		$post = get_post( $post_id );

		if ( ! $post ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="jp-block jp-block--notice">';
				echo esc_html__( 'De geselecteerde post bestaat niet (meer).', 'jellopoint-custom-blocks' );
				echo '</div>';
			}
			return;
		}

		$show_title     = ( isset( $settings['show_title'] ) && 'yes' === $settings['show_title'] );
		$show_content   = ( isset( $settings['show_content'] ) && 'yes' === $settings['show_content'] );
		$show_tagline   = ( isset( $settings['show_tagline'] ) && 'yes' === $settings['show_tagline'] );
		$tagline_field  = ! empty( $settings['tagline_field'] ) ? $settings['tagline_field'] : '';
		$tagline_pos    = ! empty( $settings['tagline_position'] ) ? $settings['tagline_position'] : 'above_title';

		$title        = get_the_title( $post_id );
		$raw_content  = get_post_field( 'post_content', $post_id );
		$content_html = '';

		if ( $raw_content ) {
			// Geen the_content filters – alleen shortcodes + automatische paragrafen.
			$content_html = wpautop( do_shortcode( $raw_content ) );
		}

		$tagline_html = '';

		if ( $show_tagline && $tagline_field && function_exists( 'get_field' ) ) {
			$raw_tagline = get_field( $tagline_field, $post_id );
			if ( is_string( $raw_tagline ) && '' !== trim( $raw_tagline ) ) {
				$tagline_html = esc_html( $raw_tagline );
			}
		}
		?>
		<div class="jp-block">
			<?php
			// Tagline boven titel.
			if ( $tagline_html && 'above_title' === $tagline_pos ) :
				?>
				<div class="jp-block__tagline">
					<?php echo $tagline_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php endif; ?>

			<?php if ( $show_title && $title ) : ?>
				<div class="jp-block__title elementor-heading-title">
					<?php echo esc_html( $title ); ?>
				</div>
			<?php endif; ?>

			<?php
			// Tagline onder titel.
			if ( $tagline_html && 'below_title' === $tagline_pos ) :
				?>
				<div class="jp-block__tagline">
					<?php echo $tagline_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
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
