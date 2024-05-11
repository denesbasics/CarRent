<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu" lang="hu">
<link rel="stylesheet" type="text/css" href="Berles.css">
<head>  
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />  
<h1>Bérlés</h1>
</head>
    <body>
    	<a href="kezdolap.html"><img src="Home-icon.png" class="kezdolapgomb" ></a>
        <form action="Berles.php" method="post">
            <div class="formkinezet"><p>
                Vezetéknév: <input class="kitoltosav" type="text" name="keresztnev" required />    
            </p>
            <p>
                Keresztnév: <input class="kitoltosav" type="text" name="vezeteknev" required />    
            </p>
            <p>
                Anyja neve: <input class="kitoltosav" type="text" name="anyja" required />    
            </p>
            <p>
                Születésnap: <input class="kitoltosav" type="text" placeholder="pl. 1993-06-21" name="szuletesnap" required />    
            </p>
            <p>
                Lakcím: <input class="kitoltosav" type="text" name="lakcim" required />    
            </p>
            <p>
                Személyi igazolványszám: <input class="kitoltosav" type="text" placeholder="222222AA" name="sziszam" required />    
            </p>
            <p>
                Adószám: <input class="kitoltosav" type="text" placeholder="12345678901" name="adoszam" required />    
            </p>
            
            <p style="display:inline" >Válasszon Autót:</p>
            <!-- Autó rendszámainak lekérdezése a legördülő listához -->
            <select class="kitoltosav" name="auto">
            	<?php
            		include 'Opendb.php';
            		$link = getDb(); // A lekérdezésnél el kellett nevezni az auto.Id-t egyszerűbben hivatkozható formára
            		$link->set_charset("utf8");
            		$osszes_auto = mysqli_query($link, 
					"SELECT DISTINCT auto.Id AS Id, auto.rendszam AS rendszam
							 FROM auto
							 LEFT OUTER JOIN kolcsonzes kcs ON kcs.autoId = auto.Id 
							 WHERE kezd IS NULL OR (kezd IS NOT NULL and visszahozta IS NOT NULL)") or die("Query Hiba: nem olvasta ki az összes autót.");
            	 ?><?php
            	 while ($row = mysqli_fetch_array($osszes_auto)): ?>
					<option value="<?=$row['Id']?>" ><?=$row['rendszam']?></option>

            	
				<?php endwhile; mysqli_close($link);  ?>
			</select>
            <p>
                Időtartam Napban: <input class="kitoltosav" type="text" placeholder="7" name="lejar" required />    
            </p>
            <p> 
                <input class="kitoltosav" type="submit" value="Elküld" name="uj" />
            </p></div>
        </form>
    </body>
    <footer>Készítette:</br>Lengyel Dénes </br>NK</footer>
</html>

<?php
	// már van ilyen ügyfél beregisztrálva esettre nincs felkészülve mondjuk úgy is működik csak külön személynek veszi
	if (isset($_POST['uj']) and isset($_POST['keresztnev']) and isset($_POST['vezeteknev']) and isset($_POST['anyja']) and isset($_POST['lejar'])
		 and isset($_POST['szuletesnap']) and isset($_POST['lakcim']) and isset($_POST['sziszam']) and isset($_POST['adoszam']) ){
		$link = getDb();
		
		$error = false;
		//Helytelen megadás hibakezelés és változókba mentés
		//Regex helyesek e a formátumok
		$keresztnev = $_POST['keresztnev'];
		$vezeteknev = $_POST['vezeteknev'];
		$anyja = $_POST['anyja'];
		$szuletesnap = $_POST['szuletesnap'];
			if (preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/",$szuletesnap) == false) { echo "Helytelen Születésnap megadás."; $error = true; }
		$lakcim = $_POST['lakcim'];
		$sziszam = $_POST['sziszam'];
			if (preg_match("/^[0-9]{6}[A-Z]{2}$/",$sziszam) == false) { echo "Helytelen Személyi igazolványszám megadás."; $error = true; }
		$adoszam = $_POST['adoszam'];
			if (preg_match("/^[0-9]{11}$/",$adoszam) == false) { echo "Helytelen adószam megadás."; $error = true; }
		$lejar = $_POST['lejar'];
			if (preg_match("/^[0-9]{2}$/",$lejar) == false) { echo "Helytelen Bérlési időtartam megadás."; $error = true; }
		//Helytelen autovalasztas kezelés és változóba mentés
		//Van e szabad auto és ha igen amit választott az szabad e
		$auto = $_POST['auto'];
		$szabad_autok = mysqli_query($link, 
			"SELECT auto.Id
			 FROM auto
			 LEFT OUTER JOIN kolcsonzes kcs ON kcs.autoId = auto.Id 
			 WHERE kezd IS NULL") or die("Query Hiba1: szabad_autok meghatározásánál.");
			 // OUTER JOIN hogy legyenek benne azok az autok is amik még nem voltak kikölcsönözve
		$error = true; // Nem tudjuk hogy benne van a kocsi úgyhogy addig hibásnak vesszük
		while($Id = mysqli_fetch_array($szabad_autok))
		{
			if($Id['Id'] == $auto) { $error = false; }
		}
		if($error){ echo "Hiba a kocsi Id olvasáskor"; exit();}
		
		//Adatbázisba másolás ügyfél adatai
		$query = sprintf("INSERT INTO ugyfel(keresztnev,vezeteknev,anyjaneve,szuletesnap,lakcim,sziszam,adoszam) 
							VALUES('%s','%s','%s','%s','%s','%s','%s')"
				, mysqli_real_escape_string($link, $keresztnev)
				, mysqli_real_escape_string($link, $vezeteknev)
				, mysqli_real_escape_string($link, $anyja)
				, mysqli_real_escape_string($link, $szuletesnap)
				, mysqli_real_escape_string($link, $lakcim)
				, mysqli_real_escape_string($link, $sziszam)
				, mysqli_real_escape_string($link, $adoszam));
		mysqli_query($link, $query) or die("Query Hiba2: Ügyfelet nem tudtuk beírni az Adatbázisba.");
		//UgyfelId-t ki kell nyerni
		$eredmeny = mysqli_query($link, "SELECT Id FROM ugyfel WHERE keresztnev = '$keresztnev' AND vezeteknev = '$vezeteknev' AND sziszam = '$sziszam'")
				or die("Query Hiba3: Ügyfél Id lekérdezés.");
		//Az eredmény tömbböt át kell adni egy változónak
		$x = mysqli_fetch_array($eredmeny);
		//A tömbből a megfelelő elemet ki kell venni
		$ugyfel = $x['Id'];
		//Valasztott autohoz linkeles kölcsönzés
		mysqli_query($link, "INSERT INTO kolcsonzes(autoId, ugyfelId, kezd, lejar, visszahozta)
								VALUES ('$auto','$ugyfel', CURDATE(),DATE_ADD(CURDATE(),INTERVAL '$lejar' DAY), NULL )")
									 or die("Query Hiba4: kölcsönzés felvételével.");
		//mysqli_free_result($szabad_autok);
		mysqli_free_result($link, $eredmeny);
		mysqli_free_result($link, $szabad_autok);									 
		mysqli_close($link);
		header("Location: Berles.php");
	}
?>