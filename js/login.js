
$(document).ready(function() {
	$.ajax({
		url: 'php/login_check.php',
		type: 'post',
		success: function(json) {
			var jsonObject = jQuery.parseJSON(json);
			console.log ("Result is: " + jsonObject.result);
			if (jsonObject.result === "true") {
				$("#signinForm").hide();
				$("#signOut").show();
			} else {
				$("#signinForm").show();
				$("#signOut").hide();
			}
		},
		error: function(xhr, desc, err) {
			console.log(xhr + "\n" + err);
		}
	});
	$("#logout").click(function() {
		$.ajax({
			url: 'php/logout.php',
			type: 'post',
			success: function() {
				console.log ("Successfully logged out");
				window.location.reload(true);
			},
			error: function(xhr, desc, err) {
				console.log(xhr + "\n" + err);
			}
		});
	});
});
function formhash(form, username, password) {
	//if (username.length > 0) {
	    var p = document.createElement("input");

	    form.appendChild(p);
	    p.name = "hashedPassword";
	    p.type = "hidden";
	    p.value = hex_sha512(password.value);

	    password.value = "";
	 
	    form.submit();
	//}	
}