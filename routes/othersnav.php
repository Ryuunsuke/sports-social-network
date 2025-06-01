<?php
    require "../functions/dtbcon.php";
    
    $user_id = $_GET['id'];

    $sql = "SELECT name FROM user WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $name = $stmt->fetch()['name'];

    $sql = "SELECT friend_id FROM friend WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $friendcount = $stmt->rowCount();

    $sql = "SELECT id FROM post WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $postcount = $stmt->rowCount();

    // Query to get the image path of the user's profile picture
    $sql = "SELECT i.path FROM profileimage p
            JOIN image i ON p.image_id = i.id
            WHERE p.user_id = :user_id
            ORDER BY i.id DESC
            LIMIT 1";  // get latest profile image

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $image = $stmt->fetch();

    $imagePath = $image ? $image['path'] : '../static/assets/defaultpfp.jpg';  // fallback default image
?>