<?php
session_start();
require('connect.php');

$isValidId = filter_input(INPUT_GET, 'comment_id', FILTER_VALIDATE_INT);

if($_POST &&
   isset($_POST['update']) &&
   isset($_POST['content']) &&
   isset($_POST['comment_id']) &&
   $_POST['content'] > 0){

    $content = $_POST['content'];
    $id = filter_input(INPUT_POST, 'comment_id', FILTER_SANITIZE_NUMBER_INT);

    $query = "UPDATE comments SET content = :content WHERE comment_id = :comment_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':content', $content);
    $statement->bindValue(':comment_id', $id, PDO::PARAM_INT);

    $statement->execute();

    header("Location: post.php?post_id=" . "{$_POST['post_id']}");
    exit;
}
elseif(isset($_GET['comment_id']) && $isValidId){
    $id = filter_input(INPUT_GET, 'comment_id', FILTER_SANITIZE_NUMBER_INT);

    $query = "SELECT * FROM comments WHERE comment_id = :comment_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':comment_id', $id, PDO::PARAM_INT);
    $statement->execute();
    $row = $statement->fetch();

    if(!isset($row['comment_id'])){
        header("Location: error.php");
        exit;
    }
}
elseif(isset($_POST['delete'])){
    $id = filter_input(INPUT_POST, 'comment_id', FILTER_SANITIZE_NUMBER_INT);
    $delete = "DELETE FROM comments WHERE comment_id = :comment_id";
    $statement = $db->prepare($delete);
    $statement->bindValue(':comment_id', $id, PDO::PARAM_INT);
    $statement->execute();

    header("Location: post.php?post_id=" . "{$_POST['post_id']}");
    exit;
}
elseif($_POST && $isValid = strlen($_POST['content']) < 1 ? true : false){
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
    <title>Editing1 Comment</title>
</head>
<body>
<?php if(isset($_SESSION['username']) && $_SESSION['user_role_id'] == 1): ?>
        <div class="container">
            <h1>Editing Comment</h1>
            <a class="btn btn-outline-primary" href="post.php?post_id=<?= $row['post_id'] ?>">Return</a>
            <form action="edit_comment.php" method="post">
                <div class="form-group my-2">
                    <div class="mb-1">
                        <label for="content">Content</label>
                    </div>
                    <textarea class="form-control" name="content" id="content" cols="30" rows="10"><?= $row['content'] ?></textarea>
                </div>
                <div class="mt-2">
                    <input type="hidden" name="comment_id" value="<?= $row['comment_id'] ?>">
                    <input type="hidden" name="post_id" value="<?= $row['post_id'] ?>">
                    <button class="btn btn-primary" name="update" type="submit">Submit</button>
                    <button class="btn btn-outline-danger" name="delete" type="submit" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</button>
                </div>
            </form>
        </div>
    <?php else: ?>
        <?php header("Location: unauthorized.php") ?>
    <?php endif ?>
</body>
</html>