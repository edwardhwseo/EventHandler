<?php
session_start();
require('connect.php');

if($_POST && strlen($_POST['title'] > 0 && strlen($_POST['content']) > 0)){
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $query = "INSERT INTO comments (title, content) VALUES (:title, :content)";
    $statement = $db->prepare($query);

    $statement->bindValue(":title", $title);
    $statement->bindValue(":content", $content);
    $statement->execute();

    header("Location: index.php");
    exit;
}
elseif($_POST && $isValid = strlen($_POST['title']) < 1 || strlen($_POST['content']) < 1 ? true : false){
    header("Location: error.php");
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
    <title>Create a Comment</title>
</head>
<body>
    <?php if(isset($_SESSION['username'])): ?>
        <div class="container">
            <div>
                <h1>Create a Comment</h1>
                <a class="btn btn-outline-primary" href="index.php">Return</a>
            </div>
            <div class="my-2">
                <form action="comment.php" method="post">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input class="form-control" type="text" name="title" id="title">
                    </div>
                    <div class="form-group">
                        <label for="content">Content</label>
                        <textarea class="form-control" name="content" id="content" cols="30" rows="10"></textarea>
                    </div>
                    <div class="my-2">
                        <button class="btn btn-outline-primary" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    <?php else: ?>
    <?php endif ?>
</body>
</html>