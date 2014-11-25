/*
* edit.js script
* Make a ajax call to retrieveScenes to retrieve all available scenes in MySQL database
* The scenes will be dynamically put into panels and added into availableScenes div
* The div will be updated and display all available scenes in database
*/
$(document).ready(function() {
	$.ajax({
		url: 'php/retrieveScenes.php',
		type: 'post',
		cache: false,
		success: function(json) {
			var jsonObject = jQuery.parseJSON(json);
			var editDisplay = "";
			var counter = 0;
			$.each(jsonObject, function(i, item) {
				if (typeof item == 'object') {
					
					editDisplay += '<div class="panel panel-default col-sm-4 panelSize">'
                        + '<div class="panel-heading paneltitle">'
                        +  '<h3 class="panel-title">'+item.name+'</h3>'
                        +'</div><div class="panel-body panelbody">'
                        +'<img src="'+item.path+'image1.jpg" class="img-responsive">';
					if (counter == 0) {
						editDisplay += '<div class="radio"><label><input type="radio" name="optionsRadios" value="'+item.name+'" checked>'+item.name+'</label></div></div></div>';
					} else {
						editDisplay += '<div class="radio"><label><input type="radio" name="optionsRadios" value="'+item.name+'">'+item.name+'</label></div></div></div>';
					}
					counter++;
				} else {
					return false;
				}
			});
			if (counter == 0) {
				editDisplay += '<h1 class="text-center">No Available Scene, Please create at least ONE<small><br/><a href="addscene.html" class="text-center">Add Scene</a></small></h1><br><br>';
				$("#editScenes").hide();
			} else if (!$("#editSceneForm").is(":visible") ){
				$("#editScenes").show();
			}
			$('#availableScenes').html(editDisplay);
		},
		error: function(xhr, desc, err) {
			console.log(xhr + "\n" + err);
		}
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
		silverlight_xap_url : 'Moxie.xap',
		init : {
            FilesAdded: function(up, files) {
                plupload.each(files, function(file) {
                    if (up.files.length > 1) {
						$('#audiouploader').plupload('notify', 'error', "Notice: Only ONE soundtrack can be added!!!<br/>Please replace the current one.");
                        up.removeFile(file);
                    }
                });
            }
		}
	});
	$("#cancelEdit").click (function(e) {
		window.location.reload(true);
		$("#editSceneForm").hide();
		$("#viewAvaiScenes").show();
	});
	$("#editScenes").click (function(e) {
		$("#editSceneForm").show();
		$("#viewAvaiScenes").hide();
		$('[name="title"]').prop('readonly', true);
		var selectedScene = "";
		$('[name="optionsRadios"]').each(function () {
			if($(this).is(':checked')) {
				selectedScene = $(this).val();
			}
		});
		$.ajax({
			url: 'php/retrieveSceneInfo.php',
			type: 'POST',
			cache: false,
			data: {name:selectedScene},
			success: function(json) {
				var jsonObject = jQuery.parseJSON(json);
				var pathToData = "";
				var numberOfFile = 0;
				var soundtrack = "";
				$.each(jsonObject, function(i, item) {
					if (typeof item == 'object') {
						$('[name="title"]').val(item.name);
						$('[name="desc"]').html(item.description);
						pathToData = item.path;
						numberOfFile = item.numImages;
						soundtrack = item.soundtrack;
					} else {
						alert ("Cannot retrieve data of this scene, please check database side");
						return false;
					}
					var picsUploader = $("#picsuploader").plupload('getUploader');
					picsUploader.bind('BeforeUpload', function(up) {
						up.settings.multipart_params.title = item.name;
					});
					
					for(i = 1; i <= numberOfFile; i++) {
						var path = pathToData+"image"+i+".jpg";
						var id = new Date().getTime() + Math.floor(Math.random()*10000) + 10000;
						var file = new plupload.File(id, path, 0);
						file.status = plupload.DONE;
						file.percent = 100;
						picsUploader.addFile(file, "image"+i+".jpg");
						picsUploader.trigger("FilesAdded");
					}
					
					var audioUploader = $("#audiouploader").plupload('getUploader');
					audioUploader.bind('BeforeUpload', function(up) {
						up.settings.multipart_params.title = item.name;
					});
					soundtrack = $.trim(soundtrack);
					var id = new Date().getTime() + Math.floor(Math.random()*10000) + 10000;
					var file = new plupload.File(id, soundtrack, 0);
					file.status = plupload.DONE;
					file.percent = 100;
					audioUploader.addFile(file, soundtrack);
				});
			},
			error: function(xhr, desc, err) {
				console.log(xhr + "\n" + err);
			}
		});		
	});

	$("#editSceneForm").submit (function(event){
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
			audioUploader.bind('UploadComplete', function (){
				$('#picsuploader').plupload('start');
				$('#picsuploader').on('complete', function () {
				//picsUploader.bind('UploadComplete', function () {
					postData.append("numImg", picsUploader.files.length);
					postData.append("audio", $("span:contains(.mp3)").text());
					postData.append("images", $("span:contains(.jpg)").text());
					console.log ("NumImg: " +$("#picsuploader").plupload('getUploader').files.length+ "Audio is: " +$("span:contains(.mp3)").text()+ " Images: " + $("span:contains(.jpg)").text());
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
						},
						error: function (xhr, textStatus, errorThrown) {
							window.location.reload(true);
						}						
					});
				});
            });
			$('#audiouploader').plupload('start');
		} else {
			alert ("You must at least have 1 audio file and 1 picture or 1 video file");
		}
		return false;
	});
});