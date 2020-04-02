<?php

session_start();
if(!isset($_SESSION["id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}

require 'database.php';
require 'functions.php';



if ( !empty($_POST)) {

	// initialize user input validation variables
	$playerError = null;
	$serverError = null;
	
	// initialize $_POST variables
	$player = $_POST['ply_name'];    // same as HTML name= attribute in put box
	$server = $_POST['ser_name'];
	
	// validate user input
	$valid = true;
	if (empty($player)) {
		$playerError = 'Please choose a player';
		$valid = false;
	}
	if (empty($server)) {
		$serverError = 'Please choose an server';
		$valid = false;
	} 
		
	// insert data
	if ($valid) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO games 
			(ser_id,pla_id) 
			values(?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($server,$player));
		Database::disconnect();
		header("Location: games.php");
	}
}
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
				<h3>Book a Game</h3>
			</div>
	
			<form class="form-horizontal" action="game_create.php" method="post">
		
				<div class="control-group">
					<label class="control-label">Player</label>
					<div class="controls">
						<?php
							$pdo = Database::connect();
							$sql = 'SELECT * FROM players ORDER BY name ASC';
							echo "<select class='form-control' name='ply_name' id='ply_name'>";
								foreach ($pdo->query($sql) as $row) {
								    if($_SESSION['admin'] == 1 || $_SESSION['id'] == $row['id'])
									echo "<option value='" . $row['id'] . " '> " . $row['name'] . "</option>";
								}
							echo "</select>";
							Database::disconnect();
						?>
					</div>	<!-- end div: class="controls" -->
				</div> <!-- end div class="control-group" -->
			  
				<div class="control-group">
					<label class="control-label">Server</label>
					<div class="controls">
						<?php
							$pdo = Database::connect();
							$sql = 'SELECT * FROM servers ORDER BY name ASC';
							echo "<select class='form-control' name='ser_name' id='ser_name'>";
								foreach ($pdo->query($sql) as $row) {
								echo "<option value='" . $row['id'] . " '> " . $row['name'] . " " . $row['region'] ." $" . $row['cost'] . "</option>";
								}
								
							echo "</select>";
							Database::disconnect();
						?>
					</div>	<!-- end div: class="controls" -->
				</div> <!-- end div class="control-group" -->
				
				<div class="control-group <?php echo !empty($sDateError)?'error':'';?>">
					<label class="control-label">Start Date</label>
					<div class="controls">
						<input name="start" type="date"  placeholder="Date" value="<?php echo !empty($start)?$start:'';?>">
						<?php if (!empty($sDateError)): ?>
							<span class="help-inline"><?php echo $sDateError;?></span>
						<?php endif; ?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($eDateError)?'error':'';?>">
					<label class="control-label">End Date</label>
					<div class="controls">
						<input name="end" type="date"  placeholder="Date" value="<?php echo !empty($end)?$end:'';?>">
						<?php if (!empty($eDateError)): ?>
							<span class="help-inline"><?php echo $eDateError;?></span>
						<?php endif; ?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($chargeError)?'error':'';?>">
					<label class="control-label">Charge</label>
					<div class="controls">
						<input name="name" type="text"  placeholder="$0.00" value="<?php echo !empty($charge)?$charge:'';?>" readonly>
						<?php if (!empty($chargeError)): ?>
							<span class="help-inline"><?php echo $chargeError;?></span>
						<?php endif; ?>
					</div>
				</div>

				<div class="form-actions">
					<button type="submit" class="btn btn-success">Confirm</button>
						<a class="btn" href="games.php">Back</a>
				</div>
				
			</form>
			
		</div> <!-- end div: class="span10 offset1" -->
    </div> <!-- end div: class="container" -->

  </body>
</html>
