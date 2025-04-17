<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php';
session_start();

header('Content-Type: application/json'); // Tell JS we're returning JSON

$student_id = $_SESSION['student_id'] ?? null;
$exam_id = $_POST['exam_id'] ?? null;
if (!$student_id || !$exam_id) {
    echo json_encode([
        "status" => "error",
        "message" => "Student ID or Exam ID missing"
    ]);
    exit;
}

$score = 0;

// Fetch correct answers from database
// Fetch only questions of the submitted exam
$sql = "SELECT * FROM questions WHERE exam_id = $exam_id";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $qid = $row['id']; 
    $correct_option = $row['correct_option']; 

    if (isset($_POST["q$qid"]) && $_POST["q$qid"] == $correct_option) {
        $score++;
    }
}
// Check if score already exists for this student and exam
$check_sql = "SELECT * FROM student_exam_scores WHERE student_id = $student_id AND exam_id = $exam_id";
$check_result = $conn->query($check_sql);

if ($check_result->num_rows > 0) {
    // Update existing score
    $update_sql = "UPDATE student_exam_scores SET score = $score WHERE student_id = $student_id AND exam_id = $exam_id";
    $conn->query($update_sql);
} else {
    // Insert new score
    $insert_sql = "INSERT INTO student_exam_scores (student_id, exam_id, score) VALUES ($student_id, $exam_id, $score)";
    $conn->query($insert_sql);
}
header('Content-Type: application/json'); // Let the browser know you're sending JSON
echo json_encode([
    "status" => "success",
    "message" => "Exam submitted!",
    "score" => $score
]);

$conn->close();
?>