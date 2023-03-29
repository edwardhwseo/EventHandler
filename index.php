<?php
session_start();
require('connect.php');

$query = "SELECT * FROM events";
$statement = $db->prepare($query);
$statement->execute();

if(isset($_POST['submit']) && isset($_POST['search']) && strlen($_POST['search']) > 0){
    $keyword = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $query = "SELECT * FROM posts p JOIN users u ON u.user_id = p.user_id WHERE title LIKE '%$keyword%' OR content LIKE '%$keyword%'";
    $statement_post = $db->prepare($query);
    $statement_post->execute();
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
    <title>Home Page</title>
</head>
<body>
    <div class="container">
        <nav class="navbar navbar-expand-lg bg-light border p-3 my-3">
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="btn btn-light" href="index.php">Home</a>
                    </li>
                    <?php while($row = $statement->fetch()): ?>
                        <li class="nav-item">
                            <a class="btn btn-light" href="event.php?event_id=<?= $row['event_id']?>"><?= $row['title'] ?></a>
                        </li>
                    <?php endwhile ?>
                </ul>
            </div>
            <div class="collapse navbar-collapse d-flex justify-content-end">
                <form class="form-inline d-flex my-2 my-lg-0" action="index.php" method="post">
                    <input class="form-control mr-sm-2 mx-1" name="search" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" name="submit" type="submit">Search</button>
                </form>
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
        <?php if(isset($_SESSION['username'])): ?>
            <div>
                <h5>Welcome Back, <?= $_SESSION['username'] ?>!</h5>
            </div>
            <div>
                <a class="btn btn-outline-primary" href="create_post.php">Create an Event</a>
                <a class="btn btn-outline-success" href="create.php">Create a Category</a>
            </div>
        <?php endif ?>
        <div class="my-2">
            <?php if(isset($_POST['submit']) && isset($_POST['search']) && strlen($_POST['search']) > 0): ?>
            <?php while($post = $statement_post->fetch()): ?>
                <div class="card bg-light mb-2 p-2">
                    <div>
                        <h3>
                            <a class="link-dark text-decoration-none" href="post.php?post_id=<?= $post['post_id'] ?>"><?= $post['title'] ?></a>
                        </h3>
                    </div>
                    <p>
                        <small><?= $post['username'] ?> &middot; <?= date("F j, Y, g:i a", strtotime($post['created_at'])) ?> &middot; <a class="text-decoration-none" href="edit_post.php?post_id=<?= $post['post_id'] ?>">Edit</a></small>
                    </p>
                    <div>
                        <?= $post['content'] ?>
                    </div>
                </div>
            <?php endwhile ?>
            <?php endif ?>
        </div>
        <div class="my-3">
            <h5>Quick Access</h5>
            <a class="btn btn-outline-primary" href="admin.php">Admin</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>