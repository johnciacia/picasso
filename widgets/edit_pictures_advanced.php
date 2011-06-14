<?php
  if(count($pictures) == 0) {
    echo "<p>No pictures...</p>";
  } else {
  	echo '<table width="100%" style="border-collapse:collapse;" id="picasso-pictures">';
    foreach($pictures as $picture) {
    	$info = pathinfo($picture->filename);
    	$t = $info['filename'] . "_thumb." . $info['extension'];
    	
    	echo '<tr style="margin-bottom:20px;">';

			echo '<td width="25%" style="border-bottom: 1px solid #999;padding-bottom:10px;padding-top:10px;text-align:center;">';
				echo "<img src='" . PICASSO_UPLOAD_URL . "/$album/" . $t . "' /><br />";
				echo "<input type='button' class='button' onclick='window.location=\"?action=picasso-set-album-cover&pid={$picture->id}&aid={$_GET['id']}\"' name='delete' value='Set Album Cover' /><br />";
				echo "<input type='button' class='button' onclick='window.location=\"?action=picasso-delete-picture&id={$picture->id}\"' name='delete' value='Delete' />";
			echo '</td>';
			
			echo '<td valign="top" style="border-bottom: 1px solid #999;padding-bottom:10px;padding-top:10px;">';
			?>
				<table width="100%">
					<tr>
						<td style="width: 90px;"><p>Caption: </p></td>
						<td><textarea style="width: 100%" name="description">This doesn't work. Sorry.</textarea></td>
					</tr>
					
					<tr>
						<td><p>Direct Link: </p></td>
						<td><input type="text" style="width:100%" value="<?php echo PICASSO_UPLOAD_URL . "/{$album}/{$picture->filename}"; ?>" /></td>
					</tr>

					<tr>
						<td><p>HTML Link: </p></td>
						<td><input type="text" style="width:100%" value="&lt;a href=&quot;<?php echo PICASSO_UPLOAD_URL . "/{$album}/{$picture->filename}"; ?>&quot;&gt;&lt;img src=&quot;<?php echo PICASSO_UPLOAD_URL . "/{$album}/{$t}"; ?>&quot; /&gt;&lt;/a&gt;" /></td>
					</tr>
					
					<tr>
						<td><p>Share To: </p></td>
						<td>
							<a href="http://www.facebook.com/sharer.php?u=<?php echo PICASSO_UPLOAD_URL . "/{$album}/{$picture->filename}"; ?>" onclick="window.open(this.href,'sharer','toolbar=0,status=0,width=626,height=436'); return false;">Facebook</a>
							| Twitter</td>
					</tr>

					
				</table>
				
			<?php
			
			echo '</td>';
      echo '</tr>';
    
    }
    
    echo '</table>';
  }
?>