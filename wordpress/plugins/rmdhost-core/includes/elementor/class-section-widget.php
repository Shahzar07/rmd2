<?php
/**
 * Base widget: builds Elementor controls from the section schema and renders
 * the theme section.
 *
 * @package RMDHost_Core
 */

namespace RMDHost_Core\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;
use RMDHost_Core\Render;
use RMDHost_Core\Schema;

defined( 'ABSPATH' ) || exit;

/**
 * Section widget.
 */
abstract class Section_Widget extends Widget_Base {

	/**
	 * Section slug (set by each subclass).
	 *
	 * @var string
	 */
	protected $section = '';

	/**
	 * Elementor icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-section';

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'rmdhost-' . $this->section;
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		$sections = Render::sections();
		return 'RMDHost · ' . ( $sections[ $this->section ] ?? $this->section );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return $this->icon;
	}

	/**
	 * Categories.
	 *
	 * @return string[]
	 */
	public function get_categories() {
		return array( 'rmdhost' );
	}

	/**
	 * Search keywords.
	 *
	 * @return string[]
	 */
	public function get_keywords() {
		return array( 'rmdhost', 'hosting', 'server', $this->section );
	}

	/**
	 * Front-end script dependency (theme bundle).
	 *
	 * @return string[]
	 */
	public function get_script_depends() {
		return array( 'rmdhost-main' );
	}

	/**
	 * Render on the server in the editor too (sections are dynamic).
	 *
	 * @return bool
	 */
	public function is_dynamic_content(): bool {
		return true;
	}

	/**
	 * Controls.
	 */
	protected function register_controls() {
		$defaults = function_exists( 'rmdhost_section_defaults' ) ? rmdhost_section_defaults( $this->section ) : array();
		$fields   = Schema::fields( $this->section );

		$this->start_controls_section(
			'rmd_content',
			array(
				'label' => __( 'Content', 'rmdhost-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		if ( ! $fields ) {
			$this->add_control(
				'rmd_info',
				array(
					'type' => Controls_Manager::RAW_HTML,
					'raw'  => esc_html__( 'This section is filled automatically from your RMDHost data (Customizer and RMDHost menu).', 'rmdhost-core' ),
				)
			);
		}
		foreach ( $fields as $key => $field ) {
			if ( 'repeater' === $field[0] ) {
				$repeater = new Repeater();
				foreach ( $field['fields'] as $sub_key => $sub ) {
					$repeater->add_control( $sub_key, $this->control( $sub, null ) );
				}
				$items = array();
				foreach ( (array) ( $defaults[ $key ] ?? array() ) as $item ) {
					$row = array();
					foreach ( $field['fields'] as $sub_key => $sub ) {
						$row[ $sub_key ] = $this->default_value( $sub, $item[ $sub_key ] ?? '' );
					}
					$items[] = $row;
				}
				$first = array_keys( $field['fields'] );
				$this->add_control(
					$key,
					array(
						'label'       => $field[1],
						'type'        => Controls_Manager::REPEATER,
						'fields'      => $repeater->get_controls(),
						'default'     => $items,
						'title_field' => in_array( 'title', $first, true ) ? '{{{ title }}}' : ( in_array( 'label', $first, true ) ? '{{{ label }}}' : '{{{ tag }}}' ),
					)
				);
				continue;
			}
			$this->add_control( $key, $this->control( $field, $defaults[ $key ] ?? null ) );
		}
		$this->end_controls_section();

		$this->start_controls_section(
			'rmd_style',
			array(
				'label' => __( 'Section', 'rmdhost-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'rmd_padding',
			array(
				'label'              => __( 'Vertical padding', 'rmdhost-core' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => array( 'px', 'em', 'vh' ),
				'allowed_dimensions' => 'vertical',
				'selectors'          => array(
					'{{WRAPPER}} section.section, {{WRAPPER}} section.cta-band' => 'padding-top: {{TOP}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'rmd_bg',
			array(
				'label'     => __( 'Background', 'rmdhost-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} section.section' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rmd_note',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Colours, fonts and spacing follow the RMDHost design system. Change the accent colour under Appearance → Customize → RMDHost Theme.', 'rmdhost-core' ),
				'content_classes' => 'elementor-descriptor',
			)
		);
		$this->end_controls_section();
	}

	/**
	 * Control definition for a field.
	 *
	 * @param array $field   Field.
	 * @param mixed $fallback Default value.
	 * @return array
	 */
	private function control( $field, $fallback ) {
		$c = array(
			'label'       => $field[1],
			'label_block' => true,
		);
		switch ( $field[0] ) {
			case 'textarea':
				$c['type'] = Controls_Manager::TEXTAREA;
				$c['rows'] = 3;
				break;
			case 'lines':
				$c['type']        = Controls_Manager::TEXTAREA;
				$c['rows']        = 4;
				$c['description'] = __( 'One item per line.', 'rmdhost-core' );
				break;
			case 'url':
				$c['type']    = Controls_Manager::URL;
				$c['options'] = false;
				break;
			case 'number':
				$c['type'] = Controls_Manager::NUMBER;
				$c['min']  = 0;
				break;
			case 'switch':
				$c['type']        = Controls_Manager::SWITCHER;
				$c['label_block'] = false;
				break;
			case 'image':
				$c['type']        = Controls_Manager::MEDIA;
				$c['description'] = __( 'Leave empty to use the bundled photo.', 'rmdhost-core' );
				break;
			case 'group':
				$c['type']    = Controls_Manager::SELECT;
				$c['options'] = array( '' => '—' ) + Schema::groups();
				break;
			case 'faq_group':
				$c['type']    = Controls_Manager::SELECT;
				$c['options'] = array( 'general' => __( 'General (homepage)', 'rmdhost-core' ) ) + Schema::groups();
				break;
			case 'groups':
				$c['type']     = Controls_Manager::SELECT2;
				$c['multiple'] = true;
				$c['options']  = Schema::groups();
				break;
			case 'select':
				$c['type']    = Controls_Manager::SELECT;
				$c['options'] = $field['options'];
				break;
			default:
				$c['type'] = Controls_Manager::TEXT;
		}
		if ( null !== $fallback ) {
			$c['default'] = $this->default_value( $field, $fallback );
		}
		return $c;
	}

	/**
	 * Convert a section default into the control's value format.
	 *
	 * @param array $field Field.
	 * @param mixed $value Default.
	 * @return mixed
	 */
	private function default_value( $field, $value ) {
		switch ( $field[0] ) {
			case 'switch':
				return $value ? 'yes' : '';
			case 'url':
				return array( 'url' => (string) $value );
			case 'image':
				return is_numeric( $value ) ? array( 'id' => (int) $value ) : array( 'url' => '' );
			case 'lines':
				return implode( "\n", (array) $value );
			case 'groups':
				return array_values( (array) $value );
			default:
				return is_scalar( $value ) ? (string) $value : '';
		}
	}

	/**
	 * Render.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		echo Render::section( $this->section, Schema::to_args( $this->section, (array) $settings ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme template parts escape their output.
	}
}
