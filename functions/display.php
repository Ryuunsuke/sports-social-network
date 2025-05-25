<?php
    session_start();
	require "dtbcon.php";

    $isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    
    if (isset($_GET['country_id'])) {
		$country_id = $_GET['country_id'];

		$sql = "SELECT * FROM province WHERE country_id = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$country_id]);
		$provinces = $stmt->fetchAll(PDO::FETCH_ASSOC);

		header('Content-Type: application/json');
		echo json_encode($provinces);
		exit;
	}

	// If AJAX request for towns
	if (isset($_GET['province_id'])) {
		$province_id = $_GET['province_id'];

		$sql = "SELECT * FROM town WHERE province_id = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$province_id]);
		$towns = $stmt->fetchAll(PDO::FETCH_ASSOC);

		header('Content-Type: application/json');
		echo json_encode($towns);
		exit;
	}

    //selecting country + province + town to store results
    $sql = "SELECT * FROM country";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$country = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$sql = "SELECT * FROM province";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$province = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$sql = "SELECT * FROM town";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$town = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$sql = "SELECT * FROM activitytype";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$AT = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>