<?php
// Start the session
session_start();

// Include database connection
require '../database_connection.php';

// User auth
if (!isset($_SESSION['roleid'])) {
    header("Location: ../index.php?error=You Need To Login First");
    exit();
}

// Exam auth
if (!isset($_SESSION["examid"])) {
    header("Location: ../index.php?error=You Need To Login First");
    exit();
}

// Get Exam Details Of Student
$sql = "SELECT * FROM `examenrollment` WHERE  student_id='{$_SESSION['roleid']}' AND Exam_id='{$_SESSION['examid']}'";
$result = mysqli_query($conn, $sql);

if ($result->num_rows > 0) {
    // Complete the already saved Exam
    if (isset($_POST['id']) && isset($_POST['name'])) {
        $question_an = $_POST['id'];
        $no = $_POST['name'];
        $c = array_combine($no, $question_an);

        foreach ($c as $qus => $ans) {
            $sql = "SELECT * FROM `question` WHERE examid='{$_SESSION['examid']}' AND questionNo='$qus'";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $questionID = $row['id'];

                $sqlnew = "SELECT * FROM `student-answers` WHERE  question_id='$questionID' AND student_id='{$_SESSION['roleid']}'";
                $resultnew = $conn->query($sqlnew);

                if ($resultnew->num_rows > 0) {
                    // Update existing record
                    $sql1 = "SELECT * FROM `answer` WHERE questionId='$questionID' AND optionvalue='$ans'";
                    $result1 = $conn->query($sql1);

                    if ($result1->num_rows > 0) {
                        $row1 = $result1->fetch_assoc();
                        $optionID = $row1['id'];
                        $answercheck = $row1['iscoorect'];
                        $mark = ($answercheck == 1) ? "Pass" : "Fail";

                        $sql3 = "UPDATE `student-answers` SET `option_id`='$optionID', `question_result`='$mark' WHERE student_id='{$_SESSION['roleid']}' AND question_id='$questionID'";
                        
                        if ($conn->query($sql3) === TRUE) {
                            echo "Record updated successfully";
                        } else {
                            echo "Error updating record: " . $conn->error;
                        }
                    }
                } else {
                    // Insert new record
                    $sql1 = "SELECT * FROM `answer` WHERE questionId='$questionID' AND optionvalue='$ans'";
                    $result1 = $conn->query($sql1);

                    if ($result1->num_rows > 0) {
                        $row1 = $result1->fetch_assoc();
                        $optionID = $row1['id'];
                        $answercheck = $row1['iscoorect'];
                        $mark = ($answercheck == 1) ? "Pass" : "Fail";

                        $sql3 = "INSERT INTO `student-answers`(`option_id`, `question_id`, `student_id`, `exam_id`, `question_result`) VALUES ('$optionID', '$questionID', '{$_SESSION['roleid']}', '{$_SESSION['examid']}', '$mark')";
                        
                        if ($conn->query($sql3) === TRUE) {
                            echo "New record created successfully";
                        } else {
                            echo "Error creating new record: " . $conn->error;
                        }
                    }
                }
            }
        }
    }

    // Calculate the result and save it into the database
    $noofquestion = "SELECT COUNT(id) as noofquestion FROM `question` WHERE examid='{$_SESSION['examid']}'";
    $noofquestionresult = mysqli_query($conn, $noofquestion);
    $data = $noofquestionresult->fetch_assoc();
    $no_of_question = (int)$data['noofquestion'];
    $markperquestion = (100 / $no_of_question);

    $count_correct = "SELECT COUNT(id) AS nocorrect FROM `student-answers` WHERE student_id='{$_SESSION['roleid']}' AND exam_id='{$_SESSION['examid']}' AND question_result='Pass'";
    $no_correct_result = mysqli_query($conn, $count_correct);
    $data1 = $no_correct_result->fetch_assoc();
    $no_of_correct = (int)$data1['nocorrect'];

    $exam_totall_marks = (int)($markperquestion * $no_of_correct);

    if ($exam_totall_marks > 85) {
        $grade = "A";
        $state = "Passed";
    } elseif ($exam_totall_marks > 65) {
        $grade = "B";
        $state = "Passed";
    } elseif ($exam_totall_marks > 45) {
        $grade = "C";
        $state = "Passed";
    } elseif ($exam_totall_marks > 25) {
        $grade = "S";
        $state = "Passed";
    } else {
        $grade = "W";
        $state = "Failed";
    }

    $updateExamResult = "UPDATE `examenrollment` SET `Examstatus`='attended',`result`='$exam_totall_marks',`GRADE`='$grade',`ExamResult`='$state' WHERE student_id='{$_SESSION['roleid']}' AND Exam_id='{$_SESSION['examid']}'";
    
    if ($conn->query($updateExamResult) === TRUE) {
        echo "Exam result updated successfully";
    } else {
        echo "Error updating exam result: " . $conn->error;
    }
} else {
    // Complete without saved Exam
    $sqlFisrt = "INSERT INTO `examenrollment`(`student_id`, `Exam_id`, `Examstatus`) VALUES ('{$_SESSION['roleid']}', '{$_SESSION['examid']}', 'attended')";

    if ($conn->query($sqlFisrt) === TRUE) {
        $question_an = $_POST['id'];
        $no = $_POST['name'];
        $c = array_combine($no, $question_an);

        foreach ($c as $qus => $ans) {
            $sql = "SELECT * FROM `question` WHERE examid='{$_SESSION['examid']}' AND questionNo='$qus'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $questionID = $row['id'];

                $sql1 = "SELECT * FROM `answer` WHERE questionId='$questionID' AND optionvalue='$ans'";
                $result1 = $conn->query($sql1);

                if ($result1->num_rows > 0) {
                    $row1 = $result1->fetch_assoc();
                    $optionID = $row1['id'];
                    $answercheck = $row1['iscoorect'];
                    $mark = ($answercheck == 1) ? "Pass" : "Fail";

                    $sql3 = "INSERT INTO `student-answers`(`option_id`, `question_id`, `student_id`, `exam_id`, `question_result`) VALUES ('$optionID', '$questionID', '{$_SESSION['roleid']}', '{$_SESSION['examid']}', '$mark')";

                    if ($conn->query($sql3) === TRUE) {
                        echo "New record created successfully";
                    } else {
                        echo "Error creating new record: " . $conn->error;
                    }
                }
            }
        }

        // Calculate the result and save it into the database
        $noofquestion = "SELECT COUNT(id) as noofquestion FROM `question` WHERE examid='{$_SESSION['examid']}'";
        $noofquestionresult = mysqli_query($conn, $noofquestion);
        $data = $noofquestionresult->fetch_assoc();
        $no_of_question = (int)$data['noofquestion'];
        $markperquestion = (100 / $no_of_question);

        $count_correct = "SELECT COUNT(id) AS nocorrect FROM `student-answers` WHERE student_id='{$_SESSION['roleid']}' AND exam_id='{$_SESSION['examid']}' AND question_result='Pass'";
        $no_correct_result = mysqli_query($conn, $count_correct);
        $data1 = $no_correct_result->fetch_assoc();
        $no_of_correct = (int)$data1['nocorrect'];

        $exam_totall_marks = ($markperquestion * $no_of_correct);

        if ($exam_totall_marks > 85) {
            $grade = "A";
            $state = "Passed";
        } elseif ($exam_totall_marks > 65) {
            $grade = "B";
            $state = "Passed";
        } elseif ($exam_totall_marks > 45) {
            $grade = "C";
            $state = "Passed";
        } elseif ($exam_totall_marks > 25) {
            $grade = "S";
            $state = "Passed";
        } else {
            $grade = "W";
            $state = "Failed";
        }

        $updateExamResult = "UPDATE `examenrollment` SET `Examstatus`='attended',`result`='$exam_totall_marks',`GRADE`='$grade',`ExamResult`='$state' WHERE student_id='{$_SESSION['roleid']}' AND Exam_id='{$_SESSION['examid']}'";
        
        if ($conn->query($updateExamResult) === TRUE) {
            echo "Exam result updated successfully";
        } else {
            echo "Error updating exam result: " . $conn->error;
        }
    } else {
        echo "Error creating new record: " . $conn->error;
    }
}
?>
