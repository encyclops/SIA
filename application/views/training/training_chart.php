<?php
ob_start();
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div class="row">
  <div class="col-md-3">
    <div class="card card-stats card-warning">
      <div class="card-body ">
        <div class="row">
          <div class="col-5">
            <div class="icon-big text-center">
              <i class="la la-users"></i>
            </div>
          </div>
          <div class="col-7 d-flex align-items-center">
            <div class="numbers">
              <p class="card-category">Jumlah Training</p>
              <h4 class="card-title">
                <?php echo $getCountTraining; ?>
                <!-- give code for jumlah training -->
              </h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card card-stats card-primary">
      <div class="card-body ">
        <div class="row">
          <div class="col-5">
            <div class="icon-big text-center">
              <i class="la la-bar-chart"></i>
            </div>
          </div>
          <div class="col-7 d-flex align-items-center">
            <div class="numbers">
              <p class="card-category">Jumlah Materi</p>
              <h4 class="card-title"> <?php echo $getCountSubstance; ?></h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card card-stats card-success">
      <div class="card-body">
        <div class="row">
          <div class="col-5">
            <div class="icon-big text-center">
              <i class="la la-newspaper-o"></i>
            </div>
          </div>
          <div class="col-7 d-flex align-items-center">
            <div class="numbers">
              <p class="card-category">Materi Telah Diakses</p>
              <h4 class="card-title"><?php echo $getCountDoneLesson; ?></h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card card-stats card-danger    ">
      <div class="card-body ">
        <div class="row">
          <div class="col-5">
            <div class="icon-big text-center">
              <i class="la la-check-circle"></i>
            </div>
          </div>
          <div class="col-7 d-flex align-items-center">
            <div class="numbers">
              <p class="card-category">Persentase Materi Belum</p>
              <h4 class="card-title"><?php echo $getCountNotDoneEmp; ?> %</h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="card" style="height: 300px;">
      <div class="card-header">
        <div class="row">
          <div class="col">
            <h4 class="card-title">Tren Pengaksesan Materi Training</h4>
          </div>
        </div>
      </div>
      <div style="overflow-x: auto;">
        <div class="card-body">
          <canvas id="chartContainer" style="height:100%; width: 100%"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-4">
    <div class="card" style="height: 300px;">
      <div class="card-header d-flex align-items-center justify-content-between" style="background-color: #1256e2;">
        <h5 class="card-title m-0 me-2" style="color: white"> <img src="<?= base_url('assets/img/crown.png') ?>" alt="User" class=" img-fluid" style="max-width: 30px; max-height: 27px; padding-bottom:8px; padding-right:3px; opacity:0.9" /> Materi Terpopuler</h5>
        </li>
        <label class="switch">
          <input type="checkbox" onchange="checkedSubstance(this.checked);">
          <span class="slider round"></span>
        </label>
      </div>
      <div class="card-body" style="overflow-y: scroll;">
        <div id="materisubstance">
          <ul class="p-0 m-0" style="max-height: 250px;">
            <?php
            $i = 1;
            foreach ($getFavoriteSubstance as $e) {
            ?>
              <li class="d-flex mb-1 pb-1">
                <div class="avatar flex-shrink-0 me-3">
                  <?php if ($i == 1) { ?>
                    <img src="<?= base_url('assets/img/1th.png') ?>" alt="User" class="rounded-circle img-fluid" style="max-width: 30px; max-height: 30px; " />
                  <?php } ?>
                  <?php if ($i == 2) { ?>
                    <img src="<?= base_url('assets/img/2th.png') ?>" alt="User" class="rounded-circle img-fluid" style="max-width: 30px; max-height: 30px; " />
                  <?php } ?>
                  <?php if ($i == 3) { ?>
                    <img src="<?= base_url('assets/img/3th.png') ?>" alt="User" class="rounded-circle img-fluid" style="max-width: 30px; max-height: 30px; " />
                  <?php } ?>
                  <?php if ($i > 3) { ?>
                    <img src="<?= base_url('assets/img/th.png') ?>" alt="User" class="rounded-circle img-fluid" style="max-width: 30px; max-height: 30px; " />
                  <?php } ?>
                </div>
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-3">
                  <div class="me-2" style="padding-right: 10px; padding-left: 10px">

                    <span class="user-level"><b> <?php echo $e->judul_training_detail; ?></b></span>
                    <small class="text-muted d-block mb-1"><?php echo $e->judul_training_header; ?></small>
                  </div>

                  <div class="user-progress d-flex align-items-center gap-2">
                    <img src="<?= base_url('assets/img/cc-success.png') ?>" alt="User" class="rounded-circle img-fluid" style="max-width: 25px; max-height: 25px; padding-right: 4px; padding-left: 5px" />
                  </div>
                  <div class="user-progress d-flex align-items-center gap-1">
                    <h6 class="mb-0" style="padding-right: 5px;"><?php echo $e->total; ?></h6>
                    <span class="text-muted"> Kali</span>
                  </div>
                </div>
              </li>
              <hr>
            <?php
              $i++;
            } ?>

          </ul>

        </div>
        <div id="tablesubstance" style="display: none;">
          <canvas id="barChart" style="max-height: 250px; overflow-y: auto;"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card" style="height: 300px;">
      <div class="card-header d-flex align-items-center justify-content-between" style="background-color: #1256e2;">
        <h5 class="card-title m-0 me-2" style="color: white"><img src="<?= base_url('assets/img/crown.png') ?>" alt="User" class=" img-fluid" style="max-width: 30px; max-height: 27px; padding-bottom:8px; padding-right:3px; opacity:0.9" /> Karyawan Teratas</h5>
        <label class="switch">
          <input type="checkbox" onchange="checkedEmployee(this.checked);">
          <span class="slider round"></span>
        </label>
      </div>
      <div class="card-body" style="overflow-y: auto;">
        <div id="tableEmployee">
          <ul class="p-0 m-0" style="max-height: 250px; overflow-y: auto;">
            <?php
            $i = 1;
            foreach ($getHighestEmployee as $e) {
            ?>
              <li class="d-flex mb-4 pb-1">
                <div class="avatar flex-shrink-0 me-3">
                  <?php if ($i == 1) { ?>
                    <img src="<?= base_url('assets/img/1th.png') ?>" alt="User" class="rounded-circle img-fluid" style="max-width: 30px; max-height: 30px; " />
                  <?php } ?>
                  <?php if ($i == 2) { ?>
                    <img src="<?= base_url('assets/img/2th.png') ?>" alt="User" class="rounded-circle img-fluid" style="max-width: 30px; max-height: 30px; " />
                  <?php } ?>
                  <?php if ($i == 3) { ?>
                    <img src="<?= base_url('assets/img/3th.png') ?>" alt="User" class="rounded-circle img-fluid" style="max-width: 30px; max-height: 30px; " />
                  <?php } ?>
                  <?php if ($i > 3) { ?>
                    <img src="<?= base_url('assets/img/th.png') ?>" alt="User" class="rounded-circle img-fluid" style="max-width: 30px; max-height: 30px; " />
                  <?php } ?>
                </div>
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-3">
                  <div class="me-2" style="padding-right: 10px; padding-left: 10px">
                    <span class="user-level"><b> <?php echo isset($e['nama']) ? $e['nama'] : ''; ?></b></span>
                    <small class="text-muted d-block mb-1"><?php echo isset($e['npk']) ? $e['npk'] : ''; ?></small>

                  </div>
                  <div class="user-progress d-flex align-items-center gap-2">
                    <img src="<?= base_url('assets/img/cc-success.png') ?>" alt="User" class="rounded-circle img-fluid" style="max-width: 25px; max-height: 25px; padding-right: 4px; padding-left: 5px" />
                  </div>
                  <div class="user-progress d-flex align-items-center gap-1">
                    <h6 class="mb-0" style="padding-right: 5px;"><?php echo isset($e['total']) ? $e['total'] : ''; ?>x</h6>
                    <span class="text-muted"> Akses</span>
                  </div>
                </div>
              </li>
              <hr>
            <?php

              $i++;
            } ?>

          </ul>
        </div>
        <div id="grafikEmployee" style="display: none;">
          <canvas id="barChartEmployee" style="max-height: 250px; overflow-y: auto;"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card" style="height: 300px;">
      <div class="card-header d-flex align-items-center justify-content-between" style="background-color: #1256e2;">
        <h5 class="card-title m-0 me-2" style="color: white"><img src="<?= base_url('assets/img/crown.png') ?>" alt="User" class=" img-fluid" style="max-width: 30px; max-height: 27px; padding-bottom:8px; padding-right:3px; opacity:0.9" /> Training Terpopuler</h5>
        <label class="switch">
          <input type="checkbox" unchecked onchange="checkedTraining(this.checked);">
          <span class="slider round"></span>
        </label>

      </div>
      <div class="card-body" style="overflow-y: auto;">
        <div id="tableTraining">
          <ul class="p-0 m-0" style="max-height: 250px; ">
            <?php
            $i = 1;
            foreach ($getFavoriteTraining as $e) {
            ?>

              <li class="d-flex mb-4 pb-1">
                <div class="avatar flex-shrink-0 me-3">
                  <?php if ($i == 1) { ?>
                    <img src="<?= base_url('assets/img/1th.png') ?>" alt="User" class="rounded-circle img-fluid" style="max-width: 30px; max-height: 30px; " />
                  <?php } ?>
                  <?php if ($i == 2) { ?>
                    <img src="<?= base_url('assets/img/2th.png') ?>" alt="User" class="rounded-circle img-fluid" style="max-width: 30px; max-height: 30px; " />
                  <?php } ?>
                  <?php if ($i == 3) { ?>
                    <img src="<?= base_url('assets/img/3th.png') ?>" alt="User" class="rounded-circle img-fluid" style="max-width: 30px; max-height: 30px; " />
                  <?php } ?>
                  <?php if ($i > 3) { ?>
                    <img src="<?= base_url('assets/img/th.png') ?>" alt="User" class="rounded-circle img-fluid" style="max-width: 30px; max-height: 30px; " />
                  <?php } ?>
                </div>
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-3">
                  <div class="me-2" style="padding-right: 10px; padding-left: 10px">
                    <span class="user-level"><b> <?php echo $e->judul_training_header; ?></b> </span>
                  </div>
                  <div class="user-progress d-flex align-items-center gap-2">
                    <img src="<?= base_url('assets/img/cc-success.png') ?>" alt="User" class="rounded-circle img-fluid" style="max-width: 25px; max-height: 25px; padding-right: 4px; padding-left: 5px" />
                  </div>
                  <div class="user-progress d-flex align-items-center gap-1">
                    <!-- <h6 class="mb-0" style="padding-right: 5px;"><?php echo $e->total; ?></h6> -->
                    <span class="text-muted">Diakses <?php echo $e->total; ?> Orang</span>
                  </div>
                </div>
              </li>
              <hr>
            <?php
              $i++;
            } ?>

          </ul>
        </div>
        <div id="grafikTraining" style="display: none;">
          <canvas id="barChartTraining" style="max-height: 250px; overflow-y: auto;"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- <div class="row">
  <div class="col-md-4">
      <div class="card" style="height: 300px;">
          <div class="card-header">
              <div class="row">
                  <div class="col">
                      <h4 class="card-title">Karyawan Teratas</h4>
                  </div>
              </div>
          </div>
          <div style="overflow-x: auto;">
            <table class="table table-head-bg-primary mt-4 mx-auto" >
                  <thead>
                      <tr>
                          <th scope="col" class="text-center" style="width: 60px;">#</th>
                          <th scope="col" class="text-center" style="width: 700px;">Materi</th>
                          <th scope="col" class="text-center">Training</th>
                          <th scope="col" class="text-center">Diakses</th>
                      </tr>
                  </thead>
                  <tbody>
                  <?php
                  $i = 1;
                  if (empty($getFavoriteSubstance)) {
                    // If no data, display a row with a message
                  ?>
                      <tr>
                          <td colspan="4" class="text-center">No data to be displayed.</td>
                      </tr>
                      <?php
                    } else {
                      // Loop through the data and display rows
                      foreach ($getFavoriteSubstance as $e) {
                      ?>
                          <tr>
                              <td><?php echo $i; ?></td>
                              <td><?php echo $e->judul_training_detail; ?></td>
                              <td><?php echo $e->judul_training_header; ?></td>
                              <td><?php echo $e->total; ?></td>
                          </tr>
                          <?php
                          $i++;
                        }
                      }
                          ?>
                  </tbody>
            </table>
          </div>
      </div>
  </div>         
    <div class="col-md-4">
    <div class="card" style="height: 300px;">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <h4 class="card-title">Karyawan Teratas</h4>
                </div>
            </div>
        </div>
        <div style="overflow-x: auto;">
            <table class="table table-head-bg-primary mt-4 mx-auto" style="width: 90%;">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">NPK</th>
                        <th scope="col">Total Akses Materi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $i = 1;
                if (empty($getHighestEmployee)) {
                  // If no data, display a row with a message
                ?>
                        <tr>
                            <td colspan="3" class="text-center">No data to be displayed.</td>
                        </tr>
                        <?php
                      } else {
                        // Loop through the data and display rows
                        foreach ($getHighestEmployee as $e) {

                        ?>
                            
                             <td><?php echo isset($e['npk']) ? $e['npk'] : ''; ?></td>
        <td><?php echo isset($e['nama']) ? $e['nama'] : ''; ?></td>
        <td><?php echo isset($e['total']) ? $e['total'] : ''; ?></td>
    </tr>
                            <?php
                            $i++;
                          }
                        }
                            ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

		<div class="col-md-4">
    <div class="card" style="height: 300px;">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <h4 class="card-title">Training Terpopuler</h4>
                </div>
            </div>
        </div>
        <div style="overflow-x: auto;">
            <table class="table table-head-bg-primary mt-4 mx-auto" style="width: 90%;">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama Training</th>
                        <th scope="col">Total Diakses</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $i = 1;
                if (empty($getFavoriteTraining)) {
                  // If no data, display a row with a message
                ?>
                        <tr>
                            <td colspan="3" class="text-center">No data to be displayed.</td>
                        </tr>
                        <?php
                      } else {
                        // Loop through the data and display rows
                        foreach ($getFavoriteTraining as $e) {
                        ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $e->judul_training_header; ?></td>
                                <td><?php echo $e->total; ?></td>
                            </tr>
                            <?php
                            $i++;
                          }
                        }
                            ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<div class="row">
		<div class="col-md-4">
      <div class="card" style="height: 300px;">
        <div class="card-header">
        <div class="row">
          <div class="col">
          <h4 class="card-title">Materi Terpopuler</h4>
          </div>
        </div>
        </div>
        <div class="card-body"  style=" overflow-y: scroll;" >
          <div id="tableFavoriteSubstance">
            <div style="overflow-x: auto;">
              <canvas id="favoriteSubstanceChart"  style="height:100%; width: 100%" ></canvas>
            </div>
          </div>
        </div>
      </div>
		</div>
		<div class="col-md-4">
			<div class="card" style="height: 300px;">
				<div class="card-header">
					<div class="row">
							<div class="col">
								<h4 class="card-title">Karyawan Teratas</h4>
							</div>
					</div>
				</div>
				<div class="card-body " style=" overflow-y: scroll;">
					<div >
						<canvas id="highestEmployeeChart"  style="height:100%; width: 100%" ></canvas>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card" style="height: 300px;">
				<div class="card-header">
					<div class="row">
						<div class="col">
							<h4 class="card-title">Tren Pengaksesan Materi Training</h4>
						</div>
					</div>
				</div>
				<div style="overflow-x: auto;">
					<div class="card-body" >
						<canvas id="getFavoriteTrainingChart"  style="height:100%; width: 100%" ></canvas>
					</div>
				</div>
			</div>
		</div>
