<?php
require "../functions/dtbcon.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int) $_POST['id'];

    // Set unsubscribe_date to the current date
    $stmt = $pdo->prepare("UPDATE user SET role_id = 1 WHERE id = ?");
    
    if ($stmt->execute([$id])) {
        echo "Updated user role successfully.";
    } else {
        echo "Error updating user.";
    }
} else {
    echo "Invalid request.";
}

?>