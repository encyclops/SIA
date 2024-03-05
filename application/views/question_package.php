<?php
ob_start();
?>
<script>
	<?php if ($this->session->flashdata('error_message')) : ?>
		Swal.fire({
			icon: 'error',
			title: 'Oops...',
			text: '<?php echo $this->session->flashdata('error_message'); ?>',
		});
	<?php endif; ?>
</script>
<div class="container-fluid">
	<div class="row" id="packagePage">
		<div class="col-md-12">
			<div class="card p-2 m-0">
				<div class="card-header">
					<div class="row">
						<div class="col">
							<h4 class="card-title">Kelola Paket Soal</h4>
							<p class="card-category">Paket Soal</p>
						</div>
						<div class="col d-flex align-items-center justify-content-end">
							<button onclick="showPForm('x')" class="btn btn-primary" id="addPackageBtn">Tambah</button>
						</div>
					</div>
				</div>
				<div class="card-body" style="max-height: 480px; overflow-y: scroll; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
					<?php
					$i = 1;
					if (empty($package)) {
					?>
						<div class="avatar flex-shrink-0 me-3 justify-content-center d-flex">

							<img src="<?= base_url('assets/img/dataEmpty1.jpg') ?>" alt="User" class="img-fluid" style="max-width: 310px; max-height: 190px;" />

						</div>
						<hr>
						<h5 style="text-align: center;">Data Tidak Ada</h5>
					<?php
					} else { ?>
						<table id="allPackageTable" name="table" class="table table-hover table-head-bg-info my-2">
							<thead>
								<tr>
									<th scope="col" style="width: 50px;">No.</th>
									<th scope="col">Id Paket Soal</th>
									<th scope="col">Paket Soal</th>
									<th scope="col" style="width: 95px;">Nama Training</th>
									<th scope="col" style="width: 135px;" class="text-center">Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php $i = 1;
								foreach ($package as $e) { ?>
									<tr>

										<td><?php echo $i; ?></td>
										<td><?php echo $e->package_uniqueId; ?></td>
										<td><?php echo $e->package_name; ?></td>
										<td><?php echo $e->training_id; ?></td>
										<td>
											<div class="d-flex justify-content-center">
												<a href="javascript:void(0)" id="editBtn" onclick="showPForm(<?php echo $e->package_id; ?>)" class="btn btn-warning mr-2"><i class="la la-pencil" style="font-size: 16px;"></i></a>
												<a href="javascript:void(0)" id="deleteBtn" onclick="deletePackage(<?php echo $e->package_id; ?>)" class="btn btn-danger"><i class="la la-trash" style="font-size: 16px;"></i></a>
											</div>
										</td>
									</tr>
								<?php $i++;
								} ?>
							</tbody>
						</table>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<div class="row" id="modifyPackagePage" style="display: none;">
		<div class="loader-container" id="loaderDiv">
			<div class="loader">
				<div class="loader-reverse"></div>
			</div>
			<p class="m-0">&emsp;Loading data...</p>
		</div>
		<div class="col-md-12">
			<div class="card p-2 m-0">
				<div class="card-header">
					<div class="row">
						<div class="col">
							<h4 class="card-title" id="titlePackage">Tambah Paket Soal</h4>
							<p class="card-category" id="navPackage">Paket Soal / Tambah Paket Soal</p>
						</div>
					</div>
				</div>
				<form role="form" id="formPackage" method="post" enctype="multipart/form-data">
					<div class="card-body" style="max-height: 480px; overflow-y: auto;" id="scrollableDiv">
						<div class="row py-2">
							<div class="col-md-6">
								<div class="form-group p-0">
									<label for="aOption">Id Paket<span style="color: red;">*</span></label>
									<input class="form-control" id="idUniqPaket" name="idUniqPaket" rows="3" spellcheck="false" style="resize: none;" placeholder="Masukkan id paket" required></input>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group p-0">
									<label for="bOption">Nama Paket <span style="color: red;">*</span></label>
									<input class="form-control" id="namePaket" name="namePaket" rows="3" spellcheck="false" style="resize: none;" placeholder="Masukkan Nama Paket" required></input>
								</div>
							</div>
						</div>
						<div class="row py-2">
							<div class="col-md-6">
								<div class="form-group p-0">
									<label for="chooseTrain">Training <span style="color: red;">*</span></label>
									<select class="form-control form-control" id="chooseTrain" name="chooseTrain" required>
										<option value="default" selected disabled>-- Pilih Training --</option>
										<?php foreach ($train as $t) : ?>
											<option value="<?php echo $t->id_training_header; ?>"><?php echo $t->judul_training_header; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group p-0">
									<label for="chooseTrain">Jumlah Soal <span style="color: red;">*</span></label>
									<select class="form-control form-control" id="chooseTrain" name="chooseTrain" required>
										<option value="default" selected disabled>-- Pilih Training --</option>
										<?php foreach ($train as $t) : ?>
											<option value="<?php echo $t->id_training_header; ?>"><?php echo $t->judul_training_header; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="card-body">
						<input type="hidden" name="package_id" id="package_id">
						<button type="button" class="btn btn-success float-right" onclick="validatePForm()">Submit</button>
						<a href="javascript:void(0)" onclick="changePForm('main')" class="btn btn-danger"> Kembali</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php
/* Store the content of the buffer for later use */
$contentPlaceHolder = ob_get_contents();
/* Clean out the buffer, and destroy the output buffer */
ob_end_clean();
/* Call the master page. It will echo the content of the placeholders in the designated locations */
include __DIR__ . "/layout.php";
include __DIR__ . '/script2.php';
?>