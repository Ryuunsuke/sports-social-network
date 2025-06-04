<?php
require "../functions/dtbcon.php";

header('Content-Type: application/json');

$name = $_POST['name'] ?? '';
$surname = $_POST['surname'] ?? '';

// Fetch user info, image path, and unsubscribe status
$sql = "SELECT u.name, u.surname, p.id AS post_id, p.title, p.post_date, i.id AS image_id, i.path AS postimage
        FROM post p
        JOIN postimage pi ON pi.post_id = p.id
        JOIN image i ON i.id = pi.image_id
        JOIN user u ON u.id = p.user_id
        WHERE u.name LIKE ? AND u.surname LIKE ?
        ORDER BY p.post_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute(["%$name%", "%$surname%"]);

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($users);
?>
