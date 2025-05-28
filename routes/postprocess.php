<?php
require "../functions/dtbcon.php";
session_start(); // Required if you're using $_SESSION

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    // Sanitize input values
    $activityType = htmlspecialchars($_POST['at']);
    $title = htmlspecialchars($_POST['title']);
    $routeId = intval($_POST['route_id']);
    $partnerId = !empty($_POST['partner']) ? intval($_POST['partner']) : null;

    $postDate = date('Y-m-d H:i:s');

    // Insert into post table
    $sql = "INSERT INTO post (user_id, title, post_date, activity_type_id, route_id) 
            VALUES (:user_id, :title, :post_date, :activity_type_id, :route_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':user_id' => $userId,
        ':title' => $title,
        ':post_date' => $postDate,
        ':activity_type_id' => $activityType,
        ':route_id' => $routeId
    ]);
    $postId = $pdo->lastInsertId();

    // Insert activity partner if selected
    if ($partnerId) {
        $sql = "INSERT INTO activitypartner (post_id, user_id) VALUES (:post_id, :user_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':post_id' => $postId,
            ':user_id' => $partnerId
        ]);
    }

    echo "<pre>";
    print_r($_FILES['inputImage']);
    echo "</pre>";

    // Handle image uploads
    if (!empty($_FILES['inputImage']['name'][0])) {
        foreach ($_FILES['inputImage']['name'] as $index => $name) {
            $tmpPath = $_FILES['inputImage']['tmp_name'][$index];
            $size = $_FILES['inputImage']['size'][$index];
            $originalName = $_FILES['inputImage']['name'][$index];
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);

            $imageInfo = getimagesize($tmpPath);
            if ($imageInfo === false) {
                echo "Skipped file: $originalName is not a valid image.<br>";
                continue;
            }

            $uploadDir = realpath(__DIR__ . '/../static/assets/');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // ==== Replace your move_uploaded_file() block with this ====

            // Load image from temp path depending on mime type
            switch ($imageInfo['mime']) {
                case 'image/jpeg':
                    $srcImage = imagecreatefromjpeg($tmpPath);
                    break;
                case 'image/png':
                    $srcImage = imagecreatefrompng($tmpPath);
                    break;
                case 'image/gif':
                    $srcImage = imagecreatefromgif($tmpPath);
                    break;
                default:
                    echo "Unsupported image type: $originalName<br>";
                    continue 2; // skip this file
            }

            if (!$srcImage) {
                echo "Failed to create image from file: $originalName<br>";
                continue;
            }

            $width = 500;
            $height = 500;
            $dstImage = imagecreatetruecolor($width, $height);

            if ($imageInfo['mime'] == 'image/png' || $imageInfo['mime'] == 'image/gif') {
                imagecolortransparent($dstImage, imagecolorallocatealpha($dstImage, 0, 0, 0, 127));
                imagealphablending($dstImage, false);
                imagesavealpha($dstImage, true);
            }

            imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $width, $height, $imageInfo[0], $imageInfo[1]);

            $uniqueName = uniqid('img_') . '.' . $extension;
            $targetPath = $uploadDir . DIRECTORY_SEPARATOR . $uniqueName;

            switch ($imageInfo['mime']) {
                case 'image/jpeg':
                    imagejpeg($dstImage, $targetPath, 90);
                    break;
                case 'image/png':
                    imagepng($dstImage, $targetPath);
                    break;
                case 'image/gif':
                    imagegif($dstImage, $targetPath);
                    break;
            }

            imagedestroy($srcImage);
            imagedestroy($dstImage);

            $relativePath = '../static/assets/' . $uniqueName;

            $stmt = $pdo->prepare("INSERT INTO image (user_id, name, size, height, width, path) 
                                VALUES (:user_id, :name, :size, :height, :width, :path)");
            $stmt->execute([
                ':user_id' => $userId,
                ':name' => $uniqueName,
                ':size' => filesize($targetPath),
                ':height' => $height,
                ':width' => $width,
                ':path' => $relativePath,
            ]);
            $imageId = $pdo->lastInsertId();

            $stmt = $pdo->prepare("INSERT INTO postimage (post_id, image_id) VALUES (:post_id, :image_id)");
            $stmt->execute([
                ':post_id' => $postId,
                ':image_id' => $imageId
            ]);
        }
    }

    echo "Activity posted successfully.";
    header("Location: ../templates/dashboard.php");
    exit;
} else {
    echo "No data received.";
    header("Location: ../templates/routeplan.php");
    exit;
}
?>
