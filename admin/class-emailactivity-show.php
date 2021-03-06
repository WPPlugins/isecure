<?php
class ISecure_EmailActivity_Show 
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
		 $table_name = $wpdb->prefix . "isecure_email_activity";
		 $total_items_query = "SELECT count(*) FROM $table_name";
        $total_items = $wpdb->get_var($total_items_query, 0, 0);
		echo $total_items;
	}
	
	public function init()
	{
		 global $wpdb;
        $paged = $total_pages = 1;
        $srno = 0;
        $recordperpage = 10;
        $table_name = $wpdb->prefix . "isecure_email_activity";
        $where = "where 1=1";
        $to_email = $subject = $sent_date = "";
        
		if (isset($_GET['paged']))
            $paged = $this->ual_test_input($_GET['paged']);
        $offset = ($paged - 1) * $recordperpage;
       
        
		if (isset($_GET['to_email']) && $_GET['to_email'] != "" && $_GET['to_email'] != "0") {
            $to_email = $this->ual_test_input($_GET['to_email']);
            $where.=" and to_email='$to_email'";
        }
        
		
        
        // query for display all the user activity data start
        $select_query = "SELECT * from $table_name $where ORDER BY sent_date desc LIMIT $offset,$recordperpage";
       // echo $select_query;
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
 <?php _e('Any mail sent through wordpress is been logged. <br> Sometimes spammers sends mail using your wordpress but administrator is unaware of it. <br>So keep eye on it before your site gets blacklisted.' ); ?>
 </div>
            <h2><?php _e('Email Activities', 'wp_user_log'); ?></h2>
            <form method="get"  class="frm-user-activity">
                <div class="tablenav top">
                    <!-- Search Box start -->
                    <div class="sol-search-div">
                        <p class="search-box">
                            <label class="screen-reader-text" for="search-input"><?php _e('Search', 'wp_user_log'); ?> :</label>
                            <input type="hidden" name="page" value="isecure_email_activity">
						</p>
                    </div>
                    <!-- Search Box end -->
                    <!-- Drop down menu for Role Start -->
                    <div class="alignleft actions">
                        <select name="to_email">
                            <option selected value="0"><?php _e('All Receiver', 'wp_user_log'); ?></option>
                            <?php
                            $to_query = "SELECT distinct to_email from $table_name";
							
                            $get_roles = $wpdb->get_results($to_query);
                            foreach ($get_roles as $role) 
							{
                                $user_role = $role->to_email;
                                if ($user_role != "") {
                                    ?>
                                    <option value="<?php echo $user_role; ?>" ><?php echo ucfirst($user_role); ?></option>
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
                        <?php $items = sprintf(_n('%s item', '%s items', $total_items, 'wp_user_log'), $total_items); ?>
                        <span class="displaying-num"><?php echo $items; ?></span>
                        <div class="tablenav-pages" <?php
                        if ((int) $total_pages <= 1) {
                            echo 'style="display:none;"';
                        }
                        ?>>
                            <span class="pagination-links">
                                <a class="first-page <?php if ($paged == '1') echo 'disabled'; ?>" href="<?php echo '?page=isecure_email_activity&paged=1&userrole=' . $us_role . '&username=' . $us_name . '&type=' . $ob_type . '&txtsearch=' . $searchtxt; ?>" title="Go to the first page">&laquo;</a>
                                <a class="prev-page <?php if ($paged == '1') echo 'disabled'; ?>" href="<?php echo '?page=isecure_email_activity&paged=' . $prev_page . '&userrole=' . $us_role . '&username=' . $us_name . '&type=' . $ob_type . '&txtsearch=' . $searchtxt; ?>" title="Go to the previous page">&lsaquo;</a>
                                <span class="paging-input">
                                    <input class="current-page" type="text" size="1" value="<?php echo $paged; ?>" name="paged" title="Current page"> of
                                    <span class="total-pages"><?php echo $total_pages; ?></span>
                                </span>
                                <a class="next-page <?php if ($paged == $total_pages) echo 'disabled'; ?>" href="<?php echo '?page=isecure_email_activity&paged=' . $next_page . '&userrole=' . $us_role . '&username=' . $us_name . '&type=' . $ob_type . '&txtsearch=' . $searchtxt; ?>" title="Go to the next page">&rsaquo;</a>
                                <a class="last-page <?php if ($paged == $total_pages) echo 'disabled'; ?>" href="<?php echo '?page=isecure_email_activity&paged=' . $total_pages . '&userrole=' . $us_role . '&username=' . $us_name . '&type=' . $ob_type . '&txtsearch=' . $searchtxt; ?>" title="Go to the last page">&raquo;</a>
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
                            <th scope="col"><?php _e('Sent Date', 'isecure'); ?></th>
                            <th scope="col" class="sol-col-width"><?php _e('Receiver Email', 'isecure'); ?></th>
							 <th scope="col"><?php _e('Subject', 'isecure'); ?></th>
                          </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th scope="col"><?php _e('No.', 'isecure'); ?></th>
                            <th scope="col"><?php _e('Sent Date', 'isecure'); ?></th>
                            <th scope="col" class="sol-col-width"><?php _e('Receiver Email', 'isecure'); ?></th>
							 <th scope="col"><?php _e('Subject', 'isecure'); ?></th>
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
                                    <td><?php echo $data->sent_date; ?></td>
                                    <td><?php echo ucfirst($data->to_email); ?></td>
                                    <td><?php echo ucfirst($data->subject); ?></td>
                                    
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
                                <a class="first-page <?php if ($paged == '1') echo 'disabled'; ?>" href="<?php echo '?page=isecure_email_activity&paged=1&userrole=' . $us_role . '&username=' . $us_name . '&type=' . $ob_type; ?>" title="Go to the first page">&laquo;</a>
                                <a class="prev-page <?php if ($paged == '1') echo 'disabled'; ?>" href="<?php echo '?page=isecure_email_activity&paged=' . $prev_page . '&userrole=' . $us_role . '&username=' . $us_name . '&type=' . $ob_type; ?>" title="Go to the previous page">&lsaquo;</a>
                                <span class="paging-input">
                                    <input class="current-page" type="text" size="1" value="<?php echo $paged; ?>" name="paged" title="Current page"> of
                                    <span class="total-pages"><?php echo $total_pages; ?></span>
                                </span>
                                <a class="next-page <?php if ($paged == $total_pages) echo 'disabled'; ?>" href="<?php echo '?page=isecure_email_activity&paged=' . $next_page . '&userrole=' . $us_role . '&username=' . $us_name . '&type=' . $ob_type; ?>" title="Go to the next page">&rsaquo;</a>
                                <a class="last-page <?php if ($paged == $total_pages) echo 'disabled'; ?>" href="<?php echo '?page=isecure_email_activity&paged=' . $total_pages . '&userrole=' . $us_role . '&username=' . $us_name . '&type=' . $ob_type; ?>" title="Go to the last page">&raquo;</a>
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