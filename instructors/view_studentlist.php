<?php

include('../functions/db_connection.php');
session_start();

if (!isset($_GET['class_id'])) {
    die('Class not specified.');
}

$class_id = intval($_GET['class_id']);

// Fetch class information
$classQuery = "SELECT subject_name FROM classes WHERE id = ?";
$stmt = $conn->prepare($classQuery);
$stmt->bind_param("i", $class_id);
$stmt->execute();
$classResult = $stmt->get_result();
$class = $classResult->fetch_assoc();

if (!$class) {
    die('Class not found.');
}

// Fetch enrolled students (including image and email from users table)
$studentQuery = "
    SELECT s.id AS student_id, u.name, s.course, s.year_level, u.image, u.email
    FROM enrollments e
    JOIN students s ON e.student_id = s.id
    JOIN users u ON s.user_id = u.id
    WHERE e.class_id = ?";
$stmt = $conn->prepare($studentQuery);
$stmt->bind_param("i", $class_id);
$stmt->execute();
$students = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Enrolled Students</title>
    <link rel="stylesheet" href="../assets/css/hpstyle.css">
    <style>
        .student-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
        }

        .student-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            transition: transform 0.3s;
            background-color: #fff;
        }

        .student-card:hover {
            transform: translateY(-5px);
        }

        .student-card h3 {
            margin: 0 0 10px;
        }

        .student-card p {
            margin: 0 0 10px;
        }

        .student-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
            align-self: center;
        }

        .manage-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 8px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            text-align: center;
            display: block;
            margin-top: 10px;
        }

        .manage-btn:hover {
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
        }

        .back-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>

<?php 
    include('../includes/header.php');
    include('../includes/sideNavInstructor.php');
?>

<button class="back-btn" onclick="window.history.back()">Go Back</button>

<h2 class="headerH2">Enrolled Students in <?php echo htmlspecialchars($class['subject_name']); ?></h2>

<div class="student-container">
    <?php if ($students->num_rows == 0): ?>
        <p>No students enrolled in this class.</p>
    <?php else: ?>
        <?php while ($student = $students->fetch_assoc()): ?>
            <div class="student-card">
                <?php if ($student['image']): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($student['image']); ?>" alt="Student Image" class="student-image">
                <?php else: ?>
                    <img src="../assets/images/default-avatar.jpg" alt="Default Student Image" class="student-image">
                <?php endif; ?>

                <h3><?php echo htmlspecialchars($student['name']); ?></h3>
                <p><strong>Course:</strong> <?php echo htmlspecialchars($student['course']); ?></p>
                <p><strong>Year Level:</strong> <?php echo htmlspecialchars($student['year_level']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>

                <a href="manage_scores.php?class_id=<?php echo $class_id; ?>&student_id=<?php echo $student['student_id']; ?>" class="manage-btn">
                    Manage Assessment Scores
                </a>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

</body>

</html>
