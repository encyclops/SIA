<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Question extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
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
        $data['soal']        = $this->QuestionM->getQuestions();
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
        redirect('Question/getPackage');
    }


    public function retrievePackage($id)
    {
        if (!$this->isAllowed()) return redirect(site_url());
        $data = $this->QuestionM->getPackage($id);
        echo json_encode($data);
    }
    public function editPackage()
    {
        if (!$this->isAllowed()) return redirect(site_url());
        $this->QuestionM->editPackage();
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

    public function getPreExam()
    {
        if (!$this->isAllowed()) return redirect(site_url());
        $npk = $this->session->userdata('npk');
        $data['notif']        = $this->TrainingM->getNotif($npk);
        $data['notifMateri'] = $this->TrainingM->getNotifMateri($npk);
        $data['totalNotif'] = count($data['notif']) + count($data['notifMateri']);

        $data['preExam']        = $this->QuestionM->getPreExam();
        $this->load->view('exam', $data);
    }
}
