<?php

include('../functions/db_connection.php');
session_start();

if (!isset($_GET['student_id']) || !isset($_GET['class_id'])) {
    die('Missing parameters.');
}

$student_id = intval($_GET['student_id']);
$class_id = intval($_GET['class_id']);

// Fetch student information
$studentQuery = "
    SELECT u.name, s.course, s.year_level 
    FROM students s
    JOIN users u ON s.user_id = u.id
    WHERE s.id = ?";
$stmt = $conn->prepare($studentQuery);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

if (!$student) {
    die('Student not found.');
}

// Fetch all assessments for this class and any scores recorded for the student
$assessmentQuery = "
    SELECT a.id AS assessment_id, a.type, a.title, a.total_score, 
           s.score, s.recorded_at
    FROM assessments a
    LEFT JOIN student_scores s 
        ON a.id = s.assessment_id AND s.student_id = ?
    WHERE a.class_id = ?
    ORDER BY a.type, a.created_at";
$stmt = $conn->prepare($assessmentQuery);
$stmt->bind_param("ii", $student_id, $class_id);
$stmt->execute();
$assessments = $stmt->get_result();

// Organize assessments by type (quiz, activity, exam)
$groupedAssessments = [
    'quiz' => [],
    'activity' => [],
    'exam' => []
];

while ($row = $assessments->fetch_assoc()) {
    $groupedAssessments[$row['type']][] = $row;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Manage Student Scores</title>
    <link rel="stylesheet" href="../assets/css/hpstyle.css">
    <style>
        .score-section {
            margin: 30px;
        }
        .score-card {
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }
        .score-card h4 {
            margin: 0 0 10px;
        }
        .input-score {
            padding: 5px;
            width: 80px;
        }
        .submit-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 8px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #45a049;
        }
        .headerH2 {
            margin: 20px;
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
            background-color: #e53935;
        }
    </style>
</head>

<body>

<?php 
    include('../includes/header.php');
    include('../includes/sideNavInstructor.php');
?>



<h2 class="headerH2">Scores for <?php echo htmlspecialchars($student['name']); ?> (<?php echo htmlspecialchars($student['course']); ?> - Year <?php echo htmlspecialchars($student['year_level']); ?>)</h2>

<?php foreach ($groupedAssessments as $type => $assessments): ?>
    <div class="score-section">
        <h3><?php echo ucfirst($type); ?> Scores</h3>

        <?php foreach ($assessments as $assessment): ?>
            <div class="score-card">
                <h4><?php echo htmlspecialchars($assessment['title']); ?></h4>
                <p>Max Score: <?php echo htmlspecialchars($assessment['total_score']); ?></p>

                <form action="../functions/update_student_score.php" method="POST">
                    <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                    <input type="hidden" name="assessment_id" value="<?php echo $assessment['assessment_id']; ?>">
                    <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">

                    <label for="score">Score:</label>
                    <input type="number" id="score" name="score" class="input-score"
                        value="<?php echo isset($assessment['score']) ? htmlspecialchars($assessment['score']) : ''; ?>" 
                        max="<?php echo $assessment['total_score']; ?>" required>
                    
                    <button type="submit" class="submit-btn">Update Score</button>
                </form>

                <p>Recorded At: <?php echo $assessment['recorded_at'] ? htmlspecialchars($assessment['recorded_at']) : 'Not yet recorded'; ?></p>
            </div>
        <?php endforeach; ?>

    </div>
<?php endforeach; ?>

</body>

</html>
