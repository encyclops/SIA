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
<div class="container-fluid">
    <div id="showListFpet">
        <div class="row">
            <div class="col-md-12">
                <div class="card p-2 mb-3">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title">Approval FPET</h4>
                                <p class="card-category">Form Penilaian dan Evaluasi Training</p>
                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        <table name="table" class="table table-hover table-head-bg-info my-2">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center" style="width: 50px;">No.</th>
                                    <th scope="col" class="text-center" style="width: 500px;">Trainer</th>
                                    <th scope="col" class="text-center" style="width: 700px;">Target</th>
                                    <th scope="col" class="text-center" style="width: 500px;">Approval Atasan</th>
                                    <th scope="col" class="text-center" style="width: 500px;">Approval HR</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tBodymainTable">
                                <?php
                                if (empty($fpet)) {
                                    echo '<tr><td colspan="6" class="text-center">Belum ada data</td></tr>';
                                } else {
                                    $i = 1;
                                    foreach ($fpet as $t) {
                                        // Define status text based on the value of statusApproved
                                        if (isset($t['approvedHr']) == $this->session->userdata('npk') && isset($t['approved']) != $this->session->userdata('npk') && isset($t['statusApproved == 2)'])) {
                                        } else {
                                            $statusText = '';
                                            switch (isset($t['statusApproved']) ? $t['statusApproved'] : '') {
                                                case 0:
                                                    $statusText = 'Ditolak';
                                                    break;
                                                case 1:
                                                    $statusText = 'Disetujui';
                                                    break;
                                                case 2:
                                                default:
                                                    $statusText = 'Belum disetujui';
                                                    break;
                                            }

                                            $statusTextHr = '';
                                            switch (isset($t['statusApprovedHr']) ? $t['statusApprovedHr'] : '') {
                                                case 0:
                                                    $statusTextHr = 'Ditolak';
                                                    break;
                                                case 1:
                                                    $statusTextHr = 'Disetujui';
                                                    break;
                                                case 2:
                                                default:
                                                    $statusTextHr = 'Belum disetujui';
                                                    break;
                                            }
                                ?>
                                            <tr>
                                                <td><?php echo $i ?></td>
                                                <td><?php echo isset($t['nama']) ? $t['nama'] : ''; ?></td>
                                                <td><?php echo isset($t['target']) ? $t['target'] : ''; ?></td>
                                                <td><?php echo $statusText ?></td>
                                                <td><?php echo $statusTextHr ?></td>
                                                <td class="text-center"><a href="javascript:void(0)" onclick="showDetailApprovalFpet(<?php echo isset($t['idFpet']) ? $t['idFpet'] : ''; ?>)" class="btn btn-primary"></i>Detail</a></th>

                                            </tr>
                                <?php
                                            $i++;
                                        }
                                    }
                                    if ($i == 1) {
                                        echo '<tr><td colspan="6" class="text-center">Belum ada data</td></tr>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="addFpet" style="display: none;">
        <div class="loader-container" id="loaderDiv">
            <div class="loader">
                <div class="loader-reverse"></div>
            </div>
            <p class="m-0">&emsp;Loading data...</p>
        </div>
        <div class="col-md-12">
            <form id="formFpet" method="post" enctype="multipart/form-data" role="form">
                <div class="card p-2">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <div class="card-title" id="cardTitle">Form Pengajuan dan Evaluaasi Training</div>
                                <p class="card-category" id="cardCategory">FPET / Tambah FPET</p>
                            </div>
                            <div class="col">
                                <div class="d-flex justify-content-end" id="btnDetailFpet" style="display: none;">
                                    <a id="approveBtnFpet" class="btn btn-info" style="margin-right: 9px; display: none;"></i> Approve</a>
                                    <a id="rejectBtnFpet" class="btn btn-danger " style="margin-right: 9px; display: none;"></i> Reject</a>
                                    <a id="approveBtnFpetHr" href="javascript:void(0)" onclick="showFormTrainModal()" class="btn btn-info" style="margin-right: 9px; display: none;"></i> Approve HR</a>
                                    <a id="rejectBtnFpetHr" class="btn btn-danger " style="margin-right: 9px; display: none;"></i> Reject HR</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="border-bottom: 1px solid #ebedf2 !important;">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="trainer">Pilih Calon Trainer <span style="color: red;">*</span></label>
                                    <select class="form-control input-pill mb-3" id="trainer" name="trainer">
                                        <option disabled selected>Pilih</option>
                                        <?php foreach ($employee as $e) : ?>
                                            <option value="<?php echo $e->NPK; ?>"><?php echo $e->NAMA; ?> (<?php echo $e->DEPARTEMEN; ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="trainer">Saran training <span style="color: red;">*</span></label>
                                    <input type="text" maxlength="40" class="form-control input-pill mb-3" name="trainSuggest" id="trainSuggest" placeholder="Masukkan Saran Training">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="my-2">Kondisi Aktual</label>
                                <textarea class="form-control" id="actual" name="actual" rows="2" maxlength="200" placeholder="Masukkan pendapat Anda"></textarea>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label>Kemampuan saat ini <span style="color: red;">*</span></label><br />
                                    <label class="form-radio-label">
                                        <input class="form-radio-input" type="radio" name="rActual" id="rActual" value="0" required>
                                        <span class="form-radio-sign">0%</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="rActual" id="rActual" value="25">
                                        <span class="form-radio-sign">25%</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="rActual" id="rActual" value="50">
                                        <span class="form-radio-sign">50%</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="rActual" id="rActual" value="75">
                                        <span class="form-radio-sign">75%</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="rActual" id="rActual" value="100">
                                        <span class="form-radio-sign">100%</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="my-2">Target / Standard</label>
                                <textarea class="form-control" id="target" name="target" rows="2" maxlength="200" placeholder="Masukkan pendapat Anda"></textarea>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label for="chooseTrain">Kemampuan Yang diinginkan<span style="color: red;">*</span></label> <br />
                                    <label class="form-radio-label">
                                        <input class="form-radio-input" type="radio" name="rTarget" id="rTarget" value="0" required>
                                        <span class="form-radio-sign">0%</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="rTarget" id="rTarget" value="25">
                                        <span class="form-radio-sign">25%</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="rTarget" id="rTarget" value="50">
                                        <span class="form-radio-sign">50%</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="rTarget" id="rTarget" value="75">
                                        <span class="form-radio-sign">75%</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="rTarget" id="rTarget" value="100">
                                        <span class="form-radio-sign">100%</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-md-6">
                                <label class="my-2">Evaluasi Hasil</label>
                                <textarea class="form-control" id="eval" name="eval" rows="2" maxlength="200" placeholder="Masukkan pendapat Anda"></textarea>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label for="chooseTrain">Evaluasi Hasil<span style="color: red;">*</span></label> <br />
                                    <label class="form-radio-label">
                                        <input class="form-radio-input" type="radio" name="rEval" id="rEval" value="0" required>
                                        <span class="form-radio-sign">0%</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="rEval" id="rEval" value="25">
                                        <span class="form-radio-sign">25%</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="rEval" id="rEval" value="50">
                                        <span class="form-radio-sign">50%</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="rEval" id="rEval" value="75">
                                        <span class="form-radio-sign">75%</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="rEval" id="rEval" value="100">
                                        <span class="form-radio-sign">100%</span>
                                    </label>
                                </div>
                            </div>
                        </div> -->
                        <div class="row">
                            <div class="col-md-12">
                                <label class="my-2">Keterangan dan Saran</label>
                                <textarea class="form-control" id="notes" name="notes" rows="1" maxlength="200" placeholder="Masukkan pendapat Anda"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="approvedHR">Pilih Pihak HRD yang Menyetujui <span style="color: red;">*</span></label>
                                    <select class="form-control input-pill mb-3" id="approvedHR" name="approvedHr">
                                        <option disabled selected>Pilih </option>
                                        <?php foreach ($employee as $e) : ?>
                                            <option value="<?php echo $e->NPK; ?>"><?php echo $e->NAMA; ?> (<?php echo $e->DEPARTEMEN; ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="approved">Pilih Pihak yang Menyetujui <span style="color: red;">*</span></label>
                                    <select class="form-control input-pill mb-3" id="approved" name="approved">
                                        <option disabled selected>Pilih </option>
                                        <?php foreach ($employee as $e) : ?>
                                            <option value="<?php echo $e->NPK; ?>"><?php echo $e->NAMA; ?> (<?php echo $e->DEPARTEMEN; ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="row" id="makeTrain" style=" display: none;">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label>Apakah Anda ingin mengambil usulan training bedasar training yang ada? <span style="color: red;">*</span></label><br />
                                    <label class="form-radio-label">
                                        <input class="form-radio-input" type="radio" name="rEstablished" id="rEstablishedY" value="Ya" required onchange="toggleTrainSections()">
                                        <span class="form-radio-sign">Ya</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="rEstablished" id="rEstablishedN" value="Tidak" onchange="toggleTrainSections()">
                                        <span class="form-radio-sign">Tidak</span>
                                    </label>
                                </div>
                            </div>
                            <div id="trainSection1" style="display: none;">
                                <div class="row">
                                    <div class="col">
                                        <input type="text" hidden class="form-control input-pill mb-3" name="idFpet" id="idFpet">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="chooseTrain">Pilih Training <span style="color: red;">*</span></label>
                                    <select class="form-control" id="chooseTrain" name="chooseTrain">
                                        <option disabled selected>Pilih </option>
                                        <?php foreach ($training as $t) : ?>
                                            <option value="<?php echo $t->id_training_header; ?>"><?php echo $t->judul_training_header; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="trainSection2" style="display: none;">
                            <div class="row">
                                <div class="col">
                                    <label class="my-2">Judul Training</label>
                                    <input type="text" class="form-control input-pill mb-3" name="title" id="title" placeholder="Masukkan Judul Training">
                                </div>
                                <div class="col">
                                    <label class="my-2">Lembaga Pelaksana</label><br />

                                    <input type="text" class="form-control input-pill mb-3" name="educator" id="educator" placeholder="Masukkan Lembaga Pelaksana">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label class="my-2">Jadwal training</label>
                                    <input type="date" min="<?php echo date('Y-m-d') ?>" class="form-control input-pill mb-3" name="schedule" id="schedule" placeholder="Pilih Jadwal">
                                </div>
                                <div class="col">
                                    <label class="my-2">Biaya Pelaksanaan</label><br />
                                    <input type="text" class="form-control input-pill mb-3" name="cost" id="cost" placeholder="Masukkan Biaya ">
                                </div>
                            </div>
                        </div> -->
                        <div class="card-body" id="divBackSub">
                            <button type="button" id="btnSubApprove" class="btn btn-success float-right">Simpan</button>
                            <a href="javascript:void(0)" onclick="changeFormFpet('main')" class="btn btn-danger"></i> Kembali</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="modal fade" id="trainModal" tabindex="-1" role="dialog" aria-labelledby="trainModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 950px">
            <div class="modal-content" style="width: 950px;">
                <div class="card p-2 mb-0">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title" id="trainModalLabel">Tambah Train</h4>
                                <p class="card-category">Approval FPET / Tambah Training</p>
                            </div>
                            <div class="col d-flex justify-content-end">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <form id="formMakeTrain" method="post" enctype="multipart/form-data" role="form">
                            <div class="card-body p-0">
                                <div class="row" id="makeTrain" style=" display: none;">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <label>Apakah Anda ingin mengambil usulan training bedasar training yang ada? <span style="color: red;">*</span></label><br />
                                            <label class="form-radio-label ml-3">
                                                <input class="form-radio-input" type="radio" name="rEstablished" id="rEstablishedY" value="1" onchange="toggleTrainSections()">
                                                <span class="form-radio-sign">Ya</span>
                                            </label>
                                            <label class="form-radio-label ml-3">
                                                <input class="form-radio-input" type="radio" name="rEstablished" id="rEstablishedN" value="0" onchange="toggleTrainSections()">
                                                <span class="form-radio-sign">Tidak</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="trainer">Saran training <span style="color: red;">*</span></label>
                                            <input type="text" maxlength="40" class="form-control input-pill mb-3" name="trainSuggestModal" id="trainSuggestModal" placeholder="Masukkan Saran Training" readonly>
                                        </div>
                                    </div>
                                    <div id="trainSection1" style="display: none;">
                                        <div class="row">
                                            <div class="col">
                                                <input type="text" hidden class="form-control input-pill mb-3" name="idFpet" id="idFpet">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="chooseTrain">Pilih Training <span style="color: red;">*</span></label>
                                            <select class="form-control" id="chooseTrain" name="chooseTrain">
                                                <option disabled selected>Pilih </option>
                                                <?php foreach ($training as $t) : ?>
                                                    <option value="<?php echo $t->id_training_header; ?>"><?php echo $t->judul_training_header; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="trainSection2" style="display: none;">
                                    <div class="row">
                                        <div class="col">
                                            <label class="my-2">Judul Training</label>
                                            <input type="text" class="form-control input-pill mb-3" name="title" id="title" placeholder="Masukkan Judul Training">
                                        </div>
                                        <div class="col">
                                            <label class="my-2">Lembaga Pelaksana</label><br />

                                            <input type="text" class="form-control input-pill mb-3" name="educator" id="educator" placeholder="Masukkan Lembaga Pelaksana">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label class="my-2">Jadwal training</label>
                                            <input type="date" min="<?php echo date('Y-m-d') ?>" class="form-control input-pill mb-3" name="schedule" id="schedule" placeholder="Pilih Jadwal">
                                        </div>
                                        <div class="col">
                                            <label class="my-2">Biaya Pelaksanaan</label><br />
                                            <input type="number" class="form-control input-pill mb-3" name="cost" id="cost" placeholder="Masukkan Biaya ">
                                        </div>
                                    </div>
                                    <input type="text" hidden class="form-control input-pill mb-3" name="npk" id="npk">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <label>Pilih Jenis Traininsg <span style="color: red;">*</span></label><br />
                                                <label class="form-radio-label">
                                                    <input class="form-radio-input" type="radio" name="categoryTrain" id="categoryTrain" value="Inhouse">
                                                    <span class="form-radio-sign">In-House</span>
                                                </label>
                                                <label class="form-radio-label ml-3">
                                                    <input class="form-radio-input" type="radio" name="categoryTrain" id="categoryTrain" value="Outhouse">
                                                    <span class="form-radio-sign">Out-House</span>
                                                </label>
                                                <label class="form-radio-label ml-3">
                                                    <input class="form-radio-input" type="radio" name="categoryTrain" id="categoryTrain" value="Elearning">
                                                    <span class="form-radio-sign">Elearning</span>
                                                </label>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" onclick="submitTrain()" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Function to disable options in rTarget greater than selected rActual
    function disableRTargetOptions(checkedValue) {
        var rTargetRadios = document.getElementsByName('rTarget');
        rTargetRadios.forEach(radio => {
            if (parseInt(radio.value) < parseInt(checkedValue)) {
                radio.disabled = true;
            } else {
                radio.disabled = false;
            }
        });
    }

    // Function to disable options in rActual less than selected rTarget
    function disableRActualOptions(checkedValue) {
        var rActualRadios = document.getElementsByName('rActual');
        rActualRadios.forEach(radio => {
            if (parseInt(radio.value) > parseInt(checkedValue)) {
                radio.disabled = true;
            } else {
                radio.disabled = false;
            }
        });
    }

    // Event listener for rActual radio buttons
    var rActualRadios = document.getElementsByName('rActual');
    rActualRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                disableRTargetOptions(this.value);
            }
        });
    });

    // Event listener for rTarget radio buttons
    var rTargetRadios = document.getElementsByName('rTarget');
    rTargetRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                disableRActualOptions(this.value);
            }
        });
    });

    function doUpdate() {
        document.getElementById('btnSubApprove').style.display = 'block';
        enableFormElements();
    }

    function clearFormFpet() {
        // Reset input values
        document.getElementById('idFpet').value = '';
        document.getElementById('trainer').value = '';
        document.getElementById('actual').value = '';
        document.getElementById('target').value = '';
        document.getElementById('notes').value = '';
        document.getElementById('approvedHR').value = '';
        document.getElementById('approved').value = '';

        // Reset radio buttons for rActual
        var rActualRadios = document.getElementsByName('rActual');
        rActualRadios.forEach(radio => {
            radio.checked = false;
        });

        // Reset radio buttons for rTarget
        var rTargetRadios = document.getElementsByName('rTarget');
        rTargetRadios.forEach(radio => {
            radio.checked = false;
        });

        // Reset radio buttons for rEval
        var rEvalRadios = document.getElementsByName('rEval');
        rEvalRadios.forEach(radio => {
            radio.checked = false;
        });

        // Reset select elements
        document.getElementById('chooseTrain').selectedIndex = 0;
        document.getElementById('approvedHR').selectedIndex = 0;
        document.getElementById('approved').selectedIndex = 0;

        // Reset radio buttons for rEstablished
        document.getElementById('rEstablishedY').checked = false;
        document.getElementById('rEstablishedN').checked = false;
    }

    function save() {
        var formElements = document.getElementById("formFpet");
        formElements.submit();
    }

    function submitTrain() {
        var formElements = document.getElementById("formMakeTrain");
        formElements.submit();
    }

    function disableFormElements() {
        // Get all form elements
        var formElements = document.getElementById("formFpet").elements;

        // Iterate through each form element
        for (var i = 0; i < formElements.length; i++) {
            // Set disabled attribute to true
            formElements[i].disabled = true;
        }
    }

    function enableFormElements() {
        // Get all form elements
        var formElements = document.getElementById("formFpet").elements;

        // Iterate through each form element
        for (var i = 0; i < formElements.length; i++) {
            // Set disabled attribute to false
            formElements[i].disabled = false;
        }
    }



    var rowFtpe = 0;

    function changeFormFpet() {
        var formElement = document.getElementById('formFpet');
        formElement.removeAttribute('action');
        document.getElementById("showListFpet").style.display = 'block';
        document.getElementById("addFpet").style.display = 'none';
        document.getElementById("approveBtnFpetHr").style.display = 'none';
        document.getElementById("rejectBtnFpetHr").style.display = 'none';
        document.getElementById("rejectBtnFpet").style.display = 'none';
        document.getElementById("approveBtnFpet").style.display = 'none';
    }

    function showAdd(kode) {
        enableFormElements();
        clearFormFpet();

        var formElement = document.getElementById('formFpet');
        formElement.setAttribute('action', '<?php echo base_url('FPET/saveFpet/') ?>');
        document.getElementById("showListFpet").style.display = 'none';
        document.getElementById("addFpet").style.display = 'block';
    }


    // function addRowFtpe() {
    // 	var tableBody = document.getElementById('tBodyFtpe');
    // 	var idNow = getMaxRow(tableBody, 'rowFtpe');

    // 	var contentRow = document.createElement('tr');
    // 	contentRow.id = 'rowFtpe' + idNow;

    // 	createNumberCell(idNow, contentRow);
    // 	createInputCell('gap' + idNow, 'text', 'Kondisi Aktual...', contentRow, 1);
    // 	createInputCell('target' + idNow, 'text', 'Target...', contentRow, 1);
    // 	createInputCell('result' + idNow, 'text', 'Evaluasi Hasil...', contentRow, 1);
    // 	createDeleteActionCell('deleteRowFtpe' + idNow, contentRow);

    // 	rowFtpe++;
    // 	isDataExistTable(7, tableBody, rowFtpe);
    // 	tableBody.appendChild(contentRow);

    // 	document.getElementById('deleteRowFtpe' + idNow).onclick = function() {
    // 		removeRowFtpe(idNow, 'rowFtpe', tableBody);
    // 	};
    // }

    // function removeRowFtpe(id, rowName, tableBody) {
    // 	var column = tableBody.closest('table').querySelector('thead').querySelectorAll('th').length;
    // 	if (column == 4) column = 6;
    // 	console.log(rowName + id);
    // 	document.getElementById(rowName + id).remove();
    // 	window[rowName] -= 1;
    // 	isDataExistTable(column, tableBody, window[rowName]);
    // }

    // function isDataExistTable(colspan, tableBody, row) {
    // 	if (row == 0) {
    // 		if (document.getElementById('emptyData' + tableBody.id) != null) {
    // 			document.getElementById('emptyData' + tableBody.id).remove();
    // 		}
    // 		var departmentRow = document.createElement('tr');
    // 		if (colspan == 1) colspan = 6;

    // 		var emptyDataCell = document.createElement('td');
    // 		emptyDataCell.colSpan = colspan;
    // 		emptyDataCell.id = 'emptyData' + tableBody.id;
    // 		emptyDataCell.textContent = 'Belum ada data.';
    // 		emptyDataCell.classList.add('text-center');
    // 		departmentRow.appendChild(emptyDataCell);

    // 		tableBody.appendChild(departmentRow);
    // 	} else if (document.getElementById('emptyData' + tableBody.id) != null) {
    // 		document.getElementById('emptyData' + tableBody.id).remove();
    // 	}
    // }

    // function createNumberCell(text, tr) {
    // 	var cell = document.createElement('td');
    // 	cell.textContent = text + '.';
    // 	cell.classList.add('text-center');
    // 	tr.appendChild(cell);
    // }

    // function createDeleteActionCell(idname, tr) {
    // 	var icon = document.createElement('i');
    // 	icon.classList.add('la', 'la-trash-o');

    // 	var a = document.createElement('a');
    // 	a.href = 'javascript:void(0)';
    // 	a.id = a.name = idname;
    // 	a.classList.add('btn', 'btn-danger');
    // 	a.appendChild(icon);

    // 	var cell = document.createElement('td');
    // 	cell.appendChild(a);
    // 	tr.appendChild(cell);
    // }


    // Function to toggle visibility of trainSection1 and trainSection2
    function toggleTrainSections() {
        var rEstablishedValue = document.querySelector('input[name="rEstablished"]:checked').value;

        if (rEstablishedValue == "1") {
            document.getElementById('trainSection1').style.display = 'block';
            document.getElementById('trainSection2').style.display = 'none';
        } else {
            document.getElementById('trainSection1').style.display = 'none';
            document.getElementById('trainSection2').style.display = 'block';
        }
    }


    // Call toggleTrainSections initially to set the initial visibility state
    toggleTrainSections();



    async function showDetailApprovalFpet(id) {
        if (id != '0') {
            var formElement = document.getElementById('formFpet');
            formElement.setAttribute('action', '<?php echo base_url('FPET/modifyFpet/') ?>' + id);
            fetch('<?= base_url('FPET/showDetail/') ?>' + id)
                .then(response => {
                    return response.json(); // Parse response as JSON
                })
                .then(data => {
                    console.log(data);
                    var dataFpet = data.dataFpet; // Extract dataFpet object from response

                    if (dataFpet) {
                        disableFormElements();
                        // Update input values

                        document.getElementById('trainSuggest').value = dataFpet.trainSuggest || '';
                        document.getElementById('trainSuggestModal').value = dataFpet.trainSuggest || '';
                        document.getElementById('idFpet').value = dataFpet.idFpet || '';
                        document.getElementById('trainer').value = dataFpet.trainerNpk || '';
                        document.getElementById('actual').value = dataFpet.actual || '';
                        document.getElementById('target').value = dataFpet.target || '';
                        document.getElementById('npk').value = dataFpet.trainerNpk || '';
                        document.getElementById('notes').value = dataFpet.notes || '';
                        document.getElementById('approvedHR').value = dataFpet.approvedHr || '';

                        document.getElementById('approved').value = dataFpet.approved || '';
                        var rActualRadios = document.getElementsByName('rActual');
                        rActualRadios.forEach(radio => {
                            if (radio.value === dataFpet.ractual.toString()) {
                                radio.checked = true;
                            }
                        });
                        // Set the radio button for rTarget based on the value received
                        var rTargetRadios = document.getElementsByName('rTarget');
                        rTargetRadios.forEach(radio => {
                            if (radio.value === dataFpet.rtarget.toString()) {
                                radio.checked = true;
                            }
                        });

                        // Set the radio button for rEval based on the value received
                        var rEvalRadios = document.getElementsByName('rEval');
                        rEvalRadios.forEach(radio => {
                            if (radio.value === dataFpet.reval.toString()) {
                                radio.checked = true;
                            }
                        });
                        var formElement = document.getElementById('formMakeTrain');
                        formElement.setAttribute('action', '<?php echo base_url('FPET/approveHrFpet/') ?>' + id);

                        document.getElementById('rTarget' + (dataFpet.rTarget || '')).checked = true;
                        document.getElementById('rEval' + (dataFpet.rEval || '')).checked = true;
                        document.getElementById('btnSubApprove').style.display = 'none';
                        // Show the buttons
                        document.getElementById('btnDetailFpet').style.display = 'block';
                        if (dataFpet.status == '1') {
                            if (dataFpet.approved == <?php echo $this->session->userdata('npk'); ?> && dataFpet.statusApproved === 2) {
                                document.getElementById('rejectBtnFpet').style.display = 'inline-block';
                                document.getElementById('approveBtnFpet').style.display = 'inline-block';
                                var rejectBtnFpet = document.getElementById('rejectBtnFpet');
                                rejectBtnFpet.setAttribute('href', '<?= base_url('FPET/rejectFpet/') ?>' + id);
                                var approveBtnFpet = document.getElementById('approveBtnFpet');
                                approveBtnFpet.setAttribute('href', '<?= base_url('FPET/approveFpet/') ?>' + id);
                            }
                            if (dataFpet.approvedHr == <?php echo $this->session->userdata('npk'); ?> && (dataFpet.statusApproved === 1 || dataFpet.statusApproved === 0) && dataFpet.statusApprovedHr == 2) {
                                document.getElementById('rejectBtnFpetHr').style.display = 'inline-block';
                                document.getElementById('approveBtnFpetHr').style.display = 'inline-block';
                                var rejectBtnFpet = document.getElementById('rejectBtnFpetHr');
                                rejectBtnFpet.setAttribute('href', '<?= base_url('FPET/rejectHrFpet/') ?>' + id);
                                // var approveBtnFpet = document.getElementById('approveBtnFpetHr');
                                // approveBtnFpet.setAttribute('href', '<?= base_url('FPET/approveHrFpet/') ?>' + id);
                                document.getElementById('makeTrain').style.display = 'block';
                                // var makeTrainElements = document.getElementById("makeTrain").getElementsByTagName("input");

                                // Enable form elements in makeTrain, trainSection1, and trainSection2
                                // enableFormElements(document.getElementById("makeTrain"));
                                // enableFormElements(document.getElementById("trainSection1"));
                                // enableFormElements(document.getElementById("trainSection2"));
                            }
                        }
                        document.getElementById("showListFpet").style.display = 'none';
                        document.getElementById("addFpet").style.display = 'block';
                        callLoader();

                    } else {
                        console.error('Error: No data found for id ' + id);
                    }
                })
                .catch(error => {
                    console.error('Error fetching data showdetail:', error);
                });
        }
    }

    function enableFormElements(container) {
        // Get all form elements within the container 
        var elements = container.querySelectorAll('input, select');
        // Iterate through each form element
        for (var i = 0; i < elements.length; i++) {
            // Remove the disabled attribute
            elements[i].disabled = false;
        }

    }

    function showFormTrainModal() {


        new bootstrap.Modal(document.getElementById('trainModal')).show();

    };
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