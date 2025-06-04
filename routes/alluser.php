<?php
require "../functions/dtbcon.php";

header('Content-Type: application/json');

$name = $_POST['name'] ?? '';
$surname = $_POST['surname'] ?? '';

// Fetch user info, image path, and unsubscribe status
$sql = "SELECT u.id, u.name, u.surname, i.path AS profilepic, u.unsubscribe_date 
        FROM user u
        JOIN profileimage pfp ON pfp.user_id = u.id
        JOIN image i ON i.id = pfp.image_id
        WHERE u.name LIKE ? AND u.surname LIKE ?";
$stmt = $pdo->prepare($sql);
$stmt->execute(["%$name%", "%$surname%"]);

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($users);
?>
