<?php
    if (isset($_GET['idBileturt']) == false)
    {
        header('Location:panel.php');
        exit();
    }

    session_start();
    $kasjerLogin = $_SESSION['kasjerName'];
    $idBiletu = $_GET['idBileturt'];
    $noweWolneMiejsca = $_GET['noweWolneMiejscart'];
    $idSeansu = $_GET['idSeansurt'];
    $input_query = "UPDATE Zamowienie SET statusZamowienia = 'REJECTED', kasjer = '".$kasjerLogin."' WHERE idBiletu = ".$idBiletu;
    $input_query_update = "UPDATE Seans SET wolneMiejsca = wolneMiejsca + ".$noweWolneMiejsca." WHERE idSeansu = ".$idSeansu;

    require_once 'connect.php';
    $conn = pg_connect($connectInfo);

    // Wykonanie serii operacji jako tranzakcje.
	pg_query("BEGIN");

    $query1 = pg_query($conn, $input_query);
    $query2 = pg_query($conn, $input_query_update);

    if ($query1 && $query2)
	{
		pg_query("COMMIT");
	}
	else
	{
		pg_query("ROLLBACK");
		$_SESSION['panelMessage'] = "Nastąpił błąd!";
    }
    
    header('Location:panel.php');
    pg_close($conn);

?>