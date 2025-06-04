<?php
	require "../functions/userinfodisplay.php";
	require "../routes/nav.php";
	require "../routes/friendsearch.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Friends Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../static/css/nav.css">
  <link rel="stylesheet" href="../static/css/indexbootstrap.css">
  <link rel="stylesheet" href="../static/css/main.css">
  <link rel="stylesheet" href="../static/css/friends.css">
</head>
<body>
<!-- navbar -->
	<div id="navbar">
		<nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
			<div class="container px-4 px-lg-5">
				<a class="navbar-brand" href="#page-top">Sportbook</a>
				<button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
					Menu
					<i class="fas fa-bars"></i>
				</button>
				<div class="collapse navbar-collapse" id="navbarResponsive">
					<ul class="navbar-nav ms-auto">
						<li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
						<li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
						<li class="nav-item"><a class="nav-link" href="friends.php">Friends</a></li>
						<?php if ($_SESSION['user_role'] == "1"): ?>
						<li class="nav-item"><a class="nav-link" href="admin.php">Administration</a></li>
						<?php endif; ?>
						<li class="nav-item"><a class="nav-link" href="../routes/logout.php">Logout</a></li>
					</ul>
				</div>
			</div>
		</nav>
	</div>
	<!-- end nav -->
		
	<!-- greeting user -->
	<div class="page-container"><br/>
		<section id="title-header" class="title-header" style="text-align: center">
			<header class="section-header">
				<h3>Hi, <span>
					<?php echo $name; ?>
				</span></h3>
			</header>
			<div class="title-container">
			<hr color="white" />
				<div class="row counters">
					<!-- Left: Friends -->
					<div class="col-lg-4 col-4 text-center">
						<span data-toggle="counter-up" id="friends-count" style="color: white;">
							<?php echo $friendcount; ?>
						</span>
						<p style="color: white;">Friends</p>
					</div>
				
					  <!-- Middle: Profile Picture -->
					<div class="col-lg-4 col-4 text-center">
						<div class="profile-picture">
							<img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Profile picture" style="width: 100px; height: 100px; border-radius: 50%; border: 3px solid white;">
						</div>
					</div>
				
					  <!-- Right: Posts -->
					<div class="col-lg-4 col-4 text-center">
						<span data-toggle="counter-up" id="posts-count" style="color: white;">
							<?php echo $postcount; ?>
						</span>
						<p style="color: white;">No. of Posts</p>
					</div>  
				</div>
			<hr/>
		</section>

    <!-- search prompt -->
    <h3 class="text-xl font-semibold mb-4" style="color: white;">Find friends</h3>
    <div class="boxwrapper">
			<div class="bg-white rounded-lg p-6">
				<div class="space-y-4">
					<div class="flex items-center space-x-4">
						<div class="w-full space-y-3 pl-6">
							<form method="post" action="">
								<input type="text" name="name" id="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" placeholder="Name"/> 
								<input type="text" name="surname" id="surname" value="<?php echo isset($_POST['surname']) ? htmlspecialchars($_POST['surname']) : ''; ?>" placeholder="Surname"/> 
								<input type="submit" value="Find" name="Find"/>
							</form>
							<div id="searchResults" class="user-list">
								<?php
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
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
    <!-- end prompt   -->

    <!-- show friends -->
    <h3 class="text-xl font-semibold mb-4" style="color: white;">My friends</h3>
    <div class="boxwrapper">
			<div class="bg-white rounded-lg p-6">
				<div class="space-y-4">
					<div class="flex items-center space-x-4">
						<div class="w-full space-y-3 pl-6">
              				<div id="friendsGrid" class="grid"></div>
							<?php 
								if (isset($_POST['Add']) || isset($_POST['Remove'])) {
									renderFriends($user_id);
								} else {
									renderFriends($user_id);
								}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
    <!-- end show friends -->
  </div>

  <script src="../static/js/friends.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

  <script src="https://unpkg.com/@studio-freight/lenis"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/ScrollTrigger.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
  <script src="https://unpkg.com/lenis@1.1.14/dist/lenis.min.js"></script>

  <script src="../static/js/nav.js"></script>

</body>
</html>
