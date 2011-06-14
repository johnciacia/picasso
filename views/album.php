<div class="picasso">
<?php

foreach($pictures as $picture) {    
	$info = pathinfo($picture->filename);
	$t = $info['filename'] . "_thumb." . $info['extension'];
	
  echo "<a rel='picasso-album' title='{$picture->description}' href='" . PICASSO_UPLOAD_URL . "/{$album->dir}/$picture->filename'>";
  echo "<img class='thumb' src='" . PICASSO_UPLOAD_URL . "/{$album->dir}/$t' />";
  echo "</a>";
}

?>
</div>

<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery("a[rel=picasso-album]").fancybox({
			'transitionIn'		: 'none',
			'transitionOut'		: 'none',
			'titlePosition' 	: 'over',
			'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
				return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
			}
		});
	});
</script>