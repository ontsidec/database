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
	<link rel="stylesheet" href="style.php" media="screen">
</head>

<body>
	<ul>
		<li><a href="index.php">Strona główna</a></li>
        <li><a href="zolnierz.php">Żołnierze</a></li>
        <li><a href="nalezy.php">Służba</a></li>
		<li><a class="active" href="druzyna.php">Drużyny</a></li>
        <li><a href="pluton.php">Plutony</a></li>
        <li><a href="kompania.php">Kompanie</a></li>
		<div style="float: right">
			<?php
				echo "<li><div class='witaj'><a>Witaj " .$_SESSION['user']."!</a></div></li>";
			?>
			<li><a href="wyloguj.php">Wyloguj się</a></li>
		</div>
	</ul>

	<?php

	require_once "connect.php";

	$polaczenie = pg_connect("dbname = '$db_name' user = '$db_user' password = '$db_password' host = '$host'") or die("Nie mogę polączyć się z baza danych!");

	$tabela = 'druzyna';
	$nazwa_id = 'id_druzyny';

	$query = "SELECT id_plutonu, nr_plutonu FROM pluton;";
	$wynik = pg_query($polaczenie, $query);
	$liczba_wierszy = pg_num_rows($wynik);

	echo "
		<div id='dodaj'>
			<form action=dodaj.php method=POST>
                <label for='nr_druzyny'>Nr drużyny</label>
				<input type='text' id='nr_druzyny' name='nr_druzyny'>

				<label for='nr_plutonu'>Nr plutonu</label>
				<select id='nr_plutonu' name='nr_plutonu'>";

                for($w = 0; $w < $liczba_wierszy; $w++) {
					$id_plutonu = pg_fetch_result($wynik, $w, 'id_plutonu');
					$nr_plutonu = pg_fetch_result($wynik, $w, 'nr_plutonu');
					echo "<option value=$id_plutonu>$nr_plutonu</option>";
				}

    echo "
                </select>
				<label for='kto_dowodzi'>Dowódca</label>
				<select id='kto_dowodzi' name='kto_dowodzi'>";

                $query = "SELECT id_zolnierza, imie, nazwisko FROM zolnierz WHERE stopien > '2';";
                $wynik = pg_query($polaczenie, $query);
                $liczba_wierszy = pg_num_rows($wynik);

				for($w = 0; $w < $liczba_wierszy; $w++) {
					$id_zolnierza = pg_fetch_result($wynik, $w, 'id_zolnierza');
					$imie = pg_fetch_result($wynik, $w, 'imie');
                    $nazwisko = pg_fetch_result($wynik, $w, 'nazwisko');
					echo "<option value=$id_zolnierza>$imie $nazwisko</option>";
				}
	echo "
				</select>

				<input type='hidden' name='tabela' value=$tabela>
				<input type='submit' name='Dodaj' value='Dodaj'>
			</form>
		</div>
		<div class='h_line'></div>";

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

	$query = "SELECT d.id_druzyny, d.nr_druzyny, p.nr_plutonu, k.nr_kompanii, z.imie, z.nazwisko 
			FROM druzyna d JOIN zolnierz z ON  d.kto_dowodzi = z.id_zolnierza JOIN pluton p ON d.nr_plutonu = p.id_plutonu JOIN kompania k ON p.nr_kompanii = k.id_kompanii
			ORDER BY d.id_druzyny;";

	$wynik = pg_query($polaczenie, $query);

	$liczba_kolumn = pg_num_fields($wynik);
	$liczba_wierszy = pg_num_rows($wynik);

	echo "<table>
			<tr>";

	for ($k = 0; $k < $liczba_kolumn; $k++)
	{	
		echo "<th>";	
		echo pg_field_name($wynik, $k);	
		echo "</th>";
	}

	echo "<th>Usuń</th><th>Edytuj</th>
			</tr>";

	for ($w = 0; $w < $liczba_wierszy; $w++)
	{
		echo "<tr>";
		for ($k = 0; $k < $liczba_kolumn; $k++)
		{		
			echo "<td>";
			echo pg_fetch_result($wynik, $w, $k);	
			echo "</td>";
		}
	
		$id = pg_fetch_result($wynik, $w, 0);
		echo "<td><div id='edycja'><form action=usun.php method=POST>
				<input type=hidden name=id value=$id>
				<input type=hidden name=tabela value=$tabela>
				<input type=hidden name=nazwa_id value=$nazwa_id>
				<input type=submit name=usun value=Usuń></form></div></td>";

			
		echo "<td><div id='edycja'><form action='' method=POST>
				<input type=hidden name=idos value=$id>
				<input type=submit id='myBtn' name=zmien value=Edytuj></form></div></td>
			</tr>";
	}

	echo "</table>";

	if ($_POST)	{
		$id = $_POST['idos'];
		$query = "SELECT d.nr_druzyny, p.nr_plutonu, k.nr_kompanii, z.imie, z.nazwisko 
			FROM druzyna d JOIN zolnierz z ON d.kto_dowodzi = z.id_zolnierza AND d.id_druzyny = $id JOIN pluton p ON d.nr_plutonu = p.id_plutonu JOIN kompania k ON p.nr_kompanii = k.id_kompanii;";
		$wynik = pg_query($query);
        $nr_druzyny = pg_fetch_result($wynik, 0, 'nr_druzyny');
        $nr_plutonu = pg_fetch_result($wynik, 0, 'nr_plutonu');
		$nr_kompanii = pg_fetch_result($wynik, 0, 'nr_kompanii');
		$imie = pg_fetch_result($wynik, 0, 'imie');
        $nazwisko = pg_fetch_result($wynik, 0, 'nazwisko');

		echo "
			<div id='myModal' class='modal'>
				<div class='modal-content'>
					<div class='modal-header'>
						<span class='close'>&times;</span>
						<h2>Edycja rekordu</h2>
					</div>
					<div class='modal-body'>
						<div id='formularz'>
							<div id='tytul-formularza'>
								<div id='tytul'>Podaj nowe dane dla:<br></div>  
								<div id='dane'>$nr_druzyny $nr_plutonu $nr_kompanii $imie $nazwisko<br></div> 
							</div>";

                        $query = "SELECT id_kompanii, nr_kompanii FROM kompania;";
                        $wynik = pg_query($polaczenie, $query);
                        $liczba_wierszy = pg_num_rows($wynik);

						echo "
							<form action=edytuj.php method=POST>
                                <label for='nr_druzyny'>Nr drużyny</label>
                                <input type='text' id='nr_druzyny' name='nr_druzyny'>

                                <label for='nr_plutonu'>Nr plutonu</label>
                                <select id='nr_plutonu' name='nr_plutonu'>";

                                for($w = 0; $w < $liczba_wierszy; $w++) {
                                    $id_plutonu = pg_fetch_result($wynik, $w, 'id_plutonu');
                                    $nr_plutonu = pg_fetch_result($wynik, $w, 'nr_plutonu');
                                    echo "<option value=$id_plutonu>$nr_plutonu</option>";
                                }

                    echo "
                                </select>
                                <label for='kto_dowodzi'>Dowódca</label>
                                <select id='kto_dowodzi' name='kto_dowodzi'>";

                                $query = "SELECT id_zolnierza, imie, nazwisko FROM zolnierz WHERE stopien > '2';";
                                $wynik = pg_query($polaczenie, $query);
                                $liczba_wierszy = pg_num_rows($wynik);

                                for($w = 0; $w < $liczba_wierszy; $w++) {
                                    $id_zolnierza = pg_fetch_result($wynik, $w, 'id_zolnierza');
                                    $imie = pg_fetch_result($wynik, $w, 'imie');
                                    $nazwisko = pg_fetch_result($wynik, $w, 'nazwisko');
                                    echo "<option value=$id_zolnierza>$imie $nazwisko</option>";
			                	}
						echo "
								</select>

								<input type= hidden name=tabela value=$tabela>
								<input type= hidden name=nazwa_id value=$nazwa_id>
								<input type='hidden' name='idos' value=$id>
								<input type='submit' name='zmien' value='Zmień'>
							</form>	
						</div>
					</div>
				</div>
			</div>";
	}

	pg_close($polaczenie);

	?>
		<script>
			var modal = document.getElementById('myModal');
			var span = document.getElementsByClassName("close")[0];

			span.onclick = function () {
				modal.style.display = "none";
			}

			window.onclick = function (event) {
				if (event.target == modal) {
					modal.style.display = "none";
				}
			}
		</script>
	</body>

	</html>