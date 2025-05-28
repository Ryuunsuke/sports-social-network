<?php
require "dtbcon.php";

if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];

    $sql = "SELECT at.name FROM activitytype at
            JOIN post p ON p.activity_type_id = at.id
            WHERE p.id = :post_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':post_id' => $post_id]);
    $atname = $stmt->fetch(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($atname);
    exit;
}

?>