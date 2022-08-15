<?php
require_once "db-connect.php";

$search = $_GET["search"];

$sql = "SELECT* FROM product where `name` LIKE '$search%' OR `description` LIKE '$search%'";

$result = mysqli_query($connect, $sql);

// if(mysqli_num_rows($result) == 0){
//     echo "no Result";
// }else {
//     while($row = mysqli_fetch_assoc($result)){
//         echo "<p>{$row['name']} {$row["surname"]}  {$row["email"]}</p>";
//     }
// }
