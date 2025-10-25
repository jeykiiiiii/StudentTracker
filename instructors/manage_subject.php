<?php

include('../functions/db_connection.php');
session_start();

if (!isset($_GET['class_id'])) {
    die('Class not specified.');
}

$class_id = intval($_GET['class_id']);

$classQuery = "SELECT subject_name, schedule FROM classes WHERE id = ?";
$stmt = $conn->prepare($classQuery);
$stmt->bind_param("i", $class_id);
$stmt->execute();
$classResult = $stmt->get_result();
$class = $classResult->fetch_assoc();

if (!$class) {
    die('Class not found.');
}

$studentQuery = "
    SELECT s.id AS student_id, u.name, s.course, s.year_level
    FROM enrollments e
    JOIN students s ON e.student_id = s.id
    JOIN users u ON s.user_id = u.id
    WHERE e.class_id = ?";
$stmt = $conn->prepare($studentQuery);
$stmt->bind_param("i", $class_id);
$stmt->execute();
$students = $stmt->get_result();

$assessmentQuery = "SELECT id, title FROM assessments WHERE class_id = ?";
$stmt = $conn->prepare($assessmentQuery);
$stmt->bind_param("i", $class_id);
$stmt->execute();
$assessments = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Enrolled Students & Assessments</title>
    <link rel="stylesheet" href="../assets/css/hpstyle.css">
    <style>
        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
        }
        .card {
            display: flex;
            flex-direction: column;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            transition: transform 0.3s;
            background-color: #fff;
            min-height: 340px;
            max-height: 600px;
        }

        .card-assessment {
            display: flex;
            flex-direction: column;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            transition: transform 0.3s;
            background-color: #fff;
            height: 340px;
        }

        .card-flex {
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
            height: 340px; 
        }


        .card .submit-btn {
            margin-top: auto;
        }

        .card > div {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #f0f0f0;
            border-radius: 8px; 
            background-color: #f9f9f9;
        }

        .card input[type="text"],
        .card input[type="number"],
        .card select {
            padding: 10px;
            margin: 5px 0;
            width: 100%;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .card .submit-btn {
            margin-top: auto;
        }

        .card h3, .card-flex h3 {
            font-size: 18px;
            margin-bottom: 12px;
        }

        .card .assessment-form {
            display: flex;
            flex-direction: column;
            gap: 15px; 
        }

        .card:hover, .card-flex:hover {
            transform: translateY(-5px);
        }
        .card h3, .card-flex h3 {
            margin: 0 0 10px;
        }


        .card p {
            margin: 0 0 10px;
        }

        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 8px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            text-align: center;
            display: inline-block;
            margin-top: 10px;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .headerH2 {
            margin: 20px;
        }

        .assessment-form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .input-field {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .submit-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 8px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color:#45a049;
        }

        .back-btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 10%;
            text-decoration: none;
            color: white;
            margin: 10px;
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

<a class="back-btn" href="../instructors/classlist.php">Go Back </a>

<div class="container">

    <div class="card-flex">
        <h3><?php echo htmlspecialchars($class['subject_name']); ?></h3>
        <p><strong>Schedule:</strong> <?php echo htmlspecialchars($class['schedule']); ?></p>
        <a href="view_studentlist.php?class_id=<?php echo $class_id; ?>" class="btn">View Student List</a>
    </div>

    <form id="add-assessment-form" action="../functions/process_add_assessment.php" method="POST" class="assessment-form">
    <div class="card">
        
        <h3>Add Assessment</h3>
            <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
            
        <div>
            <label for="title">Assessment Title:</label>
            <input type="text" id="title" name="title" class="input-field" required>

            <label for="type">Assessment Type:</label>
            <select id="type" name="type" class="input-field" required>
                <option value="quiz">Quiz</option>
                <option value="activity">Activity</option>
                <option value="exam">Exam</option>
            </select>

            <label for="total_score">Total Score:</label>
            <input type="number" id="total_score" name="total_score" class="input-field" required>

        </div>
            <button type="submit" class="submit-btn" onclick="confirmAdd()">Add Assessment</button>

        </form>


    </div>

    <form id="delete-assessment-form" action="../functions/process_delete_assessment.php" method="POST" class="assessment-form">
        <div class="card">
            <h3>Delete Assessment</h3>
                <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">

                <div>
                    <label for="assessment">Select Assessment to Delete:</label>
                    <select id="assessment" name="assessment_id" class="input-field" required>
                        <?php while ($assessment = $assessments->fetch_assoc()): ?>
                            <option value="<?php echo $assessment['id']; ?>"><?php echo htmlspecialchars($assessment['title']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            <button type="button" class="submit-btn" style="background-color: #f44336;" onclick="confirmDelete()">Delete Assessment</button>
    </form>


    
</div>

<div class="card">
    <h3>All Assessments</h3>
    <ul>
        <?php 
        $assessmentQuery = "SELECT title, type, total_score FROM assessments WHERE class_id = ?";
        $stmt = $conn->prepare($assessmentQuery);
        $stmt->bind_param("i", $class_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0): 
            while ($assessment = $result->fetch_assoc()): ?>
                <li>
                    <strong><?php echo htmlspecialchars($assessment['title']); ?></strong> 
                    (<?php echo ucfirst($assessment['type']); ?> - Total Score: <?php echo $assessment['total_score']; ?>)
                </li>
            <?php endwhile; 
        else: ?>
            <li>No assessments available.</li>
        <?php endif; ?>
    </ul>
</div>

<script src="../assets/js/script.js">

</script>





</body>

</html>
