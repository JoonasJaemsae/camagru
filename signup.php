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
        <form method="POST" action="signup.php">
            <input type="text" name="login" placeholder="Username">
            <input type="password" name="password" placeholder="Password">
            <input type="password" name="passwordAgain" placeholder="Retype password">
            <input type="text" name="email" placeholder="Email">
            <input type="submit" id="submit" name="submit" value="Register">
        </form>
        <div class="flex-container1">
            <div class="subBoxLeft">
                <a href="./index.html">Back to login</a>
            </div>

        </div>
        <div>
            <?php
            if (isset($_POST['submit'])) {
                if ($_POST['password'] == $_POST['passwordAgain']) {
                    if ($_POST['login'] && $_POST['password']
                        && $_POST['passwordAgain'] && $_POST['email']) {
                            // header("Location: account_validation.php");
                        echo '<p style="color: blue; text-align: center">Registration successful! Click the link above to return
                        to login screen</p>';
                    } else {
                        echo '<p style="color: red; text-align: center">Please fill in all the fields!</p>';
                    }
                }
                else {
                    echo '<p style="color: red; text-align: center">Passwords don\'t match. Please check your password!</p>';
                }
            }
            ?>
        </div>
    </div>
</body>

</html>

<?php
// function display()
//             {
//                 echo "Hello " . $_POST['login'];
//             }
// if (isset($_POST['submit'])) {
//     display();
// }

// INSERT INTO testusers (`login`, `password`, `email`)
// VALUES ('jjamsa', 'root', 'jamsa.joonas@gmail.com'); INSERT INTO testusers (`login`, `password`, `email`)
// VALUES (‘lsalmi’, ‘test123’, ‘lsalmi@geemail.com'); INSERT INTO testusers (`login`, `password`, `email`)
// VALUES (‘mapostol’, ‘test1234’, ‘mapostol@geemail.com'); INSERT INTO testusers (`login`, `password`, `email`)
// VALUES (‘plehtika’, ‘test12345’, ‘plehtika@geemail.com');