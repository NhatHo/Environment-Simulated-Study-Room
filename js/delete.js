/*
* delete.js script
* Make a ajax call to retrieveScenes to retrieve all available scenes in MySQL database
* The scenes will be dynamically put into panels and added into availableScenes div
* The div will be updated and display all available scenes in database
*/
$(document).ready(function() {
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
		cache: false,
		data: {page:'adminOnly'},
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
	$.ajax({
		url: 'php/retrieveScenes.php',
		type: 'post',
		cache: false,
		success: function(json) {
			var jsonObject = jQuery.parseJSON(json);
			var deleteDisplay = "";
			var counter = 0;
			$.each(jsonObject, function(i, item) {
				if (typeof item == 'object') {
					deleteDisplay += '<div class="panel panel-default col-sm-4 panelSize">'
                        + '<div class="panel-heading paneltitle">'
                        +  '<h3 class="panel-title">'+item.title+'</h3>'
                        +'</div><div class="panel-body panelbody">'
                        +'<img src="'+item.coverImage+'" class="img-responsive">'
						+'<div class="checkbox"><label><input type="checkbox" name="options" value="'+item.title+'">'+item.title+'</label></div></div></div>';
					counter ++;
				} else {
					return false;
				}
			});
			if (counter == 0) {
				deleteDisplay += '<h1 class="text-center">No Available Scene, Please create at least ONE<small><br/><a href="addScene.html" class="text-center">Add Scene</a></small></h1><br><br>';
				$("#deleteScenes").hide();
			} else {
				$("#deleteScenes").show();
			}
			$('#availableScenes').html(deleteDisplay);
		},
		error: function(xhr, desc, err) {
			console.log(xhr + "\n" + err);
		}
	});
	$("#deleteScenes").click (function(event) {
		var checkedScenes = [];
		var selectedScenes = "";
		$('[name="options"]').each(function () {
			if($(this).is(':checked')) {
				//console.log ("This box is checked: " + $(this).val());
				checkedScenes.push($(this).val());
				selectedScenes += $(this).val() + "<br/>";
			}
		});
		if (checkedScenes.length > 0) {
			bootbox.dialog({
				message: "Do you want to delete these scenes? <br/>" + selectedScenes,
				title: "Confirmation",
				buttons: {
					confirm: {
						label: "Confirm",
						className: "btn-primary",
						callback: function() {
							$.ajax({
								url: "php/delete.php",
								type:"post", //send it through get method
								data:{scenes: checkedScenes},
								success: function(response) {
									console.log ("Response in Success: " + response);
									window.location.reload(true);
								},
								error: function(xhr) {
									console.log ("Response in error: " + response);
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
			bootbox.alert("At least ONE scene must be selected to DELETE");
		}
	});
});