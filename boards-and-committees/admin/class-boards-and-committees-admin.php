<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Boards_And_Committees
 * @subpackage Boards_And_Committees/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Boards_And_Committees
 * @subpackage Boards_And_Committees/admin
 * @author     Paul Lindquist <paul.lindquist@gmail.com>
 */
class Boards_And_Committees_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $boards_and_committees    The ID of this plugin.
	 */
	private $boards_and_committees;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private function addRoleToMember($role_id, $member_id, $group_id) {
		//FIXME: this.. group table(s) no longer exist
		$member_group_insert = array(
			'group_id' => $group_id,
			'member_id' => $member_id,
			'role' =>  $member_role
		);
		$wpdb->insert( $wpdb->prefix . 'member_groups', $member_group_insert );
	}

	private function insertMemberIfNotExists($member_name) {
		global $wpdb;

		$wpdb->show_errors     = true;
		$wpdb->suppress_errors = false;

		$member_sql = 'SELECT ' . $wpdb->prefix . 'members.id FROM ' . $wpdb->prefix . 'members WHERE ' . $wpdb->prefix . 'members.name = "'. $member_name .'";';
		$member_results = $wpdb->get_results( $member_sql , OBJECT);

		if (empty($member_results)) {
			$member_insert = array(
				'name' => $member_name
			);
			$wpdb->insert( $wpdb->prefix . 'members', $member_insert );
			$member_id = $wpdb->insert_id;
		} else {
			// hopefully, we only get one??
			foreach ($member_results as $member_result) {
				$member_id = $member_result->id;
			}
		}
		return $member_id;
	}

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $boards_and_committees       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $boards_and_committees, $version ) {

		$this->boards_and_committees = $boards_and_committees;
		$this->version = $version;

		add_action( 'wp_ajax_add_committee', array( $this, 'add_committee') );
		add_action( 'wp_ajax_delete_committee', array( $this, 'delete_committee') );
		add_action( 'wp_ajax_update_committee', array( $this, 'update_committee') );

		add_action( 'wp_ajax_add_committee_member', array( $this, 'add_committee_member') );
		add_action( 'wp_ajax_delete_committee_member', array( $this, 'delete_committee_member') );
		add_action( 'wp_ajax_update_committee_member', array( $this, 'update_committee_member') );

		add_action( 'wp_ajax_add_board', array( $this, 'add_board') );
		add_action( 'wp_ajax_delete_board', array( $this, 'delete_board') );

		add_action( 'wp_ajax_add_member', array( $this, 'add_member') );
		add_action( 'wp_ajax_delete_member', array( $this, 'delete_member') );

	}

	/**
	 *	Deletes a member from the database
	 *
	 * @since    1.0.1
	 */
	public function delete_member() {
		global $wpdb;

		$wpdb->show_errors     = true;
		$wpdb->suppress_errors = false;

		if(!empty($_POST['member_id'])){
			$member_id = $_POST['member_id'];
			$delete = array(
				'id' => $member_id
			);

			$wpdb->delete( $wpdb->prefix .'members', $delete );
			echo '{member_name:' . $member_id . '}';
		}
		wp_die();
	}

	/**
	 *	Adds a member to the database
	 *
	 * @since    1.0.1
	 */
	public function add_member() {
		$new_member_id;

		if(!empty($_POST['member_name'])){
			$member_name = $_POST['member_name'];
			$new_member_id = $this->insertMemberIfNotExists($member_name);
			echo '{"member_id": ' . $new_member_id . '}';
		}
		wp_die();
	}

	/**
	 *	Adds a board to the database
	 *
	 * @since    1.0.1
	 */
	public function add_board() {
		global $wpdb;

		$wpdb->show_errors     = true;
		$wpdb->suppress_errors = false;

		if(!empty($_POST['board_name'])){
			$board_name = $_POST['board_name'];
			// FIXME: 2 is boards in wp_groups table
			$insert = array(
				'group_type' => '1',
				'name' => $board_name
			);

			$wpdb->insert( $wpdb->prefix .'boards', $insert );
			echo '{board_name:' . $board_name . '}';
		}

		wp_die();

	}
	/**
	 *	Deletes a board from the database
	 *
	 * @since    1.0.1
	 */
	public function delete_board() {
		global $wpdb;

		$wpdb->show_errors     = true;
		$wpdb->suppress_errors = false;

		if(!empty($_POST['board_id'])){
			$board_id = $_POST['board_id'];
			$delete = array(
				'id' => $board_id
			);

			$wpdb->delete( $wpdb->prefix .'boards', $delete );
			echo '{board_name:' . $board_id . '}';
		}

		wp_die();

	}

	/**
	 *	Updates a committee from the database
	 *
	 * @since    1.0.1
	 */
	public function update_committee() {
		global $wpdb;

		$committee_name;

		$wpdb->show_errors     = true;
		$wpdb->suppress_errors = false;


		if(!empty($_POST['committee_name'])) {
			$committee_name = $_POST['committee_name'];
			$committee_id = $_POST['committee_id'];

			$wpdb->update( $wpdb->prefix .'committees',
				array(
					'name' => $committee_name
				),
				array(
					'id' => $committee_id
				)
			);
			echo '{committee_name:' . $committee_name . ', committee_id:' . $committee_id . '}';
			echo var_dump( $wpdb->last_query );
		}
		wp_die();
	}

	/**
	 *	Updates a committee from the database
	 *
	 * @since    1.0.1
	 */
	public function update_committee_member() {
		global $wpdb;

		$committee_name;

		$wpdb->show_errors     = true;
		$wpdb->suppress_errors = false;


		if(!empty($_POST['committee_id'])) {
			$committee_id = $_POST['committee_id'];
			$member_id = $_POST['member_id'];
			$member_role = $_POST['member_role'];

			$wpdb->update( $wpdb->prefix .'committee_members',
				array(
					'role' => $member_role
				),
				array(
					'member_id' => $member_id,
					'committee_id' => $committee_id
				)
			);
			echo '{ member : "' . $member_id . '", committee : "' . $committee_id . '", role : "' . $member_role . '" }';
		} else {
			echo '{ test : "2" }';
		}
		wp_die();
	}

	/**
	 *	Adds a member to a committee or board in the database
	 *
	 * @since    1.0.1
	 */
	public function add_committee_member() {
		global $wpdb;

		$wpdb->show_errors     = true;
		$wpdb->suppress_errors = false;

		if(!empty($_POST['member_id'])){
			$member_id = $_POST['member_id'];
			$committee_id = $_POST['committee_id'];
			$committee_member_sql = 'SELECT ' . $wpdb->prefix . 'committee_members.member_id FROM ' . $wpdb->prefix . 'committee_members WHERE ' . $wpdb->prefix . 'committee_members.member_id = "'. $member_id . '" AND ' . $wpdb->prefix . 'committee_members.committee_id = "' . $committee_id . '";';
			$committee_member_results = $wpdb->get_results( $committee_member_sql , OBJECT);

			if (empty($committee_member_results)) {
				$insert = array(
					'member_id' => $member_id,
					'committee_id' => $committee_id
				);
				$wpdb->insert( $wpdb->prefix .'committee_members', $insert );
				echo '{ "member_exists" : "false", "sql": '. $committee_member_sql .' }';
			} else {
				echo '{ "member_exists" : "true", "sql": '. $committee_member_sql .' }';
			}
		}
			wp_die();
	}

		/**
		 *	Adds a committee to the database
		 *
		 * @since    1.0.1
		 */
	public function add_committee() {
		global $wpdb;

		$wpdb->show_errors     = true;
		$wpdb->suppress_errors = false;


		if(!empty($_POST['committee_name'])){
			$committee_name = $_POST['committee_name'];
			// FIXME: 2 is committees in wp_groups table
			$insert = array(
				'group_type' => '2',
				'name' => $committee_name
			);

			$wpdb->insert( $wpdb->prefix .'committees', $insert );
			echo '{committee_name:' . $committee_name . '}';
		}

		wp_die();

	}

	/**
	 *	Deletes a committee member from the database
	 *
	 * @since    1.0.1
	 */
	public function delete_committee_member() {
		global $wpdb;

		$wpdb->show_errors     = true;
		$wpdb->suppress_errors = false;


		if(!empty($_POST['committee_id'])){
			$committee_id = $_POST['committee_id'];
			$member_id = $_POST['member_id'];
			$delete = array(
				'committee_id' => $committee_id,
				'member_id' => $member_id,
			);

			$wpdb->delete( $wpdb->prefix .'committee_members', $delete );
			echo '{committee_name:' . $committee_id . '}';
		}

		wp_die();

	}
	/**
	 *	Deletes a committee from the database
	 *
	 * @since    1.0.1
	 */
	public function delete_committee() {
		global $wpdb;

		$wpdb->show_errors     = true;
		$wpdb->suppress_errors = false;


		if(!empty($_POST['committee_id'])){
			$committee_id = $_POST['committee_id'];
			$delete = array(
				'id' => $committee_id
			);

			$wpdb->delete( $wpdb->prefix .'committees', $delete );
			echo '{committee_name:' . $committee_id . '}';
		}

		wp_die();

	}

	/**
	 *	Displays admin page
	 *
	 * @since    1.0.1
	 */
	public function output_content() {
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
		$html .= stripslashes($engine->render($active_tab_page, $wrapped_result));
		$html .= '</div>';
		echo $html;
	}


	/**
	 * Adds the admin menu item
	 *
	 * @since    1.0.1
	 */
	public function add_menu_page() {
		add_menu_page( 'Boards and Committees', 'Boards and Committees', 'edit_posts', 'boards_and_committees', array($this, 'output_content'), 'dashicons-id', 7  );
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
		 * defined in Boards_And_Committees_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Boards_And_Committees_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->boards_and_committees, plugin_dir_url( __FILE__ ) . 'css/boards-and-committees-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
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

		wp_enqueue_script( $this->boards_and_committees, plugin_dir_url( __FILE__ ) . 'js/boards-and-committees-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->boards_and_committees, 'boards_and_committees', array( 'ajaxurl' => admin_url(  'admin-ajax.php' ) ) );

		}

	}
