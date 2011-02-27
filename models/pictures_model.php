<?php
/**
*
*/
class PicturesModel {

  /**
  *
  */
  function addPicture($args)
  {
  
    global $wpdb;
    
    $data = array(
      'aid' => $args['aid'],
      'filename' => $args['filename'],
			'uid' => $args['uid'],
      'description' => ''
    );
    
    $wpdb->insert("{$wpdb->prefix}picasso_pictures", (array) $data); 

  }
  
  /**
  * @param $aid Album ID
  */  
  function getPictures($aid)
  {
  
    global $wpdb;
    
    $sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}picasso_pictures WHERE `aid` = %d ORDER BY `id` DESC", $aid);
    return $wpdb->get_results($sql);
    
  }
  
  function getAlbumById($id)
  {
    global $wpdb;
    
    $sql = "SELECT `aid` FROM `{$wpdb->prefix}picasso_pictures` WHERE `id` = %d";
    return $wpdb->get_var($wpdb->prepare($sql, $id));	  	 	
  }
  
  function getFilenameById($id)
  {
    global $wpdb;
    
    $sql = "SELECT `filename` FROM `{$wpdb->prefix}picasso_pictures` WHERE `id` = %d";
    return $wpdb->get_var($wpdb->prepare($sql, $id));	 
  }
  
  
  
  /**
  *
  */
  function getNumPictures()
  {
  
    global $wpdb;
    return $wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->prefix}picasso_pictures`");
    
  }
  
  /**
  *
  */
  function deletePicture($id)
  {
    global $wpdb;
    $id = (int) $wpdb->escape($id);
    $sql = "DELETE FROM `{$wpdb->prefix}picasso_pictures` WHERE `id` = $id";  
    $wpdb->query($sql);
  }
  

}

?>