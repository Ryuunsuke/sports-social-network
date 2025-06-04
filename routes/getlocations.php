<?php
require "../functions/dtbcon.php";

header('Content-Type: application/json');

if (isset($_GET['country'])) {

    $country = trim($_GET['country']);
    
    // Get country ID
    $stmt = $pdo->prepare("SELECT id FROM country WHERE id = ?");
    $stmt->execute([$country]);
    $country_id = $stmt->fetchColumn();

    if ($country_id) {
        $stmt = $pdo->prepare("SELECT name FROM province WHERE country_id = ? ORDER BY name ASC");
        $stmt->execute([$country_id]);
        $provinces = $stmt->fetchAll(PDO::FETCH_COLUMN);

        echo json_encode($provinces);
    } else {
        echo json_encode([]);
    }
} else if (isset($_GET['province'])) {
    $province = trim($_GET['province']);
    
    // Get province ID
    $stmt = $pdo->prepare("SELECT id FROM province WHERE name = ?");
    $stmt->execute([$province]);
    $province_id = $stmt->fetchColumn();

    if ($province_id) {
        $stmt = $pdo->prepare("SELECT name FROM town WHERE province_id = ? ORDER BY name ASC");
        $stmt->execute([$province_id]);
        $towns = $stmt->fetchAll(PDO::FETCH_COLUMN);

        echo json_encode($towns);
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}
?>