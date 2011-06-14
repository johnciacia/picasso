<form action="admin-post.php" method="post" enctype="multipart/form-data">
  <label for="file">Filename:</label>
    <input type="file" name="file" id="file" /> 
    <input type="hidden" name="album" value="<?php echo $album; ?>" />
    <input type="hidden" name="aid" value="<?php echo $_GET['id']; ?>" />
    <input type="hidden" name="action" value="picasso-upload-picture" />    
    <input type="submit" class="button" name="submit" value="Upload" />
</form>