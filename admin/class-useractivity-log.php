<?php
class ISecure_UserActivity_Log 
{
	
	public function __construct( ) 
	{
		
		add_action('wp_login', array($this,'isecure_shook_wp_login'));
		add_action('wp_logout', array($this,'isecure_shook_wp_logout'));
		add_action('delete_user', array($this,'isecure_shook_delete_user'));
add_action('user_register', array($this,'isecure_shook_user_register'));
add_action('profile_update', array($this,'isecure_shook_profile_update'));
add_action('add_attachment', array($this,'isecure_shook_add_attachment'));
add_action('edit_attachment', array($this,'isecure_shook_edit_attachment'));
add_action('delete_attachment', array($this,'isecure_shook_delete_attachment'));
add_action('wp_insert_comment', array($this,'isecure_shook_wp_insert_comment'));
add_action('edit_comment', array($this,'isecure_shook_edit_comment'));
add_action('trash_comment', array($this,'isecure_shook_trash_comment'));
	add_action('spam_comment', array($this,'isecure_shook_spam_comment'));
	add_action('unspam_comment', array($this,'isecure_shook_unspam_comment'));
add_action('delete_comment', array($this,'isecure_shook_delete_comment'));
add_action('wp_update_nav_menu', array($this,'isecure_shook_wp_update_nav_menu'));
add_action('wp_create_nav_menu', array($this,'isecure_shook_wp_create_nav_menu'));
add_action('delete_nav_menu', array($this,'isecure_shook_delete_nav_menu'), 10, 2);
add_action('activated_plugin', array($this,'isecure_shook_activated_plugin'));
add_action('deactivated_plugin', array($this,'isecure_shook_deactivated_plugin'));
add_action('created_term', array($this,'isecure_shook_created_term'), 10, 2);
add_action('edited_term', array($this,'isecure_shook_edited_term'), 10, 2);
add_action('delete_term', array($this,'isecure_shook_delete_term'), 10, 3);
add_action('switch_theme', array($this,'isecure_shook_switch_theme'));
add_action('customize_save', array($this,'isecure_shook_customize_save'));
add_action('export_wp', array($this,'isecure_shook_export_wp'));
	add_action('transition_post_status', array($this,'isecure_shook_transition_post_status'), 10, 3);
add_action('delete_site_transient_update_themes', array($this,'isecure_shook_theme_deleted'));

add_filter('wp_login_failed', 'isecure_shook_wp_login_failed');
add_filter('widget_update_callback', 'isecure_shook_widget_update_callback');

	}
	
	
	/*
 * Insert record into wp_user_activity table
 * 
 * @param int $post_id Post ID.
 * @param string $post_title Post Title.
 * @param string $obj_type Object Type (Plugin, Post, User etc.).
 * @param int $current_user_id current user id.
 * @param string $current_user current user name.
 * @param string $user_role current user Role.
 * @param string $user_mail current user Email address.
 * @param datetime $modified_date current user's modified time.
 * @param string $ip current user's IP address.
 * @param string $action current user's activity name.
 * 
 */
 public function isecure_user_activity_add($post_id, $post_title, $obj_type, $current_user_id, $current_user, $user_role, $user_mail, $modified_date, $ip, $action) 
 {
        global $wpdb;
        $table_name = $wpdb->prefix . "isecure_user_activity";
        $insert_query = $wpdb->query("INSERT INTO $table_name (post_id,post_title,user_id, user_name, user_role, user_email, ip_address, modified_date, object_type, action) VALUES ('$post_id','$post_title','$current_user_id', '$current_user', '$user_role','$user_mail', '$ip', '$modified_date', '$obj_type', '$action')");
    }
	
public function isecure_get_activity_function($action, $obj_type, $post_id, $post_title) 
{
        $modified_date = current_time('mysql');
        $ip = $_SERVER['REMOTE_ADDR'];
        $current_user_id = get_current_user_id();
        $current_user1 = wp_get_current_user();
        $current_user = $current_user1->user_login;
        $user = new WP_User($current_user_id);
        global $wpdb;
        $table_name = $wpdb->prefix . "users";
        $get_emails = "SELECT * from $table_name where user_login='$current_user'";
        $mails = $wpdb->get_results($get_emails);
        foreach ($mails as $k => $v) {
            $user_mail = $v->user_email;
        }
        if (!empty($user->roles) && is_array($user->roles)) {
            foreach ($user->roles as $role)
                $user_role = $role;
        }
       $this->isecure_user_activity_add($post_id, $post_title, $obj_type, $current_user_id, $current_user, $user_role, $user_mail, $modified_date, $ip, $action);
    }
	/*
 * Add activity for the current user when login
 * 
 * @param string $user_login current user's login name.
 * 
 */


