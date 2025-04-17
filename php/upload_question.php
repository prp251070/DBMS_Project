<?php
session_start();
include 'db_connect.php';

// Redirect to login if teacher not logged in
if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher_login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];

// Fetch exams created by the logged-in teacher
$sql = "SELECT id, title FROM exams WHERE teacher_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

$exams = [];
while ($row = $result->fetch_assoc()) {
    $exams[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Excel File</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">Upload Questions via Excel</h2>
        <form action="process_excel.php" method="post" enctype="multipart/form-data" class="p-4 bg-white rounded shadow-sm">

            <div class="mb-3">
                <label class="form-label">Select Exam:</label>
                <select name="exam_id" class="form-select" required>
                    <option value="">-- Select Exam --</option>
                    <?php foreach ($exams as $exam): ?>
                        <option value="<?= $exam['id'] ?>"><?= htmlspecialchars($exam['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Upload Excel File (.xlsx):</label>
                <input type="file" name="questions_file" class="form-control" accept=".xlsx" required>
            </div>

            <button type="submit" class="btn btn-success">📤 Upload Questions</button>
        </form>
    </div>
</body>
</html>
