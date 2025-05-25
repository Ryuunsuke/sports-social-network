<?php
    session_start();
    require "../functions/dtbcon.php";
    require "../functions/utilities.php";

    $response = ['success' => false, 'message' => 'Unknown error'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $name = isset($_POST['name']) ? clean($_POST['name']) : null;
        $surname = isset($_POST['surname']) ? clean($_POST['surname']) : null;
        $AT = isset($_POST['AT']) ? clean($_POST['AT']) : null;
        $dob = isset($_POST['dob']) ? clean($_POST['dob']) : null;
        $town = isset($_POST['town']) ? clean($_POST['town']) : null;

        // Find user by email
        $sql = "UPDATE user SET 
                    name = :name, 
                    surname = :surname, 
                    birth_date = :dob,
                    preferred_activity_id = :at,
                    town_id = :town
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':surname' => $surname,
            ':dob' => $dob,
            ':at' => $AT,
            ':town' => $town,
            ':id' => $_SESSION['user_id']
        ]);

        if ($stmt->rowCount()) {
            $response['success'] = true;
            $response['message'] = 'Updated successfully';
        } else {
            $response['message'] = 'Failed to update';
        }
    } else {
        $response['message'] = 'Invalid request method';
    }

    echo json_encode($response);
    exit;
?>