<?php
require "dtbcon.php";

$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

// Fetch likes
$likeQuery = $pdo->prepare("
    SELECT u.name 
    FROM postlike l
    JOIN user u ON l.user_id = u.id 
    WHERE l.post_id = ?
");
$likeQuery->execute([$post_id]);
$likes = $likeQuery->fetchAll(PDO::FETCH_COLUMN);

$likeCount = count($likes);

if ($likeCount === 0) {
    $likeText = "Be the first to like this";
} elseif ($likeCount === 1) {
    $likeText = "1 like: " . $likes[0];
} else {
    $likeText = $likeCount . " likes: " . implode(", ", $likes);
}

header('Content-Type: application/json');
echo json_encode([
    'count' => $likeCount,
    'text' => $likeText,
]);
exit;

?>