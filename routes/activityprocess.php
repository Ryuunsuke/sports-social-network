<?php
require "../functions/dtbcon.php"; 
require "../functions/utilities.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = isset($input['action']) ? $input['action'] : 'add';

    $at = isset($input['activity']) ? clean($input['activity']) : null;

    if ($action === 'add') {
        if ($at) {
            try {

                // Country: check if exists, else insert
                $sql = "SELECT id FROM activitytype WHERE name = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$at]);
                $atResult = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$atResult) {
                     
                    $sql = "INSERT INTO activitytype (name) VALUES (?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$at]);
                     
                    echo json_encode(["status" => "success", "message" => "Activity saved."]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Activity already existed."]);
                }

            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => htmlspecialchars($e->getMessage())]);
            }
        }
    } else if ($action === 'delete') {
        try {
                $stmt = $pdo->prepare("SELECT id FROM activitytype WHERE name = ?");
                $stmt->execute([$at]);
                $atResult = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($atResult) {
                    $at_id = $atResult['id'];

                    $pdo->prepare("DELETE FROM activitytype WHERE id = ?")->execute([$at_id]);
                    resetAutoIncrementToMaxId($pdo, 'activitytype');
                     
                    echo json_encode(["status" => "success", "message" => "Activity deleted."]);
                    return;
                }else{
                    echo json_encode(["status" => "error", "message" => "Activity does not exist."]);
                    return;
                }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => htmlspecialchars($e->getMessage())]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Cannot recognize action."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Please fill in all fields."]);
}

function resetAutoIncrementToMaxId(PDO $pdo, string $tableName, string $idColumn = 'id') {
    try {
        // Get the max ID in the table
        $stmt = $pdo->prepare("SELECT MAX($idColumn) AS max_id FROM $tableName");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $maxId = $result['max_id'] ?? 0;
        if ($maxId === null) {
            $maxId = 0;  // no rows
        }

        // If no rows, reset to 1
        $nextAutoIncrement = ($maxId !== null && $maxId > 0) ? ($maxId + 1) : 1;

        // Reset auto_increment
        $pdo->exec("ALTER TABLE $tableName AUTO_INCREMENT = $nextAutoIncrement");

        return true;

    } catch (Exception $e) {
        // Log or handle error
        return false;
    }
}
?>