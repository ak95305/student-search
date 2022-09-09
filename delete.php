<?php 
$del_id = $_POST["id"];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = 'students';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$del_sql = "DELETE FROM `students_list` WHERE student_code = '".$del_id."'";

if($del_res = mysqli_query($conn, $del_sql)){
    echo 1;
}else{
    echo 0;
}

?>