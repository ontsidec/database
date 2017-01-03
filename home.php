<?php

session_start();

if (!isset($_SESSION['zalogowany']))
{
	header('Location: index.php');
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
	<ul>
		<li><a class="active" href="index.php">Strona główna</a></li>
		<li><a href="zolnierz.php">Żołnierze</a></li>
		<li><a href="nalezy.php">Służba</a></li>
		<li><a href="druzyna.php">Drużyny</a></li>
		<li><a href="pluton.php">Plutony</a></li>
		<li><a href="kompania.php">Kompanie</a></li>
		<div style="float: right">
			<?php
				echo "<li><div class='witaj'><a>Witaj " .$_SESSION['user']."!</a></div></li>";
			?>
			<li><a href="wyloguj.php">Wyloguj się</a></li>
		</div>
	</ul>
</body>

</html>