<div class="picasso">
<?php

	foreach($albums as $album) {
		
		if($album->filename == "") {
			$img = WP_PLUGIN_URL . '/picasso/images/default_album.jpg';
		} else {
			$info = pathinfo($album->filename);
			$t = $info['filename'] . "_thumb." . $info['extension'];
			$s = $album->dir;
			$img = PICASSO_UPLOAD_URL . "/$s/$t";
    }
		
    $query = explode('?', $_SERVER['REQUEST_URI']);
    $add = '?';
    if (isset($query[1])) {
      $add .= $query[1].'&amp;';
    }
    $add .= "id={$album->id}";
    $url = $query[0].$add;

		echo "<div class='picasso-gallery' style='display: table-cell'>";
		echo "<a href='$url'>";
		//@TODO The image width and height should not be static
		echo "<img class='thumb' width='150' height='150' src='$img' alt='' />";
		echo "</a>";
		echo "<label style='padding-left: 10px; display: block'>{$album->name}</label>";
		echo "</div>";

			
	}
?>
</div>