</div> -->
<div class="row">
  <div class="col-md-4">
    <div class="card" style="height: 300px;">
      <div class="card-header d-flex align-items-center justify-content-between bg-warning">
        <h5 class="card-title m-0 me-2" style="color: white"> Daftar materi yang belum diakses Partisipan</h5>

      </div>
      <div class="card-body" style="overflow-y: scroll;">
        <div id="materiNotDonesubstance">
          <ul class="p-0 m-0" style="max-height: 250px">
            <?php
            $i = 1;
            foreach ($getNotDoneLesson as $e) {
            ?>
              <li class="d-flex mb-4 pb-1">
                <div class="avatar flex-shrink-0 me-3 ">

                  <img src="<?= base_url('assets/img/alert.png') ?>" alt="User" class="rounded-circle img-fluid" style="max-width: 30px; max-height: 30px; " />
                </div>
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-3">
                  <div class="me-2" style="padding-right: 10px; padding-left: 10px">
                    <small class="text-muted d-block mb-1"><?php echo $e->judul_training_header; ?></small>
                    <h6 class="mb-0"> <?php echo $e->judul_training_detail; ?></h6>
                  </div>
                  <div class="user-progress d-flex align-items-center gap-2">
                    <img src="<?= base_url('assets/img/cc-success.png') ?>" alt="User" class="rounded-circle img-fluid" style="max-width: 25px; max-height: 25px; padding-right: 4px; padding-left: 5px" />
                  </div>
                  <div class="user-progress d-flex align-items-center gap-1">
                    <h6 class="mb-0" style="padding-right: 5px;"><?php echo $e->npk; ?></h6>
                    <span class="text-muted"></span>
                  </div>
                </div>
              </li>
            <?php
              $i++;
            } ?>

          </ul>
        </div>
        <div id="tableNotDonesubstance" style="display: none;">
          <canvas id="barChartNotDonesubstance" style="max-height: 250px; overflow-y: auto;"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card" style="height: 300px;">
      <div class="card-header d-flex align-items-center justify-content-between bg-warning">
        <h5 class="card-title m-0 me-2" style="color: white"> NPK yang Belum Pernah Akses Materi </h5>

      </div>
      <div class="card-body" style="overflow-y: scroll;">

        <ul class="p-0 m-0" style="max-height: 250px">
          <?php
          $i = 1;
          if (empty($getNotOpenTrain)) {
          ?>
            <div class="avatar flex-shrink-0 me-3 justify-content-center d-flex">
              <img src="<?= base_url('assets/img/dataEmpty1.jpg') ?>" alt="User" class="img-fluid" style="max-width: 310px; max-height: 190px;" />
            </div>

            <?php
          } else {
            foreach ($getNotOpenTrain as $e) {

            ?>
              <li class="d-flex mb-4 pb-1">
                <div class="avatar flex-shrink-0 me-3">
                  <img src="<?= base_url('assets/img/alert.png') ?>" alt="User" class="rounded-circle img-fluid" style="max-width: 30px; max-height: 30px; " />
                </div>
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-3">
                  <div class="me-2" style="padding-right: 10px; padding-left: 10px">
                    <h6 class="mb-0"> <?php echo $e->npk; ?></h6>
                  </div>
                  <div class="user-progress d-flex align-items-center gap-2">
                    <img src="<?= base_url('assets/img/cc-success.png') ?>" alt="User" class="rounded-circle img-fluid" style="max-width: 25px; max-height: 25px; padding-right: 4px; padding-left: 5px" />
                  </div>

                </div>
              </li>
              <hr>
          <?php
              $i++;
            }
          } ?>
        </ul>

      </div>
    </div>
  </div>
  <!-- <div class="col-md-4" >
    <div class="card" style="height: 300px;">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title m-0 me-2">Training Terpouler</h5>
        <label class="switch">
          <input type="checkbox"  onchange="checkedTraining(this.checked);" >
          <span class="slider round"></span>
        </label>
      </div>
      <div class="card-body" >
        <div id="tableTraining">
          <ul class="p-0 m-0" style="max-height: 250px; overflow-y: auto;">
            <?php
            $i = 1;
            foreach ($getFavoriteTraining as $e) {
            ?>
            <li class="d-flex mb-4 pb-1">
              <div class="avatar flex-shrink-0 me-3">
                <img src="<?= base_url('assets/img/cc-success.png') ?>" alt="User" class="rounded-circle img-fluid" style="max-width: 30px; max-height: 30px; " />
              </div>
              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-3">
                <div class="me-2" style="padding-right: 10px; padding-left: 10px">
                  <small class="text-muted d-block mb-1"><?php echo $e->judul_training_header; ?></small>
                  <h6 class="mb-0"> <?php echo $e->judul_training_header; ?></h6>
                </div>
                <div class="user-progress d-flex align-items-center gap-2">
                <img src="<?= base_url('assets/img/cc-success.png') ?>" alt="User" class="rounded-circle img-fluid" style="max-width: 25px; max-height: 25px; padding-right: 4px; padding-left: 5px" />
                </div>
                <div class="user-progress d-flex align-items-center gap-1">
                  <h6 class="mb-0" style="padding-right: 5px;"><?php echo $e->total; ?></h6>
                    <span class="text-muted"> Akses</span>
                </div>
              </div>
            </li>
            <?php
              $i++;
            } ?>
          
          </ul>
        </div>
        <div id="grafikTraining"  style="display: none;">
          <canvas id="barChartTraining" style="max-height: 250px; overflow-y: auto;"></canvas>
        </div>
      </div>
    </div>
  </div> -->
