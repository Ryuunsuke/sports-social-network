<?php

header('Content-Type: text/html; charset=utf-8');

require_once '../functions/utilities.php';
require "../functions/dtbcon.php";

$response = ['success' => false, 'message' => 'Unknown error'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = isset($_POST['REmail']) ? clean($_POST['REmail']) : null;
    $pass = isset($_POST['RPSW']) ? clean($_POST['RPSW']) : null;
    $name = isset($_POST['RName']) ? clean($_POST['RName']) : null;
    $surname = isset($_POST['RSurname']) ? clean($_POST['RSurname']) : null;
    $activity = isset($_POST['RAT']) ? clean($_POST['RAT']) : null;
    $dob = isset($_POST['RDOB']) ? clean($_POST['RDOB']) : null;
    $country = isset($_POST['RCountry']) ? clean($_POST['RCountry']) : null;
    $province = isset($_POST['RProvince']) ? clean($_POST['RProvince']) : null;
    $town = isset($_POST['RTown']) ? clean($_POST['RTown']) : null;

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
