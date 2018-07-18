//Requires jQuery library to work, change utag, ptag and loginsubmit to related form fields.
$(function() {
	loginsubmit = $("#loginsubmit"); //Login submit element (input type=submit)
	loginsubmit.prop("disabled", true); //Disables the submit button untill proper validation is done
	//On username, password input field keypress -> run function
	$("#username, #password").on("keyup", function() {
	utag = $("#username"); //Gets element data from input where id = username
	ptag = $("#password"); //Gets element data from input where id = password
	passlen = ptag.val().length; //Gets password length
	regexp = /[a-zA-Z0-9@.]+/; //Regex pattern allow any characters from (a-z A-Z and 0-9).
	matches = utag.val().match(regexp); //Validates username with regexp
	lp1 = false; //Login pass -> (username)
	lp2 = false; //Login pass -> (password)
	
		if (matches != null) { //Checks if matches is null (incase user starts from password field instead of username)
		userlen = matches[0].length; //Gets updated username length
		$("#username").val(matches); //Removes any excess characters
			if (userlen >= 5 && userlen <= 320) { //Username between 5 and 320 characters -> depending if email is login tool
			utag.css({"color":"green"}); //Makes textarea text green indicating everything is fine
			lp1 = true; //Login validate = pass
			} else {
			utag.css({"color":"red"}); //Makes textarea text red indicating something is wrong
			lp1 = false; //Login validate = fail
			}
		}
			
		//Same as above, but for password
		if (passlen >= 5 && passlen <= 150) {
		ptag.css({"color":"green"});
		lp2 = true; //Password validate = pass
		} else {
		ptag.css({"color":"red"});
		lp2 = false; //Password validate = fail
		}
		
		//Both login and password validations pass -> enable submit
		if (lp1 == true && lp2 == true) {
		loginsubmit.prop("disabled", false);	
		} else {
		//Disable submit
		loginsubmit.prop("disabled", true);	
		}
	});
});