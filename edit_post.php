<?php

/*******w******** 
    
    Name: Edward Seo
    Description: Page to edit Posts

****************/

session_start();
require('connect.php');
require('image_upload_functions.php');
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

    //Image Upload
    $image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);
    $upload_error_detected = isset($_FILES['image']) && ($_FILES['image']['error'] > 0) && ($_FILES['image']['error'] != 4);
    
    if($image_upload_detected){
        $image_filename = $_FILES['image']['name'];
        $temporary_image_path = $_FILES['image']['tmp_name'];
        $new_image_path = file_upload_path($image_filename);
        if(file_is_an_image($temporary_image_path, $new_image_path)){
            move_uploaded_file($temporary_image_path, $new_image_path);
            
            $query = "INSERT INTO images (post_id, file_name) VALUES (:post_id, :file_name)";
            $image_statement = $db->prepare($query);
            $image_statement->bindValue(":post_id", $id, PDO::PARAM_INT);
            $image_statement->bindValue(":file_name", $image_filename);
            $image_statement->execute();
            
            //resize_medium_and_thumbnail($new_image_path);
        }
        else if(file_is_a_pdf($temporary_image_path, $new_image_path)){
            move_uploaded_file($temporary_image_path, $new_image_path);
        }
    }
    elseif($upload_error_detected){
        header("Location: error.php");
        exit;
    }

    //Image Remove
    if(isset($_POST['image-checkbox'])){
        $query = "DELETE FROM images WHERE post_id = :post_id";
        $image_remove_statement = $db->prepare($query);
        $post_id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
        $image_remove_statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
        $image_remove_statement->execute();
    }

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
elseif(isset($_POST['delete'])){
    $id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
    $delete = "DELETE FROM posts WHERE post_id = :post_id";
    $statement = $db->prepare($delete);
    $statement->bindValue(':post_id', $id, PDO::PARAM_INT);
    $statement->execute();

    header("Location: event.php?event_id=" . "{$_POST['event_id']}");
    exit;
}
elseif($_POST && $isValid = strlen($_POST['title']) < 1 || strlen($_POST['content']) < 1 ? true : false){
    header("Location: error.php");
    exit;
}
elseif(!$isValidId){
    header("Location: index.php");
    exit;
}

//Image
$query = "SELECT file_name FROM images WHERE post_id = :post_id";
$image_statement = $db->prepare($query);
$post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT);
$image_statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
$image_statement->execute();
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
            <a class="btn btn-outline-primary" href="event.php?event_id=<?= $row['event_id'] ?>">Return</a>
            <form action="edit_post.php" method="post" enctype="multipart/form-data">
                <div class="form-group row my-2">
                    <div class="col">
                        <label for="title">Title</label>
                        <input class="form-control" type="text" name="title" id="title" value="<?= $row['title'] ?>">
                    </div>
                    <?php if(!($image = $image_statement->fetch())): ?>
                        <div class="col">
                            <label for="image">Image</label>
                            <input class="form-control" type="file" name="image" id="image">
                        </div>
                    <?php else: ?>
                        <div class="form-check col">
                            <div>
                                <br>
                            </div>
                            <input class="form-check-input" name="image-checkbox" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                Remove Image
                            </label>
                        </div>
                    <?php endif ?>
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
                    <button class="btn btn-outline-danger" name="delete" type="submit" onclick="return confirm('Are you sure you want to delete this post?')">Delete</button>
                </div>
            </form>
        </div>
    <?php else: ?>
        <?php header("Location: unauthorized.php") ?>
    <?php endif ?>
</body>
</html>