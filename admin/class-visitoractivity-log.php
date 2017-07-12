<?php
class ISecure_VisitorActivity_Log
{
	
	public function __construct( ) 
	{
		add_filter('wp_head', array($this,'log_visit'));
	}
	
	
	

 public function log_visit() 
 {
		global $wpdb;
		$table_name = $wpdb->prefix . "isecure_visitor_activity";

		//referer data
		$referer = $this->visit_get_referer();
		$referer = explode(',',$referer);
		$referer_doamin = $referer['0'];
		$referer_url = $referer['1'];
		
		//URL Data
		$url_id_array = $this->visit_geturl_id();
		$url_id_array = explode(',',$url_id_array);
		$url_id = $url_id_array['0'];
		$url_term = $url_id_array['1'];
		
		/* //device data
		$browser = new Browser_wpls();
		$platform = $browser->getPlatform();
		$browser = $browser->getBrowser();
		$screensize = wpls_get_screensize();
		
		// geo data
		$ip = $_SERVER['REMOTE_ADDR'];
		$geoplugin = new geoPlugin();
		$geoplugin->locate();
		$city = $geoplugin->city;
		$region = $geoplugin->region;
		$countryName = $geoplugin->countryCode; */

		// Log into the database
		$wpdb->insert( $table_name, array(
		
					'session_id'=> session_id(),
					'visit_date'=> $this->visit_get_date(),
					'visit_time'=> $this->visit_get_time(),
					'visit_endtime'=> $this->visit_get_datetime(),
					'ip'=>$_SERVER['REMOTE_ADDR'],
					'region'=> null,
					'countryName'=> null,
					'userid'=> $this->visit_getuser(),
					'url_id'=> $url_id,
					'url_term'=> $url_term,
					'referer_doamin'=> $referer_doamin,
					'referer_url'=> $referer_url,
					'screensize'=> null,
			) );

	}

	public function visit_get_date()
		{	
			$gmt_offset = get_option('gmt_offset');
			$visit_datetime = date('Y-m-d', strtotime('+'.$gmt_offset.' hour'));
			
			return $visit_datetime;
		
		}
		
	public function visit_get_time()
		{	
			$gmt_offset = get_option('gmt_offset');
			$visit_time = date('H:i:s', strtotime('+'.$gmt_offset.' hour'));
			
			return $visit_time;
		
		}

	
		
	public 	function visit_get_datetime()
		{	
			$gmt_offset = get_option('gmt_offset');
			$visit_datetime = date('Y-m-d H:i:s', strtotime('+'.$gmt_offset.' hour'));
			
			return $visit_datetime;
		
		}	

	public function visit_getuser()
	{
		if ( is_user_logged_in() ) 
			{
				$userid = get_current_user_id();
			}
		else
			{
				$userid = "guest";
			}
			
		return $userid;
	}		
	
	public function visit_get_referer()
	{	
		if(isset($_SERVER["HTTP_REFERER"]))
			{
				$referer = $_SERVER["HTTP_REFERER"];
				$pieces = parse_url($referer);
				$domain = isset($pieces['host']) ? $pieces['host'] : '';
					if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs))
						{
							$referer = $regs['domain'];
						}
					else
						{
							$referer = "none";
						}
				
				$referurl = $_SERVER["HTTP_REFERER"];
			
			}
		else
			{
				$referer = "direct";
				$referurl = "none";
			}
		return $referer.",".$referurl;
	}

	public function visit_geturl_id()
	{	
		global $post;
		
		
		
		if(is_home()) // working fine with http://
			{
				$url_term = 'home';
				
				$home_url = get_bloginfo( 'url' );
			
				$url_id = $home_url;
			}
		elseif(is_singular()) //working fine
			{
				$url_term = get_post_type();
				$url_id = get_the_ID();
			}
		elseif( is_tag()) // http added
			{
				$url_term = 'tag';
				$url_id = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			}			
			
		elseif(is_archive()) // http added
			{
				$url_term = 'archive';
				$url_id = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			}
		elseif(is_search())
			{
				$url_term = 'search';
				$url_id = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			}			
			
			
		elseif( is_404())
			{
				$url_term = 'err_404';
				$url_id = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			}			
		elseif( is_admin())
			{
				$url_term = 'dashboard';
				$url_id = admin_url();
			}	

		else
			{
				$url_term = 'unknown';
				$url_id = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			}
					
	
		return $url_id.",".$url_term;
		
	}


		
}
?>