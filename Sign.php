<?php
session_start(); 
require 'database_connection.php';
if(isset($_POST['Sign'])){
    $email=$_POST['email'];
    
    $password=md5($_POST['password']);

    $sql="SELECT * FROM users WHERE email='$email'";
    $result=mysqli_query($conn,$sql);
    $rowcount=mysqli_num_rows($result);

    if($rowcount==1){
        $sql="SELECT * FROM users WHERE email='$email' AND password='$password'";
        $result=mysqli_query($conn,$sql);
        $rowcount=mysqli_num_rows($result);

        if($rowcount==1){
            $data=mysqli_fetch_assoc($result);
            if($data['usertype']=='Student'){
                $_SESSION['student_login_id'] = $data['id'];
            	header("Location: student/Student_home.php");
		        exit();
            }else{
                $_SESSION['teacher_login_id'] = $data['id'];
            	header("Location: Teacher/dashboard.php");
		        exit();
            }
        }else{
            header("Location: index.php?error=you entered wrong password");
            exit();
        }
    }
    else{
        header("Location: index.php?error=email does not exist");
		exit();
      
    }
}
?>