<form action="admin-post.php" method="post">
      
<table width="95%">

  <tr>
    <td width="20%">
      <p>Name: </p>
    </td>
    
    <td>
      <input style="width:100%;" type="text" name="name" value="<?php echo $album; ?>" />
	  <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
      <input type="hidden" name="action" value="picasso_update_album" /><br />
			
    </td>
  </tr>
	
	<tr>
		<td></td>
		<td>
			<p style="vertical-align:bottom;"> <img style="vertical-align:bottom;" src="<?php echo WP_PLUGIN_URL; ?>/picasso/images/world.png" /> 
			<input style="vertical-align:bottom;" type="radio" name="privacy" value="0" 
				<?php if($privacy == 0) echo 'checked="checked"' ?> /> Public</p>
			<p style="vertical-align:bottom;"> <img style="vertical-align:bottom;" src="<?php echo WP_PLUGIN_URL; ?>/picasso/images/lock.png" /> <input style="vertical-align:bottom;" type="radio" name="privacy" value="1"
				<?php if($privacy == 1) echo 'checked="checked"' ?> /> Private</p>
		</td>
	</tr>
  
  <tr>
    <td colspan="2">
      <input class="button-primary" type="submit" value="Submit" />
    </td>
  </tr>
  
</table>
</form>