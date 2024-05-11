<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu" lang="hu">
<head>  
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />  
<h1>Admin kezelőfelület</h1>
</head>
<body>
<link rel="stylesheet" type="text/css" href="Admin.css">

<div>

	
	<div class="box-1">
		<!-- Autók adatainak Módosítás-->
		<div class="box-1-1">
		<form method="post" action="AutoUpdate.php">
			
			<div class="formkinezet"><p class="formcim">Autó Módosítása</p>
			<p>Autó Azonosító ID:
			<input type="text" class="kitoltosav" name="autoId" placeholder="Egy egész szám"></p>
			<p>Autó Rendszám:
			<input type="text" class="kitoltosav" name="rendszam" placeholder="AAA111"></p>
			<p>Autó Kaució:
			<input type="text" class="kitoltosav" name="Kaukcio" placeholder="Ft-ban"></p>
			<p>Autó Napi Ár:
			<input type="text" class="kitoltosav" name="napiAr" placeholder="Ft / nap"></p>
			<p>Autó Leírás:
			<input type="text" class="kitoltosav" name="leiras" placeholder="További információk"></p>
			<p class="comment">(Pl. Típus: Ferrari Fogyasztás: 12l/100km Évjárat: 1999 Személyekszáma: 2)</p>
			<input type="submit" class="kitoltosavgomb" name="update" value="Autó Módosítás">
			</div>
		</form>
		</div>

		<!-- Új autó felvétele -->
		<div class="box-1-2">
			<form method="post" action="AutoInsert.php">
				
				<div class="formkinezet"><p class="formcim">Új autó felvétele</p>
				<p>Autó Rendszám:
				<input type="text" class="kitoltosav" name="rendszam" placeholder="AAA111"></p>
				<p>Autó Kaució:
				<input type="text" class="kitoltosav" name="Kaukcio" placeholder="Ft-ban"></p>
				<p>Autó Napi Ár:
				<input type="text" class="kitoltosav" name="napiAr" placeholder="Ft / nap"></p>
				<p>Autó Leírás:
				<input type="text" class="kitoltosav" name="leiras" placeholder="További információk"></p>
				<p class="comment">(Pl. Típus: Ferrari Fogyasztás: 12l/100km Évjárat: 1999 Személyekszáma: 2)</p>
				<input type="submit" class="kitoltosavgomb" name="insert" value="Autó Felvétel">
				</div>
			</form>
		</div>
	</div>	
	
	<div class="box-2">		
		<!-- Visszahozta az autót -->
		<div class="box-2-1">
		<form method="post" action="Visszahoz.php">
			
			<div class="formkinezet"><p class="formcim">Visszahozatal mentés</p>
			<p>Autó Id:
			<input type="text" class="kitoltosav" name="autoId" placeholder="Autó Id azonosítója" required></p>
			<p>Ügyfél Id:
			<input type="text" class="kitoltosav" name="ugyfelId" placeholder="Ügyfél Id azonosítója" required></p>
			<p>Visszahozatal Dátuma:
			<input type="text" class="kitoltosav" name="returndate" placeholder="1999-11-21" required></p>
			<input type="submit" class="kitoltosavgomb" name="return" value="Visszahozta">
			</div>
		</form>
		</div>

		<!-- Autók / Ügyfelek kilistázása -->
		<div class="box-2-2">
		<form method="post" action="#tabla"> <!-- Odaugrik a táblához mert a táblák id-je #tabla -->
			
			<div class="formkinezet"><p class="formcim">Összes auto vagy ügyfél kilistázása</p>
				<p  class="radiobox">Összes Autó
				<input type="radio" name="kereses" value="auto" checked></p>
				<p  class="radiobox">Összes Ügyfél
				<input type="radio" class="radiobox" name="kereses" value="ugyfel"></p>
				<input type="submit" class="kitoltosavgomb" name="all" value="Kilistázás">
			</div>
		</form>

		</div>
		
		<!-- Törlés -->
		<div class="box-2-3">
		<form method="post" action="AutoDelete.php">
			
			<div class="formkinezet"><p class="formcim">Autó vagy Ügyfél törlés az Adatbázisból</p>
			
			<p>Autó Id:
				<span class="kettoinput" >
				<input type="checkbox" name="auto_torles" value="auto">
				<input type="text" name="autoId" placeholder="Autó Id azonosítója">
				</span>
			</p>

			<p>Ügyfél Id:
				<span class="kettoinput" >
				<input type="checkbox" name="ugyfel_torles" value="ugyfel">
				<input type="text" name="ugyfelId" placeholder="Ügyfél Id azonosítója">
				</span>
			</p>
			<input type="submit" class="kitoltosavgomb" name="delete" value="Törlés">
			</div>
		</form>
		</div>
	</div>

