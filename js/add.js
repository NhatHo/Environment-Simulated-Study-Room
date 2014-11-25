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
			prevent_duplicates: true,
			// Specify what files to browse for
			mime_types: [
				{title : "Image files", extensions : "jpg,gif,png"}
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
		multi_selection: false,
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
	$("#create").click (function(event) {
		event.stopPropagation();
		event.preventDefault();
		var picsUploader = $("#picsuploader").plupload('getUploader');
		picsUploader.bind('BeforeUpload', function(up) {
			up.settings.multipart_params.title = $('input[name="title"]').val();
		});
		var audioUploader = $("#audiouploader").plupload('getUploader');
		audioUploader.bind('BeforeUpload', function(up) {
			up.settings.multipart_params.title = $('input[name="title"]').val();
		});
		if (picsUploader.files.length > 0 && audioUploader.files.length > 0) {
			var numberOfImages = picsUploader.files.length;
			var title = $('[name="title"]').val();
			var desc = $('[name="desc"]').val();
			var audioFile = $("span:contains(.mp3)").text();
			var pictureFiles = $("span:contains(.jpg)").text();
			var pics = pictureFiles.replace(new RegExp(".jpg", 'g'), ".jpg<br/>");
			var postData = new FormData();
			postData.append("title", title);
			postData.append("desc", desc);
			postData.append("numImg", numberOfImages);
			postData.append("audio", audioFile);
			bootbox.dialog({
				message: "Please confirm Scene information below: <br/>" + "<br/>Name:" + title + "<br/><br/>Description: " + desc + "<br/><br/>Pictures: " + pics + "<br/>Soundtrack: " + audioFile,
				title: "Confirmation",
				buttons: {
					confirm: {
						label: "Confirm",
						className: "btn-primary",
						callback: function() {
							audioUploader.bind('UploadComplete', function (){
								$('#picsuploader').plupload('start');
								$('#picsuploader').on('complete', function () {
									formURL = $("#addSceneForm").attr("action");
									method = $("#addSceneForm").attr("method");
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
							$('#audiouploader').plupload('start');
						}
					},
					cancel: {
						label: "Cancel",
						className: "btn-cancel"
					}
				}
			});
		} else {
			bootbox.alert("You must have Title, Description, ONE cover picture AND ONE soundtrack");
		}
		return false;
	});
});

