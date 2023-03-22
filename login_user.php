<?php
session_start();

    if(isset($_GET['user_id']) && isset($_GET['username']) && isset($_GET['user_role_id'])){
        $_SESSION['user_id'] = $_GET['user_id'];
        $_SESSION['user_role_id'] = $_GET['user_role_id'];
        $_SESSION['username'] = $_GET['username'];
    }
?>