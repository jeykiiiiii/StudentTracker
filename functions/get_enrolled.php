<?php
function getStudentClasses($student_id) {
    global $conn;
    $sql = "SELECT c.subject_name, c.schedule, u.name AS instructor_name 
            FROM enrollments e
            JOIN classes c ON e.class_id = c.id
            JOIN instructors i ON c.instructor_id = i.id
            JOIN users u ON i.user_id = u.id
            WHERE e.student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    return $stmt->get_result();
}

?>