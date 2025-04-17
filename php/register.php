<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Secure password

    $sql = "INSERT INTO students (name, email, password) VALUES ('$name', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        // Show dialog box for successful registration
        echo "<script>
                alert('Registration successful!');
                window.location.href = '../index.html'; // Redirect to login page
              </script>";
    } else {
        // Show error dialog if there's an issue with the query
        echo "<script>
                alert('Error: " . $conn->error . "');
              </script>";
    }
}
?>
