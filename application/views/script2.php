<!-- Starter Function -->
<script>
	// Global Variables
	var empArrAdmin = [];
	var empArrNon = [];
	var tags = [];
	var admins;
	var rowCountMateriForm = 0;
	var isAdmin = '<?php echo $this->session->userdata['role']; ?>' == 'admin';
	var trStat = 0;

	function confirmDeleteTraining(id) {
		Swal.fire({
			title: 'Konfirmasi Hapus Training',
			text: 'Apakah Anda yakin ingin menghapus data ini?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya',
			cancelButtonText: 'Tidak'
		}).then((result) => {
			if (result.isConfirmed) {
				window.location.href = '<?= base_url('Training/modifyTraining/') ?>' + id;
			}
		});
	}

	function changeForm(kode, status) {
		var listCardDiv = document.getElementById('listCardDiv');
		var detailFormDiv = document.getElementById('detailFormDiv');
		changeDisplayOfElements('block', ['temaDiv', 'substanceDiv']);

		if (kode.includes('edit')) {

			changeDisplayOfElements('block', ['allEmpDiv', 'submitBtn', 'substanceTableEdit', 'addFileBtn']);
			changeDisplayOfElements('none', [isAdmin ? 'detailEmpDiv' : 'detailOnlyDiv', 'substanceTableDetail', 'editBtn', 'deleteBtn', 'publishBtn']);
			changeTitle('Ubah Training', true, status);
			var badgeElements = document.querySelectorAll('.badge tags');
			badgeElements.forEach(function(element) {
				element.style.pointerEvents = 'pointer';
			});
		} else if (kode === 'main') {
			listCardDiv.style.display = 'block';
			detailFormDiv.style.display = 'none';
			clearForm();
		} else if (kode === 'tambah') {
			listCardDiv.style.display = 'none';
			detailFormDiv.style.display = 'block';
			isDataTableExist(rowCountMateriForm, 'x', 4, 'emptyData', 'tBodySubstanceTableEdit2');
			isDataTableExist(rowCountMateriForm, 'x', 4, 'emptyData', 'tBodySubstanceTableEdit');
			document.getElementById('formTraining').setAttribute('action', '<?php echo base_url('Training/saveTraining') ?>');
			changeDisplayOfElements('block', ['allEmpDiv', 'submitBtn', 'substanceTableEdit', 'addFileBtn']);
			changeDisplayOfElements('none', [isAdmin ? 'detailEmpDiv' : 'detailOnlyDiv', 'substanceTableDetail']);
			var badgeElements = document.querySelectorAll('.badge tags');
			badgeElements.forEach(function(element) {
				element.style.pointerEvents = 'pointer';
			});
			changeTitle('Tambah Training', true, '');
		} else if (kode === 'detail') {
			listCardDiv.style.display = 'none';
			detailFormDiv.style.display = 'block';
			changeDisplayOfElements('none', ['allEmpDiv', 'submitBtn', 'substanceTableEdit', 'addFileBtn']);
			changeDisplayOfElements('block', [isAdmin ? 'detailEmpDiv' : 'detailOnlyDiv', 'substanceTableDetail']);
			changeTitle('Detail Training', true, '');
			var badgeElements = document.querySelectorAll('.badge tags');
			badgeElements.forEach(function(element) {
				element.style.pointerEvents = 'none';
				element.removeAttribute('onclick');
			});
		}
	}

	function clearForm() {
		document.getElementById('temaTraining').value = '';
		document.getElementById('pemateri').value = '';
		document.getElementById('search_keyword').value = '';
		empArrAdmin = [];
		empArrNon = [];
		searchKeyword('', '', 'allEmpTable');
		rowCountMateriForm = 0;
		document.getElementById('allEmpTableDiv').scrollTop = 0;
		populateTagsSection(<?php echo json_encode($tags) ?>, 'clear');
		toggleAll(false);
		document.getElementById('dropdownMenu1').textContent = 'ALL';
	}

	function changeDisplayOfElements(displayValue, elementsArray) {
		elementsArray.forEach((elementId) => {
			document.getElementById(elementId).style.display = displayValue;
		});
	}

	function changeTitle(title, call, status) {
		document.getElementById('cardTitle').textContent = title;
		document.getElementById('cardCategory').textContent = 'Training / ' + title;
		document.getElementById('temaTraining').readOnly = title.includes('Tambah') || title.includes('Ubah') ? false : true;
		document.getElementById('pemateri').readOnly = title.includes('Tambah') || title.includes('Ubah') ? false : true;
		if (status == 2) {
			document.getElementById('temaTraining').readOnly = true;
			document.getElementById('pemateri').readOnly = true;
		}
		if (call) callLoader();
	}

	function callLoader() {
		var loader = document.getElementById('loaderDiv');
		setTimeout(function() {
			loader.classList.add('fade-out');
			setTimeout(function() {
				loader.style.display = 'none';
			}, 500);
		}, 500);
		loader.style.display = '';
		loader.classList.remove('fade-out');
	}

	function activateClassActive(tabName) {
		var tabs = document.querySelectorAll('#statusTabs .nav-link');
		tabs.forEach(function(tab) {
			tab.classList.remove('active');
		});

		var clickedTab = document.getElementById(tabName + 'Tab');
		clickedTab.classList.add('active');
	}

	function createInputCell(idname, type, placeholder, tr) {

		var cell = document.createElement('td');
		var input = document.createElement('input');
		input.type = type;
		input.id = idname;
		input.name = idname;
		input.classList.add('form-control', 'form-control-sm');
		console.log("dsfsd" + idname);
		if (idname.includes('materiTitle')) {
			console.log("dsd" + idname);
			input.required = true;
		}

		type == 'hidden' ? input.value = placeholder : input.placeholder = placeholder;
		if (type == 'hidden') {
			tr.appendChild(input);
		} else {
			if (type == 'file') {
				input.classList.replace('form-control', 'form-control-file');
				input.accept = '.pdf';
				input.addEventListener('change', function() {
					var file = this.files[0];
					if (file) {
						var fileType = file.type.toLowerCase();
						if (fileType !== 'application/pdf' || (file && file.size > 10 * 1024 * 1024)) {
							let errorMessage = '';
							if (fileType !== 'application/pdf') {
								errorMessage = 'Upload File harus berjenis PDF!';
							} else if (file && file.size > 10 * 1024 * 1024) {
								errorMessage = 'Ukuran file maksimal 10MB!';
							}
							Swal.fire({
								icon: 'error',
								title: 'Error Upload File!',
								text: errorMessage,
								confirmButtonColor: '#3085d6',
								confirmButtonText: 'OK'
							}).then((result) => {
								if (result.isConfirmed) {
									this.value = '';
								}
							});
						}
					}
				});
			}
			cell.appendChild(input);
			tr.appendChild(cell);
		}
	}

	function createFileCell(id, path, code, tr) {
		var cell = document.createElement('td');
		cell.classList.add('text-center');

		var a = document.createElement('a');
		fetch('<?= base_url('Plus/hasRead/') ?>' + id)
			.then(response => response.json())
			.then(dataExists => {
				var fileText = 'View PDF';
				console.log('Data exists:', dataExists);
				if (dataExists) {
					if (code == 'detail') fileText += ' \u00A0\uD83D\uDC41';
				}
				a.textContent = fileText;
			})
			.catch(error => {
				console.error('Error fetching data:', error);
			});
		a.href = 'javascript:void(0)';
		a.addEventListener('click', function(event) {
			if (code == 'detail') {
				fetch('<?= base_url('Training/addProgress/') ?>' + id)

					.then(response => {
						return response.text();
					})
					.catch(error => {
						console.error('Error fetching data:', error);
					});
			}
			var modal = document.getElementById('pdfModal');
			modal.style.display = 'block';

			var pdfViewer = document.getElementById('pdfViewer');
			pdfViewer.src = `${path}#toolbar=0&zoom=100&view=FitH`;

			var closeButton = document.getElementsByClassName('close')[0];
			closeButton.onclick = function() {
				if (code == 'main') window.location.reload();
				else {
					modal.style.display = 'none';
					pdfViewer.src = '';
					pdfViewer.contentWindow.document.body.oncontextmenu = function() {
						return false;
					}
				}
			}

			window.onclick = function(event) {
				if (event.target === modal) {
					modal.style.display = 'none';
					pdfViewer.src = '';
					pdfViewer.contentWindow.document.body.oncontextmenu = function() {
						return false;
					};
				}
			};
		});

		cell.appendChild(a);
		tr.appendChild(cell);
	}

	function createTextCell(text, tr, code, align) {
		var cell = document.createElement('td');
		cell.textContent = (code === 'number') ? text + '.' : text;
		if (align == 'center') cell.classList.add('text-center');
		tr.appendChild(cell);
	}

	function createBadgeApproval(idDetail, npk, id, tr) {
		<?php if ($this->session->userdata('role') == 'admin') { ?>
			var cell = document.createElement('td');
			cell.classList.add('text-center');

			var spanA = document.createElement("span");
			spanA.className = "badge badge-success";
			spanA.textContent = "Approve";
			spanA.style.cursor = "pointer";
			spanA.onclick = function() {
				if (!spanA.disabled) {
					modifyApproval(idDetail, npk, id, 1);
					cell.removeChild(spanR);
					spanA.disabled = true;
				}
			};
			cell.appendChild(spanA);

			var spanR = document.createElement("span");
			spanR.className = "badge badge-danger";
			spanR.textContent = "Reject";
			spanR.style.cursor = "pointer";
			spanR.onclick = function() {
				if (!spanR.disabled) {
					modifyApproval(idDetail, npk, id, 3);
					cell.removeChild(spanA);
					spanR.disabled = true;
				}
			};
			cell.appendChild(spanR);

			tr.appendChild(cell);

		<?php } ?>
	}

	async function createCheckboxCell(name, value, tr, id, code, stat) {
		try {
			//	console.log("stat", name, value, tr, id, code, stat);
			var tema = value.match(/[a-zA-Z]+|\d+/g)[0];
			var npk = value.match(/[a-zA-Z]+|\d+/g)[1];

			var cell = document.createElement('td');
			cell.classList.add('text-center');

			var label = document.createElement('label');
			label.classList.add('form-check-label');

			var input = document.createElement('input');
			input.type = 'checkbox';
			input.name = name;
			input.value = value;
			input.classList.add('form-check-input');
			input.style.position = 'fixed';

			// A condition to disable checking in training detail, specific for admin
			if (code != 'edit' && admins.includes(npk)) input.checked = input.disabled = true;

			// A condition to check whether the participant is allowed to edit or not in training detail
			if (code == 1) {
				input.checked = true;
			}

			// A condition when editing training
			else if (code == 'edit' && id != null && stat != 'admin') {
				// Condition if the user is not an admin
				if (id == 'non') {
					// Condition if the data choosen by non-admin has the value checked
					if (empArrNon != null && empArrNon.includes(value)) {
						input.checked = true;
					}
				}
				// Condition if the data choosen is an admin
				if (empArrAdmin != null && empArrAdmin.includes(value)) {
					input.checked = true;
					if (!isAdmin) input.disabled = true;
				}
			}

			// A condition when adding new admin
			else if (stat == 'admin' && admins.includes(value)) {
				input.checked = input.disabled = true;
			}

			label.appendChild(input);

			var span = document.createElement('span');
			span.classList.add('form-check-sign');
			span.id = value;
			span.onclick = function() {
				if (code == 'edit') addEmp(value);
				else if (stat != 2) {
					const val = document.querySelector('input[name="chkBoxAcc"][value="' + value + '"]').checked ? 0 : 1;
					modifyAccess(tema, val, npk, id);
				}
			}
			label.appendChild(span);

			cell.appendChild(label);
			tr.appendChild(cell);
		} catch (error) {
			console.error('Error:', error);
		}
	}

	function createDeleteActionCell(idname, tr) {
		var icon = document.createElement('i');
		icon.classList.add('la', 'la-trash-o');

		var a = document.createElement('a');
		a.href = 'javascript:void(0)';
		a.id = a.name = idname;
		a.classList.add('btn', 'btn-danger');
		a.appendChild(icon);

		var cell = document.createElement('td');
		cell.appendChild(a);
		tr.appendChild(cell);
	}

	async function createMultipleCells(emp, tr, id, file, part, stat) {
		await createCheckboxCell('chkBoxAcc', 'part' + emp, tr, id, part, stat);
		await createCheckboxCell('chkBoxAcc', 'file' + emp, tr, id, file, stat);
		if (stat == 2) {
			createBadgeApproval('', emp, id, tr);
			document.querySelector('input[value="part' + emp + '"]').disabled = true;
			document.querySelector('input[value="file' + emp + '"]').disabled = true;
		}
	}

	function getMaxRow(tBody, rowName) {
		var rows = tBody.getElementsByTagName('tr');

		var maxId = 0;
		for (var i = 0; i < rows.length; i++) {
			var currentId = parseInt(rows[i].id.replace(rowName, ''));
			if (currentId > maxId) {
				maxId = currentId;
			}
		}
		return maxId + 1;
	}

	function validateNumericInput(input) {
		const numericRegex = /^[0-9]*$/;

		if (!numericRegex.test(input.value)) {
			document.getElementById('numericError').innerText = 'Hanya menerima angka';
		} else {
			document.getElementById('numericError').innerText = '';
		}
	}

	function validateTextOnly(input) {
		input.value = input.value.replace(/[^a-zA-Z\s]/g, '');
	}

	function addSeparator(input) {
		let value = input.value.replace(/\D/g, '');
		value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
		input.value = value;
	}

	function isDataTableExist(counter, kode, colspan, idname, tbodyName) {
		const tableBody = document.getElementById(tbodyName);
		if (kode != 1) {
			for (var i = tableBody.rows.length - 1; i >= 0; i--) {
				tableBody.deleteRow(i);
			}
		}

		const existingCell = document.getElementById(idname);
		if (counter == 0) {
			if (existingCell) document.getElementById(idname).remove();
			const row = document.createElement('tr');
			const cell = document.createElement('td');
			cell.colSpan = colspan;
			cell.id = idname;
			cell.textContent = 'No data to be displayed.';
			cell.classList.add('text-center');
			row.appendChild(cell);
			tableBody.appendChild(row);
		} else if (existingCell) document.getElementById(idname).remove();
	}
