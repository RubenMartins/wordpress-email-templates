<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wp.timersys.com
 * @since      1.0.0
 *
 * @package    Mailtpl
 * @subpackage Mailtpl/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mailtpl
 * @subpackage Mailtpl/admin
 * @author     Damian Logghe <info@timersys.com>
 */
class Mailtpl_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}


	/**
	 * Create the wp-admin menu link
	 */
	public function add_menu_link() {
		#add_menu_page( 'Email Templates', 'Email Templates', 'manage_options', $this->plugin_name , array( $this, 'settings_page'), 'dashicons-feedback'  );
		global $menu;
		$link = add_query_arg(
			array(
				'url'               => urlencode( site_url('/?mailtpl_display=true') ),
				'return'            => urlencode( admin_url() ),
				'mailtpl_display'   => 'true'
			),
			admin_url( 'customize.php' )
		);
		array_push($menu , array( 'Email Templates', 'manage_options', $link, '', 'menu-top', 'menu-mailtpl', 'dashicons-feedback' ) );

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mailtpl_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mailtpl_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mailtpl-admin.css', array(), $this->version, 'all' );

	}


	/**
	 * If we are in our template strip everything out and leave it clean
	 * @since 1.0.0
	 */
	public function remove_all_actions(){
		global $wp_scripts, $wp_styles;

		$exceptions = array(
			'mailtpl-js',
			'jquery',
			'query-monitor',
			'mailtpl-front-js',
			'customize-preview',
			'customize-controls',
		);

		foreach( $wp_scripts->queue as $handle ){
			if( in_array($handle, $exceptions))
				continue;
			wp_dequeue_script($handle);
		}

		foreach( $wp_styles->queue as $handle ){
			if( in_array($handle, $exceptions) )
				continue;
			wp_dequeue_style($handle);
		}

		// Now remove actions
		$action_exceptions = array(
			'wp_print_footer_scripts',
			'wp_admin_bar_render',
		);

		// No core action in header
		remove_all_actions('wp_header');

		global $wp_filter;
		foreach( $wp_filter['wp_footer'] as $priority => $handle ){
			if( in_array( key($handle), $action_exceptions ) )
				continue;
			unset( $wp_filter['wp_footer'][$priority] );
		}
	}

}