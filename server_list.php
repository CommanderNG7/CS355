<?php
session_start();
if(!isset($_SESSION["id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
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
            <div class="row">
                <h3>Server List</h3>
            </div>
            <div class="row">
                <p>
                    <?php
                    if($_SESSION['admin']!='1')
                    echo "<a href='server_create.php' class='btn btn-success'>Create</a>";
                    ?>
                    <a href="games.php" class="btn btn-success">Games</a>
                    <a href="logout.php" class="btn btn-success">Log Out</a>

                </p>
                 
                <table class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Region</th>
                          <th>Capacity</th>
                          <th>Cost Per Day</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                       include 'database.php';
                       $pdo = Database::connect();
                       $sql = 'SELECT * FROM servers ORDER BY id DESC';
                       foreach ($pdo->query($sql) as $row) {
                                echo '<tr>';
                                echo '<td>'. $row['name'] . '</td>';
                                echo '<td>'. $row['region'] . '</td>';
                                echo '<td>'. $row['user_amt'] . '</td>';
                                echo '<td>'.'$'. $row['cost'] . '</td>';
                                echo '<td width=250>';
                                echo '<a class="btn" href="player_read.php?id='.$row['id'].'">Read</a>';
                                echo ' ';
                                echo ' ';
                                echo '</td>';
                                echo '</tr>';
                       }
                       Database::disconnect();
                      ?>
                      </tbody>
                </table>
        </div>
    </div> <!-- /container -->
  </body>
</html>
