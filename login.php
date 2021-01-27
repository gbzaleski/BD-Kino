<?php 
	session_start();
	if (isset($_SESSION['loggedIn'])) 
		if($_SESSION['loggedIn'] == true)
		{		
			header('Location:panel.php');
			exit();
		}
	#session_unset();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="UTF-8">
	<title>CinemaSigma</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chromie=1"/>
	<link href="https://fonts.googleapis.com/css?family=Lato:400,700&amp;subset=latin-ext" rel="stylesheet">
	<link rel="icon" type="image/png" href="sigma.png">
	<meta name="viewport" content="width=device-width, initial-scale=1">	
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
	<div class = "wrapper">
	<div class="limiter">
		<div class="container-login100" >
			<div class="wrap-login100 p-l-85 p-r-85 p-t-55 p-b-55" style="margin-top: -50px;">
				<div style="text-align:center;margin-bottom: 15px;font-size: 22.79px;">
					<b>Panel Kasjera</b> by Cinema Sigma
				</div>
				<form class="login100-form validate-form flex-sb flex-w" action="proceed.php" method="post">
					<span class="login100-form-title p-b-32">
						Logowanie DO sySteMu
					</span>

					<span class="txt1 p-b-11">
						Login
					</span>
					<div class="wrap-input100 validate-input m-b-36" data-validate = "Nie podano nazwy użytkownika!">
						<input class="input100" type="text"  name="login">
						<span class="focus-input100"></span>
					</div>
					
					<span class="txt1 p-b-11">
						Hasło
					</span>
					<div class="wrap-input100 validate-input m-b-12" data-validate = "Nie podano hasła!">
						<span class="btn-show-pass">
							<i class="fa fa-eye"></i>
						</span>
						<input class="input100" type="password" name="password">
						<span class="focus-input100"></span>
					</div>
					<div style="margin-bottom: 15px;margin-top: 5px;">
					<?php
						if (isset($_SESSION['logError'])) 
						{
							echo $_SESSION['logError'];
							unset($_SESSION['logError']);
						}
					?>
					</div>
					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Zaloguj
						</button>
					</div>
				</form>
				<div style="text-align: center;margin-top: 25px;margin-bottom: -30px;font-size:20px;"><a href="index.php" style ="font-size:17px;">Powrót</a><div>
			</div>
		</div>
	</div>
	
	<div id="dropDownSelect1"></div>
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="vendor/animsition/js/animsition.min.js"></script>
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="vendor/select2/select2.min.js"></script>
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
	<script src="vendor/countdowntime/countdowntime.js"></script>
	<script src="js/main.js"></script>

	<div style = "clear:both;"></div>
	</div>
</body>
</html>