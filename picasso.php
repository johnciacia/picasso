<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/*
Plugin Name: Picasso
Plugin URI: http://www.johnciacia.com/picasso/
Description: Gallery
Version: 0.1
Author: John Ciacia
Author URI: http://www.johnciacia.com

Copyright 2011  John Ciacia  (email : john [at] johnciacia [dot] com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

define('PICASSO_UPLOAD_DIR', WP_CONTENT_DIR . '/uploads/picasso');
define('PICASSO_UPLOAD_URL',  WP_CONTENT_URL . '/uploads/picasso');

require_once('models/albums_model.php');
require_once('models/pictures_model.php');
require_once('helpers/file_helper.php');
require_once('helpers/image_helper.php');
require_once('helpers/error_helper.php');

$picasso = new Picasso();

/**
*
*/
add_action('admin_menu', array($picasso , 'admin_menu'));
/**
*
*/
add_action('init', array($picasso, 'init'));
/**
*
*/
add_action('admin_init', array($picasso, 'admin_init'));
/**
*
*/
add_action('load-toplevel_page_picasso', 
  array(&$picasso, 'on_load_toplevel_page_picasso'));
/**
*
*/  
add_action('load-admin_page_picasso-edit-album', 
  array(&$picasso, 'on_load_admin_page_picasso_edit_album'));
/**
*
*/  
add_filter('screen_layout_columns', 
  array(&$picasso, 'set_columns'), 10, 2);  
/**
*
*/  
register_activation_hook(__FILE__, array(&$picasso , 'install'));

/**
*
*/
add_action('admin_post_picasso_save_album', 
  array(&$picasso, 'saveAlbumAction'));
/**
*
*/  
add_action('admin_action_picasso-delete-album', 
  array(&$picasso, 'deleteAlbumAction'));
/**
*
*/    
add_action('admin_action_picasso-delete-picture', 
  array(&$picasso, 'deletePictureAction'));
/**
*
*/    
add_action('admin_action_picasso-set-album-cover', 
  array(&$picasso, 'setAlbumCoverAction'));
/**
*
*/
add_action('admin_post_picasso-upload-picture', 
  array(&$picasso, 'uploadPictureAction'));
/**
*
*/    
add_action('admin_post_nopriv_picasso-upload-picture', 
  array(&$picasso, 'uploadPictureAction'));
/**
*
*/  
add_action('wp_footer', array(&$picasso, 'footerAction'));
/**
*
*/  
add_shortcode('picasso', array($picasso , 'createGallery'));

  
class Picasso {

  var $albumsModel;
  var $picturesModel;
  var $fileHelper;
  var $imageHelper;
  var $errorHelper;
  var $incldueJS;

  /**
  * Create necessary models
  */
  function Picasso() 
  {
  	$this->includeJS = false;
    $this->albumsModel = new AlbumsModel();
    $this->picturesModel = new PicturesModel();
    $this->fileHelper = new FileHelper();
    $this->imageHelper = new ImageHelper();
    $this->errorHelper = ErrorHelper::getInstance();
    
  }
  
  /**
  * Load necessary javascript libraries
  */
  function init() 
  {
    //Load scripts
    wp_register_script('picasso_script_1', 
      WP_PLUGIN_URL . '/picasso/fancybox/jquery.mousewheel-3.0.4.pack.js');
    wp_register_script('picasso_script_2', 
      WP_PLUGIN_URL . '/picasso/fancybox/jquery.fancybox-1.3.4.pack.js');

    
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    
    //Load styles
    wp_register_style('picasso_style_1',
      WP_PLUGIN_URL . '/picasso/style.css');
    wp_register_style('picasso_style_2',
      WP_PLUGIN_URL . '/picasso/fancybox/jquery.fancybox-1.3.4.css');
      
    wp_enqueue_style('picasso_style_1');
    wp_enqueue_style('picasso_style_2');
    
  }
  
  /**
  * Load necessary javascript libraries for admin interface
  */  
  function admin_init()
  {
    wp_enqueue_script('swfupload-all');
    wp_enqueue_script('wp-lists');
    wp_enqueue_script('common');
    wp_enqueue_script('postbox');
  }

  /**
  *
  */  
  function set_columns($columns, $screen) {
    //if ($screen == $this->pagehook) {
    //    $columns[$this->pagehook] = 2;
    //}
    
    $columns['toplevel_page_picasso'] = 2;
    $columns['admin_page_picasso-edit-album'] = 2;
    return $columns;
  }
	
  /**
  * Add admin menu links
  */
  function admin_menu() 
  {

    add_menu_page('Picasso', 'Picasso', 'publish_pages', 
      'picasso', array(&$this , 'dashboardPage'));
      
    add_submenu_page('picasso', 'Settings', 'Settings', 
      'manage_options', 'picasso-settings', array(&$this, 'settingsPage'));

     add_submenu_page(null, null, 'Edit Album', 
      'publish_pages', 'picasso-edit-album', array(&$this, 'editAlbumPage'));
      
  }
  
