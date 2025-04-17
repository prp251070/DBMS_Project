<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher_login.php");
    exit();
}

// Handle POST request for exam deletion
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['exam_id'])) {
    $exam_id = $_POST['exam_id'];

    // Verify that the exam belongs to the logged-in teacher
    $stmt = $conn->prepare("SELECT id FROM exams WHERE id = ? AND teacher_id = ?");
    $stmt->bind_param("ii", $exam_id, $_SESSION['teacher_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        // Proceed to delete the exam (CASCADE will handle related questions and scores)
        $delete = $conn->prepare("DELETE FROM exams WHERE id = ?");
        $delete->bind_param("i", $exam_id);

        if ($delete->execute()) {
            $_SESSION['message'] = "✅ Subject deleted successfully!";
        } else {
            $_SESSION['message'] = "❌ Error deleting the subject.";
        }

        $delete->close();
    } else {
        $_SESSION['message'] = "⚠️ Unauthorized attempt or subject not found.";
    }

    $stmt->close();
}

// Redirect back to manage_question.php after deletion
header("Location: manage_questions.php");
exit();
?>
