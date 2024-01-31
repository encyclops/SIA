<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Sistem Informasi Training</title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
	<link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
	<link rel="stylesheet" href="<?php echo site_url('assets/css/ready.css') ?>">
	<link rel="stylesheet" href="<?php echo site_url('assets/css/demo.css') ?>">
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body>
	<div class="wrapper">

		<script>
			<?php if ($this->session->flashdata('error_message')) : ?>
				Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: '<?php echo $this->session->flashdata('error_message'); ?>',
				});
			<?php endif; ?>
		</script>
		<div class="main-panel" style="width: 100%">
			<div class="content" style="position: fixed;width: 100%;height: 100%;display: flex;justify-content: center;align-items: center;background:  url('assets/img/bgLog.png') center/cover no-repeat;">
				<div class="container-fluid">
					<div class="row justify-content-center" style="width: 100%; height: 100%;">
						<div class="col-md-6 d-flex align-items-center justify-content-center">

							<img src="assets/img/picLog.png" style="width: 80%">

						</div>
						<div class="col-md-6 d-flex align-items-center justify-content-center">
							<div class="card">
								<div class="card-header">
									<div class="card-title">Login</div>
								</div>
								<form action="<?php echo base_url() ?>login/checkLogin" method="post" enctype="multipart/form-data">
									<div class="card-body">
										<div class="form-group">
											<label for="pillInput">NPK</label>
											<input type="text" class="form-control input-pill" id="npk" name="npk" placeholder="NPK" required>
										</div>
										<div class="form-group">
											<label for="pillInput"> Password</label>
											<input type="password" class="form-control input-pill" id="password" name="password" placeholder="Password">
										</div>
									</div>
									<div class="card-action">
										<button type="submit" ID="submit" name="submit" Class="btn btn-success">LOGIN</button>

										<button class="btn btn-danger">Cancel</button>
									</div>
								</form>
							</div>
						</div>

						<!-- <div class="col-md-4"></div> -->
					</div>
				</div>
			</div>

		</div>
	</div>

	</div>
</body>

<script>
	function updateDateTime() {
		// Get the current date and time
		var now = new Date();

		// Format the time as HH:MM:SS
		var formattedTime = now.toLocaleTimeString();

		// Format the date as DD-MMM-YYYY in Bahasa Indonesia
		var days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
		var dayName = days[now.getDay()]; // Get day name
		var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
		var monthName = months[now.getMonth()]; // Get month name
		var formattedDate = now.getDate() + ' ' + monthName + ' ' + now.getFullYear();

		// Update the elements with the formatted time, nameday, and date
		$("#timestamp").text(formattedTime);
		$("#nameday").text(dayName);
		$("#date").text(formattedDate);
	}

	// Call the function when the page has finished loading
	$(document).ready(function() {
		updateDateTime(); // Update date, time, and nameday when the page loads

		// Set interval to update the date, time, and nameday every second (1000ms)
		setInterval(updateDateTime, 1000);
	});
</script>
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