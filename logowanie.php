<?php
	session_start();
	if((!isset($_POST['login']))||(!isset($_POST['pwd'])))
	{
		header('Location: index.php');
		exit();
	}

	require_once "connect.php";
	$connection = @new mysqli($host, $db_user, $db_pwd, $db_name);
	if($connection->connect_errno!=0)
	{
		echo "Error: ".$connection->connect_errno." Opis: ".$connection->connect_error;
	}
	else
	{
		$login = $_POST['login'];
		$pwd = $_POST['pwd'];
		$login = htmlentities($login, ENT_QUOTES, "UTF-8");
		
		
		
		if($result = @$connection->query(sprintf("SELECT * FROM uzytkownicy WHERE user = '%s'",
		mysqli_real_escape_string($connection, $login))))
		{
			$rows = $result->num_rows;
			if($rows > 0)
			{
					$row = $result->fetch_assoc();
					if(password_verify($pwd, $row['pass']))
					{
					$_SESSION['zalogowany'] = true;
					
					$_SESSION['id'] = $row['id'];
					$_SESSION['user'] = $row['user'];
					$_SESSION['drewno'] = $row['drewno'];
					$_SESSION['kamien'] = $row['kamien'];
					$_SESSION['zboze'] = $row['zboze'];
					$_SESSION['email'] = $row['email'];
					$_SESSION['dnipremium'] = $row['dnipremium'];
					
					unset($_SESSION['error']);
					
					$result->close();
					header('Location: game.php');
				}
				else
				{
					$_SESSION['error'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
					header('Location: index.php');
				}
			}
			else
			{
				$_SESSION['error'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
				header('Location: index.php');
			}			
		}		
		$connection->close();
	}
?>