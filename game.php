<?php
	session_start();
?>

<!DOCTYPE HTML>
<html lang = "pl">
	<head>
		<meta charset = "utf-8" />
		<title>Osadnicy</title>
	</head>
	<body>
		<?php
			echo "<p>Witaj ".$_SESSION['user'].'! [<a href="logout.php">Wyloguj się</a>]</p>';
			echo "<p><b>Drewno</b>: ".$_SESSION['drewno'];
			echo " | <b>Kamień</b>: ".$_SESSION['kamien'];
			echo " | <b>Zboże</b>: ".$_SESSION['zboze']."</p>";
			echo "<p><b>E-mail</b>: ".$_SESSION['email'];
			echo "<br /><b>Data wygaśnięcia premium</b>: ".$_SESSION['dnipremium']."</p>";
			
		?>

	</body>
</html>
