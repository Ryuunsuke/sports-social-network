<?php
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

			echo "<div style='display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px;'>";

			foreach ($result as $user) {
				if (!isset($user['id']) || $user['id'] == $user_id) continue;

				echo "<div style='border: 1px solid #ccc; padding: 10px; border-radius: 8px; text-align: center;'>";

				// Display profile image
				if (isset($user['path']) && !empty($user['path'])) {
					echo "<img src='" . htmlspecialchars($user['path']) . "' alt='Profile Picture' style='width: 100px; height: 100px; object-fit: cover; border-radius: 50%; margin-bottom: 10px;'>";
				} else {
					echo "<div style='width: 100px; height: 100px; background: #eee; border-radius: 50%; margin: 0 auto 10px;'></div>";
				}

				// Display name and last activity
				echo "<strong>" . htmlspecialchars($user['name']) . " " . htmlspecialchars($user['surname']) . "</strong><br>";
				echo "<small>Last activity: " . htmlspecialchars($user['lastActivity']) . "</small><br><br>";

				// Add/Remove friend form
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

			echo "</div>";

		} catch (SoapFault $e) {
			echo 'SOAP Error: ' . $e->getMessage();
		}
	}
    function renderFriends($user_id) {
        try {
            $client = new SoapClient(null, [
				'location' => 'http://myproject.local/ws/searchengine.php',
				'uri'      => 'http://myproject.local/ws/searchengine.php',
				'trace'    => 1,
				'exceptions' => true
			]);
            $parameters = array('user_id' => $user_id);
            $result = $client->__soapCall('displayFriends', [ $parameters ]);

            echo "<div style='display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px;'>";
            foreach ($result as $user) {
				if (!isset($user['id']) || $user['id'] == $user_id) continue;

				echo "<div style='border: 1px solid #ccc; padding: 10px; border-radius: 8px; text-align: center;'>";

				// Display profile image
				if (isset($user['path']) && !empty($user['path'])) {
					echo "<img src='" . htmlspecialchars($user['path']) . "' alt='Profile Picture' style='width: 100px; height: 100px; object-fit: cover; border-radius: 50%; margin-bottom: 10px;'>";
				} else {
					echo "<div style='width: 100px; height: 100px; background: #eee; border-radius: 50%; margin: 0 auto 10px;'></div>";
				}

				// Display name and last activity
				$profileUrl = "../templates/othersprofile.php?id=" . urlencode($user['id']);
                echo "<strong><a href='$profileUrl' style='text-decoration: none; color: inherit;'>" . 
                    htmlspecialchars($user['name']) . " " . htmlspecialchars($user['surname']) . 
                    "</a></strong><br>";
				echo "<small>Last activity: " . htmlspecialchars($user['lastActivity']) . "</small><br><br>";

				// Add/Remove friend form
				echo '<form method="post">';
				echo '<input type="hidden" name="friend_id" value="' . $user['id'] . '">';
				echo '<input type="hidden" name="name" value="' . $user['name'] . '">';
				echo '<input type="hidden" name="surname" value="' . $user['surname'] . '">';
				if ($user['isFriend']) {
					echo '<input type="submit" name="Remove" value="Remove Friend">';
				} else {
					echo '<input type="submit" name="Add" value="Add Friend">';
				}
				echo '</form>';

				echo "</div>";
			}

			echo "</div>";

        } catch (SoapFault $e) {
			echo 'SOAP Error: ' . $e->getMessage();
		}
    }
?>