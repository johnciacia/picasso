<?php
  if(count($pictures) == 0) {
    echo "<p>No pictures...</p>";
  } else {
    foreach($pictures as $picture) {
    

    	$info = pathinfo($picture->filename);
    	$t = $info['filename'] . "_thumb." . $info['extension'];
    	
      echo "<img src='" . PICASSO_UPLOAD_URL . "/$album/" . $t . "' /> ";
    
    }
  }
?>