<?php
namespace JelloPoint\CustomBlocks\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * JelloPoint Custom Block widget.
 *
 * Toont de titel + content van een gekozen post uit ACF-compatibele custom post types
 * (bijv. standaard_teksten), met optionele ACF-tagline, featured image en button,
 * en volledige styling-controls.
 */
class Custom_Block extends Widget_Base {

	public function get_name() {
		return 'jellopoint_custom_block';
	}

	public function get_title() {
		return __( 'JelloPoint Custom Block', 'jellopoint-custom-blocks' );
	}

	public function get_icon() {
		return 'eicon-text';
	}

	public function get_categories() {
		return [ 'jellopoint-widgets' ];
	}

	public function get_keywords() {
		return [ 'jellopoint', 'custom', 'block', 'tekst', 'standaard', 'content', 'cpt', 'image', 'button' ];
	}

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

		/**
		 * AFBEELDING (Featured Image)
		 */
		$this->add_control(
			'show_image',
			[
				'label'        => __( 'Toon Afbeelding (Featured Image)', 'jellopoint-custom-blocks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Ja', 'jellopoint-custom-blocks' ),
				'label_off'    => __( 'Nee', 'jellopoint-custom-blocks' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'image_position',
			[
				'label'     => __( 'Afbeelding positie', 'jellopoint-custom-blocks' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'top',
				'options'   => [
					'top'    => __( 'Boven titel & tekst', 'jellopoint-custom-blocks' ),
					'bottom' => __( 'Onder titel & tekst', 'jellopoint-custom-blocks' ),
					'left'   => __( 'Links van titel & tekst', 'jellopoint-custom-blocks' ),
					'right'  => __( 'Rechts van titel & tekst', 'jellopoint-custom-blocks' ),
				],
				'condition' => [
					'show_image' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image_size',
				'default'   => 'medium',
				'separator' => 'none',
				'condition' => [
					'show_image' => 'yes',
				],
			]
		);

		/**
		 * BUTTON
		 */
		$this->add_control(
			'show_button',
			[
				'label'        => __( 'Toon Button', 'jellopoint-custom-blocks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Ja', 'jellopoint-custom-blocks' ),
				'label_off'    => __( 'Nee', 'jellopoint-custom-blocks' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'       => __( 'Button tekst', 'jellopoint-custom-blocks' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Lees meer', 'jellopoint-custom-blocks' ),
				'placeholder' => __( 'Bijv. Lees meer', 'jellopoint-custom-blocks' ),
				'condition'   => [
					'show_button' => 'yes',
				],
			]
		);

		$this->add_control(
			'button_url',
			[
				'label'         => __( 'Button URL', 'jellopoint-custom-blocks' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => __( 'https://voorbeeld.nl', 'jellopoint-custom-blocks' ),
				'options'       => [ 'url', 'is_external', 'nofollow' ],
				'default'       => [
					'url'         => '',
					'is_external' => false,
					'nofollow'    => false,
				],
				'show_external' => true,
				'condition'     => [
					'show_button' => 'yes',
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

		/**
		 * STYLING – Afbeelding
		 */
		$this->start_controls_section(
			'section_style_image',
			[
				'label'     => __( 'Afbeelding', 'jellopoint-custom-blocks' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_image' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label'      => __( 'Breedte', 'jellopoint-custom-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range'      => [
					'%'  => [
						'min' => 10,
						'max' => 100,
					],
					'px' => [
						'min' => 50,
						'max' => 800,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .jp-block__media img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_max_width',
			[
				'label'      => __( 'Max-width', 'jellopoint-custom-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range'      => [
					'%'  => [
						'min' => 10,
						'max' => 100,
					],
					'px' => [
						'min' => 50,
						'max' => 1000,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .jp-block__media img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_margin',
			[
				'label'      => __( 'Margin', 'jellopoint-custom-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .jp-block__media' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label'      => __( 'Border radius', 'jellopoint-custom-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .jp-block__media img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * STYLING – Button
		 */
		$this->start_controls_section(
			'section_style_button',
			[
				'label'     => __( 'Button', 'jellopoint-custom-blocks' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_button' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'label'    => __( 'Typografie', 'jellopoint-custom-blocks' ),
				'selector' => '{{WRAPPER}} .jp-block__button',
			]
		);

		$this->start_controls_tabs( 'button_style_tabs' );

		$this->start_controls_tab(
			'button_style_normal',
			[
				'label' => __( 'Normaal', 'jellopoint-custom-blocks' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     => __( 'Tekstkleur', 'jellopoint-custom-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jp-block__button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label'     => __( 'Achtergrondkleur', 'jellopoint-custom-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jp-block__button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_style_hover',
			[
				'label' => __( 'Hover', 'jellopoint-custom-blocks' ),
			]
		);

		$this->add_control(
			'button_text_color_hover',
			[
				'label'     => __( 'Tekstkleur (hover)', 'jellopoint-custom-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jp-block__button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_bg_color_hover',
			[
				'label'     => __( 'Achtergrondkleur (hover)', 'jellopoint-custom-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jp-block__button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'button_padding',
			[
				'label'      => __( 'Padding', 'jellopoint-custom-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .jp-block__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
			[
				'label'      => __( 'Border radius', 'jellopoint-custom-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .jp-block__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_align',
			[
				'label'     => __( 'Uitlijning', 'jellopoint-custom-blocks' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => [
						'title' => __( 'Links', 'jellopoint-custom-blocks' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'     => [
						'title' => __( 'Midden', 'jellopoint-custom-blocks' ),
						'icon'  => 'eicon-text-align-center',
					],
					'flex-end'   => [
						'title' => __( 'Rechts', 'jellopoint-custom-blocks' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .jp-block__button-wrap' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Lijst met posts uit ACF-gerelateerde custom post types,
	 * waarbij bekende "systeem-CPT's" (zoals Elementor Templates) uitgesloten worden.
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

		// Bekende "systeem" CPT's die we NIET willen tonen.
		$exclude_slugs = [
			'elementor_library',
			'elementor_snippet',
			'wp_block',
			'wp_template',
			'wp_template_part',
		];

		// Als ACF aanwezig is, beperken tot ACF-compatibele post types.
		if ( function_exists( 'acf_get_post_types' ) ) {
			$acf_types = acf_get_post_types(
				[
					'exclude' => [ 'post', 'page', 'attachment' ],
				]
			);

			if ( ! empty( $acf_types ) ) {
				$post_types = array_intersect_key( $post_types, array_flip( $acf_types ) );
			}
		}

		// Uitgesloten CPT's verwijderen.
		foreach ( $exclude_slugs as $slug ) {
			if ( isset( $post_types[ $slug ] ) ) {
				unset( $post_types[ $slug ] );
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

		$show_title    = ( isset( $settings['show_title'] ) && 'yes' === $settings['show_title'] );
		$show_content  = ( isset( $settings['show_content'] ) && 'yes' === $settings['show_content'] );
		$show_tagline  = ( isset( $settings['show_tagline'] ) && 'yes' === $settings['show_tagline'] );
		$tagline_field = ! empty( $settings['tagline_field'] ) ? $settings['tagline_field'] : '';
		$tagline_pos   = ! empty( $settings['tagline_position'] ) ? $settings['tagline_position'] : 'above_title';

		$show_image = ( isset( $settings['show_image'] ) && 'yes' === $settings['show_image'] );
		$image_pos  = ! empty( $settings['image_position'] ) ? $settings['image_position'] : 'top';

		$show_button   = ( isset( $settings['show_button'] ) && 'yes' === $settings['show_button'] );
		$button_text   = ! empty( $settings['button_text'] ) ? $settings['button_text'] : '';
		$button_url    = ! empty( $settings['button_url']['url'] ) ? $settings['button_url']['url'] : '';
		$button_target = ! empty( $settings['button_url']['is_external'] ) ? '_blank' : '_self';
		$button_rel    = ! empty( $settings['button_url']['nofollow'] ) ? 'nofollow' : '';

		$title        = get_the_title( $post_id );
		$raw_content  = get_post_field( 'post_content', $post_id );
		$content_html = '';

		if ( $raw_content ) {
			$content_html = wpautop( do_shortcode( $raw_content ) );
		}

		$tagline_html = '';

		if ( $show_tagline && $tagline_field && function_exists( 'get_field' ) ) {
			$raw_tagline = get_field( $tagline_field, $post_id );
			if ( is_string( $raw_tagline ) && '' !== trim( $raw_tagline ) ) {
				$tagline_html = esc_html( $raw_tagline );
			}
		}

		// Featured image – via wp_get_attachment_image.
		$image_html = '';
		if ( $show_image && has_post_thumbnail( $post_id ) ) {
			$image_id = get_post_thumbnail_id( $post_id );
			if ( $image_id ) {
				$size       = ! empty( $settings['image_size_size'] ) ? $settings['image_size_size'] : 'medium';
				$image_html = wp_get_attachment_image( $image_id, $size );
			}
		}

		// Button HTML.
		$button_html = '';
		if ( $show_button && $button_text && $button_url ) {
			$rel_parts = [];
			if ( $button_rel ) {
				$rel_parts[] = $button_rel;
			}
			if ( '_blank' === $button_target ) {
				$rel_parts[] = 'noopener';
				$rel_parts[] = 'noreferrer';
			}
			$rel_attr = '';
			if ( ! empty( $rel_parts ) ) {
				$rel_attr = implode( ' ', array_unique( $rel_parts ) );
			}

			$button_html  = '<div class="jp-block__button-wrap" style="display:flex;">';
			$button_html .= sprintf(
				'<a class="jp-block__button" href="%s"%s%s>%s</a>',
				esc_url( $button_url ),
				$button_target ? ' target="' . esc_attr( $button_target ) . '"' : '',
				$rel_attr ? ' rel="' . esc_attr( $rel_attr ) . '"' : '',
				esc_html( $button_text )
			);
			$button_html .= '</div>';
		}

		// Inner body (zonder wrapper) als herbruikbare routine.
		$render_body_inner = function () use ( $show_title, $title, $tagline_pos, $tagline_html, $show_content, $content_html, $button_html ) {
			if ( 'above_title' === $tagline_pos && $tagline_html ) {
				echo '<div class="jp-block__tagline">';
				echo $tagline_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</div>';
			}

			if ( $show_title && $title ) {
				echo '<div class="jp-block__title elementor-heading-title">';
				echo esc_html( $title );
				echo '</div>';
			}

			if ( 'below_title' === $tagline_pos && $tagline_html ) {
				echo '<div class="jp-block__tagline">';
				echo $tagline_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</div>';
			}

			if ( $show_content && $content_html ) {
				echo '<div class="jp-block__content">';
				echo $content_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</div>';
			}

			if ( $button_html ) {
				echo $button_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		};

		// Bepaal of we side-by-side layout (links/rechts) hebben.
		$has_side_image = ( $image_html && in_array( $image_pos, [ 'left', 'right' ], true ) );

		$wrapper_classes = [
			'jp-block',
			'jp-block--image-' . $image_pos,
		];

		$wrapper_style = '';
		if ( $has_side_image ) {
			// Flex voor de hele block-wrapper.
			$wrapper_style = 'display:flex;gap:1.5rem;align-items:flex-start;flex-wrap:wrap;';
		}

		echo '<div class="' . esc_attr( implode( ' ', $wrapper_classes ) ) . '"';
		if ( $wrapper_style ) {
			echo ' style="' . esc_attr( $wrapper_style ) . '"';
		}
		echo '>';

		if ( $has_side_image ) {
			// LINKS / RECHTS LAYOUT
			if ( 'left' === $image_pos ) {
				if ( $image_html ) {
					echo '<div class="jp-block__media" style="flex:0 0 auto;">';
					echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '</div>';
				}
				echo '<div class="jp-block__body" style="flex:1 1 0;">';
				$render_body_inner();
				echo '</div>';
			} else { // right
				echo '<div class="jp-block__body" style="flex:1 1 0;">';
				$render_body_inner();
				echo '</div>';
				if ( $image_html ) {
					echo '<div class="jp-block__media" style="flex:0 0 auto;">';
					echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '</div>';
				}
			}
		} else {
			// TOP / BOTTOM LAYOUT
			if ( $image_html && 'top' === $image_pos ) {
				echo '<div class="jp-block__media">';
				echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</div>';
			}

			echo '<div class="jp-block__body">';
			$render_body_inner();
			echo '</div>';

			if ( $image_html && 'bottom' === $image_pos ) {
				echo '<div class="jp-block__media">';
				echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</div>';
			}
		}

		echo '</div>'; // .jp-block
	}
}
