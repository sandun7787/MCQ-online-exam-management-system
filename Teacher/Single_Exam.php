<?php
session_start();
//include database connection
require '../database_connection.php';
// user auth
if (!isset($_SESSION['teacher_login_id']))
{
  header("Location: ../index.php?error=You Need To Login First");
  exit();
}
$question=array();
$exam_main=0;
//get if already have exam 
if(isset($_GET['id'])){
    $sql="SELECT * FROM `exams` WHERE id='$_GET[id]'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $examdetails = $result->fetch_assoc();  
        $exam_main=$examdetails['id'];
    } 

    $sql="select * from question where question.examid=$_GET[id]"; 
    $result1=mysqli_query($conn,$sql);
    if(mysqli_num_rows($result1)>0){
        while($row1=mysqli_fetch_assoc($result1))
        {
            $temp=array();
            array_push($temp,$row1['Question']);
            $ans = ""; // Initialize $ans before the loop
            $sql="select * from answer where questionId='$row1[id]'"; 
            $result=mysqli_query($conn,$sql);
            while($row=mysqli_fetch_assoc($result))
            {
                if($row['iscoorect']==1){
                    $ans=$row['optionvalue'];
                }
                array_push($temp,$row['optionvalue']);
            }
            array_push($temp,$ans);
            array_push($question,$temp);
        }
    }
}

//delete question
if(isset($_GET['delete'])){

    $id=$_GET['delete'];
    $id1=$_GET['id'];
    $sql1 ="DELETE FROM `question` WHERE id='$id'";
    $sql ="DELETE FROM `answer` WHERE questionId='$id'";
    if ($conn->query($sql1) === TRUE && $conn->query($sql) === TRUE) {
        header("Location: single_Exam.php?id=$id1");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$userdata = "SELECT * FROM exam.users WHERE id='" . $_SESSION['teacher_login_id'] . "'";
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
<title>Tachers || Single Exam page</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
<link rel="stylesheet" href="../assets/css/Teacher/single_exam.css">
<link rel="stylesheet" type="text/css" href="../assets/css/jquery.datetimepicker.css" >
<script src="../assets/js/jquery.datetimepicker.full.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" integrity="sha512-c42qTSw/wPZ3/5LBzD+Bw5f7bSF2oxou6wEb+I/lqeaKV5FDIfMvvRp772y4jcJLKuGUOpbJMdg/BTl50fJYAw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.9/sweetalert2.min.css" integrity="sha512-cyIcYOviYhF0bHIhzXWJQ/7xnaBuIIOecYoPZBgJHQKFPo+TOBA+BY1EnTpmM8yKDU4ZdI3UGccNGCEUdfbBqw==" crossorigin="anonymous" referrerpolicy="no-referrer" /><script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.9/sweetalert2.all.min.js" integrity="sha512-IZ95TbsPTDl3eT5GwqTJH/14xZ2feLEGJRbII6bRKtE/HC6x3N4cHye7yyikadgAsuiddCY2+6gMntpVHL1gHw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<body>
        <nav class="shadow-sm navbar navbar-expand-lg navbar-light bg-ligh bg-white rounded">
          <a class="navbar-brand" href="#">SCHOOL MCQ ONLINE APPLICATION</a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <ul class="navbar-nav align-items-center">
                <li class="nav-item ">
                  <a class="nav-link" href="Dashboard.php">Dashboard </a>
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
        <div class="side2 border" style="height:631.2px;">
          <?php if (isset($_GET['success'])) {
            echo"<script>Swal.fire({
              title: 'Exam',
              icon: 'success',
              text: '$_GET[success]',
              confirmButtonText: 'OK',
              
              }).then((result) => {
              if (result.isConfirmed) {
                window.location.assign('single_Exam.php?id=$exam_main')
              } 
              })</script>";
            }?>
          <div class="examname">
            <a href="Teacher_home.php"><i class="fas fa-chevron-left fa-2x"></i></a>
            <?php
              if(isset($examdetails['name'])){
                echo' <input type="text" class="form-control exam" name="exam" id="examName" value="'.$examdetails['name'].'" required>';
                echo' <input type="hidden" class="form-control exam" id="examID" name="examid" value="'.$examdetails['id'].'">';
              }
              else{
                echo' <input type="text" class="form-control exam" id="examName" name="exam"  placeholder="Exam name" required>';
              }
            ?> 
          </div>
          <div class="main">   
                <div class="form-group pull-right control">
                    <label for="Question" id="Question">Question List</label>
                    <button type="button" class="btn btn-danger p-2 ml-auto" data-toggle="modal" data-target="#myModal1">Add Question</button>
                </div>   
                <table id="tbl" class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width:500px;">Question</th>
                      <th style="width:600px;">Answers</th>
                      <th style="width:80px;">Action</th>
                    </tr>
                  </thead>
                  <tbody id="jar">
                 
                  </tbody>
                </table>

                <div class="container">
                   <div class="row">
                      <div class="col-sm">
                      <?php
            if (isset($examdetails['duration'])) {
                echo '<input type="text" class="form-control" name="datetime" id="datetimepicker" value="' . $examdetails['dateandtime'] . '" required data-toggle="popover" data-trigger="manual" data-content="Please select a future date and time." data-placement="bottom">';
            } else {
                echo '<input type="text" class="form-control" name="datetime" id="datetimepicker" placeholder="Exam Date Time" required data-toggle="popover" data-trigger="manual" data-content="Please select a future date and time." data-placement="bottom">';
            }
            ?>
        </div>
        <div class="col-sm">
        <?php
