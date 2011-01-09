<?php

/**
* Image helper class
*/
class ImageHelper 
{
	
	/**
	* @TODO - Error checking
	*/
  function createThumbnail($filename, $album, $w = 100, $h = 100)
  {
  	
  	$image = PICASSO_UPLOAD_DIR . "/$album/$filename";
  	
    list($width, $height, $type) = getimagesize($image);
    $mime = image_type_to_mime_type($type);
		
    $thumb = imagecreatetruecolor($w, $h);
    switch($mime) {
    	case 'image/jpeg':    
    		$temp = imagecreatefromjpeg($image);
    		break;
    		
  		case 'image/gif':
    		$temp = imagecreatefromgif($image);
  			break;
  			
			case 'image/png':
    		$temp = imagecreatefrompng($image);
  			break;
  			
    	default:
    		return false;
    }
    
    
    $info = pathinfo($image);
    $t = $info['filename'] . "_thumb." . $info['extension'];
    
    $image = PICASSO_UPLOAD_DIR . "/$album/$t";
    
    
    switch($mime) {
    	case 'image/jpeg':
    		imagejpeg($thumb, $image, 100);
    		break;
    		
  		case 'image/gif':
    		imagegif($thumb, $image);
  			break;
  			
			case 'image/png':
    		imagepng($thumb, $image, 100);
  			break;
  			
    	default:
    		return false;
    }
    
    return true;
    		
  }
	
}


?>