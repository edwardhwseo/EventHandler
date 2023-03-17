<?php

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
    <title>Registration</title>
</head>
<body>
    <div class="container">
        <div class="row my-3">
            <div>
                <h1>Sign Up</h1>
            </div>
        </div>
        <form action="thankyou.php" method="post">
            <div class="row mb-2">
                <div class="form-group col-md-2">
                    <input class="form-control" name="fname" placeholder="First Name">
                </div>
                <div class="form-group col-md-2">
                    <input class="form-control" name="lname" placeholder="Last Name">
                </div>
            </div>
            <div class="form-group mb-2 col-4">
                <input class="form-control" name="username" placeholder="Username">
            </div>
            <div class="row mb-2">
                <div class="form-group col-md-2">
                    <input class="form-control" type="password" name="password" placeholder="Password">

                </div>
                <div class="form-group col-md-2">
                    <input class="form-control" type="password" name="confirmpass" placeholder="Confirm">
                </div>
                <div class="mx-2">
                    <small class="form-text text-muted">Use 8 or more characters with a mix of letters, numbers and symbols</small>
                </div>
            </div>
            <div class="form-group col-4">
                <input class="form-control" type="email" name="email" placeholder="Email">
            </div>
        </form>
    </div>
</body>
</html>