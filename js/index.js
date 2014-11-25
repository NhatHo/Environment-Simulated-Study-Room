/*
* Enable carousel selection actions
* Enable and disable login form upon buttons clicked
*/
$(document).ready(function() {
	$('#signinButton').click(function() {
		$('#signinForm').show();
	});
	$('#closeForm').click(function(){
		$('#signinForm').hide();
	});
	$("#carousel-selector").on("swiperight", function() {
		$("#carousel-selector").carousel('prev');
	});
	$("#carousel-selector").on("swipeleft", function() {
		$("#carousel-selector").carousel('next');
	});
});
/*
* index.js
* Make an AJAX call to retrieveScenes.php script
* Get the feedback from the server and dynamically create carousel and indicators div
* The parts will be added into sceneSelector div so users can view it
*/
$(document).ready(function() {
	$.ajax({
		url: 'php/retrieveScenes.php',
		type: 'post',
		cache: false,
		success: function(json) {
			var jsonObject = jQuery.parseJSON(json);
			var counter = 0;
			var carouselDisplay = "";
			var indicator = "";
			$.each(jsonObject, function(i, item) {
				if (typeof item == 'object') {
					if (counter == 0) {
						carouselDisplay += '<div class="item active"';
						indicator += '<li data-target="#carousel-selector" data-slide-to="0" class="active"></li>';
					} else {
						carouselDisplay += '<div class="item"';
						indicator += '<li data-target="#carousel-selector" data-slide-to="'+counter+'"></li>';
					}
					carouselDisplay += ' id="'+item.name+'"><img src="'+item.path+'image1.jpg" style="height:100vh; width:100%;" alt="Slide1" class="carousel-image">'
					+'<div class="container"><div class="carousel-caption">'
					+'<h1>'+item.name+'</h1><p>'+item.description+'</p><p>'
					+'<a class="btn btn-lg btn-primary" href="#" role="button">Play Scene</a></p></div></div></div>';
					counter ++;
				} else {
					return false;
				}
			});
			if (counter == 0) {
				carouselDisplay += '<h1 class="text-center empty">NO AVAILABLE SCENE<br/>Please contact administrator to get scenes set up.</h1>';
			} else {
				window.location.reload(true);
				$('.carousel-indicators').html(indicator);
			}
			$('#sceneSelector').html(carouselDisplay);
		},
		error: function(xhr, desc, err) {
			console.log(xhr + "\n" + err);
		}
	});
});