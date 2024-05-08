<?php 
session_start();

require '../database_connection.php';

//teacher auth
if (!isset($_SESSION['roleid']))
{
  header("Location: ../index.php?error=You Need To Login First");
  exit();
}
$userdata="SELECT * FROM mcqsystem1.user where roleid='$_SESSION[roleid]'";
$userresult=mysqli_query($conn,$userdata);
$userdetails=mysqli_fetch_assoc($userresult);
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Home || Page</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
<link rel="stylesheet" href="../assets/css/Teacher/teachers_home.css">
</head>
<body>
<nav class="shadow-sm navbar navbar-expand-lg navbar-light bg-ligh bg-white rounded">
  <a class="navbar-brand" href="#">SCHOOL MCQ ONLINE APPLICATION</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <ul class="navbar-nav align-items-center">
        <li class="nav-item ">
          <a class="nav-link" href="dashboard.php">Dashboard </a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="student_home.php">Exams<span class="sr-only">(current)</span></a>
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
     
        <div class="side2 border" style="height: 631.2px;"> 
            <div class="main">   
              <form action="" method="POST">
                <div class="form-group pull-right search">
                    <input type="text" class="search form-control datasearch" placeholder="Search..." name="searchvalue" required>
                    <button type="submit" class="btn btn-primary btnsearch" name="search">Search</button>
                    <a href="Teacher_home.php" class="btn btn-warning ml-3">Reset</a>
                    <a href="single_Exam.php" class="btn btn-success ml-3">New Exam</a>

              </form> 
                      
                </div>
                <table id="example" class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Exam</th>
                      <th>Laste Updated</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody id="jar">
                  <?php 
                  require('../database_connection.php');
                  $sql = "SELECT * FROM `exams` WHERE teacherid='$_SESSION[roleid]'";
                  if(isset($_POST['search'])){
                    $sql="SELECT * FROM `exams` WHERE teacherid='$_SESSION[roleid]' and name LIKE '%$_POST[searchvalue]%'";
                  }
                  $result=mysqli_query($conn,$sql);
                  if($result->num_rows > 0)
                  {
                     
                      while ($row=mysqli_fetch_array($result))
                      {
                       echo"<tr class='content'>";
                       echo"<td>".$row['name']."</td>";
                       echo"<td>".$row['updatedate']."</td>"; 
                       if($row['status']=='draft'){
                        echo"<td>".'<a href="single_Exam.php?id=' . $row['id'] . '">'.$row['status']."</td>";
                       }else{
                        echo"<td>".'<a href="monitorexam.php?id=' . $row['id'] . '">'.$row['status']."</td>";
                       }
                       echo"</tr>";
                      }
                      echo" </tbody>
                         </table>
                         <nav class='bar'>
                           <ul class='pagination justify-content-center pagination-sm'>
                           </ul>
                       </nav> ";
                  }
                  else{
                    echo'<tr><td colspan="4" class="text-center">NO DATA AVAILABLE</td></tr>';
                    echo" </tbody></table>";
                  }
                    ?>
                </div>   
        </div>
</body>
</html>
<script src="../assets/js/Teacher/TeacherHomePargination.js"></script>
<script>
  $(document).ready(function() {

$('#example tr').click(function() {
    var href = $(this).find("a").attr("href");
    if(href) {
        window.location = href;
    }
});

});
</script>