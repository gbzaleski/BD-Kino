<!DOCTYPE HTML>
<html lang="pl">

<head>
    <meta charset="utf-8" />
    <title>CinemaSigma</title>
    <meta name="description" content="Grzegorz Zaleski" />
    <meta name="keywords" content="elitarnosc, mim, student, zycie, pls-help" />
    <meta http-equiv = "X-UA-Compatible" content = "IE=edge,chrome=1" />
	<link rel = "stylesheet" href = "style.css" type = "text/css" />
	<link href="https://fonts.googleapis.com/css?family=Lato:400,700&amp;subset=latin-ext" rel="stylesheet">
	<link rel="icon" type="image/png" href="sigma.png">	
	<script src="js.js"></script>
	<script src="../JQ.js"></script>
</head>
  
<body>
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
				<a href  = "login.php">Panel Kasjera</a>
			</div>
		</div>
		
		<div class ="content" id="content" style = "color: black !IMPORTANT;">
		<div class = "form" id = "queryformdiv">
		<form method="POST" action="index.php" id = "queryform">
			<label for="title">Tytuł:</label><br>
			<input type="text" id="title" name="title"><br>
			<label for="director">Reżyser:</label><br>
			<input type="text" id="director" name="director"><br>
			<label for="genre">Gatunek:</label><br>
			<input type="text" id="genre" name="genre"><br>
			<label for="day">Dzień:</label><br>
			<select name="day" id="day">
				<option value = -1>
				<option value = 0>Poniedziałek
				<option value = 1>Wtorek
				<option value = 2>Środa
				<option value = 3>Czwartek
				<option value = 4>Piątek
				<option value = 5>Sobota
				<option value = 6>Niedziela
			</select><br>
			<label for="time">Godzina:</label><br>
			<input type="time" id="timefrom" name="timefrom"> ‐ <input type="time" id="timeto" name="timeto"><br>
			<center><div class="wrap"><button name ="enter" TYPE="submit" VALUE="Pokaż" id = "submit" class="button">Pokaż</button></div></center>
		</form>
		</div>

		<?php
		session_start();

		if (isset($_SESSION['fineMessage']))
		{
			echo '<script type="text/javascript">formHide()</script>';
			echo '<center style = "font-size: 150%; color: #7CFC00;">'.$_SESSION['fineMessage'].'<center>';
			echo '<center><div class="wrap"><a href = "index.php"><button class="buttondown button">Nowe Wyszukiwanie</button></a></div></center>';
			unset($_SESSION['fineMessage']);
		}

		else if (isset($_SESSION['errMessage']))
		{
			echo '<script type="text/javascript">formHide()</script>';
			echo '<center style = "font-size: 150%; color: #ff0033;">'.$_SESSION['errMessage'].'<center>';
			echo '<center><div class="wrap"><a href = "index.php"><button class="buttondown button">Nowe Wyszukiwanie</button></a></div></center>';
			unset($_SESSION['errMessage']);
		}

		else if (isset($_POST['enter']))
		{
			$title = ucwords(strtolower(pg_escape_string($_POST['title'])));
			$director = ucwords(strtolower(pg_escape_string($_POST['director'])));
			$genre = ucwords(strtolower(pg_escape_string($_POST['genre'])));
			$day = $_POST['day'];
			$timefrom = $_POST['timefrom'];
			$timeto = $_POST['timeto'];

			require_once 'connect.php';
			$conn = pg_connect($connectInfo);

			$input_query = "SELECT * FROM Seans
			LEFT JOIN Film ON film = idFilmu
			WHERE wolneMiejsca > 0
			AND (tytul LIKE '%".$title."%' OR tytul LIKE '%".strtolower($title)."%')
			AND (rezyser LIKE '%".$director."%' OR rezyser LIKE '%".strtolower($director)."%')
			AND (gatunek LIKE '%".$genre."%' OR gatunek LIKE '%".strtolower($genre)."%') ";

			if ($day != -1)
				$input_query .= "AND dzienTygodnia = ".$day." ";

			if ($timefrom != "")
				$input_query .= "AND godzina >= '".$timefrom."' ";

			if ($timeto != "")
				$input_query .= "AND godzina <= '".$timeto."' ";

			$input_query .= "ORDER BY dzienTygodnia, godzina";

			$query = pg_query($conn, $input_query);
			
			echo<<<END
				<table align="center" border="1" bordercolor="#d5d5d5" cellpadding="0" cellspacing="0">     
				<tr><td align="center" bgcolor="e5e5e5"><b>Tytuł</b></td>
				<td align="center" bgcolor="e5e5e5"><b>Rok Produkcji</b></td>
				<td align="center" bgcolor="e5e5e5"><b>Dzień</b></td>
				<td align="center" bgcolor="e5e5e5"><b>Rezyser</b></td>
				<td align="center" bgcolor="e5e5e5"><b>Gatunek</b></td>
				</tr><br>
			END;

			$tydzien = array(
				0  => "Poniedziałek",
				1  => "Wtorek",
				2  => "Środa",
				3  => "Czwartek",
				4  => "Piątek",
				5  => "Sobota",
				6  => "Niedziela",
			);

			while ($row = pg_fetch_array($query)) 
			{
				$SeansId = $row['idseansu'];
				$dzien = $row['dzientygodnia'];
				$godz = substr($row['godzina'], 0, 5);
				$wolneMiejsca = $row['wolnemiejsca'];

				#$film = pg_fetch_array(pg_query($conn, "SELECT * FROM Film WHERE idFilmu = ".$row['film']));

				$tytul = $row['tytul'];
				$rokProdukcji = $row['rokprodukcji'];
				$gatunek = $row['gatunek'];
				$rezyser = $row['rezyser'];
				$dlugosc_h = substr($row['czaswyswietlania'], 0, 2);
				$dlugosc_min = substr($row['czaswyswietlania'], 3, 2);	
				if ($dlugosc_h[0] == '0')
					$dlugosc_h = substr($dlugosc_h, 1, 1);
				if ($dlugosc_min[0] == '0')
					$dlugosc_min = substr($dlugosc_min, 1, 1);							
				$opis = $row['opis'];

				#echo $dlugosc_h. " h "; if ($dlugosc_min != 0) echo $dlugosc_min." min "; echo "<br>";
				

				echo '<tr id="tr'.$SeansId.'" onclick="show(\''.$opis.'\', '.$wolneMiejsca.', '.$SeansId.', '.$dlugosc_h.', '.$dlugosc_min.')" ><td id="t1'.$SeansId.'" class="down" align="center">'.$tytul.'</td>';
				echo '<td id="t2'.$SeansId.'" class="down" align="center">'.$rokProdukcji.'</td>';
				echo '<td id="t3'.$SeansId.'" class="down" align="center">'.$tydzien[$dzien].' '.$godz.'</td>';
				echo '<td id="t5'.$SeansId.'" class="down" align="center">'.$rezyser.'</td>';
				echo '<td id="t5'.$SeansId.'" class="down" align="center">'.$gatunek.'</td>';
				echo '</tr>';
			}
		
			echo '</table>';
			echo '<script type="text/javascript">formHide()</script>';
			echo '<center><div class="wrap"><a href = "index.php"><button class="buttondown button">Nowe Wyszukiwanie</button></a></div></center>';
			pg_close($conn);
		}
		?>
		
		</div>
		<div class = "authors">Cinema Sigma<span style="margin-left: 10px;"></span>  &copy 2020
		</div>
	</div>
   </body>
</html>
