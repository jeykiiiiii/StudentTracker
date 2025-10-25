<?php

function getInstructorClasses($userId, $conn) {
    // Ensure the user is an instructor
    $instructorQuery = "SELECT id FROM instructors WHERE user_id = ?";
    $stmt = $conn->prepare($instructorQuery);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return []; // Not an instructor or no associated record
    }

    $instructor = $result->fetch_assoc();
    $instructorId = $instructor['id'];

    // Fetch the classes associated with this instructor
    $classQuery = "SELECT id, subject_name, schedule FROM classes WHERE instructor_id = ?";
    $stmt = $conn->prepare($classQuery);
    $stmt->bind_param("i", $instructorId);
    $stmt->execute();
    $result = $stmt->get_result();

    $classes = [];

    while ($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }

    return $classes;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class List</title>
    <link rel="stylesheet" href="../assets/css/hpstyle.css">
</head>

<body>
    <?php 
        include('../includes/header.php'); 
        include('../includes/sideNavInstructor.php'); 
        include('../functions/db_connection.php'); 

        session_start();
        $userId = $_SESSION['user_id'];
        $classes = getInstructorClasses($userId, $conn);

        if (isset($_GET['delete_class_id'])) {
            $classIdToDelete = $_GET['delete_class_id'];
            deleteClass($classIdToDelete, $conn);
            header("Location: ../instructors/class_list.php");
            exit();
        }

        function deleteClass($classId, $conn) {
            $deleteQuery = "DELETE FROM classes WHERE id = ?";
            $stmt = $conn->prepare($deleteQuery);
            $stmt->bind_param("i", $classId);
            if ($stmt->execute()) {
                echo "<script>alert('Class deleted successfully.');</script>";
            } else {
                echo "<script>alert('Error deleting class.');</script>";
            }
        }
    ?>

    <a class="back-btn" href="../instructors/instructordashboard.php">Go Back</a>

    <h2 class="headerH2">Class Schedules</h2>

    <div class="class-container">
        <?php if (empty($classes)): ?>
            <p>No classes found.</p>
        <?php else: ?>
            <?php foreach ($classes as $class): ?>
                <div class="class-card">
                    <h3><?php echo htmlspecialchars($class['subject_name']); ?></h3>
                    <p>Schedule: <?php echo htmlspecialchars($class['schedule']); ?></p>
                    <a href="manage_subject.php?class_id=<?php echo $class['id']; ?>">
                        <button>Manage Subject</button>
                    </a>
                    <a href="?delete_class_id=<?php echo $class['id']; ?>" onclick="return confirm('Are you sure you want to delete this class?');">
                        <button class="del-btn">Delete Subject</button>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</body>

<script src="../assets/js/script.js"></script>

<style>
    .class-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        padding: 20px;
    }

    .class-card {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        border: 1px solid #ddd;
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 300px;
        height: 200px;
        transition: transform 0.3s;
    }

    .class-card:hover {
        transform: translateY(-5px);
    }

    .class-card h3 {
        margin: 0 0 10px;
    }

    .class-card p {
        margin: 0 0 10px;
    }

    .class-card a {
        margin-top: auto; 
    }

    .class-card button {
        padding: 8px 12px;
        border: none;
        border-radius: 8px;
        background-color: #4CAF50;
        color: white;
        cursor: pointer;
        transition: background-color 0.3s;
        width: 100%;
        margin-bottom: 5px;
    }

    .class-card button:hover {
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
        background-color: #45a049;
    }

    .del-btn {
        background-color: #f44336 !important;
        color: white !important;
        transition: background-color 0.3s;
    }

    .del-btn:hover {
        background-color: darkred !important;
    }
</style>


</html>
