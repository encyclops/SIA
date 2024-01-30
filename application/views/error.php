<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Forms - Ready Bootstrap Dashboard</title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
	<link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
	<link rel="stylesheet" href="<?php echo site_url('assets/css/ready.css') ?>">
	<link rel="stylesheet" href="<?php echo site_url('assets/css/demo.css') ?>">
</head>

<body>
	<div class="wrapper">
		<div class="content" style="position: fixed; width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;">
			<div class="container-fluid">
				<div class="row justify-content-center">

					<div class="col-md-4">
						<div class="card">
							<div class="card-header">
								<div class="card-title">Login</div>
							</div>
							<form action="<?php echo base_url() ?>login/checkLogin" method="post" enctype="multipart/form-data">
								<div class="card-body">
									<div class="form-group">
										<label for="pillInput">NPK</label>
										<input type="text" class="form-control input-pill" id="npk" name="npk" placeholder="NPK">
									</div>
									<div class="form-group">
										<label for="pillInput"> Password</label>
										<input type="password" class="form-control input-pill" id="password" name="password" placeholder="Password">
									</div>
								</div>
							</form>
						</div>
					</div>

					<!-- <div class="col-md-4"></div> -->
				</div>
			</div>
		</div>
	</div>
</body>
<script src="<?php echo site_url('assets/js/core/jquery.3.2.1.min.js') ?>"></script>
<script src="<?php echo site_url('assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js') ?>"></script>
<script src="<?php echo site_url('assets/js/core/popper.min.js') ?>"></script>
<script src="<?php echo site_url('assets/js/core/bootstrap.min.js') ?>"></script>
<script src="<?php echo site_url('assets/js/plugin/chartist/chartist.min.js') ?>"></script>
<script src="<?php echo site_url('assets/js/plugin/chartist/plugin/chartist-plugin-tooltip.min.js') ?>"></script>
<script src="<?php echo site_url('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') ?>"></script>
<script src="<?php echo site_url('assets/js/plugin/bootstrap-toggle/bootstrap-toggle.min.js') ?>"></script>
<script src="<?php echo site_url('assets/js/plugin/jquery-mapael/jquery.mapael.min.js') ?>"></script>
<script src="<?php echo site_url('assets/js/plugin/jquery-mapael/maps/world_countries.min.js') ?>"></script>
<script src="<?php echo site_url('assets/js/plugin/chart-circle/circles.min.js') ?>"></script>
<script src="<?php echo site_url('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') ?>"></script>
<script src="<?php echo site_url('assets/js/ready.min.js') ?>"></script>

</html>