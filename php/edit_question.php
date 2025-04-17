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

// Fetch question data
$sql = "SELECT * FROM questions WHERE id = ?";
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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question_text = $_POST['question'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $option4 = $_POST['option4'];
    $correct_option = $_POST['correct_option'];

    $sql = "UPDATE questions SET question=?, option1=?, option2=?, option3=?, option4=?, correct_option=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $question_text, $option1, $option2, $option3, $option4, $correct_option, $question_id);

    if ($stmt->execute()) {
        echo "Question updated successfully!";
        header("Location: manage_questions.php?exam_id=" . $question['exam_id']);
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Question</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f6fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card-custom {
            max-width: 600px;
            margin: 60px auto;
            padding: 30px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .card-custom h2 {
            color: #2c3e50;
        }

        .form-label {
            font-weight: 600;
            color: #333;
        }

        .form-control {
            border-radius: 8px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: none;
        }

        .btn-primary {
            background-color: #007bff;
            border-radius: 8px;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            border-radius: 8px;
        }
    </style>
</head>
<body>

    <div class="card card-custom">
        <h2 class="text-center mb-4">Edit Question</h2>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Question:</label>
                <input type="text" name="question" class="form-control" value="<?php echo htmlspecialchars($question['question']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Option 1:</label>
                <input type="text" name="option1" class="form-control" value="<?php echo htmlspecialchars($question['option1']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Option 2:</label>
                <input type="text" name="option2" class="form-control" value="<?php echo htmlspecialchars($question['option2']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Option 3:</label>
                <input type="text" name="option3" class="form-control" value="<?php echo htmlspecialchars($question['option3']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Option 4:</label>
                <input type="text" name="option4" class="form-control" value="<?php echo htmlspecialchars($question['option4']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Correct Option (1-4):</label>
                <input type="number" name="correct_option" class="form-control" value="<?php echo $question['correct_option']; ?>" min="1" max="4" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">💾 Update Question</button>
        </form>

        <div class="text-center mt-3">
            <a href="manage_questions.php?exam_id=<?php echo $question['exam_id']; ?>" class="btn btn-secondary">⬅️ Back to Questions</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
