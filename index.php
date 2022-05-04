<?php
	session_start();
	if((isset($_SESSION['zalogowany']))&&($_SESSION['zalogowany'] == true))
	{
		header('Location: game.php');
		exit();
	}
	
?>

<!DOCTYPE HTML>
<html lang = "pl">
	<head>
		<meta charset = "utf-8" />
		<title>Osadnicy</title>
	</head>
	<body>
		Tylko martwi ujrzeli koniec wojny - Platon <br /><br />

		<a href = "registration.php">Rejestracja - załóż darmowe konto!</a> <br /><br />

		<form action = "logowanie.php" method = "post">
			Login:<br /> <input type = "text" name = "login"><br />
			Hasło:<br /> <input type = "password" name = "pwd"><br /><br />
			<input type = "submit" value = "Zaloguj"><br />
			<?php
				if(isset($_SESSION['error']))
				{
					echo $_SESSION['error'];
				}

			?>		
		</form>

	</body>
</html>