 public function isecure_shook_wp_login($user_login) 
 {
        global $wpdb;
        $table_name = $wpdb->prefix . "users";
        $action = "logged in";
        $obj_type = "user";
        $current_user = $user_login;
        $get_uid = "SELECT * from $table_name where user_login='$current_user'";
        $c_uid = $wpdb->get_results($get_uid);
        foreach ($c_uid as $k => $v) {
            $user_idis = $v->ID;
            $user_mail = $v->user_email;
        }
        $current_user_id = $user_idis;
        $user = new WP_User($current_user_id);
        if (!empty($user->roles) && is_array($user->roles)) {
            foreach ($user->roles as $role)
                $user_role = $role;
        }
        $post_id = $current_user_id;
        $post_title = $current_user;
        $modified_date = current_time('mysql');
        $ip = $_SERVER['REMOTE_ADDR'];
        $this->isecure_user_activity_add($post_id, $post_title, $obj_type, $current_user_id, $current_user, $user_role, $user_mail, $modified_date, $ip, $action);
    }


/*
 * Get activity for the current user when logout
 */


public function isecure_shook_wp_logout() 
{
        $action = "logged out";
        $obj_type = "user";
        $post_id = get_current_user_id();
        $user_nm = get_user_by('id', $post_id);
        $post_title = $user_nm->user_login;
        $this->isecure_get_activity_function($action, $obj_type, $post_id, $post_title);
    }

/*
 * Get activity for the delete user
 * 
 * @param int $user Post ID
 * 
 */


  public  function isecure_shook_delete_user($user) {
        $action = "delete user";
        $obj_type = "user";
        $post_id = $user;
        $user_nm = get_user_by('id', $post_id);
        $post_title = $user_nm->user_login;
        $this->isecure_get_activity_function($action, $obj_type, $post_id, $post_title);
    }


/*
 * Get activity for the registered user
 * 
 * @param int $user Post ID
 * 
 */


  public  function isecure_shook_user_register($user) {
        $action = "user register";
        $obj_type = "user";
        $post_id = $user;
        $user_nm = get_user_by('id', $post_id);
        $post_title = $user_nm->user_login;
        $this->isecure_get_activity_function($action, $obj_type, $post_id, $post_title);
    }


/*
 * Get activity for the user - update profile
 * 
 * @param int $user Post ID
 * 
 */


 public   function isecure_shook_profile_update($user) {
        $action = "profile update";
        $obj_type = "user";
        $post_id = $user;
        $user_nm = get_user_by('id', $post_id);
        $post_title = $user_nm->user_login;
        $this->isecure_get_activity_function($action, $obj_type, $post_id, $post_title);
    }


/*
 * Get activity for the user - add attach media file
 * 
 * @param int $attach Post ID
 * 
 */


  public  function isecure_shook_add_attachment($attach) {
        $action = "add attachment";
        $obj_type = "attachment";
        $post_id = $attach;
        $post_title = get_the_title($post_id);
        $this->isecure_get_activity_function($action, $obj_type, $post_id, $post_title);
    }


/*
 * Get activity for the user - edit attach media file
 * 
 * @param int $attach Post ID
 * 
 */


 public   function isecure_shook_edit_attachment($attach) {
        $post_id = $attach;
        $post_title = get_the_title($post_id);
        $action = "edit attachment";
        $obj_type = "attachment";
        $this->isecure_get_activity_function($action, $obj_type, $post_id, $post_title);
    }


/*
 * Get activity for the user - delete attach media file
 * 
 * @param int $attach Post ID
 * 
 */


