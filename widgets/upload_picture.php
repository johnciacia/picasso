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

<div id="swfupload-control">
	<input type="button" id="picasso-upload-button" />
	<div id="media-items" class="hide-if-no-js"> </div>
</div>
<?php
	$siteurl = get_site_url() . "/wp-includes/js/swfupload/swfupload.swf";
	$button = get_site_url() . '/wp-includes/images/upload.png?ver=20100531';
?>
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
			button_image_url: "<?php echo $button; ?>",
			button_placeholder_id: "picasso-upload-button",
			upload_url : "admin-post.php",
			flash_url : "<?php echo $siteurl; ?>",
			file_post_name: "file",
			file_types: "*.jpg;*.png;*.gif",
			post_params : {
				"action" : "picasso-upload-picture",
				"aid" : <?php echo $_GET['id']; ?>,
				"album" : "<?php echo $album; ?>"
			},
			file_size_limit : "2097152b",
			//swfupload_loaded_handler : swfuploadHandler,
			
			file_dialog_start_handler : fileDialogStart,
			file_queued_handler : fileQueued,
			//file_queue_error_handler : fileQueueError,
			file_dialog_complete_handler : fileDialogComplete,
			
			upload_start_handler : uploadStart,
			upload_progress_handler : uploadProgress,
			upload_success_handler : uploadSuccess,
			//upload_error_handler : uploadError,
			//upload_complete_handler : uploadComplete,
			
			custom_settings : {
				degraded_element_id : "html-upload-ui", // id of the element displayed when swfupload is unavailable
				swfupload_element_id : "flash-upload-ui" // id of the element displayed when swfupload is available
			},
			debug: false
		};
		swfu = new SWFUpload(settings);
};

function fileDialogStart() {
    jQuery("#media-upload-error").empty()
}
function fileQueued(fileObj) {
    jQuery(".media-blank").remove();
    if (jQuery("form.type-form #media-items").children().length == 1 && jQuery(".hidden", "#media-items").length > 0) {
        jQuery(".describe-toggle-on").show();
        jQuery(".describe-toggle-off").hide();
        jQuery(".slidetoggle").slideUp(200).siblings().removeClass("hidden")
    }
    jQuery("#media-items").append('<div id="media-item-' + fileObj.id + '" class="media-item child-of-' + post_id + '"><div class="progress"><div class="bar"></div></div><div class="filename original"><span class="percent"></span> ' + fileObj.name + "</div></div>");
    jQuery(".progress", "#media-item-" + fileObj.id).show();
    jQuery("#insert-gallery").attr("disabled", "disabled");
    jQuery("#cancel-upload").attr("disabled", "")
}
function uploadStart(fileObj) {
    try {
        if (typeof topWin.tb_remove != "undefined") {
            topWin.jQuery("#TB_overlay").unbind("click", topWin.tb_remove)
        }
    } catch(e) {}
    return true
}
function uploadProgress(fileObj, bytesDone, bytesTotal) {
    var w = jQuery("#media-items").width() - 2,
    item = jQuery("#media-item-" + fileObj.id);
    jQuery(".bar", item).width(w * bytesDone / bytesTotal);
    jQuery(".percent", item).html(Math.ceil(bytesDone / bytesTotal * 100) + "%");
    if (bytesDone == bytesTotal) {
        //jQuery(".bar", item).html('<strong class="crunching">' + swfuploadL10n.crunching + "</strong>")
    }
}
function uploadSuccess(fileObj, serverData) {
    if (serverData.match("media-upload-error")) {
        jQuery("#media-item-" + fileObj.id).html(serverData);
        return
    }
    //prepareMediaItem(fileObj, serverData);
    //updateMediaForm();
    if (jQuery("#media-item-" + fileObj.id).hasClass("child-of-" + post_id)) {
        jQuery("#attachments-count").text(1 * jQuery("#attachments-count").text() + 1)
    }
}
function uploadComplete(fileObj) {
    if (swfu.getStats().files_queued == 0) {
        jQuery("#cancel-upload").attr("disabled", "disabled");
        jQuery("#insert-gallery").attr("disabled", "")
    }
}
function fileQueueError(fileObj, error_code, message) {
    if (error_code == SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
        wpQueueError(swfuploadL10n.queue_limit_exceeded)
    } else {
        if (error_code == SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT) {
            fileQueued(fileObj);
            wpFileError(fileObj, swfuploadL10n.file_exceeds_size_limit)
        } else {
            if (error_code == SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE) {
                fileQueued(fileObj);
                wpFileError(fileObj, swfuploadL10n.zero_byte_file)
            } else {
                if (error_code == SWFUpload.QUEUE_ERROR.INVALID_FILETYPE) {
                    fileQueued(fileObj);
                    wpFileError(fileObj, swfuploadL10n.invalid_filetype)
                } else {
                    wpQueueError(swfuploadL10n.default_error)
                }
            }
        }
    }
}
function fileDialogComplete(num_files_queued) {
    try {
        if (num_files_queued > 0) {
            this.startUpload()
	        } 
    } catch(ex) {
        this.debug(ex)
    }
}
//]]>
</script> 
