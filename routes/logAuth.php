<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

require_once '../functions/funcAuth.php';
require "../functions/dtbcon.php";

$response = ['success' => false, 'message' => 'Unknown error'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = isset($_POST['LEmail']) ? clean($_POST['LEmail']) : null;
    $pass = isset($_POST['LPSW']) ? clean($_POST['LPSW']) : null;

    // Find user by email
    $sql = "SELECT * FROM user WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verify password
        if (password_verify($pass, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role_id'];
            $response['success'] = true;
            $response['message'] = 'Logged in successfully';

        } else {
            $response['message'] = 'Password is not correct';
        }
    } else {
        $response['message'] = 'Email does not exist';
    }

} else {
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
exit;
?>
