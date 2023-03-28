<?php
session_start();
require('connect.php');
date_default_timezone_set('America/Winnipeg');

$isValidId = filter_input(INPUT_GET, 'post_id', FILTER_VALIDATE_INT);

if($_POST &&
   isset($_POST['update']) &&
   isset($_POST['title']) &&
   isset($_POST['content']) &&
   isset($_POST['post_id']) &&
   $_POST['title'] > 0 &&
   $_POST['content'] > 0){

    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = $_POST['content'];
    $updated = date('Y-m-d H:i:s');
    $id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);

    $query = "UPDATE posts SET title = :title, content = :content, updated_by = :updated_by WHERE post_id = :post_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':title', $title);
    $statement->bindValue(':content', $content);
    $statement->bindValue(':updated_by', $updated);
    $statement->bindValue(':post_id', $id, PDO::PARAM_INT);

    $statement->execute();

    header("Location: event.php?event_id=" . "{$_POST['event_id']}");
    exit;
}
elseif(isset($_GET['post_id']) && $isValidId){
    $id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT);

    $query = "SELECT * FROM posts WHERE post_id = :post_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':post_id', $id, PDO::PARAM_INT);
    $statement->execute();
    $row = $statement->fetch();

    if(!isset($row['post_id'])){
        header("Location: error.php");
        exit;
    }
}
elseif($_POST && $isValid = strlen($_POST['title']) < 1 || strlen($_POST['content']) < 1 ? true : false){
    header("Location: error.php");
    exit;
}
elseif(!$isValidId){
    header("Location: index.php");
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
    <script src="https://cdn.tiny.cloud/1/4ed3kawviq0nm81x4z3c5mynsqp1niz58qg14requ2l5qp8t/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <title>Editing - <?= $row['title'] ?></title>
</head>
<body>
<?php if(isset($_SESSION['username'])): ?>
        <div class="container">
            <h1>Editing - <?= $row['title'] ?></h1>
            <a href="event.php?event_id=<?= $row['event_id'] ?>">Return</a>
            <form action="edit_post.php" method="post">
                <div class="form-group my-3">
                    <label for="title">Title</label>
                    <input class="form-control" type="text" name="title" id="title" value="<?= $row['title'] ?>">
                </div>
                <div class="form-group">
                    <div class="mb-1">
                        <label for="content">Content</label>
                    </div>
                    <textarea class="form-control" name="content" id="content" cols="30" rows="10"><?= $row['content'] ?></textarea>
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
                    <input type="hidden" name="post_id" value="<?= $row['post_id'] ?>">
                    <input type="hidden" name="event_id" value="<?= $row['event_id'] ?>">
                    <button class="btn btn-primary" name="update" type="submit">Submit</button>
                </div>
            </form>
        </div>
    <?php else: ?>
        <?php header("Location: unauthorized.php") ?>
    <?php endif ?>
</body>
</html>