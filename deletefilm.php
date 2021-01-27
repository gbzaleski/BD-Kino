<?php
    if (isset($_GET['idFilmu']) == false)
    {
        header('Location:panel.php');
        exit();
    }

    session_start();
    $idFilmu = $_GET['idFilmu'];
    $input_query = "DELETE FROM Film WHERE idFilmu = ".$idFilmu;

    require_once 'connect.php';
    $conn = pg_connect($connectInfo);
    pg_query($conn, $input_query);
    if ($query == false)
    {
        $_SESSION['panelMessage'] = "Nastąpił błąd!";
    }
    pg_close($conn);
?>