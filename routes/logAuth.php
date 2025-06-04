<?php
session_start();
session_regenerate_id(true); // generate new session ID at login attempt start
$_SESSION = [];   
header('Content-Type: application/json; charset=utf-8');

ini_set('display_errors', 0); // disable errors from being output
error_reporting(0);

require_once '../functions/utilities.php';
require "../functions/dtbcon.php";

$response = ['success' => false, 'message' => 'Unknown error'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = isset($_POST['LEmail']) ? clean($_POST['LEmail']) : null;
    $pass = isset($_POST['LPSW']) ? clean($_POST['LPSW']) : null;

    // Find user by email
    try {
        $sql = "SELECT * FROM user WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
        echo json_encode($response);
        exit;
    }

    if ($user) {
        // Verify password
        if ($user['unsubscribe_date'] != NULL){
            $response['message'] = 'Your account has been deregistered.';
        } else {
            if (password_verify($pass, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role_id'];
                $response['success'] = true;
                $response['message'] = 'Logged in successfully';
            } else {
                $response['message'] = 'Password is not correct';
            }
        }
    } else {
        $response['message'] = 'Email does not exist';
    }

} else {
    $response['message'] = 'Invalid request method';
}

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
exit;
?>
