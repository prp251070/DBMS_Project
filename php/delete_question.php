<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher_login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit();
}

$question_id = $_GET['id'];

// Get exam ID before deleting (to redirect correctly)
$sql = "SELECT exam_id FROM questions WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $question_id);
$stmt->execute();
$result = $stmt->get_result();
$question = $result->fetch_assoc();
$stmt->close();

if (!$question) {
    echo "Question not found.";
    exit();
}

// Delete question
$sql = "DELETE FROM questions WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $question_id);

if ($stmt->execute()) {
    header("Location: manage_questions.php?exam_id=" . $question['exam_id']);
    exit();
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
?>
