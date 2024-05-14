<<?php  
session_start();
if (!isset($_SESSION['student_login_id']) || !isset($_SESSION["examid"])) {
    header("Location: ../index.php?error=You Need To Login First");
    exit();
}

$connect = mysqli_connect("localhost", "root", "", "exam");  
$record_per_page = 1;  
$page = isset($_POST["page"]) ? $_POST["page"] : 1;  
$start_from = ($page - 1) * $record_per_page;  

$query = "SELECT * FROM `question` WHERE examid='$_SESSION[examid]' LIMIT $start_from, $record_per_page";  
$result = mysqli_query($connect, $query);  

$output = '';

while ($row = mysqli_fetch_array($result)) { 
    $output .= '<label for="">Q.'.$row['questionNo'].')'.$row['Question'] .'</label>';
    $output .= '<input type="hidden" id="hiddenNo" name="hidenQno" value="'.$row['questionNo'].'">';
    $qustionsoption = "SELECT * FROM `answer` WHERE questionId='$row[id]'";
    $optionresult = $connect->query($qustionsoption);

    if ($optionresult->num_rows > 0) {
        $output .= '<div class="d-flex flex-column mb-3 border w-25 pl-3 pt-2">';
        while ($ans = $optionresult->fetch_assoc()) {
            $output .= '<div class="d-flex flex-row"><input id="quesans" name="choice" data-id="'.$row['questionNo']. '" data-value="'.$ans['optionvalue']. '" value="'.$ans['optionvalue']. '" class="radio1" type="radio"><label for="">'.$ans['optionvalue'].'</label></div>';
        }
        $output .= '</div>';
    }
}

$page_query = "SELECT * FROM `question` WHERE examid='$_SESSION[examid]' ";  
$page_result = mysqli_query($connect, $page_query);  
$total_records = mysqli_num_rows($page_result);  
$total_pages = ceil($total_records / $record_per_page); 

if ($total_pages > 1) {
    $output .= '<div class="d-flex flex-row justify-content-between w-25">
                    <button type="button" class="btn btn-secondary Pervious" id='.$total_pages.' page='.$page.'>Pervious</button>
                    <label for="" id="currentquestion"> Question '.$page.'</label>
                    <button type="button" class="btn btn-secondary next" id='.$total_pages.' page='.$page.'>Next</button>
                </div>';
} 

$output .= '<div><nav aria-label="..."><ul class="pagination pagination-sm justify-content-center">';  

for ($i = 1; $i <= $total_pages; $i++) {  
    $output .= '<li class="page-item"><a class="page-link pagination_link" id='.$i.'>'.$i.'</a></li>';
}  

$output .= '</ul></nav></div>';  
echo $output;  
?>
