    <?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class FPET extends CI_Controller
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
            $data['fpet']          = $this->FPETM->getFpet();
            $this->load->view('fpet/masterFpet', $data);
        }


        public function approvalMenu()
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
            $this->load->view('fpet/approvalFpet', $data);
        }

        public function isAllowed()
        {
            return $this->session->userdata('isLogin');
        }



        public function saveFpet()
        {

            // Retrieve form data from POST request
            $approved = $this->input->post('approved');
            $trainer = $this->input->post('trainer');
            $approvedHr = $this->input->post('approvedHr');

            // Retrieve other form data


            $rEstablished = $this->input->post('rEstablished');
            $lastInsertedTrain = null;
            if ($rEstablished == "Ya") {
                $lastInsertedTrain = $this->input->post('chooseTrain');
                $rEstablished = 1;
            } else if ($rEstablished == "Tidak") {
                $categoryTrain = $this->input->post('categoryTrain');

                $title = $this->input->post('title');
                $educator = $this->input->post('educator');
                $schedule = $this->input->post('schedule');
                $cost = $this->input->post('cost');
                $dataTrain = array(
                    'categoryTrain' => $categoryTrain, // Add categoryTrain to the data array
                    'title' => $title, // Add title to the data array
                    'educator' => $educator, // Add educator to the data array
                    'schedule' => $schedule, // Add schedule to the data array
                    'cost' => $cost
                );

                $this->FPETM->saveTrain($dataTrain);
                $lastInsertedTrain = $this->db->insert_id();
                $rEstablished = 0;
            }

            $actual = $this->input->post('actual');
            $target = $this->input->post('target');
            $eval = $this->input->post('eval');
            $notes = $this->input->post('notes');
            $rActual = $this->input->post('rActual');
            $rTarget = $this->input->post('rTarget');
            $rEval = $this->input->post('rEval');
            // Assuming you want to save this data to the database
            // Load the model if not already loaded
            $this->load->model('FPETM');

            // Prepare data to be saved
            $data = array(
                'approved' => $approved,
                'trainerNpk' => $trainer,
                'approvedHr' => $approvedHr,
                'idTrain' => $lastInsertedTrain,
                'rEstablished' => $rEstablished,
                'evaluator' => $this->input->post('evaluator'),
                'actual' => $actual,
                'target' => $target,
                'eval' => $eval,
                'notes' => $notes,
                'status' => 2,
                'rActual' => $rActual, // Add rActual to the data array
                'rTarget' => $rTarget, // Add rTarget to the data array
                'rEval' => $rEval,
                'statusApproved' => 2,
                'statusApprovedHr' => 2,
                'created_date'              => date('Y/m/d H:i:s'),
                'created_by'                => $this->session->userdata('npk')
            );
            // $data2 = array(
            //     'title' => $title,
            //     'educator' => $educator,
            //     'schedule' => $schedule,
            //     'cost' => $cost,
            //     'categoryTrain' => $categoryTrain
            // );
            // Call the model function to save the data
            $saved = $this->FPETM->saveFPET($data);

            //  redirect(site_url('FPET'));
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

        public function removeFpet($id)
        {
            if (!$this->isAllowed()) return redirect(site_url());

            $this->FPETM->removeFpet($id);



            redirect(site_url('FPET'));
        }

        public function rejectFpet($id)
        {
            if (!$this->isAllowed()) return redirect(site_url());
            $this->FPETM->rejectApproveFpet($id, 0);
            redirect(site_url('FPET/approvalMenu'));
        }

        public function approveFpet($id)
        {
            if (!$this->isAllowed()) return redirect(site_url());
            $this->FPETM->rejectApproveFpet($id, 1);
            redirect(site_url('FPET/approvalMenu'));
        }

        public function publishFpet($id)
        {
            if (!$this->isAllowed()) return redirect(site_url());
            $this->FPETM->publishFpet($id);
            redirect(site_url('FPET'));
        }

        public function modifyFpet($id)
        {
            // Retrieve form data from POST request
            $approved = $this->input->post('approved');
            $trainer = $this->input->post('trainer');
            $approvedHr = $this->input->post('approvedHr');
            $approvedHr = $this->input->post('approvedHr');
            // Retrieve other form data
            $categoryTrain = $this->input->post('categoryTrain');
            $chooseTrain = $this->input->post('chooseTrain');
            $title = $this->input->post('title');
            $educator = $this->input->post('educator');
            $schedule = $this->input->post('schedule');
            $cost = $this->input->post('cost');
            $actual = $this->input->post('actual');
            $target = $this->input->post('target');
            $eval = $this->input->post('eval');
            $notes = $this->input->post('notes');
            $rActual = $this->input->post('rActual');
            $rTarget = $this->input->post('rTarget');
            $rEval = $this->input->post('rEval');
            // Assuming you want to save this data to the database
            // Load the model if not already loaded
            $this->load->model('FPETM');

            // Prepare data to be saved
            $data = array(
                'approved' => $approved,
                'trainerNpk' => $trainer,
                'approvedHr' => $approvedHr,
                'chooseTrain' => $chooseTrain,
                'evaluator' => $this->input->post('evaluator'),
                'actual' => $actual,
                'target' => $target,
                'eval' => $eval,
                'notes' => $notes,
                'status' => 2,
                'rActual' => $rActual, // Add rActual to the data array
                'rTarget' => $rTarget, // Add rTarget to the data array
                'rEval' => $rEval,
                'statusApproved' => 2,
                'statusApprovedHr' => 2,
                'modified_date'             => date('Y/m/d H:i:s'),
                'modified_by'               => $this->session->userdata('npk'),
            );
            $data2 = array(
                'title' => $title,
                'educator' => $educator,
                'schedule' => $schedule,
                'cost' => $cost,
                'categoryTrain' => $categoryTrain
            );
            // Call the model function to save the data
            $saved = $this->FPETM->modifyFpet($data);

            redirect(site_url('FPET'));
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
