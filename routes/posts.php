<?php
require "../functions/dtbcon.php";

$query = $pdo->prepare("
    SELECT 
        p.id, p.user_id, u.name, u.surname, i.path, p.title, p.post_date, p.route_id,
        at.name AS activity_type_name,
        GROUP_CONCAT(DISTINCT 
            CASE 
                WHEN atp.post_id = p.id THEN partner.name 
                ELSE 'None'
            END
            SEPARATOR ', '
        ) AS partners
    FROM post p
    JOIN user u ON p.user_id = u.id
    JOIN activitytype at ON p.activity_type_id = at.id

    -- join friends of the post owner
    LEFT JOIN friend f2 ON f2.user_id = u.id
    LEFT JOIN user partner ON f2.friend_id = partner.id

    -- join activitypartner, only for partners that match the post activity
    LEFT JOIN activitypartner atp 
        ON atp.user_id = partner.id 
        AND atp.post_id = p.id

    JOIN profileimage pfp ON pfp.user_id = u.id
    JOIN image i ON pfp.image_id = i.id

    WHERE p.user_id = :user_id OR p.user_id IN (
        SELECT friend_id FROM friend WHERE user_id = :user_id
    )
    -- Only include partners who actually have activitypartner entry for the post's activity
    AND atp.user_id IS NOT NULL

    GROUP BY p.id
    ORDER BY p.post_date DESC
");
$query->execute([':user_id' => $_SESSION['user_id']]);
$allposts = $query->fetchAll(PDO::FETCH_ASSOC);
?>
