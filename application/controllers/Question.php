    <?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class Question extends CI_Controller
    {
        public $score2 = 'x';
        public function __construct()
        {
            parent::__construct();

            $this->load->model("OracleDBM");
            $this->load->model("QuestionM");
            $this->load->model("TrainingM");
            $this->load->helper(array('form', 'url'));
            $this->load->library('session');
            $this->load->library('form_validation');
        }

        public function index()
        {
            if (!$this->isAllowed()) return redirect(site_url());
            $npk = $this->session->userdata('npk');
            $data['soal']        = $this->QuestionM->getQuestion();
            $data['notif']        = $this->TrainingM->getNotif($npk);
            $data['package']        = $this->QuestionM->getPackages();
            $data['notifMateri'] = $this->TrainingM->getNotifMateri($npk);
            $data['totalNotif'] = count($data['notif']) + count($data['notifMateri']);
            $this->load->view('question_index', $data);
        }

        public function isAllowed()
        {
            return $this->session->userdata('isLogin') && $this->session->userdata('role') == 'admin';
        }

        public function saveQuestion()
        {
            if (!$this->isAllowed()) return redirect(site_url());
            $this->QuestionM->saveQuestion();
            redirect('Question');
        }

        public function editQuestion()
        {
            if (!$this->isAllowed()) return redirect(site_url());
            $this->QuestionM->editQuestion();
            redirect('Question');
        }

        public function retrieveQuestion($id)
        {
            if (!$this->isAllowed()) return redirect(site_url());
            $data = $this->QuestionM->getQuestion($id);
            echo json_encode($data);
        }

        public function deleteQuestion($id)
        {
            if (!$this->isAllowed()) return redirect(site_url());
            $result = $this->QuestionM->deleteQuestion($id);

            if ($result) {
                echo json_encode(array('success' => true));
            } else {
                echo json_encode(array('success' => false, 'error' => 'Deletion failed.'));
            }
        }


        public function getPackage()
        {
            if (!$this->isAllowed()) return redirect(site_url());
            $npk = $this->session->userdata('npk');
            $data['package']        = $this->QuestionM->getPackages();
            $data['notif']        = $this->TrainingM->getNotif($npk);
            $data['notifMateri'] = $this->TrainingM->getNotifMateri($npk);
            $data['totalNotif'] = count($data['notif']) + count($data['notifMateri']);

            $data['train']        = $this->QuestionM->getTrains();
            $this->load->view('question_package', $data);
        }


        public function savePackage()
        {
            if (!$this->isAllowed()) return redirect(site_url());
            $this->QuestionM->savePackage();
            $lastInsertedId = $this->db->insert_id();

            $count = 0;
            foreach ($this->input->post() as $key => $value) {
                if (strpos($key, 'answer') !== false) {
                    $count++;
                }
            }
            for ($i = 1; $i <= $count; $i++) {
                $data = array(
                    'question'      => $this->input->post('question' . $i),
                    'answer'        => $this->input->post('answerSelect' . $i),
                    'a'             => $this->input->post('aOption' . $i),
                    'b'             => $this->input->post('bOption' . $i),
                    'c'             => $this->input->post('cOption' . $i),
                    'd'             => $this->input->post('dOption' . $i),
                    'q_level'       => $this->input->post('levelSelect' . $i),
                    'created_date'  => date('Y/m/d H:i:s'),
                    'modified_date' => date('Y/m/d H:i:s'),
                    'created_by'    => $this->session->userdata('npk'),
                    'modified_by'   => $this->session->userdata('npk'),
                    'status'        => 1,
                    'package_id'    => $lastInsertedId,
                );

                $this->QuestionM->saveQuestion($data);
            }

            redirect('Question/getPackage');
        }


        public function retrievePackage($id)
        {
            if (!$this->isAllowed()) return redirect(site_url());
            $data['package'] = $this->QuestionM->getPackage($id);
            $data['questions'] = $this->QuestionM->getQuestions($id);
            echo json_encode($data);
        }

        public function editPackage()
        {
            if (!$this->isAllowed()) return redirect(site_url());
            $this->QuestionM->editPackage();
            $oldCount = $this->QuestionM->getQuestions($this->input->post('package_id'));

            $arrNew = array();
            $count = 0;
            foreach ($this->input->post() as $key => $value) {
                if (strpos($key, 'answer') !== false) {
                    $arrNew[$count] = $this->input->post('questionId' . ($count + 1));
                    $count++;
                }
            }

            if (!empty($oldCount)) {
                $questionIds = array_column($oldCount, 'question_id');
            }

            $difference = array_diff($questionIds, $arrNew);
            foreach ($difference as $value) {
                $this->QuestionM->deleteQuestion($value);
            }

            for ($i = 1; $i <= $count; $i++) {
                $data = array(
                    'question'      => $this->input->post('question' . $i),
                    'answer'        => $this->input->post('answerSelect' . $i),
                    'a'             => $this->input->post('aOption' . $i),
                    'b'             => $this->input->post('bOption' . $i),
                    'c'             => $this->input->post('cOption' . $i),
                    'd'             => $this->input->post('dOption' . $i),
                    'q_level'       => $this->input->post('levelSelect' . $i),
                    'modified_date' => date('Y/m/d H:i:s'),
                    'modified_by'   => $this->session->userdata('npk'),
                    'status'        => 1,
                );

                if ($i <= count($oldCount)) {
                    $where = array(
                        'question_id'    => $this->input->post('questionId' . $i),
                    );

                    $this->QuestionM->editQuestion($data, $where);
                } else {
                    $data['created_by'] = $this->session->userdata('npk');
                    $data['created_date'] = date('Y/m/d H:i:s');
                    $data['package_id'] = $this->input->post('package_id');

                    $this->QuestionM->saveQuestion($data);
                }
            }

            redirect('Question/getPackage');
        }


        public function deletePackage($id)
        {
            if (!$this->isAllowed()) return redirect(site_url());
            $result = $this->QuestionM->deletePackage($id);

            if ($result) {
                echo json_encode(array('success' => true));
            } else {
                echo json_encode(array('success' => false, 'error' => 'Deletion failed.'));
            }

            redirect('Question/getPackage');
        }

        public function savePreExam()
        {
            if (!$this->isAllowed()) return redirect(site_url());
            $this->QuestionM->savePreExam();
            redirect('Question');
        }

        public function getPreExam($id)
        {
            if (!$this->isAllowed()) return redirect(site_url());
            $npk = $this->session->userdata('npk');
            $data['notif']        = $this->TrainingM->getNotif($npk);
            $data['notifMateri'] = $this->TrainingM->getNotifMateri($npk);
            $data['totalNotif'] = count($data['notif']) + count($data['notifMateri']);
            $data['score'] = 'x';
            $data['preExam']        = $this->QuestionM->getPreExam($id);
            $this->load->view('exam', $data);
        }




        public function saveExam()
        {
            $npk = $this->session->userdata('npk');

            $count = 0;
            foreach ($this->input->post() as $key => $value) {
                if (strpos($key, 'answer') !== false) {
                    $count++;
                }
            }
            $idPackage = $this->input->post('idPackage');

            //   $idQuestion = $this->input->post('idQuestion');
            $totalQuestion = $this->QuestionM->getTotalQuestion($idPackage);

            $trueAnswer = 0;

            for ($i = 0; $i < $count; $i++) {
                $idQuestion = $this->input->post('idQuestion' . $i);
                $answerUser = $this->input->post('answer' . $i);

                $answerKey = $this->QuestionM->getAnswerKey($idQuestion);

                //  $this->QuestionM->saveAnswerUser($npk);
                if ($answerKey->answer       == $answerUser) {
                    $trueAnswer++;
                }

                // $data = array(
                //     'answerUser' => $answerUser,
                //     'examId' => $examId,
                //     'npk' => $npk,
                // );
                // $this->QuestionM->saveAnswerUser($data);
            }
            print_r($totalQuestion) + "true1";
            $score = round(($trueAnswer / $totalQuestion) * 100, 2);
            $data = array(
                'score' => $answerUser,
                'npk' =>    $this->session->userdata('npk'),
                'package_id' => $idPackage,
            );
            $this->score2 = $score;
            $data['trueAnswer'] = $trueAnswer;
            $data['totalQuestion'] = $totalQuestion;
            print_r($this->score2);
            $this->QuestionM->savePreExam();
            redirect('Question/getScore/' . $this->score2);
        }

        public function getScore($score3)
        {
            if (!$this->isAllowed()) return redirect(site_url());
            $npk = $this->session->userdata('npk');
            $data['notif'] = $this->TrainingM->getNotif($npk);
            $data['package'] = $this->QuestionM->getPackages();
            $data['notifMateri'] = $this->TrainingM->getNotifMateri($npk);
            $data['totalNotif'] = count($data['notif']) + count($data['notifMateri']);

            $score = $score3; // Remove the $ symbol
            $data['score'] = $score;
            $this->load->view('examResult', $data);
        }

        // public function getScoreTest($score3)
        // {
        //     if (!$this->isAllowed()) return redirect(site_url());
        //     $npk = $this->session->userdata('npk');
        //     $data['notif'] = $this->TrainingM->getNotif($npk);
        //     $data['package'] = $this->QuestionM->getPackages();
        //     $data['notifMateri'] = $this->TrainingM->getNotifMateri($npk);
        //     $data['totalNotif'] = count($data['notif']) + count($data['notifMateri']);
        //     $data['score'] = $this->QuestionM->getPackages();
        //     $score = $score3; // Remove the $ symbol
        //     $data['score'] = $score;
        //     $this->load->view('examResult', $data);
        // }


        public function getGlobalScore()
        {
            if (!$this->isAllowed()) return redirect(site_url());
            $npk = $this->session->userdata('npk');
            //    $data['score'] = $this->QuestionM->getGlobalScore();
            $data['notif'] = $this->TrainingM->getNotif($npk);

            $data['notifMateri'] = $this->TrainingM->getNotifMateri($npk);
            $data['totalNotif'] = count($data['notif']) + count($data['notifMateri']);

            $getScoreExam2    = $this->QuestionM->getGlobalScore();

            $getData = [];
            foreach ($getScoreExam2 as $a) {
                $employee = $this->OracleDBM->getEmpBy('NPK', $a->npk);
                if ($employee !== null && is_object($employee)) {
                    $combine = [
                        'npk' => $employee->NPK,
                        'nama' => $employee->NAMA,
                        'training_id' => $a->training_id,
                        'package_name' => $a->package_name,
                        'scorePre' => $a->scorePre,
                        'scorePost' => $a->scorePost,
                        'package_id' => $a->package_id
                    ];
                    $getData[] = $combine;
                } else {
                }
            }

            $data['score']   = $getData;

            $this->load->view('exam/score', $data);
        }
    }
