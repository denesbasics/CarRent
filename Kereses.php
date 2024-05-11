<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu" lang="hu">
<head>  
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />  
<h1 >Keressen a szabad autók között</h1>
</head>
	<body>
		<a href="kezdolap.html"><img src="Home-icon.png" class="kezdolapgomb" ></a>
		<link rel="stylesheet" href="Kereses.css">
		<!-- Gomb kódja: http://codepen.io/rocbear/pen/mwquv innen megszerkesztve -->
		<form method="post" action="Kereses.php">
			<p  class="radiobox">Napi árral Növekvő sorrendben
			<input type="radio" name="kereses" value="nnapi" checked></p>
			<p  class="radiobox">Kaució Csökkenő sorrendben
			<input type="radio" class="radiobox" name="kereses" value="cskau"></p>
			<p  class="radiobox">Összes foglalt autók
			<input type="radio" class="radiobox" name="kereses" value="foglalt"></p>
			<p  class="radiobox">Összes szabad autó
			<input type="radio" class="radiobox" name="kereses" value="all"></p></br>
			<div class="checkboxstyle">
				<p class="checkfonts">Porsche
				<input style="padding-right: 10px;" type="checkbox" name="vehicle1" value="Porsche"></p>
				<p class="checkfonts">Opel
				<input type="checkbox" name="vehicle2" value="Opel"></p>
				<p class="checkfonts">Ferrari
				<input type="checkbox" name="vehicle3" value="Ferrari"></p>
				<p class="checkfonts">Lada
				<input type="checkbox" name="vehicle4" value="Lada"></p>
				<p class="checkfonts">Mercedes
				<input type="checkbox" name="vehicle5" value="Mercedes"></p>
				<p class="checkfonts">BMW
				<input type="checkbox" name="vehicle6" value="BMW"></p>
			</div>
			<input type="submit" name="mehet" value="Keresés">
		</form>
		<?php
		include 'Opendb.php';
		if ( isset($_POST['mehet']) ){
			$link = getDb();
			$kereses = $_POST['kereses'];
			if(isset($_POST['vehicle1'])){ $Porsche = $_POST['vehicle1']; } else { $Porsche = 'nincs';}
			if(isset($_POST['vehicle2'])){ $Opel = $_POST['vehicle2']; } else { $Opel = 'nincs';}
			if(isset($_POST['vehicle3'])){ $Ferrari = $_POST['vehicle3']; } else { $Ferrari = 'nincs';}
			if(isset($_POST['vehicle4'])){ $Lada = $_POST['vehicle4']; } else { $Lada = 'nincs';}
			if(isset($_POST['vehicle5'])){ $Mercedes = $_POST['vehicle5']; } else { $Mercedes = 'nincs';}
			if(isset($_POST['vehicle6'])){ $BMW = $_POST['vehicle6']; } else { $BMW = 'nincs';}

			switch ($kereses) {
			 	case 'nnapi':
			 		$eredmeny = mysqli_query($link,
			 				"SELECT auto.Id, auto.leiras, auto.napiAr, auto.Kaukcio, auto.rendszam
							 FROM auto
							 LEFT OUTER JOIN kolcsonzes kcs ON kcs.autoId = auto.Id 
							 WHERE (kezd IS NULL OR (kezd IS NOT NULL and visszahozta IS NOT NULL)) AND (auto.leiras LIKE '%$Opel%' OR
							 						 auto.leiras LIKE '%$Porsche%' OR
							 						 auto.leiras LIKE '%$Ferrari%' OR
							 						 auto.leiras LIKE '%$Lada%' OR
							 						 auto.leiras LIKE '%$BMW%' OR
							 						 auto.leiras LIKE '%$Mercedes%')
							 ORDER BY auto.napiAr ASC");
			 				if (!$eredmeny) {
							    printf("Error: %s\n", mysqli_error($link));
							    exit();
							}
			 		break;
			 	case 'cskau':
			 		$eredmeny = mysqli_query($link,
			 				"SELECT auto.Id, auto.leiras, auto.napiAr, auto.Kaukcio, auto.rendszam
							 FROM auto
							 LEFT OUTER JOIN kolcsonzes kcs ON kcs.autoId = auto.Id 
							 WHERE (kezd IS NULL OR (kezd IS NOT NULL and visszahozta IS NOT NULL)) AND (auto.leiras LIKE '%$Opel%' OR
							 						 auto.leiras LIKE '%$Porsche%' OR
							 						 auto.leiras LIKE '%$Ferrari%' OR
							 						 auto.leiras LIKE '%$Lada%' OR
							 						 auto.leiras LIKE '%$BMW%' OR
							 						 auto.leiras LIKE '%$Mercedes%')
							 ORDER BY auto.Kaukcio DESC");
			 		break;	
			 	case 'foglalt':
			 		$eredmeny = mysqli_query($link,
			 				"SELECT auto.Id, auto.leiras, auto.napiAr, auto.Kaukcio, auto.rendszam
							 FROM auto
							 LEFT OUTER JOIN kolcsonzes kcs ON kcs.autoId = auto.Id 
							 WHERE kezd IS NOT NULL and visszahozta IS NULL");
			 		break;	
			 	case 'all': // Összes szabad
			 		$eredmeny = mysqli_query($link,
			 				"SELECT DISTINCT auto.Id, auto.leiras, auto.napiAr, auto.Kaukcio, auto.rendszam
							 FROM auto
							 LEFT OUTER JOIN kolcsonzes kcs ON kcs.autoId = auto.Id 
							 WHERE kezd IS NULL OR (kezd IS NOT NULL and visszahozta IS NOT NULL)");
			 		break;
			 	default:
			 		break;
			 } 

		}
		?>
		<table>
            <tr>
                <th>Leírás</th>
                <th>Rendszám</th>
                <th>Napi Ár(Ft/nap)</th>
                <th>Kaució(Ft)</th>     
            </tr> 
			<?php 
			if(isset($_POST['mehet'])){
			while ($row = mysqli_fetch_array($eredmeny)): ?>
				<tr>
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
			<?php endwhile; ?>
        </table>
		<?php
			mysqli_close($link);} // ez a lezáró kapcsos jel azért kell mert az if(isset()) külön php címképen kezdődött de még ide tartozik
		?>
	</body>
	<footer>Készítette:</br>Lengyel Dénes </br>A5PSO0</footer>
</html>
