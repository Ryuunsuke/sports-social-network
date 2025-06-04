<?php
require "../functions/dtbcon.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int) $_POST['id'];

    // Set unsubscribe_date to the current date
    $stmt = $pdo->prepare("UPDATE user SET unsubscribe_date = NOW() WHERE id = ?");
    
    if ($stmt->execute([$id])) {
        echo "User deregistered successfully.";
    } else {
        echo "Error deregistering user.";
    }
} else {
    echo "Invalid request.";
}

?>