<?php
session_start();
// Include database connection
require '../database_connection.php';

// Teacher authentication
if (!isset($_SESSION['roleid']) || $_SESSION['roleid'] != 1) {
    header("Location: ../index.php?error=You Need To Login First");
    exit();
}

// Get exams created by this teacher
$query = $conn->query("SELECT * FROM `exams` WHERE teacherid='$_SESSION[roleid]' AND status='published' ORDER BY id ASC");
$Exam = array();
if ($query) {
    foreach ($query as $data) {
        $Exam[] = $data['name'];
    }
} else {
    echo "Error: " . $conn->error;
}

// Get total students
$query1 = $conn->query("SELECT * FROM `exams` WHERE teacherid='$_SESSION[roleid]' AND status='published'");
// Initialize arrays and variables
$a = array();
$b = array();
$c = array();
$d = array();
$e = array();
$totall = 0;

// Calculate average result grade percentages
$PI = "SELECT COUNT(id) as no, GRADE FROM `examenrollment` GROUP BY GRADE ORDER BY GRADE ASC";
$PIresult = mysqli_query($conn, $PI);
if ($PIresult) {
    while ($value = mysqli_fetch_assoc($PIresult)) {
        array_push($a, $value['no']);
        array_push($c, $value['GRADE']);
        $totall += $value['no'];
    }
    foreach ($a as $value) {
        $z = ($value / $totall) * 100;
        array_push($b, $z);
    }
} else {
    echo "Error: " . mysqli_error($conn);
}

// Fetch student enrollment data for charts
$studentchart = "SELECT count(id) ,examenrollment.Exam_id FROM mcqsystem.examenrollment GROUP BY Exam_id ORDER BY Exam_id ASC";
$studentchartR = mysqli_query($conn, $studentchart);
if ($studentchartR) {
    while ($value = mysqli_fetch_assoc($studentchartR)) {
        array_push($d, $value['count(id)']);
    }
} else {
    echo "Error: " . mysqli_error($conn);
}

// Fetch exam data for charts
$examchart = "SELECT * FROM mcqsystem1.examenrollment INNER JOIN exams ON examenrollment.Exam_id=exams.id GROUP BY examenrollment.Exam_id ORDER BY examenrollment.Exam_id ASC";
$examchartR = mysqli_query($conn, $examchart);
if ($examchartR) {
    while ($value = mysqli_fetch_assoc($examchartR)) {
        array_push($e, $value['name']);
    }
} else {
    echo "Error: " . mysqli_error($conn);
}

