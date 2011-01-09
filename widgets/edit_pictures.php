<?php
  if(count($pictures) == 0) {
    echo "<p>No pictures...</p>";
  } else {
    foreach($pictures as $picture) {
    
    	$a = explode(".", $picture->filename);
    	$b = $a[0] . "_t." . $a[1];
    	
      echo "<img src='" . WP_CONTENT_URL . "/uploads/picasso/$album/" . $b . "' /> ";
    
    }
  }
?>