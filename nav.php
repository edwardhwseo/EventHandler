<?php
require('connect.php');

$query = "SELECT * FROM events";
$statement = $db->prepare($query);
$statement->execute();
?>

<nav class="navbar navbar-expand-lg bg-light border p-3 my-3">
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav">
            <?php while($row = $statement->fetch()): ?>
                <li class="nav-item">
                    <a class="btn btn-light" href="event.php?event_id=<?= $row['event_id']?>"><?= $row['title'] ?></a>
                </li>
            <?php endwhile ?>
            <li class="nav-item">
                <a class="btn btn-outline-success" href="login.php">Sign In</a>
                <a class="btn btn-outline-primary" href="register.php">Sign Up</a>
            </li>
        </ul>
    </div>
</nav>