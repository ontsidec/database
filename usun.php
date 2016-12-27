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
<?php
require_once "connect.php";
$polaczenie = pg_connect("dbname='$db_name' user='$db_user' password='$db_password' host='$host'") or die("Nie mogę polączyć się z baza danych!");
$id = $_POST['id'];
$tabela = $_POST['tabela'];
$nazwa_id = $_POST['nazwa_id'];
$query = "DELETE FROM $tabela WHERE $nazwa_id = '$id'";
$wynik = pg_query($query);
pg_close($polaczenie);
if ($wynik) {
	$_SESSION['success'] = ' Usunięto rekord.';
	header("Location: $tabela/$tabela.php");
} else {
	$_SESSION['alert'] = ' Nie udało się usunąć rekordu.';
	header("Location: $tabela/$tabela.php");
}
?>
</body>
</html>