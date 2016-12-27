<?php

	session_start();
	
	if ((!isset($_POST['login'])) || (!isset($_POST['haslo'])))
	{
		header('Location: index.php');
		exit();
	}

	$host = "localhost";
	$db_user = "frapaw";
	$db_name = "projekt_frapaw";

	$login = $_POST['login'];
	$haslo = $_POST['haslo'];

	$login = htmlentities($login, ENT_QUOTES, "UTF-8");
	$haslo = htmlentities($haslo, ENT_QUOTES, "UTF-8");

	$haslo_hash = crypt($haslo,'salt');
	$db_haslo_hash = crypt($haslo_hash,'salt_baza');

	$polaczenie = pg_connect("dbname='$db_name' user='$db_user' password='$db_haslo_hash' host='$host'") or die("Nie moge polaczyc sie z baza danych !");

	$query = "SELECT * FROM uzytkownicy WHERE uzytkownik='$login' AND haslo='$haslo_hash';";

	$wynik = pg_query($polaczenie,$query);
	
	$ilu_userow = pg_num_rows($wynik);

	if($ilu_userow>0)
	{
		$_SESSION['zalogowany'] = true;
		
		$_SESSION['id'] = pg_fetch_result($wynik,0,0);
		$_SESSION['user'] = pg_fetch_result($wynik,0,1);
		$_SESSION['password'] = pg_fetch_result($wynik,0,2);
		$_SESSION['db_password'] = $db_haslo_hash;
		
		unset($_SESSION['blad']);
		header('Location: home.php');
		
	} else {
		
		$_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
		header('Location: index.php');
		
	}	
	
	pg_close($polaczenie);

?>