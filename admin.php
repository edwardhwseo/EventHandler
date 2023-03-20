<?php
require('connect.php');
require('authorize.php');

$query = "SELECT * FROM events";
$statement = $db->prepare($query);
$statement->execute();

if(isset($_POST['delete'])){
  $id = filter_input(INPUT_POST, 'event_id', FILTER_SANITIZE_NUMBER_INT);
  
  $query = "DELETE FROM events WHERE event_id = :event_id";
  $statement = $db->prepare($query);
  $statement->bindValue(':event_id', $id, PDO::PARAM_INT);
  
  $statement->execute();
  
  header("Location: admin.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
	rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD"
	crossorigin="anonymous">
  <title>Administrator Page</title>
</head>
<body>
  <div class="container">
    <div class="border bg-light">
      <h1>Administrator Page</h1>
    </div>
    <form action="create.php" method="post">
      <div>
        <button class="btn btn-primary" type="submit">Create Event Type</button>
      </div>
    </form>
    <div>
      <ul class="list-group list-group-flush">
        <?php while($row = $statement->fetch()): ?>
          <form action="admin.php" method="post">
            <li class="list-group-item">
              <input type="hidden" name="event_id" value="<?= $row['event_id'] ?>">
              <a href="edit.php?event_id=<?= $row['event_id'] ?>"><?= $row['title'] ?></a>
              <button class="btn btn-danger" type="submit" name="delete" onclick="return confirm('Are you sure you wish to delete this post?')">Delete</button>
            </li>
          </form>
        <?php endwhile ?>
      </ul>
    </div>
  </div>
</body>
</html>