// Fetch user data
$userdata = "SELECT * FROM mcqsystem1.user WHERE roleid='$_SESSION[roleid]'";
$userresult = mysqli_query($conn, $userdata);
if ($userresult) {
    $userdetails = mysqli_fetch_assoc($userresult);
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Tachers || dashboard</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<link rel="stylesheet" href="../assets/css/dashboard.css">
<style>
  .chartbox{
    width: 300px;
  }
</style>
<body>
<nav class="shadow-sm navbar navbar-expand-lg navbar-light bg-ligh bg-white rounded">
  <a class="navbar-brand" href="#">SCHOOL MCQ ONLINE APPLICATION</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <ul class="navbar-nav align-items-center">
        <li class="nav-item active">
          <a class="nav-link" href="dashboard.php">Dashboard <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Teacher_home.php">Exams</a>
        </li>
      </ul>
    <ul class="navbar-nav ml-auto">
    <div class="user-box dropdown">
						<a class="d-flex align-items-center nav-link  dropdown-toggle-nocaret" href="#" role="button" data-toggle="dropdown"  aria-expanded="false">
							<img src="../assets/u.jpg" width="50" class="rounded-circle" alt="user avatar">
							<div class="user-info ps-3 ml-1">
								<p class="user-name mb-0"><?php echo $userdetails['name']; ?></p>
								<p class="designattion mb-0">Teacher</p>
							</div>
						</a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated profile-dropdown">
                    <a class="dropdown-item text-danger" href="../logout.php">Logout</a>
             </div>
		  </div>
    </ul>
  </div>
</nav>
        
            <div class="side2 border" >
                <div class="title-main">
                    <h3 class="title">Dashboard</h3>
                </div>

                <div class=" content d-flex flex-column ">
                    <div class=" d-flex flex-row justify-content-around">
                            <div  class="w-50  mr-5 border">
                            <h4 class="text-center">Attendance Per Exam</h4>
                              <canvas id="myChart1" class="ml-5 mt-3"></canvas>
                            </div>
                            <div class="w-50 mr-5 border"  id="donutchart">
                            <h4 class="text-center">Average Result Grade Percentages(%)</h4>
                            <div class="chartbox ml-5">
                              <canvas id="myChart" height="40vh" width="80vw" class="ml-5 mt-3"></canvas>
                              </div>
                            </div>
                    </div>
                    <div class=" d-flex flex-row justify-content-around">
                        <div  class="w-50 mr-5 mt-3 border">
                            <h4 class="text-center">Average Top Result Student</h4>
                            <table class="table table-responsive-lg border ml-5 w-75 ">
                                <thead>
                                  <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Student_Name</th>
                                    <th scope="col">Exam_Name</th>
                                    <th scope="col">Result</th>
                                  </tr>
                                </thead>
                                <tbody id="top" >
                                <?php
                                require('../database_connection.php');

                                $sql = "SELECT users.name as name, exams.name as exam ,examenrollment.result as result FROM examenrollment JOIN users ON examenrollment.student_id = users.user_login_id JOIN exams ON examenrollment.Exam_id = exams.id WHERE exams.teacherid=$_SESSION[teacher_login_id] ORDER BY examenrollment.result DESC,examenrollment.Exam_id ASC;";
                                $result = mysqli_query($conn, $sql);

                                if (!$result) {
                                    // Handle query error
                                    echo "Error: " . mysqli_error($conn);
                                } else {
                                    if ($result->num_rows > 0) {
                                        $i = 1;
                                        while ($row = mysqli_fetch_array($result)) {
                                            echo "<tr class='content'>";
                                            echo "<td>" . $i . "</td>";
                                            echo "<td>" . $row['name'] . "</td>";
                                            echo "<td>" . $row['exam'] . "</td>";
                                            echo "<td>" . $row['result'] . "</td>";
                                            echo "</tr>";
                                            $i += 1;
                                        }
                                    }
                                }
                                ?>

                                </tbody>
                              </table>
                              <nav class="bar">
                                  <ul class="pagination pagination1  justify-content-center pagination-sm">
                                   </ul>
                              </nav>
                        </div>
                        <div  class="w-50 mr-5 mt-3 border">
                            <h4 class="text-center">Average Low Result Student</h4>
                            <table class="table table-responsive-lg border ml-5 w-75 ">
                                <thead>
                                  <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Student_Name</th>
                                    <th scope="col">Exam_Name</th>
                                    <th scope="col">Result</th>
                                  </tr>
                                </thead>
                                <tbody id="jar">
                                <?php 
                                require('../database_connection.php');
                                $sql = "SELECT users.name as name, exams.name as exam , examenrollment.result as result FROM examenrollment JOIN users ON examenrollment.student_id = users.user_login_id JOIN exams ON examenrollment.Exam_id = exams.id WHERE exams.teacherid=$_SESSION[teacher_login_id] ORDER BY examenrollment.result ASC,examenrollment.Exam_id ASC;";
                                $result=mysqli_query($conn,$sql);
                                if($result->num_rows > 0)
                                {
                                  $i=1;
                                  while ($row=mysqli_fetch_array($result))
                                  {
                                  echo"<tr class='content'>";
                                  echo"<td>".$i."</td>";
                                  echo"<td>".$row['name']."</td>"; 
                                  echo"<td>".$row['exam']."</td>"; 
                                  echo"<td>".$row['result']."</td>"; 
                                  echo"</tr>";
                                  $i+=1;
                                  }
                                 }
                                ?>
                                </tbody>
                              </table>
                              <nav class="bar">
                                  <ul class="pagination pagination2  justify-content-center pagination-sm">
                                   </ul>
                              </nav>
                        </div>
                   </div>

                </div>  
            </div>
                  

</body>
</html>
<script src="../assets/js/Teacher/dashboardPagination1.js"></script>
<script src="../assets/js/Teacher/dashboardPagination2.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const colors=[];
  const grade=<?php echo json_encode($c) ?>;
  for(var i=0;i<grade.length;i++){
    if(grade[i]=="A"){
      colors.push("rgb(0,255,0)");
    }
    if(grade[i]=="B"){
      colors.push("rgb(0,0,205)");
    }
    if(grade[i]=="C"){
      colors.push("rgb(173,216,230)");
    }
    if(grade[i]=="S"){
      colors.push("rgb(255,215,0)");
    }
    if(grade[i]=="W"){
      colors.push("rgb(255,0,0)");
    }

  }
  console.log(colors)

  const data = {
  labels: <?php echo json_encode($c) ?> ,
  datasets: [{
    label:  <?php echo json_encode($c) ?> ,
    data: <?php echo json_encode($b) ?>,
    backgroundColor:colors,
    hoverOffset: 4
  }]
};
const options={
 plugins:{
  legend:{
    display:true,
    position:'right',
    labels:{
      boxWidth:10,
      padding:20
    }
  },
  title:{
    display:false,
    text:'Average Result Grade Percentages(%)'
  }
  
 },


}
const config = {
  type: 'doughnut',
  data: data,
  options
  
};
</script>
<script>
    const myChart = new Chart(
      document.getElementById('myChart'),
      config
    );
 </script>



<script>
  const labels1 = <?php echo json_encode($e) ?>;
const data1 = {
  labels: labels1,
  datasets: [
    {
      label: 'Students',
      data: <?php echo json_encode($d) ?>,
      backgroundColor:['rgb(255, 99, 132)'],
      stack: 'Stack 0',
    }
   
  ]
};
  const config1 = {
  type: 'bar',
  data: data1,
  options: {
    plugins: {
      title: {
        display: false,
        text: 'Chart.js Bar Chart - Stacked'
      },
    },
    responsive: true,
    interaction: {
      intersect: false,
    },
    scales: {
      x: {
        stacked: true,
      },
      y: {
        stacked: true
      }
    }
  }
};


const myChart1 = new Chart(
      document.getElementById('myChart1'),
      config1
    );
</script>