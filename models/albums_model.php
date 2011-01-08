<?php
/**
*
*/
class AlbumsModel {

  /**
  *
  */
  function getAlbums() 
  {
    global $wpdb;
    
    return $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}picasso_albums`", OBJECT); 
  }

  /**
  *
  */  
  function getAlbumById($id)
  {
    global $wpdb;
    
    return $wpdb->get_var($wpdb->prepare("SELECT `name` FROM `{$wpdb->prefix}picasso_albums` WHERE `id` = %d", $id));
  }
  
  /**
  * @return false on error or the id of the inserted row on success
  * @param $args an array of unescaped "raw" data to be inserted
  */
  function addAlbum($args)
  {
    global $wpdb;
    /**
    * http://codex.wordpress.org/Function_Reference/wpdb_Class#INSERT_rows
    * Both $data columns and $data values should be "raw" (neither should be SQL escaped).
    */
    
    /**
    * @TODO: Check to see if NAME already exists - return false
    * @TODO: Use WP_Error
    */
    if($args['name'] == "") {
      return false;
      return new WP_Error('picasso-error', __('You must supply an album name.'));
    }
      
    if(strlen($args['name']) > 255) {
      return false;
      return new WP_Error('picasso-error', __('The name must be less than 255 characters'));
    }
      
    $data = array('name' => $args['name']);
    $wpdb->insert("{$wpdb->prefix}picasso_albums", (array) $data);  
    
    return $wpdb->insert_id;
  }
  
  /**
  *
  */  
  function deleteAlbum($id)
  {
    global $wpdb;
    $id = (int) $wpdb->escape($id);
    $sql = "DELETE FROM `{$wpdb->prefix}picasso_albums` WHERE `id` = $id";  
    $wpdb->query($sql);
  }
 
  /**
  *
  */ 
  function getNumAlbums()
  {
    global $wpdb;
    return $wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->prefix}picasso_albums`");
  }
}

?>