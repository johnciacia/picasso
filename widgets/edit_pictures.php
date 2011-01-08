<?php
  if(count($pictures) == 0) {
    echo "<p>No pictures...</p>";
  } else {
    foreach($pictures as $picture) {
    
      echo "<img src='" . WP_CONTENT_URL . "/picasso/$album/" . $picture->filename . "' width='100' height='100' /> ";
    
    }
  }
?>