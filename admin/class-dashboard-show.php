<?php
class ISecure_Dashboard_Show
{
	public function __construct( ) 
	{
		
	}
	
		
	public function init()
	{
	?>
		
				
		

		<div class="wrap about-wrap">
 <img src="<?php echo plugins_url( 'isecure.png', __FILE__ ); ?>"><br>
 <div class="about-text">
 <?php _e('We monitor your server/site from unusual activities.' ); ?>
 </div>

 <?php
 //Add your information to wathdog dashboard
 do_action('isecure_dashboard');
 
 ?>
 
 
</div>
		
		<?php
	}
	
}

?>