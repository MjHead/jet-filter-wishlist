<?php
/**
 * Class: Jet_Filter_Wishlist_Provider
 * Name: Wishlist
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define Jet_Filter_Wishlist_Provider class
 */
class Jet_Filter_Wishlist_Provider extends Jet_Smart_Filters_Provider_Base {

	private $query_id;

	/**
	 * Watch for default query
	 */
	public function __construct() {

		if ( ! jet_smart_filters()->query->is_ajax_filter() ) {
			add_filter( 'elementor/widget/before_render_content', array( $this, 'store_default_settings' ), 0, 2 );
			add_filter( 'woocommerce_product_object_query_args', array( $this, 'store_default_query' ), 0, 2 );
		}

	}

	/**
	 * Store default query args
	 *
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	public function store_default_settings( $widget ) {

		if ( 'jet-wishlist' !== $widget->get_name() ) {
			return;
		}

		$settings = $widget->get_settings();

		if ( empty( $settings['_element_id'] ) ) {
			$this->query_id = false;
		} else {
			$this->query_id = $settings['_element_id'];
		}

		jet_smart_filters()->providers->store_provider_settings( $this->get_id(), array(
			'empty_wishlist_text'     => $settings['empty_wishlist_text'],
			'wishlist_remove_text'    => $settings['remove_button_text'],
			'wishlist_remove_icon'    => $settings['remove_button_icon'],
			'thumbnail_position'      => $settings['thumbnail_position'],
			'cw_thumbnail_size'       => $settings['cw_thumbnail_size_size'],
			'cw_rating_icon'          => $settings['cw_rating_icon'],
			'wishlist_columns'        => $settings['wishlist_columns'],
			'wishlist_columns_tablet' => $settings['wishlist_columns_tablet'],
			'wishlist_columns_mobile' => $settings['wishlist_columns_mobile'],
			'_widget_id'              => $this->get_id()
		), $this->query_id );

	}

	public function store_default_query( $query_args ) {

		$args = array(
			'include' => $query_args['include'],
		);

		jet_smart_filters()->query->store_provider_default_query( $this->get_id(), $args, $this->query_id );

		return $query_args;
	}

	/**
	 * Get provider name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'Wishlist';
	}

	/**
	 * Get provider ID
	 *
	 * @return string
	 */
	public function get_id() {
		return 'jet-wishlist';
	}

	/**
	 * Get filtered provider content
	 *
	 * @return string
	 */
	public function ajax_get_content() {

		if ( ! function_exists( 'jet_cw_widgets_functions' ) ) {
			return;
		}

		add_filter( 'woocommerce_product_object_query_args', array( $this, 'add_query_args' ), 0 );

		jet_cw_widgets_functions()->get_widget_wishlist( jet_smart_filters()->query->get_query_settings() );

	}

	/**
	 * Get provider wrapper selector
	 *
	 * @return string
	 */
	public function get_wrapper_selector() {
		return '.jet-wishlist.jet-cw';
	}

	/**
	 * Action for wrapper selector - 'insert' into it or 'replace'
	 *
	 * @return string
	 */
	public function get_wrapper_action() {
		return 'insert';
	}

	/**
	 * Set prefix for unique ID selector. Mostly is default '#' sign, but sometimes class '.' sign needed
	 *
	 * @return bool
	 */
	public function id_prefix() {
		return '#';
	}

	/**
	 * Add custom settings for AJAX request
	 */
	public function add_settings( $settings, $widget ) {

		if ( 'jet-wishlist' !== $widget->get_name() ) {
			return $settings;
		}

		return jet_smart_filters()->query->get_query_settings();
	}

	/**
	 * Pass args from reuest to provider
	 */
	public function apply_filters_in_request() {

		$args = jet_smart_filters()->query->get_query_args();

		if ( ! $args ) {
			return;
		}

		add_filter( 'jet-engine/listing/grid/posts-query-args', array( $this, 'add_query_args' ) );

	}

	/**
	 * Add custom query arguments
	 *
	 * @param array $args [description]
	 */
	public function add_query_args( $args = array() ) {

		$args = array_merge( $args, jet_smart_filters()->query->get_query_args() );

		return $args;

	}
}
