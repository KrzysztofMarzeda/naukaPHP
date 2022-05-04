<?php
	session_start();
	if(isset($_SESSION['udanarejestracja']))
	{
		header('Location: index.php');
		exit();
	}
	else
	{
		unset($_SESSION['udanarejestracja']);
	}
	
?>

<!DOCTYPE HTML>
<html lang = "pl">
	<head>
		<meta charset = "utf-8" />
		<title>Osadnicy - gra</title>
	</head>
	<body>
		Dziękujemy za rejestrację w serwisie! Możesz już zalogowac się na swoje konto!<br /><br />

		<a href = "index.php"> Zaloguj się na swoje konto!</a>
	</body>
</html>
