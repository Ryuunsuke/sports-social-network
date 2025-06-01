<?php
    session_start();
    require "../functions/dtbcon.php";

    ini_set('display_errors', 1);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/error.log');

    class searchengine
    {
        private $pdo;

        public function __construct()
        {
            global $pdo;
            $this->pdo = $pdo;
        }

        public function SearchByNameAndSurname($param)
        {
            $name = isset($param['name']) ? $param['name'] : '';
            $surname = isset($param['surname']) ? $param['surname'] : '';
            $caller_id = isset($param['user_id']) ? $param['user_id'] : '';
            
            $sql = "SELECT u.id, i.path, u.name, u.surname FROM user u
                    JOIN profileimage p ON p.user_id = u.id
                    JOIN image i ON i.id = p.image_id
                    WHERE u.name = :name and u.surname = :surname";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':name' => $name, ':surname' => $surname]);

            if ($stmt->rowCount() > 0){
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($users as &$user) {
                    $user['isFriend'] = $this->checkiffriend($user['id'], $caller_id);
                    $user['lastActivity'] = $this->lastactivity($user['id']);
                }
                return $users;
            } else {
                return [['message' => 'There is no user']];
            }
        }

        public function checkiffriend($friend_id, $caller_id)
        {
            $sql = "SELECT * FROM friend
                    WHERE user_id = :user_id AND friend_id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $friend_id, ':user_id' => $caller_id]);

            return $stmt->rowCount() > 0 ? true : false;
        }

        public function lastactivity($id)
        {
            $sql = "SELECT a.name FROM post p
                    JOIN activitytype a ON a.id = p.activity_type_id
                    WHERE user_id = :id
                    ORDER BY p.post_date DESC
                    LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $lastActivity = $stmt->fetch(PDO::FETCH_ASSOC);

            return $lastActivity ? $lastActivity['name'] : "None";
        }

        public function displayFriends($param)
        {
            $caller_id = isset($param['user_id']) ? $param['user_id'] : '';
            
            $sql = "SELECT u.id, i.path, u.name, u.surname FROM user u
                    JOIN profileimage p ON p.user_id = u.id
                    JOIN image i ON i.id = p.image_id
                    JOIN friend f ON f.friend_id = u.id
                    WHERE f.user_id = :user_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':user_id' => $caller_id]);

            if ($stmt->rowCount() > 0){
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($users as &$user) {
                    $user['isFriend'] = $this->checkiffriend($user['id'], $caller_id);
                    $user['lastActivity'] = $this->lastactivity($user['id']);
                }
                return $users;
            } else {
                return [['message' => 'You have no friend']];
            }
        }
    }

    try {
        $server = new SoapServer(null, [
            'uri'=> 'http://myproject.local/ws/searchengine.php'
        ]);
        $server->setClass('searchengine');
        $server->handle();
    } catch (SOAPFault $f) {
        error_log($f->faultstring);
    }
?>