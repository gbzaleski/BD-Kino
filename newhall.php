<?php
if (isset($_POST['liczba']) == false)
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

$wolneMiejsca = $_POST['liczba'];
$get_new_id_quary = "SELECT max(nrSali) + 1 FROM Sala";
$query = pg_query($conn, $get_new_id_quary);
$newNrSali = pg_fetch_array($query)[0];
	if ($newNrSali == null)
		$newNrSali = 1;

$input_query = "INSERT INTO Sala VALUES(".$newNrSali.", ".$wolneMiejsca.")";

$query = pg_query($conn, $input_query);
if ($query == false)
{
    $_SESSION['panelMessage'] = "Nastąpił błąd!";
}

header('Location:panel.php');
pg_close($conn);
