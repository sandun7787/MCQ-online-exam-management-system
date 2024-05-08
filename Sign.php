<?php
session_start(); 
require 'database_connection.php';

if(isset($_POST['Sign'])){
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM user WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    $rowcount = mysqli_num_rows($result);

    if($rowcount == 1){
        $data = mysqli_fetch_assoc($result);
        if($data['password'] == $password){
            if($data['roleid'] == 2){ // Assuming '2' is the identifier for students
                $_SESSION['roleid'] = $data['id'];
                header("Location: Student/Student_home.php");
                exit();
            } else if ($data['roleid'] == 1) { // Assuming '1' is the identifier for teachers
                $_SESSION['roleid'] = $data['id'];
                header("Location: Teacher/dashboard.php");
                exit();
            }
        } else {
            header("Location: index.php?error=you entered wrong password");
            exit();
        }
    } else {
        header("Location: index.php?error=email does not exist");
        exit();
    }
}
?>
