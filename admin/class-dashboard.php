<?php
class ISecure_Dashboard
{
	
	public function __construct( ) 
	{
		add_action( 'isecure_dashboard',  array($this,'add_all' ));
	}
	
	public function add_all()
		{
			?>
			
			  <div class="isecure_panel blue">
				  <a href="<?php echo admin_url( 'edit-comments.php' ); ?>"> <span> 
				 <?php
						echo  wp_count_comments()->total_comments;
				 ?>
				 </span>Comments</a>
				  </div>
				  
				  <div class="isecure_panel green">
  <a href="<?php echo admin_url( 'edit.php?post_type=page' ); ?>"> <span> 
 <?php

$count_pages = wp_count_posts('page');
$published_posts = $count_pages->publish;
echo $published_posts;
 ?>
 </span>Pages</a>
  </div>
 
  <div class="isecure_panel yellow">
  <a href="<?php echo admin_url( 'edit.php' ); ?>"> <span> 
 <?php

$count_posts = wp_count_posts();
$published_posts = $count_posts->publish;
echo $published_posts;
 ?>
 </span>Posts</a>
  </div>
  
 <div class="isecure_panel yellow">
  <a href="<?php echo admin_url( 'admin.php?page=isecure_user_activity' ); ?>"> <span> 
 <?php
 
 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-useractivity-show.php';
		$abc=new ISecure_UserActivity_Show;
		$abc->total_count();
 ?>
 </span>User Activities</a>
  </div>
  
  <div class="isecure_panel blue">
     <a href="<?php echo admin_url( 'admin.php?page=isecure_email_activity' ); ?>"> <span> 
 <?php
 
 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-emailactivity-show.php';
		$abc=new ISecure_EmailActivity_Show;
		$abc->total_count();
 ?>
 </span>Email Log</a>
  </div>

<div class="isecure_panel red">
   <a href="<?php echo admin_url( 'users.php' ); ?>"> <span> 
    <?php
	$result = count_users();
echo $result['total_users'];
	?>
	</span> Users</a>
  </div>
			
			<?php
		}
	

}
?>