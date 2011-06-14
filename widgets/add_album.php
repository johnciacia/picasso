<form action="admin-post.php" method="post">
<?php
?>
      
<table width="95%">

  <tr>
    <td width="20%">
      <p>Name: </p>
    </td>
    
    <td>
      <input style="width:100%;" type="text" name="name" />
      <input type="hidden" name="action" value="picasso_save_album" />
    </td>
  </tr>
  
  <tr>
    <td colspan="2">
      <input class="button-primary" type="submit" value="Submit" />
    </td>
  </tr>
  
</table>
</form>