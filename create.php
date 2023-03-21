<?php
session_start();
require('connect.php');

if($_POST && strlen($_POST['title'] > 0 && strlen($_POST['content']) > 0)){
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = $_POST['content'];

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
    <script src="https://cdn.tiny.cloud/1/4ed3kawviq0nm81x4z3c5mynsqp1niz58qg14requ2l5qp8t/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <title>Document</title>
</head>
<body>
    <?php if(isset($_SESSION['username']) && $_SESSION['user_role_id'] == 1): ?>
        <div class="container">
            <h1>Create an Event</h1>
            <a href="admin.php">Return</a>
            <form action="create.php" method="post">
                <div class="form-group my-2">
                    <label for="title">Title</label>
                    <input class="form-control" type="text" name="title" id="title">
                </div>
                <div class="form-group">
                    <div class="mb-1">
                        <label for="content">Content</label>
                    </div>
                    <textarea class="form-control" name="content" id="content" cols="30" rows="10"></textarea>
                    <script>
                    tinymce.init({
                        selector: 'textarea',
                        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss',
                        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                        tinycomments_mode: 'embedded',
                        tinycomments_author: 'Author name',
                        mergetags_list: [
                            { value: 'First.Name', title: 'First Name' },
                            { value: 'Email', title: 'Email' },
                        ]
                    });
                    </script>
                </div>
                <div class="mt-2">
                    <button class="btn btn-primary" type="submit">Create</button>
                </div>
            </form>
        </div>
    <?php else: ?>
        <?php header("Location: unauthorized.php") ?>
    <?php endif ?>
</body>
</html>