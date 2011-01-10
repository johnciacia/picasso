<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/*
Plugin Name: Picasso
Plugin URI: http://www.johnciacia.com/picasso/
Description: Gallery
Version: 0.1
Author: John Ciacia
Author URI: http://www.johnciacia.com

Copyright 2009  John Ciacia  (email : john [at] johnciacia [dot] com)

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
add_action('admin_post_picasso-upload-picture', 
  array(&$picasso, 'uploadPictureAction'));
  
add_action('admin_post_nopriv_picasso-upload-picture', 
  array(&$picasso, 'uploadPictureAction'));
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

  /**
  * Create necessary models
  */
  function Picasso() 
  {
  
    $this->albumsModel = new AlbumsModel();
    $this->picturesModel = new PicturesModel();
    $this->fileHelper = new FileHelper();
    $this->imageHelper = new ImageHelper();
    $this->errorHelper = ErrorHelper::getInstance();
    
  }
  
  /**
  * Load necessary javascript libraries
  * @TODO - Only load scripts on the necessary pages
  */
  function init() 
  {
    //Load scripts
    wp_register_script('picasso_script_1', 
      WP_PLUGIN_URL . '/picasso/fancybox/jquery.mousewheel-3.0.4.pack.js');
    wp_register_script('picasso_script_2', 
      WP_PLUGIN_URL . '/picasso/fancybox/jquery.fancybox-1.3.4.pack.js');

    
    wp_enqueue_script('common');
    wp_enqueue_script('wp-lists');
    wp_enqueue_script('postbox');
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('swfupload-all');
    wp_enqueue_script('picasso_script_1');
    wp_enqueue_script('picasso_script_2');
    
    //Load styles
    wp_register_style('picasso_style_1',
      WP_PLUGIN_URL . '/picasso/style.css');
    wp_register_style('picasso_style_2',
      WP_PLUGIN_URL . '/picasso/fancybox/jquery.fancybox-1.3.4.css');
      
    wp_enqueue_style('picasso_style_1');
    wp_enqueue_style('picasso_style_2');
    
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
      
    add_meta_box('picasso-edit-pictures', 'Edit Pictures', array(&$this, 'editPicturesWidget'), 
      'admin_page_picasso-edit-album', 'normal', 'core'); 

    add_meta_box('picasso-upload-picture', 'Edit Pictures', array(&$this, 'uploadPictureWidget'),
      'admin_page_picasso-edit-album', 'normal', 'core'); 
      
  }  
  
  /**
  * Create necessary tables and add necessary options
  */
  function install() 
  {
  
    global $wpdb;
    
    $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}picasso_albums` (
      `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
      `name` VARCHAR( 255 ) NOT NULL
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
    
  }
  
  
  
  
  /**
  *
  */  
  function dashboardPage() 
  {
  
    global $screen_layout_columns;
    $data = array();
    $pagehook = "toplevel_page_picasso";
    require_once('template.php');
    
  }

  /**
  *
  */	
  function settingsPage() 
  {
  
    //load settings page view
    
  }

  /**
  *
  */  
  function editAlbumPage()
  {
  
    global $screen_layout_columns;
    
    if((int)$_GET['id'] != $_GET['id']) {
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
  	//$error = $this->errorHelper::getInstance();
  	//$this->errorHelper->setError('This is a test');
  	
  	echo $this->errorHelper->getErrorCount();
  	die();
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
  *
  */  
  function uploadPictureAction()
  {

    $file = $this->fileHelper->upload(md5($_POST['album']));
    if($file === false) {
      die("The picture failed to upload");
    }
    
    
    //@TODO - Create thumbnail
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
  *
  */  
  function saveAlbumAction() 
  {
    if (!current_user_can('publish_pages'))
      wp_die(__('Begone'));
    
    $this->errorHelper->setError('This is a test');
      
    //check_admin_referer('picasso-general');

    $data = array('name' => $_POST['name']);
    
    //@TODO - more elegant error handling
    $this->albumsModel->addAlbum($data);
    
    if($this->fileHelper->createAlbum($_POST['name']) != true)
      die("Cannot create the album.");
    
    wp_redirect($_SERVER['HTTP_REFERER']);
		
  }
  
  /**
  *
  */  
  function deleteAlbumAction() 
  {
  
    if (!current_user_can('publish_pages'))
      wp_die(__('Begone'));
      
    $this->albumsModel->deleteAlbum($_GET['album']);	
    wp_redirect($_SERVER['HTTP_REFERER']);
    
  }
  
  
  
  
  
  
  function createGallery($atts)
  {
    extract(shortcode_atts(array('id' => 0), $atts));
    $album = $this->albumsModel->getAlbumById($id);
    $album = md5($album);
    $pictures = $this->picturesModel->getPictures($id);
    
    foreach($pictures as $picture) {    
    	$info = pathinfo($picture->filename);
    	$t = $info['filename'] . "_thumb." . $info['extension'];
    	
      echo "<a rel='example_group' href='" . PICASSO_UPLOAD_URL . "/$album/$picture->filename'>";
      echo "<img src='" . PICASSO_UPLOAD_URL . "/$album/$t' /> ";
      echo "</a>";
    }
    

$str = <<<EOD
    	<script type="text/javascript">
		jQuery(document).ready(function() {


			jQuery("a[rel=example_group]").fancybox({
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'titlePosition' 	: 'over',
				'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
					return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
				}
			});


		});
	</script>
EOD;

  echo $str;
  
  }
}

?>