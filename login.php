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
    <script src="login.js"></script>
    <title>Sign In</title>
</head>
<body>
    <div class="container">
        <div class="border p-3 my-3 bg-light">
            <div class="row mb-3">
                <div>
                    <h1>Sign In</h1>
                </div>
            </div>
            <div class="username-invalid-msg alert alert-danger" role="alert">
                Sorry this username does not exist.
            </div>
            <div class="password-invalid-msg alert alert-danger" role="alert">
                Invalid password.
            </div>
            <form class="login_form" action="index.php" method="post">
                <div class="form-group mb-2">
                    <input class="username form-control" name="username" placeholder="Username">
                </div>
                <div class="row mb-2">
                    <div class="form-group mb-2">
                        <input class="form-control" type="password" name="password" placeholder="Password">
                    </div>
                </div>
                <button class="btn btn-primary" id="submit" type="submit">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>