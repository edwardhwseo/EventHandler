<?php

require('connect.php');
//require('admin.php');

if($_POST && strlen($_POST['title'] > 0 && strlen($_POST['content']) > 0)){
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $query = "INSERT INTO events (title, content) VALUES (:title, :content)";
    $statement = $db->prepare($query);

    $statement->bindValue(":title", $title);
    $statement->bindValue(":content", $content);
    $statement->execute();

    header("Location: index.php");
    exit;
}
/*elseif($_POST && $isValid = strlen($_POST['title']) < 1 || strlen($_POST['content']) < 1 ? true : false){

}*/
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
    <title>Document</title>
</head>
<body>
    <div class="container">
        <div class="row col-3">
            <form action="create.php" method="post">
                <fieldset>
                    <div>
                        <legend>Create an Event</legend>
                    </div>
                    <div class="row">
                        <label for="title">Title</label>
                        <input type="text" name="title" id="title">
                    </div>
                    <div class="row">
                        <label for="content">Content</label>
                        <textarea name="content" id="content" cols="30" rows="10"></textarea>
                    </div>
                    <div>
                        <input type="submit" name="command" value="Create">
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</body>
</html>