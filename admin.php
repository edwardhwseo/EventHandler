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
    <div>
      <h1>Administrator Page</h1>
    </div>
    <form action="create.php" method="post">
      <div class="my-2">
        <button class="btn btn-outline-primary" type="submit">Create Event Type</button>

      </div>
    </form>
    <div>
      <ul class="list-group list-group-flush">
        <?php while($row = $statement->fetch()): ?>
          <form action="admin.php" method="post">
            <li class="list-group-item">
              <input type="hidden" name="event_id" value="<?= $row['event_id'] ?>">
              <?= $row['title'] ?>
              
              <a class="btn btn-outline-secondary btn-sm" href="edit.php?event_id=<?= $row['event_id'] ?>">Edit
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"/>
              </svg>
            </a>
              </button>
              <button class="btn btn-outline-danger btn-sm" type="submit" name="delete" onclick="return confirm('Are you sure you wish to delete this post?')">Delete
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                  <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                </svg>
              </button>

            </li>
          </form>
        <?php endwhile ?>
      </ul>
    </div>
  </div>
</body>
</html>