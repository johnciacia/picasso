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
      'description' => ''
    );
    
    $wpdb->insert("{$wpdb->prefix}picasso_pictures", (array) $data); 

  }
  
  /**
  *
  */  
  function getPictures($aid)
  {
  
    global $wpdb;
    
    $sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}picasso_pictures WHERE `aid` = %d ORDER BY `id` DESC", $aid);
    return $wpdb->get_results($sql);
    
  }
  
  /**
  *
  */
  function getNumPictures()
  {
  
    global $wpdb;
    return $wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->prefix}picasso_pictures`");
    
  }

}

?>