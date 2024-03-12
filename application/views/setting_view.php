<?php
ob_start();
?>
<div class="container-fluid">
	<div id="listCardDiv">
		<div class="row">
			<div class="col-md-12">
				<div class="card p-2 mb-3">
					<div class="card-header">
						<div class="row">
							<div class="col">
								<h4 class="card-title">Pengaturan</h4>
								<p class="card-category">Pengaturan AWI Training System</p>
							</div>
						</div>
					</div>
					<div class="card-body pt-0">
                        <p class="card-category">Formulir Pengajuan dan Evaluasi Training (FPET)</p>
						<div class="row form-group px-0" style="border-bottom: 1px solid #ebedf2 !important;">
                            <div class="col-md-5">
                                <label for="hrDefault" class="pt-2">Default HR</label>
                            </div>
                            <div class="col-md-7">
                                <select class="form-control input-pill" id="hrDefault">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                        </div>
                        <p class="card-category">Pre-test dan Post-test</p>
                        <div class="row form-group px-0" >
                            <div class="col-md-5">
                                <label for="qAmountDefault" class="pt-2">Default Jumlah Soal dalam 1 Halaman</label>
                            </div>
                            <div class="col-md-7">
                                <select class="form-control input-pill" id="qAmountDefault">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                </select>
                            </div>
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include __DIR__ . '/script2.php'; ?>
<?php
/* Store the content of the buffer for later use */
$contentPlaceHolder = ob_get_contents();
/* Clean out the buffer, and destroy the output buffer */
ob_end_clean();
/* Call the master page. It will echo the content of the placeholders in the designated locations */
include __DIR__ . "/layout.php";
?>