  public  function isecure_shook_delete_attachment($attach) {
        $post_id = $attach;
        $post_title = get_the_title($post_id);
        $action = "delete attachment";
        $obj_type = "attachment";
        $this->isecure_get_activity_function($action, $obj_type, $post_id, $post_title);
    }


/*
 * Get activity for the user - Insert Comment
 * 
 * @param int $comment Comment ID
 * 
 */


  public  function isecure_shook_wp_insert_comment($comment) {
        $action = "insert comment";
        $obj_type = "comment";
        $comment_id = $comment;
        $com = get_comment($comment_id);
        $post_id = $com->comment_post_ID;
        $post_title = get_the_title($post_id);
        $this->isecure_get_activity_function($action, $obj_type, $post_id, $post_title);
    }


/*
 * Get activity for the user - Edit Comment
 * 
 * @param int $comment Comment ID
 * 
 */


 public   function isecure_shook_edit_comment($comment) {
        $action = "edit comment";
        $obj_type = "comment";
        $comment_id = $comment;
        $com = get_comment($comment_id);
        $post_id = $com->comment_post_ID;
        $post_title = get_the_title($post_id);
        $this->isecure_get_activity_function($action, $obj_type, $post_id, $post_title);
    }


/*
 * Get activity for the user - Trash Comment
 * 
 * @param int $comment Comment ID
 * 
 */


 public   function isecure_shook_trash_comment($comment) {
        $action = "trash comment";
        $obj_type = "comment";
        $comment_id = $comment;
        $com = get_comment($comment_id);
        $post_id = $com->comment_post_ID;
        $post_title = get_the_title($post_id);
        $this->isecure_get_activity_function($action, $obj_type, $post_id, $post_title);
    }


/*
 * Get activity for the user - Spam Comment
 * 
 * @param int $comment Comment ID
 * 
 */


 public   function isecure_shook_spam_comment($comment) {
        $action = "spam comment";
        $obj_type = "comment";
        $comment_id = $comment;
        $com = get_comment($comment_id);
        $post_id = $com->comment_post_ID;
        $post_title = get_the_title($post_id);
        $this->isecure_get_activity_function($action, $obj_type, $post_id, $post_title);
    }


/*
 * Get activity for the user - Unspam Comment
 * 
 * @param int $comment Comment ID
 * 
 */


 public   function isecure_shook_unspam_comment($comment) {
        $action = "unspam comment";
        $obj_type = "comment";
        $comment_id = $comment;
        $com = get_comment($comment_id);
        $post_id = $com->comment_post_ID;
        $post_title = get_the_title($post_id);
        $this->isecure_get_activity_function($action, $obj_type, $post_id, $post_title);
    }


/*
 * Get activity for the user - Delete Comment
 * 
 * @param int $comment Comment ID
 * 
 */


  public  function isecure_shook_delete_comment($comment) {
        $action = "delete comment";
        $obj_type = "comment";
        $comment_id = $comment;
        $com = get_comment($comment_id);
        $post_id = $com->comment_post_ID;
        $post_title = get_the_title($post_id);
        $this->isecure_get_activity_function($action, $obj_type, $post_id, $post_title);
    }


/*
 * Get activity for the user - Create Terms
 * 
 * @param int $term Post ID
 * @param string $taxonomy taxonomy name
 * 
 */


 public   function isecure_shook_created_term($term, $taxonomy) {
        $action = "created term";
        $obj_type = "term";
        if ('nav_menu' === $taxonomy)
            return;
        global $wpdb;
        $post_id = $term;
        $tab_nm = $wpdb->prefix . "terms";
        $get_term_name = "SELECT * from $tab_nm where term_id=$post_id";
        $terms_nm = $wpdb->get_results($get_term_name);
        foreach ($terms_nm as $k => $v) {
            $post_title = $v->name;
        }
        $this->isecure_get_activity_function($action, $obj_type, $post_id, $post_title);
    }


/*
 * Get activity for the user - Edit Terms
 * 
 * @param int $term Post ID
 * @param string $taxonomy taxonomy name
 * 
 */


