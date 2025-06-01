<form method="post" action="">
    <input type="text" name="name" id="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" placeholder="Name"/> 
    and 
    <input type="text" name="surname" id="surname" value="<?php echo isset($_POST['surname']) ? htmlspecialchars($_POST['surname']) : ''; ?>" placeholder="Surname"/> 
    <input type="submit" value="Find" name="Find"/>
</form>

<?php 
session_start();

$user_id = $_SESSION['user_id'];

function searchAndRender($name, $surname, $user_id) {
    try {
        $client = new SoapClient(null, [
            'location' => 'http://myproject.local/ws/searchengine.php',
            'uri'      => 'http://myproject.local/ws/searchengine.php',
            'trace'    => 1,
            'exceptions' => true
        ]);

        $parameters = array('name' => $name, 'surname' => $surname, 'user_id' => $user_id);
        $result = $client->__soapCall('SearchByNameAndSurname', [ $parameters ]);

        echo "<pre>";
        print_r($result);
        echo "</pre>";

        echo "<h3>Search Results:</h3>";

        foreach ($result as $user) {
            echo "<div style='margin-bottom: 10px'>";
            echo htmlspecialchars($user['name'] . ' ' . $user['surname']) . " - Last activity: " . htmlspecialchars($user['lastActivity']) . "<br>";
            if (!isset($user['id']) || $user['id'] == $user_id) continue;
            echo '<form method="post">';
            echo '<input type="hidden" name="friend_id" value="' . $user['id'] . '">';
            echo '<input type="hidden" name="name" value="' . htmlspecialchars($name) . '">';
            echo '<input type="hidden" name="surname" value="' . htmlspecialchars($surname) . '">';
            if ($user['isFriend']) {
                echo '<input type="submit" name="Remove" value="Remove Friend">';
            } else {
                echo '<input type="submit" name="Add" value="Add Friend">';
            }
            echo '</form>';
            echo "</div>";
        }

    } catch (SoapFault $e) {
        echo 'SOAP Error: ' . $e->getMessage();
    }
}

// Handle add/remove first
if (isset($_POST['Add']) || isset($_POST['Remove'])) {
    try {
        $friend_id = $_POST['friend_id'];
        $name = $_POST['name'];
        $surname = $_POST['surname'];

        $client = new SoapClient(null, [
            'location' => 'http://myproject.local/ws/friendprocess.php',
            'uri'      => 'http://myproject.local/ws/friendprocess.php',
            'trace'    => 1,
            'exceptions' => true
        ]);

        $parameters = array('user_id' => $user_id, 'friend_id' => $friend_id);

        if (isset($_POST['Add'])) {
            $response = $client->__soapCall('AddFriend', [ $parameters ]);
        } else {
            $response = $client->__soapCall('RemoveFriend', [ $parameters ]);
        }

        echo "<pre>";
        print_r($response);
        echo "</pre>";

        // Refresh the search results with the last searched name and surname
        searchAndRender($name, $surname, $user_id);

    } catch (SoapFault $e) {
        echo 'SOAP Error: ' . $e->getMessage();
    }

} elseif (isset($_POST['Find'])) {
    // Initial Find request
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    searchAndRender($name, $surname, $user_id);
}
?>