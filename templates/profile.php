<?php
	require "../functions/userinfodisplay.php";
	require "../routes/nav.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profile Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../static/css/post.css">
  <link rel="stylesheet" href="../static/css/nav.css">
  <link rel="stylesheet" href="../static/css/indexbootstrap.css">
  <link rel="stylesheet" href="../static/css/main.css">
  <link rel="stylesheet" href="../static/css/cropper.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

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
						<li class="nav-item"><a class="nav-link" href="friends.html">Friends</a></li>
						<?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == "1"): ?>
						<li class="nav-item"><a class="nav-link" href="admin.html">Administration</a></li>
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
		<!-- change profile prompt button -->
		<section id="startofcrop">
			<div class="prompt mt-4 mb-4">
				<div class="text-center">
					<!-- Hidden file input -->
					<input type="file" id="inputImage" accept="image/*" style="display:none;" />

					<!-- Custom button -->
					<button class="genbtn btn-primary" id="changepfpButton" type="button">Change profile picture</button>

					<div>
						<img id="image" style="width: 500px; height: 500px; display: none;">
					</div>

					<div style="display: flex; justify-content: space-between; width: 100%;">
						<button class="genbtn btn-primary" id="acceptcropButton" style="display:none;">Accept and Upload</button>
						<button class="genbtn btn-primary" id="cancelcropButton" style="display:none;">Cancel</button>
					</div>	
				</div>
			</div>
		</section>	
		
		<!-- end prompt   -->
		<!-- description -->
		<h3 class="text-xl font-semibold mb-4" style="color: white;">Profile description</h3>
		<div class="boxwrapper">
			<div class="bg-white rounded-lg p-6">
				<div class="space-y-4">
					<div class="flex items-center space-x-4">
						<div class="w-full space-y-3 pl-6">
							<form class="profile-content animate" id="profilemodal" method="POST">
								<div>
									<label for="name" class="block text-sm font-medium text-gray-700">Name</label>
									<input type="text" id="name" name="name" value="<?php echo $name ?>" required/>
								</div>
								<div>
									<label for="surname" class="block text-sm font-medium text-gray-700">Surname</label>
									<input type="text" id="surname" name="surname" value="<?php echo $surname ?>" required/>
								</div>
								<div>
									<label for="activity" class="block text-sm font-medium text-gray-700">Preferred Activity Type</label>
									<br>
									<select name="AT" id="AT" required>
										<!-- Display the selected ActivityType -->
										<option value="<?php echo htmlspecialchars($chosen_at['id']); ?>" selected>
											<?php echo htmlspecialchars($chosen_at['name']); ?>
										</option>

										<!-- Loop through all ActivityTypes, excluding the chosen one -->
										<?php if ($AT): ?>
											<?php foreach ($AT as $ATs): ?>
												<?php if ($ATs['id'] != $chosen_at['id']): ?>
													<option value="<?php echo htmlspecialchars($ATs['id']); ?>">
														<?php echo htmlspecialchars($ATs['name']); ?>
													</option>
												<?php endif; ?>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
									<br>
								</div>
								<div>
									<label for="dob" class="block text-sm font-medium text-gray-700">Date of Birth</label>
									<input type="date" id="dob" name="dob" value="<?php echo $dob ?>" required/>
								</div>
								<div>
									<label for="country" class="block text-sm font-medium text-gray-700">Country</label>
									<br>
									<select name="country" id="country" required>
										<!-- Display the selected country -->
										<option value="<?php echo htmlspecialchars($chosen_country['id']); ?>" selected>
											<?php echo htmlspecialchars($chosen_country['name']); ?>
										</option>

										<!-- Loop through countries, excluding the selected one -->
										<?php foreach ($country as $countries): ?>
											<?php if ($countries['id'] != $chosen_country['id']): ?>
												<option value="<?php echo htmlspecialchars($countries['id']); ?>">
													<?php echo htmlspecialchars($countries['name']); ?>
												</option>
											<?php endif; ?>
										<?php endforeach; ?>
									</select>
									<br>
								</div>
								<div>
									<label for="province" class="block text-sm font-medium text-gray-700">Province</label>
									<br>
									<select name="province" id="province" required>
										<!-- Display the selected country -->
										<option value="<?php echo htmlspecialchars($chosen_province['id']); ?>" selected>
											<?php echo htmlspecialchars($chosen_province['name']); ?>
										</option>

										<!-- Loop through countries, excluding the selected one -->
										<?php foreach ($province as $provinces): ?>
											<?php if ($provinces['id'] != $chosen_province['id']): ?>
												<option value="<?php echo htmlspecialchars($provinces['id']); ?>">
													<?php echo htmlspecialchars($provinces['name']); ?>
												</option>
											<?php endif; ?>
										<?php endforeach; ?>
									</select>
									<br>
								</div>
								<div>
									<label for="town" class="block text-sm font-medium text-gray-700">Town</label>
									<br>
									<select name="town" id="town" required>
										<!-- Display the selected country -->
										<option value="<?php echo htmlspecialchars($chosen_town['id']); ?>" selected>
											<?php echo htmlspecialchars($chosen_town['name']); ?>
										</option>

										<!-- Loop through countries, excluding the selected one -->
										<?php foreach ($town as $towns): ?>
											<?php if ($towns['id'] != $chosen_town['id']): ?>
												<option value="<?php echo htmlspecialchars($towns['id']); ?>">
													<?php echo htmlspecialchars($towns['name']); ?>
												</option>
											<?php endif; ?>
										<?php endforeach; ?>
									</select>
									<br>
								</div>
								<div class="text-center">
									<button type="submit" class="genbtn btn-primary" id="saveProfileBtn">Save profile</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- end description -->

		<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script>
		<script src="https://rawcdn.githack.com/mpetazzoni/leaflet-gpx/master/gpx.js" defer></script>

		<!-- Posts Section Starts Here -->
		<h3 class="text-xl font-semibold mb-4" style="color: white;">Your posts</h3>
		<h2 id="js-error" style="padding: 20px;">Loading...</h2>
		<?php if ($myposts): ?>
			<?php foreach ($myposts as $post): ?>
				<div class="boxwrapper" data-post-id="<?= htmlspecialchars($post['id']) ?>">

					<!-- Top Section -->
					<div class="top-s">
						<div class="top-info">
							<div class="profile-picture">
								<img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Profile picture" style="width: 100px; height: 100px; border-radius: 50%; border: 3px solid white;">
							</div>
							<div class="top-title">
								<div class="profile-name">
									<a href="#"><?= htmlspecialchars($name) ?> <?= htmlspecialchars($surname) ?> </a>
								</div>
								<small><?= htmlspecialchars($post['post_date']) ?></small>
							</div>
						</div>
						<div class="post-content">
							<strong><?= htmlspecialchars($post['title']) ?></strong><br />
							Activity Type: <?= htmlspecialchars($post['activity_type_name']) ?><br />
							Date: <?= htmlspecialchars($post['post_date']) ?>
						</div>
					</div>

					<!-- GPX Map Display -->
                    <div class="boxwrapper">
                        <div id="map-<?= $post['id'] ?>" style="height: 500px;"></div>
                    </div>

					<script>
						const postMapIds = <?= json_encode(array_column($myposts, 'id')) ?>;

						document.addEventListener("DOMContentLoaded", function () {
							postMapIds.forEach(postId => {
								const mapId = "map-" + postId;
								const mapContainer = document.getElementById(mapId);
								if (!mapContainer) return;

								const map = L.map(mapId).setView([0, 0], 2);
								L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
									attribution: '© OpenStreetMap contributors'
								}).addTo(map);

								const gpxOptions = {
									async: true,
									marker_options: {
										startIconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.7.0/pin-icon-start.png',
										endIconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.7.0/pin-icon-end.png',
										shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.7.0/pin-shadow.png'
									}
								};

								fetch("../functions/getpostgpx.php?id=" + postId)
									.then(res => res.text())
									.then(gpxText => {
										const gpxLayer = new L.GPX(gpxText, gpxOptions)
											.on('loaded', function(e) {
												map.fitBounds(e.target.getBounds());
											})
											.addTo(map);
									});
							});
						});
					</script>

                    <!-- Pictures -->
                    <div class="prompt mt-4 mb-4">
                        <div class="text-center">
                            <!-- Images -->
							<div class="post-images">
								<div class="post-images">
									<div id="post-images-container-<?= $post['id'] ?>"></div>
								</div>
								<script>
									const postId = <?= json_encode($post['id']) ?>;

									function loadImages(postId) {
										fetch('../functions/getpicperpost.php?post_id=' + postId)
											.then(response => response.json())
											.then(images => {
												const container = document.getElementById('post-images-container-' + postId);
												container.innerHTML = '';

												if (images.length === 0) {
													container.innerHTML = '<p>No images for this post.</p>';
													return;
												}

												images.forEach(src => {
													const imgDiv = document.createElement('div');
													imgDiv.className = 'post-image';

													const img = document.createElement('img');
													img.src = src;
													img.alt = 'Post Image';
													img.style.maxWidth = '100%';
													img.style.height = 'auto';
													img.style.marginBottom = '10px';

													imgDiv.appendChild(img);
													container.appendChild(imgDiv);
												});
											})
											.catch(err => {
												console.error('Error loading images:', err);
											});
									}

									document.addEventListener('DOMContentLoaded', () => {
										loadImages(postId);
									});
								</script>
							</div>
                        </div>
                    </div>

					<!-- Like Section -->
					<div class="like-section">
						<div class="top-part">
							<div class="left-part">
								<div class="react">👏🏿</div>
								<div class="id-name">
									<div id="post-likes-container-<?= $post['id'] ?>"></div>
								</div>
								<script>
									function loadLikes(postId) {
										fetch('../functions/getlikeperpost.php?post_id=' + postId)
											.then(response => response.json())
											.then(data => {

												const container = document.getElementById('post-likes-container-' + postId);
												if (!container) return;

												container.textContent = data.text;
											})
											.catch(err => {
												console.error('Error loading likes:', err);
											});
									}

									document.addEventListener('DOMContentLoaded', () => {
										loadLikes(postId);
									});
								</script>
							</div>
						</div>
						<div class="bottom-part">
							<form method="POST" action="../routes/likepost.php">
								<input type="hidden" name="post_id" value="<?= $post['id'] ?>">
								<input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
								<button type="submit" class="like-btn">
									<div class="react">👏🏿</div>
									<span>Applaude</span>
								</button>
							</form>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
 	</div>

	<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.7.0/gpx.min.js"></script>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

	<script src="https://unpkg.com/@studio-freight/lenis"></script>	
	<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
	<script src="https://unpkg.com/lenis@1.1.14/dist/lenis.min.js"></script>
	
	<script src="../static/js/nav.js"></script>

	<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.js"></script>
	<script src="../static/js/imagecrop.js"></script>

	<script src="../static/js/profileupdate.js"></script>
</body>
</html>