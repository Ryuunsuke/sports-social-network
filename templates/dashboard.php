<?php
  session_start();
  require "../functions/dtbcon.php";
  require "../routes/posts.php";
  require "../routes/nav.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../static/css/post.css">
  <link rel="stylesheet" href="../static/css/nav.css">
  <link rel="stylesheet" href="../static/css/indexbootstrap.css">
  <link rel="stylesheet" href="../static/css/main.css">
  <link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
  />
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
    <section id="title-header" class="title-header">
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
    <!-- end greeting user -->
    <!-- Post Prompt button -->
    <div class="prompt mt-4 mb-4">
      <div class="text-center">
        <button class="genbtn btn-primary" onclick="window.location.href='routeplan.php'">Post something</button>
      </div>
    </div>
    <!-- end prompt   -->

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.7.0/gpx.min.js" defer></script>

    <h3 class="text-xl font-semibold mb-4" style="color: white;">Posts</h3>
    <!-- Posts Section Starts Here -->
		<h2 id="js-error" style="padding: 20px;">Loading...</h2>
		<?php if ($allposts): ?>
			<?php foreach ($allposts as $post): ?>
				<div class="boxwrapper" data-post-id="<?= htmlspecialchars($post['id']) ?>">

					<!-- Top Section -->
					<div class="top-s">
						<div class="top-info">
							<div class="profile-picture">
								<img src="<?php echo htmlspecialchars($post['path']); ?>" alt="Profile picture" style="width: 100px; height: 100px; border-radius: 50%; border: 3px solid white;">
							</div>
							<div class="top-title">
								<div class="profile-name">
									<a href="#"><?= htmlspecialchars($post['name']) ?> <?= htmlspecialchars($post['surname']) ?> </a>
								</div>
								<small><?= htmlspecialchars($post['post_date']) ?></small>
							</div>
						</div>
						<div class="post-content">
							<strong><?= htmlspecialchars($post['title']) ?></strong><br />
							Activity Type: <?= htmlspecialchars($post['activity_type_name']) ?><br />
							Partner(s): <?= htmlspecialchars($post['partners'] !== null ? $post['partners'] : 'None') ?><br />
						</div>
					</div>

					<!-- GPX Map Display -->
          <div class="boxwrapper">
              <div id="map-<?= $post['id'] ?>" style="height: 500px;"></div>
          </div>

        <!-- Pictures -->
        <div class="prompt mt-4 mb-4">
            <div class="text-center">
                <!-- Images -->
              <div class="post-images">
                <div class="post-images">
                  <div id="post-images-container-<?= $post['id'] ?>"></div>
                </div>
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
							</div>
						</div>
						<div class="bottom-part">
							<button class="like-btn" onclick="likePost(<?= $post['id'] ?>)">
								<div class="react">👏🏿</div>
								<span>Applaude</span>
							</button>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
  </div>

  <script>
		const postMapIds = <?= json_encode(array_column($allposts, 'id')) ?>;

		document.addEventListener("DOMContentLoaded", function () {
			postMapIds.forEach(postId => {
				const mapId = "map-" + postId;
				const mapContainer = document.getElementById(mapId);
				if (!mapContainer) return;

        L.Icon.Default.mergeOptions({
          iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
          iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
          shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
        });

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
			const postIds = <?= json_encode(array_column($allposts, 'id')) ?>;

			postIds.forEach(postId => {
				loadImages(postId);
				loadLikes(postId);
			});
		});

		async function likePost(postId) {
			const userId = <?= $_SESSION['user_id'] ?>;

			try {
				const response = await fetch('../routes/likepost.php', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded',
					},
					body: `post_id=${postId}&user_id=${userId}`
				});

				if (response.ok) {
					// Wait for the like to be saved before updating the UI
					loadLikes(postId);
				} else {
					console.error('Failed to like post.');
				}
			} catch (error) {
				console.error('Error:', error);
			}
		}

	</script>      

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.7.0/gpx.min.js"></script>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

	<script src="https://unpkg.com/@studio-freight/lenis"></script>	
	<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
	<script src="https://unpkg.com/lenis@1.1.14/dist/lenis.min.js"></script>
	
	<script src="../static/js/nav.js"></script>

</body>
</html>
