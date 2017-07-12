<?php

/**
 * Fired during plugin activation
 *
 * @link       http://odude.com/
 * @since      1.0.0
 *
 * @package    ISecure
 * @subpackage ISecure/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    ISecure
 * @subpackage ISecure/includes
 * @author     Navneet Gupta <navneet@odude.com>
 */
class ISecure_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		
		
		//create table for user activity
        global $wpdb;
        $table_name = $wpdb->prefix . "isecure_user_activity";
        //table is not created. you may create the table here.
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) 
		{
            $create_table_query = "CREATE TABLE $table_name (uactid bigint(20) unsigned NOT NULL auto_increment,post_id int(20) unsigned NOT NULL,post_title varchar(250) NOT NULL,user_id bigint(20) unsigned NOT NULL default '0',user_name varchar(50) NOT NULL,user_role varchar(50) NOT NULL,user_email varchar(50) NOT NULL,ip_address varchar(50) NOT NULL,modified_date datetime NOT NULL default '0000-00-00 00:00:00',object_type varchar(50) NOT NULL default 'post',action varchar(50) NOT NULL,PRIMARY KEY (uactid))";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta($create_table_query);
        }
		
		//create table for email activity
		$table_name = $wpdb->prefix . "isecure_email_activity";
        //table is not created. you may create the table here.
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) 
		{
			$create_table_query = "CREATE TABLE $table_name (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				to_email VARCHAR(100) NOT NULL,
				subject VARCHAR(250) NOT NULL,
				message TEXT NOT NULL,
				headers TEXT NOT NULL,
				attachments TEXT NOT NULL,
				sent_date timestamp NOT NULL,
				PRIMARY KEY (id)
			)";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta($create_table_query);
		}
		
		
		//create table for visitor activity
		$table_name = $wpdb->prefix . "isecure_visitor_activity";
        //table is not created. you may create the table here.
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) 
		{
			$create_table_query = "CREATE TABLE $table_name (
					id int(100) NOT NULL AUTO_INCREMENT,
					session_id	VARCHAR( 255 )	NOT NULL,
					visit_date	DATE NOT NULL,
					visit_time	TIME NOT NULL,
					visit_endtime	TIME NOT NULL,
					userid	VARCHAR( 50 ),
					browser	VARCHAR( 50 ),
					platform	VARCHAR( 50 ),
					ip	VARCHAR( 20 ),
					city	VARCHAR( 50 ),
					region	VARCHAR( 50 ),
					countryName	VARCHAR( 50 ),
					url_id	VARCHAR( 255 ),
					url_term	VARCHAR( 255 ),
					referer_doamin	VARCHAR( 255 ),
					referer_url	TEXT,
					screensize	VARCHAR( 50 ),
					PRIMARY KEY (id)
			)";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta($create_table_query);
		}
		
   

	}

}
