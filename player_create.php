<?php
session_start();
	
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
	$email = $_POST['email'];
	$region = $_POST['region'];
	$password = $_POST['password'];
	$passwordhash = MD5($password);
	
	// initialize $_FILES variables
	if(!empty($_FILES['image']['name'])){
	$fileName = $_FILES['image']['name'];
	$tmpName  = $_FILES['image']['tmp_name'];
	$fileSize = $_FILES['image']['size'];
	$fileType = $_FILES['image']['type'];
	$image = file_get_contents($tmpName);
	}
	else{
	    $fileName = 'empty';
	$image  = null;
	$fileSize = 0;
	$fileType = 'empty';
	}

	// validate user input
	$valid = true;
	if (empty($name)) {
		$fnameError = 'Please enter Name';
		$valid = false;
	}

	// do not allow 2 records with same email address!
	if (empty($email)) {
		$emailError = 'Please enter valid Email Address (REQUIRED)';
		$valid = false;
	} else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
		$emailError = 'Please enter a valid Email Address';
		$valid = false;
	}

	$pdo = Database::connect();
	$sql = "SELECT * FROM players";
	foreach($pdo->query($sql) as $row) {

		if($email == $row['email']) {
			$emailError = 'Email has already been registered!';
			$valid = false;
		}
	}
	Database::disconnect();
	
	// email must contain only lower case letters
	if (strcmp(strtolower($email),$email)!=0) {
		$emailError = 'email address can contain only lower case letters';
		$valid = false;
	}
	
	if (empty($region)) {
		$regionError = 'Please select your region';
		$valid = false;
	}
	
	if (empty($password)) {
		$passwordError = 'Please enter valid Password';
		$valid = false;
	}
	// restrict file types for upload
	$types = array('image/jpeg','image/gif','image/png');
	if($fileSize > 0) {
		if(in_array($_FILES['image']['type'], $types)) {
		}
		else {
			$filename = null;
			$filetype = null;
			$filesize = null;
			$filecontent = null;
			$imageError = 'improper file type';
			$valid=false;
			
		}
	}
	// insert data
	if ($valid) 
	{
		$pdo = Database::connect();
		
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO players (name,hash,region,email,image,
		filename,filesize,filetype) values(?, ?, ?, ?, ?, ?, ?,?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($name,$passwordhash,$region,$email,$image,
		$fileName,$fileSize,$fileType));
		
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM players WHERE email = ? AND hash = ? LIMIT 1";
		$q = $pdo->prepare($sql);
		$q->execute(array($email,$passwordhash));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		
		$_SESSION['id'] = $data['id'];
		
	$to      = $email; // Send email to our user
$subject = 'Signup | Verification'; // Give the email a subject 
$message = '
 
Thanks for signing up!
Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.
 
------------------------
Username: '.$email.'
Password: '.$password.'
------------------------
 
Please click this link to activate your account:
https://ngallati.000webhostapp.com/As05/verify.php?email='.$email.'&hash='.$passwordhash.'
 
'; // Our message above including the link
                     
$headers = 'From:noreply@ngallati.000webhostapp.com' . "\r\n"; // Set from headers
mail($to, $subject, $message, $headers); // Send our email

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
			<?php
				require 'functions.php';
			?>
			<div class="row">
				<h3>Add New Player</h3>
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
				
				<div class="control-group <?php echo !empty($emailError)?'error':'';?>">
					<label class="control-label">Email</label>
					<div class="controls">
						<input name="email" type="text" placeholder="Email Address" value="<?php echo !empty($email)?$email:'';?>">
						<?php if (!empty($emailError)): ?>
							<span class="help-inline"><?php echo $emailError;?></span>
						<?php endif;?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($passwordError)?'error':'';?>">
					<label class="control-label">Password</label>
					<div class="controls">
						<input id="password" name="password" type="password"  placeholder="password" value="<?php echo !empty($password)?$password:'';?>">
						<?php if (!empty($passwordError)): ?>
							<span class="help-inline"><?php echo $passwordError;?></span>
						<?php endif;?>
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
			  
				<div class="control-group <?php echo !empty($imageError)?'error':'';?>">
					<label class="control-label">Image</label>
					<div class="controls">
						<input type="hidden" name="MAX_FILE_SIZE" value="16000000">
						<input name="image" type="file" id="image">
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
