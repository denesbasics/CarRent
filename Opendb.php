<?php
function getDb()
{
	$link = mysqli_connect("localhost", "root", "")
			or die("Kapcsolodási hiba" . mysqli_error());
	mysqli_select_db($link, "test");
	mysqli_set_charset($link,"utf8");
	mysqli_query($link, "set character_set_results='utf8'"); // ékezetesen jöjjenek vissza az adatok	
	return $link;
}
?>