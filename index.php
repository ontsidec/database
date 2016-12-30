<?php

session_start();

if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany'] == true))
{
	header('Location: home.php');
	exit();
}

?>

<!DOCTYPE HTML>
<html lang="pl">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Baza danych</title>
	<link rel="stylesheet" href="style.php" media="screen">
</head>

<body>
	<div class='karta'>
		<div id='formularz'>
			<form action="zaloguj.php" method="POST">
				<label for='login'>Login</label>
				<input type="text" id='login' name="login">

				<label for='haslo'>Hasło</label>
				<input type="password" id='haslo' name="haslo">

				<input type="submit" value="Zaloguj się">
			</form>

			<?php
				if(isset($_SESSION['blad'])) {
					echo $_SESSION['blad'];
					unset($_SESSION['blad']);
				}
			?>
			
		</div>
	</div>
</body>

</html>