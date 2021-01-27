<?php
    if (isset($_GET['idBiletuct']) == false)
    {
        header('Location:panel.php');
        exit();
    }

    session_start();
    $kasjerLogin = $_SESSION['kasjerName'];
    $idBiletu = $_GET['idBiletuct'];
    $input_query = "UPDATE Zamowienie SET statusZamowienia = 'ACCEPTED', kasjer = '".$kasjerLogin."' WHERE idBiletu = ".$idBiletu;

    require_once 'connect.php';
    $conn = pg_connect($connectInfo);
    $query = pg_query($conn, $input_query);
    if ($query == false)
    {
        $_SESSION['panelMessage'] = "Nastąpił błąd!";
        header('Location:panel.php');
        exit();
    }
    pg_close($conn);
?>