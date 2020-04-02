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
			<p>
				<?php
					echo '<a href="player_list.php" class="btn btn-primary">Players</a>';

					echo '<a href="server_list.php" class="btn btn-primary">Servers</a> &nbsp;';

                    echo '<a href="game_create.php" class="btn btn-success">Book a Game</a> &nbsp';

					//echo '<a href="games.php">All Assignments</a>&nbsp;';
				?>
			</p>
			
			<table class="table table-striped table-bordered" style="background-color: lightgrey !important">
				<thead>
					<tr>
					    <th>Player</th>
						<th>Server</th>
						<th>Start</th>
						<th>End</th>
						<th>Charge</th>
					</tr>
				</thead>
				<tbody>
				<?php 
					include 'database.php';
					include 'functions.php';
					$pdo = Database::connect();
					
						$sql = "SELECT games.id, players.name as pla_name, servers.name, start, end, charge
						FROM players, servers, games
						WHERE ser_id = servers.id and pla_id = players.id
						ORDER BY pla_name ASC";

/*SELECT events.date, events.location, events.description, customers.name 
FROM assignments, customers, events 
WHERE assignments.assign_id = customers.id AND assignments.assign_event_id = events.id
ORDER BY events.date;*/

					foreach ($pdo->query($sql) as $row) {
						echo '<tr>';
						echo '<td>'. $row['pla_name'] . '</td>';
						echo '<td>'. $row['name'] . '</td>';
						echo '<td>'. $row['start'] . '</td>';
						echo '<td>'. $row['end'] . '</td>';
						echo '<td>'. $row['charge'] . '</td>';
						echo '<td width=250>';
						# use $row[0] because there are 3 fields called "id"
						echo '<a class="btn" href="game_read.php?id='.$row[0].'">Details</a>';
						echo ' ';
						echo '<a class="btn" href="game_update.php?id='.$row[0].'">Update</a>';
						echo ' ';
						echo '<a class="btn" href="game_delete.php?id='.$row[0].'">Delete</a>';
						echo '</td>';
						echo '</tr>';
					}
					Database::disconnect();
				?>
				</tbody>
			</table>
    	</div>

    </div> <!-- end div: class="container" -->
	
</body>
</html>
