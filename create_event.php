<?php
     
    require 'database.php';
    require 'functions.php';
 
    if ( !empty($_POST)) {
        // keep track validation errors
        $dateError = null;
        $locationError = null;
        $descriptionError = null;
         
        // keep track post values
        $date = $_POST['date'];
        $location = $_POST['location'];
        $description = $_POST['description'];
         
        // validate input
        $valid = true;
        if (empty($date)) {
            $dateError = 'Please enter Date';
            $valid = false;
        }
         
        if (empty($location)) {
            $locationError = 'Please enter Location';
            $valid = false;
        } 
         
        if (empty($description)) {
            $descriptionError = 'Please enter Description';
            $valid = false;
        }
         
        // insert data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO events (`date`,location,description) values(?, ?, ?)";
            $q = $pdo->prepare($sql);
            $q->execute(array($date,$location,$description));
            Database::disconnect();
            header("Location: events.php");
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
                        <h3>Create an Event</h3>
                    </div>
             
                    <form class="form-horizontal" action="create_event.php" method="post">
                      <div class="control-group <?php echo !empty($dateError)?'error':'';?>">
					<label class="control-label">Date</label>
					<div class="controls">
						<input name="date" type="date"  placeholder="Date" value="<?php echo !empty($date)?$date:'';?>">
						<?php if (!empty($dateError)): ?>
							<span class="help-inline"><?php echo $dateError;?></span>
						<?php endif; ?>
					</div>
				</div>
                      <div class="control-group <?php echo !empty($locationError)?'error':'';?>">
                        <label class="control-label">Location</label>
                        <div class="controls">
                            <input name="location" type="text" placeholder="Location" value="<?php echo !empty($location)?$location:'';?>">
                            <?php if (!empty($locationError)): ?>
                                <span class="help-inline"><?php echo $locationError;?></span>
                            <?php endif;?>
                        </div>
                      </div>
                      <div class="control-group <?php echo !empty($descriptionError)?'error':'';?>">
                        <label class="control-label">Description</label>
                        <div class="controls">
                            <input name="description" type="text"  placeholder="Description" value="<?php echo !empty($description)?$description:'';?>">
                            <?php if (!empty($descriptionError)): ?>
                                <span class="help-inline"><?php echo $descriptionError;?></span>
                            <?php endif;?>
                        </div>
                      </div>
                      <div class="form-actions">
                          <button type="submit" class="btn btn-success">Create</button>
                          <button class="btn" onclick="events.php">Back</button>
                        </div>
                    </form>
                </div>
                 
    </div> <!-- /container -->
  </body>
</html>

