/*
* add.js script
* Ajax call to the addScene.php script
* The POST call contains all of the information including title, description, audio, video, and pictures files and links
* After that it will detect the response from the server to show success or error message accordingly
* ajax return false to prevent the web page from loading a new page upon submission
*/

$(document).ready(function() {
	$("#resetFields").click (function(e) {
		window.location.reload();
	});
	$("#picsuploader").plupload({
		// General settings
		runtimes : 'html5,flash,silverlight,html4',
		url : 'php/resourcesUploader.php',
		// User can upload no more then 20 files in one go (sets multiple_queues to false)
		max_file_count: 20,
		chunk_size: '1mb',
		// Resize images on clientside if we can
		resize : {
			width : 1920, 
			height : 1000, 
			quality : 90,
			crop: true // crop to exact dimensions
		},
		filters : {
			// Maximum file size
			max_file_size : '1000mb',
			// Specify what files to browse for
			mime_types: [
				{title : "Image files", extensions : "jpg,gif,png"},
				{title : "Zip files", extensions : "zip"}
			]
		},
		multipart: true,
        multipart_params: {},
		// Rename files by clicking on their titles
		rename: true,
		// Sort files
		sortable: true,
		// Enable ability to drag'n'drop files onto the widget (currently only HTML5 supports that)
		dragdrop: true,
		// Views to activate
		views: {
			list: true,
			thumbs: true, // Show thumbs
			active: 'thumbs'
		},
		// Flash settings
		flash_swf_url : 'Moxie.swf',
		// Silverlight settings
		silverlight_xap_url : 'Moxie.xap'
	});
	$("#audiouploader").plupload({
		// General settings
		runtimes : 'html5,flash,silverlight,html4',
		url : 'php/resourcesUploader.php',
		// User can upload no more then 20 files in one go (sets multiple_queues to false)
		multiple_queues: false,
		max_file_count: 1,
		chunk_size: '1mb',
		filters : {
			mime_types : [
				 {title : "Movies", extensions : "avi,mov,mp4,flv"}, 
				 {title : "Audio", extensions : "mp3"}
			],
			max_file_size: "1000mb",
			prevent_duplicates: true
		},
		multipart: true,
        multipart_params: {},
		// Rename files by clicking on their titles
		rename: true,
		// Enable ability to drag'n'drop files onto the widget (currently only HTML5 supports that)
		dragdrop: true,
		// Views to activate
		views: {
			list: true,
			thumbs: true, // Show thumbs
			active: 'thumbs'
		},
		// Flash settings
		flash_swf_url : 'Moxie.swf',
		// Silverlight settings
		silverlight_xap_url : 'Moxie.xap'
	});
	$("#addSceneForm").submit (function(event){
		event.stopPropagation();
		event.preventDefault();
		var formObj = $(this);
		var postData = new FormData(this);
		var picsUploader = $("#picsuploader").plupload('getUploader');
		picsUploader.bind('BeforeUpload', function(up) {
			up.settings.multipart_params.title = $('input[name="title"]').val();
		});
		var audioUploader = $("#audiouploader").plupload('getUploader');
		audioUploader.bind('BeforeUpload', function(up) {
			up.settings.multipart_params.title = $('input[name="title"]').val();
		});
		if (picsUploader.files.length > 0 && audioUploader.files.length > 0) {
			postData.append("numImg", picsUploader.files.length);
			postData.append("audio", $("span:contains(.mp3)").text());
			picsUploader.bind('FileUploaded', function (){
				audioUploader.bind('FileUploaded', function (){
					formURL = formObj.attr("action");
					method = formObj.attr("method");
					$.ajax({
						url: formURL,
						type: method,
						data: postData,
						contentType: false,
						cache: false,
						processData: false,
						dataType: 'json',
						success: function(data, textStatus, jqXHR) {
							if(typeof data.error === 'undefined')
							{
								$('#successMsg').show();
							}
							else
							{
								console.log('ERRORS: ' + data.error);
							}
						},
						error: function (xhr, textStatus, errorThrown) {
							$('failMsg').show();
						}
					});							
                });
            });
            $('#picsuploader').plupload('start');
			$('#audiouploader').plupload('start');
		} else {
			alert ("You must at least have 1 audio file and 1 picture or 1 video file");
		}
		return false;
	});
});

