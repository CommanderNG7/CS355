<?php

session_start(); 

require 'database.php';

if ( !empty($_POST)) { // if $_POST filled then process the form

	// initialize $_POST variables
	$email = $_POST['email'];
	$password = $_POST['password'];
	$passwordhash = MD5($password);
		
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM players WHERE email = ? AND hash = ? AND valid = '1' LIMIT 1";
	$q = $pdo->prepare($sql);
	$q->execute(array($email,$passwordhash));
	$data = $q->fetch(PDO::FETCH_ASSOC);
	if($data) { // if successful login set session variables
		echo "success!";
		$_SESSION['id'] = $data['id'];
		$sessionid = $data['id'];
		$_SESSION['name'] = $data['email'];
		Database::disconnect();
		header("Location: games.php?id=$sessionid ");
		// javascript below is necessary for system to work on github
		echo "<script type='text/javascript'> document.location = 'games.php'; </script>";
		exit();
	}
	else { // otherwise go to login error page
		Database::disconnect();
		header("Location: login_error.html");
	}
} 
// if $_POST NOT filled then display login form, below.

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">

		<div class="span10 offset1">
		
			<div class="row">
			</div>


			<div class="row">
				<h3>Player Login</h3>
			</div>

			<form class="form-horizontal" action="login.php" method="post">
								  
				<div class="control-group">
					<label class="control-label">Email</label>
					<div class="controls">
						<input name="email" type="text"  placeholder="me@email.com" required> 
					</div>	
				</div> 
				
				<div class="control-group">
					<label class="control-label">Password</label>
					<div class="controls">
						<input name="password" type="password" placeholder="password" required> 
					</div>	
				</div> 

				<div class="form-actions">
					<button type="submit" class="btn btn-success">Sign in</button>
					&nbsp; &nbsp;
					<a class="btn btn-primary" href="player_create.php">Join (New Player)</a>
				</div>
				
			</form>


		</div> <!-- end div: class="span10 offset1" -->
				
    </div> <!-- end div: class="container" -->

  </body>
  
</html>
