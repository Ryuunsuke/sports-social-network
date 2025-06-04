<?php
	require "./functions/display.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Sportbook</title>
	<!-- Fonts-->
	<script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
	<link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet" />
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
    <link rel="stylesheet" href="./static/css/index.css">
	<link rel="stylesheet" href="./static/css/indexbootstrap.css">
	<link rel="stylesheet" href="./static/css/signup.css">
	<!-- <link rel="stylesheet" href="../static/css/nav.css"> -->
</head>
	<body id="page-top">
		<!-- Navigation -->
		<nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
			<div class="container px-4 px-lg-5">
				<a class="navbar-brand" href="#page-top">Sportbook</a>
				<button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
					Menu
					<i class="fas fa-bars"></i>
				</button>
				<div class="collapse navbar-collapse" id="navbarResponsive">
					<ul class="navbar-nav ms-auto">
						<li class="nav-item"><a class="nav-link" href="#template">Template</a></li>
						<li class="nav-item"><a class="nav-link" href="#about">About</a></li>
						<li class="nav-item"><a class="nav-link" href="#signup">Login/SignUp</a></li>
					</ul>
				</div>
			</div>
		</nav>
		<!-- end nav -->
		<!-- title -->
		<div class="sportbook">
			<section class="sportbook__header">
				<div class="sportbook__visuals">
					<div class="sportbook__black-line-overflow"></div>
					<div data-sportbook-layers class="sportbook__layers">
						<img src="https://cdn.prod.website-files.com/671752cd4027f01b1b8f1c7f/6717795be09b462b2e8ebf71_osmo-parallax-layer-3.webp" loading="eager" width="800" data-sportbook-layer="1" alt="" class="sportbook__layer-img">
						<img src="https://cdn.prod.website-files.com/671752cd4027f01b1b8f1c7f/6717795b4d5ac529e7d3a562_osmo-parallax-layer-2.webp" loading="eager" width="800" data-sportbook-layer="2" alt="" class="sportbook__layer-img">
						<div data-sportbook-layer="3" class="sportbook__layer-title">
							<h2 class="sportbook__title">Sportbook</h2>
						</div>
						<img src="https://cdn.prod.website-files.com/671752cd4027f01b1b8f1c7f/6717795bb5aceca85011ad83_osmo-parallax-layer-1.webp" loading="eager" width="800" data-sportbook-layer="4" alt="" class="sportbook__layer-img">
					</div>
					<div class="sportbook__fade"></div>
				</div>
			</section>
			<section class="sportbook__content">
				<h2 class="sportbook__title2">Sportbook</h2>
			</section>
		</div>
		<!-- end title -->
		<!-- About-->
		<section class="about-section text-center" id="template">
			<div class="container px-4 px-lg-5">
				<div class="row gx-4 gx-lg-5 justify-content-center">
					<div class="col-lg-8">
						<h2 class="text-white mb-4">Built with Bootstrap 5</h2>
						<p class="text-white-50">
							Grayscale is a free Bootstrap theme created by Start Bootstrap. It can be yours right now, simply download the template on
							<a href="https://startbootstrap.com/theme/grayscale/">the preview page.</a>
							The theme is open source, and you can use it for any purpose, personal or commercial.
						</p>
					</div>
				</div>
				<img class="img-fluid" src="../static/assets/img/ipad.png" alt="..." />
			</div>
		</section>
		<!-- Projects-->
		<section class="projects-section bg-light" id="about">
			<div class="container px-4 px-lg-5">
				<!-- Featured Project Row-->
				<div class="row gx-0 mb-4 mb-lg-5 align-items-center">
					<div class="col-xl-8 col-lg-7"><img class="img-fluid mb-3 mb-lg-0" src="../static/assets/runningcoup.jpg" alt="..." /></div>
					<div class="col-xl-4 col-lg-5">
						<div class="featured-text text-center text-lg-left">
							<h4>Sport Social Network</h4>
							<p class="text-black-50 mb-0">Sportbook connects sport enthusiastics together</p>
						</div>
					</div>
				</div>
				<!-- Project One Row-->
				<div class="row gx-0 mb-5 mb-lg-0 justify-content-center">
					<div class="col-lg-6"><img class="img-fluid" src="../static/assets/mountainjog.jpg" alt="..." /></div>
					<div class="col-lg-6">
						<div class="bg-black text-center h-100 project">
							<div class="d-flex h-100">
								<div class="project-text w-100 my-auto text-center text-lg-left">
									<h4 class="text-white">Locations</h4>
									<p class="mb-0 text-white-50">Anywhere your legs can reach</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Project Two Row-->
				<div class="row gx-0 justify-content-center">
					<div class="col-lg-6"><img class="img-fluid" src="../static/assets/dreamjog.jpg" alt="..." /></div>
					<div class="col-lg-6 order-lg-first">
						<div class="bg-black text-center h-100 project">
							<div class="d-flex h-100">
								<div class="project-text w-100 my-auto text-center text-lg-right">
									<h4 class="text-white">Start Running</h4>
									<p class="mb-0 text-white-50">Push yourself over the limit</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Sign up-->
		<section class="signup-section" id="signup">
			<div class="container px-4 px-lg-5">
				<div class="row gx-4 gx-lg-5">
					<div class="col-md-10 col-lg-8 mx-auto text-center">
						<!-- Button -->
						<div class="signup-wrap">
							<button>
								<span style="font-weight: bold;">Sign Up</span>
							</button>
						</div>

						<!-- Background -->
						<svg style="position: absolute; width: 100%; height: 100%; z-index: 0;" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
							<defs>
								<pattern id="dottedGrid" width="30" height="30" patternUnits="userSpaceOnUse">
									<circle cx="2" cy="2" r="1" fill="rgba(0,0,0,0.15)" />
								</pattern>
							</defs>
							<rect width="100%" height="100%" fill="url(#dottedGrid)" />
						</svg>
					</div>
				</div>
			</div>
			<!-- modals -->
			<div id="id01" class="loginmodal">
				<form class="loginmodal-content animate" id="logmodal" method="POST">
					<div class="imgcontainer">
						<span onclick="closemodals()" class="close" title="Close Modal">&times;</span>
					</div>
				
					<div class="container">
						<label for="uname"><b>Email</b></label>
						<input type="email" id="LEmail" placeholder="Enter Email" name="LEmail" required>
					
						<label for="psw"><b>Password</b></label>
						<input type="password" id="LPSW" placeholder="Enter Password" name="LPSW" required>
							
						<span class="register">Don't have an account? <a style="text-decoration: underline; cursor: pointer;" onclick="showRegister()">Register</a></span>
					</div>
				
					<div class="container" style="background-color:#FFFF">
						<button type="button" onclick="closemodals()" class="cancelbtn">Cancel</button>
						<button type="submit" class="loginbtn">Login</button>
					</div>
					
				</form>
			</div>
	
			<div id="id02" class="registermodal">
				<form class="registermodal-content animate" id="regmodal" method="POST">
					<div class="imgcontainer">
						<span onclick="closemodals()" class="close" title="Close Modal">&times;</span>
					</div>
				
					<div class="container">
						<label for="registeremail"><b>Email</b></label>
						<br>
						<input type="email" id="REmail" placeholder="Enter Email" name="REmail" required>
						<br>

						<label for="registerpsw"><b>Password</b></label>
						<input type="password" id="RPSW" placeholder="Enter Password" name="RPSW" required minlength="6" maxlength="12">

						<label for="registername"><b>Name</b></label>
						<input type="text" id="name" name="name" placeholder="Enter Name">

						<label for="registersurname"><b>Surname</b></label>
						<input type="text" id="surname" name="surname" placeholder="Enter Surname">

						<label for="registeractivity"><b>Prefered Activity Type</b></label>
						<br>
						<select id="at" name="at" required>
							<option value="">--Select--</option>
							<?php if ($AT): ?>
								<?php foreach ($AT as $atItem): ?>
									<option value="<?php echo htmlspecialchars($atItem['id']); ?>">
										<?php echo htmlspecialchars($atItem['name']); ?>
									</option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
						<br>

						<label for="registerdob"><b>Date Of Birth</b></label>
						<br>
						<input type="date" id="dob" name="dob" placeholder="Enter Date of Birth" onchange="checkAge()">
						<br>
						
						<label for="registercountry"><b>Country</b></label>
						<br>
						<select id="country" name="country" required>
							<option value="">--Select--</option>
							<?php foreach ($country as $countries): ?>
								<option value="<?php echo htmlspecialchars($countries['id']); ?>">
									<?php echo htmlspecialchars($countries['name']); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<br>
						
						<label for="registerprovince"><b>Province</b></label>
						<br>
						<select id="province" name="province" required>
							<option value="">--Select Country First--</option>
							<option value="Example">Example</option>
						</select>
						<br>

						<label for="registertown"><b>Town</b></label>
						<br>
						<select id="town" name="town" required>
							<option value="">--Select Province First--</option>
							<option value="Example">Example</option>
						</select>
						<br>
							
						<span class="register">Already have an account? <a style="text-decoration: underline; cursor: pointer;" onclick="showLogin()">Login</a></span>
					</div>
				
					<div class="container" style="background-color:#FFFF">
						<button type="button" onclick="closemodals()" class="cancelbtn">Cancel</button>
						<button type="submit" class="registerbtn">Register</button>
					</div>
					
				</form>
			</div>

			<!--  end modals -->
		<!-- end Sign up -->
		</section>
		<!-- Contact-->
		<section class="contact-section bg-black">
			<div class="container px-4 px-lg-5">
				<div class="social d-flex justify-content-center">
					<a class="mx-2" href="https://github.com/Ryuunsuke/sports-social-network"><i class="fab fa-github"></i></a>
				</div>
			</div>
		</section>

		<script>
			const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;
		</script>

		<!-- Bootstrap core JS-->
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

		<script src="https://unpkg.com/@studio-freight/lenis"></script>

		<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
		<script src="https://unpkg.com/lenis@1.1.14/dist/lenis.min.js"></script>

		<script src="./static/js/login.js"></script>
		<script src="./static/js/signup.js"></script>
		<script src="./static/js/indexnavbar.js"></script>
		<script src="./static/js/utilities.js"></script>
		<script src="./static/js/nav.js"></script>
		<script src="../static/js/addresschange.js"></script>
	</body>
</html>
