<?php
session_start();

    if(isset($_GET['username']) && isset($_GET['user_role_id'])){
        $_SESSION['username'] = $_GET['username'];
        $_SESSION['user_role_id'] = $_GET['user_role_id'];
    }
?>