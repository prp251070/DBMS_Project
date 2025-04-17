<?php
include 'db_connect.php';

// Get the exam_id from the query parameter
$exam_id = isset($_GET['exam_id']) ? $_GET['exam_id'] : 0;

// If exam_id is not valid, return an empty response
if ($exam_id <= 0) {
    echo json_encode([]);
    exit;
}

// Modify SQL to fetch only questions related to the selected exam
$sql = "SELECT * FROM questions WHERE exam_id = $exam_id";
$result = $conn->query($sql);

$questions = [];
while ($row = $result->fetch_assoc()) {
    $questions[] = $row;
}

// Return the questions as a JSON response
echo json_encode($questions);
?>
