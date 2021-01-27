<?php
    if (isset($_GET['idSeansu']) == false)
    {
        header('Location:panel.php');
        exit();
    }

    session_start();
    $idSeansu = $_GET['idSeansu'];
    $input_query = "DELETE FROM Seans WHERE idSeansu = ".$idSeansu;

    require_once 'connect.php';
    $conn = pg_connect($connectInfo);
    pg_query($conn, $input_query);
    if ($query == false)
    {
        $_SESSION['panelMessage'] = "Nastąpił błąd!";
    }
    pg_close($conn);
?>