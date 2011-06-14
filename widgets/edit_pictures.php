<?php
if(!isset($_GET['pid'])) {
	echo "<p>Please select a picture to edit.</p>";
} else {
?>
<form action="admin-post.php" method="post">
<?php
  	echo '<table width="100%" style="border-collapse:collapse;">';

    	$info = pathinfo($picture->filename);
    	$t = $info['filename'] . "_thumb." . $info['extension'];
    	
    	echo '<tr style="margin-bottom:20px;">';

			echo '<td width="25%" style="text-align:center">';
				echo "<img src='" . PICASSO_UPLOAD_URL . "/$album/" . $t . "' /><br />";
			echo '</td>';
			
			echo '<td valign="top">';
			?>
				<table width="100%">
					<tr>
						<td style="width: 90px;"><p>Caption: </p></td>
						<td><textarea style="width: 100%" name="description"><?php echo $picture->description; ?></textarea></td>
					</tr>
					
					<tr>
						<td><p>Direct Link: </p></td>
						<td><input type="text" style="width:100%" value="<?php echo PICASSO_UPLOAD_URL . "/{$album}/{$picture->filename}"; ?>" disabled="disabled" /></td>
					</tr>

					<tr>
						<td><p>HTML Link: </p></td>
						<td><input type="text" style="width:100%" value="&lt;a href=&quot;<?php echo PICASSO_UPLOAD_URL . "/{$album}/{$picture->filename}"; ?>&quot;&gt;&lt;img src=&quot;<?php echo PICASSO_UPLOAD_URL . "/{$album}/{$t}"; ?>&quot; /&gt;&lt;/a&gt;" disabled="disabled" /></td>
					</tr>
					
					<tr>
						<td><p>Share To: </p></td>
						<td>
							<a href="http://www.facebook.com/sharer.php?u=<?php echo PICASSO_UPLOAD_URL . "/{$album}/{$picture->filename}"; ?>" onclick="window.open(this.href,'sharer','toolbar=0,status=0,width=626,height=436'); return false;">Facebook</a>
							| Twitter</td>
					</tr>
					
					<tr>
						<td colspan="2">
						<?php
							echo "<input type='button' onclick='window.location=\"?action=picasso-set-album-cover&pid={$picture->id}&aid={$_GET['id']}\"' name='album_cover' class='button' value='Set As Album Cover' />&nbsp;";

							
							echo "<input type='button' style='display:inline !important;' class='button' onclick='window.location=\"?action=picasso-delete-picture&id={$picture->id}\"' name='delete' value='Delete' />";

						?>							
							
					
							<input type="hidden" name="id" value="<?php echo $_GET['pid']; ?> ">
							<input type="hidden" value="picasso_update_picture" name="action" />
							<input class="button-primary" style="float:right;" type="submit" value="Submit" />
						</td>
					</tr>
					
				</table>
				

			</td>
      </tr>
    

    
    </table>

</form>

<?php } ?>