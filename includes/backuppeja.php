<?php

class Dbh {
 
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $charset;
 
    public function connect() {
        $this->servername = "localhost";
        $this->port = "8888";
        $this->username = "root";
        $this->password = "root";
        $this->dbname = "rex";
        $this->charset = "utf8mb4";
 
        // ";port=".$this->port.
 
        try {
            $dsn = "mysql:host=".$this->servername.";dbname=".$this->dbname.";charset=$this->charset";
            $pdo = new PDO($dsn, $this->username, $this->password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}

?>
 
<?php
 
$host = 'localhost';
$username = 'root';
$password = 'root';
$dbName = 'rex';
$charset = "utf8mb4";
$dsn = "mysql:host=$host;dbname=$dbName;charset=$charset";
 
try {
   $dbConn = new PDO($dsn, $username, $password);
   echo "Connection successful!";
} catch (PDOException $errorMessage) {
   echo $errorMessage . "<br>";
   echo "Jotain meni pieleen" . "<br>";
}
 
?>