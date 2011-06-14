<?php 
if (!defined( 'WP_UNINSTALL_PLUGIN')) {
	exit();
}
	
function uninstall() {	
	global $wpdb;
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}picasso_albums");
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}picasso_pictures");	
  delete_option('PICASSO_DBVERSION');
  delete_option('PICASSO_ERROR');
}

uninstall();

?>