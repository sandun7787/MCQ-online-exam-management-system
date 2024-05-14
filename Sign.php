<?php
session_start(); 
require 'database_connection.php';

if(isset($_POST['Sign'])){
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM user WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $sql);
    $rowcount = mysqli_num_rows($result);

    if($rowcount == 1){
        $data = mysqli_fetch_assoc($result);
        $roleid = $data['roleid'];

        $_SESSION['roleid'] = $roleid;

        if($roleid == 2){
            header("Location: student/Student_home.php");
            
        } elseif($roleid == 1) {
            header("Location: Teacher/Teacher_home.php");
            
        } else {
            // Handle other roles or unexpected cases
        }
    } else {
        header("Location: index.php?error=Invalid email or password");
       
    }
}
?>