  /**
  * Add widgets to the dashboard page
  */
  function on_load_toplevel_page_picasso() 
  {
  
    add_meta_box('picasso-show-albums', 'Albums', array(&$this, 'showAlbumsWidget'), 
      'toplevel_page_picasso', 'side', 'core');

    add_meta_box('picasso-overview', 'Overview', array(&$this, 'overviewWidget'), 
      'toplevel_page_picasso', 'side', 'core');
      
    add_meta_box('picasso-add-album', 'Add Album', array(&$this, 'addAlbumWidget'), 
      'toplevel_page_picasso', 'normal', 'core');

    add_meta_box('picasso-credits', 'Credits', array(&$this, 'creditsWidget'), 
      'toplevel_page_picasso', 'normal', 'core');
      
  }
  
  /**
  * Add widgets to the edit-album page
  */
  function on_load_admin_page_picasso_edit_album() 
  {
  
    add_meta_box('picasso-edit-album', 'Edit Album', array(&$this, 'editAlbumWidget'), 
      'admin_page_picasso-edit-album', 'normal', 'core');
			
    add_meta_box('picasso-upload-picture', 'Upload Pictures', array(&$this, 'uploadPictureWidget'),
      'admin_page_picasso-edit-album', 'normal', 'core'); 

    add_meta_box('picasso-edit-pictures', 'Edit Pictures', array(&$this, 'editPicturesWidget'), 
      'admin_page_picasso-edit-album', 'normal', 'core'); 

    add_meta_box('picasso-album-information', 'Album', array(&$this, 'albumInformationWidget'), 
      'admin_page_picasso-edit-album', 'side', 'core'); 

  }  

