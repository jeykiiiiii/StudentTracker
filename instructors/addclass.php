<?php

include('../functions/db_connection.php');

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$instructorQuery = "SELECT id FROM instructors WHERE user_id = ?";
$stmt = $conn->prepare($instructorQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "You are not authorized to add classes.";
    exit();
}

$instructor = $result->fetch_assoc();
$instructorId = $instructor['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subjectName = trim($_POST['subject_name']);
    $day = trim($_POST['day']);
    $startTime = trim($_POST['start_time']);
    $endTime = trim($_POST['end_time']);

    if (empty($subjectName) || empty($day) || empty($startTime) || empty($endTime)) {
        $errorMsg = "Please fill out all fields.";
    } else {
        // Combine day with start and end times for the schedule
        $schedule = $day . ' ' . $startTime . ' to ' . $endTime; 
        $insertClassQuery = "INSERT INTO classes (instructor_id, subject_name, schedule) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertClassQuery);
        $stmt->bind_param("iss", $instructorId, $subjectName, $schedule);
        
        if ($stmt->execute()) {
            $successMsg = "Class added successfully!";
        } else {
            $errorMsg = "Error adding class: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Class</title>
    <link rel="stylesheet" href="../assets/css/hpstyle.css">
    <style>
        .add-class-page .form-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .add-class-page h2 {
            text-align: center;
        }

        .add-class-page label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        .add-class-page select,
        .add-class-page input {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .add-class-page button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .add-class-page button:hover {
            background-color: #45a049;
        }

        .add-class-page .success {
            color: #4CAF50;
            text-align: center;
        }

        .add-class-page .error {
            color: #E74C3C;
            text-align: center;
        }
    </style>
</head>

<body>

    <?php 
        include('../includes/header.php');
        include('../includes/sideNavInstructor.php');
    ?>


<div class="add-class-page">

    <div class="form-container">
        <h2>Add a New Class</h2>

        <?php if (isset($successMsg)): ?>
            <p class="success"><?php echo htmlspecialchars($successMsg); ?></p>
        <?php endif; ?>

        <?php if (isset($errorMsg)): ?>
            <p class="error"><?php echo htmlspecialchars($errorMsg); ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="subject_name">Subject Name:</label>
            <input type="text" id="subject_name" name="subject_name" required>

            <label for="day">Day:</label>
            <select id="day" name="day" required>
                <option value="">Select a day</option>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
            </select>

            <label for="start_time">Start Time:</label>
            <select id="start_time" name="start_time" required>
                <option value="">Select start time</option>
                <?php
                    // Generate time slots from 7:00 AM to 9:00 PM in 30-minute intervals
                    $start = strtotime('07:00 AM');
                    $end = strtotime('09:00 PM');
                    $interval = 30 * 60; // 30 minutes in seconds

                    for ($currentTime = $start; $currentTime <= $end; $currentTime += $interval) {
                        echo '<option value="' . date('h:i A', $currentTime) . '">' . date('h:i A', $currentTime) . '</option>';
                    }
                ?>
            </select>

            <label for="end_time">End Time:</label>
            <select id="end_time" name="end_time" required>
                <option value="">Select end time</option>
                <?php
                    // Generate time slots from 7:00 AM to 9:00 PM in 30-minute intervals
                    for ($currentTime = $start; $currentTime <= $end; $currentTime += $interval) {
                        echo '<option value="' . date('h:i A', $currentTime) . '">' . date('h:i A', $currentTime) . '</option>';
                    }
                ?>
            </select>

            <button type="submit">Add Class</button>
        </form>
    </div>

</div>


</body>

<script src="../assets/js/script.js"></script>

</html>
