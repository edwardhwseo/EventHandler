<?php

/*******w******** 
    
    Name: Edward Seo
    Description: Page to display a specified Event Category

****************/

session_start();
require('connect.php');

//Nav
$query = "SELECT * FROM events";
$statement_nav = $db->prepare($query);
$statement_nav->execute();

//Select
$query = "SELECT * FROM events";
$statement_type = $db->prepare($query);
$statement_type->execute();

if($_GET && isset($_GET['event_id']) && filter_input(INPUT_GET, 'event_id', FILTER_VALIDATE_INT)){
    $query = "SELECT * FROM events WHERE event_id = :event_id LIMIT 1";
    $statement = $db->prepare($query);

    $id = filter_input(INPUT_GET, 'event_id', FILTER_SANITIZE_NUMBER_INT);
    $statement->bindValue('event_id', $id, PDO::PARAM_INT);
    $statement->execute();

    $event = $statement->fetch();
}
else if(!isset($_GET['event_id'])){
    header("Location: index.php");
    exit;
}

//Post
$query = "SELECT * FROM posts p JOIN users u ON u.user_id = p.user_id WHERE event_id = :event_id ORDER BY created_at DESC";
$statement_post = $db->prepare($query);
$event_id = filter_input(INPUT_GET, 'event_id', FILTER_SANITIZE_NUMBER_INT);
$statement_post->bindValue('event_id', $event_id, PDO::PARAM_INT);
$statement_post->execute();
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
    <title><?= $event['title'] ?></title>
</head>
<body>
    <div class="container">
    <nav class="navbar navbar-expand-lg bg-light border p-3 my-3">
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="btn btn-light" href="index.php">Home</a>
                    </li>
                    <?php while($row = $statement_nav->fetch()): ?>
                        <li class="nav-item">
                            <a class="btn btn-light" href="event.php?event_id=<?= $row['event_id']?>"><?= $row['title'] ?></a>
                        </li>
                    <?php endwhile ?>
                </ul>
            </div>
            <div class="collapse navbar-collapse d-flex justify-content-end">
                <form class="form-inline d-flex my-2 my-lg-0" action="search_post.php" method="get">
                    <div>
                        <select class="select form-select w-auto" name="type">
                            <option value="0">All Categories</option>
                            <?php while($type = $statement_type->fetch()): ?>
                                <option value="<?= $type['event_id'] ?>"><?= $type['title'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </div>
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
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="logout.php">Sign Out</a>
                                </li>
                            </ul>
                        </li>
                    <?php endif ?>
                </ul>
            </div>
        </nav>
        <h1>
            <?= $event['title'] ?>
            <a class="btn btn-outline-secondary btn-sm" href="edit.php?event_id=<?= $event['event_id'] ?>">Edit
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"/>
            </svg>
            </a>
        </h1>
        <h5><?= $event['content'] ?></h5>
        <a class="btn btn-outline-primary" href="create_post.php?event_id=<?= $event['event_id'] ?>">Create an Event</a>
        <div class="my-3">
            <?php while($post = $statement_post->fetch()): ?>
                <div class="card bg-light mb-2 p-2">
                    <div class="d-flex">
                        <h3>
                            <a class="link-dark text-decoration-none" href="post.php?post_id=<?= $post['post_id'] ?>"><?= $post['title'] ?></a>
                        </h3>
                        <?php if(strlen($post['location']) > 0): ?>
                        <div>
                            <a class="btn btn-success btn-sm mx-2" href="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                                    <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A31.493 31.493 0 0 1 8 14.58a31.481 31.481 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94zM8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10z"/>
                                    <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                </svg>
                                <?= $post['location'] ?>
                            </a>
                        </div>
                        <?php endif ?>
                    </div>
                    <p>
                        <small><?= $post['username'] ?> &middot; <?= date("F j, Y, g:i a", strtotime($post['created_at'])) ?> &middot; <a class="text-decoration-none" href="edit_post.php?post_id=<?= $post['post_id'] ?>">Edit</a></small>
                    </p>
                    <div>
                        <?= $post['content'] ?>
                    </div>
                </div>
            <?php endwhile ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>