<?php
session_start(); //Aloittaa session, jotta voidaan katsoa onko käyttäjä jo kirjautunut sisään
if (!isset($_SESSION['staff_id']) && !isset($_SESSION['staff_permlevel'])) { //Katsoo onko käyttäjä kirjautunut jo sisään
	if (isset($_POST['username']) && isset($_POST['password'])) { //Validoi onko post käyttäjänimi ja salasana syötetty
		$postun = $_POST['username']; //Formin input nimi ->$postun variableen
		$postpw = $_POST['password']; //Formin input salasana ->$postpw variableen
		if (strlen($postun) >= 3 && strlen($postun <= 255) && strlen($postpw) >= 5 && strlen($postpw) <= 200) { //Käyttäjänimen ja salasanan pituuden validointi
			if (filter_var($postun, FILTER_VALIDATE_EMAIL)) { //PHP:n oma email validointi username kenttään
				//Jos tietokantaa ei ole yhdistetty, tehdään yhdistys
				if (!isset($conn)) {
				include "inc/db_connect_inc.php";
				}
				//Hakee käyttäjän IP-osoiteen ja muuttaa sen jos käyttäjä avaa selaimen localhostista
				if ($_SERVER['REMOTE_ADDR'] == "::1") {
				$remIP = "localhost"; //Localhost IP:n manuaali-asetus
				} else {
				$remIP = $_SERVER['REMOTE_ADDR']; //Käyttjän IP-osoite
				}
				
				$curdate = date("Y-m-d H:i:s"); //Tämänhetkinen aika sekunttikohtaisesti
				$allowed_date = date("Y-m-d H:i:s", strtotime("-15 minutes")); //Ylempi - 15 minuuttia
					
				/* 
				--------------------------
				Anti Bruteforce validointi
				--------------------------
				Jos käyttäjä kirjautuu väärillä tunnuksilal sisään $rows_false_logins asetetun määrän yli,
				kirjautuminen evätään hakemalla tieto ca_staff_failed_login_log taulukosta.
				*/
				if ($res_get_false_logins = $conn->prepare("SELECT id FROM ca_staff_failed_login_log WHERE user_ip = ? AND dt > ?")) {
					$res_get_false_logins->bind_param("ss",$remIP,$allowed_date); //Asettaa hakuparametrin variablet 
					$res_get_false_logins->execute(); //Ajaa Haun
					$res_get_false_logins->store_result(); //Tallentaa haun tulokset
					$rows_false_logins = $res_get_false_logins->num_rows(); //Laskeen tallennetut tulokset
					$res_get_false_logins->free_result(); //Vapauttaa tulokset
					
					//Kirjautumis koodia jatketaan koska failed_logineita ei ole tarpeeksi
					if ($rows_false_logins < 5) {
						$post_vpa = hash("sha256", $postpw); //Salasana cryptataan sha256 muotoon
						$activate = 1; //Aktivoidun tunnuksen variable
						
						//Ajaa queryn joka validoi onko käyttäjätunnus ja cryptattu salasana sama kuin tietokannassa ja onko käyttäjä aktivoitu
						if ($res_gd = $conn->prepare("SELECT id, Permission, FirstName, LastName FROM ca_staff WHERE email = ? AND password = ? AND Active = ?")) {
						$res_gd->bind_param("ssi",$postun, $post_vpa, $activate); //Asettaa kirjautumis parametrit
							if ($res_gd->execute()) { //Ajaa Queryn asetetuilla paremetreillä
							$res_gd->store_result(); //Tallentaa tulokset
							$rows_gd = $res_gd->num_rows(); //Laskee löytyykö kyseistä käyttäjää
								if ($rows_gd == 1) { //Käyttäjä löytyi -> Tallentaa tiedot sessioon.
								$res_gd->bind_result($id,$perms,$firstname,$lastname);
								$res_gd->fetch();
								$_SESSION['staff_id'] = $id;
								$_SESSION['staff_permlevel'] = $perms;
								$_SESSION['staff_fullname'] = $firstname . " " . $lastname;
								header("Location: list_room_logs.php"); //Uudelleenohjaa käyttäjän.
								} else {
									//Käyttäjänimi tai salasana oli väärä, asetetaan tieto failed_login kantaan
									if ($res_insert_falselogin = $conn->prepare("INSERT INTO ca_staff_failed_login_log (user_ip, dt) VALUES (?,?)")) {
										$res_insert_falselogin->bind_param("ss", $remIP, $curdate);
										$res_insert_falselogin->execute();
										echo "Kirjautuminen epäonnistui: " . $rows_false_logins . "/5 kertaa. (Jos tämä ylittyy, kirjautuminen evätään 15 minuutin ajaksi ensimmäisestä kirjautumisesta)";
									} else {
									/* 
									Virhe prepared statementistä, mitään viestiä ei ilmoiteta, koska virhe
									kyselyssä.
									
									Lisäinfo: ERROR IN PREPARE (INSERT) -> SQL MISTAKE (failed to insert false login data)
									*/
									}
								}
							$res_gd->free_result(); //Vapauttaa tulokset
							} else {
							/* 
							Virhe prepared statementistä, mitään viestiä ei ilmoiteta, koska virhe
							kyselyssä.
									
							Lisäinfo: $res_gd->execute()
							*/
							}
						} else {
						/* 
						Virhe prepared statementistä, mitään viestiä ei ilmoiteta, koska virhe
						kyselyssä.
							
						Lisäinfo: ERROR IN PREPARE (INSERT) -> SQL MISTAKE (failed to get account data)
						*/
						}				
					} else {
					//Anti-bruteforce iskee -> käyttäjä kirjautunut väärillä tunnuksilla yli X määrän
					echo "<p>Liian monta virheellistä kirjautumista, ole hyvä ja yritä uudelleen 15 minuutin kuluttua.</p>";	
					}
				} else {
				/* 
				Virhe prepared statementistä, mitään viestiä ei ilmoiteta, koska virhe
				kyselyssä.
					
				Lisäinfo: ERROR IN PREPARE (SELECT) -> SQL MISTAKE (failed to select failed login data)
				*/
				}
			include "inc/db_disconnect_inc.php"; //Sulkee tietokannan
			} else {
			//Filter_validate ei päässyt läpi
			echo "<p>Sähköposti väärässä muodossa.</p>";	
			}
		} else {
		//Käyttäjänimen ja salasanan pituuden
		echo "<p>Käyttäjänimen tai salasanan pituus väärä. Käyttäjänimi 3-255, Salasana 5-200.";	
		}		
	} else {
	//Ei tunnistettu käyttäjänimen tai salasanan syöttöä
	echo "<p>Ei tunnistettu käyttäjänimen tai salasanan syöttöä.</p>";	
	}
} else {
//Käyttäjä on kirjautunut jo sisään, uudelleen ohjaa index.php sivulle.
header("Location: list_room_logs.php");
echo "<p>Olet jo kirjautunut sisään, mikäli sivusto ei uudelleenohjaa paina <a href='list_room_logs.php'>tästä</a>.</p>";
}
?>