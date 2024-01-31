<?php
ob_start();
?>
<?php
$combinedData = [];
$uniqueIds = [];
foreach ($substance as $s) {
	$title = $s->judul_training_detail;
	$id_header = $s->id_training_header;
	$id_detail = $s->id_training_detail;
	$path = $s->path_file_training_detail;
	$status = $s->status;
	$combinedData[] = array(
		'title' => $title, 'id_header' => $id_header,
		'id_detail' => $id_detail, 'path' => $path,
		'status' => $status
	);
	if (!in_array($id_header, $uniqueIds)) {
		$uniqueIds[] = $id_header;
	}
}
$combinedDataJSON = json_encode($combinedData);
?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
<!-- Remember to include jQuery :) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
<div class="container-fluid">
	<div id="listCardDiv">
		<div class="row">
			<div class="col-md-12">
				<div class="card p-2 mb-3">
					<div class="card-header">
						<div class="row">
							<div class="col">
								<h4 class="card-title">Daftar Training</h4>
								<p class="card-category">Training</p>
							</div>
							<?php if ($this->session->userdata['role'] == 'admin') { ?>
								<div class="col d-flex align-items-center justify-content-end">
									<a href="javascript:void(0)" onclick="changeForm('tambah')" class="btn btn-primary"></i> Tambah</a>
								</div>
							<?php } ?>
						</div>
					</div>
					<div class="card-body">
						<div class="row py-2">
							<div class="col-md-1 d-flex mb-3 align-items-center">
								<label for="search_keyword">Filter Tag:&nbsp;&nbsp;</label>
							</div>
							<div class="col-md-2">
								<button class="btn btn-primary dropdown-toggle" type="button" name="" id="ddTags" style="width: 100%; text-align: start;" data-toggle="dropdown" aria-expanded="false">
									ALL
								</button>
								<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, 0px, 0px); top: 0px; left: 0px; will-change: transform; max-height: 200px; overflow-y: auto;">
									<a class="dropdown-item" id="all" href="javascript:void(0)" onclick="tagFilter('', 'ALL')">ALL</a>
									<?php foreach ($tags as $t) { ?>
										<a class="dropdown-item" id="<?php echo $t->id_tag ?>" href="javascript:void(0)" onclick="tagFilter(<?php echo $t->id_tag ?>, '<?php echo $t->name_tag ?>')"><?php echo $t->name_tag ?></a>
									<?php } ?>
								</ul>
							</div>
							<div class="col-md-1"></div>
							<div class="col-md-6">
								<div class="form-group form-inline p-0">
									<label for="search_training">Search:&nbsp;&nbsp;</label>
									<div class="col-md-9 p-0">
										<input type="text" class="form-control input-full" id="search_training" name="search_training" style="width: 100%;">
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<label class="form-radio-label mb-3 float-right">
									<span class="form-radio-sign">My Training: &nbsp;&nbsp;</span>
									<input type="checkbox" data-toggle="toggle" data-onstyle="info" data-style="btn-round" name="optionsRadiosA" value="" id="myTraining" onchange="toggleMine(this.checked);">
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php if($this->session->userdata('role') == 'admin') { ?>
		<div class="row">
			<div class="col-md-12 mb-2 p-3 d-flex justify-content-center">
				<ul class="nav nav-pills nav-primary" id="statusTabs">
					<li class="nav-item">
						<a id="allTab" class="nav-link active" href="javascript:void(0)" onclick="toggleTab('all')">All</a>
					</li>
					<li class="nav-item">
						<a id="publishedTab" class="nav-link" href="javascript:void(0)" onclick="toggleTab('published')">Published</a>
					</li>
					<li class="nav-item">
						<a id="draftTab" class="nav-link" href="javascript:void(0)" onclick="toggleTab('draft')">Draft</a>
					</li>
					<li class="nav-item">
						<a id="allWithRequestTab" class="nav-link" href="javascript:void(0)" onclick="toggleTab('allWithRequest')">Published with Request</a>
					</li>
				</ul>
			</div>
		</div>
		<?php } ?>
		<div class="row" id="trainingContainer">
			<?php $i = 1;
			$j = 1;
			foreach ($training as $t) { ?>
				<div class="col-sm-3 card-item <?php echo $i <= 4 ? 'fade-in' : 'fade-out' ?>">
					<div class="card" style="border-radius: 20px;">
						<div class="card-header">
							<img src="assets/img/picLog.png" style="width: 100%">
							<div class="row overlay-content" style="width: 100%">
								<div class="col-sm-6">
									<?php if ($t->status == 2) { ?>
										<span class="badge badge-success">Published</span>
									<?php } else { ?>
										<span class="badge badge-warning">Draft</span>
									<?php } ?>
								</div>
								<div class="col-sm-6 justify-content-end d-flex">
									<?php if ($t->detail_request == 'true' || $t->participant_request == 'true') { ?>
										<span class="badge badge-warning">!</span>
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-sm-8 pr-0">
									<h4 class="card-title"><?php echo (strlen($t->judul_training_header) > 15) ? substr($t->judul_training_header, 0, 15) . '...' : $t->judul_training_header; ?></h4>
									<p class="card-category"><i class="la la-file-pdf-o"></i>&ensp;<?php echo $t->detail_count ?> materi</p>
									<p class="card-category"><i class="la la-users"></i>&ensp;<?php echo $t->participant_count ?> partisipan</p>
								</div>
								<div class="col d-flex align-items-center justify-content-end p-0 pr-3">
									<a href="javascript:void(0)" onclick="showDetail(<?php echo $t->id_training_header ?>)" class="btn btn-primary px-2"><i class="la la-bars" style="font-size: 16px;"></i> Detail</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php $i++;
			} ?>
		</div>
		<div class="row" id="pagingContainer">
			<?php $k = 1;
			$l = 1;
			foreach ($training as $t) {
				if ($k == 1) { ?>
					<div class="col align-items-center justify-content-center d-flex">
						<ul class="pagination pg-primary">
							<li class="page-item">
								<a class="page-link" href="#" aria-label="Previous">
									<span aria-hidden="true">«</span>
									<span class="sr-only">Previous</span>
								</a>
							</li>
						<?php }
					if ($k % 4 == 1) { ?>
							<script>
								console.log(<?php echo $k ?>)
							</script>
							<li class="page-item" data-page="<?php echo $l ?>"><a class="page-link" href="javascript:void(0)" onclick="showPage(<?php echo $l ?>)"><?php echo $l ?></a></li>
					<?php $l++;
					}
					$k++;
				} ?>
					<li class="page-item">
						<a class="page-link" href="#" aria-label="Next">
							<span aria-hidden="true">»</span>
							<span class="sr-only">Next</span>
						</a>
					</li>
						</ul>
					</div>
		</div>
	</div>
	<div id="pdfModal" class="modal">
		<div class="modal-content">
			<span class="close">&times;</span>
			<div class="iframe-container">
				<iframe id="pdfViewer" width="100%" height="500px" class="pdf-iframe">></iframe>
				<div class="overlay"></div>
			</div>
		</div>
	</div>
	<div class="row" id="detailFormDiv" style="display: none;">
		<div class="loader-container" id="loaderDiv">
			<div class="loader">
				<div class="loader-reverse"></div>
			</div>
			<p class="m-0">&emsp;Loading data...</p>
		</div>
		<form role="form" id="formTraining" method="post" enctype="multipart/form-data">
			<div class="col-md-12">
				<div class="card p-2">
					<div class="card-header">
						<div class="row">
							<div class="col">
								<div class="card-title" id="cardTitle">Tambah Training</div>
								<p class="card-category" id="cardCategory">Training / Tambah Training</p>
							</div>
							<div class="col">
								<div class="d-flex justify-content-end" id="btnDetailEmpDiv" style="display: none;">
									<a href="javascript:void(0)" id="publishBtn" class="btn btn-info" style="margin-right: 9px; display: none;"></i> Publish</a>
									<a href="javascript:void(0)" id="editBtn" class="btn btn-warning" style="margin-right: 9px; display: none;"></i> Edit</a>
									<a href="javascript:void(0)" id="deleteBtn" class="btn btn-danger " style="display: none;"></i> Hapus</a>
								</div>
							</div>
						</div>
					</div>
					<div id="temaDiv" class="card-body" style="border-bottom: 1px solid #ebedf2 !important;">
						<label for="temaTraining" class="my-2">Tema Training<span style="color: red;">*</span></label>
						<input type="text" class="form-control input-pill mb-3" name="temaTraining" id="temaTraining" placeholder="Masukkan Tema Training">
						<span style="color: red;" class="mb-3" id="errorMessages"></span>
						<input type="text" name="idTraining" id="idTraining" hidden>
						<div class="row">
							<div class="col">
								<label class="my-2">Pemateri</label>
								<input type="text" class="form-control input-pill mb-3" name="pemateri" id="pemateri" placeholder="Masukkan Pemateri">
							</div>
							<div class="col">
								<label class="my-2">Tags</label><br />
								<div id="tagsContainer">
									<?php $i = 1;
									function isColorLight($hexColor)
									{
										$r = hexdec(substr($hexColor, 1, 2));
										$g = hexdec(substr($hexColor, 3, 2));
										$b = hexdec(substr($hexColor, 5, 2));
										$luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
										if ($luminance > 0.7) {
											return '#000000';
										} else {
											return '#ffffff';
										}
									}
									foreach ($tags as $t) {
										$textColor = isColorLight($t->color); ?>
										<span class="badge tags" id="tags<?php echo $t->id_tag ?>" style="background-color: <?php echo $t->color ?>; color: <?php echo $textColor ?>; border-color: white;" onclick="addTags('tags<?php echo $t->id_tag ?>')" onmouseover="mouseIn('tags<?php echo $t->id_tag . '\', \'' . $t->color ?>')" onmouseout="mouseOut('tags<?php echo $t->id_tag . '\', \'' .  $t->color ?>')"><?php echo $t->name_tag ?></span>
									<?php } ?>
								</div>
								<!-- <input type="text" class="form-control input-pill mb-3" name="pemateri" id="pemateri" placeholder="Masukkan Pemateri"> -->
							</div>
						</div>
					</div>
					<div id="allEmpDiv" class="card-body" style="border-bottom: 1px solid #ebedf2 !important;">
						<label class="mb-2">Partisipan</label>
						<div class="row py-2">
							<div class="col-md-1 d-flex mb-2 align-items-center">
								<label for="search_keyword">Departemen:&nbsp;&nbsp;</label>
							</div>
							<div class="col-md-3">
								<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" style="width: 100%; text-align: start;" data-toggle="dropdown" aria-expanded="false">
									ALL
								</button>
								<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, 0px, 0px); top: 0px; left: 0px; will-change: transform; max-height: 200px; overflow-y: auto;">
									<a class="dropdown-item" id="all" href="javascript:void(0)" onclick="searchKeyword('', 'ALL', 'allEmpTable');">ALL</a>
									<?php foreach ($dept as $d) { ?>
										<a class="dropdown-item" id="<?php echo $d->DEPARTEMEN ?>" href="javascript:void(0)" onclick="searchKeyword('', '<?php echo $d->DEPARTEMEN ?>', 'allEmpTable')"><?php echo $d->DEPARTEMEN ?></a>
									<?php } ?>
								</ul>
							</div>
							<div class="col-md-1"></div>
							<div class="col-md-4">
								<div class="form-group form-inline p-0">
									<label for="search_keyword" class="col-md-3 col-form-label p-0">Cari Nama: </label>
									<div class="col-md-9 p-0">
										<input type="text" class="form-control input-full" id="search_keyword" placeholder="Enter Input">
									</div>
								</div>
							</div>
							<div class="col-md-1"></div>
							<div class="col-md-2">
								<label class="form-radio-label mb-3 mr-3 float-right">
									<span class="form-radio-sign">Select All: &nbsp;&nbsp;</span>
									<input type="checkbox" data-toggle="toggle" data-onstyle="info" data-style="btn-round" name="optionsRadiosA" value="" onchange="toggleAll(this.checked);">
								</label>
							</div>
						</div>
						<div id="allEmpTableDiv" style="max-height: 300px; overflow-y: scroll;">
							<table id="allEmpTable" name="table" class="table table-hover table-head-bg-info my-2">
								<thead>
									<tr>
										<th scope="col" class="text-center" style="width: 50px;">No.</th>
										<th scope="col" class="text-center" style="width: 500px;">Nama Karyawan</th>
										<th scope="col" class="text-center" style="width: 500px;">Departemen</th>
										<th scope="col" class="text-center">Aksi</th>
									</tr>
								</thead>
								<tbody id="tBodyAllEmp">
									<?php $i = 1;
									foreach ($employee as $e) { ?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><?php echo $e->NAMA; ?></td>
											<td><?php echo $e->DEPARTEMEN; ?></td>
											<td class="text-center">
												<label class="form-check-label">
													<input class="form-check-input" type="checkbox" style="position: fixed;" value="<?php echo $e->NPK; ?>" name="chkBoxemp[]">
													<span class="form-check-sign" onclick="addEmp('<?php echo $e->NPK; ?>')"></span>
												</label>
											</td>
										</tr>
									<?php $i++;
									} ?>
								</tbody>
							</table>
						</div>
					</div>
					<div id="detailEmpDiv" class="card-body" style="border-bottom: 1px solid #ebedf2 !important; display: none;">
						<label class="mb-2">Partisipan</label>
						<div class="form-inline py-2">
							<label for="skDetail" class="col-md-1 p-0">Search:&nbsp;&nbsp;</label>
							<div class="col-md-11 p-0">
								<input type="text" class="form-control input-full" id="skDetail" name="skDetail">
							</div>
						</div>
						<div style="max-height: 300px; overflow-y: scroll;">
							<table id="detailEmpTable" name="table" class="table table-hover table-head-bg-info my-2">
								<thead>
									<tr>
										<th scope="col" class="text-center" style="width: 60px;">No.</th>
										<th scope="col" class="text-center" style="width: 350px;">Nama Karyawan</th>
										<th scope="col" class="text-center" style="width: 300px;">Departemen</th>
										<th scope="col" class="text-center" style="width: 100px;">Progres</th>
										<th scope="col" class="text-center" style="width: 100px;">Persentase</th>
										<th scope="col" class="text-center" style="width: 100px;">Tambah Partisipan</th>
										<th scope="col" class="text-center" style="width: 100px;">Upload Materi</th>
										<th scope="col" class="text-center">Permintaan</th>
									</tr>
								</thead>
								<tbody id="tBodyDetailEmp">
								</tbody>
							</table>
						</div>
					</div>
					<div id="detailOnlyDiv" class="card-body" style="border-bottom: 1px solid #ebedf2 !important; display: none;">
						<label class="mb-2">Partisipan</label>
						<div class="form-inline py-2">
							<label for="skDetail" class="col-md-1 p-0">Search:&nbsp;&nbsp;</label>
							<div class="col-md-11 p-0">
								<input type="text" class="form-control input-full" id="skDetail" name="skDetail">
							</div>
						</div>
						<div style="max-height: 300px; overflow-y: scroll;">
							<table id="detailOnlyEmpTable" name="table" class="table table-hover table-head-bg-info my-2">
								<thead>
									<tr>
										<th scope="col" class="text-center" style="width: 50px;">No.</th>
										<th scope="col" class="text-center">Nama Karyawan</th>
										<th scope="col" class="text-center" style="width: 300px;">Departemen</th>
										<th scope="col" class="text-center" style="width: 100px;">Progres</th>
										<th scope="col" class="text-center" style="width: 100px;">Persentase</th>
									</tr>
								</thead>
								<tbody id="tBodyDetailOnlyEmp">
								</tbody>
							</table>
						</div>
					</div>
					<div id="substanceDiv" class="card-body" style="border-bottom: 1px solid #ebedf2 !important;">
						<div class="row justify-content-between m-0 p-0">
							<label class="my-2">Materi</label>
							<a id="addFileBtn" href="javascript:void(0)" onclick="addRow()" class="btn btn-info btn-border float-right mb-3">Tambah Materi</a>
						</div>
						<div style="max-height: 300px; overflow-y: scroll;">
							<table id="substanceTableEdit" name="table" class="table table-hover table-head-bg-info my-2">
								<thead>
									<tr>
										<!-- <th scope="col" class="text-center" style="width: 50px;">No.</th> -->
										<th scope="col" class="text-center" style="width: 560px;">Judul Materi</th>
										<th scope="col" class="text-center" style="width: 560px;">File</th>
										<th scope="col" class="text-center" style="width: 90px;">Aksi</th>
									</tr>
								</thead>
								<tbody id="tBodySubstanceTableEdit2">
									<!-- Rows will be dynamically generated here -->
								</tbody>
								<tbody id="tBodySubstanceTableEdit">
									<!-- Rows will be dynamically generated here -->
								</tbody>
							</table>
						</div>
						<div style="max-height: 300px; overflow-y: scroll;">
							<table id="substanceTableDetail" name="table" class="table table-hover table-head-bg-info my-2" style="display: none;">
								<thead>
									<tr>
										<th scope="col" class="text-center" style="width: 50px;">No.</th>
										<th scope="col" class="text-center" style="width: 600px;">Judul Materi</th>
										<th scope="col" class="text-center" style="width: 600px;">File</th>
									</tr>
								</thead>
								<tbody id="tBodySubstanceTableDetail">
									<!-- Rows will be dynamically generated here -->
								</tbody>
							</table>
						</div>
					</div>
					<div class="card-body">
						<button type="button" onclick="validateForm()" class="btn btn-success float-right" id="submitBtn">Simpan</button>
						<a href="javascript:void(0)" onclick="changeForm('main')" class="btn btn-danger"></i> Kembali</a>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<script>
	$(document).ready(function() {
		$('#detailEmpTable').DataTable();
	});
</script>

<?php include __DIR__ . '/../script2.php'; ?>
<?php
/* Store the content of the buffer for later use */
$contentPlaceHolder = ob_get_contents();
/* Clean out the buffer, and destroy the output buffer */
ob_end_clean();
/* Call the master page. It will echo the content of the placeholders in the designated locations */
include __DIR__ . "/../layout.php";
?>