<?php
include_once "config.php";
$connection = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
if (!$connection) {
	throw new Exception("Cannot connect to database");
}

$query = "SELECT * FROM tasks WHERE complete = 0 ORDER BY date";
$result = mysqli_query($connection,$query);

$completeTaskQuery = "SELECT * FROM tasks WHERE complete = 1 ORDER BY date DESC";
$resultCompleteTasks = mysqli_query($connection,$completeTaskQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
	
	<title>Document</title>
	
	<style>
	
	body{
		display:flex;
		align-items:center;
		justify-content:center;
		height: 100vh;
	}
	a {
		color: cadetblue;
	}
	
	.custom-select{
		width:auto;
		height:auto;
	}
	
	input[type="submit"] {
		padding: 6.5px 15px;
		background: cadetblue;
		border: none;
		border-radius: 2px;
		color: #fff;
		font-weight: bold;
	}
	
	.all-tasks {
		padding: 30px 0 50px;
	}
	</style>
</head>
<body>

	<div class="container">
		<div class="row">
			<div class="col">
				<div class="header">
					<h1>Tasks Manager</h1>
					<p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sit labore ipsam soluta? Architecto aliquid vel asperiores voluptatum, sequi dolorum excepturi.</p>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col">
				<div class="all-tasks">
					<h3>Complete Tasks</h3>
					<?php if(mysqli_num_rows($resultCompleteTasks) > 0) : ?>
						<table class="table">
							<thead>
								<tr>
									<th></th>
									<th scope="col">id</th>
									<th scope="col">Task</th>
									<th scope="col">Date</th>
									<th scope="col">Action</th>
								</tr>
							</thead>
							<tbody>
							<?php 
								while ($cdata = mysqli_fetch_assoc($resultCompleteTasks)) : 
								$timestamp = strtotime($cdata['date']);
								$cdate = date("jS M, Y",$timestamp);
							?>
								<tr>
									<th scope="row"><input type="checkbox" value="<?php echo $cdata['id']; ?>"></th>
									<th><?php echo $cdata['id']; ?></th>
									<td><?php echo $cdata['task']; ?></td>
									<td><?php echo $cdate; ?></td>
									<td><a class="delete" data-taskid="<?php echo $cdata['id']; ?>" href="#">Delete</a> | <a class="incomplete" data-taskid="<?php echo $cdata['id']; ?>" href="#">Mark as incomplete</a></td>
								</tr>
								<?php 
							endwhile;
							?>
							</tbody>
						</table>
					<?php else: ?>
						<p>No Completed Task.</p>
					<?php endif; ?>
					
					<h3>Upcoming Tasks</h3>
					<?php if(mysqli_num_rows($result) > 0) : ?>						
						<table class="table">
							<thead>
								<tr>
									<th></th>
									<th scope="col">id</th>
									<th scope="col">Task</th>
									<th scope="col">Date</th>
									<th scope="col">Action</th>
								</tr>
							</thead>
							<tbody>
							<?php 
								while ($data = mysqli_fetch_assoc($result)) : 
								$timestamp = strtotime($data['date']);
								$date = date("jS M, Y",$timestamp);
							?>
								<tr>
									<th scope="row"><input type="checkbox" value="<?php echo $data['id']; ?>"></th>
									<th><?php echo $data['id']; ?></th>
									<td><?php echo $data['task']; ?></td>
									<td><?php echo $date; ?></td>
									<td><a class="delete" data-taskid="<?php echo $data['id']; ?>" href="#">Delete</a> | <a data-taskid="<?php echo $data['id']; ?>" class="complete" href="#">Complete</a></td>
								</tr>
								<?php 
							endwhile;
							mysqli_close($connection);
							?>
							</tbody>
						</table>
						<select class="custom-select">
							<option selected>With Selected</option>
							<option value="1">One</option>
							<option value="2">Two</option>
							<option value="3">Three</option>
						</select>
						<input type="submit" value="Submit">
					<?php else: ?>
						<p>No Task Found.</p>
					<?php endif; ?>
					
				</div>
				
			</div>
		</div>
		
		<div class="row">
			<div class="col">
				<h3>Add Tasks</h3>
				<form method="post" action="tasks.php">
				<?php 
					if('true' == isset($_GET['added'])){
						echo "<span style='color:cadetblue;font-weight:700;'>Task Added.</span>";
					}				
				?>
					<div class="form-group">
						<label for="task">Task</label>
						<input type="text" name="task" class="form-control" id="task">
					</div>
					<div class="form-group">
						<label for="date">Date</label>
						<input type="date" name="date" class="form-control" id="date">
					</div>
					<input type="submit" value="Add Task">
					<input type="hidden" name="action" value="add">
				</form>
			</div>
		</div>
	</div>
	
	<form action="tasks.php" method="post" id="completetask">
		<input type="hidden" name="action" id="caction" value="complete">
		<input type="hidden" name="taskid" id="taskid">
	</form>	
	<form action="tasks.php" method="post" id="deletetask">
		<input type="hidden" name="action" id="caction" value="delete">
		<input type="hidden" name="taskid" id="dtaskid">
	</form>	
	<form action="tasks.php" method="post" id="incompletetask">
		<input type="hidden" name="action" id="caction" value="incomplete">
		<input type="hidden" name="taskid" id="ictaskid">
	</form>
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
	<script>
		;(function($) {
			$(document).ready(function(){
				$('.complete').on('click', function(){
					var id = $(this).data('taskid');
					$('#taskid').val(id);
					$('#completetask').submit();
				});
				$('.delete').on('click', function(){
					if (confirm("Are you sure you want to delete?")) {
						var id = $(this).data('taskid');
						$('#dtaskid').val(id);
						$('#deletetask').submit();
					}
				});
				$('.incomplete').on('click', function(){
					if (confirm("Are you sure you want to mark as incomplete?")) {
						var id = $(this).data('taskid');
						$('#ictaskid').val(id);
						$('#incompletetask').submit();
					}
				});
			});
		})(jQuery);
	</script>
</body>
</html>