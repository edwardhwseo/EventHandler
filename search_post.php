<?php
session_start();
require('connect.php');

//Pagination
/*$records_per_page = 2;

$query = "SELECT * FROM posts";
$post_statement = $db->prepare($query);
$post_statement->execute();
$total_records = $post_statement->rowCount();
$total_pages = ceil($total_records / $records_per_page);*/

if(isset($_GET['page'])){
    $page = $_GET['page'];
}
else{
    $page = 1;
}

$limit = 2;
$inital_row = ($page - 1) * $limit;

$query = "SELECT * FROM events";
$statement = $db->prepare($query);
$statement->execute();

//Select
$query = "SELECT * FROM events";
$statement_type = $db->prepare($query);
$statement_type->execute();

if(isset($_GET['search']) && strlen($_GET['search']) > 0){
    $keyword = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $records_per_page = 2;
    $total_records = 0;
    $total_pages = 0;

    if(isset($_GET['type']) && $_GET['type'] != 0){
        $event_id = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_NUMBER_INT);
        $query = "SELECT * FROM posts p JOIN users u ON u.user_id = p.user_id WHERE event_id = :event_id AND title LIKE '%$keyword%' LIMIT $inital_row, $limit";
        $statement_post = $db->prepare($query);
        $statement_post->bindValue(':event_id', $event_id, PDO::PARAM_INT);
        $statement_post->execute();
        $total_records = $statement_post->rowCount();
        $total_pages = ceil($total_records / $records_per_page);
    }
    else{
        $query = "SELECT * FROM posts p JOIN users u ON u.user_id = p.user_id WHERE title LIKE '%$keyword%' OR content LIKE '%$keyword%' LIMIT $inital_row, $limit";
        $statement_post = $db->prepare($query);
        $statement_post->execute();
        $total_records = $statement_post->rowCount();
        $total_pages = ceil($total_records / $records_per_page);
    }
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
    <title>Searched Posts</title>
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
        <?php if(isset($_GET['search']) && strlen($_GET['search']) > 0): ?>
            <div class="mb-3">
                <h4>Posts searched with "<?= $keyword ?>"</h4>
            </div>
            <div class="my-2">
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
                <div>
                    <?php if($page >=2): ?>
                        <a href="search_post.php?search=<?= $_GET['search'] ?>&type=<?= $_GET['type'] ?>&page=<?= $page-1 ?>">Prev</a>
                    <?php endif ?>
                    <?php for($i=1; $i <= $total_pages; $i++): ?>
                        <?php if($i == $page): ?>
                            <a class="text-decoration-none active" href="search_post.php?search=<?= $_GET['search'] ?>&type=<?= $_GET['type'] ?>&page=<?= $i ?>"><?= $i ?></a>
                        <?php else: ?>
                            <a class="text-decoration-none" href="search_post.php?search=<?= $_GET['search'] ?>&type=<?= $_GET['type'] ?>&page=<?= $i ?>"><?= $i ?></a>
                        <?php endif ?>
                    <?php endfor ?>
                    <?php if($page <= $total_pages): ?>
                        <a href="search_post.php?search=<?= $_GET['search'] ?>&type=<?= $_GET['type'] ?>&page=<?= $page+1 ?>">Next</a>
                    <?php endif ?>
                </div>
        <?php else: ?>
            <h4>No Posts Found</h4>
        <?php endif ?>
            </div>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>