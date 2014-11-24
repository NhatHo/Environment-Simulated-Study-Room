/*
* delete.js script
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
			var deleteDisplay = "";
			var counter = 0;
			$.each(jsonObject, function(i, item) {
				if (typeof item == 'object') {
					deleteDisplay += '<div class="panel panel-default col-sm-4 panelSize">'
                        + '<div class="panel-heading paneltitle">'
                        +  '<h3 class="panel-title">'+item.name+'</h3>'
                        +'</div><div class="panel-body panelbody">'
                        +'<img src="'+item.path+'image1.jpg" class="img-responsive">'
						+'<div class="checkbox"><label><input type="checkbox" name="options" value="'+item.name+'">'+item.name+'</label></div></div></div>';
					counter ++;
				} else {
					return false;
				}
			});
			if (counter == 0) {
				deleteDisplay += '<h1 class="text-center">No Available Scene, Please create at least ONE<small><br/><a href="addscene.html" class="text-center">Add Scene</a></small></h1>';
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
		$('[name="options"]').each(function () {
			if($(this).is(':checked')) {
				//console.log ("This box is checked: " + $(this).val());
				checkedScenes.push($(this).val());
			}
		});
		if (checkedScenes.length > 0) {
			
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
		} else {
			alert ("At least ONE scene must be selected to DELETE");
		}
	});
});