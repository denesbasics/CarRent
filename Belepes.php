<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu" lang="hu">
<head>
	<title>Belépés</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="style_login.css">
</head>
<body>
	<div id="formkinezet">
		<form action="Authority.php" method="POST">
			<p>
				<label>Username:</label>
				<input type="text" id="user" placeholder="Felhasználónév" name="user"/>
			</p>
			<p>
				<label>Password:</label>
				<input type="password" placeholder="Jelszó" id="pass" name="pass"/>
			</p>
			<p>
				<input type="submit" id="btn" value="Belépés"/>
			</p>
		</form>
	</div>
</body>
</html> 
