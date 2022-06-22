<!DOCTYPE html>
<html>
<head>
<title>Camagru</title>

<style>

<?php

include "style.css";

?>

</style>

</head>

</html>

<?php

if ($_POST['username'] == 'jjamsa' && $_POST['password'] == 'jjamsa') {
    ?>
    <!DOCTYPE html>
    <html>
    <body>
        <h1 style="margin-top: 100px">Welcome, master!</h1>
    
    <div>
          <img src="http://media2.s-nbcnews.com/i/streams/2013/June/130617/6C7911377-tdy-130617-leo-toasts-1.jpg"
          alt="Congrats!">
    </div>
    </body>
    </html>
<?php

}
else {
    echo "Incorrect login details. Click "
    . '<a href="./index.html">here</a>'
    . " to return to the login page.";
}

?>
