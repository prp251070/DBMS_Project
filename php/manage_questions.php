<?php
session_start();
include 'db_connect.php';


// Show deletion success message if set
$successMessage = '';
if (isset($_SESSION['message'])) {
    $successMessage = $_SESSION['message'];
    unset($_SESSION['message']);
}

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

// Get selected exam ID
$exam_id = isset($_GET['exam_id']) ? $_GET['exam_id'] : "";

// Fetch questions based on exam selection
$questions = [];
if ($exam_id) {
    $sql = "SELECT * FROM questions WHERE exam_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $exam_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $questions = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Questions</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color:rgb(250, 254, 242);
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
            margin-top: 50px;
            background:rgb(252, 252, 252);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.3);
        }
        h2 {
            text-align: center;
            color: #343a40;
            margin-bottom: 20px;
        }
        table {
            margin-top: 20px;
        }
        .btn-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

        <div class="container">
            <h2>Manage Questions</h2>
            <?php if (!empty($successMessage)) : ?>
                <div class="alert alert-info text-center">
            <?php echo $successMessage; ?>
        </div>
        <?php endif; ?>

        <form method="get">
            <label for="exam_id" class="form-label">Select Exam:</label>
            <select name="exam_id" id="exam_id" class="form-select" onchange="this.form.submit()">
            <option value="">-- Select Exam --</option>
            <?php foreach ($exams as $exam) { ?>
            <option value="<?php echo $exam['id']; ?>" <?php if ($exam_id == $exam['id']) echo 'selected'; ?>>
                <?php echo htmlspecialchars($exam['title']); ?>
            </option>
            <?php } ?>
            </select>
        </form>
        <?php if ($exam_id): ?>
    <!-- Delete subject button -->
        <form action="delete_exam.php" method="post" onsubmit="return confirm('Are you sure you want to delete this subject and all its questions & scores?')">
            <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
            <button type="submit" class="btn btn-danger mt-3">Delete Subject</button>
        </form>
            <!-- Upload Excel button -->
        <a href="upload_question.php?exam_id=<?php echo $exam_id; ?>" class="btn btn-success">
            Upload Questions via Excel
        </a>
        <?php endif; ?>



        <?php if (!empty($questions)) { ?>
            <table class="table table-bordered mt-4">
                <thead class="table-dark">
                    <tr>
                        <th>Question</th>
                        <th>Options</th>
                        <th>Correct Answer</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($questions as $q) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($q['question']); ?></td>
                            <td>
                                1) <?php echo htmlspecialchars($q['option1']); ?><br>
                                2) <?php echo htmlspecialchars($q['option2']); ?><br>
                                3) <?php echo htmlspecialchars($q['option3']); ?><br>
                                4) <?php echo htmlspecialchars($q['option4']); ?>
                            </td>
                            <td><strong><?php echo $q['correct_option']; ?></strong></td>
                            <td>
                                <a href="edit_question.php?id=<?php echo $q['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="delete_question.php?id=<?php echo $q['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } elseif ($exam_id) { ?>
            <p class="text-danger text-center mt-3">No questions found for this exam.</p>
        <?php } ?>

        <div class="btn-container">
            <a href="teacher_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

</body>
</html>
