<?php 

if(isset($cover[0]->filename) && $cover[0]->filename != "") {
	$info = pathinfo($cover[0]->filename);
	$t = $info['filename'] . "_thumb." . $info['extension'];

	echo "<div style='text-align:center;'><img src='".PICASSO_UPLOAD_URL . "/{$cover[0]->dir}/{$t}' /></div>"; 
} else {
	
	echo "<p>Please select an album cover...</p>";
	
}
?>