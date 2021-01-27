<?php 
	session_start();
	session_unset();

	if (isset($_POST['login']))
	{
		$Validated = true;

		$login = $_POST['login'];
		if ((strlen($login) < 5) OR (strlen($login) > 15))
		{
			 $Validated = false;	
			 $_SESSSION['errLogin'] = '<div class="err">Login musi posiadać od 5 do 15 znakow!</div>';
		}
		if (ctype_alnum($login) == false)
		{
			 $Validated = false;	
			 $_SESSSION['errLogin'] = '<div class="err">Błędne znaki w loginie!</div>';
		}

		$mail = strtolower($_POST['email']);

		$password1 = $_POST['pass1'];	
		$password2 = $_POST['pass2'];
		if ((strlen($password1) < 5) OR (strlen($password2) > 15))
		{
			 $Validated = false;	
			 $_SESSSION['errPass'] = '<div class="err">Hasło musi posiadać od 5 do 15 znakow!</div>';
		}
		if (ctype_alnum($password1) == false)
		{
			 $Validated = false;	
			 $_SESSSION['errPass'] = '<div class="err">Błędne znaki w haśle!</div>';
		}
		if ($password1 != $password2)
		{
			$Validated = false;	
			$_SESSSION['errPass'] = '<div class="err">Powtórzone hasło jest inne!</div>';
		}
		$hashedPass = password_hash($password1,PASSWORD_DEFAULT);

		if (isset($_POST['accepted']) == false) 
		{
			$Validated = false; 
			$_SESSSION['errReg'] = '<div class="err">Regulamin nie został zaakceptowany</div>';
		}

		$secKey = "6Lcvq04UAAAAABRO94OSXTbyUDlIk9_nNsD0BwM9";
		$CAPTCHAchecker = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secKey.'&response='.$_POST['g-recaptcha-response']);
		$CAPTCHAres = json_decode($CAPTCHAchecker);

		if ($CAPTCHAres->success == false)
		{
			$Validated = false;
			$_SESSSION['errBot'] = '<div class="err">Błąd CAPTCHA - Potwiedź że nie jesteś botem!</div>';
		}

		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		try
		{
			$con = new mysqli($host, $dbUser, $dbPassword, $dbName);
			if ($con->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				$res = $con->query(sprintf("SELECT * FROM uzytkownicy WHERE email='%s'",$mail));
				if ($res == false)
				{
					throw new Exception($con->error);
				}
				else 
				{
					$found = $res->num_rows;
					if ($found > 0)
					{
						$Validated = false;
						$_SESSSION['errMail'] = '<div class="err">Taki adres email jest już zarejestrowany!</div>';
					}
				}

				$res = $con->query(sprintf("SELECT * FROM uzytkownicy WHERE user='%s'",$login));
				if ($res == false)
				{
					throw new Exception($con->error);
				}
				else 
				{
					$found = $res->num_rows;
					if ($found > 0)
					{
						$Validated = false;
						$_SESSSION['errLogin'] = '<div class="err">Taki login jest już zajęty!</div>';
					}
				}

				if ($Validated == true)
				{
					if ($con->query("INSERT INTO `uzytkownicy` (`id`, `user`, `pass`, `email`, `drewno`, `drewnolvl`, `zelazo`, `zelazolvl`, `jedzenielvl`, `mags`, `bersers`, `curlevel`, `pierwszelog`, `ostatnielog`, `czasgry`, `koniecpremium`) 
						VALUES (NULL, '$login', '$hashedPass', '$mail', '500', '1', '500', '1', '1', '0', '0', '1', now(), now(), '0', now() + INTERVAL 3 DAY)"))
					{
						session_unset();
						$_SESSION['regged'] = $login;
						header('Location: index.php');
					}
					else
					{
					 	throw new Exception($con->error);
					}
				}
				$con->close();
			}
		}
		catch(Exception $e)
		{
			echo '<div class="err">Serwer nie odpowiadamy - przepraszamy i prosimy o rejestrację w innym terminie.</div>';
			#echo '</br>'.$e;
		}		
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7; IE=EmulateIE9">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>
    <link rel="stylesheet" type="text/css" href="style2.css" media="all" />
    <link rel="stylesheet" type="text/css" href="demo2.css" media="all" />
    <meta charset="utf-8"/>
	<title>Outlasting Thriver - Rejestracja</title>
	<script src='https://www.google.com/recaptcha/api.js'></script>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chromie=1"/>

	<style type="text/css">
	.err
		{
			color: red;
			font-weight: 800;
			margin: 5px;
			margin-top: 0px;
			margin-bottom: 10px;
		}

	</style>
</head>
<body style="background-color: #EBEBEB;">
<div class="container">
			<header>
				<h1><span>Rejestracja</span><b>Outlasting Thriver </b><i style="font-style: normal; font-weight: 400;">by Zaleski Projects</i></h1>
            </header> 

      <div class="form" style = "box-shadow: 0 0 3px #FF0000, 0 0 5px #0000FF;">
    		<form id="contactform" method="post"> 
    			<p class="contact"><label for="username">Nazwa użytkownika</label></p> 
    			<input id="username" name="login" placeholder="login" required="" tabindex="2" type="text"> 
    			 <?php 
				if (isset($_SESSSION['errLogin'])) 
				{
					echo $_SESSSION['errLogin'];
					unset($_SESSSION['errLogin']);
				}
		 		?>
    			<p class="contact"><label for="email">Adres email</label></p> 
    			<input id="email" name="email" placeholder="przykład@domena.com" required="" type="email">            
    			 <?php 
				if (isset($_SESSSION['errMail'])) 
				{
				echo $_SESSSION['errMail'];
				unset($_SESSSION['errMail']);
				}
				 ?>
                <p class="contact"><label for="password">Hasło</label></p> 
    			<input type="password" id="password" name="pass1" required=""> 
    			<?php 
				if (isset($_SESSSION['errPass'])) 
				{
					echo $_SESSSION['errPass'];
					unset($_SESSSION['errPass']);
				}
				 ?>
                <p class="contact"><label for="repassword">Powtórz hasło</label></p> 
    			<input type="password" id="repassword" name="pass2" required=""> 
    			<span></span><br>
        		<label>Akceptuję <a href="regulamin.pdf" target="_blank" style="margin-bottom: 15px;color:blue;">regulamin</a> <input id = "p" type="checkbox" name="accepted"/></label></br>
        		<?php 
				if (isset($_SESSSION['errReg'])) 
				{
					echo $_SESSSION['errReg'];
					unset($_SESSSION['errReg']);
				}
				 ?>
        		<div class="g-recaptcha" data-sitekey="6Lcvq04UAAAAAGCCQp-jTgl5BseuZKqAByly3IrQ"></div></br>
            	<?php 
				if (isset($_SESSSION['errBot'])) 
				{
					echo $_SESSSION['errBot'];
					unset($_SESSSION['errBot']);
				}
			 	?>
            <input class="buttom" name="submit" id="submit" tabindex="5" value="Zarejestruj się!" type="submit"> 	 
   </form> 
</div>      
</div>

</body>
</html>