</script>

<!-- Back End Connector -->
<script>
	function submitEdit(code) {
		if (code == 'training') searchKeyword('', '', 'allEmpTable');
		var form = document.getElementById('formTraining');
		var input = document.createElement('input');
		input.type = 'hidden';
		input.name = 'empSelected';
		input.value = JSON.stringify(isAdmin ? empArrAdmin : empArrNon);
		var span = document.createElement('input');
		span.type = 'hidden';
		span.name = 'tags';
		span.value = JSON.stringify(tags);
		form.appendChild(input);
		form.appendChild(span);
		form.submit();
	}

	function modifyAccess(code, value, npk, header) {
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4) {
				if (xhr.status === 200) {

				} else {
					console.error('Error fetching data');
				}
			}
		};

		var params = 'code=' + encodeURIComponent(code) +
			'&value=' + encodeURIComponent(value) +
			'&npk=' + encodeURIComponent(npk) +
			'&header=' + encodeURIComponent(header);

		xhr.open('POST', '<?php echo base_url('Plus/modifyAccess/') ?>', true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.send(params);
	}

	function modifyApproval(idDetail, npk, id, status) {
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4) {
				if (xhr.status === 200) {

				} else {
					console.error('Error fetching data');
				}
			}
		};
		var params = 'idDetail=' + encodeURIComponent(idDetail) +
			'&npk=' + encodeURIComponent(npk) +
			'&id=' + encodeURIComponent(id) +
			'&status=' + encodeURIComponent(status);

		xhr.open('POST', '<?php echo base_url('Training/modifyApproval/') ?>', true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.send(params);
	}

	async function getAdmins() {
		try {
			const fetchedAdmins = await new Promise((resolve, reject) => {
				var xhr = new XMLHttpRequest();
				xhr.onreadystatechange = function() {
					if (xhr.readyState === XMLHttpRequest.DONE) {
						if (xhr.status === 200) {
							const admins = JSON.parse(xhr.responseText).map(obj => obj.npk);
							resolve(admins);
						} else {
							console.error('Error fetching data');
							reject(new Error('Error fetching data'));
						}
					}
				};

				xhr.open('GET', '<?= base_url('Plus/getAdmins/') ?>', true);
				xhr.send();
			});
			admins = fetchedAdmins;
		} catch (error) {
			console.error('Error:', error.message);
		}
	}

	async function getAccessData(npk, id) {
		try {
			const response = await fetch('<?php echo base_url('Plus/getAccessData?') ?>npk=' + npk + '&id=' + id);
			if (response.ok) {
				const access = await response.json();
				return access[0]; // Return the access data
			} else {
				console.error('Error fetching data response NOT OK');
				return null;
			}
		} catch (error) {
			console.error('Error fetching data catch:', error);
			return null;
		}
	}

	async function getTrainingByNPK(isAll, keyword, tagID) {
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function() {
			if (xhr.readyState === XMLHttpRequest.DONE) {
				if (xhr.status === 200) {
					const data = JSON.parse(xhr.responseText);
					modifyTrainingTable(data);
				} else {
					console.error('Error fetching data');
				}
			}
		};

		xhr.open('POST', '<?php echo base_url('Plus/getTrainingByNPK/') ?>', true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.send('isAll=' + encodeURIComponent(isAll) + '&keyword=' + encodeURIComponent(keyword) + '&tag=' + encodeURIComponent(tagID));
	}

	async function getTrainingByStatus(status) {
		console.log(status);
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function() {
			if (xhr.readyState === XMLHttpRequest.DONE) {
				if (xhr.status === 200) {
					const data = JSON.parse(xhr.responseText);
					modifyTrainingTable(data);
				} else {
					console.error('Error fetching data');
				}
			}
		};

		xhr.open('POST', '<?php echo base_url('Plus/getTrainingByStatus/') ?>', true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.send('status=' + status);
	}
</script>

<!-- Training Detail Section -->
<script>
	showPage(1);

	function showPage(pageNumber) {
		var cards = document.getElementById('listCardDiv').getElementsByClassName('card-item');
		var pageItems = document.getElementsByClassName('page-item');
		for (var i = 0; i < pageItems.length; i++) {
			pageItems[i].classList.remove('active');
		}
		document.querySelector('.pagination li[data-page="' + pageNumber + '"]').classList.add('active');

		for (var i = 0; i < cards.length; i++) {
			if (i >= (pageNumber - 1) * 4 && i < pageNumber * 4) {
				cards[i].classList.remove('fade-out');
				cards[i].classList.remove('hide-after-fade-out');
				cards[i].classList.add('fade-in');
			} else {
				cards[i].classList.remove('fade-in');
				cards[i].classList.add('fade-out');
				cards[i].classList.add('hide-after-fade-out');
			}
		}
	}

	async function showDetail(id) {
		rowCountMateriForm = 1;
		await getAdmins();
		var tableBody = document.getElementById('tBodyDetailEmp');
		var tableBodyDetOnly = document.getElementById('tBodyDetailOnlyEmp');
		var t = isAdmin ? document.getElementById('tBodyDetailEmp') : document.getElementById('tBodyDetailOnlyEmp');
		for (var i = t.rows.length - 1; i >= 0; i--) {
			t.deleteRow(i);
		}

		changeForm('detail', '');

		empArrAdmin = [];
		const promises = [];
		const checked = [];

		var counterEmp = 0;
		var counterSub = 1;
		if (id != '0') {
			var formElement = document.getElementById('formTraining');
			formElement.setAttribute('action', '<?php echo base_url('Training/editTraining/') ?>' + id);

			fetch('<?= base_url('Training/showDetail/') ?>' + id)
				.then(response => {
					return response.text();
				})
				.then(response => {
					var data = JSON.parse(response);
					console.log(data);
					var status = data.header[0].status;
					console.log(status + "sdf");

					data.employee.forEach(async function(emp) {

						if (emp.STATUS != 3) {
							empArrAdmin.push(emp.NPK);
							// Data row
							var tr = document.createElement('tr');
							var idRow = document.getElementById('tBodyDetailEmp') ? 'rowForm' : 'rowFormDet';
							tr.id = idRow + emp.NPK;

							createTextCell(counterEmp + 1, tr, 'number', 'center');
							createTextCell(emp.NAMA, tr, 'text', 'left');
							createTextCell(emp.DEPARTEMEN, tr, 'text', 'left');
							createTextCell(emp.PROGRESS, tr, 'text', 'center');
							createTextCell(Math.round(emp.PERCENT) + '%', tr, 'text', 'center');
							const accessPromise = getAccessData(emp.NPK, id).then(acc => {
								if (isAdmin) {
									createMultipleCells(emp.NPK, tr, id, acc.file, acc.part, emp.STATUS)
								}
							});

							t.appendChild(tr);
							counterEmp++;
							promises.push(accessPromise);
						}
					});

					Promise.all(promises).then(() => {
						if (tableBodyDetOnly) isDataTableExist(counterEmp, 1, 5, 'emptyParticipantDet', 'tBodyDetailOnlyEmp');
						if (tableBody) isDataTableExist(counterEmp, 1, 8, 'emptyParticipant', 'tBodyDetailEmp');
					});

					document.getElementById('idTraining').value = data.header[0].id_training_header;
					document.getElementById('temaTraining').value = data.header[0].judul_training_header;
					document.getElementById('pemateri').value = data.header[0].pemateri;
					document.getElementById('editBtn').onclick = function() {
						doEdit(id, status);
					};

					var base_url = "<?= base_url('Training/modifyTraining/') ?>";
					var judul_training_header = data.header[0].id_training_header;
					if (document.getElementById('deleteBtn')) document.getElementById('deleteBtn').href = (base_url + judul_training_header) + 0;
					if (document.getElementById('publishBtn')) document.getElementById('publishBtn').href = (base_url + judul_training_header) + 2;

					rowCountMateriForm = data.substance.length;
					isDataTableExist(rowCountMateriForm, 'x', 3, 'emptyData', 'tBodySubstanceTableDetail');
					if (rowCountMateriForm != 0) {
						data.substance.forEach(function(substance) {
							var tableBody = document.getElementById('tBodySubstanceTableDetail');
							var row = document.createElement('tr');

							createTextCell(counterSub, row, 'number', 'center');
							createTextCell(substance.judul_training_detail, row, 'text', 'left');
							createFileCell(substance.id_training_detail, substance.path_file_training_detail, substance.status == 2 ? '' : 'detail', row);
							if (substance.status == 2) {
								createBadgeApproval(substance.id_training_detail, '', '', row);
							}
							counterSub++;
							tableBody.appendChild(row);
						});
					}

					populateTagsSection(data.tags, 'detail');

					const accessData = getAccessData(<?php echo $this->session->userdata['npk']; ?>, id).then(access => {
						if (access.part == 1 || access.file == 1 || isAdmin) {
							arr = ['editBtn'];
							if ((data.header[0].status == 1)) {
								arr.push('deleteBtn');
								arr.push('publishBtn');
							} else {
								changeDisplayOfElements('none', ['deleteBtn', 'publishBtn']);
							}
							changeDisplayOfElements('block', arr);
						}
					});

				})
				.catch(error => {
					console.error('Error fetching data showdetail:', error);
				});
		}
	}

	async function toggleTab(tabName) {
		activateClassActive(tabName);
		status = '';
		if (tabName == 'all') status = '> 0';
		else if (tabName == 'published') status = '= 2';
		else if (tabName == 'draft') status = '= 1';
		else if (tabName == 'allWithRequest') status = '> x';
		await getTrainingByStatus(status);
	}

	function modifyTrainingTable(trainings) {
		const container = document.getElementById('trainingContainer');
		const paging = document.getElementById('pagingContainer');
		container.innerHTML = '';
		paging.innerHTML = '';
		<?php if ($this->session->userdata('role') == 'admin') { ?>
			$abc = 1;
		<?php } ?>
		var counter = 1;
		console.log(trainings);
		console.log(isAdmin + "tes");
		trainings.forEach((t, index) => {
			const cardHTML = `
				<div class="col-sm-3 card-item ${counter <= 4 ? 'fade-in' : 'fade-out hide-after-fade-out'}">
					<div class="card" style="border-radius: 20px;">
						<div class="card-header">
							<img src="assets/img/picLog.png" style="width: 100%">
							<div class="row overlay-content" style="width: 100%">
								<div class="col-sm-6">
								${t.status === 2 ? `
									<span class="badge badge-success">Published</span>`
								: '<span class="badge badge-warning">Draft</span>'}
								</div>
								<div class="col-sm-6 justify-content-end d-flex">
								${t.detail_request == "true" || t.participant_request == "true" ? 
									'<?php if ($this->session->userdata("role") == "admin") { ?>'+	
									'<span class="badge badge-warning">!</span>'+
									'<?php } ?>' : ''}
								</div>
							</div>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-sm-8 pr-0">
									<h4 class="card-title">${t.judul_training_header.length > 15 ? t.judul_training_header.substring(0, 15) + '...' : t.judul_training_header}</h4>
									<p class="card-category">${t.detail_count} materi</p>
									<p class="card-category">${t.participant_count} partisipan</p>
								</div>
								<div class="col d-flex align-items-center justify-content-end p-0 pr-3">
									<a href="javascript:void(0)" onclick="showDetail(${t.id_training_header})" class="btn btn-primary px-2">
										<i class="la la-bars" style="font-size: 16px;"></i> Detail
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			`;

			container.innerHTML += cardHTML;
			counter++;
		});

		if (trainings.length == 0) {
			const cardHTML = `
				<div class="col card-item fade-in">
					<div class="row justify-content-center">
						<div class="col-md-4">
							<div class="card" style="border-radius: 20px;">
								<div class="card-header">
									<img src="assets/img/dataEmpty1.jpg" style="max-height: 163px">
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col">
											<h4 class="card-title">Tidak ada data training!</h4>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			`;

			container.innerHTML = cardHTML;
		}

		const paginationHTML = `
			<div class="col align-items-center justify-content-center d-flex">
				<ul class="pagination pg-primary">
					<li class="page-item">
						<a class="page-link" href="#" aria-label="Previous">
							<span aria-hidden="true">«</span>
							<span class="sr-only">Previous</span>
						</a>
					</li>
					${trainings.map((t, index) => {
						if (index % 4 == 0) {
							return `
								<li class="page-item ${Math.ceil(index / 4) + 1 == 1 ? 'active' : ''}" data-page="${Math.ceil(index / 4) + 1}">
									<a class="page-link" href="javascript:void(0)" onclick="showPage(${Math.ceil(index / 4) + 1})">${Math.ceil(index / 4) + 1}</a>
								</li>
							`;
						}
					}).join('')}
					<li class="page-item">
						<a class="page-link" href="#" aria-label="Next">
							<span aria-hidden="true">»</span>
							<span class="sr-only">Next</span>
						</a>
					</li>
				</ul>
			</div>
		`;

		document.getElementById('pagingContainer').innerHTML += paginationHTML;
	}

	async function tagFilter(id, name) {
		document.getElementById('ddTags').textContent = name;
		document.getElementById('ddTags').name = id;
		if (isAdmin) activateClassActive('all');
		await getTrainingByNPK(!document.getElementById('myTraining').checked, document.getElementById('search_training').value.trim(), id);
	}

	async function toggleMine(select) {
		if (isAdmin) activateClassActive('all');
		await getTrainingByNPK(!select, document.getElementById('search_training').value.trim(), document.getElementById('ddTags').name.trim());
	}

	document.getElementById('search_training').addEventListener('keyup', function() {
		(async () => {
			if (isAdmin) activateClassActive('all');
			await getTrainingByNPK(!document.getElementById('myTraining').checked, this.value.trim(), document.getElementById('ddTags').name.trim());
		})();
	});
</script>

<!-- Participant Section -->
<script>
	function toggleAll(select) {
		var checkboxes = document.querySelectorAll('.form-check-input');
		checkboxes.forEach(checkbox => {
			if (!checkbox.disabled) {
				var index = empArrAdmin.indexOf(checkbox.value);
				checkbox.checked = select;
				if (index !== -1) {
					empArrAdmin.splice(index, 1);
				} else {
					empArrAdmin.push(checkbox.value);
				}
			}
		});
		console.log('emp: ' + empArrAdmin);
	}

	document.getElementById('search_keyword').addEventListener('keyup', function() {
		var keyword = this.value.trim();
		searchKeyword(keyword, '', 'allEmpTable');
	});

	function searchKeyword(name, dept, tableName) {
		if (dept != '') {
			document.getElementById('dropdownMenu1').textContent = dept;
		}
		if (dept == '') dept = document.getElementById('dropdownMenu1').textContent.trim();
		if (name == '') name = document.getElementById('search_keyword') ? document.getElementById('search_keyword').value.trim() :
			document.getElementById('search_employee').value.trim();
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function() {
			if (xhr.readyState === XMLHttpRequest.DONE) {
				if (xhr.status === 200) {
					var filteredData = JSON.parse(xhr.responseText);
					populateTable(filteredData.employees, tableName);
				} else {
					console.error('Error fetching data');
				}
			}
		};

		xhr.open('POST', '<?php echo base_url('Plus/searchEmployee/') ?>', true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.send('search_employee=' + encodeURIComponent(name) + '&code=' + encodeURIComponent(dept));
	}

	function populateTable(filteredData, tableName) {
		var tableBody = document.getElementById(tableName).querySelector('tbody');
		tableBody.innerHTML = ''; // Clear existing table content
		var i = 1;
		filteredData.forEach(function(row) {
			var tr = document.createElement('tr');
			Object.keys(row).forEach(function(key) {
				if (key === 'NPK' || key === 'NAMA' || key === 'DEPARTEMEN') {
					var td = document.createElement('td');
					if (key === 'NPK') td.textContent = i;
					else td.textContent = row[key];
					tr.appendChild(td);
					if (key === 'DEPARTEMEN') {
						//xyz
						console.log("hasd");
						createCheckboxCell('chkBoxemp[]', row['NPK'], tr, isAdmin ? '' : 'non', 'edit', tableName.includes('Admin') ? 'admin' : '');
					}
				}
			});
			tableBody.appendChild(tr);
			i++;
		});
		isDataTableExist(filteredData.length, 1, 4, 'emptyParticipant', tableName);
	}

	async function addEmp(id) {
		console.log('adm: ' + isAdmin);
		if (isAdmin) {
			var index = empArrAdmin.indexOf(id);
			if (index !== -1) {
				empArrAdmin.splice(index, 1);
			} else {
				empArrAdmin.push(id);
			}
		} else {
			var index = empArrNon.indexOf(id);
			if (index !== -1) {
				empArrNon.splice(index, 1);
			} else {
				empArrNon.push(id);
			}
		}
	}
</script>

<!-- Substance Section -->
<script>
	var no = 0;

	function addRow() {
		var tableBody = document.getElementById('tBodySubstanceTableEdit');
		var tableBody2 = document.getElementById('tBodySubstanceTableEdit2');

		var materiRow = document.createElement('tr');
		var idNow = getMaxRow(tableBody, 'rowFormMateri');
		var idNow2 = getMaxRow(tableBody2, 'rowFormMateri');
		console.log(idNow2);
		var no1 = 0;
		idNow3 = idNow2 + no1;

		if (idNow2 < 2) {
			console.log("sdf");
		} else {
			if (no == 0 || idNow < idNow2) {
				no = no + 1;
				var idNow4 = idNow3;
				idNow = idNow4;
			}
		}

		materiRow.id = 'rowFormMateri' + idNow;
		createInputCell('materiTitle' + idNow, 'text', 'Masukkan judul materi...', materiRow);
		createInputCell('materiFile' + idNow, 'file', '', materiRow);
		createDeleteActionCell('deleteRowSubstance' + idNow, materiRow);

		rowCountMateriForm++;
		isDataTableExist(rowCountMateriForm, 1, 4, 'emptyData', 'tBodySubstanceTableEdit');
		tableBody.appendChild(materiRow);

		document.getElementById('deleteRowSubstance' + idNow).onclick = function() {
			removeRow(idNow);
		}
	}

	function removeRow(id) {
		document.getElementById('rowFormMateri' + id).remove();
		rowCountMateriForm--;
		isDataTableExist(rowCountMateriForm, 1, 4, 'emptyData', 'tBodySubstanceTableEdit');
	}
</script>

<!-- Tags Section -->
<script>
	function mouseIn(id, color) {
		var hoverDiv = document.getElementById(id);
		var rgb = parseInt(color.slice(1), 16);
		var r = (rgb >> 16) & 0xff;
		var g = (rgb >> 8) & 0xff;
		var b = (rgb >> 0) & 0xff;

		r = Math.max(0, r - 20);
		g = Math.max(0, g - 20);
		b = Math.max(0, b - 20);

		hoverDiv.style.backgroundColor = 'rgba(' + r + ',' + g + ',' + b + ', 0.7)';
	}

	function mouseOut(id, color) {
		var hoverDiv = document.getElementById(id);
		hoverDiv.style.backgroundColor = color;
	}

	function isColorLight(hexColor) {
		const r = parseInt(hexColor.substr(1, 2), 16);
		const g = parseInt(hexColor.substr(3, 2), 16);
		const b = parseInt(hexColor.substr(5, 2), 16);
		const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;

		if (luminance > 0.7) {
			return '#000000';
		} else {
			return '#ffffff';
		}
	}

	function populateTagsSection(data, code) {
		const container = document.getElementById('tagsContainer');
		container.innerHTML = '';
		if (code == 'clear') tags = [];
		data.forEach(function(tag) {
			var col = isColorLight(tag.color);
			// 
			const cardHTML = `
				<span class="badge tags" id="tags${tag.id_tag}" style="background-color: ${tag.color}; color: ${col}; border-color: white;" onclick="addTags('tags${tag.id_tag}')"
				onmouseover="mouseIn('tags${tag.id_tag}', '${tag.color}')" onmouseout="mouseOut('tags${tag.id_tag}', '${tag.color}')">${tag.name_tag}</span>
			`;
			if (code == 'detail') tags.push(tag.id_tag);
			container.innerHTML += cardHTML;
		});
	}

	function addTags(id) {
		if (!document.getElementById('cardTitle').textContent.toLowerCase().includes('detail')) {
			var span = document.getElementById(id);
			var index = tags.indexOf(parseInt(id.substr("tags".length)));
			if (index !== -1) {
				tags.splice(index, 1);
				span.style.borderColor = 'white';
			} else {
				tags.push(parseInt(id.substr("tags".length)));
				span.style.borderColor = 'blue';
			}
		}
	}
</script>

<!-- Training Edit -->
<script>
	async function doEdit(id, status) {
		let npk = <?php echo $this->session->userdata('npk'); ?>;

		let canEdit = false;
		if (await checkAccess(npk, id)) canEdit = true;
		// else if (isAdmin) canEdit = true;

		if (!canEdit) {
			Swal.fire({
				title: 'ERROR',
				text: 'Anda mengakses menu terlarang. Silakan refresh halaman!',
				icon: 'error',
				confirmButtonColor: '#d33',
				confirmButtonText: 'OK'
			});
			return;
		}

		changeForm('edit', status);
		// await checkAccess(npk, id);
		rowCountMateriForm = 0;
		var counterSub = 1;
		var idHeader = document.getElementById('idTraining').value;

		empArrAdmin.forEach(function(emp) {
			const chb = document.querySelector('input[value="' + emp + '"]');
			chb.checked = true;
			if (!isAdmin) chb.disabled = true;
		});

		empArrNon.forEach(function(emp) {
			const chb = document.querySelector('input[value="' + emp + '"]');
			chb.checked = true;
		});

		isDataTableExist(rowCountMateriForm, 'x', 4, 'emptyData', 'tBodySubstanceTableEdit2');
		isDataTableExist(rowCountMateriForm, 'x', 4, 'emptyData', 'tBodySubstanceTableEdit');
		var tableBody = document.getElementById('tBodySubstanceTableEdit2');
		<?php echo $combinedDataJSON ?>.forEach(function(substance) {
			if (substance.id_header == idHeader) {
				var materiRow = document.createElement('tr');
				var idNow = rowCountMateriForm + 1;
				materiRow.id = 'rowFormMateri' + idNow;

				// createTextCell(idNow, materiRow, 'number', 'center');
				createInputCell('materiId' + idNow, 'hidden', substance.id_detail, materiRow);
				createTextCell(substance.title, materiRow, 'text', 'left')
				createFileCell(substance.id_detail, substance.path, '', materiRow);
				createDeleteActionCell('deleteRowSubstanceCommit' + idNow, materiRow);

				rowCountMateriForm++;
				isDataTableExist(rowCountMateriForm, 1, 4, 'emptyData', 'tBodySubstanceTableEdit');
				tableBody.appendChild(materiRow);

				document.getElementById('deleteRowSubstanceCommit' + idNow).onclick = function() {
					removeRow(idNow);
				}
			}
		});

		populateTagsSection(<?php echo json_encode($tags) ?>, 'edit');
		tags.forEach(function(tag) {
			document.getElementById('tags' + tag).style.borderColor = 'blue';
		});
	}

	async function checkAccess(npk, id) {
		const accessData = await getAccessData(npk, id);
		if (!accessData) {
			return false;
		}

		let found = false;

		if (accessData.part == 1) {
			changeDisplayOfElements('block', ['allEmpDiv']);
			found = true;
		}
		if (accessData.file == 1) {
			changeDisplayOfElements('block', ['substanceDiv']);
			found = true;
		}
		if ('<?php echo $this->session->userdata['role']; ?>' == 'admin') {
			changeDisplayOfElements('block', ['substanceDiv', 'allEmpDiv', 'temaDiv']);
			found = true;
		}

		return found;
	}

	function validateForm() {
		var errorMessages = document.getElementById('errorMessages');

		if (!errorMessages) {
			console.error("Error: 'errorMessages' element not found.");
			return;
		}

		var errors = [];

		// Use attribute selector to select elements whose IDs or names contain 'materiTitle'
		var materiTitleFields = document.querySelectorAll('[id*="materiTitle"], [name*="materiTitle"]');

		materiTitleFields.forEach(function(fieldElement) {
			var fieldValue = fieldElement.value.trim();

			if (fieldValue === '') {
				fieldElement.style.borderColor = 'red';
				var label = document.querySelector('label[for="' + fieldElement.id + '"]');
				var labelText = label ? label.textContent.trim() : fieldElement.id;
				errors.push(labelText);
				//	errorMessages.textContent = '* ' + labelText + ' wajib diisi!';
				fieldElement.classList.remove('mb-3');
			} else {
				fieldElement.style.borderColor = ''; // Reset border
			}
		});

		var additionalFields = [
			'temaTraining'
			// Add more fields if needed
		];

		additionalFields.forEach(function(fieldId) {
			var fieldValue = document.getElementById(fieldId).value.trim();
			var fieldElement = document.getElementById(fieldId);

			if (fieldValue === '') {
				fieldElement.style.borderColor = 'red';
				//	var label = document.querySelector('label[for="' + fieldId + '"]');
				//var labelText = label ? label.textContent.trim() : fieldId;
				// errors.push(labelText);
				// errorMessages.textContent = '* ' + labelText + ' wajib diisi!';
				fieldElement.classList.remove('mb-3');
			} else {
				fieldElement.style.borderColor = ''; // Reset border
			}
		});

		if (errors.length > 0) {
			// Handle error messages as needed
			document.body.scrollIntoView({
				behavior: 'smooth',
				block: 'start'
			});
		} else {
			errorMessages.textContent = ''; // Clear error messages
			submitEdit('training');
		}
	}
</script>