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

if ($tabela == 'nalezy') {
    $p_id_zolnierza = $_POST['p_id_zolnierza'];
    $p_id_druzyny = $_POST['p_id_druzyny'];
    $p_od_kiedy = $_POST['p_od_kiedy'];
    $id_zolnierza = $_POST['kto_nalezy'];
    $id_druzyny = $_POST['gdzie_nalezy'];
    $od_kiedy = $_POST['od_kiedy'];
    $do_kiedy = $_POST['do_kiedy'];

    $od_kiedy = htmlentities($od_kiedy, ENT_QUOTES, "UTF-8");
    $do_kiedy = htmlentities($do_kiedy, ENT_QUOTES, "UTF-8");

    $query = "UPDATE nalezy SET kto_nalezy = '$id_zolnierza', gdzie_nalezy = '$id_druzyny', od_kiedy = '$od_kiedy', do_kiedy = '$do_kiedy' WHERE kto_nalezy = '$p_id_zolnierza' AND gdzie_nalezy = '$p_id_druzyny' AND od_kiedy = '$p_od_kiedy';";
}

if ($tabela == 'druzyna') {
    $id = $_POST['idos'];
    $nr_druzyny = $_POST['nr_druzyny'];
    $nr_plutonu = $_POST['nr_plutonu'];
    $kto_dowodzi = $_POST['kto_dowodzi'];

    $nr_druzyny = htmlentities($nr_druzny, ENT_QUOTES, "UTF-8");

    $query = "UPDATE druzyna SET nr_druzyny = '$nr_druzyny', nr_plutonu = '$nr_plutonu', kto_dowodzi = '$kto_dowodzi' WHERE id_druzny = '$id';";
}

if ($tabela == 'pluton') {
    $id = $_POST['idos'];
    $nr_plutonu = $_POST['nr_plutonu'];
    $nr_kompanii = $_POST['nr_kompanii'];
    $kto_dowodzi = $_POST['kto_dowodzi'];

    $nr_plutonu = htmlentities($nr_plutonu, ENT_QUOTES, "UTF-8");

    $query = "UPDATE pluton SET nr_plutonu = '$nr_plutonu', nr_kompanii = '$nr_kompanii', kto_dowodzi = '$kto_dowodzi' WHERE id_plutonu = '$id';";
}

if ($tabela == 'kompania') {
    $id = $_POST['idos'];
    $nr_kompanii = $_POST['nr_kompanii'];
    $kto_dowodzi = $_POST['kto_dowodzi'];

    $nr_kompanii = htmlentities($nr_kompanii, ENT_QUOTES, "UTF-8");

    $query = "UPDATE kompania SET nr_kompanii = '$nr_kompanii', kto_dowodzi = '$kto_dowodzi' WHERE id_kompanii = '$id';";
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