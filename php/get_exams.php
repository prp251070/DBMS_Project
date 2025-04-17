<?php
include 'db_connect.php';

$sql = "SELECT * FROM exams";
$result = $conn->query($sql);

$exams = [];
while ($row = $result->fetch_assoc()) {
    $exams[] = $row;
}

echo json_encode($exams);
?>