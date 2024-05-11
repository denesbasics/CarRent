<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu" lang="hu">
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<?php
		include 'Opendb.php';
		if ( isset($_POST['return']) ){
			$link = getDb();

		$error = false; // még nincs hiba
		$autoId = $_POST['autoId'];
			if (preg_match("/^[0-9]+$/",$autoId) == false) { echo "Helytelen Autó Id megadás."; $error = true; }
		$ugyfelId = $_POST['ugyfelId'];
			if (preg_match("/^[0-9]+$/",$ugyfelId) == false) { echo "Helytelen Ügyfél Id megadás."; $error = true; }
		$visszahozta = $_POST['returndate'];
			if (preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/",$visszahozta) == false) { echo "Helytelen Visszahozatali Dátum megadás."; $error = true; }

			// Van e egyáltalán ilyen kölcsönzés
			$BenneVan = mysqli_query($link, 
				"SELECT autoId, ugyfelId
				 FROM kolcsonzes
				 WHERE kolcsonzes.ugyfelId = '$ugyfelId' AND kolcsonzes.autoId = '$autoId'") or 
			die("Query Hiba1: Nem sikerült ilyen Id párt találni a kölcsonzésben.");

			$error = true; // Nem tudjuk hogy benne van az ügyfél úgyhogy addig hibásnak vesszük
			while($Id = mysqli_fetch_array($BenneVan))
			{
				if(($Id['ugyfelId'] == $ugyfelId) &&($Id['autoId'] == $autoId)) { $error = false; }
			}
			if($error){ echo "Hiba a megfelelő kölcsönzés megtalálásakor.";}			

		//Problémát észlelünk akkor kilépünk
		if ($error){ exit();}


		$query = sprintf("UPDATE kolcsonzes
							SET kolcsonzes.visszahozta = '%s'
							WHERE kolcsonzes.autoId = '$autoId' AND kolcsonzes.ugyfelId = '$ugyfelId'"
				, mysqli_real_escape_string($link, $visszahozta));
		mysqli_query($link, $query) or die("Query Hiba2: Nem sikerült a kölcsönzés adatainak a frissítése");
									 
		mysqli_close($link);
		header("Location: Admin.php");
	}
?>
</html>