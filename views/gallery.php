<?php


	foreach($albums as $album) {
		
		$info = pathinfo($album->filename);
		$t = $info['filename'] . "_thumb." . $info['extension'];
		$s = md5($album->name);

	  echo "<a href='{$_SERVER["REQUEST_URI"]}&id={$album->aid}'>";
	  echo "<img src='" . PICASSO_UPLOAD_URL . "/$s/$t' /> ";
	  echo "</a>";

		
	}

?>