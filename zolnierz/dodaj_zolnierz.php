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
    <link rel="stylesheet" href="../style.php" media="screen">
</head>
<body>
	<ul>
		<li><a href="../index.php">Strona główna</a></li>
    	<li><a class="active" href="zolnierz.php">Żołnierze</a></li>
		<div style="float: right">
		<?php
		echo "<li><div class='witaj'><a>Witaj " .$_SESSION['user']."!</a></div></li>";
		?>
		<li><a href="../wyloguj.php">Wyloguj się</a></li>
		</div>
	</ul>
<?php
require_once "../connect.php";
$polaczenie = pg_connect("dbname='$db_name' user='$db_user' password='$db_password' host='$host'") or die("Nie mogę polączyć się z baza danych!");
$imie = $_POST['imie'];
$nazwisko = $_POST['nazwisko'];
$nr_ksiazeczki = $_POST['nr_ksiazeczki'];
$stopien = $_POST['stopien'];
$imie = htmlentities($imie, ENT_QUOTES, "UTF-8");
$nazwisko = htmlentities($nazwisko, ENT_QUOTES, "UTF-8");
$nr_ksiazeczki = htmlentities($nr_ksiazeczki , ENT_QUOTES, "UTF-8");
$query = "INSERT INTO zolnierz(imie, nazwisko, nr_ksiazeczki, stopien) VALUES ('$imie','$nazwisko','$nr_ksiazeczki','$stopien');";
$wynik = pg_query($query);
pg_close($polaczenie);
if ($wynik) {
	$_SESSION['success'] = ' Dodano nowego żołnierza.';
	header('Location: zolnierz.php');
} else {
	$_SESSION['alert'] = ' Nie udało się dodać żołnierza. Sprawdź poprawność danych (długość nr książczki - 8 znaków)!';
	header('Location: zolnierz.php');
}
?>
</body>
</html>