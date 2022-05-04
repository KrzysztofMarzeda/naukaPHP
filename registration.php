<?php
	session_start();
	
	if(isset($_POST['email']))
	{
		$wszystko_ok = true;
		//Sprawdzenie nickname'u
		$nick = $_POST['nick'];
		//Długość
		if((strlen($nick))<3 ||(strlen($nick)>20))
		{
			$wszystko_ok = false;
			$_SESSION['e_nick'] = "Nick musi posiadać od 3 do 20 znaków!";
		}
		
		if(ctype_alnum($nick) == false)
		{
			$wszystko_ok = false;
			$_SESSION['e_nick'] = "Nick może składać się tylko z liter i cyfr(bez polskich znaków)!";
		}
		//Sprawdzenie emailu
		$email = $_POST['email'];
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL); //usuwanie znaków specjalnych i polskich
		
		if((filter_var($emailB, FILTER_VALIDATE_EMAIL)) == false || ($emailB != $email))
		{
			$wszystko_ok = false;
			$_SESSION['e_email'] = "Podaj poprawny adres e-mail!";
		}
		//Sprawdzenie haseł
		$pwd1 = $_POST['pwd1'];
		$pwd2 = $_POST['pwd2'];
		
		if((strlen($pwd1)<8) || (strlen($pwd1)>20))
		{
			$wszystko_ok = false;
			$_SESSION['e_pwd'] = "Hasło musi posiadać od 8 do 20 znaków!";
		}
		if($pwd1 != $pwd2)
		{
			$_SESSION['e_pwd'] = "Podane hasła nie są identyczne!";
		}
		$pwd_hash = password_hash($pwd1, PASSWORD_DEFAULT);
		//Sprawdzenie checkboxa
		if(!isset($_POST['regulamin']))
		{
			$wszystko_ok = false;
			$_SESSION['e_regulamin'] = "Potwierdź akceptację regulaminu!";
		}
		//Sprawdzenie captchy
		$secret_key = "6LfS8aYfAAAAAO_zuB0Y5kYyaULf5jcU3ASec94Q";
		$check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$_POST['g-recaptcha-response']);
		$answer = json_decode($check);
		
		if($answer->success == false)
		{
			$wszystko_ok = false;
			$_SESSION['e_captcha'] = "Potwierdź, że nie jesteś botem!";
		}
		
		require_once "connect.php";
		
		mysqli_report(MYSQLI_REPORT_STRICT); //Zamiast warrningów rzucamy wyjątki
		
		try
		{
			$connection = new mysqli($host, $db_user, $db_pwd, $db_name);
			if($connection->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				//Sprawdzenie czy mail jest w bazie
				$result = $connection->query("SELECT id FROM uzytkownicy WHERE email = '$email'");
				if(!$result)
				{
					throw new Exception($connection->error);
				}
				$how_many_mails = $result->num_rows;
				if($how_many_mails > 0)
				{
					$wszystko_ok = false;
					$_SESSION['e_email'] = "Podany e-mail jest zajęty";
				}
				
				//Sprawdzenie czy nick jest w bazie
				$result = $connection->query("SELECT id FROM uzytkownicy WHERE user = '$nick'");
				if(!$result)
				{
					throw new Exception($connection->error);
				}
				$how_many_nicknames = $result->num_rows;
				if($how_many_nicknames > 0)
				{
					$wszystko_ok = false;
					$_SESSION['e_nick'] = "Podany nick jest zajęty";
				}
				//wszystko ok
				if($wszystko_ok == true)
				{
						if($connection->query("INSERT INTO uzytkownicy VALUES (NULL, '$nick', '$pwd_hash', '$email', 100, 100, 100, now() + INTERVAL 14 DAY)"))
						{
							$_SESSION['udanarejestracja'] = true;
							header('Location: welcome.php');
						}
						else
						{
							throw new Exception($connection->error);
						}
				}
				
				$connection->close();
			}
	
		}
		catch(Exception $e)
		{
			echo '<span style = "color:red;">Błąd serwera!</span>';
			//echo '<br /> Informacja developerska: '.$e;
		}
		
		
		
	}
	
?>

<!DOCTYPE HTML>
<html lang = "pl">
<head>
	<meta charset = "utf-8" />
	<title>Osadnicy - rejestracja</title>
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<style>
		.error{
			color: red;
			margin-top: 10px;
			margin-bottom: 10px;
		}
	</style>
</head>
<body>
	<form method = "post">
		Nickname: <br /> <input type = "text" name = "nick" /><br />
		<?php
			if(isset($_SESSION['e_nick']))
			{
				echo '<div class = "error">'.$_SESSION['e_nick'].'</div>';
				unset($_SESSION['e_nick']);
			}
		?>
		
		E-mail: <br /> <input type = "text" name = "email" /><br />
		
		<?php
			if(isset($_SESSION['e_email']))
			{
				echo '<div class = "error">'.$_SESSION['e_email'].'</div>';
				unset($_SESSION['e_email']);
			}
		?>
		
		Hasło: <br /> <input type = "password" name = "pwd1"><br />
		
		<?php
			if(isset($_SESSION['e_pwd']))
			{
				echo '<div class = "error">'.$_SESSION['e_pwd'].'</div>';
				unset($_SESSION['e_pwd']);
			}
		?>
		
		Powtórz hasło: <br /> <input type = "password" name = "pwd2"><br />
		<label><input type = "checkbox" name = "regulamin" /> Akceptuje regulamin</label>
		<?php
			if(isset($_SESSION['e_regulamin']))
			{
				echo '<div class = "error">'.$_SESSION['e_regulamin'].'</div>';
				unset($_SESSION['e_regulamin']);
			}
		?>
		
		<div class="g-recaptcha" data-sitekey="6LfS8aYfAAAAAPB6wApV5wozDk1NFaynpYr665pr"></div>
		<?php
			if(isset($_SESSION['e_captcha']))
			{
				echo '<div class = "error">'.$_SESSION['e_captcha'].'</div>';
				unset($_SESSION['e_captcha']);
			}
		?>
		
		<br />
		<input type = "submit" value = "Zarejestruj się" />	
	</form>

</body>
</html>