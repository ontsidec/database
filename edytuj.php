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

if ($tabela == 'zolnierz') {
    $id = $_POST['idos'];
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    $nr_ksiazeczki = $_POST['nr_ksiazeczki'];
    $stopien = $_POST['stopien'];

    $imie = htmlentities($imie, ENT_QUOTES, "UTF-8");
    $nazwisko = htmlentities($nazwisko, ENT_QUOTES, "UTF-8");
    $nr_ksiazeczki = htmlentities($nr_ksiazeczki, ENT_QUOTES, "UTF-8");

    $query = "UPDATE zolnierz SET imie = '$imie', nazwisko = '$nazwisko', nr_ksiazeczki = '$nr_ksiazeczki', stopien = '$stopien' WHERE id_zolnierza = '$id';";
}

$wynik = pg_query($query);

if ($wynik) {
    $_SESSION['success'] = ' Zaktualizowano rekord.';
    header("Location: $tabela.php");
} else {
    $_SESSION['alert'] = ' Nie udało się zaktualizować rekordu.';
    header("Location: $tabela.php");
}

pg_close($polaczenie);

?>