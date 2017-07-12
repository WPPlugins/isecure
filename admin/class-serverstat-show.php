<?php
class ISecure_Serverstat_Show
{
	
	public function __construct( ) 
	{
			add_action( 'isecure_dashboard',  array($this,'add_to_dashboard' ));	
	}
	
	public function add_to_dashboard()
		{
			?>
			<div class="isecure_panel extra">
			<?php $this->init_extra(); 	?>
		  </div>
		  <div class="isecure_panel extra">
			<?php $this->init(); 	?>
		  </div>
			<?php
		}
		
	public function init()
	{
		?>
		
		<div class="wrap about-wrap">
		<ul>
						<li><strong><?php _e('Server IP', 'wp-server-stats'); ?></strong> : <span><?php echo $this->check_server_ip(); ?></span></li>	
						<li><strong><?php _e('Server Location', 'wp-server-stats'); ?></strong> : <span><?php echo $this->check_server_location(); ?></span></li>
						<li><strong><?php _e('Server Hostname', 'wp-server-stats'); ?></strong> : <span><?php echo gethostname(); ?></span></li>
												
					
					
					</ul>
		</div>
		
		
		<?php
	}
	
	public function init_extra()
	{
		$memory_usage_MB = function_exists('memory_get_usage') ? round(memory_get_usage() / 1024 / 1024, 2) : 0;
					
		$memory_usage_pos = round ($memory_usage_MB / (int) $this->check_memory_limit_cal() * 100, 0);
		
		
		?>
		<div class="wrap about-wrap">
		<ul>
				
		<li><strong><?php _e('PHP Version', 'wp-server-stats'); ?></strong> : <span><?php echo PHP_VERSION; ?>&nbsp;/&nbsp;<?php echo (PHP_INT_SIZE * 8) . __('Bit OS'); ?></span></li>
						<li><strong><?php _e('Memory Limit', 'isecure'); ?></strong> : <span><?php echo $this->check_limit(); ?></span></li>
						<li><strong><?php _e('Memory Usages', 'isecure'); ?></strong> : <span><?php echo $memory_usage_MB; ?> (<?php echo $memory_usage_pos."%"; ?>)</span></li>
					
					
					</ul>
					</div>
		
		<?php
		
					
	}
	 function check_memory_limit_cal() {
        	return (int) ini_get('memory_limit');
        }
		
	function check_server_ip() {
			return gethostbyname( gethostname() );
		}
	function check_server_location() {
			//$ip = $_REQUEST['REMOTE_ADDR'];
			$ip = gethostbyname( gethostname() );
			$query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
			$server_location = wp_cache_get( 'server_location' );
			if( false === $server_location && $query && $query['status'] == 'success' ) {
			  $server_location = $query['city'] . ', ' . $query['country'];
			  wp_cache_set( 'server_location', $server_location );
			  return $server_location;
			} else {
			  return $query['message'];
			}
		}
		
				
		
		
		 function check_limit() {
            $memory_limit = ini_get('memory_limit');
			if (preg_match('/^(\d+)(.)$/', $memory_limit, $matches)) {
			    if ($matches[2] == 'G') {
			    	$memory_limit = $matches[1] . ' ' . 'GB'; // nnnG -> nnn GB
			    } else if ($matches[2] == 'M') {
			        $memory_limit = $matches[1] . ' ' . 'MB'; // nnnM -> nnn MB
			    } else if ($matches[2] == 'K') {
			        $memory_limit = $matches[1] . ' ' . 'KB'; // nnnK -> nnn KB
			    } else if ($matches[2] == 'T') {
			    	$memory_limit = $matches[1] . ' ' . 'TB'; // nnnT -> nnn TB
			    } else if ($matches[2] == 'P') {
			    	$memory_limit = $matches[1] . ' ' . 'PB'; // nnnP -> nnn PB
			    }
			}
			return $memory_limit;
        }
		
}

?>