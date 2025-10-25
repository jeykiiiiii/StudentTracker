<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the posted data
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['id']) || !is_numeric($data['id'])) {
        echo json_encode(['success' => false, 'error' => 'Invalid input data']);
        exit();
    }

    $id = $data['id'];
    $user_id = $_SESSION['user_id']; // Get the logged-in user's ID

    // MySQL database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "student_register";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'error' => 'Connection failed: ' . $conn->connect_error]);
        exit();
    }

    // Prepare and execute the DELETE statement
    $sql = "DELETE FROM qr_codes WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo json_encode(['success' => false, 'error' => 'Error preparing statement']);
        exit();
    }

    $stmt->bind_param('ii', $id, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'QR Code not found or you do not have permission to delete it']);
    }

    $stmt->close();
    $conn->close();
}
?>
