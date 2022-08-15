<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "mediastore";

$connect = mysqli_connect($host, $user, $pass, $db);

if (isset($_GET['db-connect'])) {
    $sql = "SELECT * from product";
    $result = mysqli_query($connect, $sql);
    echo json_encode($product = mysqli_fetch_all($result, MYSQLI_ASSOC));    
} 