</div>
<!-- <div class="row">
    <div class="col-md-4">
      <div class="card" style="height: 300px;">
        <div class="card-header">
          <div class="row">
              <div class="col">
                <h4 class="card-title">Materi belum Diakses Partisipan</h4>
              </div>
          </div>
        </div>
        <div class="card-body" style="height: 300px; overflow-y: scroll;">
          <div id="tableFavoriteSubstance">
            <table class="table table-head-bg-warning mt-4  mx-auto" style="width: 90%">
              <thead>
                <tr>
                  <th scope="col" class="text-center" style="width: 60px;">No.</th>
                  <th scope="col" class="text-center" style="width: 700px;"></th>
                  <th scope="col" class="text-center">Training</th>
                  <th scope="col" class="text-center">Diakses</th>
                </tr>
              </thead>
              <tbody>
                <?php $i = 1;
                foreach ($getNotDoneLesson as $e) { ?>
                          <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $e->judul_training_detail; ?></td>
                            <td><?php echo $e->judul_training_header; ?></td>
                            <td><?php echo $e->npk; ?></td>
                          </tr>
                  <?php $i++;
                } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card" style="height: 300px;">
        <div class="card-header">
          <div class="row">
              <div class="col">
                <h4 class="card-title">NPK yang Belum Akses Materi Sama Sekali	</h4>
              </div>
          </div>
        </div>
        <div class="card-body" style="height: 300px; overflow-y: scroll;">
          <div id="tableFavoriteSubstance">
            <div style="overflow-x: auto;">
                <table class="table table-head-bg-warning mt-4  mx-auto" style="width: 90%;  overflow-x: auto;">
                  <thead>
                    <tr>
                      <th scope="col" class="text-center" style="width: 60px;">No</th>
                      <th scope="col" class="text-center" style="width: 700px;">Materi</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php $i = 1;
                  if (empty($getNotAccessMaterial)) {
                  }
                  foreach ($getNotOpenTrain as $e) { ?>
                            <tr>
                              <td><?php echo $i; ?></td>
                              <td><?php echo $e->npk; ?></td>
                            </tr>
                    <?php $i++;
                  } ?>
                  </tbody>
                </table>
            </div>
          </div>
        </div>
      </div>
    </div>
