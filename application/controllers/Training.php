<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Training extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model("OracleDBM");
		$this->load->model("TrainingM");
		$this->load->model("AdminM");
		$this->load->helper(array('form', 'url'));
		$this->load->library('session');
		$this->load->library('form_validation');
	}

	public function index()
	{
		if (!$this->isAllowed()) return redirect(site_url());
		$npk = $this->session->userdata('npk');
		$data['training']   = $this->TrainingM->getTrainingByNPK(true, '', '');
		$data['substance']  = $this->TrainingM->getAllSubstance();
		$data['employee']   = $this->OracleDBM->getAllEmp();
		$data['dept']       = $this->OracleDBM->getAllDept();
		$data['notif']		= $this->TrainingM->getNotif($npk);
		$data['tags']  		= $this->AdminM->getTags();
		$data['notifMateri']   = $this->TrainingM->getNotifMateri($npk);
		$data['totalNotif'] = count($data['notif']) + count($data['notifMateri']);

		$this->load->view('training/training_index', $data);
	}

	public function isAllowed()
	{
		return $this->session->userdata('isLogin');
	}

	public function showDetail($id)
	{
		$data["header"] = $this->TrainingM->getTrainingHeader($id);
		$emps           = $this->TrainingM->getEmployeeByTraining($id);
		$detailEmployeeE = [];
		foreach ($emps as $emp) {
			$employee   = $this->OracleDBM->getEmpBy('NPK', $emp->npk);
			$prog       = $this->TrainingM->getProgress($id, $emp->npk);
			$combinedData = [
				'NPK'       => $employee->NPK,
				'NAMA'      => $employee->NAMA,
				'DEPARTEMEN' => $employee->DEPARTEMEN,
				'PERCENT'   => $prog->percentage,
				'PROGRESS'  => $prog->progress,
				'STATUS'	=> $this->TrainingM->getAccessByNPKID($employee->NPK, $id)->access_permission,
			];
			$detailEmployeeE[] = $combinedData;
		}

		$data["substance"]  = $this->TrainingM->getSubstanceByTraining($id);
		$data["employee"]   = $detailEmployeeE;
		$data["tags"]   	= $this->AdminM->getTagsByID($id);

		echo json_encode($data);
	}

	public function modifyTraining($str)
	{
		$id = substr($str, 0, strlen($str) - 1);
		$code = substr($str, -1);
		$this->TrainingM->modifyTraining($id, $code);
		redirect('Training');
	}

	public function saveTraining()
	{
		$this->TrainingM->saveTraining();
		$lastInsertedId = $this->db->insert_id();

		$count = 0;
		foreach ($this->input->post() as $key => $value) {
			if (strpos($key, 'materiTitle') !== false) {
				$count++;
			}
		}

		for ($i = 1; $i <= $count; $i++) {
			$judulMateri = $this->input->post('materiTitle' . $i);

			if ($judulMateri !== null) {
				$config['upload_path']   = './uploads/';
				$config['allowed_types'] = 'pdf';
				$config['max_size']      = 10240;

				$this->load->library('upload', $config);

				if ($this->upload->do_upload('materiFile' . $i)) {
					$fileData = $this->upload->data();
					$fileName = $fileData['file_name'];
					$filePath = 'uploads/' . $fileName;

					$this->TrainingM->saveSubstance($filePath, $judulMateri, $lastInsertedId);
				} else {
					echo $this->upload->display_errors();
				}
			}
		}

		$checkedCheckboxes = $this->input->post('chkBoxemp');
		if (!empty($checkedCheckboxes)) {
			foreach ($checkedCheckboxes as $checkbox) {
				$npks =  (string)$checkbox;
				$ids = $lastInsertedId;
				$this->TrainingM->saveParticipant($npks, $ids);
			}
		}

		$tags = json_decode($this->input->post('tags'));
		if (!empty($tags)) {
			foreach ($tags as $tag) {
				$this->TrainingM->saveTagDetail($tag, $lastInsertedId);
			}
		}
		redirect('Training');
	}

	public function editTraining($id)
	{
		if (!$this->session->userdata('isLogin')) {
			return redirect('Login');
		} else {
			$this->TrainingM->modifyTrainingHeader();
			$employees = $this->input->post('empSelected');
			if (!empty($employees)) {
				$id =	$this->input->post('idTraining');

				$this->TrainingM->resetParticipant(json_decode($employees), $id);
				foreach (json_decode($employees) as $employee) {

					$getId = $this->TrainingM->getAccessByNPKID($employee, $id);
					if ($getId == null) {
						$this->TrainingM->saveParticipant($employee, $id);
					} else {
						$this->TrainingM->modifyParticipant($employee, $id);
					}
				}
			}

			$tags = json_decode($this->input->post('tags'));
			$this->TrainingM->resetTags($tags, $this->input->post('idTraining'));
			if (!empty($tags)) {
				$id =	$this->input->post('idTraining');
				foreach ($tags as $tag) {
					if (!$this->TrainingM->getDataTag($tag, $id)) $this->TrainingM->saveTagDetail($tag, $id);
				}
			} 

			$idDetail = $this->TrainingM->getSubstanceByTraining($id);
			$materiIdArray = array();

			foreach ($this->input->post() as $key => $value) {
				if (strpos($key, 'materiId') !== false) {
					$materiIdArray[] = $value;
				}
			}

			foreach ($idDetail as $detail) {
				if (!in_array($detail->id_training_detail, $materiIdArray)) {
					$this->TrainingM->modifySubstance($detail->id_training_detail);
				}
			}
			$count = 0;
			foreach ($this->input->post() as $key => $value) {
				if (strpos($key, 'materiTitle') !== false) {
					$count++;
				}
			}

			if ($count > 0) {
				$index = 1;
				$counter = 1;

				// Count the number of 'materiTitle' keys


				// Find the starting index where 'materiTitle' key exists
				while (!array_key_exists('materiTitle' . $counter, $this->input->post())) {
					$index++;
					$counter++;
				}

				// Loop through 'materiTitle' keys
				for ($i = $index; $i <= $count + $index - 1; $i++) {
					echo "<script>console.log('aa + $i');</script>";

					// Assuming you want to concatenate the string, use "." instead of "+"
					print_r($i . "aa");

					$judulMateri = $this->input->post('materiTitle' . $i);
					print_r($judulMateri . "bb");

					if ($judulMateri !== null) {
						echo "<script>console.log('bb');</script>";
						print_r($judulMateri . "cc");

						$config['upload_path']   = './uploads/';
						$config['allowed_types'] = 'pdf';
						$config['max_size']      = 10240;

						$this->load->library('upload', $config);

						if ($this->upload->do_upload('materiFile' . $i)) {
							$fileData = $this->upload->data();
							$fileName = $fileData['file_name'];
							$filePath = 'uploads/' . $fileName;

							$this->TrainingM->saveSubstance($filePath, $judulMateri, $id);
						} else {
							echo $this->upload->display_errors();
						}
					}
				}
			}
			redirect('Training');
		}
	}

	public function modifyApproval()
	{
		$npk		= $this->input->post('npk');
		$status		= $this->input->post('status');
		$this->TrainingM->modifyApproval($npk, $status);
	}

	public function addProgress($id)
	{
		if (!$this->session->userdata('isLogin')) {
			return redirect('Login');
		} else {
			$this->TrainingM->saveProgress($id);
		}
	}

	public function removeNotif()
	{
		// Check if the request is an AJAX request
		// Get the 'id' and 'npk' parameters from POST data
		$id = $this->input->post('id');
		$npk = $this->input->post('npk');
		echo "<script>console.log('aa + $id' + $npk);</script>";
		// Call the removeNotif method from your model
		$this->TrainingM->removeNotif($id, $npk);

		// Return a success message to the client
		echo json_encode(['status' => 'success', 'message' => 'Notification removed successfully.']);
	}

	public function removeNotifMateri($id)
	{
		// Log a message to the CodeIgniter log file
		log_message('info', 'Notification removed successfully. ID: ' . $id);
		if (!$this->session->userdata('isLogin')) {
			return redirect('Login');
		} else {
			$this->TrainingM->removeNotifMateri($id);
		}
	}
}
