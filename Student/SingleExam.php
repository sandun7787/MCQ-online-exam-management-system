<?php 
session_start();
date_default_timezone_set('Asia/Kolkata');

//include database connection
require '../database_connection.php';
if (!isset($_SESSION['student_login_id']))
{
  header("Location: ../index.php?error=You Need To Login First");
  exit();
}

//get the Exam id using GET method
if(isset($_GET['id'])){
     $sql = "SELECT * FROM `exams` WHERE id='$_GET[id]'";
     $result = $conn->query($sql);
     $exam = $result->fetch_assoc();
     $_SESSION["name"] = $exam['name'];
     $_SESSION["examid"] = $_GET['id'];
     $_SESSION["duration"] = $exam['duration'];
     $_SESSION['start_time']=$exam['dateandtime'];

     $date_now=strtotime(date('Y-m-d h:i:sa'));
     $startdate=strtotime($exam['dateandtime']);
     $end_time=$end_time=date(' Y-m-d H:i:s',strtotime('+'.$_SESSION["duration"].'minute',strtotime($_SESSION["start_time"])));
     $end_time=strtotime($end_time);

     if ($date_now > $startdate) {
          if($date_now > $end_time){
               header("Location: ./Student_home.php?error=Exam Time is End");
          }
        
      }else{
          header("Location: ./Student_home.php?error=Exam start on "."$_SESSION[start_time]");
      }

   }
//get the Exam Status using GET Method 
if((isset($_GET['status'])))
{
     if($_GET['status']=="attended"){
           header("Location:ExamResults.php?exid=$_GET[id]");

     
     }elseif($_GET['status']=="End"){
          header("Location:Student_home.php");
     }
}

//get saved answer From Database
$getans = "SELECT question.questionNo, answer.optionvalue 
           FROM `student-answers` 
           JOIN answer ON `student-answers`.option_id = answer.id 
           JOIN question ON `student-answers`.question_id = question.id 
           WHERE `student-answers`.exam_id= $_SESSION[examid] 
           AND `student-answers`.student_id='$_SESSION[student_login_id]'";
//crate php array for save answer(database)
$getAnswer = array();
$getansresult = $conn->query($getans);

// Check if the query executed successfully
if ($getansresult === false) {
    // There was an error in the query
    echo "Error executing query: " . $conn->error;
} else {
    // Query executed successfully, check if there are rows in the result
    if ($getansresult->num_rows > 0) {
        // Loop through each row and store the data in the $getAnswer array
        while ($ansdata = $getansresult->fetch_assoc()) {
            $no = $ansdata['questionNo'];
            $getAnswer[$no] = $ansdata['optionvalue'];
        }
    }
}


 ?>
 <?php
//set the time standard

//calculate Exam End Time
$end_time=$end_time=date(' Y-m-d H:i:s',strtotime('+'.$_SESSION["duration"].'minute',strtotime($_SESSION["start_time"])));
//store Exam End time In session Varible
$_SESSION["end_time"]=$end_time;


$userdata = "SELECT * FROM exam.users WHERE id='" . $_SESSION['student_login_id'] . "'";


$userresult = mysqli_query($conn, $userdata);

if (!$userresult) {
    die("Query failed: " . mysqli_error($conn));
}

$userdetails = mysqli_fetch_assoc($userresult);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Student || Single exam page</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
<link rel="stylesheet" href="../assets/css/student/Exam.css">
<body>
<nav class="shadow-sm navbar navbar-expand-lg navbar-light bg-ligh bg-white rounded">
   <a class="navbar-brand" href="#">SCHOOL MCQ ONLINE APPLICATION</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <ul class="navbar-nav ml-auto">
    <div class="user-box dropdown">
		<a class="d-flex align-items-center nav-link  dropdown-toggle-nocaret" href="#" role="button" data-toggle="dropdown"  aria-expanded="false">
		<img src="../assets/u.jpg" width="50" class="rounded-circle" alt="user avatar">
			<div class="user-info ps-3 ml-1">
				<p class="user-name mb-0"><?php echo $userdetails['name']; ?></p>
				<p class="designattion mb-0">Student</p>
			</div>
		</a>
     <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated profile-dropdown">
          <a class="dropdown-item text-danger" href="../logout.php">Logout</a>
     </div>
     </div>
    </ul>
  </div>
