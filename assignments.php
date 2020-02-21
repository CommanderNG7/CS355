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
					echo '<a href="assign_create.php" class="btn btn-primary">Add Assignment</a>';

					echo '<a href="customers.php">Customers</a> &nbsp;';

                    echo '<a href="events.php">Events</a> &nbsp';

					echo '<a href="assignments.php">All Assignments</a>&nbsp;';
				?>
			</p>
			
			<table class="table table-striped table-bordered" style="background-color: lightgrey !important">
				<thead>
					<tr>
						<th>Date</th>
						<th>Location</th>
						<th>Description</th>
						<th>Name</th>
					</tr>
				</thead>
				<tbody>
				<?php 
					include 'database.php';
					include 'functions.php';
					$pdo = Database::connect();
					
						$sql = "SELECT * FROM assignments 
						LEFT JOIN customers ON customers.id = assignments.assign_cust_id 
						LEFT JOIN events ON events.id = assignments.assign_event_id
						ORDER BY date ASC, location, description, name;";

					foreach ($pdo->query($sql) as $row) {
						echo '<tr>';
						echo '<td>'. Functions::dayMonthDate($row['date']) . '</td>';
						echo '<td>'. $row['location'] . '</td>';
						echo '<td>'. $row['description'] . '</td>';
						echo '<td>'. $row['name'] . '</td>';
						echo '<td width=250>';
						# use $row[0] because there are 3 fields called "id"
						echo '<a class="btn" href="assign_read.php?id='.$row[0].'">Details</a>';
						echo ' ';
						echo '<a class="btn" href="assign_update.php?id='.$row[0].'">Update</a>';
						echo ' ';
						echo '<a class="btn" href="assign_delete.php?id='.$row[0].'">Delete</a>';
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
