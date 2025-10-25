<?php

include('db_connection.php');
session_start();

// Check if class_id and assessment_id are set and valid
if (isset($_POST['class_id']) && isset($_POST['assessment_id'])) {
    $class_id = intval($_POST['class_id']);
    $assessment_id = intval($_POST['assessment_id']);

    // Prepare the query to delete the assessment
    $deleteQuery = "DELETE FROM assessments WHERE id = ? AND class_id = ?";

    // Prepare the statement
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("ii", $assessment_id, $class_id);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect to the same page or another page (optional)
        $_SESSION['success_message'] = "Assessment deleted successfully!";
        header("Location: ../instructors/manage_subject.php?class_id=" . $class_id); // Redirect back to class view
        echo "<script>alert('Assessment successfully deleted!');</script>";

        exit;
    } else {
        // Handle failure
        $_SESSION['error_message'] = "Failed to delete the assessment. Please try again.";
        header("Location: ../instructors/manage_subject.php?class_id=" . $class_id);
        echo "<script>alert('Assessment deletion failed');</script>";

        exit;
    }
} else {
    // Handle the case where required data is missing
    $_SESSION['error_message'] = "Invalid request. Please try again.";
    header("Location: view_class.php");
    exit;
}

?>