</nav>
	   
         
            <div class="side2 border"  style="height:631.2px;">
              <div class="mt-5 ml-3 d-flex flex-row">
                <a href="Student_home.php"><i class="fas fa-chevron-left fa-2x"></i></a>
                <h3 class="ml-3" ><?php echo   $_SESSION["name"] ;?></h3>
           
               </div>
          
                <h5 class="ml-2 text-center" id="response"></h5>
                <div class="d-flex flex-column align-items-center" id="pagination_data">


                </div>
                <div class="mt-5 btncontrol">
                  <button class="btn btn-primary" onclick="save()">Save</button>
                  <button class="btn btn-info" id="myCheck" onclick="complete()">Complete</button>
                </div>
            </div> 


</body>
</html>
<script>  
//get selected saved answers from Datbase
var abc=<?php echo json_encode($getAnswer);?>;
//create array in javaScript
const main=[];
const quesno=[];
const selectcheck=[];


 $(document).ready(function(){  
     //call function for load the question and answers
      load_data();  
      //load question and Answers using ajax
      function load_data(page)  
      {  
           $.ajax({  
                url:'pagination.php',  
                method:"POST",  
                data:{page:page},  
                success:function(data){  
                     $('#pagination_data').html(data); 
                     ok(); 
                }  
           })  
        
      }  

      //question no Pargination
      $(document).on('click', '.pagination_link', function(){  
           var page = $(this).attr("id");  
           load_data(page);  
      });
      //next button pargination
      $(document).on('click', '.next', function(){  
           var totall = $(this).attr("id");  
           var page = parseInt($(this).attr("page")); 
           if(page<totall){
              page+=1;
           }
           load_data(page);  
          
      });
      //Pervious button pargination
      $(document).on('click', '.Pervious', function(){  
           var totall = $(this).attr("id");  
           var page = parseInt($(this).attr("page")); 
           if(page>1){
              page-=1;
           }
           load_data(page);  
          
      });
      
      // call the function to change the answers
      $(document).on('change', '.radio1', function(){  
           var ans = $(this).data("value");  
           var questionNo= $(this).data("id");
          main.push(ans);
          quesno.push(questionNo)
          selectcheck[questionNo]=ans
           ok(); 
      });  
 });  


 //create ok function
function ok()
{
  const nodeList = document.querySelectorAll("[name='choice']");
  const  x = document.getElementById("hiddenNo").value;
  console.log(selectcheck)
 
      for(var i=0; i<nodeList.length; i++)
      {
        if(abc[x]==nodeList[i].value)
        {
          nodeList[i].checked=true;
        }
      }
   

 
     for(var i=0; i<nodeList.length; i++)
      {
               if(selectcheck[x]==nodeList[i].value)
               {
                    nodeList[i].checked=true;
               }
      }

     
}


//create function for save Exam data 
function save(){
     const nodeList = document.querySelectorAll("[name='choice']");

$.ajax({  
           url:'Save.php',  
           method:"POST", 
           data:{id:main,name:quesno},
           type:'JSON', 
           success:function(data){  
               window.location.assign("student_home.php");
           }  
            
      })  

}

//Create function for complete the Exam
function complete(){
var exid=<?php echo json_encode( $_SESSION['examid']);?>

$.ajax({  
           url:'complete.php',  
           method:"POST", 
           data:{id:main,name:quesno},
           type:'JSON', 
           success:function(data){  
               window.location.assign("ExamResults.php?exid="+exid);
    
           }         
      })  
}

//======  This for Time Count ========

var countDownDate = <?php echo strtotime($_SESSION["end_time"]) ?> * 1000;
var now = <?php echo time() ?> * 1000;

// Update the count down every 1 second
var x = setInterval(function() {
now = now + 1000;
// Find the distance between now an the count down date
var distance = countDownDate - now;
// Time calculations for days, hours, minutes and seconds
var days = Math.floor(distance / (1000 * 60 * 60 * 24));
var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
var seconds = Math.floor((distance % (1000 * 60)) / 1000);
// Output the result in an element with id="demo"
document.getElementById("response").innerHTML ="Timeleft :"+" "+ days + "d " + hours + "h " +
minutes + "m " + seconds + "s ";
// If the count down is over, write some text 
if (distance < 0) {
clearInterval(x);
document.getElementById("myCheck").click()
}    
}, 1000);
</script>