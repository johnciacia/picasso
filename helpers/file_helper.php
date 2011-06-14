<?php

//This class is highly experimental and should not be relied upon.
class FileHelper {

  //@TODO - Better error handling
  function upload($album)
  {
  
    $types = array('image/gif', 'image/jpeg', 'image/png');
    $directory = WP_CONTENT_DIR . "/picasso/$album/";
    //@TODO - filename should be an md5 or sanatized
    $file = wp_unique_filename($directory, $_FILES['file']['name']);
    
    //Is the MIME type valid?
    //wp_check_filetype()
    if(!in_array($_FILES['file']['type'], $types)) {
      die("MIME type invalid");
      return false;
    }
     
    //Is the file size larger than the max size allowed by PHP?
    //@TODO - this is hackish. there has to be a better way to get the file size
    $max_size = (ini_get('upload_max_filesize'));
    $mul = substr($max_size, -1);
    $mul = ($mul == 'M' ? 1048576 : ($mul == 'K' ? 1024 : ($mul == 'G' ? 1073741824 : 1)));
    $max_size = $max_size * $mul;
    
    if($_FILES['file']['size'] >= $max_size) {
      die("File size too big");
      return false;
    }
    
    //Where there any errors?
    if ($_FILES['file']['error'] > 0) {
      die("There were errors uploading");
      return false;
    }
    
    //Does the directory exist?
    if($directory == false) {
      die("The directory exists");
      return false;
    }
      
    //Can we write to the directory?
    if(!is_writable($directory)) {
      die("The directory is not writable");
      return false;
    }
    
    //Does the file already exist?
    //This should never happen since $file is unique
    if(file_exists($directory . $file)) {
      echo "The file already exists";
      return false;
    }
    
    //Was the file uploaded succesfully?
    if(move_uploaded_file($_FILES['file']['tmp_name'], $directory . $file) == false) {
      die("The file failed to move");
      return false;
    }
    
    //The file was uploaded with no problems.
    return $file;
  
  }
  
  function createAlbum($album)
  {
    $name = md5($album);
    $directory = WP_CONTENT_DIR . "/picasso/$album/";
    
    //@TODO - if $directory already exists, should we upload pictures there anyway? or return an error?
    //if(file_exists($directory))
    //  return false;
    
    //@TODO - recursive directory creation does not work with mac/bsd
    return wp_mkdir_p($directory);
  
  }

}




?>