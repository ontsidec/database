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
		<li><a class="active" href="nalezy.php">Służba</a></li>
        <li><a href="druzyna.php">Drużyny</a></li>
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

	$tabela = 'nalezy';
	$nazwa_id = 'kto_nalezy';

	$query = "SELECT id_zolnierza, imie, nazwisko FROM zolnierz;";
	$wynik = pg_query($polaczenie, $query);
	$liczba_wierszy = pg_num_rows($wynik);

	echo "
		<div id='dodaj'>
			<form action=dodaj.php method=POST>
				<label for='kto_nalezy'>Żołnierz</label>
				<select id='kto_nalezy' name='kto_nalezy'>";

				for($w = 0; $w < $liczba_wierszy; $w++) {
					$id_zolnierza = pg_fetch_result($wynik, $w, 'id_zolnierza');
					$imie = pg_fetch_result($wynik, $w, 'imie');
                    $nazwisko = pg_fetch_result($wynik, $w, 'nazwisko');
					echo "<option value=$id_zolnierza>$imie $nazwisko</option>";
				}
	echo "
				</select>
                <label for='gdzie_nalezy'>Przydział drużyny</label>
				<select id='gdzie_nalezy' name='gdzie_nalezy'>";

                $query = "SELECT id_druzyny, nr_druzyny FROM druzyna;";
                $wynik = pg_query($polaczenie, $query);
                $liczba_wierszy = pg_num_rows($wynik);

                for($w = 0; $w < $liczba_wierszy; $w++) {
					$id_druzyny = pg_fetch_result($wynik, $w, 'id_druzyny');
					$nr_druzyny = pg_fetch_result($wynik, $w, 'nr_druzyny');
					echo "<option value=$id_druzyny>$nr_druzyny</option>";
				}

    echo "
                </select>
                <label for='od_kiedy'>Początek służby</label>
				<input type='text' id='od_kiedy' name='od_kiedy'>

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

	$query = "SELECT z.imie, z.nazwisko, d.nr_druzyny, p.nr_plutonu, k.nr_kompanii, n.od_kiedy, n.do_kiedy 
			FROM nalezy n JOIN zolnierz z ON  n.kto_nalezy = z.id_zolnierza JOIN druzyna d ON n.gdzie_nalezy = d.id_druzyny JOIN pluton p ON d.nr_plutonu = p.id_plutonu JOIN kompania k ON p.nr_kompanii = k.id_kompanii
			ORDER BY k.nr_kompanii;";

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
		$id2 = pg_fetch_result($wynik, $w, 1);
		$id3 = pg_fetch_result($wynik, $w, 2);
		$id4 = pg_fetch_result($wynik, $w, 3);
		$id5 = pg_fetch_result($wynik, $w, 4);
		$id6 = pg_fetch_result($wynik, $w, 5);
		echo "<td><div id='edycja'><form action=usun.php method=POST>
				<input type=hidden name=id value=$id>
				<input type=hidden name=id2 value=$id2>
				<input type=hidden name=id3 value=$id3>
				<input type=hidden name=id4 value=$id4>
				<input type=hidden name=id5 value=$id5>
				<input type=hidden name=id6 value=$id6>
				<input type=hidden name=tabela value=$tabela>
				<input type=submit name=usun value=Usuń></form></div></td>";

			
		echo "<td><div id='edycja'><form action='' method=POST>
				<input type=hidden name=idos value=$id>
				<input type=hidden name=id2 value=$id2>
				<input type=hidden name=id3 value=$id3>
				<input type=hidden name=id4 value=$id4>
				<input type=hidden name=id5 value=$id5>
				<input type=hidden name=id6 value=$id6>
				<input type=submit id='myBtn' name=zmien value=Edytuj></form></div></td>
			</tr>";
	}

	echo "</table>";

	if ($_POST)	{
		$id = $_POST['idos'];
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

		$query = "SELECT z.imie, z.nazwisko, d.nr_druzyny, p.nr_plutonu, k.nr_kompanii, n.od_kiedy, n.do_kiedy  
			FROM nalezy n JOIN zolnierz z ON  n.kto_nalezy = z.id_zolnierza AND n.kto_nalezy = '$id_zolnierza' JOIN druzyna d ON n.gdzie_nalezy = d.id_druzyny AND n.gdzie_nalezy = '$id_druzyny' JOIN pluton p ON d.nr_plutonu = p.id_plutonu JOIN kompania k ON p.nr_kompanii = k.id_kompanii WHERE n.od_kiedy = '$id6';";
		$wynik = pg_query($query);
		$imie = pg_fetch_result($wynik, 0, 'imie');
        $nazwisko = pg_fetch_result($wynik, 0, 'nazwisko');
        $nr_druzyny = pg_fetch_result($wynik, 0, 'nr_druzyny');
        $nr_plutonu = pg_fetch_result($wynik, 0, 'nr_plutonu');
		$nr_kompanii = pg_fetch_result($wynik, 0, 'nr_kompanii');
		$od_kiedy = pg_fetch_result($wynik, 0, 'od_kiedy');
		$do_kiedy = pg_fetch_result($wynik, 0, 'do_kiedy');

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
								<div id='dane'>$imie $nazwisko $nr_druzyny $nr_plutonu $nr_kompanii $od_kiedy $do_kiedy<br></div> 
							</div>";

						echo "
							<form action=edytuj.php method=POST>
								<input type='hidden' name='p_id_zolnierza' value=$id_zolnierza>
								<input type='hidden' name='p_id_druzyny' value=$id_druzyny>
								<input type='hidden' name='p_od_kiedy' value=$id6>

                                <label for='kto_nalezy'>Żołnierz</label>
								<select id='kto_nalezy' name='kto_nalezy'>";

								$query = "SELECT id_zolnierza, imie, nazwisko FROM zolnierz;";
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
								<label for='gdzie_nalezy'>Przydział drużyny</label>
								<select id='gdzie_nalezy' name='gdzie_nalezy'>";

								$query = "SELECT id_druzyny, nr_druzyny FROM druzyna;";
								$wynik = pg_query($polaczenie, $query);
								$liczba_wierszy = pg_num_rows($wynik);

								for($w = 0; $w < $liczba_wierszy; $w++) {
									$id_druzyny = pg_fetch_result($wynik, $w, 'id_druzyny');
									$nr_druzyny = pg_fetch_result($wynik, $w, 'nr_druzyny');
									echo "<option value=$id_druzyny>$nr_druzyny</option>";
								}

						echo "
								</select>
								<label for='od_kiedy'>Początek służby</label>
								<input type='text' id='od_kiedy' name='od_kiedy'>

								<label for='do_kiedy'>Koniec służby</label>
								<input type='text' id='do_kiedy' name='do_kiedy'>

								<input type='hidden' name='tabela' value=$tabela>
								<input type='hidden' name='nazwa_id' value=$nazwa_id>
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