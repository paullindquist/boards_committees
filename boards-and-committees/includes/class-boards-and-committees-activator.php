<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Boards_And_Committees
 * @subpackage Boards_And_Committees/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Boards_And_Committees
 * @subpackage Boards_And_Committees/includes
 * @author     Paul Lindquist <paul.lindquist@gmail.com>
 */
class Boards_And_Committees_Activator {

    /**
     * Create boards and committees tables
     *
     * @since    1.0.1
     */
    public static function activate() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $groups_table_name = $wpdb->prefix . 'groups';
        $groups_sql = "CREATE TABLE $groups_table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            group_name TEXT NOT NULL,
            UNIQUE KEY id (id)
        ) $charset_collate;";

        $boards_table_name = $wpdb->prefix . 'boards';
        $boards_sql = "CREATE TABLE $boards_table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            group_type mediumint(9),
            name TEXT NOT NULL,
            UNIQUE KEY id (id)
        ) $charset_collate;";

        $committees_table_name = $wpdb->prefix . 'committees';
        $committees_sql = "CREATE TABLE $committees_table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            group_type mediumint(9),
            name TEXT NOT NULL,
            UNIQUE KEY id (id)
        ) $charset_collate;";

        $members_table_name = $wpdb->prefix . 'members';
        $members_sql = "CREATE TABLE $members_table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name TEXT NOT NULL,
            UNIQUE KEY id (id)
        ) $charset_collate;";

        $member_groups_table_name = $wpdb->prefix . 'member_groups';
        $member_groups_sql = "CREATE TABLE $member_groups_table_name (
            group_id mediumint(9) NOT NULL,
            member_id mediumint(9) NOT NULL,
            UNIQUE KEY (group_id, member_id)
        ) $charset_collate;";


        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $groups_sql );
        dbDelta( $boards_sql );
        dbDelta( $committees_sql );
        dbDelta( $members_sql );
        dbDelta( $member_groups_sql );

        $groups_insert = 'INSERT INTO ' . $wpdb->prefix .'groups (id, group_name) ' . ' VALUES ("1", "committee"), ("2", "board")';
        $wpdb->query($groups_insert);

    }

}
