<?php
# Dowolny element z formularza
if (isset($_POST['title']) == false)
{
    header('Location:panel.php');
    exit();
}

session_start();
try 
{
    require_once 'connect.php';
    $conn = pg_connect($connectInfo);
} 
Catch (Exception $e) 
{
    $_SESSION['panelMessage'] = "Nastąpił błąd!";
    header('Location:panel.php');
    exit();
}

$get_new_id_quary = "SELECT max(idFilmu) + 1 FROM Film";
$query = pg_query($conn, $get_new_id_quary);
if ($query == false)
{
    $_SESSION['panelMessage'] = "Nastąpił błąd!";
    header('Location:panel.php');
    exit();
}
$idFilmu = pg_fetch_array($query)[0];
	if ($idFilmu == null)
        $idFilmu = 1;


$tytul = $_POST['title'];
$year = $_POST['year'];
$rezyser = $_POST['director'];
$gatunek = $_POST['genre'];
$dlugosc = $_POST['length'];
$opis = $_POST['desc'];

$input_query = "INSERT INTO Film VALUES ('".$idFilmu."', '".$tytul."', '".$year."', '".$rezyser."', '".$gatunek."', '".$dlugosc."', '".$opis."')";

$query = pg_query($conn, $input_query);
if ($query == false)
{
    $_SESSION['panelMessage'] = "Nastąpił błąd!";
}

header('Location:panel.php');
pg_close($conn);
