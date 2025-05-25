<?php
session_start();

header('Content-Type: application/json; charset=utf-8');

require "../functions/dtbcon.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['croppedImage'])) {
    $file = $_FILES['croppedImage'];

    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        die("Upload failed with error code " . $file['error']);
    }

    $size = $file['size'];
    $tmpPath = $file['tmp_name'];
    $originalName = $file['name']; // get original name with extension
    $extension = pathinfo($originalName, PATHINFO_EXTENSION); // extract extension

    // Get image dimensions
    $imageInfo = getimagesize($tmpPath);
    if ($imageInfo === false) {
        die("Uploaded file is not a valid image.");
    }
    $width = $imageInfo[0];
    $height = $imageInfo[1];

    // Save directory
    $uploadDir = realpath(__DIR__ . '/../static/assets/');

    if (file_exists($uploadDir) && !is_dir($uploadDir)) {
        // A file exists where a directory should be
        die("Error: '$uploadDir' exists as a file, not a directory.");
    }

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // To avoid overwriting, generate unique file name
    $uniqueName = uniqid('img_') . '.' . $extension;
    $targetPath = $uploadDir . DIRECTORY_SEPARATOR . $uniqueName;

    if (move_uploaded_file($tmpPath, $targetPath)) {
        // Store relative path for web use
        $relativePath = '../static/assets/' . $uniqueName;

        // Insert into DB
        $sql = "INSERT INTO image (user_id, name, size, height, width, path) VALUES (:user_id, :name, :size, :height, :width, :path)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $_SESSION['user_id'],
            ':name' => $uniqueName,
            ':size' => $size,
            ':height' => $height,
            ':width' => $width,
            ':path' => $relativePath,
        ]);
        $image_id = $pdo->lastInsertId();
        
        $sql = "INSERT INTO profileimage (user_id, image_id) VALUES (:user_id, :image_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $_SESSION['user_id'],
            ':image_id' => $image_id
        ]);

        echo "Cropped image uploaded and saved successfully!";
    } else {
        echo "Failed to move uploaded file.";
    }
} else {
    echo "No image uploaded.";
}
?>