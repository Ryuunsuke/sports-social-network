<?php
	require "display.php";

	$user_id = $_GET['id'];
	//retrieving user's information from database
    $sql = "SELECT * FROM user WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
	
	$query = $pdo->prepare("
		SELECT p.id, p.user_id, p.title, p.post_date, p.route_id,
			at.name AS activity_type_name
		FROM post p
		JOIN user u ON p.user_id = u.id
		JOIN activitytype at ON p.activity_type_id = at.id
		WHERE u.id = :user_id
		ORDER BY p.post_date DESC
	");
	$query->execute([':user_id' => $user_id]);
	$theirposts = $query->fetchAll(PDO::FETCH_ASSOC);

	$surname = $user['surname'];
	$dob = $user['birth_date'];
	$chosen_at_id = $user['preferred_activity_id'];
	$chosen_town_id = $user['town_id'];
	$register_date = $user['register_date'];

	//extracting names from id
	function findByID($array, $id) {
		foreach ($array as $item) {
			if ($item['id'] === $id) 
				return [
					'id' => $item['id'],
					'name' => $item['name']
				];
		}
		return null;
	}

	function getLinkID($array, $id, $link_key) {
		foreach ($array as $item) {
			if ($item['id'] === $id) {
				return $item[$link_key];
			}
		}
		return null;
	}

	$chosen_at = findByID($AT, $chosen_at_id);

	$chosen_town = findByID($town, $chosen_town_id);
	
	$chosen_province = findByID($province, getLinkID($town, $chosen_town_id, 'province_id'));
	$chosen_country = findByID($country, getLinkID($province, $chosen_province['id'], 'country_id'));

?>