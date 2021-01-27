<?php
# Dowolny element z formularza
if (isset($_POST['time']) == false)
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

$get_new_id_quary = "SELECT max(idseansu) + 1 FROM Seans";
$query = pg_query($conn, $get_new_id_quary);
if ($query == false)
{
    $_SESSION['panelMessage'] = "Nastąpił błąd!";
    header('Location:panel.php');
    exit();
}
$idSeansu = pg_fetch_array($query)[0];
	if ($idSeansu == null)
        $idSeansu = 1;

$film = $_POST['film'];
$dzienTygodnia = $_POST['day'];
$godzina = $_POST['time'];
$wolneMiejsca = $_POST['freeSeats'];
$sala = $_POST['hall'];

$input_query = "INSERT INTO Seans VALUES ('".$idSeansu."', '".$dzienTygodnia."', '".$godzina."', '".$wolneMiejsca."', '".$sala."', '".$film."')";

$query = pg_query($conn, $input_query);
if ($query == false)
{
    $_SESSION['panelMessage'] = "Nastąpił błąd!";
}

header('Location:panel.php');
pg_close($conn);
