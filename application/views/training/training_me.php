<?php
ob_start();
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div class="row">
  <div class="col-md-6">
    <div class="card card-stats card-info">
      <div class="card-body ">
        <div class="row">
          <div class="col-5">
            <div class="icon-big text-center">
              <i class="la la-users"></i>
            </div>
          </div>
          <div class="col-7 d-flex align-items-center">
            <div class="numbers">
              <p class="card-category">Total Training Tersedia </p>
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
  <div class="col-md-6">
    <div class="card card-stats card-warning">
      <div class="card-body ">
        <div class="row">
          <div class="col-5">
            <div class="icon-big text-center">
              <i class="la la-bar-chart"></i>
            </div>
          </div>
          <div class="col-7 d-flex align-items-center">
            <div class="numbers">
              <p class="card-category">Total Materi Tersedia</p>
              <h4 class="card-title"> <?php echo $getCountSubstance; ?></h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- <div class="col-md-3">
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
                            <h4 class="card-title"><?php echo $getCountMyTraining; ?></h4>
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
                            <h4 class="card-title"><?php echo $getCountMySubstance; ?> %</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
</div>

<div class="row">
  <div class="col-md-12">
    <div class="card" style="height: 300px;">
      <div class="card-header">
        <div class="row">
          <div class="col">
            <h4 class="card-title">Tren Pengaksesan Materi Baru</h4>
          </div>
        </div>
      </div>
      <div style="overflow-x: auto;">
        <div class="card-body">
          <canvas id="chartContainerPersonal" style="height:100%; width: 100%"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row row-card-no-pd">
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <p class="fw-bold mt-1">Materi yang sudah saya akses</p>
        <h4><b><?php echo $getCountMyDoneLesson; ?></b></h4>
        <a href="<?php echo base_url('Training') ?>" class="btn btn-primary btn-full text-left mt-3 mb-3"><i class="la la-plus"></i>Belajar Lagi</a>
      </div>
      <div class="card-footer">
        <ul class="nav">
          <li class="nav-item"><a class="btn btn-default btn-link" href="#"><i class="la la-history"></i> History</a></li>
          <li class="nav-item ml-auto"><a class="btn btn-default btn-link" href="#"><i class="la la-refresh"></i> Refresh</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="col-md-5">
    <div class="card">
      <div class="card-body">
        <!-- <div class="progress-card">
											<div class="d-flex justify-content-between mb-1">
												<span class="text-muted">Persentase Materi Belum</span>
												<span class="text-muted fw-bold"> <?php echo $getCountMyNotDone; ?>%</span>
											</div>
											<div class="progress mb-2 bg-success" style="height: 7px;">
												<div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo $getCountMyNotDone; ?>%" aria-valuenow="<?php echo $getCountMyDoneLesson; ?>" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-placement="top" title="78%"></div>
											</div>
										</div> -->
        <div class="progress-card">
          <div class="d-flex justify-content-between mb-1">
            <span class="text-muted">Persentase Materi Sudah</span>
            <span class="text-muted fw-bold"> <?php echo $getCountMyDonePercent; ?>%</span>
          </div>
          <div class="progress mb-2" style="height: 7px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $getCountMyDonePercent; ?>%" aria-valuenow="<?php echo $getCountMyDonePercent; ?>" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-placement="top" title=""></div>
          </div>
        </div>
        <div class="progress-card">
          <div class="d-flex justify-content-between mb-1">
            <span class="text-muted">DIISI APA 1</span>
            <span class="text-muted fw-bold"> 70%</span>
          </div>
          <div class="progress mb-2" style="height: 7px;">
            <div class="progress-bar bg-primary" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-placement="top" title="70%"></div>
          </div>
        </div>
        <div class="progress-card">
          <div class="d-flex justify-content-between mb-1">
            <span class="text-muted">DIISI APA 2</span>
            <span class="text-muted fw-bold"> 60%</span>
          </div>
          <div class="progress mb-2" style="height: 7px;">
            <div class="progress-bar bg-warning" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-placement="top" title="60%"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card card-stats">
      <div class="card-body">
        <p class="fw-bold mt-1">Statistic</p>
        <div class="row">
          <div class="col-5">
            <div class="icon-big text-center icon-warning">
              <i class="la la-pie-chart text-warning"></i>
            </div>
          </div>
          <div class="col-7 d-flex align-items-center">
            <div class="numbers">
              <p class="card-category">Materi Saya</p>
              <h4 class="card-title"> <?php echo $getCountMySubstance; ?></h4>
            </div>
          </div>
        </div>
        <hr />
        <div class="row">
          <div class="col-5">
            <div class="icon-big text-center">
              <i class="la la-heart-o text-primary"></i>
            </div>
          </div>
          <div class="col-7 d-flex align-items-center">
            <div class="numbers">
              <p class="card-category">Training Saya</p>
              <h4 class="card-title"><?php echo $getCountMyTraining; ?></h4>
            </div>
          </div>
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
          <h4 class="card-title">Materi Terpopuler</h4>
          </div>
        </div>
        </div>
        <div class="card-body" style="height: 300px;">
          <div id="tableFavoriteSubstance">
            
          <div style="overflow-x: auto;">
              <table class="table table-head-bg-primary mt-4  mx-auto" style="width: 90%">
                <thead>
                  <tr>
                    <th scope="col" class="text-center" style="width: 60px;">#</th>
                    <th scope="col" class="text-center" style="width: 700px;">Materi</th>
                    <th scope="col" class="text-center">Training</th>
                    <th scope="col" class="text-center">Diakses</th>
                  </tr>
                </thead>
                <tbody>
                <?php $i = 1;
                foreach ($getFavoriteSubstance as $e) { ?>
                          <tr>
                            <td><?php echo $i; ?></td>
                            
                            <td><?php echo $e->judul_training_detail; ?></td>
                            <td><?php echo $e->judul_training_header; ?></td>
                            <td><?php echo $e->total; ?></td>
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
                  <th scope="col" class="text-center" style="width: 700px;">Materi</th>
                  <th scope="col" class="text-center">Training</th>
                  <th scope="col" class="text-center">Diakses</th>
                </tr>
              </thead>
              <tbody>
                <?php $i = 1;
                foreach ($getNotDoneLesson as $e) { ?>
                          <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $e->npk; ?></td>
                            <td><?php echo $e->judul_training_header; ?></td>
                            <td><?php echo $e->judul_training_detail; ?></td>
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
		
</div> -->

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
                    <th scope="col" class="text-center" style="width: 700px;">Materi</th>
                    <th scope="col" class="text-center">Training</th>
                    <th scope="col" class="text-center">Diakses</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i = 1;
                  foreach ($getNotDoneLesson as $e) { ?>
                            <tr>
                              <td><?php echo $i; ?></td>
                              <td><?php echo $e->npk; ?></td>
                              <td><?php echo $e->judul_training_header; ?></td>
                              <td><?php echo $e->judul_training_detail; ?></td>
                            </tr>
                    <?php $i++;
                  } ?>
                </tbody>
              </table>
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
  // Get the data from the PHP array
  var trendAccessData = <?php echo json_encode($getMyTrendAccess); ?>;
  console.log(trendAccessData);

  // Create the chart
  var ctx = document.getElementById('chartContainerPersonal').getContext('2d');
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

<?php include __DIR__ . '/../script.php'; ?>
<?php
/* Store the content of the buffer for later use */
$contentPlaceHolder = ob_get_contents();
/* Clean out the buffer, and destroy the output buffer */
ob_end_clean();
/* Call the master page. It will echo the content of the placeholders in the designated locations */
include __DIR__ . "/../layout.php";
?>