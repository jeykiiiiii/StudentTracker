<?php
include('../functions/db_connection.php');
session_start();

if (!isset($_SESSION['student_id'])) {
    die('Student is not logged in.');
}

if (!isset($_GET['class_id'])) {
    die('Class ID is missing.');
}

$class_id = intval($_GET['class_id']);
$student_id = $_SESSION['student_id'];  

$filter_type = isset($_GET['type']) ? $_GET['type'] : '';

$query = "
    SELECT c.subject_name, u.name AS instructor_name, c.schedule
    FROM classes c
    JOIN instructors i ON c.instructor_id = i.id
    JOIN users u ON i.user_id = u.id
    WHERE c.id = ?";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die('MySQL prepare failed: ' . $conn->error);
}

$stmt->bind_param("i", $class_id);

if (!$stmt->execute()) {
    die('Execute failed: ' . $stmt->error);
}

$subject = $stmt->get_result()->fetch_assoc();

if (!$subject) {
    die('Subject not found.');
}

$scores_query = "
    SELECT a.title AS assessment_title, a.type AS assessment_type, ss.score, a.total_score 
    FROM student_scores ss
    JOIN assessments a ON ss.assessment_id = a.id
    WHERE ss.student_id = ? AND a.class_id = ?";

if ($filter_type) {
    $scores_query .= " AND a.type = ?";
}

$scores_stmt = $conn->prepare($scores_query);

if ($scores_stmt === false) {
    die('MySQL prepare failed: ' . $conn->error);
}

if ($filter_type) {
    $scores_stmt->bind_param("iis", $student_id, $class_id, $filter_type);
} else {
    $scores_stmt->bind_param("ii", $student_id, $class_id);
}

if (!$scores_stmt->execute()) {
    die('Execute failed: ' . $scores_stmt->error);
}

$scores_result = $scores_stmt->get_result();

$total_score = 0;
$total_possible_score = 0;
while ($row = $scores_result->fetch_assoc()) {
    $total_score += $row['score'];
    $total_possible_score += $row['total_score'];
    $scores[] = $row;
}
$scores_result->data_seek(0);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject Details - <?php echo htmlspecialchars($subject['subject_name']); ?></title>
    <link rel="stylesheet" href="../assets/css/hpstyle.css">
    <style>
        .subject-details {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .subject-details h2 {
            margin-bottom: 20px;
        }
        .subject-details p {
            margin-bottom: 10px;
        }
        .back-btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 10%;
            margin: 10px;
        }
        .back-btn:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .filter-form {
            margin-top: 20px;
        }

        .select {
            padding: 10px;
        }
    </style>
</head>
<body>

    <?php include("../includes/header.php"); ?>
    <?php include("../includes/sideNavStudent.php"); ?>

    <a class="back-btn" href="subjects.php">Back</a>

    <div class="subject-details">
        <h2>Subject: <?php echo htmlspecialchars($subject['subject_name']); ?></h2>
        <p><strong>Instructor:</strong> <?php echo htmlspecialchars($subject['instructor_name']); ?></p>
        <p><strong>Schedule:</strong> <?php echo htmlspecialchars($subject['schedule']); ?></p>

        <h3>Student Scores</h3>

        <form method="GET" class="filter-form">
            <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
            <label for="type">Filter by Assessment Type:</label>
            <select id="type" name="type" onchange="this.form.submit()">
                <option value="" <?php echo $filter_type == '' ? 'selected' : ''; ?>>All</option>
                <option value="quiz" <?php echo $filter_type == 'quiz' ? 'selected' : ''; ?>>Quiz</option>
                <option value="activity" <?php echo $filter_type == 'activity' ? 'selected' : ''; ?>>Activity</option>
                <option value="exam" <?php echo $filter_type == 'exam' ? 'selected' : ''; ?>>Exam</option>
            </select>
        </form>

        <?php if (count($scores) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Assessment Title</th>
                        <th>Type</th>
                        <th>Score</th>
                        <th>Total Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($scores as $score): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($score['assessment_title']); ?></td>
                            <td><?php echo htmlspecialchars($score['assessment_type']); ?></td>
                            <td><?php echo htmlspecialchars($score['score']); ?></td>
                            <td><?php echo htmlspecialchars($score['total_score']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        
        <?php if ($filter_type !== '' && count($scores) > 0): ?>
            <h3>Total Scores</h3>
            <table>
                <tr>
                    <th>Total Score</th>
                    <th>Total Possible Score</th>
                </tr>
                <tr>
                    <td><?php echo $total_score; ?></td>
                    <td><?php echo $total_possible_score; ?></td>
                </tr>
            </table>
            <?php endif; ?>

        <?php else: ?>
            <p>No scores found for this class.</p>
        <?php endif; ?>
    </div>
            

</body>
</html>
