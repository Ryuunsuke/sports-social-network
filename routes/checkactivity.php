<?php
require "../functions/dtbcon.php";
require "../functions/utilities.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $at = isset($input['activity']) ? clean($input['activity']) : null;

    try {
        if ($at) {
            $stmt = $pdo->prepare("SELECT id FROM activitytype WHERE name = ?");
            $stmt->execute([$at]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                echo json_encode(["status" => "success", "message" => "Activity exists."]);
            } else {
                echo json_encode(["status" => "error", "message" => "Activity does not exist."]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Activity is required."]);
        }
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Server error: " . htmlspecialchars($e->getMessage())]);
    }
}
?>
