<?php
require "../functions/dtbcon.php";
require "../functions/utilities.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $level = isset($input['level']) ? $input['level'] : 'country';

    $country = isset($input['country']) ? clean($input['country']) : null;
    $province = isset($input['province']) ? clean($input['province']) : null;
    $town = isset($input['town']) ? clean($input['town']) : null;

    try {
        if ($level === 'country') {
            if ($country) {
                $stmt = $pdo->prepare("SELECT id FROM country WHERE name = ?");
                $stmt->execute([$country]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result) {
                    echo json_encode(["status" => "success", "message" => "Country exists."]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Country does not exist."]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Country is required."]);
            }

        } elseif ($level === 'province') {
            if ($country && $province) {
                // Get country ID
                $stmt = $pdo->prepare("SELECT id FROM country WHERE name = ?");
                $stmt->execute([$country]);
                $countryData = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($countryData) {
                    $stmt = $pdo->prepare("SELECT id FROM province WHERE name = ? AND country_id = ?");
                    $stmt->execute([$province, $countryData['id']]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($result) {
                        echo json_encode(["status" => "success", "message" => "Province exists."]);
                    } else {
                        echo json_encode(["status" => "error", "message" => "Province does not exist."]);
                    }
                } else {
                    echo json_encode(["status" => "error", "message" => "Country does not exist."]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Country and province are required."]);
            }

        } elseif ($level === 'town') {
            if ($country && $province && $town) {
                // Get country ID
                $stmt = $pdo->prepare("SELECT id FROM country WHERE name = ?");
                $stmt->execute([$country]);
                $countryData = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($countryData) {
                    // Get province ID
                    $stmt = $pdo->prepare("SELECT id FROM province WHERE name = ? AND country_id = ?");
                    $stmt->execute([$province, $countryData['id']]);
                    $provinceData = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($provinceData) {
                        $stmt = $pdo->prepare("SELECT id FROM town WHERE name = ? AND province_id = ?");
                        $stmt->execute([$town, $provinceData['id']]);
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($result) {
                            echo json_encode(["status" => "success", "message" => "Town exists."]);
                        } else {
                            echo json_encode(["status" => "error", "message" => "Town does not exist."]);
                        }
                    } else {
                        echo json_encode(["status" => "error", "message" => "Province does not exist."]);
                    }
                } else {
                    echo json_encode(["status" => "error", "message" => "Country does not exist."]);
                }

            } else {
                echo json_encode(["status" => "error", "message" => "Country, province, and town are required."]);
            }

        } else {
            echo json_encode(["status" => "error", "message" => "Unknown action."]);
        }

    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Server error: " . htmlspecialchars($e->getMessage())]);
    }
}
?>