if (isset($examdetails['duration'])) {
    echo '<div class="input-group">';
    echo '<input type="number" class="form-control" id="durationTime" name="duration" value="' . $examdetails['duration'] . '" min="1" required>';
    echo '<div class="input-group-append">';
    echo '<span class="input-group-text">minutes</span>';
    echo '</div>';
    echo '</div>';
} else {
    echo '<div class="input-group">';
    echo '<input type="number" class="form-control" id="durationTime" name="duration" placeholder="Exam Duration" min="1" required>';
    echo '<div class="input-group-append">';
    echo '<span class="input-group-text">minutes</span>';
    echo '</div>';
    echo '</div>';
}
?>

      </div>
                      <div class="col-sm">
                        <button type="submit" class="btn Publish" id="btn-pub" name="publish">Publish Paper</button>
                      </div>
                      <div class="col-sm">
                        <input type="submit" class="btn btn-success" id="btn-save" name="save" value="Save">
                      </div>
                     
                    </div>
                   </div>
                </div>
             
        </div>

                  <!-- insert model -->
                  <div class="modal" id="myModal1">
                    <div class="modal-dialog">
                      <div class="modal-content">
                      
                        <!-- Modal Header -->
                        <div class="modal-header">
                          <h4 class="modal-title">ADD NEW QUESTION</h4>
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        
                        <!-- Modal body -->
                        <div class="modal-body">
                            <input type="text" id="add-question" class="form-control" placeholder="Question Name">
                            <br>
                            <label for="a"> Answers list</label>
                            <div class="d-flex flex-column mb-3">
                              <div class="p-2 group d-flex flex-row"><input  value="1" name="add-choice" id="add-r1"  class=" mr-2" type="radio"><input type="text" id="add-ans1"  class="form-control "  placeholder="Answer 1"></div>
                              <div class="p-2 group d-flex flex-row"><input  value="2" name="add-choice" id="add-r2"  class=" mr-2" type="radio"><input type="text" id="add-ans2" class="form-control "  placeholder="Answer 2" ></div>
                              <div class="p-2 group d-flex flex-row"><input  value="3" name="add-choice" id="add-r3"   class=" mr-2" type="radio"><input type="text" id="add-ans3" class="form-control "  placeholder="Answer 3" ></div>
                              <div class="p-2 group d-flex flex-row"><input  value="4" name="add-choice" id="add-r4"   class=" mr-2" type="radio"><input type="text" id="add-ans4" class="form-control "  placeholder="Answer 4"></div>
                            </div>
                        </div> 
                        <!-- Modal footer -->
                        <div class="modal-footer">
                          <button type="button" class="btn btn-warning btn-add" data-dismiss="modal" >Add</button>
                          <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                        </div>
                        
                      </div>
                    </div>
                  </div>
                </div>
              <!-- Edit model -->
                <div class="modal" id="myModal">
                    <div class="modal-dialog">
                      <div class="modal-content">
                      
                        <!-- Modal Header -->
                        <div class="modal-header">
                          <h4 class="modal-title">UPDATE QUESTION</h4>
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        
                        <!-- Modal body -->
                        <div class="modal-body">
                            <input type="text" id="question" class="form-control" placeholder="Question Name">
                            <br>
                            <label for="a"> Answers list</label>
                            <div class="d-flex flex-column mb-3">
                              <div class="p-2 group d-flex flex-row"><input  value="1" name="choice" id="r1"  class=" mr-2" type="radio"><input type="text" id="ans1"  class="form-control "  placeholder="Answer 1"></div>
                              <div class="p-2 group d-flex flex-row"><input  value="2" name="choice" id="r2"  class=" mr-2" type="radio"><input type="text" id="ans2" class="form-control "  placeholder="Answer 2" ></div>
                              <div class="p-2 group d-flex flex-row"><input  value="3" name="choice" id="r3"   class=" mr-2" type="radio"><input type="text" id="ans3" class="form-control "  placeholder="Answer 3" ></div>
                              <div class="p-2 group d-flex flex-row"><input  value="4" name="choice" id="r4"   class=" mr-2" type="radio"><input type="text" id="ans4" class="form-control "  placeholder="Answer 4"></div>
                            </div>
                        </div> 
                        <!-- Modal footer -->
                        <div class="modal-footer">
                          <button type="button"  class="btn btn-warning btn-up" data-dismiss="modal" onclick="btn-save()" >Save</button>
                          <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                        </div>
                        
                      </div>
                    </div>
                  </div>
                </div>
