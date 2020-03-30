<?php
session_start();
if($_SESSION['admin'] != '1'){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}	
require 'database.php';

if ( !empty($_POST)) { // if not first time through

	// initialize user input validation variables
	$fnameError = null;
	$lnameError = null;
	$emailError = null;
	$regionError = null;
	$passwordError = null;
	$titleError = null;
	$imageError = null; // not used
	
	// initialize $_POST variables
	$name = $_POST['name'];
	$region = $_POST['region'];
		$capacity = $_POST['capacity'];
			$cost = $_POST['cost'];


	// validate user input
	$valid = true;
	if (empty($name)) {
		$fnameError = 'Please enter Name';
		$valid = false;
	}

	$pdo = Database::connect();
	$sql = "SELECT * FROM servers";
	foreach($pdo->query($sql) as $row) {

		if($name == $row['name']) {
			$fnameError = 'Name has already been registered!';
			$valid = false;
		}
	}
	Database::disconnect();
	
	if (empty($region)) {
		$regionError = 'Please select your region';
		$valid = false;
	}
	
	if (empty($capacity) || $capacity <= 0) {
		$capacityError = 'Please enter valid Capacity';
		$valid = false;
	}
	
	if (empty($cost) || $cost < 0) {
		$cost = 'Please enter valid Cost';
		$valid = false;
	}

	// insert data
	if ($valid) 
	{
		$pdo = Database::connect();
		
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO players (name,region,capacity,cost) values(?, ?, ?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($name,$region,$capacity,$cost));

		Database::disconnect();
		header("Location: server_list.php");	
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
			<?php
				require 'functions.php';
			?>
			<div class="row">
				<h3>Add New Server</h3>
			</div>
	
			<form class="form-horizontal" action="player_create.php" method="post" enctype="multipart/form-data">

				<div class="control-group <?php echo !empty($fnameError)?'error':'';?>">
					<label class="control-label">Name</label>
					<div class="controls">
						<input name="name" type="text"  placeholder="Name" value="<?php echo !empty($name)?$name:'';?>">
						<?php if (!empty($nameError)): ?>
							<span class="help-inline"><?php echo $nameError;?></span>
						<?php endif; ?>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Region</label>
					<div class="controls">
						<select class="form-control" name="region">
							<option value="North America" selected>North America</option>
							<option value="Europe" >Europe</option> 
							<option value="Asia" >Asia</option>
							<option value="South America" >South America</option>
							<option value="Africa" >Africa</option>
							<option value="Australia" >Australia</option>
						</select>
					</div>
				</div>
				
								<div class="control-group <?php echo !empty($capacityError)?'error':'';?>">
					<label class="control-label">Capacity</label>
					<div class="controls">
						<input id="capacity" name="capacity" type="number"  placeholder="0" value="<?php echo !empty($capacity)?$capacity:'';?>">
						<?php if (!empty($capacityError)): ?>
							<span class="help-inline"><?php echo $capacityError;?></span>
						<?php endif;?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($costError)?'error':'';?>">
					<label class="control-label">Cost Per Day</label>
					<div class="controls">
						<input id="cost" name="cost" type="number"  placeholder="$0.00" value="<?php echo !empty($cost)?$cost:'';?>">
						<?php if (!empty($ccostError)): ?>
							<span class="help-inline"><?php echo $costError;?></span>
						<?php endif;?>
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
