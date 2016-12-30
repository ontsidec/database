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

if ($tabela == 'zolnierz') {
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    $nr_ksiazeczki = $_POST['nr_ksiazeczki'];
    $stopien = $_POST['stopien'];

    $imie = htmlentities($imie, ENT_QUOTES, "UTF-8");
    $nazwisko = htmlentities($nazwisko, ENT_QUOTES, "UTF-8");
    $nr_ksiazeczki = htmlentities($nr_ksiazeczki, ENT_QUOTES, "UTF-8");

    $query = "INSERT INTO zolnierz(imie, nazwisko, nr_ksiazeczki, stopien) VALUES ('$imie','$nazwisko','$nr_ksiazeczki','$stopien');";
}

$wynik = pg_query($query);

if ($wynik) {
	$_SESSION['success'] = ' Dodano nowy rekord.';
	header('Location: zolnierz.php');
} else {
	$_SESSION['alert'] = ' Nie udało się dodać nowego rekordu. Sprawdź poprawność danych!';
	header('Location: zolnierz.php');
}

pg_close($polaczenie);

?>