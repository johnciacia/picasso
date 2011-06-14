<?php

  if(count($albums) == 0) {
    echo "<p>No albums...</p>";
  } else {
    
    foreach($albums as $album) {
      echo "<p><span style=''>[{$album->id}] {$album->name}</span> <span style='float: right;'>[ <a href='?page=picasso-edit-album&id={$album->id}'>Edit</a> | <a href='?action=picasso-delete-album&album={$album->id}' onclick='return delete_album()'>Delete</a> ]</span></p>";
    }
    
  }

?>

<script type="text/javascript">

  function delete_album() {
    return confirm('Deleting this album will delete all the images in the database. The physical images will not be deleted. Press yes to continue or cancel to quit. [This needs to be a more user friendly message]');
  }


</script>