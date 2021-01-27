<?php
    if (isset($_GET['nrSali']) == false)
    {
        header('Location:panel.php');
        exit();
    }

    session_start();
    $nrSali = $_GET['nrSali'];
    $input_query = "DELETE FROM Sala WHERE nrSali = ".$nrSali;

    require_once 'connect.php';
    $conn = pg_connect($connectInfo);
    pg_query($conn, $input_query);
    if ($query == false)
    {
        $_SESSION['panelMessage'] = "Nastąpił błąd!";
    }
    pg_close($conn);
?>