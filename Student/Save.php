<?php
//this all code are use to save the save the Exam anwser
session_start();
//include database connection
require '../database_connection.php';
//user auth
if (!isset($_SESSION['roleid']))
{
  header("Location: ../index.php?error=You Need To Login First");
  exit();
}
//exam auth
if (!isset($_SESSION["examid"]))
{
  header("Location: ../index.php?error=You Need To Login First");
  exit();
}
//save the already saved exam
$sql="SELECT * FROM `examenrollment` WHERE  student_id='$_SESSION[roleid]' AND Exam_id='$_SESSION[examid]'";
$result=mysqli_query($conn,$sql);
if($result->num_rows > 0)
{
  $question_an = $_POST['id'];
  $no = $_POST['name'];
  $c=array_combine($no,$question_an);
  
  foreach($c as $qus=>$ans){
  
      $sql="SELECT * FROM `question` WHERE examid='$_SESSION[examid]' AND questionNo='$qus'";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) 
      {
           $row = $result->fetch_assoc();
           $questionID=$row['id'] ;

          $sqlnew="SELECT * FROM `student-answers` WHERE  question_id='$questionID' AND student_id='$_SESSION[student_login_id]'";
          $resultnew = $conn->query($sqlnew);
          if ($resultnew->num_rows > 0) 
          {
            $sql1="SELECT * FROM `answer` WHERE questionId='$questionID' AND optionvalue='$ans'";
            $result1=$conn->query($sql1);
            if ($result1->num_rows > 0) 
            {
             $row1= $result1->fetch_assoc();
             $optionID=$row1['id'];
             $answercheck=$row1['iscoorect'];
             if($answercheck==1){
                $mark="Pass";
             }
             else{
                $mark="fail";
             }
             $sql3="UPDATE `student-answers` SET `option_id`='$optionID',`question_result`='$mark' WHERE student_id='$_SESSION[student_login_id]' AND question_id='$questionID'";
             if ($conn->query($sql3) === TRUE) {
                echo "New record created successfully";
              } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
              }
    
            }
          }
          else{
            $sql1="SELECT * FROM `answer` WHERE questionId='$questionID' AND optionvalue='$ans'";
          $result1=$conn->query($sql1);
          if ($result1->num_rows > 0) 
          {
           $row1= $result1->fetch_assoc();
           $optionID=$row1['id'];
           $answercheck=$row1['iscoorect'];
           if($answercheck==1){
              $mark="Pass";
           }
           else{
              $mark="fail";
           }
           $sql3="INSERT INTO `student-answers`(`option_id`, `question_id`, `student_id`, `exam_id`, `question_result`) 
           VALUES ('$optionID','$questionID','$_SESSION[roleid]','$_SESSION[examid])','$mark')";
           if ($conn->query($sql3) === TRUE) {
              echo "New record created successfully";
            } else {
              echo "Error: " . $sql . "<br>" . $conn->error;
            }
  
          }

          }
           
      }    
  }



}
//save new exam anwsers
else
{
  $sqlFisrt="INSERT INTO `examenrollment`(`student_id`, `Exam_id`, `Examstatus`) 
VALUES ('$_SESSION[roleid]','$_SESSION[examid]','draft')";
if ($conn->query($sqlFisrt) === TRUE) 
{
$question_an = $_POST['id'];
$no = $_POST['name'];
$c=array_combine($no,$question_an);

foreach($c as $qus=>$ans){

    $sql="SELECT * FROM `question` WHERE examid='$_SESSION[examid]' AND questionNo='$qus'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) 
    {
         $row = $result->fetch_assoc();
         $questionID=$row['id'] ;
         $sql1="SELECT * FROM `answer` WHERE questionId='$questionID' AND optionvalue='$ans'";
        $result1=$conn->query($sql1);
        if ($result1->num_rows > 0) 
        {
         $row1= $result1->fetch_assoc();
         $optionID=$row1['id'];
         $answercheck=$row1['iscoorect'];
         if($answercheck==1){
            $mark="Pass";
         }
         else{
            $mark="fail";
         }
         $sql3="INSERT INTO `student-answers`(`option_id`, `question_id`, `student_id`, `exam_id`, `question_result`) 
         VALUES ('$optionID','$questionID','$_SESSION[roleid]','$_SESSION[examid])','$mark')";
         if ($conn->query($sql3) === TRUE) {
            echo "New record created successfully";
          } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
          }

        }
    }    
}
}
}
?>