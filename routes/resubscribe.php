<?php
require "../functions/dtbcon.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int) $_POST['id'];

    // Clear the unsubscribe_date
    $stmt = $pdo->prepare("UPDATE user SET unsubscribe_date = NULL WHERE id = ?");
    
    if ($stmt->execute([$id])) {
        echo "User resubscribed successfully.";
    } else {
        echo "Error resubscribing user.";
    }
} else {
    echo "Invalid request.";
}
?>