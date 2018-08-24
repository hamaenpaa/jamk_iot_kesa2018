<?php
include("db_connect_inc.php"); 

if (!isset($_SESSION)) {
	session_start();	
} 
if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_permlevel'])) { //Katsoo onko käyttäjä kirjautunut jo sisään
	$validate_info = validate();
	if ($validate_info["passed"]) {
		if (checkLogin($conn,$validate_info["postun"],$validate_info["postpw"])) {
			echo "here189273489127378912739129839128";
			include("db_disconnect_inc.php"); 
			header("Location: ../index.php");
		}
	}
} else {
	//Käyttäjä on kirjautunut jo sisään, uudelleen ohjaa index.php sivulle.
	header("Location: ../index.php");
	include("db_disconnect_inc.php"); 
	echo "<p>Olet jo kirjautunut sisään, mikäli sivusto ei uudelleenohjaa paina <a href='index.php'>tästä</a>.</p>";
}

function validate() {
	$postun = "";
	$postpw = "";
	if (isset($_POST['username']) && isset($_POST['password'])) { //Validoi onko post käyttäjänimi ja salasana syötetty
		$postun = $_POST['username']; //Formin input nimi ->$postun variableen
		$postpw = $_POST['password']; //Formin input salasana ->$postpw variableen
		if (strlen($postun) >= 3 && strlen($postun <= 255) && strlen($postpw) >= 5 && strlen($postpw) <= 200) { //Käyttäjänimen ja salasanan pituuden validointi
			$passed = true;
		} else {
			//Käyttäjänimen ja salasanan pituuden
			echo "<p>Käyttäjänimen tai salasanan pituus väärä. Käyttäjänimi 3-255, Salasana 5-200.";
			$passed = false;
		}		
	} else {
		//Ei tunnistettu käyttäjänimen tai salasanan syöttöä
		echo "<p>Ei tunnistettu käyttäjänimen tai salasanan syöttöä.</p>";	
		$passed = false;
	}	
	return array("passed" => $passed, "postun" => $postun, "postpw" => $postpw);
}


function checkLogin($conn,$postun,$postpw) {
	echo "dadaaaa";
	// Find user IP or check localhost
	if ($_SERVER['REMOTE_ADDR'] == "::1") {
		$remIP = "localhost"; //Localhost IP:n manuaali-asetus
	} else {
		$remIP = $_SERVER['REMOTE_ADDR']; //Käyttjän IP-osoite
	}
				
	$curdate = date("Y-m-d H:i:s"); //Tämänhetkinen aika sekunttikohtaisesti
	$allowed_date = date("Y-m-d H:i:s", strtotime("-15 minutes")); //Ylempi - 15 minuuttia	
				
	$passed = false;
	$rows_false_logins = 0;
	$too_many_error_logins = false;
	
	/* 
		--------------------------
		Anti Bruteforce validointi
		--------------------------
		Jos käyttäjä kirjautuu väärillä tunnuksilal sisään $rows_false_logins asetetun määrän yli,
		kirjautuminen evätään hakemalla tieto ca_staff_failed_login_log taulukosta.
	*/
	if ($res_get_false_logins = $conn->prepare("SELECT id FROM ca_user_failed_login_log WHERE user_ip = ? AND dt > ?")) {
		$res_get_false_logins->bind_param("ss",$remIP,$allowed_date); //Asettaa hakuparametrin variablet 
		$res_get_false_logins->execute(); //Ajaa Haun
		$res_get_false_logins->store_result(); //Tallentaa haun tulokset
		$rows_false_logins = $res_get_false_logins->num_rows(); //Laskeen tallennetut tulokset
		$res_get_false_logins->free_result(); //Vapauttaa tulokset
					
		//Kirjautumis koodia jatketaan koska failed_logineita ei ole tarpeeksi
		if ($rows_false_logins < 5) {
			$post_vpa = hash("sha256", $postpw); //Salasana cryptataan sha256 muotoon
						
			//Ajaa queryn joka validoi onko käyttäjätunnus ja cryptattu salasana sama kuin tietokannassa ja onko käyttäjä aktivoitu
			if ($res_gd = $conn->prepare("SELECT id, Permission FROM ca_user WHERE username = ? AND password = ?")) {
				$res_gd->bind_param("ss",$postun, $post_vpa); //Asettaa kirjautumis parametrit
				if ($res_gd->execute()) { //Ajaa Queryn asetetuilla paremetreillä
					$res_gd->store_result(); //Tallentaa tulokset
					$rows_gd = $res_gd->num_rows(); //Laskee löytyykö kyseistä käyttäjää
					if ($rows_gd == 1) { //Käyttäjä löytyi -> Tallentaa tiedot sessioon.
						$res_gd->bind_result($id,$perms);
						$res_gd->fetch();
						$_SESSION['user_id'] = $id;
						$_SESSION['user_permlevel'] = $perms;
						$passed = true;
						echo "hereasdasdasdasdasdasdasdasdasdasdasda";
					} else {
						echo "klfjasdkljfl";
						//Käyttäjänimi tai salasana oli väärä, asetetaan tieto failed_login kantaan
						if ($res_insert_falselogin = $conn->prepare("INSERT INTO ca_user_failed_login_log (user_ip, dt) VALUES (?,?)")) {
							$res_insert_falselogin->bind_param("ss", $remIP, $curdate);
							$res_insert_falselogin->execute();
							$rows_false_logins++;
							echo "Kirjautuminen epäonnistui: " . $rows_false_logins . "/5 kertaa. ";
							if ($rows_false_logins < 5) {
								echo "(Jos tämä ylittyy, kirjautuminen evätään 15 minuutin ajaksi ensimmäisestä kirjautumisesta)";
							}
							else {
								echo "Kirjautuminen evätään 15 minuutin ajaksi ensimmäisestä kirjautumisesta.";
							}
						} else {
								echo "hmmmm2...";
						}
					}
					$res_gd->free_result(); //Vapauttaa tulokset
				} else {
					echo "lajsfzkldfj";
				}
			} else {
				echo "hmmmm...";
			}
		} else {
			$too_many_error_logins = true;
			//Anti-bruteforce iskee -> käyttäjä kirjautunut väärillä tunnuksilla yli X määrän
			echo "<p>Liian monta virheellistä kirjautumista, ole hyvä ja yritä uudelleen 15 minuutin kuluttua.</p>";	
		}  
			
	} else {
		echo "sdklasöldköl";
	}
	return $passed;	
}

?>