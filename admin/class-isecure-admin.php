<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://odude.com/
 * @since      1.0.0
 *
 * @package    ISecure
 * @subpackage ISecure/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    ISecure
 * @subpackage ISecure/admin
 * @author     Navneet Gupta <navneet@odude.com>
 */
class ISecure_Admin {

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
		
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_menu', array($this,'isecure_add_admin_menu') );
		add_action( 'admin_init', array($this,'isecure_help_page_init' ));
		add_action( 'admin_init', array($this,'isecure_activity_init' ));
		add_action( 'admin_init', array($this,'isecure_setting_page_init' ));
		
		
		 
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
		 * defined in ISecure_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The ISecure_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/isecure-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'_tab', plugin_dir_url( __FILE__ ) . 'css/aristo.css', array(), $this->version, 'all' );
		wp_enqueue_style('font-awesome','https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css');
		//wp_enqueue_style('pure-min','http://yui.yahooapis.com/pure/0.6.0/pure-min.css');

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
		 * defined in ISecure_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The ISecure_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/isecure-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-form');
		wp_enqueue_script('jquery-ui-core');
		 wp_enqueue_script('jquery-ui-tabs');// enqueue jQuery UI Tabs
	}
	
	 function isecure_add_admin_menu(  ) 
	{ 
	add_menu_page(__('iSecure', 'isecure'), __('iSecure', 'isecure'), 'manage_options', 'isecure', 'isecure_dashboard', 'dashicons-admin-users');
	add_submenu_page( 'isecure', 'ISecure User Activity', 'User Activity', 'manage_options', 'isecure_user_activity', 'isecure_user_activity' );
	add_submenu_page( 'isecure', 'ISecure Email Activity', 'Email Activity', 'manage_options', 'isecure_email_activity', 'isecure_email_activity' );
	add_submenu_page( 'isecure', 'ISecure Visitor Activity', 'Visitor Activity', 'manage_options', 'isecure_visitor_activity', 'isecure_visitor_activity' );
	//add_submenu_page( 'isecure', 'ISecure Help', 'Help', 'manage_options', 'isecure_help', 'isecure_help_page' );
	
	} 

	public function init()
	{
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-useractivity-log.php';
		$userlog=new ISecure_UserActivity_Log;	
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-emailactivity-log.php';
		$emaillog=new ISecure_EmailActivity_Log;
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-visitoractivity-log.php';
		$visitorlog=new ISecure_VisitorActivity_Log;
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-serverstat-show.php';
		$serverstat=new ISecure_Serverstat_Show;
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-dashboard.php';
		$dashboard=new ISecure_Dashboard;
		
		
		
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-setting.php';
	}



function isecure_help_page_init()
{

	
	function isecure_help_page()
	{
		
		$defArray = array( "publish" => "1" );
		$options = get_option( "isecure_settings", $defArray );
		$options = wp_parse_args( $options , $defArray  );
		
		print_r($options);
		
		
	}
}
function isecure_setting_page_init()
{

	
	function isecure_setting_page()
	{
		
		
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-setting.php';
		//$abc1=new ISecure_Setting;
		//$abc1->isecure_options_page();
			
	}
	
}
function isecure_activity_init()
{
	function isecure_dashboard() 
	{
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-dashboard-show.php';
		$dashboard=new ISecure_Dashboard_Show;
		$dashboard->init();
		
	}
	
	function isecure_user_activity()
	{
		
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-useractivity-show.php';
		$abc=new ISecure_UserActivity_Show;
		$abc->init();
		
		
	}
	
	function isecure_email_activity()
	{
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-emailactivity-show.php';
		$abc=new ISecure_EmailActivity_Show;
		$abc->init();
		
		
	}
	
		function isecure_visitor_activity()
	{
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-visitoractivity-show.php';
		$abc=new ISecure_VisitorActivity_Show;
		$abc->init();
		
		
	}
	
}

	
	
	
	
}