 public   function isecure_shook_edited_term($term, $taxonomy) {
        $action = "edited term";
        $obj_type = "term";
        if ('nav_menu' === $taxonomy)
            return;
        global $wpdb;
        $post_id = $term;
        $tab_nm = $wpdb->prefix . "terms";
        $get_term_name = "SELECT * from $tab_nm where term_id=$post_id";
        $terms_nm = $wpdb->get_results($get_term_name);
        foreach ($terms_nm as $k => $v) {
            $post_title = $v->name;
        }
        $this->isecure_get_activity_function($action, $obj_type, $post_id, $post_title);
    }


/*
 * Get activity for the user - Delete Terms
 * 
 * @param int $term_id Post ID
 * @param string $taxonomy taxonomy name
 * @param string $deleted_term = null
 * 
 */


  public  function isecure_shook_delete_term($term_id, $taxonomy_name, $deleted_term = null) {
        if ('nav_menu' === $taxonomy_name)
            return;
        $term = $deleted_term;
        if ($term && !is_wp_error($term)) {
            global $wpdb;
            $action = 'delete term';
            $obj_type = 'Term';
           $this-> isecure_get_activity_function($action, $obj_type, $term_id, $term->name);
        }
    }


/*
 * Get activity for the user - Update navigation menu
 * 
 * @param int $menu Post ID
 * 
 */


  public  function isecure_shook_wp_update_nav_menu($menu) {
        $action = "update nav menu";
        $obj_type = "menu";
        $post_id = $menu;
        $menu_object = wp_get_nav_menu_object($post_id);
        $post_title = $menu_object->name;
        $this->isecure_get_activity_function($action, $obj_type, $post_id, $post_title);
    }


/*
 * Get activity for the user - Create navigation menu
 * 
 * @param int $menu Post ID
 * 
 */


 public   function isecure_shook_wp_create_nav_menu($menu) {
        $action = "create nav menu";
        $obj_type = "menu";
        $post_id = $menu;
        $menu_object = wp_get_nav_menu_object($post_id);
        $post_title = $menu_object->name;
        $this->isecure_get_activity_function($action, $obj_type, $post_id, $post_title);
    }


/*
 * Get activity for the user - Delete navigation menu
 * 
 * @param int $tt_id Post ID
 * @param string $deleted_term Post Title
 * 
 */


 public   function isecure_shook_delete_nav_menu($tt_id, $deleted_term) {
        $action = "delete nav menu";
        $obj_type = "menu";
        $post_id = $tt_id;
        $post_title = $deleted_term->name;
        $this->isecure_get_activity_function($action, $obj_type, $post_id, $post_title);
    }


/*
 * Get activity for the user - Switch Theme
 * 
 * @param string $theme Post Title
 * 
 */


  public  function isecure_shook_switch_theme($theme) {
        $action = "switch theme";
        $obj_type = "theme";
        $post_id = "";
        $post_title = $theme;
        $this->isecure_get_activity_function($action, $obj_type, $post_id, $post_title);
    }


/*
 * Get activity for the user - Update Theme
 * 
 */


 public   function shook_delete_site_transient_update_themes() {
        $action = "delete_site_transient_update_themes";
        $obj_type = "theme";
        $post_id = "";
        $post_title = $theme;
       $this-> isecure_get_activity_function($action, $obj_type, $post_id, $post_title);
    }


/*
 * Get activity for the user - Customize Theme
 * 
 */


 public   function isecure_shook_customize_save() {
        $action = "customize save";
        $obj_type = "theme";
        $post_id = "";
        $post_title = "Theme Customizer";
        $this->isecure_get_activity_function($action, $obj_type, $post_id, $post_title);
    }

/*
 * Get activity for the user - Activate Plugin
 * 
 * @param string $plugin Post Title
 * 
 */


 public   function isecure_shook_activated_plugin($plugin) {
        $action = "activated plugin";
        $obj_type = "plugin";
        $post_id = "";
        $post_title = $plugin;
       $this-> isecure_get_activity_function($action, $obj_type, $post_id, $post_title);
    }


/*
 * Get activity for the user - Activate Plugin
 * 
 * @param string $new_status new posts status
 * @param string $old_status old posts status
 * @param object $post posts
 * 
 */


