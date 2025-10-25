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
