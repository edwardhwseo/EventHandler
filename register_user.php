<?php
require('connect.php');

    $fname    = filter_input(INPUT_GET, 'fname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lname    = filter_input(INPUT_GET, 'lname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $username = filter_input(INPUT_GET, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_GET, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email    = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $query = "INSERT INTO users (user_role_id,
                                 first_name,
                                 last_name,
                                 username,
                                 email,
                                 password)
              VALUES (2,
                     :fname,
                     :lname,
                     :username,
                     :email,
                     :password)";

    $statement = $db->prepare($query);
    $statement->bindValue(":fname", $fname);
    $statement->bindValue(":lname", $lname);
    $statement->bindValue(":username", $username);
    $statement->bindValue(":password", $password);
    $statement->bindValue(":email", $email);
    $statement->execute();
?>