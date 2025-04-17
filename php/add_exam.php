<?php
session_start();
include 'db_connect.php'; // Include database connection

if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $exam_title = $_POST['exam_title'];

    $sql = "INSERT INTO exams (title, teacher_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $exam_title, $_SESSION['teacher_id']);

    if ($stmt->execute()) {
        echo "<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>
                <h4>✅ Exam created successfully!</h4>
                <a href='teacher_dashboard.php' class='btn btn-success mt-3'>Go to Dashboard</a>
              </div>";
    } else {
        echo "<div style='text-align:center; margin-top:50px; font-family:sans-serif; color:red;'>
                ❌ Error: " . $conn->error . "
              </div>";
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
    <title>Create Exam</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f3f6fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card-custom {
            max-width: 450px;
            margin: 80px auto;
            padding: 30px;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0px 8px 30px rgba(0, 0, 0, 0.1);
        }
        .form-control {
            border-radius: 8px;
        }
        .btn {
            border-radius: 8px;
        }
    </style>
</head>
<body>

    <div class="card card-custom">
        <h3 class="text-center mb-4">Create New Exam</h3>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Exam Title</label>
                <input type="text" name="exam_title" class="form-control" placeholder="Enter subject name..." required>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">➕ Create Exam</button>
                <a href="teacher_dashboard.php" class="btn btn-secondary">⬅️ Back to Dashboard</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
