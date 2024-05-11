<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu" lang="hu">
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<?php
	// Auto Delete
	include 'Opendb.php';
	if (isset($_POST['delete']) ){
		$link = getDb();
		
		$error = false;		
		$torles = 'nincs';
		if(isset($_POST['auto_torles'])){ $torles = $_POST['auto_torles']; } else { echo "Nem választotta ki az auto törlést.";}
		if($torles == 'auto'){
		$autoId = $_POST['autoId'];
			if (preg_match("/^[0-9]+$/",$autoId) == false) { echo "Helytelen Autó Id megadás."; $error = true; }

			//Meg kell nézni hogy a módosítani kívánt Auto Id benne van e az adatbáziban
			$BenneVan = mysqli_query($link, 
				"SELECT auto.Id
				 FROM auto
				 WHERE auto.Id = '$autoId'") or die("Query Hiba1: Nem sikerült az autó Id lekérdezése");

			$error = true; // Nem tudjuk hogy benne van az autó úgyhogy addig hibásnak vesszük
			while($Id = mysqli_fetch_array($BenneVan))
			{
				if($Id['Id'] == $autoId) { $error = false; }
			}
			if($error){ echo "Hiba az autó Id olvasáskor";}

		//Problémát észlelünk akkor kilépünk
		if ($error){ exit();}
		//MySQL injection ellen védekezés
		$autoId = mysqli_real_escape_string($link,$autoId);

		//Adatbázisból az autóhoz köthető kölcsönzések törlése
		mysqli_query($link, "DELETE 
							FROM kolcsonzes
							WHERE kolcsonzes.autoId = '$autoId'") or 
		die("Query Hiba2: Nem sikerült a kölcsönzések törlése.");
		
		//Adatbázisból az autóhoz köthető karbantartások törlése
		mysqli_query($link, "DELETE 
							FROM karbantart
							WHERE karbantart.autoId = '$autoId'") or
		die("Query Hiba3: Nem sikerült karbantartások törlése.");
		
		//Maga az autó törlése
		mysqli_query($link, "DELETE 
							FROM auto
							WHERE auto.Id = '$autoId'") or 
		die("Query Hiba4: Nem sikerült az autó adatainak törlése.");
		}
		
		

		$torles = 'nincs';		
		if(isset($_POST['ugyfel_torles'])){ $torles = $_POST['ugyfel_torles']; } else { echo "Nem választotta ki az ügyfél törlést.";}		
		if($torles == 'ugyfel'){
		$ugyfelId = $_POST['ugyfelId'];
			if (preg_match("/^[0-9]+$/",$ugyfelId) == false) { echo "Helytelen Ügyfél Id megadás."; $error = true; }

			//Meg kell nézni hogy a módosítani kívánt Ügyfél Id benne van e az adatbáziban
			$BenneVan = mysqli_query($link, 
				"SELECT ugyfel.Id
				 FROM ugyfel
				 WHERE ugyfel.Id = '$ugyfelId'") or die("Query Hiba1: Nem sikerült az ügyfél Id lekérdezése");

			$error = true; // Nem tudjuk hogy benne van az ügyfél úgyhogy addig hibásnak vesszük
			while($Id = mysqli_fetch_array($BenneVan))
			{
				if($Id['Id'] == $ugyfelId) { $error = false; }
			}
			if($error){ echo "Hiba az ügyfél Id olvasáskor";}

		//Problémát észlelünk akkor kilépünk
		if ($error){ exit();}
		//MySQL injection ellen védekezés
		$ugyfelId = mysqli_real_escape_string($link,$ugyfelId);

		//Adatbázisból az ügyfélhez köthető kölcsönzések törlése
		mysqli_query($link, "DELETE 
							FROM kolcsonzes
							WHERE kolcsonzes.ugyfelId = '$ugyfelId'") or 
		die("Query Hiba5: Nem sikerült a kölcsönzések törlése.");
		
		//Maga az ügyfél törlése
		mysqli_query($link, "DELETE 
							FROM ugyfel
							WHERE ugyfel.Id = '$ugyfelId'") or 
		die("Query Hiba6: Nem sikerült az ügyfél adatainak törlése.");
		}

		mysqli_close($link);
		header("Location: Admin.php");

	}				
?>
</html>