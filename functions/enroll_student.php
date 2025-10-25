<?php

function enrollStudent($student_id, $class_id) {
    global $conn;
    $sql = "INSERT INTO enrollments (student_id, class_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $student_id, $class_id);
    return $stmt->execute();
}

?>