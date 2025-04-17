<?php
include 'db_connect.php'; // Include database connection
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if teacher exists
    $sql = "SELECT * FROM teachers WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $teacher = $result->fetch_assoc();

    if ($teacher && password_verify($password, $teacher['password'])) {
        $_SESSION['teacher_id'] = $teacher['id'];
        $_SESSION['teacher_name'] = $teacher['name'];
        header("Location: teacher_dashboard.php"); // Redirect to dashboard
        exit();
    } else {
        echo "Invalid email or password.";
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
    <title>Teacher Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .bg-cover {
            background-image: url('../images/chris-liverani-rD2dc_2S3i0-unsplash.jpg'); /* Path to your background image */
            background-size: cover;
            background-position: center;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 30px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }

        h2 {
            font-weight: bold;
        }

        .form-label {
            font-weight: 500;
        }

        .btn-primary {
            font-weight: 600;
        }

        a {
            text-decoration: none;
        }

        .btn-student {
            margin-top: 10px;
        }

        @media (max-width: 576px) {
            .login-container {
                margin: 15px;
            }
        }
    </style>
</head>
<body>

    <div class="bg-cover">
        <div class="login-container">
            <h2 class="text-center mb-4">Teacher Login</h2>
            <form method="post" action="teacher_login.php">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>

            <p class="text-center mt-3">
                Don't have an account? <a href="teacher_register.php">Register</a>
            </p>

            <!-- Student login button -->
            <a href="../index.html" class="btn btn-outline-secondary w-100 btn-student">Not a Teacher? Login as Student</a>
        </div>
    </div>

</body>
</html>
