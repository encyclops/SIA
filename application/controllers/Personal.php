<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Personal extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model("OracleDBM");
		$this->load->model("ChartM");
		$this->load->model("AdminM");
		$this->load->model("TrainingM");
		$this->load->helper(array('form', 'url'));
		$this->load->library('session');
		$this->load->library('form_validation');
	}

	public function index()
	{
		if (!$this->isAllowed()) return redirect(site_url());
		$npk = $this->session->userdata('npk');
		$data['getCountTraining']		= $this->ChartM->getCountTraining();
		$data['getCountSubstance']		= $this->ChartM->getCountSubstance();
		$data['getCountMyTraining']   	= $this->ChartM->getCountMyTraining();
		$data['getCountMySubstance']   	= $this->ChartM->getCountMySubstance();
		$data['getCountMyDoneLesson']   = $this->ChartM->getCountMyDoneLesson();
		$data['getCountMyNotDone']   	= $this->ChartM->getCountMyNotDone();
		$data['getCountMyDonePercent']  = $this->ChartM->getCountMyDonePercent();
		$data['getMyTrendAccess']   	= $this->ChartM->getMyTrendAccess();
		$data['getFavoriteSubstance']   = $this->ChartM->getFavoriteSubstance();
		$data['getHighestEmployee']   	= $this->ChartM->getHighestEmployee();
		$data['getFavoriteTraining']   	= $this->ChartM->getFavoriteTraining();
		$data['getNotDoneLesson']   	= $this->ChartM->getNotDoneLesson();
		$data['getNotOpenTrain']   		= $this->ChartM->getNotDoneEmployee();
		$data['notif']					= $this->TrainingM->getNotif($npk);
		$data['notifMateri']   = $this->TrainingM->getNotifMateri($npk);
		$data['totalNotif'] = count($data['notif']) + count($data['notifMateri']);;

		$this->load->view('training/training_me', $data);
	}

	public function isAllowed()
	{
		return $this->session->userdata('isLogin');
	}
}
