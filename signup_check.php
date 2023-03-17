<?php
require('connect.php');
    $registered_usernames = [];
    $registered_emails = [];
    
    $response = [
        'success' => false,
        'available' => false
    ];
    
    $query = "SELECT * FROM users";
    $statement = $db->prepare($query);
    $statement->execute();
    
    while($row = $statement->fetch()){
        $registered_usernames[] = $row['username'];
        $registered_emails[] = $row['email'];
    }

  if(isset($_GET['username']) && (strlen($_GET['username']) !== 0)){
    $response['available'] = !in_array(strtolower($_GET['username']), $registered_usernames);
    $response['success'] = true;
  }

  if(isset($_GET['email']) && (strlen($_GET['email']) !== 0)){
    $response['available'] = !in_array(strtolower($_GET['email']), $registered_emails);
    $response['success'] = true;
  }

  header('Content-Type: application/json');

  echo json_encode($response);
?>