</body>
</html>
<script>
const question=<?php echo json_encode($question);?>;
const main_id=<?php echo $exam_main;?>;

questionans()
let dataId ;

//load question
function questionans(){
  document.getElementById("jar").innerHTML="";
    for(var i=0;i<question.length;i++)
    {
        var tr=document.createElement('tr');
        var td1 = tr.appendChild(document.createElement('td'));
        var td2 = tr.appendChild(document.createElement('td'));
        var td3 = tr.appendChild(document.createElement('td'));
        anwser='';
        for(var j=0;j<5;j++){
            if(j==0){
                td1.innerHTML=question[i][j];
            }
            if(j>=1 && j<4){
                anwser+=question[i][j]+',';
             }
            if(j==4){
                anwser+=question[i][j];
            }
         
            td2.innerHTML=anwser
        }
        td3.innerHTML='<button type="button" id="edit" data-id="'+i+'" class="btn btn-sm btn-primary mr-2 edit" ><i class="fas fa-edit"></i></button><button type="button" id="delete" data-id="'+i+'" class="btn btn-danger btn-sm delete" ><i class="fas fa-trash-alt"></i></button>'
      
        document.getElementById("jar").append(tr); 
    }
    
}

//edit part
$("#tbl").on('click', '.edit', function () {
    dataId = $(this).attr("data-id");
    console.log(dataId);
    document.getElementById("question").value=question[dataId][0];
    document.getElementById("ans1").value=question[dataId][1];
    document.getElementById("r1").value=question[dataId][1];
    document.getElementById("ans2").value=question[dataId][2];
    document.getElementById("r2").value=question[dataId][2];
    document.getElementById("ans3").value=question[dataId][3];
    document.getElementById("r3").value=question[dataId][3];
    document.getElementById("ans4").value=question[dataId][4];
    document.getElementById("r4").value=question[dataId][4];

  

   const nodeList = document.querySelectorAll("[name='choice']");
   console.log(nodeList)
   for(var i=0; i<nodeList.length; i++)
  {

    if(question[dataId][5]==nodeList[i].value)
    {
      nodeList[i].checked=true;
    }
  }

    $('#myModal').modal('show');
  });

  $(function () 
  {
  $('.btn-up').click(function(){
    var question1=document.getElementById('question').value;
    var ans1=document.getElementById('ans1').value;
    var ans2=document.getElementById('ans2').value;
    var ans3=document.getElementById('ans3').value;
    var ans4=document.getElementById('ans4').value;
    var ele = document.getElementsByName('choice');

    for(i = 0; i < ele.length; i++) {
        if(ele[i].checked)
           question[dataId][5]=ele[i].value;
    }

    if(question1==''){
      Swal.fire(
          'Update Question',
          'fill out the question field!',
          'error'
      )
      return false
    }
    if(ans1==''){
      Swal.fire(
          'Update Question',
          'fill out the Answer 1 field!',
          'error'
      )
      return false
    }
    if(ans2==''){
      Swal.fire(
          'Update Question',
          'fill out the Answer 2 field!',
          'error'
      )
    
      return false
    }
    if(ans3==''){
      Swal.fire(
          'Update Question',
          'fill out the Answer 3 field!',
          'error'
      )
      return false
    }
    if(ans4==''){
      Swal.fire(
          'Update Question',
          'fill out the Answer 4 field!',
          'error'
      )
      return false
    }

    question[dataId][0]=question1;
    question[dataId][1]=ans1;
    question[dataId][2]=ans2;
    question[dataId][3]=ans3;
    question[dataId][4]=ans4;
  
    questionans()

  })
})

