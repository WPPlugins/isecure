<?php
class ISecure_EmailActivity_Log
{
	
	public function __construct( ) 
	{
		add_filter('wp_mail', array($this,'log_email'));
	}
	
	
	

 public function log_email( $mail_info ) 
 {
		global $wpdb;

		$attachment_present = ( count( $mail_info['attachments'] ) > 0 ) ? 'true' : 'false';

		// return filtered array
		$mail_info  = apply_filters( 'isecure_mail_log', $mail_info );
		$table_name = $wpdb->prefix . "isecure_email_activity";

		if ( isset( $mail_info['message'] ) ) {
			$message = $mail_info['message'];
		} else {
			
			if ( isset( $mail_info['html'] ) ) {
				$message = $mail_info['html'];
			} else {
				$message = '';
			}
		}

		// Log into the database
		$wpdb->insert( $table_name, array(
				'to_email'    => is_array( $mail_info['to'] ) ? implode( ',', $mail_info['to'] ) : $mail_info['to'],
				'subject'     => $mail_info['subject'],
				'message'     => $message,
				'headers'     => is_array( $mail_info['headers'] ) ? implode( "\n", $mail_info['headers'] ) : $mail_info['headers'],
				'attachments' => $attachment_present,
				'sent_date'   => current_time( 'mysql' ),
			) );

		return $mail_info;
	}

	
}
?>