</div> -->

<script>
  // Get the data from the views file
  var favoriteSubstanceData = <?php echo json_encode($getFavoriteSubstance); ?>;

  // Create the chart
  var favoriteSubstanceChart = new Chart("favoriteSubstanceChart", {
    type: "bar",
    data: {
      labels: favoriteSubstanceData.map(function(item) {
        return item.judul_training_detail;
      }),
      datasets: [{
        label: "Total",
        data: favoriteSubstanceData.map(function(item) {
          return item.total;
        }),
        backgroundColor: [
          "rgba(255, 99, 132, 1)",
          "rgba(54, 162, 235, 1)",
          "rgba(255, 206, 86, 1)",
          "rgba(75, 192, 192, 1)",
          "rgba(153, 102, 255, 1)",
          "rgba(255, 159, 64, 1)"
        ],
        borderColor: [
          "rgba(255, 99, 132, 1)",
          "rgba(54, 162, 235, 1)",
          "rgba(255, 206, 86, 1)",
          "rgba(75, 192, 192, 1)",
          "rgba(153, 102, 255, 1)",
          "rgba(255, 159, 64, 1)"
        ],
        borderWidth: 1,
        hoverBackgroundColor: [
          "rgba(255, 99, 132, 1)",
          "rgba(54, 162, 235, 1)",
          "rgba(255, 206, 86, 1)",
          "rgba(75, 192, 192, 1)",
          "rgba(153, 102, 255, 1)",
          "rgba(255, 159, 64, 1)"
        ],
        hoverBorderColor: [
          "rgba(255, 99, 132, 1)",
          "rgba(54, 162, 235, 1)",
          "rgba(255, 206, 86, 1)",
          "rgba(75, 192, 192, 1)",
          "rgba(153, 102, 255, 1)",
          "rgba(255, 159, 64, 1)"
        ],
        hoverBorderWidth: 2
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      },
      tooltips: {
        callbacks: {
          label: function(tooltipItem, data) {
            var label = data.labels[tooltipItem.index];
            var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
            return label + ': ' + value;
          }
        }
      },
      hover: {
        mode: 'index',
        intersect: false
      }
    }
  });


  var highestEmployee = <?php echo json_encode($getHighestEmployee); ?>;
  console.log("sff" + highestEmployee);

  // Create the chart
  var highestEmployeeChart = new Chart("highestEmployeeChart", {
    type: "bar",
    data: {
      labels: highestEmployee.map(function(item) {
        return item.npk;
      }),
      datasets: [{
        label: "Total",
        data: highestEmployee.map(function(item) {
          return item.total;
        }),
        backgroundColor: [
          "rgba(255, 99, 132, 1)",
          "rgba(54, 162, 235, 1)",
          "rgba(255, 206, 86, 1)",
          "rgba(75, 192, 192, 1)",
          "rgba(153, 102, 255, 1)",
          "rgba(255, 159, 64, 1)"
        ],
        borderColor: [
          "rgba(255, 99, 132, 1)",
          "rgba(54, 162, 235, 1)",
          "rgba(255, 206, 86, 1)",
          "rgba(75, 192, 192, 1)",
          "rgba(153, 102, 255, 1)",
          "rgba(255, 159, 64, 1)"
        ],
        borderWidth: 1,
        hoverBackgroundColor: [
          "rgba(255, 99, 132, 1)",
          "rgba(54, 162, 235, 1)",
          "rgba(255, 206, 86, 1)",
          "rgba(75, 192, 192, 1)",
          "rgba(153, 102, 255, 1)",
          "rgba(255, 159, 64, 1)"
        ],
        hoverBorderColor: [
          "rgba(255, 99, 132, 1)",
          "rgba(54, 162, 235, 1)",
          "rgba(255, 206, 86, 1)",
          "rgba(75, 192, 192, 1)",
          "rgba(153, 102, 255, 1)",
          "rgba(255, 159, 64, 1)"
        ],
        hoverBorderWidth: 2
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      },
      tooltips: {
        callbacks: {
          label: function(tooltipItem, data) {
            var label = data.labels[tooltipItem.index];
            var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
            return label + ': ' + value;
          }
        }
      },
      hover: {
        mode: 'index',
        intersect: false
      }
    }
  });

  var favoriteTraining = <?php echo json_encode($getFavoriteTraining); ?>;
  var getFavoriteTrainingChart = new Chart("getFavoriteTrainingChart", {
    type: "bar",
    data: {
      labels: favoriteTraining.map(function(item) {
        return item.judul_training_header;
      }),
      datasets: [{
        label: "Total",
        data: favoriteTraining.map(function(item) {
          return item.total;
        }),
        backgroundColor: [
          "rgba(255, 99, 132, 1)",
          "rgba(54, 162, 235, 1)",
          "rgba(255, 206, 86, 1)",
          "rgba(75, 192, 192, 1)",
          "rgba(153, 102, 255, 1)",
          "rgba(255, 159, 64, 1)"
        ],
        borderColor: [
          "rgba(255, 99, 132, 1)",
          "rgba(54, 162, 235, 1)",
          "rgba(255, 206, 86, 1)",
          "rgba(75, 192, 192, 1)",
          "rgba(153, 102, 255, 1)",
          "rgba(255, 159, 64, 1)"
        ],
        borderWidth: 1,
        hoverBackgroundColor: [
          "rgba(255, 99, 132, 1)",
          "rgba(54, 162, 235, 1)",
          "rgba(255, 206, 86, 1)",
          "rgba(75, 192, 192, 1)",
          "rgba(153, 102, 255, 1)",
          "rgba(255, 159, 64, 1)"
        ],
        hoverBorderColor: [
          "rgba(255, 99, 132, 1)",
          "rgba(54, 162, 235, 1)",
          "rgba(255, 206, 86, 1)",
          "rgba(75, 192, 192, 1)",
          "rgba(153, 102, 255, 1)",
          "rgba(255, 159, 64, 1)"
        ],
        hoverBorderWidth: 2
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      },
      tooltips: {
        callbacks: {
          label: function(tooltipItem, data) {
            var label = data.labels[tooltipItem.index];
            var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
            return label + ': ' + value;
          }
        }
      },
      hover: {
        mode: 'index',
        intersect: false
      }
    }
  });
</script>

<script>
  function checkedSubstance(checked) {
    var materiSubstance = document.getElementById("materisubstance");
    var tableSubstance = document.getElementById("tablesubstance");

    if (checked) {
      // Checkbox is checked, show materisubstance
      materiSubstance.style.display = "none";
      tableSubstance.style.display = "block";
    } else {
      // Checkbox is unchecked, show tablesubstance
      materiSubstance.style.display = "block";
      tableSubstance.style.display = "none";
    }
  }

  function checkedEmployee(checked) {
    var materiSubstance = document.getElementById("tableEmployee");
    var tableSubstance = document.getElementById("grafikEmployee");

    if (checked) {
      // Checkbox is checked, show materisubstance
      materiSubstance.style.display = "none";
      tableSubstance.style.display = "block";
    } else {
      // Checkbox is unchecked, show tablesubstance
      materiSubstance.style.display = "block";
      tableSubstance.style.display = "none";
    }
  }

  function checkedTraining(checked) {
    var materiSubstance = document.getElementById("tableTraining");
    var tableSubstance = document.getElementById("grafikTraining");

    if (checked) {
      // Checkbox is checked, show materisubstance
      materiSubstance.style.display = "none";
      tableSubstance.style.display = "block";
    } else {
      // Checkbox is unchecked, show tablesubstance
      materiSubstance.style.display = "block";
      tableSubstance.style.display = "none";
    }
  }
  // Get the data from the PHP array
  var trendAccessData = <?php echo json_encode($getTrendAccess); ?>;

  // Create the chart
  var ctx = document.getElementById('chartContainer').getContext('2d');
  var chart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: trendAccessData.map(function(item) {
        return item.YearMonth;
      }),
      datasets: [{
        label: 'Total Akses',
        data: trendAccessData.map(function(item) {
          return item.RecordCount;
        }),
        borderColor: 'rgba(75, 192, 192, 1)',
        fill: false
      }]
    },
    options: {
      responsive: true,
      title: {
        display: true,
        text: 'Trend Access Data'
      },
      scales: {
        y: {
          display: true,
          scaleLabel: {
            display: true,
            labelString: 'Total Akses'
          }
        },
        x: {
          display: true,
          scaleLabel: {
            display: true,
            labelString: 'YearMonth'
          }
        }
      }
    }
  });
