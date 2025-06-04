<?php
require "../functions/dtbcon.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'], $_POST['image_id'])) {
    $postId = (int) $_POST['post_id'];
    $imageId = (int) $_POST['image_id'];

    try {
        $pdo->beginTransaction();

        // Get the image path for the selected image
        $stmt = $pdo->prepare("
            SELECT i.path 
            FROM image i
            JOIN postimage pi ON pi.image_id = i.id
            WHERE pi.post_id = ? AND pi.image_id = ?
        ");
        $stmt->execute([$postId, $imageId]);
        $image = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($image) {
            $imagePath = $image['path'];

            // Remove this specific relationship
            $stmt = $pdo->prepare("DELETE FROM postimage WHERE post_id = ? AND image_id = ?");
            $stmt->execute([$postId, $imageId]);

            // Remove the image record from the image table
            $stmt = $pdo->prepare("DELETE FROM image WHERE id = ?");
            $stmt->execute([$imageId]);

            // Optionally delete the image file from disk
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            
            $pdo->commit();
            echo "Selected image deleted.";
        } else {
            echo "Image not found." . $postId . $imageId;
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        http_response_code(500);
        echo "Error: " . $e->getMessage();
    }
} else {
    http_response_code(400);
    echo "Invalid request.";
}
?>
