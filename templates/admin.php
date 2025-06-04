<?php
	require "../functions/display.php";
	require "../routes/nav.php";
    require "../routes/friendsearch.php";

    function formatOptionsJS($dataArray) {
        $options = [];
        foreach ($dataArray as $item) {
            $options[] = [
                'value' => $item['id'],
                'text' => $item['name']
            ];
        }
        return json_encode($options);
    }

    $countryOptions = formatOptionsJS($country);
    $provinceOptions = formatOptionsJS($province);
    $townOptions = formatOptionsJS($town);
    $atOptions = formatOptionsJS($AT);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../static/css/nav.css">
  <link rel="stylesheet" href="../static/css/indexbootstrap.css">
  <link rel="stylesheet" href="../static/css/main.css">
  <link rel="stylesheet" href="../static/css/sidebar.css">
  <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
  <link rel="stylesheet" href="../static/css/adminuserdisplay.css">

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

    <div class="container-fluid mt-5 pt-5">
        <section id="title-header" class="title-header" style="text-align: center">
			<header class="section-header">
				<h3><span>
					Manage Auxiliary Data
				</span></h3>
			</header>
			<hr/>
		</section>

        <div class="row">
            
            <!-- Left Sidebar: Buttons -->
            <div class="col-12 col-md-2 mb-4">
                <div class="d-flex flex-column gap-2">
                    <button id="addLocalities" class="btn btn-primary active" onclick="showForm('id01')">Add Localities</button>
                    <button id="addActivity" class="btn btn-primary" onclick="showForm('id02')">Add Activities</button>
                    <button id="userManage" class="btn btn-primary" onclick="showForm('id03')">Manage Users</button>
                    <button id="postManage" class="btn btn-primary" onclick="showForm('id04')">Manage Posts</button>
                </div>
            </div>

            <!-- Center Content: Message and results -->
            <div class="col-12 col-md-7 mb-4">
                <!-- management -->
                <div class="boxwrapper">

                    <form id="id01" class="localmodal" method="POST">
                        <label for="country"><b>Country</b></label><br>
                        <input id="country" name="country" placeholder="Type or select a country" autocomplete="off" required><br>
                        
                        <label for="province"><b>Province</b></label><br>
                        <input id="province" name="province" placeholder="Type or select a province" autocomplete="off" disabled><br>

                        <label for="town"><b>Town</b></label><br>
                        <input id="town" name="town" placeholder="Type or select a town" autocomplete="off" disabled><br>
                        
                        <button type="button" id="resetButton1" class="genbtn btn-primary">Reset Selection</button>
                        <button type="submit" id="addButton1" name="action" value="add" style="display:none;" class="genbtn btn-primary">Add</button>
                        <button type="submit" id="deleteButton1" name="action" value="delete" style="display:none;" class="genbtn btn-primary">Delete</button>
                    </form>

                    <form id="id02" class="activitymodal" method="POST">
                        <label for="at"><b>Activity</b></label><br>
                        <input id="at" name="at" placeholder="Type or select an activity" autocomplete="off" required><br>
                                    
                        <button type="button" id="resetButton2" class="genbtn btn-primary">Reset Selection</button>
                        <button type="submit" id="addButton2" name="action" value="add" style="display:none;" class="genbtn btn-primary">Add</button>
                        <button type="submit" id="deleteButton2" name="action" value="delete" style="display:none;" class="genbtn btn-primary">Delete</button>
                    </form>

                    <div id="id03" class="user-list"></div>

                    <div id="id04" class="post-list"></div>

                </div>
            </div>
            <!-- Right Sidebar: Forms -->
            <div class="col-12 col-md-3">
                <div class="boxwrapper">
                    <!-- Message log -->
                    <div id="messagecontainer">
                        <label for="messageLog"><b>Message Log</b></label><br>
                        <div id="messageLog"></div>
                    </div>
                    <!-- Search input -->
                    <div id="usersearchcontainer">
                        <label for="usersearch"><b>Search for user</b></label><br>
                        <form id="usersearch" class="searchmodal" method="POST">
                            <input type="text" name="name" id="name" value="" placeholder="Name"/> 
                            <input type="text" name="surname" id="surname" value="" placeholder="Surname"/> 
                            <input type="submit" value="Find" id="findButton"/>
                        </form>
                    </div>
                    
                    <!-- Delete Form -->
                    <form id="deleteForm" method="POST" style="display:none;">
                        <input type="hidden" name="action" value="delete" />
                        <div class="mb-3">
                            <label for="deleteId" class="form-label">ID to Delete:</label>
                            <input type="number" class="form-control" id="deleteId" name="id" required min="1" />
                        </div>
                        <button type="submit" class="btn btn-danger w-100">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- lenis scroll -->
    <script src="https://unpkg.com/@studio-freight/lenis"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/ScrollTrigger.min.js"></script>
    <script src="https://unpkg.com/lenis@1.1.14/dist/lenis.min.js"></script>

    <script src="../static/js/nav.js"></script>

    <!-- tom select -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    <script>
        function showForm(action) {
            // Hide all forms
            document.getElementById('id01').style.display = 'none';
            document.getElementById('id02').style.display = 'none';
            document.getElementById('id03').style.display = 'none';
            document.getElementById('id04').style.display = 'none';

            // Show chosen form
            document.getElementById(action).style.display = 'block';

            // Show messageLog only if action is id01 or id02
            const messageLog = document.getElementById('messagecontainer');
            const usersearch = document.getElementById('usersearchcontainer');
            if (action === 'id01' || action === 'id02') {
                messageLog.style.display = 'block';
                usersearch.style.display = 'none';
            } else if (action === 'id03' || action === 'id04') {
                messageLog.style.display = 'none';
                usersearch.style.display = 'block';
            } else {
                messageLog.style.display = 'none';
                usersearch.style.display = 'none';
            }

            // Button active state
            ['addLocalities', 'addActivity', 'userManage', 'postManage'].forEach(id => {
                const btn = document.getElementById(id);
                if(id === action) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }

        window.onload = function() {
            showForm('id01');
        };

        const selectData = {
            country: <?php echo $countryOptions; ?>,
            province: [], 
            town: []
        };
        const atData = <?php echo $atOptions; ?>; 
  </script>

  <script src="../static/js/locationprocess.js"></script>

  <script src="../static/js/activityprocess.js"></script>
  <script src="../static/js/adminusersearch.js"></script>
  <script src="../static/js/adminpostview.js"></script>
</body>
</html>
