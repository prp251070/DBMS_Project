<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM students WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['student_id'] = $row['id'];
            $_SESSION['student_name'] = $row['name'];
            header("Location: ../dashboard.html");
            exit();
        } else {
            // Invalid password, show alert using JavaScript
            echo "<script>alert('Invalid password. Please try again.'); window.location.href = '../index.html';</script>";
        }
    } else {
        // User not found, show alert using JavaScript
        echo "<script>alert('User not found. Please register first.'); window.location.href = '../register.html';</script>";
    }
}
?>