</div>
<div class="container">
	<a href="kezdolap.html"><img src="Home-icon.png" class="kezdolapgomb" name="HOME Gomb" value="home">
	<div class="overlay">
	<div class="kezdolaptext">Vissza a</br>Kezdőlapra</br>⇒</div>
	</div></a>
</div>
<!-- Kilistázás, a kapott tömbon végig megyünk -->
<?php
	if(isset($_POST['all'])) {
	include 'Opendb.php';
	$link = getDb();
	$kereses = $_POST['kereses'];

	switch ($kereses) {
	 	case 'auto':
	 		$eredmeny = mysqli_query($link,
	 				"SELECT *
					 FROM auto") or die ("Nem sikerült lekérdezni az összes autót.");
	 		break;
	 	case 'ugyfel':
	 		$eredmeny = mysqli_query($link,
	 				"SELECT *
					 FROM ugyfel") or die ("Nem sikerült lekérdezni az összes ügyfelet.");
	 		break;
	 	default:
	 		break;	 		
	}
}
?>
<?php if(isset($_POST['all'])){
	//if(isset($_POST['all'])) { $eredmeny = kereseseredmenye($_POST['kereses']);} 
	switch ($kereses) {
	case 'auto':?>
		<table id="tabla">
            <tr>
            	<th>Autó Id</th>
                <th>Leírás</th>
                <th>Rendszám</th>
                <th>Napi Ár(Ft/nap)</th>
                <th>Kaució(Ft)</th>     
            </tr> 
			<?php
			if(isset($_POST['all'])){
			while ($row = mysqli_fetch_array($eredmeny)): ?>
					<td>
						<?=$row['Id'] ?>
					</td>
					<td>
						<?=$row['leiras'] //echo vagy printf is jó lenne?>
					</td>
					<td>
						<?=$row['rendszam']?>
					</td>
					<td>
						<?=$row['napiAr']?>
					</td>
					<td>
						<?=$row['Kaukcio']?>
					</td>
				</tr>
			<?php endwhile;} ?>
        </table>   
        <?php
		break;
	case 'ugyfel':?>
		<table id="tabla">
            <tr>
                <th>Ügyfél Id</th>
                <th>Anyjaneve</th>
                <th>Születésnap</th>
                <th>Keresztnév</th>
                <th>Vezetéknév</th>
                <th>Lakcím</th>
                <th>Sz.Igaz.Szám</th>
                <th>Adószám</th>     
            </tr> 
			<?php
			if(isset($_POST['all'])){
			while ($row = mysqli_fetch_array($eredmeny)): ?>
					<td>
						<?=$row['Id'] //echo vagy printf is jó lenne?>
					</td>
					<td>
						<?=$row['anyjaneve']?>
					</td>
					<td>
						<?=$row['szuletesnap']?>
					</td>
					<td>
						<?=$row['keresztnev']?>
					</td>
					<td>
						<?=$row['vezeteknev']?>
					</td>
					<td>
						<?=$row['lakcim']?>
					</td>
					<td>
						<?=$row['sziszam']?>
					</td>
					<td>
						<?=$row['adoszam']?>
					</td>
				</tr>
			<?php endwhile; }?>
        </table>
        <?php
		break;
	default:
		break;
	
	}
}
?>
</body>
</html>