</script>
<script>
  // Data from PHP
  var data = <?php echo json_encode($getFavoriteTraining); ?>;

  // Extracting data for the chart
  var labels = data.map(item => item.judul_training_header);
  var values = data.map(item => item.total);

  // Creating a bar chart
  var ctx = document.getElementById('barChartTraining').getContext('2d');
  var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Total',
        data: values,
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        x: {
          title: {
            display: true,
            text: 'Judul Training Detail'
          }
        },
        y: {
          title: {
            display: true,
            text: 'Total'
          }
        }
      }
    }
  });

  var data = <?php echo json_encode($getFavoriteSubstance); ?>;

  // Extracting data for the chart
  var labels = data.map(item => item.judul_training_detail);
  var values = data.map(item => item.total);

  // Creating a bar chart
  var ctx = document.getElementById('barChart').getContext('2d');
  var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Total',
        data: values,
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        x: {
          title: {
            display: true,
            text: 'Judul Training Detail'
          }
        },
        y: {
          title: {
            display: true,
            text: 'Total'
          }
        }
      }
    }
  });

  //   var data = <?php echo json_encode($getFavoriteTraining); ?>;

  // // Extracting data for the chart
  // var labels = data.map(item => item.judul_training_header);
  // var values = data.map(item => item.total);

  // // Creating a bar chart
  // var ctx = document.getElementById('barChart').getContext('2d');
  // var myChart = new Chart(ctx, {
  //   type: 'bar',
  //   data: {
  //     labels: labels,
  //     datasets: [{
  //       label: 'Total',
  //       data: values,
  //       backgroundColor: 'rgba(75, 192, 192, 0.2)',
  //       borderColor: 'rgba(75, 192, 192, 1)',
  //       borderWidth: 1
  //     }]
  //   },
  //   options: {
  //     scales: {
  //       x: {
  //         title: {
  //           display: true,
  //           text: 'Judul Training Detail'
  //         }
  //       },
  //       y: {
  //         title: {
  //           display: true,
  //           text: 'Total'
  //         }
  //       }
  //     }
  //   }
  // });

  var data = <?php echo json_encode($getHighestEmployee); ?>;

  // Extracting data for the chart
  var labels = data.map(item => item.nama);
  var values = data.map(item => item.total);

  // Creating a bar chart
  var ctx = document.getElementById('barChartEmployee').getContext('2d');
  var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Total',
        data: values,
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        x: {
          title: {
            display: true,
            text: 'Judul Training Detail'
          }
        },
        y: {
          title: {
            display: true,
            text: 'Total'
          }
        }
      }
    }
  });
</script>
<?php include __DIR__ . '/../script.php'; ?>
<?php
/* Store the content of the buffer for later use */
$contentPlaceHolder = ob_get_contents();
/* Clean out the buffer, and destroy the output buffer */
ob_end_clean();
/* Call the master page. It will echo the content of the placeholders in the designated locations */
include __DIR__ . "/../layout.php";
?>