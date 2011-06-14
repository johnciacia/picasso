<?php

/**
* Image helper class
*/
class ImageHelper 
{
	
  function createThumbnail($image, $toWidth=100, $toHeight=100)
  {
    list($width, $height, $type) = getimagesize($image);
    $mime = image_type_to_mime_type($type);
    
    $xscale=$width/$toWidth;
    $yscale=$height/$toHeight;
    
    // Recalculate new size with default ratio
    if ($yscale>$xscale){
        $new_width = round($width * (1/$yscale));
        $new_height = round($height * (1/$yscale));
    }
    else {
        $new_width = round($width * (1/$xscale));
        $new_height = round($height * (1/$xscale));
    }

    $imageResized = imagecreatetruecolor($new_width, $new_height);
    switch($mime) {
    	case 'image/jpeg':
    		$imageTmp = imagecreatefromjpeg ($image);
    		break;
    		
  		case 'image/gif':
    		$imageTmp = imagecreatefromgif ($image);
  			break;
  			
			case 'image/png':
    		$imageTmp = imagecreatefrompng ($image);
  			break;
  			
    	default:
    		return false;
    }
    
    imagecopyresampled($imageResized, $imageTmp, 0, 0, 0, 0, $new_width, $new_height, $width, $height);	
    
    $img = WP_CONTENT_DIR . "/uploads/picasso/$album/{$image}_t";
    
    switch($mime) {
    	case 'image/jpeg':
    		imagejpeg($imageResized, $img, 100);
    		break;
    		
  		case 'image/gif':
    		imagegif($imageResized, $img);
  			break;
  			
			case 'image/png':
    		imagepng($imageResized, $img, 100);
  			break;
  			
    	default:
    		return false;
    }
    
    return $image;
    		
  }
	
}


?>