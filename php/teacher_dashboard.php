<?php
session_start();
if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher_login.php"); // Redirect if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
        }

        body {
            background: linear-gradient(to right,rgb(127, 159, 191), #ffffff);
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-card {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 40px 30px;
            border-radius: 15px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        .btn {
            font-weight: 500;
            font-size: 16px;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .d-grid .btn + .btn {
            margin-top: 12px;
        }
    </style>
</head>
<body>

    <div class="dashboard-card shadow">
        <h2 class="text-center">Welcome, <?php echo htmlspecialchars($_SESSION['teacher_name']); ?> </h2>
        <div class="d-grid gap-2 mt-4">
            <a href="add_exam.php" class="btn btn-outline-primary">📖 Create New Exam</a>
            <a href="add_question.php" class="btn btn-outline-success">📝 Add Questions</a>
            <a href="manage_questions.php" class="btn btn-outline-info">📚 Manage Exams</a>
            <a href="../leaderboard.php" class="btn btn-outline-warning">🏆 View Leaderboard</a>
            <a href="teacher_logout.php" class="btn btn-outline-danger">🚪 Logout</a>
        </div>
    </div>

</body>
</html>
