<div id="picasso-pictures">
<?php
  if(count($pictures) == 0) {
    echo "<p>No pictures...</p>";
  } else {
    foreach($pictures as $picture) {
    	$info = pathinfo($picture->filename);
    	$t = $info['filename'] . "_thumb." . $info['extension'];
		echo "<a href='?page=picasso-edit-album&id={$_GET['id']}&pid={$picture->id}'>";
		echo "<img  style='padding:5px;' src='" . PICASSO_UPLOAD_URL . "/$album/" . $t . "' width='50' height='50' />";
		echo "</a>";
    }
  }
?>
</div>