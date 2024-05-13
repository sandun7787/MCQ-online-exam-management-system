<?php 
session_start();

// Include database connection
require '../database_connection.php'; 

// User authentication
if (!isset($_SESSION['roleid']) || $_SESSION['roleid'] != 2) { // Assuming roleid 2 is for students
    header("Location: ../index.php?error=You Need To Login First");
    exit();
}

// Get the exam id using GET method
if(isset($_GET['exid'])){
    $exam_id = $_GET['exid']; // Sanitize or validate the input here
    $sql = "SELECT * FROM `exams` WHERE id='$exam_id'";
    $result = $conn->query($sql);
    if ($result && $exam = $result->fetch_assoc()) {
        // Store exam details in session variable
        $_SESSION["name"] = $exam['name'];
        $_SESSION["examid"] = $exam_id;
    } else {
        // Handle if the exam doesn't exist
    }
}

// Get the result of student from the database
$selectResult = "SELECT * FROM `examenrollment` WHERE student_id='{$_SESSION['roleid']}' AND Exam_id='{$_SESSION['examid']}'";
$sresult = $conn->query($selectResult);
if ($sresult && $resultdata = $sresult->fetch_assoc()) {
    // Store exam mark of this student
    $marks = (int)$resultdata['result'];
    $state = "Some state value"; // Assign some value to $state if needed
} else {
    // Handle if the result doesn't exist
}

//exam result calculation
  if ($marks>85)
  {
      $grade = "A";
      $state="Passed";
  }
  else if($marks>65)
  {
      $grade = "B";
      $state="Passed";
  }
  else if($marks>45)
  {
      $grade = "C";
      $state="Passed";
  }
  else if($marks>25)
  {
      $grade = "S";
      $state="Passed";
  }
  else
  {
      $grade = "W";
      $state="Failed";
  }

// Fetch user details if roleid is 2 (assuming roleid 2 is for students)
if ($_SESSION['roleid'] == 2) {
    $userdata = "SELECT * FROM mcqsystem1.user WHERE roleid='" . $_SESSION['roleid'] . "'";
    $userresult = mysqli_query($conn, $userdata);
    
    if (!$userresult) {
        die("Query failed: " . mysqli_error($conn));
    }
    
    $userdetails = mysqli_fetch_assoc($userresult);
}


 ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Student || Exam Results page</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
<link rel="stylesheet" href="../assets/css/student/examresult.css">
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

        
            <div class="side2 border d-flex flex-column"  style="height:631.2px;">
                <div class="mt-4 ml-3 d-flex flex-row">
                    <a href="Student_home.php" class="mt-0"><i class="fas fa-chevron-left fa-2x"></i></a>
                    <h3 class="ml-3 mt-0" ><?php echo $_SESSION['name']?></h3>
                </div>
                
                    <div class="border mb-1 w-25 align-items-center Result">
                        <label class="ml-4 mt-3"><b>Exam Completed</b></label>
                        <div class="d-flex flex-column text-center">
                            <?php
                                if($state=="Passed")
                                {
                               echo'<h1 class="pass display-4">'. $state.'</h2>';
                                }
                                else{
                                echo'<h1 class="fail display-4">'. $state.'</h2>';
                                }
                            ?>
                            <label for=""><?php echo $grade.'-'.  $marks;?> Points</label>    
                        </div>
    
                    </div>
                    <div class="border pb-2  w-25  align-items-center Result">
                        <label class="ml-4 mt-3"><b> Questions</b> </label>
                        <div class="d-flex flex-column" id="jar">

                        <?php
$sql1 = "SELECT * FROM question WHERE examid = $_SESSION[examid]";
$sql1result = $conn->query($sql1);

if ($sql1result === false) {
    // There was an error in the query
    echo "Error executing query: " . $conn->error;
} else {
    // Query executed successfully, check if there are rows in the result
    if ($sql1result->num_rows > 0) {
        while ($row = $sql1result->fetch_assoc()) {
            echo '<div class="p-2 group w-75 ml-5 mb-2 mt-2 shadow-sm list content">Question ' . $row['questionNo'];
            $sql2 = "SELECT * FROM `mcqsystem`.`student-answers` WHERE student_id = $_SESSION[student_login_id] AND question_id = $row[id] AND exam_id = '$_SESSION[examid]'";
            $sql2result = $conn->query($sql2);

            if ($sql2result === false) {
                // There was an error in the query
                echo "Error executing query: " . $conn->error;
            } else {
                // Query executed successfully, check if there are rows in the result
                if ($sql2result->num_rows > 0) {
                    $row2 = $sql2result->fetch_assoc();
                    if ($row2['question_result'] == "Pass") {
                        echo '<span class="Correct">Correct</span>';
                    } else {
                        echo '<span class="wrong">Wrong</span>';
                    }
                } else {
                    echo '<span class="wrong">Not answered</span>';
                }
            }
            echo '</div>';
        }
    }
}
?>

                        </div>
                   
                        <nav class="mt-1 mr-2 float-right" >
                            <ul class="pagination justify-content-center pagination-sm">
                            </ul>
                        </nav>
                        <div class="closebtn mt-5 ml-2">
                        <a class="btn btn-secondary" href="Student_home.php">Close</a>
                        </div>
                    </div>    
            </div> 

</body>
</html>
<script src="../assets/js/student/ParginationOfExamResult.js"></script>
