<?php
require('connect.php');
require('authorize.php');

if($_POST &&
   isset($_POST['update']) &&
   isset($_POST['title']) &&
   isset($_POST['content']) &&
   isset($_POST['event_id']) &&
   $_POST['title'] > 0 &&
   $_POST['content'] > 0){

    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $id = filter_input(INPUT_POST, 'event_id', FILTER_SANITIZE_NUMBER_INT);

    $query = "UPDATE events SET title = :title, content = :content WHERE event_id = :event_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':title', $title);
    $statement->bindValue(':content', $content);
    $statement->bindValue(':event_id', $id, PDO::PARAM_INT);

    $statement->execute();

    header("Location: admin.php");
    exit;
} //Retrieve event data
elseif(isset($_GET['event_id'])){
    $id = filter_input(INPUT_GET, 'event_id', FILTER_SANITIZE_NUMBER_INT);

    $query = "SELECT * FROM events WHERE event_id = :event_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':event_id', $id, PDO::PARAM_INT);
    $statement->execute();
    $row = $statement->fetch();
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
    <title>Editing <?= $row['title'] ?> Event</title>
</head>
<body>
<div class="container">
    <div class="row col-3">
        <form action="edit.php" method="post">
            <fieldset>
                <div>
                    <legend>Edit Event</legend>
                </div>
                <div class="row">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" value="<?= $row['title'] ?>">
                </div>
                <div class="row">
                    <label for="content">Content</label>
                    <textarea name="content" id="content" cols="30" rows="10"><?= $row['content'] ?></textarea>
                </div>
                <div>
                    <input type="hidden" name="event_id" value="<?= $row['event_id'] ?>">
                    <button class="btn btn-primary" name="update">Submit</button>
                </div>
            </fieldset>
        </form>
    </div>
</div>
</body>
</html>