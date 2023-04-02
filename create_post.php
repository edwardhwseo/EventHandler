<?php

/*******w******** 
    
    Name: Edward Seo
    Description: Page to create Posts

****************/

session_start();
require('connect.php');
require('image_upload_functions.php');

//Events
$query = "SELECT * FROM events";
$statement = $db->prepare($query);
$statement->execute();

//Locations
$location_json = file_get_contents('https://data.winnipeg.ca/resource/tx3d-pfxq.json');
$location = json_decode($location_json, true);
$area = [];

for($i=0; $i<count($location); $i++){
    $area[] = $location[$i]['cca'];
}

$unique_area = array_unique($area);

//Create Post
if($_POST && strlen($_POST['title']) > 0 && strlen($_POST['content']) > 0 && isset($_POST['category']) && $_POST['category'] != 0){
    $user_id = $_SESSION['user_id'];
    $event_id = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT);
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = $_POST['content'];
    $selected_location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $query = "INSERT INTO posts (user_id, event_id, title, content, location) VALUES (:user_id, :event_id, :title, :content, :location)";
    $statement = $db->prepare($query);
    
    $statement->bindValue(":user_id", $user_id);
    $statement->bindValue(":event_id", $event_id);
    $statement->bindValue(":title", $title);
    $statement->bindValue(":content", $content);
    $statement->bindValue(":location", $selected_location);
    $statement->execute();
    
    //Image Upload
    $image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);
    $upload_error_detected = isset($_FILES['image']) && ($_FILES['image']['error'] > 0) && ($_FILES['image']['error'] != 4);
    
    if($image_upload_detected){
        //Retrieve current post's post_id
        $query = "SELECT post_id FROM posts ORDER BY post_id DESC LIMIT 1";
        $post_statement = $db->prepare($query);
        $post_statement->execute();
        
        $current_post = $post_statement->fetch();
        $post_id = $current_post['post_id'];
        
        $image_filename = $_FILES['image']['name'];
        $temporary_image_path = $_FILES['image']['tmp_name'];
        $new_image_path = file_upload_path($image_filename);
        if(file_is_an_image($temporary_image_path, $new_image_path)){
            //move_uploaded_file($temporary_image_path, $new_image_path);
            resize_image($temporary_image_path, $new_image_path);
            
            $query = "INSERT INTO images (post_id, file_name) VALUES (:post_id, :file_name)";
            $image_statement = $db->prepare($query);
            $image_statement->bindValue(":post_id", $post_id, PDO::PARAM_INT);
            $image_statement->bindValue(":file_name", $image_filename);
            $image_statement->execute();
        }
        else if(file_is_a_pdf($temporary_image_path, $new_image_path)){
            move_uploaded_file($temporary_image_path, $new_image_path);
        }
    }
    elseif($upload_error_detected){
        header("Location: error.php");
        exit;
    }
    
    header("Location: index.php");
    exit;
}
elseif($_POST && $isValid = strlen($_POST['title']) < 1 || strlen($_POST['content']) < 1 || !isset($_POST['category']) ? true : false){
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
    <script src="https://cdn.tiny.cloud/1/4ed3kawviq0nm81x4z3c5mynsqp1niz58qg14requ2l5qp8t/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <title>Create an Event</title>
</head>
<body>
    <?php if(isset($_SESSION['username'])): ?>
        <div class="container">
            <div>
                <h1>Create an Event</h1>
            </div>
            <div class="my-2">
                <form action="create_post.php" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="form-group col my-2">
                            <select class="select form-select" name="category">
                                <option value="0">Select an event category...</option>
                                <?php while($event = $statement->fetch()): ?>
                                    <option value="<?= $event['event_id'] ?>"><?= $event['title'] ?></option>
                                <?php endwhile ?>
                            </select>
                        </div>
                        <div class="form-group col my-2">
                            <select class="form-select" name="location">
                                <option value="">Select a location...</option>
                                <?php foreach($unique_area as $unique): ?>
                                    <option value="<?= $unique ?>"><?= $unique ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label for="title">Title</label>
                            <input class="form-control" type="text" name="title" id="title">
                        </div>
                        <div class="col">
                            <label for="image">Image</label>
                            <input class="form-control" type="file" name="image" id="image">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="content">Content</label>
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
                    <div class="d-flex">
                    <div class="my-2 mx-1">
                        <button class="btn btn-outline-primary" type="submit">Submit</button>
                    </div>
                    <div class="my-2">
                        <?php if(isset($_GET['event_id'])): ?>
                            <a class="btn btn-outline-danger" href="event.php?event_id=<?= $_GET['event_id'] ?>">Cancel</a>
                        <?php else: ?>
                            <a class="btn btn-outline-danger" href="index.php">Cancel</a>
                        <?php endif ?>
                    </div>
                    </div>
                </form>
            </div>
        </div>
    <?php else: ?>
        <?php header("Location: unauthorized.php") ?>
    <?php endif ?>
</body>
</html>