  public  function isecure_shook_transition_post_status($new_status, $old_status, $post) 
  {
        $action = '';
        $obj_type = $post->post_type;
        $post_id = $post->ID;
        $post_title = $post->post_title;
        if ('auto-draft' === $old_status && ( 'auto-draft' !== $new_status && 'inherit' !== $new_status )) {
            $action = $obj_type . ' created';
        } elseif ('auto-draft' === $new_status || ( 'new' === $old_status && 'inherit' === $new_status )) {
            return;
        } elseif ('trash' === $new_status) {
            $action = $obj_type . ' deleted';
        } else {
            $action = $obj_type . ' updated';
        }
        $this->isecure_get_activity_function($action, $obj_type, $post_id, $post_title);
    }


/*
 * Get activity for the user - Deactivate Plugin
 * 
 * @param string $plugin Post Title
 * 
 */


  public  function isecure_shook_deactivated_plugin($plugin) {
        $action = "deactivated plugin";
        $obj_type = "plugin";
        $post_id = "";
        $post_title = $plugin;
       $this-> isecure_get_activity_function($action, $obj_type, $post_id, $post_title);
    }


/*
 * Get activity for the user - Core file updated successfully
 * 
 */


  public  function shook_core_updated_successfully() {
        $action = "core updated successfully";
        $obj_type = "update";
        $post_id = "";
        $post_title = $obj_type;
        $this->isecure_get_activity_function($action, $obj_type, $post_id, $post_title);
    }


/*
 * Get activity for the user - Export wordpress data
 * 
 */


 public   function isecure_shook_export_wp() {
        $action = "export wp";
        $obj_type = "export";
        $post_id = "";
        $post_title = $obj_type;
        $this->isecure_get_activity_function($action, $obj_type, $post_id, $post_title);
    }


/*
 * Get activity for the user - Upgrader process complete
 * 
 */


 public   function shook_upgrader_process_complete() {
        $action = "upgrade process complete";
        $obj_type = "upgrade";
        $post_id = "";
        $post_title = $obj_type;
        $this->isecure_get_activity_function($action, $obj_type, $post_id, $post_title);
    }


/*
 * Get activity for the user - Delelte theme
 * 
 */


 public   function isecure_shook_theme_deleted() {
        $backtrace_history = debug_backtrace();
        $delete_theme_call = null;
        foreach ($backtrace_history as $call) {
            if (isset($call['function']) && 'delete_theme' === $call['function']) {
                $delete_theme_call = $call;
                break;
            }
        }
        if (empty($delete_theme_call))
            return;
        $name = $delete_theme_call['args'][0];
        $action = 'Theme deleted';
        $obj_type = 'Theme';
        $post_title = $name;
        $post_id = "";
        $this->isecure_get_activity_function($action, $obj_type, $post_id, $post_title);
    }




/*
 * Get activity for the user - Login fail
 * 
 * @param string $user username
 */


  public  function isecure_shook_wp_login_failed($user) {
        $action = "login failed";
        $obj_type = "user";
        $post_id = "";
        $post_title = $user;
        $current_user = $user;
        $modified_date = current_time('mysql');
        $ip = $_SERVER['REMOTE_ADDR'];
        $user = get_user_by('login', $current_user);
        $current_user_id = $user->ID;
        if (!empty($user->roles) && is_array($user->roles)) {
            foreach ($user->roles as $role)
                $user_role = $role;
        }
        global $wpdb;
        $table_name = $wpdb->prefix . "users";
        $get_emails = "SELECT * from $table_name where user_login='$current_user'";
        $mails = $wpdb->get_results($get_emails);
        foreach ($mails as $k => $v) {
            $user_mail = $v->user_email;
        }
        $this-> isecure_user_activity_add($post_id, $post_title, $obj_type, $current_user_id, $current_user, $user_role, $user_mail, $modified_date, $ip, $action);
    }


/*
 * Get activity for the user - Widget update
 * 
 * @param string $widget widget data
 */


  public  function isecure_shook_widget_update_callback($widget) {
        $action = "widget updated";
        $obj_type = "widget";
        $post_id = "";
        $post_title = "Sidebar Widget";
        $this->isecure_get_activity_function($action, $obj_type, $post_id, $post_title);
        return $widget;
    }

}
?>