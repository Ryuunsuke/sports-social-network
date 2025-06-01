<?php
    require "../functions/dtbcon.php";

    ini_set('display_errors', 1);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/error.log');

    class friendprocess
    {
        private $pdo;

        public function __construct()
        {
            global $pdo;
            $this->pdo = $pdo;
        }

        public function AddFriend($param)
        {
            $caller_id = isset($param['user_id']) ? $param['user_id'] : '';
            $friend_id = isset($param['friend_id']) ? $param['friend_id'] : '';

            $sql = "INSERT INTO friend (user_id, friend_id) VALUES (:user_id, :friend_id)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':user_id' => $caller_id, ':friend_id' => $friend_id]);

            if ($stmt->rowCount() > 0){
                $sql = "INSERT INTO friend (user_id, friend_id) VALUES (:user_id, :friend_id)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([':user_id' => $friend_id, ':friend_id' => $caller_id]);

                return [['message' => 'You are now friend to this user.']];
            } else {
                return [['message' => 'Failed to add friend.']];
            }
        }

        public function RemoveFriend($param)
        {
            $caller_id = isset($param['user_id']) ? $param['user_id'] : '';
            $friend_id = isset($param['friend_id']) ? $param['friend_id'] : '';

            $sql = "DELETE FROM friend WHERE user_id = :user_id AND friend_id = :friend_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':user_id' => $caller_id, ':friend_id' => $friend_id]);

            if ($stmt->rowCount() > 0){
                return [['message' => 'You are no longer friend to this user.']];
            } else {
                return [['message' => 'Failed to remove friend.']];
            }
        }

        public function checkiffriend($friend_id, $caller_id)
        {
            $sql = "SELECT * FROM friend
                    WHERE user_id = :user_id AND friend_id = :friend_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':friend_id' => $friend_id, ':user_id' => $caller_id]);

            return $stmt->rowCount() > 0 ? true : false;
        }

    }

    try {
        $server = new SoapServer(null, [
            'uri'=> 'http://myproject.local/ws/friendprocess.php'
        ]);
        $server->setClass('friendprocess');
        $server->handle();
    } catch (SOAPFault $f) {
        error_log($f->faultstring);
    }
?>