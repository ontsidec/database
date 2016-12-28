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
$query = "SELECT * FROM stopnie";
$wynik = pg_query($polaczenie,$query);
$liczba_wierszy = pg_num_rows($wynik);

echo "<div id='dodaj'>";
echo "<form action=dodaj_zolnierz.php method=POST>
		<label for='imie'>Imię</label>
		<input type='text' id='imie' name='imie'>

		<label for='nazwisko'>Nazwisko</label>
        <input type='text' id='nazwisko' name=nazwisko>

		<label for='nr_ksiazeczki'>Nr książeczki</label>
		<input type='text' id='nr_ksiazeczki' name='nr_ksiazeczki'>

		<label for='stopien'>Stopień</label>
    	<select id='stopien' name='stopien'>";
		for($w = 0; $w < $liczba_wierszy; $w++) {
			$nazwa_stopnia = pg_fetch_result($wynik,$w,'nazwa_stopnia');
			$id_stopnia = pg_fetch_result($wynik,$w,'id_stopnia');
			echo "<option value=$id_stopnia>$nazwa_stopnia</option>";
		}
echo   "</select>

        <input type='submit' name='Dodaj' value='Dodaj'>
        </form>";
echo "</div>";
echo "<div class='h_line'></div>";

if(isset($_SESSION['alert']))	
{
	echo "
		<div class='alert'>
			<span class='closebtn'>&times;</span>  
			<strong>Błąd!</strong>";
	echo $_SESSION['alert'];
	echo "		
		</div>
		<script>
			var close = document.getElementsByClassName('closebtn');
			var i;

			for (i = 0; i < close.length; i++) {
				close[i].onclick = function(){
					var div = this.parentElement;
					div.style.opacity = '0';
					setTimeout(function(){ div.style.display = 'none'; }, 600);
				}
			}
		</script>";
	unset($_SESSION['alert']);
}

if(isset($_SESSION['success']))	
{
	echo "
		<div class='alert success'>
			<span class='closebtn'>&times;</span>  
			<strong>Sukces!</strong>";
	echo $_SESSION['success'];
	echo "
		</div>
		<script>
			var close = document.getElementsByClassName('closebtn');
			var i;

			for (i = 0; i < close.length; i++) {
				close[i].onclick = function(){
					var div = this.parentElement;
					div.style.opacity = '0';
					setTimeout(function(){ div.style.display = 'none'; }, 600);
				}
			}
		</script>";
	unset($_SESSION['success']);
}

$query = "SELECT z.id_zolnierza, z.imie, z.nazwisko, z.nr_ksiazeczki, s.nazwa_stopnia 
		  FROM zolnierz z JOIN stopnie s ON  z.stopien = s.id_stopnia 
		  ORDER BY z.id_zolnierza;";

$tabela = 'zolnierz';
$nazwa_id = 'id_zolnierza';

$wynik = pg_query($polaczenie,$query);

$liczba_kolumn = pg_num_fields($wynik);
$liczba_wierszy = pg_num_rows($wynik);

echo "<table>";
echo "<tr>";

for ($k = 0; $k < $liczba_kolumn; $k++)
{	
	echo "<th>";	
	echo pg_field_name($wynik,$k);	
	echo "</th>";
}

echo "<th>Usuń</th><th>Edytuj</th>";
echo "</tr>";

for ($w = 0; $w < $liczba_wierszy; $w++)
{
	echo "<tr>";
	for ($k = 0; $k < $liczba_kolumn; $k++)
	{		
		echo "<td>";
		echo pg_fetch_result($wynik,$w,$k);	
		echo "</td>";
	}
	
	$id = pg_fetch_result($wynik,$w,0);
	echo "<td><div id='edycja'><form action=../usun.php method=POST>
            <input type=hidden name=id value=$id>
			<input type=hidden name=tabela value=$tabela>
			<input type=hidden name=nazwa_id value=$nazwa_id>
            <input type=submit name=usun value=Usuń></form></div></td>";

			
	echo "<td><div id='edycja'><form action='' method=POST>
            <input type=hidden name=idos value=$id>
            <input type=submit id='myBtn' name=zmien value=Edytuj></form></div></td>";
	echo "</tr>";	
}

echo "</table>";

if ($_POST)	{
	$id = $_POST['idos'];
	$query = "SELECT z.id_zolnierza, z.imie, z.nazwisko, z.nr_ksiazeczki, s.nazwa_stopnia 
		  FROM zolnierz z JOIN stopnie s ON  id_zolnierza = $id AND z.stopien = s.id_stopnia;";
	$wynik = pg_query($query);
	$imie = pg_fetch_result($wynik,0,'imie');
	$nazwisko = pg_fetch_result($wynik,0,'nazwisko');
	$nr_ksiazeczki = pg_fetch_result($wynik,0,'nr_ksiazeczki');
	$nazwa_stopnia = pg_fetch_result($wynik,0,'nazwa_stopnia');

	echo "
	<div id='myModal' class='modal'>
		<div class='modal-content'>
			<div class='modal-header'>
				<span class='close'>&times;</span>
				<h2>Edycja rekordu</h2>
			</div>
			<div class='modal-body'>
				<div id='edytuj'>
					<div style='width: 100%; padding: 5px; display: inline-block;'>
						<div style='margin: 0px; font-size: 1.5em; font-weight: bold;'>Podaj nowe dane dla:<br></div> 
						<div style='margin: 15px;'>$imie $nazwisko $nr_ksiazeczki $nazwa_stopnia<br></div> 
					</div>";
					$query = "SELECT * FROM stopnie";
					$wynik = pg_query($polaczenie,$query);
					$liczba_wierszy = pg_num_rows($wynik);

					echo "
					<form action=edytuj_zolnierz2.php method=POST>

					<label for='imie'>Imię</label>
					<input type='text' id='imie' name='imie'>

					<label for='nazwisko'>Nazwisko</label>
					<input type='text' id='nazwisko' name=nazwisko>

					<label for='stopien'>Stopień</label>
					<select id='stopien' name='stopien'>";
					for($w = 0; $w < $liczba_wierszy; $w++) {
						$nazwa_stopnia = pg_fetch_result($wynik,$w,'nazwa_stopnia');
						$id_stopnia = pg_fetch_result($wynik,$w,'id_stopnia');
						echo "<option value=$id_stopnia>$nazwa_stopnia</option>";
					}
					echo "
					</select>

					<input type='hidden' name='idos' value=$id>
					<input type='submit' name='zmien' value='Zmień'>
					</form>	
				</div>
			</div>
		</div>
	</div>
	";
}
pg_close($polaczenie);
?>

<script>
var modal = document.getElementById('myModal');
var span = document.getElementsByClassName("close")[0];

span.onclick = function() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>
<iframe name='votar' style='display:none;'></iframe>
</body>
</html>