  /**
  * Create necessary tables and add necessary options
  */
  function install() 
  {

    global $wpdb;

    $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}picasso_albums` (
      `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    	`name` VARCHAR( 255 ) NOT NULL ,
  		`cid` INT NOT NULL DEFAULT '0' ,
    	`uid` INT NOT NULL DEFAULT  '0'
      ) ENGINE = INNODB;";
    $result = $wpdb->query($sql);

    $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}picasso_pictures` (
      `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
      `aid` INT NOT NULL ,
      `filename` VARCHAR( 255 ) NOT NULL ,
      `description` MEDIUMTEXT NOT NULL ,
      INDEX (  `aid` )
      ) ENGINE = INNODB;";
    $result = $wpdb->query($sql);

    add_option('PICASSO_DBVERSION', '1.0');
    add_option('PICASSO_ERROR', '');

  }




  /**
  * Show dashboard page
  */  
  function dashboardPage() 
  {

    global $screen_layout_columns;
    $data = array();
    $pagehook = "toplevel_page_picasso";
    require_once('template.php');

  }

  /**
  * Show settings page
  */	
  function settingsPage() 
  {

    //load settings page view

  }

  /**
  * Show album specified by the album id $_GET['id']
  */  
  function editAlbumPage()
  {

    global $screen_layout_columns;

    if((integer)$_GET['id'] != $_GET['id']) {
      die('Invalid album id');
    }

    $album = $this->albumsModel->getAlbumById((int)$_GET['id']);
    if($album == "") {
      die("That album does not exist");
    }

    $data = array('album' => $album);
    $pagehook = "admin_page_picasso-edit-album";
    require_once('template.php');

  }




  /**
  *
  */  
  function editAlbumWidget()
  {
		
    $album = $this->albumsModel->getAlbumById($_GET['id']);
    require_once('widgets/edit_album.php');
  }

  /**
  *
  */  
  function editPicturesWidget()
  {

    $pictures = $this->picturesModel->getPictures($_GET['id']);
    $album = md5($this->albumsModel->getAlbumById($_GET['id']));
    require_once('widgets/edit_pictures.php');

  }
	
	function albumInformationWidget()
	{
		$cover = $this->albumsModel->getAlbumCoverById($_GET['id']);
		print_r($cover);
		require_once('widgets/album_information.php');
	}

  /**
  *
  */  
  function uploadPictureWidget($data)
  {
    extract($data);
    require_once('widgets/upload_picture.php');
  }

  /**
  *
  */  
  function overviewWidget()
  {
    $albums = $this->albumsModel->getNumAlbums();
    $pictures = $this->picturesModel->getNumPictures();
    require_once('widgets/overview.php');
  }

  /**
  *
  */  
  function creditsWidget()
  {
    require_once('widgets/credits.php');
  }

  /**
  *
  */  
  function showAlbumsWidget() 
  {
    $albums = $this->albumsModel->getAlbums();
    require_once('widgets/show_albums.php');
  }

  /**
  *
  */  
  function addAlbumWidget()
  {
    require_once('widgets/add_album.php');
  }


  /**
  * $_POST['album'], $_FILES['name'], $_POST['aid']
  */  
  function uploadPictureAction()
  {
    $file = $this->fileHelper->upload(md5($_POST['album']));

    //@TODO - Is it more efficient to check if errors != 1 or the file === false?
    //Upload error checking
    if(ErrorHelper::getInstance()->getErrorCount() > 0) {
      $perrors = &ErrorHelper::getInstance()->getErrors();
      //@TODO: maybe there is a better way to show this??? meaning fewer new lines???
      while(!empty($perrors)){
        echo "<div id=\"message\" class=\"error\"><p>".
          array_pop($perrors) ."</p></div>";//neatly position the error
      }
      return;
    }
    
    $this->imageHelper->createThumbnail($file, md5($_POST['album']));
    
    
    $data = array(
      'filename' => $file,
      'aid' => $_POST['aid']
    );
    
    $picture = $this->picturesModel->addPicture($data);
    if($picture === false) {
      die("The picture was not inserted into the database");
    }
    
    //wp_redirect($_SERVER['HTTP_REFERER']);
  }
  
  /**
  * $_GET['id']
  */
  function deletePictureAction()
  {
    if (!current_user_can('publish_pages'))
      wp_die(__('Begone'));
      
    if($_GET['id'] != (integer)$_GET['id']) 
    	wp_die(__('Begone'));
	  	
    $album = $this->picturesModel->getAlbumById((integer)$_GET['id']);
    $album = $this->albumsModel->getAlbumById($album);
    $album = md5($album);
    
    $filename = $this->picturesModel->getFilenameById((integer)$_GET['id']);
		$file = PICASSO_UPLOAD_DIR . "/$album/$filename";
		unlink($file);
    
		$info = pathinfo($filename);
		$t = $info['filename'] . "_thumb." . $info['extension'];
		
		$file = PICASSO_UPLOAD_DIR . "/$album/$t";
		unlink($file);
    
	  $this->picturesModel->deletePicture((integer)$_GET['id']);
      
    wp_redirect($_SERVER['HTTP_REFERER']);
  }

  /**
  * $_POST['name']
  */  
  function saveAlbumAction() 
  {
    if (!current_user_can('publish_pages'))
      wp_die(__('Begone'));
      
    //check_admin_referer('picasso-general');

    $data = array('name' => $_POST['name']);
    
    //@TODO - more elegant error handling
    $album_id = $this->albumsModel->addAlbum($data);
    if ($album_id === false){
        wp_redirect($_SERVER['HTTP_REFERER']);
        exit;
    }
    
    if($this->fileHelper->createAlbum($_POST['name']) != true){
      //album directory wasn't created so remove the album
      if($this->albumsModel->deleteAlbum($album_id) === false){
        ErrorHelper::getInstance()->setError("Failure: clean incomplete album data in database.");
      }
    }
    
    wp_redirect($_SERVER['HTTP_REFERER']);
		
  }
  
  /**
  * Delete the album specified by $_GET['album']
  */  
  function deleteAlbumAction() 
  {
  
    if (!current_user_can('publish_pages'))
      wp_die(__('Begone'));
      
    $this->albumsModel->deleteAlbum($_GET['album']);	
    wp_redirect($_SERVER['HTTP_REFERER']);
    
  }
  
  /**
  * Set the cover for the album with id $_GET['aid'] 
  * to the picture with id $_GET['pid']
  */
  function setAlbumCoverAction()
  {
  	
    if (!current_user_can('publish_pages'))
      wp_die(__('Begone'));
      
    if($_GET['aid'] != (integer)$_GET['aid']) 
    	wp_die(__('Begone'));

    if($_GET['pid'] != (integer)$_GET['pid']) 
    	wp_die(__('Begone'));
        	
  	$this->albumsModel->setAlbumCover($_GET['aid'], $_GET['pid']);
    wp_redirect($_SERVER['HTTP_REFERER']);
	  	
  }
  
  /**
  * Credit to Scribu @ http://scribu.net/wordpress/optimal-script-loading.html
  */
  function footerAction()
  {
  	if($this->includeJS == true) {
	    wp_print_scripts('picasso_script_1');
	    wp_print_scripts('picasso_script_2');
  	}
  }
  
  
  
  
  
  
  /**
  * [picasso] Display all albums in gallery format
  * [picasso aid="1,2,3"] Display albums 1, 2, and 3 in gallery format
	* [picasso aid="1"] Display album 1
	*/
  function createGallery($atts)
  {
  	$this->includeJS = true;
  	
    extract(shortcode_atts(array('id' => null), $atts));
    
    if(isset($_GET['id'])) {
    	if($_GET['id'] == (integer)$_GET['id'])
    		$id = (integer)$_GET['id'];
    }

		$albums = explode(",", $id);
		
    if(count($albums) > 1 && $id != null) {
    	echo "Not supported. Sorry. :'(";
    }
    
    else if(count($albums) == 1 && $id != null) {
    	$album = $this->albumsModel->getAlbumById($id);
    	$album = md5($album);
    	$pictures = $this->picturesModel->getPictures($id);
    	require_once('views/album.php');
    }
    
    else {
    	$albums = $this->albumsModel->getAlbumCovers();
    	require_once('views/gallery.php');
    }
    
    
  }
}

?>