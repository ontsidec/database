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
$id = $_POST['idos'];
$imie = $_POST['imie'];
$nazwisko = $_POST['nazwisko'];
$stopien = $_POST['stopien'];
$imie = htmlentities($imie, ENT_QUOTES, "UTF-8");
$nazwisko = htmlentities($nazwisko, ENT_QUOTES, "UTF-8");

$query = "UPDATE zolnierz SET nazwisko= '$nazwisko', imie = '$imie', stopien = '$stopien' WHERE id_zolnierza = '$id'";
$wynik = pg_query($query);

$ls = pg_affected_rows($wynik);

pg_close($polaczenie);
echo "<div class='card'>";
echo "<div id='edytuj'>";
echo "<form action=zolnierz.php method=post>
		<div style='text-align: center;'>Edytowano $ls żołnierzy<br></div> 
        <input type=submit name=Ok value=OK></form>";
echo "</div></div>";
?>
</body>
</html>