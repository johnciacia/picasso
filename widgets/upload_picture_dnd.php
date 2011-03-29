<form id="file_upload" action="admin-post.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="file" multiple>
	<input type="hidden" name="action" value="picasso-upload-picture" />
	<input type="hidden" name="album" value="<?php echo $album; ?>" />
	<input type="hidden" name="aid" value="<?php echo $_GET['id']; ?>" />
	<input type="hidden" name="dir" value="<?php echo $dir; ?>" />
    <button>Upload</button>
    <div>Upload files</div>
</form>

<table id="files"></table>

<script type="text/JavaScript">
	jQuery(document).ready(function() {
		
		jQuery(function () {
		    jQuery('#file_upload').fileUploadUI({
		        uploadTable: jQuery('#files'),
		        downloadTable: jQuery('#files'),
		        buildUploadRow: function (files, index) {
		            return jQuery('<tr><td>' + files[index].size + '<\/td>' +
		                    '<td class="file_upload_progress"><div><\/div><\/td>' +
		                    '<td class="file_upload_cancel">' +
		                    '<button class="ui-state-default ui-corner-all" title="Cancel">' +
		                    '<span class="ui-icon ui-icon-cancel">Cancel<\/span>' +
		                    '<\/button><\/td><\/tr>');
		        },
		        buildDownloadRow: function (file) {
					jQuery("#picasso-pictures").prepend('<img src="'+file.data+'" width="50" height="50" style="padding:5px;" />');
		            return jQuery('<tr><td>' + file.filename + '<\/td><\/tr>');
		        }
		    });
		});
		
	});
</script>