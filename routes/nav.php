<?php
    require "../functions/dtbcon.php";
    
    $user_id = $_SESSION['user_id'] ?? null;

    $sql = "SELECT unsubscribe_date FROM user WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $dereg = $stmt->fetch();
        if ($dereg && !empty($dereg['unsubscribe_date'])) {
                echo "<script>
                        alert('Your account has been deregistered.');
                        window.location.href = '../index.php';
                </script>";
                $_SESSION = [];
                session_destroy();
                exit();
        }

    if (!$user_id) {
        echo "<script>
            alert('You must be logged in to access this page.');
            window.location.href = '../index.php';
        </script>";
        exit();
    }

    $sql = "SELECT name FROM user WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $name = $stmt->fetch()['name'];

    $sql = "SELECT friend_id FROM friend WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $friendcount = $stmt->rowCount();

    $sql = "SELECT id FROM post WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $postcount = $stmt->rowCount();

    $sql = "SELECT i.path FROM profileimage p
            JOIN image i ON p.image_id = i.id
            WHERE p.user_id = :user_id
            ORDER BY i.id DESC
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $image = $stmt->fetch();

    $imagePath = $image ? $image['path'] : '../static/assets/defaultpfp.jpg';

    $sql = "SELECT u.id, u.name FROM user u
            JOIN friend f ON f.friend_id = u.id
            WHERE f.user_id = :user_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $partners = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>