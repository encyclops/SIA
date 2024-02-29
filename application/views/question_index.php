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
	<div class="row" id="soalPage">
		<div class="col-md-12">
			<div class="card p-2 m-0">
				<div class="card-header">
					<div class="row">
						<div class="col">
							<h4 class="card-title">Kelola Soal</h4>
							<p class="card-category">Soal</p>
						</div>
						<div class="col d-flex align-items-center justify-content-end">
							<button onclick="showSoalModal('x')" class="btn btn-primary" id="tambahButton">Tambah</button>
						</div>
					</div>
				</div>
				<div class="card-body" style="max-height: 480px; overflow-y: scroll; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
					<?php
					$i = 1;
					if (empty($soal)) {
					?>
					<div class="avatar flex-shrink-0 me-3 justify-content-center d-flex">

						<img src="<?= base_url('assets/img/dataEmpty1.jpg') ?>" alt="User" class="img-fluid" style="max-width: 310px; max-height: 190px;" />

					</div>
					<hr>
					<h5 style="text-align: center;">Data Tidak Ada</h5>
					<?php
					} else { ?>
						<table id="allSoalTable" name="table" class="table table-hover table-head-bg-info my-2">
							<thead>
								<tr>
									<th scope="col" style="width: 50px;">No.</th>
									<th scope="col">Pertanyaan</th>
									<th scope="col" style="width: 95px;">Kesulitan</th>
									<th scope="col" style="width: 85px;">Jawaban</th>
									<th scope="col" style="width: 150px;">Pilihan A</th>
									<th scope="col" style="width: 150px;">Pilihan B</th>
									<th scope="col" style="width: 150px;">Pilihan C</th>
									<th scope="col" style="width: 150px;">Pilihan D</th>
									<th scope="col" style="width: 135px;"class="text-center">Aksi</th>
								</tr>
							</thead>
							<tbody id="tBodyAllSoal">
								<?php $i = 1;
								foreach ($soal as $e) { ?>
									<tr>
										<td><?php echo $i; ?></td>
										<td><?php echo $e->question; ?></td>
										<td><?php echo $e->q_level; ?></td>
										<td><?php echo $e->answer; ?></td>
										<td><?php echo $e->a; ?></td>
										<td><?php echo $e->b; ?></td>
										<td><?php echo $e->c; ?></td>
										<td><?php echo $e->d; ?></td>
										<td>
											<div class="d-flex justify-content-center">
												<a href="javascript:void(0)" id="editBtn" onclick="showSoalModal(<?php echo $e->question_id; ?>)" class="btn btn-warning mr-2"><i class="la la-pencil" style="font-size: 16px;"></i></a>
												<a href="javascript:void(0)" id="deleteBtn" onclick="deleteQuestion(<?php echo $e->question_id; ?>)" class="btn btn-danger"><i class="la la-trash" style="font-size: 16px;"></i></a>
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
	<div class="row" id="modifySoalPage" style="display: none;">
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
							<h4 class="card-title" id="modalTitle">Tambah Soal</h4>
							<p class="card-category" id="modalNav">Soal / Tambah Soal</p>
						</div>
					</div>
				</div>
				<form role="form" id="formTraining" method="post" enctype="multipart/form-data">
					<div class="card-body" style="max-height: 480px; overflow-y: auto;" id="scrollableDiv">
						<div class="row py-2">
							<div class="col-md-6">
								<div class="form-group p-0">
									<label for="levelSelect">Level Pertanyaan <span style="color: red;">*</span></label>
									<select class="form-control form-control" id="levelSelect" name="levelSelect" required>
										<option value="default" selected disabled>-- Pilih Tingkat Kesulitan --</option>
										<option value="Low">Low</option>
										<option value="Medium">Medium</option>
										<option value="High">High</option>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group p-0">
									<label for="answerSelect">Jawaban Benar <span style="color: red;">*</span></label>
									<select class="form-control form-control" id="answerSelect" name="answerSelect" required>
										<option value="default" selected disabled>-- Pilih Opsi Jawaban yang Benar --</option>
										<option value="A">A</option>
										<option value="B">B</option>
										<option value="C">C</option>
										<option value="D">D</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row py-2">
							<div class="col-md-12">
								<div class="form-group p-0">
									<label for="question">Pertanyaan <span style="color: red;">*</span></label>
									<input class="form-control" id="question" name="question" type="text" placeholder="Masukkan pertanyaan..." required></input>
								</div>
							</div>
						</div>
						<div class="row py-2">
							<div class="col-md-6">
								<div class="form-group p-0">
									<label for="aOption">Pilihan A <span style="color: red;">*</span></label>
									<textarea class="form-control" id="aOption" name="aOption" rows="3" spellcheck="false" style="resize: none;" placeholder="Masukkan pilihan A..." required></textarea>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group p-0">
									<label for="bOption">Pilihan B <span style="color: red;">*</span></label>
									<textarea class="form-control" id="bOption" name="bOption" rows="3" spellcheck="false" style="resize: none;" placeholder="Masukkan pilihan B..." required></textarea>
								</div>
							</div>
						</div>
						<div class="row py-2">
							<div class="col-md-6">
								<div class="form-group p-0">
									<label for="cOption">Pilihan C <span style="color: red;">*</span></label>
									<textarea class="form-control" id="cOption" name="cOption" rows="3" spellcheck="false" style="resize: none;" placeholder="Masukkan pilihan C..." required></textarea>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group p-0">
									<label for="dOption">Pilihan D <span style="color: red;">*</span></label>
									<textarea class="form-control" id="dOption" name="dOption" rows="3" spellcheck="false" style="resize: none;" placeholder="Masukkan pilihan D..." required></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="card-body">
						<input type="hidden" name="question_id" id="question_id">
						<button type="button" class="btn btn-success float-right" onclick="validateQForm()">Submit</button>
						<a href="javascript:void(0)" onclick="changeQForm('main')" class="btn btn-danger"> Kembali</a>
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