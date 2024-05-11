<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu" lang="hu">
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<?php 
	//A user és pass kinyerése a formból
	$username = $_POST["user"];
	$password = $_POST["pass"];

	//Csatlakozás a szerverhez és az adatbázishoz
	$link = mysqli_connect("localhost","root", "");
	mysqli_select_db($link, "test");
	mysqli_query($link, "set character_set_results='utf8'");

	//MySQL injuction ellen védekezés
	$username = stripcslashes($username);
	$password = stripcslashes($password);
	$username = mysqli_real_escape_string($link,$username);
	$password = mysqli_real_escape_string($link,$password);

	//Adatbázisból lekérdezzük a user adatokat
	$result = mysqli_query($link, "select * from login where username = '$username' and pass = '$password'")
				or die("Adatbázis betöltési hiba történt".mysqli_error($link));
	$row = mysqli_fetch_array($result);
    if ($row["username"] == $username && $row["pass"] == $password && ("" !== $username || "" !== $password))  {
    	echo "Login successful! Welcome " . $row["username"];
    	ob_start(); // ensures anything dumped out will be caught

		// do stuff here
		$url = '/KolcsonzesWeblap/Admin.php'; // this can be set based on whatever

		// clear out the output buffer
		while (ob_get_status()) 
		{
		    ob_end_clean();
		}

		// no redirect
		header( "Location: $url" );
	    } 

    else {
      echo "Failed to login! ";
      echo '<a href="kezdolap.html">Vissza Kezdolapra!</a>';
    }
    mysqli_close($link);
?>
</html>