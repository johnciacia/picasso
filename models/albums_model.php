<?php
/**
*
*/
class AlbumsModel {

  /**
  * Get all albums in the database
  */
  function getAlbums() 
  {
    global $wpdb;
    
    $sql = "SELECT * FROM `{$wpdb->prefix}picasso_albums`";
    return $wpdb->get_results($sql, OBJECT); 
  }

  /**
  * @param $id ID of an album
  * @return a single variable from the database or NULL if no results are found
  * Get the album specified by $id
  */  
  function getAlbumById($id)
  {
    global $wpdb;
    
    $sql = "SELECT `name` FROM `{$wpdb->prefix}picasso_albums` WHERE `id` = %d";
    return $wpdb->get_var($wpdb->prepare($sql, $id));
  }
  
  /**
  * Get the filenames of the album cover pictures
  */
  function getAlbumCovers()
	{
    global $wpdb;
    
  	$sql = "SELECT * FROM `{$wpdb->prefix}picasso_albums` 
  		JOIN `{$wpdb->prefix}picasso_pictures` 
  		WHERE {$wpdb->prefix}picasso_albums.cid = {$wpdb->prefix}picasso_pictures.id";
	  	
		return $wpdb->get_results($sql);
	}
	  
	  
	function getAlbumCoverById($id)
	{
    global $wpdb;
    
  	$sql = "SELECT * FROM `{$wpdb->prefix}picasso_albums` 
  		JOIN `{$wpdb->prefix}picasso_pictures` 
  		WHERE {$wpdb->prefix}picasso_albums.cid = {$id}";
			echo $sql;
	  	
		return $wpdb->get_results($sql);			
			
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
  * @param $id the album id to be deleted
  * @return (mixed) FALSE on error or the number of rows affected on success
  * NOTE: both 0 and FALSE can be returned so check using === not ==
  */  
  function deleteAlbum($id)
  {
    global $wpdb;
    $id = (integer) $wpdb->escape($id);
    $sql = "DELETE FROM `{$wpdb->prefix}picasso_albums` WHERE `id` = $id";  
    return $wpdb->query($sql);
  }
  
  /**
  * @param $aid Album ID
  * @param $pid Picture ID
  * Set the cover id (cid) of the album ($aid) to the picture id ($pid)
  */
  function setAlbumCover($aid, $pid)
  {
	  	global $wpdb;
	  	$wpdb->update("{$wpdb->prefix}picasso_albums", 
	  		array('cid' => $pid), array('id' => $aid));
  }
 
  /**
  * @return (integer) The number of albums
  * Get the number of albums
  */ 
  function getNumAlbums()
  {
    global $wpdb;
    return $wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->prefix}picasso_albums`");
  }
}

?>