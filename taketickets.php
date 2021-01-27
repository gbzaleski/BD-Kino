<?php
	session_start();

	if (isset($_POST['submitres']) == false)
	{
		header('Location:index.php');
		exit();
	}
    
    $seansId = pg_escape_string($_POST['showid']);
    $emailadres = pg_escape_string($_POST['emailadd']);
    $miejsca = pg_escape_string($_POST['amountInput']);

    $update_queary = "UPDATE Seans SET wolneMiejsca = wolneMiejsca - ".$miejsca." WHERE idSeansu = ".$seansId;
    $get_new_id_quary = "SELECT max(idBiletu) + 1 FROM Zamowienie";

	try 
	{
		require_once 'connect.php';
		$conn = pg_connect($connectInfo);
	} 
	Catch (Exception $e) 
	{
		$_SESSION['errMessage'] = "Nastąpił błąd!";
		header('Location:index.php');
		exit();
	}
	// Wykonanie serii operacji jako tranzakcje.
	
	pg_query("BEGIN");
	$query1 = pg_query($conn, $update_queary);

	$query2 = pg_query($conn, $get_new_id_quary);
	
	$newIdBiletu = pg_fetch_array($query2)[0];
	if ($newIdBiletu == null)
		$newIdBiletu = 1;

	$input_query = "INSERT INTO Zamowienie VALUES(".$newIdBiletu.", '".$emailadres."', ".$miejsca.", 'WAITING', ".$seansId.", null)";
	$query3 = pg_query($conn, $input_query);

	if ($query1 && $query2 && $query3)
	{
		pg_query("COMMIT");

		$_SESSION['fineMessage'] = "Rezerwacja wykonana pomyślnie!";
		header('Location:index.php');	
	}
	else
	{
		pg_query("ROLLBACK");
		$_SESSION['errMessage'] = "Nastąpił błąd!";
		header('Location:index.php');
	}
	pg_close($conn);
?>