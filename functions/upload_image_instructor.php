<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to upload an image.");
}

include('db_connection.php'); 

if (isset($_FILES['image'])) {
    $image = $_FILES['image'];

    if ($image['error'] == 0) {
        $imageData = file_get_contents($image['tmp_name']);
        $userId = $_SESSION['user_id'];

        $query = "UPDATE users SET image = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $imageData, $userId);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Profile image updated successfully!";
        } else {
            $_SESSION['message'] = "Failed to update profile image.";
        }

        $stmt->close();
    } else {
        $_SESSION['message'] = "Error uploading file: " . $image['error'];
    }

    header("Location: ../instructors/profile.php");
    exit;
} else {
    $_SESSION['message'] = "No file uploaded.";
    header("Location: ../instructors/profile.php");
    exit;
}
?>
