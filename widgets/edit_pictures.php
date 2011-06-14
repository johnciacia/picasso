<?php
  if(count($pictures) == 0) {
    echo "<p>No pictures...</p>";
  } else {
  	echo '<table width="100%" style="border-collapse:collapse;" id="picasso-pictures">';
    foreach($pictures as $picture) {
    	$info = pathinfo($picture->filename);
    	$t = $info['filename'] . "_thumb." . $info['extension'];
    	
    	echo '<tr style="margin-bottom:20px;">';

			echo '<td width="20%" style="border-bottom: 1px solid #999;padding-bottom:10px;padding-top:10px;text-align:center;">';
				echo "<img src='" . PICASSO_UPLOAD_URL . "/$album/" . $t . "' /><br />";
				echo "<input type='button' class='button' name='delete' value='Delete' />";
			echo '</td>';
			
			echo '<td valign="top" style="border-bottom: 1px solid #999;padding-bottom:10px;padding-top:10px;">';
				echo "<p>Caption: </p><textarea style='width:100%' name='description'>This doesn't work. Sorry.</textarea>";
			
			echo '</td>';
      echo '</tr>';
    
    }
    
    echo '</table>';
  }
?>