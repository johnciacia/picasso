<div class="picasso">
<?php
	foreach($albums as $album) {
		
		$info = pathinfo($album->filename);
		$t = $info['filename'] . "_thumb." . $info['extension'];
		$s = md5($album->name);
    
    $query = explode('?', $_SERVER['REQUEST_URI']);
    $add = '?';
    if (isset($query[1])) {
      $add .= $query[1].'&amp;';
    }
    $add .= "id={$album->aid}";
    $url = $query[0].$add;

	  echo "<a href='$url'>";
	  echo "<img class='thumb' src='" . PICASSO_UPLOAD_URL . "/$s/$t' /> ";
	  echo "</a>";

		
	}
?>
</div>