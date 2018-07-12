//Requires jQuery library to work, change utag and ptag to referral if required.
$(function() {
	$("loginsubmit").prop("disabled", true); //Disables the submit button untill proper validation is done
	//On username, password input field keypress -> run function
	$("#username, #password").on("keyup", function() {
	utag = $("#username"); //Creates user id input to variable
	ptag = $("#password"); //Creates password id input to variable
	userlen = utag.val().length; //Gets username length
	passlen = ptag.val().length; //Gets password length
	regexp = /[a-zA-Z0-9]+/; //Regex pattern allow any characters from (a-z A-Z and 0-9).
	matches = utag.val().match(regexp); //Validates username with regexp
	userlen = matches[0].length; //Gets updated username length
	$("#username").val(matches); //Removes any excess characters
		if (userlen >= 5 && userlen <= 320) { //Username between 5 and 320 characters -> depending if email is login tool
		utag.css({"color":"green"}); //Makes textarea text green indicating everything is fine
		} else {
		utag.css({"color":"red"}); //Makes textarea text red indicating something is wrong
		}
		//Same as above, but for password
		if (passlen >= 5 && userlen <= 150) {
		ptag.css({"color":"green"});
		} else {
		ptag.css({"color":"red"});
		}
	});
});