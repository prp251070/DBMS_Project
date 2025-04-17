<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher_login.php");
    exit();
}

// Fetch available exams
$sql = "SELECT id, title FROM exams WHERE teacher_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['teacher_id']);
$stmt->execute();
$result = $stmt->get_result();
$exams = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $exam_id = $_POST['exam_id'];
    $question = $_POST['question'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $option4 = $_POST['option4'];
    $correct_option = $_POST['correct_option'];

    $sql = "INSERT INTO questions (exam_id, question, option1, option2, option3, option4, correct_option) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssi", $exam_id, $question, $option1, $option2, $option3, $option4, $correct_option);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success text-center'>✨ Question added successfully! ✨</div>";
    } else {
        echo "<div class='alert alert-danger text-center'>❌ Error: " . $conn->error . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Question</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f2f6fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .form-wrapper {
            max-width: 600px;
            margin: 60px auto;
            padding: 35px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
        }

        .form-wrapper h2 {
            font-weight: 600;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }

        label {
            font-weight: 500;
            margin-top: 12px;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.15rem rgba(0, 123, 255, 0.25);
        }

        .btn-submit {
            margin-top: 25px;
            font-weight: 600;
            background-color: #007bff;
            border: none;
            color: white;
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .btn-submit:hover {
            background-color: #0056b3;
        }

        .back-link {
            text-align: center;
            display: block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 576px) {
            .form-wrapper {
                padding: 25px 20px;
            }
        }
    </style>
</head>
<body>

    <div class="form-wrapper">
        <h2>Add a Question</h2>
        <form method="post">
            <label for="exam_id" class="form-label">Select Exam</label>
            <select name="exam_id" id="exam_id" class="form-select" required>
                <option value="">-- Choose Exam --</option>
                <?php foreach ($exams as $exam) { ?>
                    <option value="<?php echo $exam['id']; ?>"><?php echo $exam['title']; ?></option>
                <?php } ?>
            </select>

            <label for="question" class="form-label">Question</label>
            <input type="text" name="question" id="question" class="form-control" required>

            <label for="option1" class="form-label">Option 1</label>
            <input type="text" name="option1" id="option1" class="form-control" required>

            <label for="option2" class="form-label">Option 2</label>
            <input type="text" name="option2" id="option2" class="form-control" required>

            <label for="option3" class="form-label">Option 3</label>
            <input type="text" name="option3" id="option3" class="form-control" required>

            <label for="option4" class="form-label">Option 4</label>
            <input type="text" name="option4" id="option4" class="form-control" required>

            <label for="correct_option" class="form-label">Correct Option (1-4)</label>
            <input type="number" name="correct_option" id="correct_option" class="form-control" min="1" max="4" required>

            <button type="submit" class="btn btn-submit">Add Question</button>
        </form>

        <a href="teacher_dashboard.php" class="back-link">⬅ Back to Dashboard</a>
    </div>

</body>
</html>
