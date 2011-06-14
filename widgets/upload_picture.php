<form action="admin-post.php" method="post" enctype="multipart/form-data">
  <label for="file">Filename:</label>
    <input type="file" name="file" id="file" /> 
    <input type="hidden" name="album" value="<?php echo $album; ?>" />
    <input type="hidden" name="aid" value="<?php echo $_GET['id']; ?>" />
    <input type="hidden" name="action" value="picasso-upload-picture" />    
    <input type="submit" class="button" name="submit" value="Upload" />
</form>

<!-- 
<div id="swfupload-control">
	<input type="button" id="flash-browse-button" />
	<div id="media-items" class="hide-if-no-js"> </div>
	<ol id="log"></ol>
</div>
<script type="text/javascript">
//<![CDATA[
var post_id;
var swfu;
SWFUpload.onload = function() {
	var settings = {
			button_text: '<span class="button">Select Files<\/span>',
			button_text_style: '.button { text-align: center; font-weight: bold; font-family:"Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif; font-size: 11px; text-shadow: 0 1px 0 #FFFFFF; color:#464646; }',
			button_height: "23",
			button_width: "132",
			button_text_top_padding: 3,
			button_image_url: '../wp-includes/images/upload.png?ver=20100531',
			button_placeholder_id: "flash-browse-button",
			upload_url : "admin-post.php",
			flash_url : "../wp-includes/js/swfupload/swfupload.swf",
			file_post_name: "file",
			file_types: "*.*",
			post_params : {
				"action" : "picasso-upload-picture",
				"aid" : <?php echo $_GET['id']; ?>,
				"album" : "<?php echo $album; ?>",
				"post_id" : "0",
				"auth_cookie" : "admin|1294697893|d39c7e8ea40c2bd262ef4d8e39d8c55c",
				"logged_in_cookie": "admin|1294697893|a100aa1736c4b529eb9c5167a00e1809",
				"_wpnonce" : "3ef316d551",
				"type" : "",
				"tab" : "",
				"short" : "1"
			},
			file_size_limit : "2097152b",
			file_dialog_start_handler : fileDialogStart,
			file_queued_handler : fileQueued,
			upload_start_handler : uploadStart,
			upload_progress_handler : uploadProgress,
			upload_error_handler : uploadError,
			upload_success_handler : uploadSuccess,
			upload_complete_handler : uploadComplete,
			file_queue_error_handler : fileQueueError,
			file_dialog_complete_handler : fileDialogComplete,
			custom_settings : {
				degraded_element_id : "html-upload-ui", // id of the element displayed when swfupload is unavailable
				swfupload_element_id : "flash-upload-ui" // id of the element displayed when swfupload is available
			},
			debug: false
		};
		swfu = new SWFUpload(settings);
};


//]]>
</script> 
-->
