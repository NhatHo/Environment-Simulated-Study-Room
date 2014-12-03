/*
* add.js script
* Ajax call to the addScene.php script
* The POST call contains all of the information including title, description, audio, video, and pictures files and links
* After that it will detect the response from the server to show success or error message accordingly
* ajax return false to prevent the web page from loading a new page upon submission
*/

$(document).ready(function() {
	mOxie.Mime.addMimeType("audio/x-aiff,aif aiff"); // notice that it is called before Plupload initialization
	$("#logout").click(function() {
		$.ajax({
			url: 'php/logout.php',
			type: 'post',
			success: function() {
				console.log ("Successfully logged out");
				window.location.replace("index.html");
			},
			error: function(xhr, desc, err) {
				console.log(xhr + "\n" + err);
			}
		});
	});
	$.ajax({
		url: 'php/login_check.php',
		type: 'post',
		data:{page:'adminOnly'},
		success: function(json) {
			var jsonObject = jQuery.parseJSON(json);
			if (jsonObject.result === "false") {
				window.location.replace("index.html");
			}
			else if (jsonObject.result === "ignored") {
				window.location.replace("client.html");
			}
		},
		error: function(xhr, desc, err) {
			console.log(xhr + "\n" + err);
		}
	});
	$('[name="title"]').keyup(function() {
		var regex=/[^a-z0-9\s]/gi;
		$(this).val($(this).val().replace(regex ,""));
	});
	$("#resetFields").click (function(e) {
		window.location.reload(true);
	});
	function filterFiles (up, files, id) {
		var numOfFiles = up.files.length;
		var maxNumOfFiles = 0;
		var videoExtensions = "mov,mp4,f4v,flv,avi";
		var pictureExtensions = "jpg,png";
		var removeFiles = new Array();
		for (var i = 0; i < numOfFiles; i++){
			extension  = up.files[i].name.substr(-3).toLowerCase();
			if (i == 0 && (videoExtensions.indexOf(extension) > -1 || pictureExtensions.indexOf(extension) > -1)) {
				validExtension = extension;
				if (videoExtensions.indexOf(validExtension) > -1) {
					maxNumOfFiles = 1;
				} else if (pictureExtensions.indexOf(validExtension) > -1) {
					maxNumOfFiles = 20;
				}
			}
			if(validExtension.indexOf(extension) < 0){
				removeFiles.push(up.files[i].id);
			}
		}
		if (removeFiles.length > 0){
			$("#"+id).plupload('notify', 'error', "Only " + validExtension + " file(s) are allowed");
			for (var i = 0; i < removeFiles.length; i++) {
				up.removeFile(removeFiles[i]);
			}
			numOfFiles = up.files.length;
		}
		if (numOfFiles > maxNumOfFiles) {
			$("#"+id).plupload('notify', 'error', "Only "+maxNumOfFiles+" file(s) are allowed");
			var extraFiles = new Array();
			for(var i = maxNumOfFiles; i < numOfFiles; i++) {
				extraFiles.push(up.files[i].id);
			}
			for(var i = 0; i < extraFiles.length; i++) {
				up.removeFile(extraFiles[i]);
			}
		}
	}
	var projUploaderSettings = {
		// General settings
		runtimes : 'html5,silverlight,gears,browserplus',
		url : 'php/resourcesUploader.php',
		chunk_size: '3mb',
		// Resize images on clientside if we can
		resize : {
			width : 1920, 
			height : 1000, 
			quality : 90,
			crop: true // crop to exact dimensions
		},
		filters : {
			// Maximum file size
			max_file_size : '3000mb',
			prevent_duplicates: true,
			// Specify what files to browse for
			mime_types: [
				{title : "Video files", extensions : "mov,mp4,f4v,flv,avi"},
				{title : "Image files", extensions : "jpg,png"}
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
		flash_swf_url : 'js/Moxie.swf',
		// Silverlight settings
		silverlight_xap_url : 'js/Moxie.xap'
	}
	$("#proj1Uploader").plupload($.extend(projUploaderSettings, {
		init: {
			FilesAdded: function (up, files) {
				filterFiles (up, files, "proj1Uploader");
			}
		}
	}));
	$("#proj2Uploader").plupload($.extend(projUploaderSettings, {
		init: {
			FilesAdded: function (up, files) {
				filterFiles (up, files, "proj2Uploader");
			}
		}
	}));
	$("#proj3Uploader").plupload($.extend(projUploaderSettings, {
		init: {
			FilesAdded: function (up, files) {
				filterFiles (up, files, "proj3Uploader");
			}
		}
	}));
	var coverUploaderSettings = {
		// General settings
		runtimes : 'html5,silverlight,html4',
		url : 'php/resourcesUploader.php',
		// User can upload no more then 20 files in one go (sets multiple_queues to false)
		max_file_count: 1,
		multi_selection: false,
		chunk_size: '4mb',
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
		flash_swf_url : 'js/Moxie.swf',
		// Silverlight settings
		silverlight_xap_url : 'js/Moxie.xap'
	}
	$("#audiouploader").plupload($.extend(coverUploaderSettings, {
		filters : {
			mime_types : [
				{title : "Audio", extensions : "mp3"}
			],
			max_file_size: "1000mb",
			prevent_duplicates: true
		}
	}));
	$("#coverUploader").plupload($.extend(coverUploaderSettings, {
		resize : {
			width : 1920, 
			height : 1000, 
			quality : 90,
			crop: true // crop to exact dimensions
		},
		filters : {
			mime_types : [
				{title : "Image files", extensions : "jpg,png"}
				
			],
			max_file_size: "1000mb",
			prevent_duplicates: true
		}
	}));
	$("#create").click (function(event) {
		event.stopPropagation();
		event.preventDefault();
		var coverUploader = $("#coverUploader").plupload('getUploader');
		coverUploader.bind('BeforeUpload', function(up) {
			up.settings.multipart_params.title = $('input[name="title"]').val();
		});
		var proj1Uploader = $("#proj1Uploader").plupload('getUploader');
		proj1Uploader.bind('BeforeUpload', function(up) {
			up.settings.multipart_params.title = $('input[name="title"]').val();
			up.settings.multipart_params.projector = "1";
		});
		var proj2Uploader = $("#proj2Uploader").plupload('getUploader');
		proj2Uploader.bind('BeforeUpload', function(up) {
			up.settings.multipart_params.title = $('input[name="title"]').val();
			up.settings.multipart_params.projector = "2";
		});
		var proj3Uploader = $("#proj3Uploader").plupload('getUploader');
		proj3Uploader.bind('BeforeUpload', function(up) {
			up.settings.multipart_params.title = $('input[name="title"]').val();
			up.settings.multipart_params.projector = "3";
		});
		var audioUploader = $("#audiouploader").plupload('getUploader');
		audioUploader.bind('BeforeUpload', function(up) {
			up.settings.multipart_params.title = $('input[name="title"]').val();
		});
		var title = $('[name="title"]').val();
		var desc = $('[name="desc"]').val();
		if (proj1Uploader.files.length > 0 && proj2Uploader.files.length > 0 && proj3Uploader.files.length > 0 && audioUploader.files.length > 0 && title.length > 0 && desc.length > 0) {
			var cover = $("#coverUploader").find("span.plupload_file_name_wrapper").text();
			var proj1Num = proj1Uploader.files.length;
			var proj1Files = $("#proj1Uploader").find("span.plupload_file_name_wrapper").text();
			var proj2Num = proj2Uploader.files.length;
			var proj2Files = $("#proj2Uploader").find("span.plupload_file_name_wrapper").text();
			var proj3Num = proj3Uploader.files.length;
			var proj3Files = $("#proj3Uploader").find("span.plupload_file_name_wrapper").text();
			var soundtrack = $("#audiouploader").find("span.plupload_file_name_wrapper").text();
			var postData = new FormData();
			postData.append("title", title);
			postData.append("desc", desc);
			postData.append("cover", cover);
			postData.append("proj1Num", proj1Num);
			postData.append("proj1Num", proj1Num);
			postData.append("proj1Files", proj1Files);
			postData.append("proj2Num", proj2Num);
			postData.append("proj2Files", proj2Files);
			postData.append("proj3Num", proj3Num);
			postData.append("proj3Files", proj3Files);
			postData.append("soundtrack", soundtrack);
			bootbox.dialog({
				message: "Please confirm Scene information below: <br/>" + "<br/>Name:" + title + "<br/><br/>Description: " + desc + "<br/><br/>Cover: " + cover + "<br/><br/>Projector 1: " + proj1Files
						+ "<br/><br/>Projector 2: " + proj2Files + "<br/><br/>Projector 3: " + proj3Files + "<br/><br/>Soundtrack: " + soundtrack,
				title: "Confirmation",
				buttons: {
					confirm: {
						label: "Confirm",
						className: "btn-primary",
						callback: function() {
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
									console.log ("Inside Success message");
									$('#failMsg').hide();
									$('#successMsg').show();
									$('#coverUploader').plupload('start');
									$('#proj1Uploader').plupload('start');
									$('#proj2Uploader').plupload('start');
									$('#proj3Uploader').plupload('start');
									$('#audiouploader').plupload('start');
								},
								error: function (xhr, textStatus, errorThrown) {
									console.log ("Inside error message");
									$('#successMsg').hide();
									$('#failMsg').show();
								}
							});
						}
					},
					cancel: {
						label: "Cancel",
						className: "btn-cancel"
					}
				}
			});
		} else {
			bootbox.alert("You must have Title, Description, and AT LEAST 1 item in each section");
		}
		return false;
	});
});

