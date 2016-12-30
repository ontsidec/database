<?php

session_start();

if (!isset($_SESSION['zalogowany']))
{
	header('Location: index.php');
	exit();
}	

require_once "connect.php";

$polaczenie = pg_connect("dbname = '$db_name' user = '$db_user' password = '$db_password' host = '$host'") or die("Nie mogę polączyć się z baza danych!");

$tabela = $_POST['tabela'];
$nazwa_id = $_POST['nazwa_id'];

$id = $_POST['id'];

$query = "DELETE FROM $tabela WHERE $nazwa_id = '$id';";

$wynik = pg_query($query);

if ($wynik) {
	$_SESSION['success'] = ' Usunięto rekord.';
	header("Location: $tabela.php");
} else {
	$_SESSION['alert'] = ' Nie udało się usunąć rekordu.';
	header("Location: $tabela.php");
}

pg_close($polaczenie);

?>