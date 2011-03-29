<!-- 
<form action="admin-post.php" method="post" enctype="multipart/form-data">
  <label for="file">Filename:</label>
    <input type="file" name="file" id="file" /> 
    <input type="hidden" name="album" value="<?php echo $album; ?>" />
    <input type="hidden" name="aid" value="<?php echo $_GET['id']; ?>" />
    <input type="hidden" name="action" value="picasso-upload-picture" />    
    <input type="submit" class="button" name="submit" value="Upload" />
</form>
-->

<div id="swfupload-control" style="text-align: center;">
	<input type="button" id="flash-browse-button" />
	<div id="picasso-upload-status" style="max-height:150px; text-align: left; overflow: scroll;"></div>
	<br />
	<p>
		<span id="picasso-succesful-uploads">0</span> Succesful | 
		<span id="picasso-error-uploads">0</span> Errors | 
		<span id="picasso-queued-uploads">0</span> Queued 
	</p>
</div>

<?php
	$siteurl = get_site_url() . "/wp-includes/js/swfupload/swfupload.swf";
	$button = get_site_url() . '/wp-includes/images/upload.png?ver=20100531';
	$upload_url = get_admin_url() . 'admin-post.php';
?>
<script type="text/javascript">
//<![CDATA[
var post_id;
var num_files = 0;
var file_count = 0;
var swfu;
SWFUpload.onload = function() {
	var settings = {
			button_text: '<span class="button">Select Files<\/span>',
			button_text_style: '.button { text-align: center; font-weight: bold; font-family:"Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif; font-size: 11px; text-shadow: 0 1px 0 #FFFFFF; color:#464646; }',
			button_height: "23",
			button_width: "132",
			button_text_top_padding: 3,
			button_image_url: "<?php echo $button; ?>",
			button_placeholder_id: "flash-browse-button",
			upload_url : "<?php echo $upload_url; ?>",
			flash_url : "<?php echo $siteurl; ?>",
			file_post_name: "file",
			file_types: "*.*",
			post_params : {
				"action" : "picasso-upload-picture",
				"aid" : <?php echo $_GET['id']; ?>,
				"album" : "<?php echo $album; ?>",
				"dir"		: "<?php echo $dir; ?>",
				"uid"		:	"<?php /*@TODO: The user should not have the ability to modify this value through POST requests */echo $current_user->ID; ?>", 
				"post_id" : "0",
				"auth_cookie" : "admin|1294697893|d39c7e8ea40c2bd262ef4d8e39d8c55c",
				"logged_in_cookie": "admin|1294697893|a100aa1736c4b529eb9c5167a00e1809",
				"_wpnonce" : "3ef316d551",
				"type" : "",
				"tab" : "",
				"short" : "1"
			},
			file_size_limit : "2097152b",
			file_queued_handler : fileQueued,
			upload_start_handler : uploadStart,
			upload_success_handler : uploadSuccess,
			upload_complete_handler : uploadComplete,
			file_dialog_complete_handler : fileDialogComplete,
			custom_settings : {
				degraded_element_id : "html-upload-ui", // id of the element displayed when swfupload is unavailable
				swfupload_element_id : "flash-upload-ui" // id of the element displayed when swfupload is available
			},
			debug: false
		};
		swfu = new SWFUpload(settings);
};

function fileQueued(fileObj) {

}

function uploadStart(fileObj) {
	return true;
}


function uploadSuccess(fileObj, serverData) {
	var obj = jQuery.parseJSON(serverData);
	
	if(obj.status == "SUCCESS") {
		file_count = file_count + 1;
		jQuery("#picasso-pictures").prepend('<img src="' + obj.data + '" width="50" height="50" style="padding:5px;" />');
		jQuery("#picasso-upload-status").prepend("<p>" + obj.filename + " successfully uploaded.</p>");
		jQuery('#picasso-succesful-uploads').text(""+(parseInt(jQuery('#picasso-succesful-uploads').text())+1));
	} else {
		console.log(obj.data);
		jQuery("#picasso-upload-status").prepend("<p>" + obj.filename + " failed to uploaded.</p>");
		jQuery('#picasso-error-uploads').text(""+(parseInt(jQuery('#picasso-error-uploads').text())+1));
	}
	jQuery('#picasso-queued-uploads').text(""+(parseInt(jQuery('#picasso-queued-uploads').text())-1))
}

function uploadComplete(fileObj) {
	if(file_count == num_files) {
		jQuery("#poststuff").prepend('<div id="message" class="updated below-h2"><p>All images were successfully uploaded!</p></div>');
	}

}


function fileDialogComplete(num_files_queued) {
	jQuery('#picasso-queued-uploads').text(""+num_files_queued);
	try {
		if (num_files_queued > 0) {
			num_files = num_files_queued;
			this.startUpload();
		}
	} catch (ex) {
		this.debug(ex);
	}
	
}

function cancelUpload() {
	swfu.cancelQueue();
}

//]]>
</script> 