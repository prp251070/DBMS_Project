<?php
session_start();
include 'php/db_connect.php';

$sql = "
    SELECT exams.title AS subject, students.name, ses.score
    FROM student_exam_scores ses
    JOIN exams ON ses.exam_id = exams.id
    JOIN students ON ses.student_id = students.id
    ORDER BY exams.title, ses.score DESC
";

$result = $conn->query($sql);

$leaderboards = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subject = $row['subject'];
        if (!isset($leaderboards[$subject])) {
            $leaderboards[$subject] = [];
        }
        $leaderboards[$subject][] = [
            'name' => $row['name'],
            'score' => $row['score']
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subject-wise Leaderboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    
        body {
            background-color: #f5f6fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .leaderboard-container {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: center;
            padding: 40px 20px;
        }
        .leaderboard-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0px 6px 15px rgba(0,0,0,0.1);
            padding: 20px;
            width: 600px;
        }
        .leaderboard-card h4 {
            color: #007bff;
            margin-bottom: 15px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(odd) {
            background-color: #f8f9fa;
        }
        .chart-container {
            margin-top: 20px;
        }
    </style>
</head>

<body>

<div class="text-center mt-4">
    <h2>📊 Subject-wise Leaderboards</h2>
</div>

<div class="leaderboard-container">
    <?php $chartIndex = 0; ?>
    <?php foreach ($leaderboards as $subject => $entries): ?>
        <div class="leaderboard-card">
            <h4><?php echo htmlspecialchars($subject); ?> Leaderboard</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Name</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rank = 1; ?>
                    <?php foreach ($entries as $entry): ?>
                        <tr>
                            <td><?php echo $rank++; ?></td>
                            <td><?php echo htmlspecialchars($entry['name']); ?></td>
                            <td><?php echo $entry['score']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="chart-container">
                <canvas id="chart<?php echo $chartIndex; ?>"></canvas>
            </div>

            <script>
                const ctx<?php echo $chartIndex; ?> = document.getElementById('chart<?php echo $chartIndex; ?>').getContext('2d');
                new Chart(ctx<?php echo $chartIndex; ?>, {
                    type: 'bar',
                    data: {
                        labels: <?php echo json_encode(array_column($entries, 'name')); ?>,
                        datasets: [{
                            label: 'Scores',
                            data: <?php echo json_encode(array_column($entries, 'score')); ?>,
                            backgroundColor: 'rgba(0, 123, 255, 0.6)',
                            borderColor: 'rgba(0, 123, 255, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Score'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Student'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            </script>
        </div>
        <?php $chartIndex++; ?>
    <?php endforeach; ?>
</div>
<?php
    $dashboardLink = "#"; // default

    if (isset($_SESSION['teacher_id'])) {
        $dashboardLink = "php/teacher_dashboard.php";
    } elseif (isset($_SESSION['student_id'])) {
        $dashboardLink = "dashboard.html";
    }
    ?>
    <div class="text-center mt-4 mb-5">
        <a href="<?php echo $dashboardLink; ?>" class="btn btn-secondary btn-lg">
            🔙 Back to Dashboard
        </a>
    </div>  
</body>
</html>
