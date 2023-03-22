<?php
require('connect.php');

if($_GET && isset($_GET['event_id']) && filter_input(INPUT_GET, 'event_id', FILTER_VALIDATE_INT)){
    $query = "SELECT * FROM events WHERE event_id = :event_id LIMIT 1";
    $statement = $db->prepare($query);

    $id = filter_input(INPUT_GET, 'event_id', FILTER_SANITIZE_NUMBER_INT);
    $statement->bindValue('event_id', $id, PDO::PARAM_INT);
    $statement->execute();

    $row = $statement->fetch();
}

$query = "SELECT * FROM comments WHERE event_id = :event_id";
$statement = $db->prepare($query);
$event_id = filter_input(INPUT_GET, 'event_id', FILTER_SANITIZE_NUMBER_INT);
$statement->bindValue('event_id', $event_id, PDO::PARAM_INT);
$statement->execute();
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
    <title></title>
</head>
<body>
    <div class="container">
        <h1><?= $row['title'] ?></h1>
        <h5><?= $row['content'] ?></h5>
        <a class="btn btn-outline-primary" href="index.php">Home</a>
        <a class="btn btn-outline-primary" href="comment.php?event_id=<?= $row['event_id'] ?>">Create a Comment</a>
        <div class="my-3">
            <?php while($comment = $statement->fetch()): ?>
                <div class="card bg-light mb-2 p-2">
                    <div>
                        <h3><?= $comment['title'] ?></h3>
                    </div>
                    <p>
                        <small><?= date("F j, Y, g:i a", strtotime($row['created_at'])) ?> - <a href="#">Edit</a></small>
                    </p>
                    <div>
                        <?= $comment['content'] ?>
                    </div>
                </div>
            <?php endwhile ?>
        </div>
    </div>
</body>
</html>