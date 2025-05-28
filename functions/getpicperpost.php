<?php
    require "dtbcon.php";

    $post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

    $imageQuery = $pdo->prepare("SELECT i.path FROM postimage p 
                                JOIN image i ON i.id = p.image_id
                                WHERE post_id = ?");
    $imageQuery->execute([$post_id]);
    $images = $imageQuery->fetchAll(PDO::FETCH_COLUMN);

    header('Content-Type: application/json');
    echo json_encode($images);
    exit;
?>