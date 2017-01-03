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

if ($tabela == 'nalezy') {
	$id = $_POST['id'];
	$id2 = $_POST['id2'];
	$id3 = $_POST['id3'];
	$id4 = $_POST['id4'];
	$id5 = $_POST['id5'];
	$id6 = $_POST['id6'];

	$query = "SELECT id_zolnierza FROM zolnierz WHERE imie = '$id' AND nazwisko = '$id2';";
	$wynik = pg_query($polaczenie, $query);
	$liczba_wierszy = pg_num_rows($wynik);

	for($w = 0; $w < $liczba_wierszy; $w++) {
		$id_zolnierza = pg_fetch_result($wynik, $w, 'id_zolnierza');
	}

	$query = "SELECT id_kompanii FROM kompania WHERE nr_kompanii =  '$id5';";
	$wynik = pg_query($polaczenie, $query);
	$liczba_wierszy = pg_num_rows($wynik);

	for($w = 0; $w < $liczba_wierszy; $w++) {
		$id_kompanii = pg_fetch_result($wynik, $w, 'id_kompanii');
	}

	$query = "SELECT id_plutonu FROM pluton WHERE nr_plutonu =  '$id4' AND nr_kompanii = '$id_kompanii';";
	$wynik = pg_query($polaczenie, $query);
	$liczba_wierszy = pg_num_rows($wynik);

	for($w = 0; $w < $liczba_wierszy; $w++) {
		$id_plutonu = pg_fetch_result($wynik, $w, 'id_plutonu');
	}

	$query = "SELECT id_druzyny FROM druzyna WHERE nr_druzyny =  '$id3' AND nr_plutonu = '$id_plutonu';";
	$wynik = pg_query($polaczenie, $query);
	$liczba_wierszy = pg_num_rows($wynik);

	for($w = 0; $w < $liczba_wierszy; $w++) {
		$id_druzyny = pg_fetch_result($wynik, $w, 'id_druzyny');
	}

	$query = "DELETE FROM $tabela WHERE kto_nalezy = '$id_zolnierza' AND gdzie_nalezy = '$id_druzyny' AND od_kiedy = '$id6';";

} else {
	$id = $_POST['id'];
	$nazwa_id = $_POST['nazwa_id'];

	$query = "DELETE FROM $tabela WHERE $nazwa_id = '$id';";
}

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