//add question
$(function () 
  {
  $('.btn-add').click(function(){
    var ques=document.getElementById("add-question").value;
    var ans1=document.getElementById("add-ans1").value;
    var ans2=document.getElementById("add-ans2").value;
    var ans3=document.getElementById("add-ans3").value;
    var ans4=document.getElementById("add-ans4").value;
    var ele = document.getElementsByName('add-choice');
    var anwser='';

    for(i = 0; i < ele.length; i++) {
        if(ele[i].checked)
           anwser=ele[i].value;
    }
    if(ques==''){
      Swal.fire(
          'New Question',
          'fill out the question field!',
          'error'
      )
      return false
    }
    if(ans1==''){
      Swal.fire(
          'New Question',
          'fill out the Answer 1 field!',
          'error'
      )
      return false
    }
    if(ans2==''){
      Swal.fire(
          'New Question',
          'fill out the Answer 2 field!',
          'error'
      )
      return false
    }
    if(ans3==''){
      Swal.fire(
          'New Question',
          'fill out the Answer 3 field!',
          'error'
      )
      return false
    }
    if(ans4==''){
      Swal.fire(
          'New Question',
          'fill out the Answer 4 field!',
          'error'
      )
      return false
    }

    if(anwser==''){
      Swal.fire(
          'New Question',
          'select the correct answer!',
          'error'
      )
      return false
    }
    let check=parseInt(anwser);
    switch (check) 
    {
      case 1:
       var correctans = ans1;
        break;
      case 2:
        var correctans = ans2;
        break;
      case 3:
        var correctans = ans3;
        break;
      case 4:
        var correctans = ans4;
        break;
    }

    const temp=[ques,ans1,ans2,ans3,ans4,correctans]
    question.push(temp)
    document.getElementById("add-question").value=''
    document.getElementById("add-ans1").value=''
    document.getElementById("add-ans2").value=''
    document.getElementById("add-ans3").value=''
    document.getElementById("add-ans4").value=''

    $("[type=radio]").prop("checked",false);
    $(".Correct").css("display", "none");
    questionans()

  })
})

