<?php
session_start();
require('connect.php');

// Nav
$query = "SELECT * FROM events";
$statement_nav = $db->prepare($query);
$statement_nav->execute();

$sortType = "...";

// Posts
if(isset($_POST['sort']) && $_POST['sort'] != 0){
    $sort = "";
    //$sortType = "";

    if($_POST['sort'] == 1){
        $sort = "title";
        $sortType = "Title";
    }
    else if($_POST['sort'] == 2){
        $sort = "created_at";
        $sortType = "Date Created (Oldest to Newest)";
    }
    else if($_POST['sort'] == 3){
        $sort = "created_at DESC";
        $sortType = "Date Created (Newest to Oldest)";
    }
    else{
        $sort = "updated_by";
        $sortType = "Last Updated";
    }

    $query = "SELECT * FROM posts ORDER BY $sort";
    $statement = $db->prepare($query);
    $statement->execute();
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

    <title>All Posts</title>
</head>
<body>
    <?php if(isset($_SESSION['username'])): ?>
    <div class="container">
        <nav class="navbar navbar-expand-lg bg-light border p-3 my-3">
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <?php while($row = $statement_nav->fetch()): ?>
                        <li class="nav-item">
                            <a class="btn btn-light" href="event.php?event_id=<?= $row['event_id']?>"><?= $row['title'] ?></a>
                        </li>
                    <?php endwhile ?>
                </ul>
            </div>
            <div class="collapse navbar-collapse d-flex justify-content-end">
                <ul class="navbar-nav">
                    <?php if(!isset($_SESSION['username'])): ?>
                        <li class="nav-item mx-1">
                            <a class="btn btn-outline-success" href="login.php">Sign In</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-primary" href="register.php">Sign Up</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle mx-2" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?= $_SESSION['username'] ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li>
                                    <a class="dropdown-item" href="post_list.php">All Posts</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-danger" href="logout.php">Log Out</a>
                        </li>
                    <?php endif ?>
                </ul>
            </div>
        </nav>
        <div>
            <div>
                <h4>All Posts, Sorted By <?= $sortType ?></h4>
            </div>
            <div class="mb-2">
                <form action="post_list.php" method="post">
                    <div class="my-2">
                        <select class="select form-select" name="sort">
                            <option value="0">Select...</option>
                            <option value="1">Title</option>
                            <option value="2">Date Created (Oldest to Newest)</option>
                            <option value="3">Date Created (Newest to Oldest)</option>
                            <option value="4">Last Updated</option>
                        </select>
                    </div>
                    <button class="btn btn-outline-primary" type="submit">Sort</button>
                </form>
            </div>
            <?php if(isset($_POST['sort']) && $_POST['sort'] != 0): ?>
            <?php while($post = $statement->fetch()): ?>
                <div class="border bg-light mb-2 p-2">
                    <div>
                        <h3><?= $post['title'] ?></h3>
                    </div>
                    <p>
                        <small><?= date("F j, Y, g:i a", strtotime($post['created_at'])) ?> - <a href="#">Edit</a></small>
                    </p>
                    <div>
                        <?= $post['content'] ?>
                    </div>
                </div>
            <?php endwhile ?>
            <?php endif ?>
        </div>
    </div>
    <?php else: ?>
        <?php header("Location: unauthorized.php") ?>
    <?php endif ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>