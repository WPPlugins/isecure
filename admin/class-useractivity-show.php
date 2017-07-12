<?php
class ISecure_UserActivity_Show 
{
	
	public function __construct( ) 
	{
		//add_action('init', array($this,'ual_filter_data'));
	}
	
	 public function ual_test_input($data) 
	 {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
	
	public function total_count()
	{
		global $wpdb;
		 $table_name = $wpdb->prefix . "isecure_user_activity";
		 $total_items_query = "SELECT count(*) FROM $table_name";
        $total_items = $wpdb->get_var($total_items_query, 0, 0);
		echo $total_items;
	}

	public function profile($userid)
	{
		if(is_numeric($userid))
							{
								$user_info = get_userdata($userid);
								echo "<span title='".$user_info->display_name."' class='avatar'>".get_avatar( $userid, 32 )."<i title='User'></i></span>";
							}
						else
							{
								if($userid=='guest')
									{
									echo "<span title='Guest' class='avatar'>".get_avatar( 0, 32 )."</span>";
									}
								else
									{
										$userid = get_userdatabylogin($userid );
										$userid = $userid->ID;
										$user_info = get_userdata($userid);
										echo "<span title='".$user_info->display_name."' class='avatar'>".get_avatar( $userid, 32 )."<i title='Username'></i></span>";
									}
								

							}	
	}
	
	public function init()
	{
		 global $wpdb;
        $paged = $total_pages = 1;
        $srno = 0;
        $recordperpage = 10;
        $table_name = $wpdb->prefix . "isecure_user_activity";
        $where = "where 1=1";
        $u_role = $u_name = $o_type = "";
        if (isset($_GET['paged']))
            $paged = $this->ual_test_input($_GET['paged']);
        $offset = ($paged - 1) * $recordperpage;
        $us_role = $us_name = $ob_type = $searchtxt = "";
        
		if (isset($_GET['userrole']) && $_GET['userrole'] != "" && $_GET['userrole'] != "0") {
            $us_role = $this->ual_test_input($_GET['userrole']);
            $where.=" and user_role='$us_role'";
        }
        if (isset($_GET['username']) && $_GET['username'] != "" && $_GET['username'] != "0") {
            $us_name = $this->ual_test_input($_GET['username']);
            $where.=" and user_name='$us_name'";
        }
        if (isset($_GET['type']) && $_GET['type'] != "" && $_GET['type'] != "0") {
            $ob_type = $this->ual_test_input($_GET['type']);
            $where.=" and object_type='$ob_type'";
        }
		
		if (isset($_GET['ac']) && $_GET['ac'] != "" && $_GET['ac'] != "0") {
            $ob_type = $this->ual_test_input($_GET['ac']);
            $where.=" and action='$ob_type'";
        }
  
        
        // query for display all the user activity data start
        $select_query = "SELECT * from $table_name $where ORDER BY modified_date desc LIMIT $offset,$recordperpage";
        //echo $select_query;
		$get_data = $wpdb->get_results($select_query);
        $total_items_query = "SELECT count(*) FROM $table_name $where";
        $total_items = $wpdb->get_var($total_items_query, 0, 0);
        
        // query for display all the user activity data end
        // for pagination
        $total_pages = ceil($total_items / $recordperpage);
        $next_page = (int) $paged + 1;
        if ($next_page > $total_pages)
            $next_page = $total_pages;
        $prev_page = (int) $paged - 1;
        if ($prev_page < 1)
            $prev_page = 1;
        ?>
        <div class="wrap">
            <img src="<?php echo plugins_url( 'isecure.png', __FILE__ ); ?>"><br>
 <div class="about-text">
 <?php _e('Any registred member activities is being recorded.<br>This way you will know who and when certain task is performed. <br>Now noone can say [ I HAVE NOT DONE IT ] :)' ); ?>
 </div>
			<h2><?php _e('User Activities', 'wp_user_log'); ?></h2>
			
            <form method="get"  class="frm-user-activity">
                <div class="tablenav top">
                    <!-- Search Box start -->
                    <div class="sol-search-div">
                        <p class="search-box">
                            <label class="screen-reader-text" for="search-input"><?php _e('Search', 'wp_user_log'); ?> :</label>
                            <input type="hidden" name="page" value="isecure_user_activity">
						</p>
                    </div>
                    <!-- Search Box end -->
                    <!-- Drop down menu for Role Start -->
                    <div class="alignleft actions">
                        <select name="userrole">
                            <option selected value="0"><?php _e('All Role', 'wp_user_log'); ?></option>
                            <?php
                            $role_query = "SELECT distinct user_role from $table_name";
                            $get_roles = $wpdb->get_results($role_query);
                            foreach ($get_roles as $role) {
                                $user_role = $role->user_role;
                                if ($user_role != "") {
                                    ?>
                                    <option value="<?php echo $user_role; ?>" <?php echo selected($us_role, $user_role); ?>><?php echo ucfirst($user_role); ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Drop down menu for Role end -->
                    <!-- Drop down menu for User Start -->
                    <div class="alignleft actions">
                        <select name="username" class="sol-dropdown">
                            <option selected value="0"><?php _e('All User', 'wp_user_log'); ?></option>
                            <?php
                            $username_query = "SELECT distinct user_name from $table_name";
                            $get_username = $wpdb->get_results($username_query);
                            foreach ($get_username as $username) {
                                $user_name = $username->user_name;
                                if ($user_name != "") {
                                    ?>
                                    <option value="<?php echo $user_name; ?>" <?php echo selected($us_name, $user_name); ?>><?php echo ucfirst($user_name); ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Drop down menu for User end -->
                    <!-- Drop down menu for Post type Start -->
                    <div class="alignleft actions">
                        <select name="type">
                            <option selected value="0"><?php _e('All Type', 'wp_user_log'); ?></option>
                            <?php
                            $object_type_query = "SELECT distinct object_type from $table_name";
                            $get_type = $wpdb->get_results($object_type_query);
                            foreach ($get_type as $type) {
                                $object_type = $type->object_type;
                                if ($object_type != "") {
                                    ?>
                                    <option value="<?php echo $object_type; ?>" <?php echo selected($ob_type, $object_type); ?>><?php echo ucfirst($object_type); ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
					<?php
					function selc($param)
					{
						if (isset($_GET['ac']))
						if($_GET['ac'] == $param)
						return 'selected="selected"';
						
					}
					
					?>
					      <div class="alignleft actions">
                        <select name="ac">
                            <option value="0" <?php echo selc('0'); ?>><?php _e('All Action', 'wp_user_log'); ?></option>
							<option value="logged in" <?php echo selc('logged in'); ?>><?php _e('Logged In', 'isecure'); ?></option>
							<option value="logged out" <?php echo selc('logged out'); ?>><?php _e('Logged Out', 'isecure'); ?></option>
							<option value="login failed" <?php echo selc('login failed'); ?>><?php _e('login failed', 'isecure'); ?></option>
							
							<option value="delete user" <?php echo selc('delete user'); ?>><?php _e('delete user', 'isecure'); ?></option>
							<option value="user register" <?php echo selc('user register'); ?>><?php _e('user register', 'isecure'); ?></option>
							<option value="profile update" <?php echo selc('profile update'); ?>><?php _e('profile update', 'isecure'); ?></option>
							<option value="add attachment" <?php echo selc('add attachment'); ?>><?php _e('add attachment', 'isecure'); ?></option>
							<option value="edit attachment" <?php echo selc('edit attachment'); ?>><?php _e('edit attachment', 'isecure'); ?></option>
							<option value="delete attachment" <?php echo selc('delete attachment'); ?>><?php _e('delete attachment', 'isecure'); ?></option>
							<option value="insert comment" <?php echo selc('insert comment'); ?>><?php _e('insert comment', 'isecure'); ?></option>
							<option value="edit comment" <?php echo selc('edit comment'); ?>><?php _e('edit comment', 'isecure'); ?></option>
							<option value="trash comment" <?php echo selc('trash comment'); ?>><?php _e('trash comment', 'isecure'); ?></option>
							<option value="spam comment" <?php echo selc('spam comment'); ?>><?php _e('spam comment', 'isecure'); ?></option>
							<option value="unspam comment" <?php echo selc('unspam comment'); ?>><?php _e('unspam comment', 'isecure'); ?></option>
							<option value="delete comment" <?php echo selc('delete comment'); ?>><?php _e('delete comment', 'isecure'); ?></option>
							<option value="created term" <?php echo selc('created term'); ?>><?php _e('created term', 'isecure'); ?></option>
							<option value="edited term" <?php echo selc('edited term'); ?>><?php _e('edited term', 'isecure'); ?></option>
							<option value="delete term" <?php echo selc('delete term'); ?>><?php _e('delete term', 'isecure'); ?></option>
							<option value="update nav menu" <?php echo selc('update nav menu'); ?>><?php _e('update nav menu', 'isecure'); ?></option>
							<option value="create nav menu" <?php echo selc('create nav menu'); ?>><?php _e('create nav menu', 'isecure'); ?></option>
							<option value="delete nav menu" <?php echo selc('delete nav menu'); ?>><?php _e('delete nav menu', 'isecure'); ?></option>
							
							
							<option value="switch theme" <?php echo selc('switch theme'); ?>><?php _e('switch theme', 'isecure'); ?></option>
							<option value="delete_site_transient_update_themes" <?php echo selc('delete_site_transient_update_themes'); ?>><?php _e('delete_site_transient_update_themes', 'isecure'); ?></option>
							<option value="customize save" <?php echo selc('customize save'); ?>><?php _e('customize save', 'isecure'); ?></option>
							<option value="Theme deleted" <?php echo selc('Theme deleted'); ?>><?php _e('Theme deleted', 'isecure'); ?></option>
							
							
							<option value="activated plugin" <?php echo selc('activated plugin'); ?>><?php _e('activated plugin', 'isecure'); ?></option>
							<option value="deactivated plugin" <?php echo selc('deactivated plugin'); ?>><?php _e('deactivated plugin', 'isecure'); ?></option>
							
							<option value="core updated successfully" <?php echo selc('core updated successfully'); ?>><?php _e('core updated successfully', 'isecure'); ?></option>
							<option value="export wp" <?php echo selc('export wp'); ?>><?php _e('export wp', 'isecure'); ?></option>
							<option value="upgrade process complete" <?php echo selc('upgrade process complete'); ?>><?php _e('upgrade process complete', 'isecure'); ?></option>
							
							<option value="widget updated" <?php echo selc('widget updated'); ?>><?php _e('widget updated', 'isecure'); ?></option>
							
							
							<?php
                            $object_type_query = "SELECT distinct object_type from $table_name";
                            $get_type = $wpdb->get_results($object_type_query);
                            foreach ($get_type as $type) {
                                $object_type = $type->object_type;
                                if ($object_type != "" && $object_type != "comment" && $object_type != "user") {
                                    ?>
									<option value="<?php echo $object_type.' created'; ?>" <?php echo selc($object_type.' created'); ?>><?php _e($object_type.' created', 'isecure'); ?></option>
                                    <option value="<?php echo $object_type.' updated'; ?>" <?php echo selc($object_type.' updated'); ?>><?php _e($object_type.' updated', 'isecure'); ?></option>
                                     <option value="<?php echo $object_type.' deleted'; ?>" <?php echo selc($object_type.' deleted'); ?>><?php _e($object_type.' deleted', 'isecure'); ?></option>
									  
									  
									<?php
                                }
                            }
                            ?>
							
							
							
                        </select>
                    </div>
                    <!-- Drop down menu for Post type end -->
                    <input class="button-secondary action sol-filter-btn" type="submit" value="Filter" name="btn_filter">
                    <!-- Top pagination start -->
                    <div class="tablenav-pages">
                        <?php $items = sprintf(_n('%s item', '%s items', $total_items, 'isecure'), $total_items); ?>
                        <span class="displaying-num"><?php echo $items; ?></span>
                        <div class="tablenav-pages" <?php
                        if ((int) $total_pages <= 1) {
                            echo 'style="display:none;"';
                        }
                        ?>>
                            <span class="pagination-links">
                                <a class="first-page <?php if ($paged == '1') echo 'disabled'; ?>" href="<?php echo '?page=isecure_user_activity&paged=1&userrole=' . $us_role . '&username=' . $us_name . '&type=' . $ob_type . '&txtsearch=' . $searchtxt; ?>" title="Go to the first page">&laquo;</a>
                                <a class="prev-page <?php if ($paged == '1') echo 'disabled'; ?>" href="<?php echo '?page=isecure_user_activity&paged=' . $prev_page . '&userrole=' . $us_role . '&username=' . $us_name . '&type=' . $ob_type . '&txtsearch=' . $searchtxt; ?>" title="Go to the previous page">&lsaquo;</a>
                                <span class="paging-input">
                                    <input class="current-page" type="text" size="1" value="<?php echo $paged; ?>" name="paged" title="Current page"> of
                                    <span class="total-pages"><?php echo $total_pages; ?></span>
                                </span>
                                <a class="next-page <?php if ($paged == $total_pages) echo 'disabled'; ?>" href="<?php echo '?page=isecure_user_activity&paged=' . $next_page . '&userrole=' . $us_role . '&username=' . $us_name . '&type=' . $ob_type . '&txtsearch=' . $searchtxt; ?>" title="Go to the next page">&rsaquo;</a>
                                <a class="last-page <?php if ($paged == $total_pages) echo 'disabled'; ?>" href="<?php echo '?page=isecure_user_activity&paged=' . $total_pages . '&userrole=' . $us_role . '&username=' . $us_name . '&type=' . $ob_type . '&txtsearch=' . $searchtxt; ?>" title="Go to the last page">&raquo;</a>
                            </span>
                        </div>
                    </div>
                    <!-- Top pagination end -->
                </div>
                <!-- Table for display user action start -->
                <table class="widefat post fixed striped" cellspacing="0">
                    <thead>
                        <tr>
                            <th scope="col"><?php _e('No.', 'isecure'); ?></th>
                            <th scope="col"><?php _e('Date', 'isecure'); ?></th>
                            <th scope="col"><?php _e('Role', 'isecure'); ?></th>
                            <th scope="col"><?php _e('User', 'isecure'); ?></th>
                            <th scope="col" class="sol-col-width"><?php _e('Email address', 'isecure'); ?></th>
                            <th scope="col"><?php _e('IP', 'isecure'); ?></th>
                            <th scope="col"><?php _e('Type', 'isecure'); ?></th>
                            <th scope="col"><?php _e('Action', 'isecure'); ?></th>
                            <th scope="col" class="sol-col-width"><?php _e('Description', 'isecure'); ?></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th scope="col"><?php _e('No.', 'isecure'); ?></th>
                            <th scope="col"><?php _e('Date', 'isecure'); ?></th>
                            <th scope="col"><?php _e('Role', 'isecure'); ?></th>
                            <th scope="col"><?php _e('User', 'isecure'); ?></th>
                            <th scope="col" class="sol-col-width"><?php _e('Email address', 'isecure'); ?></th>
                            <th scope="col"><?php _e('IP', 'isecure'); ?></th>
                            <th scope="col"><?php _e('Type', 'isecure'); ?></th>
                            <th scope="col"><?php _e('Action', 'isecure'); ?></th>
                            <th scope="col" class="sol-col-width"><?php _e('Description', 'isecure'); ?></th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        if ($get_data) {
                            $srno = 1 + $offset;
                            foreach ($get_data as $data) {
                                ?>
                                <tr>
                                    <td><?php
                                        echo $srno;
                                        $srno++;
                                        ?></td>
                                    <td><?php echo $data->modified_date; ?></td>
                                    <td><?php echo ucfirst($data->user_role); ?></td>
                                    <td><?php echo $this->profile($data->user_id); ?><br><?php echo ucfirst($data->user_name); ?></td>
                                    <td><?php echo $data->user_email; ?></td>
                                    <td><?php echo $data->ip_address; ?></td>
                                    <td><?php echo ucfirst($data->object_type); ?></td>
                                    <td><?php echo ucfirst($data->action); ?></td>
                                    <?php if (($data->object_type == "post" || $data->object_type == "page") && $data->action != 'post deleted' && $data->action != 'page deleted') { ?>
                                        <td><a href="<?php echo get_permalink($data->post_id); ?>"><?php echo ucfirst($data->post_title); ?></a></td>
                                        <?php
                                    } else {
                                        ?><td><?php echo ucfirst($data->post_title); ?></td>
                                            <?php
                                        }
                                        ?>
                                </tr>
                                <?php
                            }
                        } else {
                            echo '<tr class="no-items">';
                            echo '<td class="colspanchange" colspan="4">' . __('No record found.', 'isecure') . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
                <!-- Table for display user action end -->
                <!-- Bottom pagination start -->
                <div class="tablenav top">
                    <div class="tablenav-pages">
                        <span class="displaying-num"><?php echo $items; ?></span>
                        <div class="tablenav-pages" <?php
                        if ((int) $total_pages <= 1) {
                            echo 'style="display:none;"';
                        }
                        ?>>
                            <span class="pagination-links">
                                <a class="first-page <?php if ($paged == '1') echo 'disabled'; ?>" href="<?php echo '?page=isecure_user_activity&paged=1&userrole=' . $us_role . '&username=' . $us_name . '&type=' . $ob_type; ?>" title="Go to the first page">&laquo;</a>
                                <a class="prev-page <?php if ($paged == '1') echo 'disabled'; ?>" href="<?php echo '?page=isecure_user_activity&paged=' . $prev_page . '&userrole=' . $us_role . '&username=' . $us_name . '&type=' . $ob_type; ?>" title="Go to the previous page">&lsaquo;</a>
                                <span class="paging-input">
                                    <input class="current-page" type="text" size="1" value="<?php echo $paged; ?>" name="paged" title="Current page"> of
                                    <span class="total-pages"><?php echo $total_pages; ?></span>
                                </span>
                                <a class="next-page <?php if ($paged == $total_pages) echo 'disabled'; ?>" href="<?php echo '?page=isecure_user_activity&paged=' . $next_page . '&userrole=' . $us_role . '&username=' . $us_name . '&type=' . $ob_type; ?>" title="Go to the next page">&rsaquo;</a>
                                <a class="last-page <?php if ($paged == $total_pages) echo 'disabled'; ?>" href="<?php echo '?page=isecure_user_activity&paged=' . $total_pages . '&userrole=' . $us_role . '&username=' . $us_name . '&type=' . $ob_type; ?>" title="Go to the last page">&raquo;</a>
                            </span>
                        </div>
                    </div>
                </div>
                <!-- Bottom pagination end -->
            </form>

        </div>
        <?php
	}
	

	
}
?>