<?php 
	session_start();
	if (isset($_SESSION['loggedIn']) == false || $_SESSION['loggedIn'] != true) 
	{
		header('Location:login.php');
		exit();
	}
?>
<!DOCTYPE HTML>
<html lang="pl">

<head>
    <meta charset="utf-8" />
    <title>CinemaSigma</title>
    <meta name="description" content="Grzegorz Zaleski" />
    <meta name="keywords" content="elitarnosc, mim, student, zycie, pls-help" />
    <meta http-equiv = "X-UA-Compatible" content = "IE=edge,chrome=1" />
	<link rel = "stylesheet" href = "style.css" type = "text/css" />
	<link rel = "stylesheet" href = "stylepanel.css" type = "text/css" />
	<link href="https://fonts.googleapis.com/css?family=Lato:400,700&amp;subset=latin-ext" rel="stylesheet">
	<link rel="icon" type="image/png" href="sigma.png">	
	<script src="jspanel.js"></script>
	<script src="../JQ.js"></script>
</head>
  
<body onload = "startPage()">
	<div class = "wrapper">
		<div class = "header"> 
			<div class = "logo">
					<img src = "sigma.png" class = "photologo" />
					<div style = "float:left;"><span style ="color: #FF0033"> Cinema</span>Sigma</div>
					<img src = "sigma.png" style = "" class = "photologo" />
					<div style = "clear:both"></div>
			</div>
		</div>
		
		<div class = "nav">
			<div class = "box">
				<a href  = "../">Home</a>
			</div>
			<div class = "box">
				<a href  = "index.php">Wyszukiwarka</a>
			</div>
			<div class = "box">
				<a href  = "panel.php">Panel <?php echo "[".$_SESSION['kasjerName']."]";?></a>
			</div>
			<div class = "box">
				<a href  = "logout.php">Wyloguj</a>
			</div>
		</div>
		
		<div class ="content" id="content">
		<?php
			ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
			if (isset($_SESSION['panelMessage']))
			{
				echo '<script type="text/javascript">alert("'.$_SESSION['panelMessage'].'")</script>';
				unset($_SESSION['panelMessage']);
			}
			

			require_once 'connect.php';
			$conn = pg_connect($connectInfo);
			$input_query = "SELECT * FROM Zamowienie WHERE statusZamowienia = 'WAITING'";
			$query = pg_query($conn, $input_query);

			echo<<<END
				<center><div class = "imber" onclick = "hide_show_new_resv()">Złożone rezerwacje:</div></center>
				<table id = "restable" align="center" border="1" bordercolor="#d5d5d5" cellpadding="0" cellspacing="0">     
				<tr><td align="center" bgcolor="e5e5e5"><b>Bilet</b></td>
				<td align="center" bgcolor="e5e5e5"<b>Właściciel</b></td>
				<td align="center" bgcolor="e5e5e5"><b>L. Miejsc</b></td>
				<td align="center" bgcolor="e5e5e5"><b>Seans</b></td>
				<td align="center" bgcolor="e5e5e5"><b>Akcje</b></td>
				</tr><br>
			END;
			$none_res = true;
			while ($row = pg_fetch_array($query)) 
			{
				$none_res = false;
				#echo $row['idbiletu']." ".$row['wlasciciel']." ".$row['liczbamiejsc']." ".$row['seans']." ".$row['statuszamowienia']."<br>";
				$SeansId = $row['seans'];
				$biletId = $row['idbiletu'];

				echo '<tr id="tr'.$biletId.'" ><td id="t1'.$biletId.'" class="down" align="center">'.$row['idbiletu'].'</td>';
				echo '<td id="t2'.$biletId.'" class="down shorten" align="center"><div class ="scrollable">'.$row['wlasciciel'].'</div></td>';
				echo '<td id="t3'.$biletId.'" class="down" align="center">'.$row['liczbamiejsc'].'</td>';
				echo '<td id="t4'.$biletId.'" class="down" align="center">'.$SeansId.'</td>';
				echo '<td id="t4'.$biletId.'" class="down" align="center"><a onclick = "confirmTicket('.$biletId.')" class = "butaccept" >Zatwierdź</a><a onclick = "rejectTicket('.$biletId.', '.$row['liczbamiejsc'].', '.$SeansId.')" class = "butreject">Odrzuć</a></td>';
				echo '</tr>';
			}
			echo '</table>';
			if ($none_res)
				echo '<script type="text/javascript">hide_show_new_resv()</script>';

			echo '<center><div class = "imber" onclick = "hide_show_hales()">Sale:</div></center>';
			$input_query = "SELECT * FROM Sala";
			$query = pg_query($conn, $input_query);
			echo<<<END
				<table id = "reshales" align="center" border="1" bordercolor="#d5d5d5" cellpadding="0" cellspacing="0">     
				<tr><td align="center" bgcolor="e5e5e5"><b>Nr. Sali</b></td>
				<td align="center" bgcolor="e5e5e5"><b>Miejsca</b></td>
				<td align="center" bgcolor="e5e5e5"><b>Akcje</b></td>
				</tr><br>
			END;
			while ($row = pg_fetch_array($query)) 
			{
				echo '<tr id = "trh'.$row['nrsali'].'"><td class="down" align="center">'.$row['nrsali'].'</td>';
				echo '<td class="down" align="center">'.$row['liczbamiejsc'].'</td>';
				echo '<td class="down" align="center"><a onclick = "deleteHall('.$row['nrsali'].')" class = "butreject">Usuń</a></td>';
				echo '</tr>';
			}
			echo '</table>';
			?>
		<div class = "form" id = "formhall">
			<form method="POST" action="newhall.php" class ="queryform">
			<label for="liczba">Liczba Miejsc:</label><br>
			<input type="number" id="liczba" name="liczba" required><br>
			<center><div class="wrap"><button name ="enter" TYPE="submit" VALUE="Pokaż" id = "submit" class="buttonnoanim button">Dodaj Sale</button></div></center>
			</form>
		</div>
			<?php
			echo '<center><div class = "imber" onclick = "hide_show_films()">Filmy:</div></center>';
			$input_query = "SELECT * FROM Film";
			$query = pg_query($conn, $input_query);

			echo<<<END
				<table id = "resfilms" align="center" border="1" bordercolor="#d5d5d5" cellpadding="0" cellspacing="0">     
				<tr><td align="center" bgcolor="e5e5e5"><b>Tytuł</b></td>
				<td align="center" bgcolor="e5e5e5"><b>Reżyser</b></td>
				<td align="center" bgcolor="e5e5e5"><b>Gatunek</b></td>
				<td align="center" bgcolor="e5e5e5"><b>Długość</b></td>
				<td align="center" bgcolor="e5e5e5"><b>Opis</b></td>
				<td align="center" bgcolor="e5e5e5"><b>Akcje</b></td>
				</tr><br>
			END;
			while ($row = pg_fetch_array($query)) 
			{
				echo '<tr id = "trf'.$row['idfilmu'].'"><td class="down" align="center">'.$row['tytul'].'</td>';
				echo '<td class="down" align="center">'.$row['rezyser'].'</td>';
				echo '<td class="down" align="center">'.$row['gatunek'].'</td>';
				$dlugosc = $row['czaswyswietlania'];
				if ($dlugosc[0] == '0')
					$dlugosc = substr($dlugosc, 1);
				if ($dlugosc[0] == '0')
					$dlugosc = substr($dlugosc, 2);
				echo '<td class="down" align="center">'.$dlugosc.'</td>';
				echo '<td class="down shorten" align="center"><div class ="scrollable">'.$row['opis'].'</div></td>';
				echo '<td class="down" align="center"><a onclick = "deleteFilm('.$row['idfilmu'].')" class = "butreject">Usuń</a></td>';
				echo '</tr>';
			}
			echo '</table>';
			?>
		<div class = "form" id = "formfilm">
			<form method="POST" action="newfilm.php" class ="queryform">
			<label for="title">Tytuł:</label><br>
			<input type="text" id="title" name="title" required><br>
			<label for="year">Rok produkcji:</label><br>
			<input type="number" id="year" name="year" required><br>
			<label for="director">Reżyser:</label><br>
			<input type="text" id="director" name="director" required><br>
			<label for="genre">Gatunek:</label><br>
			<input type="text" id="genre" name="genre" required><br>
			<label for="length">Długość:</label><br>
			<input type="time" id="length" name="length" required><br>
			<label for="desc">Opis:</label><br>
			<input type="text" id="desc" name="desc" required><br>
			<center><div class="wrap"><button name ="enter" TYPE="submit" VALUE="Pokaż" id = "submit" class="buttonnoanim button">Dodaj Film</button></div></center>
			</form>
		</div>

			<?php
			echo '<center><div class = "imber" onclick = "hide_show_screenings()">Seanse:</div></center>';
			$input_query = "SELECT * FROM Seans JOIN Film on film = idFilmu ORDER BY dzienTygodnia, godzina";
			$query = pg_query($conn, $input_query);

			$tydzien = array(
				0  => "Poniedziałek",
				1  => "Wtorek",
				2  => "Środa",
				3  => "Czwartek",
				4  => "Piątek",
				5  => "Sobota",
				6  => "Niedziela",
			);

			echo<<<END
				<table id = "resscreens" align="center" border="1" bordercolor="#d5d5d5" cellpadding="0" cellspacing="0">     
				<tr><td align="center" bgcolor="e5e5e5"><b>Seans</b></td>
				<td align="center" bgcolor="e5e5e5"><b>Film</b></td>
				<td align="center" bgcolor="e5e5e5"><b>Sala</b></td>
				<td align="center" bgcolor="e5e5e5"><b>Kiedy</b></td>
				<td align="center" bgcolor="e5e5e5"><b>Akcje</b></td>
				</tr><br>
			END;
			while ($row = pg_fetch_array($query)) 
			{
				if ($row['wolnemiejsca'] > 0)
					echo '<tr id = "trs'.$row['idseansu'].'">';
				else 
					echo '<tr style = "background-color: #CD5C5C;" id = "trs'.$row['idseansu'].'">';
				echo '<td class="down" align="center">'.$row['idseansu'].'</td>';
				echo '<td class="down" align="center">'.$row['tytul'].'</td>';
				echo '<td class="down" align="center">'.$row['sala'].'</td>';
				echo '<td class="down" align="center">'.$tydzien[$row['dzientygodnia']].' '.substr($row['godzina'], 0, 5).'</td>';
				echo '<td class="down" align="center"><a onclick = "deleteScreening('.$row['idseansu'].')" class = "butreject">Usuń</a></td>';
				echo '</tr>';
			}
			echo '</table>';
			?>

		<div class = "form" id = "formscreening">
			<form method="POST" action="newscreening.php" class ="queryform">
			<label for="film">Film:</label><br>
			<select name="film" id="film">
				<?php
					$input_query = "SELECT idfilmu, tytul FROM Film";
					$query = pg_query($conn, $input_query);
					while ($row = pg_fetch_array($query)) 
					{
						echo '<option value = "'.$row[0].'">'.$row[1];
					}
				?>
			</select><br>
			<label for="hall">Sala:</label><br>
			<select name="hall" id="hall">
				<?php
					$input_query = "SELECT nrSali, liczbaMiejsc FROM Sala";
					$query = pg_query($conn, $input_query);
					while ($row = pg_fetch_array($query)) 
					{
						echo '<option value = "'.$row[0].'">'.$row[0].' - '.$row[1].' os.';
					}
				?>
			</select><br>	
			<label for="day">Dzień:</label><br>
			<select name="day" id="day">
				<option value = 0>Poniedziałek
				<option value = 1>Wtorek
				<option value = 2>Środa
				<option value = 3>Czwartek
				<option value = 4>Piątek
				<option value = 5>Sobota
				<option value = 6>Niedziela
			</select><br>
			<label for="time">Godzina:</label><br>
			<input type="time" id="time" name="time" required><br>
			<label for="freeSeats">Liczba biletów:</label><br>
			<input type="number" id="freeSeats" name="freeSeats" required><br>
			<center><div class="wrap"><button name ="enter" TYPE="submit" VALUE="Pokaż" id = "submit" class="buttonnoanim button">Dodaj Seans</button></div></center>
			</form>
		</div>

			<?php
			echo '<center><div class = "imber" onclick = "hide_show_old_resv()">Zamówienia zrealizowane:</div></center>';
			$input_query = "SELECT * FROM Zamowienie WHERE statusZamowienia != 'WAITING'";
			$query = pg_query($conn, $input_query);
			echo<<<END
				<table id = "oldrestable" align="center" border="1" bordercolor="#d5d5d5" cellpadding="0" cellspacing="0">     
				<tr><td align="center" bgcolor="e5e5e5"><b>Bilet</b></td>
				<td align="center" bgcolor="e5e5e5"<b>Właściciel</b></td>
				<td align="center" bgcolor="e5e5e5"><b>L. Miejsc</b></td>
				<td align="center" bgcolor="e5e5e5"><b>Seans</b></td>
				<td align="center" bgcolor="e5e5e5"><b>Kasjer</b></td>
				</tr><br>
			END;
			while ($row = pg_fetch_array($query)) 
			{
				if ($row['statuszamowienia'] == 'ACCEPTED')
					echo '<tr style = "background-color: #90ee90;"><td class="down" align="center">'.$row['idbiletu'].'</td>';
				else
					echo '<tr style = "background-color: #CD5C5C;"><td class="down" align="center">'.$row['idbiletu'].'</td>';
				echo '<td class="down shorten" align="center"><div class ="scrollable">'.$row['wlasciciel'].'</div></td>';
				echo '<td class="down" align="center">'.$row['liczbamiejsc'].'</td>';
				echo '<td class="down" align="center">'.$row['seans'].'</td>';
				echo '<td class="down" align="center">'.$row['kasjer'].'</td>';
				echo '</tr>';
			}
			echo '</table>';

			pg_close($conn);
		?>
		</div>
		<div class = "authors"> Sigma Cinema <span style="margin-left: 10px;"></span>  &copy 2020
		</div>
	</div>
   </body>
</html>
