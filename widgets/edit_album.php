<p>Major pitfall: the directory structure depends on the album name (via md5). Thus, changing the album name will change the hash rendering all links to be broken.</p>
<form action="admin-post.php" method="post">
      
<table width="95%">

  <tr>
    <td width="20%">
      <p>Name: </p>
    </td>
    
    <td>
      <input style="width:100%;" type="text" name="name" value="<?php echo $album; ?>" />
      <input type="hidden" name="action" value="picasso_save_album" /><br />
			
    </td>
  </tr>
	
	<tr>
		<td></td>
		<td>
			<p style="vertical-align:bottom;"> <img style="vertical-align:bottom;" src="<?php echo WP_PLUGIN_URL; ?>/picasso/images/world.png" /> <input style="vertical-align:bottom;" type="radio" name="privacy" value="public" checked="checked" /> Public</p>
			<p style="vertical-align:bottom;"> <img style="vertical-align:bottom;" src="<?php echo WP_PLUGIN_URL; ?>/picasso/images/lock.png" /> <input style="vertical-align:bottom;" type="radio" name="privacy" value="private" /> Private</p>
		</td>
	</tr>
  
  <tr>
    <td colspan="2">
      <input class="button-primary" type="submit" value="Submit" />
    </td>
  </tr>
  
</table>
</form>