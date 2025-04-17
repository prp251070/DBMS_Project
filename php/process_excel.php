<?php
require '../vendor/autoload.php'; // Composer autoload
include 'db_connect.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_FILES['questions_file']['tmp_name']) && isset($_POST['exam_id'])) {
    $filePath = $_FILES['questions_file']['tmp_name'];
    $examId = $_POST['exam_id'];

    $spreadsheet = IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    // Skip the header row (start from index 1)
    for ($i = 1; $i < count($rows); $i++) {
        [$question, $opt1, $opt2, $opt3, $opt4, $correct] = $rows[$i];

        if (!$question || !$correct) continue; // Skip empty rows

        $stmt = $conn->prepare("INSERT INTO questions (exam_id, question, option1, option2, option3, option4, correct_option) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssi", $examId, $question, $opt1, $opt2, $opt3, $opt4, $correct);
        $stmt->execute();
    }

    echo "Questions uploaded successfully!";
} else {
    echo "Invalid file or exam ID.";
}