// save
$(function () 
  {
  $('#btn-save').click(function(){
    var exam=document.getElementById("examName").value;
    var examdatetime=document.getElementById("datetimepicker").value;
    var duration=document.getElementById("durationTime").value;
    if(exam==''){
      Swal.fire(
          'Exam',
          'Please fil the Exam name!',
          'error'
      )
      return false
    }
    if(examdatetime==''){
      Swal.fire(
          'Exam',
          'Please select exam start datetime!',
          'error'
      )
      return false
    }
    if(duration==''){
      Swal.fire(
          'Exam',
          'Please enter duration!',
          'error'
      )
      return false
    }
    
    $.ajax({  
        url:'save.php',  
        method:"POST",  
        data:{main_id:main_id,question:question,exam:exam,examdatetime:examdatetime,duration:duration}, 
        type:'JSON', 
        success:function(data){  
          location.assign(data);

        }  
    })  
  })
})




//publish
$(function () 
  {
  $('#btn-pub').click(function(){
    var exam=document.getElementById("examName").value;
    var examdatetime=document.getElementById("datetimepicker").value;
    var duration=document.getElementById("durationTime").value;
    if(exam==''){
      Swal.fire(
          'Exam',
          'Please fil the Exam name!',
          'error'
      )
      return false
    }
    if(examdatetime==''){
      Swal.fire(
          'Exam',
          'Please select exam start datetime!',
          'error'
      )
      return false
    }
    if(duration==''){
      Swal.fire(
          'Exam',
          'Please enter duration!',
          'error'
      )
      return false
    }
    if (question.length === 0){
      Swal.fire(
          'Exam',
          'Please add atleast one question!',
          'error'
      )
    
      return false
    }

    
    $.ajax({  
        url:'publish.php',  
        method:"POST",  
        data:{main_id:main_id,question:question,exam:exam,examdatetime:examdatetime,duration:duration}, 
        type:'JSON', 
        success:function(data){  
          location.assign(data);

        }  
    })  
  })
})
 // Get current date and time
 var now = new Date();
    var year = now.getFullYear();
    var month = now.getMonth() + 1; // Month starts from 0
    var day = now.getDate();
    var hour = now.getHours();
    var minute = now.getMinutes();

    // Set minimum date and time for datetimepicker
    $('#datetimepicker').datetimepicker({
        minDate: new Date(year, month - 1, day, hour, minute),
        format: 'Y-m-d H:i', // Adjust format as needed
        timepicker: true,
        onChangeDateTime: function (selectedDate) {
            var currentDate = new Date();
            if (selectedDate < currentDate) {
                // If selected date is in the past, show popover
                $('#datetimepicker').popover('show');
                $('#datetimepicker').val('');
            } else {
                // If selected date is in the future, hide popover
                $('#datetimepicker').popover('hide');
            }
        }
    });

    // Set minimum hour if current date is selected
    $('#datetimepicker').on('change', function () {
        var selectedDate = $('#datetimepicker').val();
        var currentDate = $.datepicker.formatDate('yy-mm-dd', new Date());
        if (selectedDate.substring(0, 10) == currentDate) {
            $('#datetimepicker').datetimepicker({ minTime: hour + ':' + minute });
        } else {
            $('#datetimepicker').datetimepicker({ minTime: false });
        }
    });

    // Initialize popover
    $(function () {
        $('[data-toggle="popover"]').popover({
            content: function () {
                return $(this).data('content');
            }
        });
    });



//delete row
  $("#tbl").on('click', '.delete', function () {
  var id = $(this).attr("data-id");
  question.splice(id,1);
  $(this).closest('tr').remove();
});



if (document.querySelector('input[name="main"]')) {
    document.querySelectorAll('input[name="main"]').forEach((elem) => {
      elem.addEventListener("change", function(event) {
        $(".Correct").css("display", "none");
        $(this).next("span").css("display", "block");
      });
    });
  }
  
jQuery('#datetimepicker').datetimepicker({
  minDate: new Date(),
});
</script>