<?php

$hostname = 'localhost';
$username = 'root';
$password = '';
$dbname = 'chatapp';

$conn = mysqli_connect($hostname, $username, $password, $dbname);
if(!$conn){
    echo 'database connection error'.mysqli_connect_error();
}

?>
