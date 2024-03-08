<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ApprovalFpet extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("OracleDBM");
        $this->load->model("TrainingM");
        $this->load->model("FPETM");
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
        $data['notif']        = $this->TrainingM->getNotif($npk);
        $data['tags']          = $this->AdminM->getTags();
        $data['notifMateri']   = $this->TrainingM->getNotifMateri($npk);
        $data['totalNotif'] = count($data['notif']) + count($data['notifMateri']);
        $data['fpet']          = $this->FPETM->getApprovedFpet($npk);
        $this->load->view('fpet/masterFpet', $data);
    }


    public function isAllowed()
    {
        return $this->session->userdata('isLogin');
    }

    public function showDetail($id)
    {
        $data["dataFpet"] = $this->FPETM->detailFpet($id);
        // $detailEmployeeE = [];
        // foreach ($emps as $emp) {
        // 	$employee   = $this->OracleDBM->getEmpBy('NPK', $emp->npk);
        // 	$prog       = $this->TrainingM->getProgress($id, $emp->npk);
        // 	$combinedData = [
        // 		'NPK'       => $employee->NPK,
        // 		'NAMA'      => $employee->NAMA,
        // 		'DEPARTEMEN' => $employee->DEPARTEMEN,
        // 		'PERCENT'   => $prog->percentage,
        // 		'PROGRESS'  => $prog->progress,
        // 		'STATUS'	=> $this->TrainingM->getAccessByNPKID($employee->NPK, $id)->access_permission,
        // 	];
        // 	$detailEmployeeE[] = $combinedData;
        // }

        //	$data["employee"]   = $detailEmployeeE;

        echo json_encode($data);
    }
    public function approveRejectFpet($id, $status)
    {
        if (!$this->isAllowed()) return redirect(site_url());
        $this->FPETM->approveRejectFpet($id);
        redirect(site_url('FPET'));
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
