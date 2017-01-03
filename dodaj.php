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

if ($tabela == 'nalezy') {
    $kto_nalezy = $_POST['kto_nalezy'];
    $gdzie_nalezy = $_POST['gdzie_nalezy'];
    $od_kiedy = $_POST['od_kiedy'];

    $od_kiedy = htmlentities($od_kiedy, ENT_QUOTES, "UTF-8");

    $query = "INSERT INTO nalezy(kto_nalezy, gdzie_nalezy, od_kiedy) VALUES ('$kto_nalezy','$gdzie_nalezy','$od_kiedy');";
}

if ($tabela == 'druzyna') {
    $nr_druzyny = $_POST['nr_druzyny'];
    $nr_plutonu = $_POST['nr_plutonu'];
    $kto_dowodzi = $_POST['kto_dowodzi'];

    $nr_druzyny = htmlentities($nr_druzyny, ENT_QUOTES, "UTF-8");

    $query = "INSERT INTO druzyna(nr_druzyny, nr_plutonu, kto_dowodzi) VALUES ('$nr_druzyny','$nr_plutonu','$kto_dowodzi');";
}

if ($tabela == 'pluton') {
    $nr_plutonu = $_POST['nr_plutonu'];
    $nr_kompanii = $_POST['nr_kompanii'];
    $kto_dowodzi = $_POST['kto_dowodzi'];

    $nr_plutonu = htmlentities($nr_plutonu, ENT_QUOTES, "UTF-8");

    $query = "INSERT INTO pluton(nr_plutonu, nr_kompanii, kto_dowodzi) VALUES ('$nr_plutonu','$nr_kompanii','$kto_dowodzi');";
}

if ($tabela == 'kompania') {
    $nr_kompanii = $_POST['nr_kompanii'];
    $kto_dowodzi = $_POST['kto_dowodzi'];

    $nr_kompanii = htmlentities($nr_kompanii, ENT_QUOTES, "UTF-8");

    $query = "INSERT INTO kompania(nr_kompanii, kto_dowodzi) VALUES ('$nr_kompanii','$kto_dowodzi');";
}

$wynik = pg_query($query);

if ($wynik) {
	$_SESSION['success'] = ' Dodano nowy rekord.';
    header("Location: $tabela.php");
} else {
	$_SESSION['alert'] = ' Nie udało się dodać nowego rekordu. Sprawdź poprawność danych!';
    header("Location: $tabela.php");
}

pg_close($polaczenie);

?>