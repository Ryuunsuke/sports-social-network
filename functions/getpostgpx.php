<?php
    require "../functions/dtbcon.php";

    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    $stmt = $pdo->prepare("
        SELECT r.gpx_file
        FROM post p
        JOIN route r ON p.route_id = r.id
        WHERE p.id = ?
    ");
    
    $stmt->execute([$id]);
    $route = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($route) {
        $path = $route['gpx_file'];
        if (file_exists($path)) {
            header("Content-Type: application/gpx+xml");
            readfile($path);
        } else {
            http_response_code(404);
            echo "File not found.";
        }
    } else {
        http_response_code(404);
        echo "Route not found.";
    }
?>