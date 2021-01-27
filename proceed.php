<?php
	session_start();

	if (isset($_POST['login']) == false OR isset($_POST['password']) == false)
	{
		header('Location:login.php');
		exit();
	}
	
	try 
	{
		require_once 'connect.php';
		$conn = pg_connect($connectInfo);
	} 
	Catch (Exception $e) 
	{
		$_SESSION['logError'] = '<div id="errIcon" style="color:red; font-weight:800;">Nastąpił błąd!!</div>';
		header('Location:login.php');
		exit;
	}

	$name = pg_escape_string($_POST['login']);
	$password = hash('whirlpool', $_POST['password']);
	$input_query = "SELECT * FROM Kasjer WHERE loginname = '".$name."' AND hashpass = '".$password."'";
	$query = pg_query($conn, $input_query);
	
	if (pg_num_rows($query) == 1)
	{
		$row = pg_fetch_array($query);
		$_SESSION['loggedIn'] = true;
		$_SESSION['kasjerName'] = $name;
		unset($_SESSION['logError']);			
		header('Location:panel.php');

	}	
	else
	{	
		$_SESSION['logError'] = '<div id="errIcon" style="color:red; font-weight:800;">Niepoprawny login lub hasło!</div>';
		header('Location:login.php');
	}

	pg_close($conn);
?>