<?php
	require "../functions/display.php";
	require "../routes/nav.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Leaflet Route Planner</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../static/css/post.css">
    <link rel="stylesheet" href="../static/css/nav.css">
    <link rel="stylesheet" href="../static/css/indexbootstrap.css">
    <link rel="stylesheet" href="../static/css/main.css">
    <link rel="stylesheet" href="../static/css/imagelayout.css">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    
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
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == "1"): ?>
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

        <div class="prompt mt-4 mb-4">
            <div class="text-center">
                <button class="genbtn btn-primary" onclick="window.location.href='dashboard.php'">Back</button>
            </div>
        </div>

        <!-- startofmap -->
        <div class="page-container">
            <div class="boxwrapper">
                <form method="post" action="../routes/postprocess.php" enctype="multipart/form-data">
                    <!-- Activity Type -->
                    <label for="at"><b>Activity Type</b></label><br>
                    <select id="at" name="at" required>
                        <option value="">--Select--</option>
                        <?php if ($AT): ?>
                            <?php foreach ($AT as $atItem): ?>
                                <option value="<?= htmlspecialchars($atItem['id']) ?>">
                                    <?= htmlspecialchars($atItem['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select><br>

                    <!-- Title -->
                    <input name="title" placeholder="Title" type="text" required /><br>

                    <!-- Route -->
                    <label for="route"><b>Select a route</b></label><br>
                    <select name="route_id" id="route" required>
                        <option value="">--Select a route--</option>
                        <?php if ($routes): ?>
                            <?php foreach ($routes as $route): ?>
                                <option value="<?= htmlspecialchars($route['id']) ?>"
                                    <?= (isset($_GET['route_id']) && $_GET['route_id'] == $route['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($route['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select><br>

                    <!-- GPX Map Display -->
                    <div class="boxwrapper">
                        <div id="map" style="height: 500px;"></div>
                    </div>
                    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.7.0/gpx.min.js"></script>
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            L.Icon.Default.mergeOptions({
                                iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
                                iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png'
                            });

                            const customGpxOptions = {
                                async: true,
                                marker_options: {
                                    startIconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.7.0/pin-icon-start.png',
                                    endIconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.7.0/pin-icon-end.png',
                                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.7.0/pin-shadow.png'
                                }
                            };

                            let gpxLayer;
                            const map = L.map('map').setView([0, 0], 2);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '© OpenStreetMap contributors'
                            }).addTo(map);

                            function loadGPX(routeId) {
                                if (!routeId) return;

                                fetch("../functions/getgpx.php?id=" + routeId)
                                    .then(res => res.text())
                                    .then(gpxText => {
                                        if (gpxLayer) {
                                            map.removeLayer(gpxLayer);
                                        }
                                        gpxLayer = new L.GPX(gpxText, customGpxOptions)
                                            .on('loaded', function(e) {
                                                map.fitBounds(e.target.getBounds());
                                            })
                                            .addTo(map);
                                    });
                            }

                            // Initial load if route_id is present in URL
                            const initialRouteId = <?= isset($_GET['route_id']) ? intval($_GET['route_id']) : 'null' ?>;
                            if (initialRouteId) {
                                loadGPX(initialRouteId);
                            }

                            // Add event listener for changes
                            document.getElementById('route').addEventListener('change', function () {
                                const selectedRouteId = this.value;
                                loadGPX(selectedRouteId);
                            });
                        });
                    </script>

                    <!-- Pictures -->
                    <div class="prompt mt-4 mb-4">
                        <div class="text-center">
                            <input type="file" name="inputImage[]" id="inputImage" accept="image/*" style="display: none;" multiple />
                            <button class="genbtn btn-primary" id="addpicturebutton" type="button">
                                (Optional) Add pictures here (maximum 3)
                            </button>
                            <div id="imageContainer" class="image-preview-container"></div>
                        </div>
                    </div>

                    <!-- Partners -->
                    <label for="partner"><b>(Optional) Choose partners for activity</b></label><br>

                    <select id="partner" name="partner[]" multiple style="width: 300px; height: 100px; overflow-y: scroll;">
                        <?php if (!empty($partners)): ?>
                            <option disabled>--Select partners from your added friends--</option>
                            <?php foreach ($partners as $partner): ?>
                                <option value="<?= htmlspecialchars($partner['id']) ?>">
                                    <?= htmlspecialchars($partner['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option disabled>You have no friends</option>
                        <?php endif; ?>
                    </select><br>

                    <!-- Submit -->
                    <button class="genbtn btn-primary" type="submit">Post</button>
                </form>
            </div>
        </div>
    </div>
    
    

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://unpkg.com/@studio-freight/lenis"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
    <script src="https://unpkg.com/lenis@1.1.14/dist/lenis.min.js"></script>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.7.0/gpx.min.js"></script>

    <script src="../static/js/nav.js"></script>
    <script src="../static/js/imagehandler.js"></script>
</body>
</html>
