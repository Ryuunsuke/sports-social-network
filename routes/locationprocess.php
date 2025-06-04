<?php
require "../functions/dtbcon.php"; 
require "../functions/utilities.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $level = isset($input['level']) ? $input['level'] : 'country';
    $action = isset($input['action']) ? $input['action'] : 'add';

    $country = isset($input['country']) ? clean($input['country']) : null;
    $province = isset($input['province']) ? clean($input['province']) : null;
    $town = isset($input['town']) ? clean($input['town']) : null;

    if ($action === 'add') {
        if ($country && $province && $town) {
            try {

                // Country: check if exists, else insert
                $sql = "SELECT id FROM country WHERE name = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$country]);
                $countryresult = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($countryresult) {
                    $country_id = $countryresult['id'];
                } else {
                     
                    $sql = "INSERT INTO country (name) VALUES (?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$country]);
                    $country_id = $pdo->lastInsertId();
                     
                }

                // Province: check if exists, else insert
                $sql = "SELECT id FROM province WHERE name = ? AND country_id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$province, $country_id]);
                $provinceresult = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($provinceresult) {
                    $province_id = $provinceresult['id'];
                } else {
                     
                    $sql = "INSERT INTO province (name, country_id) VALUES (?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$province, $country_id]);
                    $province_id = $pdo->lastInsertId();
                     
                }

                // Town: check if exists, else insert
                $sql = "SELECT id FROM town WHERE name = ? AND province_id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$town, $province_id]);
                $townresult = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$townresult) {
                     
                    $sql = "INSERT INTO town (name, province_id) VALUES (?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$town, $province_id]);
                     
                    echo json_encode(["status" => "success", "message" => "Location saved."]);
                } else {
                    echo json_encode(["status" => "error", "message" => "All fields already existed."]);
                }

            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => htmlspecialchars($e->getMessage())]);
            }
        }
    } else if ($action === 'delete') {
        try {
            $level = isset($input['level']) ? $input['level'] : 'country';

            if ($level === 'town' && $country && $province && $town) {
                // Get province ID
                $stmt = $pdo->prepare("SELECT id FROM province WHERE name = ? AND country_id = (SELECT id FROM country WHERE name = ?)");
                $stmt->execute([$province, $country]);
                $provinceResult = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($provinceResult) {
                     
                    $province_id = $provinceResult['id'];
                    $stmt = $pdo->prepare("DELETE FROM town WHERE name = ? AND province_id = ?");
                    $stmt->execute([$town, $province_id]);
                    resetAutoIncrementToMaxId($pdo, 'town');
                     
                    echo json_encode(["status" => "success", "message" => "Town deleted."]);
                    return;
                }

            } else if ($level === 'province' && $country && $province) {
                // Get country ID
                $stmt = $pdo->prepare("SELECT id FROM country WHERE name = ?");
                $stmt->execute([$country]);
                $countryResult = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($countryResult) {
                    $country_id = $countryResult['id'];
                    // Delete all towns in province
                    $stmt = $pdo->prepare("SELECT id FROM province WHERE name = ? AND country_id = ?");
                    $stmt->execute([$province, $country_id]);
                    $provinceResult = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($provinceResult) {
                         
                        $province_id = $provinceResult['id'];
                        $pdo->prepare("DELETE FROM town WHERE province_id = ?")->execute([$province_id]);
                        resetAutoIncrementToMaxId($pdo, 'town');
                        $pdo->prepare("DELETE FROM province WHERE id = ?")->execute([$province_id]);
                        resetAutoIncrementToMaxId($pdo, 'province');
                         
                        echo json_encode(["status" => "success", "message" => "Province and its towns deleted."]);
                        return;
                    }
                }

            } else if ($level === 'country' && $country) {
                // Get country ID
                $stmt = $pdo->prepare("SELECT id FROM country WHERE name = ?");
                $stmt->execute([$country]);
                $countryResult = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($countryResult) {
                    $country_id = $countryResult['id'];
                    // Delete all towns and provinces under this country
                    $stmt = $pdo->prepare("SELECT id FROM province WHERE country_id = ?");
                    $stmt->execute([$country_id]);
                    $provinces = $stmt->fetchAll(PDO::FETCH_ASSOC);
                     
                    foreach ($provinces as $province) {
                        $pdo->prepare("DELETE FROM town WHERE province_id = ?")->execute([$province['id']]);
                    }
                    resetAutoIncrementToMaxId($pdo, 'town');
                    $pdo->prepare("DELETE FROM province WHERE country_id = ?")->execute([$country_id]);
                    resetAutoIncrementToMaxId($pdo, 'province');
                    $pdo->prepare("DELETE FROM country WHERE id = ?")->execute([$country_id]);
                    resetAutoIncrementToMaxId($pdo, 'country');
                     
                    echo json_encode(["status" => "success", "message" => "Country and all related provinces and towns deleted."]);
                    return;
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Could not find entries to delete."]);
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