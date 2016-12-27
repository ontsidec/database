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

$query = "SELECT id_zolnierza, imie, nazwisko, stopien from zolnierz where id_zolnierza=$id;";
$wynik = pg_query($query);
$imie = pg_fetch_result($wynik,0,'imie');
$nazwisko = pg_fetch_result($wynik,0,'nazwisko');
$stopien = pg_fetch_result($wynik,0,'stopien');
$lk = pg_num_fields($wynik);

echo "<div class='card'>";
echo "<div id='edytuj'>";
echo "<div style='width: 100%; padding: 5px; display: inline-block;'>
	 	<div style='margin: 0px; font-size: 1.5em; font-weight: bold;'>Podaj nowe dane dla:<br></div> 
	 	<div style='margin: 15px;'>$imie $nazwisko $stopien<br></div> 
	 </div>";
echo "<form action=edytuj_zolnierz2.php method=POST>";
echo   "<label for='imie'>Imię</label>
		<input type='text' id='imie' name='imie'>

		<label for='nazwisko'>Nazwisko</label>
        <input type='text' id='nazwisko' name=nazwisko>

		<label for='stopien'>Stopień</label>
    	<select id='stopien' name='stopien'>
      		<option value='Szeregowy' selected='selected'>Szeregowy</option>
			<option value='Starszy Szeregowy'>Starszy Szeregowy</option>
			<option value='Kapral'>Kapral</option>
			<option value='Starszy Kapral'>Starszy Kapral</option>
			<option value='Plutonowy'>Plutonowy</option>
			<option value='Sierzant'>Sierżant</option>
			<option value='Starszy Sierzant'>Starszy Sierżant</option>
			<option value='Mlodszy Chorazy'>Młodszy Chorąży</option>
			<option value='Chorazy'>Chorąży</option>
			<option value='Starszy Chorazy'>Starszy Chorąży</option>
			<option value='Starszy Chorazy Sztabowy'>Starszy Chorąży Sztabowy</option>
			<option value='Podporucznik'>Podporucznik</option>
			<option value='Porucznik'>Porucznik</option>
			<option value='Kapitan'>Kapitan</option>
    	</select>

        <input type='hidden' name='idos' value=$id>
        <input type='submit' name='zmien' value='Zmień'>
        </form>";
echo "</div></div>";

pg_close($polaczenie);
?>
</body>
</html>