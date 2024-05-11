<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu" lang="hu">
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<?php
	// Auto Update
	include 'Opendb.php';
	if (isset($_POST['insert']) ){
		$link = getDb();
		
		$error = false;
		//Helytelen megadás hibakezelés és változókba mentés
		//Regex helyesek e a formátumok
		$rendszam = $_POST['rendszam'];
			if (preg_match("/^[A-Z]{3}[0-9]{3}$/",$rendszam) == false) { echo "Helytelen Rendszám megadás."; $error = true; }
			//if ( $rendszam == NULL) { $rendszam = $Id['rendszam'];} most újra be kell írni nem tudom hogy leellenőrizni mi van benne és mi nincs
		$Kaukcio = $_POST['Kaukcio'];
			if (preg_match("/^[0-9]+$/",$Kaukcio) == false) { echo "Helytelen Kaució megadás."; $error = true; }
			//if ( $Kaukcio == NULL) { $Kaukcio = $Id['Kaukcio'];}
		$napiAr = $_POST['napiAr'];
			if (preg_match("/^[0-9]+$/",$napiAr) == false) { echo "Helytelen Napi Ár megadás."; $error = true; }
			//if ( !isset($napiAr)) { $napiAr = $Id['napiAr'];}
		$leiras = $_POST['leiras'];
			//if ( !isset($_POST['leiras'])) { $leiras = $Id['leiras']; echo "bent vagyok";}

		//Problémát észlelünk akkor kilépünk
		if ($error){ exit();}

		
		//Adatbázisba másolás ügyfél adatai
		//MySQL injection ellen védekezés
		$query = sprintf("INSERT INTO auto(rendszam,Kaukcio,napiAr,leiras)
							VALUES ('%s','$Kaukcio','$napiAr','%s')"
				, mysqli_real_escape_string($link, $rendszam)
				, mysqli_real_escape_string($link, $leiras));
		mysqli_query($link, $query) or die("Query Hiba2: Nem sikerült az autó adatainak a frissítése");
									 
		mysqli_close($link);
		header("Location: Admin.php");
	}
?>
</html>