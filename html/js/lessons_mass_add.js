$(function() {
/* Functions to verify if users really have the right values inserted */
$("#lesson_add_mass").prop("disabled", true);
	$("#user_error_check").on("click", function() {
		if ($("#lesson_add_mass").prop("disabled") == true) {
		ds = $("#date_start").val();
		de = $("#date_end").val();
		dsH = $("#date_start_h").val();
		deH = $("#date_end_h").val();
		
		mo = $("#lesson_monday").prop("checked");
		tu = $("#lesson_tuesday").prop("checked");
		we = $("#lesson_wednesday").prop("checked");
		th = $("#lesson_thursday").prop("checked");
		fr = $("#lesson_friday").prop("checked");
		
		
		if (mo == true) { mo_msg = "Maanantai "; } else { mo_msg = ""; }
		if (tu == true) { tu_msg = "Tiistai "; } else { tu_msg = ""; }
		if (we == true) { we_msg = "Keskiviikko "; } else { we_msg = ""; }
		if (th == true) { th_msg = "Torstai "; } else { th_msg = ""; }
		if (fr == true) { fr_msg = "Perjantai"; } else { fr_msg = ""; }
		
		message = "Vahvistus\n\n"
		message += "Lisää luennot väliltä: " + ds + " ~ " + de + "";
		message += "\n Joka: " + mo_msg + tu_msg + we_msg + th_msg + fr_msg;
		message += "\n Kello: " + dsH + " - " + deH;
		message += "\n » Voit nyt lähettää luennot palvelimelle.";
		
		
		alert(message);
		$("#lesson_add_mass").prop("disabled", false)
		} else {
		$("#lesson_add_mass").prop("disabled", true);	
		}
	});

});