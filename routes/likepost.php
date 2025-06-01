<?php
include '../functions/dtbcon.php';

$post_id = $_POST['post_id'];
$user_id = $_POST['user_id'];

// Check if already liked
$check = $pdo->prepare("SELECT * FROM postlike WHERE post_id = ? AND user_id = ?");
$check->execute([$post_id, $user_id]);

if ($check->rowCount() == 0) {
    $insert = $pdo->prepare("INSERT INTO postlike (post_id, user_id) VALUES (?, ?)");
    $insert->execute([$post_id, $user_id]);
}

?>