<?php
// Start the session
session_start();

// Include database connection
include('../functions/db_connection.php');

// Check if student_id is set in session
if (!isset($_SESSION['student_id'])) {
    echo 'Session variable student_id is not set.';
    var_dump($_SESSION); // Debugging: Show all session variables
    die('You must be logged in to view this page.');
}

$student_id = $_SESSION['student_id']; // Retrieve student ID from session

// Check if class_id is provided
if (!isset($_GET['class_id'])) {
    die('Class ID is missing.');
}

$class_id = intval($_GET['class_id']);

// Fetch subject details based on class_id
$query = "
    SELECT c.subject_name, u.name AS instructor_name, c.schedule
    FROM classes c
    JOIN instructors i ON c.instructor_id = i.id
    JOIN users u ON i.user_id = u.id
    WHERE c.id = ?";

$stmt = $conn->prepare($query);
if ($stmt === false) {
    die('Error preparing the SQL query: ' . $conn->error);
}

$stmt->bind_param("i", $class_id);
$stmt->execute();
$subject = $stmt->get_result()->fetch_assoc();

if (!$subject) {
    die('Subject not found.');
}

// Fetch the student's scores for this subject
$scores_query = "
    SELECT a.title, a.type, s.score
    FROM student_scores s
    JOIN assessments a ON s.assessment_id = a.id
    WHERE s.student_id = ? AND a.class_id = ?";

$scores_stmt = $conn->prepare($scores_query);
if ($scores_stmt === false) {
    die('Error preparing the SQL query for scores: ' . $conn->error);
}

$scores_stmt->bind_param("ii", $student_id, $class_id);
$scores_stmt->execute();
$scores_result = $scores_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject Details - <?php echo htmlspecialchars($subject['subject_name']); ?></title>
    <link rel="stylesheet" href="../assets/css/hpstyle.css">
    <style>
        /* Your existing styles */
    </style>
</head>
<body>

    <?php include("../includes/header.php"); ?>
    <?php include("../includes/sideNavStudent.php"); ?>

    <a href="subjects.php">
        <button class="back-btn">Back to Enrolled Subjects</button>
    </a>

    <div class="subject-details">
        <h2>Subject: <?php echo htmlspecialchars($subject['subject_name']); ?></h2>
        <p><strong>Instructor:</strong> <?php echo htmlspecialchars($subject['instructor_name']); ?></p>
        <p><strong>Schedule:</strong> <?php echo htmlspecialchars($subject['schedule']); ?></p>

        <h3>Your Scores:</h3>
        <table>
            <thead>
                <tr>
                    <th>Assessment Title</th>
                    <th>Type</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($scores_result->num_rows > 0): ?>
                    <?php while ($score = $scores_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($score['title']); ?></td>
                            <td><?php echo htmlspecialchars($score['type']); ?></td>
                            <td><?php echo htmlspecialchars($score['score']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No scores available for this subject.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>