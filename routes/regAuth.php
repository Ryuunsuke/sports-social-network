<?php

header('Content-Type: text/html; charset=utf-8');

require_once '../functions/utilities.php';
require "../functions/dtbcon.php";

$response = ['success' => false, 'message' => 'Unknown error'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = isset($_POST['REmail']) ? clean($_POST['REmail']) : null;
    $pass = isset($_POST['RPSW']) ? clean($_POST['RPSW']) : null;
    $name = isset($_POST['name']) ? clean($_POST['name']) : null;
    $surname = isset($_POST['surname']) ? clean($_POST['surname']) : null;
    $activity = isset($_POST['at']) ? clean($_POST['at']) : null;
    $dob = isset($_POST['dob']) ? clean($_POST['dob']) : null;
    $country = isset($_POST['country']) ? clean($_POST['country']) : null;
    $province = isset($_POST['province']) ? clean($_POST['province']) : null;
    $town = isset($_POST['town']) ? clean($_POST['town']) : null;

    date_default_timezone_set('Europe/Madrid');
    $register_date = date('Y-m-d H:i:s');

    // Check if email already exists
    $sql = "SELECT * FROM user WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':email' => $email]);

    $exist = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($exist) {
        $response['message'] = 'Email already exists';
    } else {
        // User not found, insert new user
        $insertSql = "INSERT INTO user (email, password, name, surname, birth_date, preferred_activity_id, town_id, role_id, unsubscribe_date, register_date) 
                      VALUES (:email, :password, :name, :surname, :birth_date, :preferred_activity_id, :town_id, :role_id, :unsubscribe_date, :register_date)";

        $insertStmt = $pdo->prepare($insertSql);

        $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

        $success = $insertStmt->execute([
            ':email' => $email,
            ':password' => $hashedPass,
            ':name' => $name,
            ':surname' => $surname,
            ':birth_date' => $dob,
            ':preferred_activity_id' => $activity,
            ':town_id' => $town,
            ':role_id' => 2,
            ':unsubscribe_date' => NULL,
            ':register_date' => $register_date
        ]);

        if ($success) {
            $user_id = $pdo->lastInsertId();

            $defaultpfp = "INSERT INTO image (user_id, name, size, height, width, path)
                            VALUES (:user_id, :name, :size, :height, :width, :path)";
            $stmt = $pdo->prepare($defaultpfp);
            $stmt->execute([
                ':user_id' => $user_id,
                ':name' => "default",
                ':size' => 16865,
                ':height' => 300,
                ':width' => 300,
                ':path' => "../static/assets/img_6836ee1b0b130.png"
            ]);
            $image_id = $pdo->lastInsertId();

            $stmt = $pdo->prepare("INSERT INTO profileimage (user_id, image_id) 
                                    VALUES (:user_id, :image_id)");
            $stmt->execute([
                ':user_id' => $user_id,
                ':image_id' => $image_id
            ]);

            $response['success'] = true;
            $response['message'] = 'Account successfully registered';
        } else {
            $response['message'] = 'Failed to register account';
        }
    }
} else {
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
exit;
?>
