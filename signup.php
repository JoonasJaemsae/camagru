<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru - Create an account</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <div class="accountCreationBox">
        <h1>Camagru</h1>
        <form action="welcome.php" method="POST">
            <input type="text" name="username" placeholder="Username">
            <input type="password" name="password" placeholder="Password">
            <input type="password" name="passwordAgain" placeholder="Retype password">
            <input type="submit" id="submit" value="Register">
        </form>
        <div class="flex-container1">
            <div class="subBoxLeft">
                <a href="./index.html">Back to login</a>
            </div>           

        </div>
    </div>
</body>

</html>