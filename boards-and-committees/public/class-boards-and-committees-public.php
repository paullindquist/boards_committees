<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link	   http://example.com
 * @since	  1.0.0
 *
 * @package	Boards_And_Committees
 * @subpackage Boards_And_Committees/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package	Boards_And_Committees
 * @subpackage Boards_And_Committees/public
 * @author	 Paul Lindquist <paul.lindquist@gmail.com>
 */
class Boards_And_Committees_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since	1.0.0
	 * @access   private
	 * @var	  string	$boards_and_committees	The ID of this plugin.
	 */
	private $boards_and_committees;

	/**
	 * The version of this plugin.
	 *
	 * @since	1.0.0
	 * @access   private
	 * @var	  string	$version	The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since	1.0.0
	 * @param	  string	$boards_and_committees	   The name of the plugin.
	 * @param	  string	$version	The version of this plugin.
	 */
	public function __construct( $boards_and_committees, $version ) {

		$this->boards_and_committees = $boards_and_committees;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since	1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Boards_And_Committees_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Boards_And_Committees_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->boards_and_committees, plugin_dir_url( __FILE__ ) . 'css/boards-and-committees-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since	1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Boards_And_Committees_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Boards_And_Committees_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->boards_and_committees, plugin_dir_url( __FILE__ ) . 'js/boards-and-committees-public.js', array( 'jquery' ), $this->version, false );

	}


	/**
	 * Write out shortcode contents
	 *
	 * @since	1.0.2
	 */
	public function do_shortcode() {
		global $wpdb;
		$active_tab_page = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'display_members';


		$committees_sql = 'SELECT ' . $wpdb->prefix . 'committees.id AS committee_id, ' . $wpdb->prefix . 'committees.name AS committee_name FROM ' . $wpdb->prefix . 'committees; ';

		$boards_sql = 'SELECT ' . $wpdb->prefix . 'boards.id AS board_id, '. $wpdb->prefix .'boards.name AS board_name FROM ' . $wpdb->prefix . 'boards;';

		$members_sql = 'SELECT ' . $wpdb->prefix . 'members.id AS member_id, '. $wpdb->prefix .'members.name AS member_name, ' . 
			$wpdb->prefix . 'committee_members.committee_id AS committee_member_id, ' . $wpdb->prefix . 'board_members.member_id AS board_member_id, ' .
			$wpdb->prefix . 'committee_members.role AS committee_member_role, ' . $wpdb->prefix . 'board_members.role AS board_member_role ' .
			'FROM ' . $wpdb->prefix . 'members ' .
			'LEFT OUTER JOIN ' . $wpdb->prefix . 'committee_members ON ' . $wpdb->prefix . 'committee_members.member_id = ' . $wpdb->prefix . 'members.id ' .
			'LEFT OUTER JOIN ' . $wpdb->prefix . 'board_members ON ' . $wpdb->prefix . 'board_members.member_id = ' . $wpdb->prefix . 'members.id ';


		$committees_result = $wpdb->get_results( $committees_sql,  OBJECT);
		$boards_result = $wpdb->get_results( $boards_sql,  OBJECT);
		$members_result = $wpdb->get_results( $members_sql,  OBJECT);

		foreach($committees_result as $committee) {
			$committee->members =  [];
			foreach($members_result as $member) {
				if ($member->committee_member_id == $committee->committee_id) {
					array_push($committee->members, $member);
				}
			}
		}

		$wrapped_result = new stdClass();
		$wrapped_result->boards = $boards_result;
		$wrapped_result->members = $members_result;
		$wrapped_result->committees = $committees_result;

		require plugin_dir_path( dirname( __FILE__ ) ) . 'includes/handlebars/src/Handlebars/Autoloader.php';

		Handlebars\Autoloader::register();


		$engine = new Handlebars\Handlebars(array(
			'loader' => new \Handlebars\Loader\FilesystemLoader(dirname(__FILE__) . '/views/')
		));
		$html = '<div class="wrap form-horizontal">';
		//$html .= stripslashes($engine->render($active_tab_page, $wrapped_result));
		$html .= stripslashes($engine->render('display_committees', $wrapped_result));
		$html .= '</div>';
		return $html;
	}

}
