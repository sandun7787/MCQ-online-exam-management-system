<?php 
session_start();
if (isset($_SESSION['teacher_login_id']))
{
  header("Location: Teacher/Teacher_home.php");
  exit();
}
if(isset($_SESSION['student_login_id']))
{
  header("Location: student/Student_home.php");
  exit();
}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Login Form</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="assets/css/login_page_style.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" integrity="sha512-c42qTSw/wPZ3/5LBzD+Bw5f7bSF2oxou6wEb+I/lqeaKV5FDIfMvvRp772y4jcJLKuGUOpbJMdg/BTl50fJYAw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.9/sweetalert2.min.css" integrity="sha512-cyIcYOviYhF0bHIhzXWJQ/7xnaBuIIOecYoPZBgJHQKFPo+TOBA+BY1EnTpmM8yKDU4ZdI3UGccNGCEUdfbBqw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.9/sweetalert2.all.min.js" integrity="sha512-IZ95TbsPTDl3eT5GwqTJH/14xZ2feLEGJRbII6bRKtE/HC6x3N4cHye7yyikadgAsuiddCY2+6gMntpVHL1gHw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
<div class="shadow login-form border bg-white rounded">
    <?php if (isset($_GET['error'])) { ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo $_GET['error']; ?>
      </div>
      <?php }?>
    <form action="Sign.php" method="post" onsubmit="return validate()">   
        <div class="form-group">
            <label for="floatingInput">Email Address</label>
            <input type="text" class="form-control" placeholder="Email Address" id="email" name="email">
            <i class="fas fa-check-circle er"></i>
			<i class="fas fa-exclamation-circle ew"></i>
			<small class="e-error" style="display: none;color: red;">You have entered an invalid email address!</small>
        </div>
        <div class="form-group">
            <label for="floatingInput">Password</label>
            <input type="password" class="form-control" placeholder="Password" id="pwd" name="password">
            <i class="fas fa-check-circle pr"></i>
			    <i class="fas fa-exclamation-circle pw"></i>
			    <small class="p-error" style="display: none;color: red;">password cannot be blank</small>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-block" name="Sign">Sign in</button>
        </div>     
    </form>
</div>
</body>
</html>
<script src="assets/js/loginpage.js"></script>