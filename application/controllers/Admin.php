<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("OracleDBM");
        $this->load->model("AdminM");
        $this->load->model("TrainingM");
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
        $this->load->library('form_validation');
    }

    public function index()
    {
        if (!$this->isAllowed()) return redirect(site_url());
        $admins = $this->AdminM->getAdmins();

        $detailEmployee = [];
        foreach ($admins as $adm) {
            $detailEmployee[] = $this->OracleDBM->getEmpBy('NPK', $adm->npk);
        }
        usort($detailEmployee, function($a, $b) {
            return strcmp($a->NAMA, $b->NAMA);
        });

        $npk = $this->session->userdata('npk');
        $data['admin']      = $detailEmployee;
        $data['tags']       = $this->AdminM->getTags();
        $data['countAdmin'] = $this->AdminM->getAdminTotal();
        $data['employee']   = $this->OracleDBM->getAllEmp();
        $data['dept']       = $this->OracleDBM->getAllDept();
        $data['notif']      = $this->TrainingM->getNotif($npk);

        $data['notifMateri']   = $this->TrainingM->getNotifMateri($npk);
        $data['totalNotif'] = count($data['notif']) + count($data['notifMateri']);
        foreach ($data['employee'] as &$employee) {
            $employee->isAdmin = $this->AdminM->isNpkAdmin($employee->NPK);
        }

        $this->load->view('admin_index', $data);
    }

    public function isAllowed()
    {
        return $this->session->userdata('isLogin') && $this->session->userdata('role') == 'admin';
    }

    public function getAdmins()
    {
        echo json_encode($this->AdminM->getAdmins());
    }

    public function saveAdmin()
    {
        if (!$this->isAllowed()) return redirect(site_url());
        $admins = json_decode($this->input->post('empSelected'));
        if (!empty($admins)) {
            foreach ($admins as $admin) {

                $this->AdminM->saveAdmin((string)$admin);
            }
        }
        redirect('Admin');
    }

    public function deleteAdmin($npk)
    {
        if (!$this->isAllowed()) return redirect(site_url());
        $this->AdminM->deleteAdmin($npk);
        redirect(site_url('Admin'));
    }

    public function saveTag()
    {
        if (!$this->isAllowed()) return redirect(site_url());
        $name = $this->input->post('nameTag');
        $color = $this->input->post('colorTag');
        $this->AdminM->saveTag($name, $color);
        redirect(site_url('Admin'));
    }

    public function deleteTag($id)
    {
        if (!$this->isAllowed()) return redirect(site_url());
        $count = $this->AdminM->getCountTag($id);
        print_r($count);
        if ($count > 0) {

            // echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>';
            // echo '<script>
            //         Swal.fire({
            //             title: "Error!",
            //             text: "Taggar tidak bisa dihapus karena masih digunakan",
            //             icon: "error",
            //             confirmButtonText: "OK",
            //         });
            //       </script>';
        } else {
            $this->AdminM->deleteTag($id);
            // echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>';
            // echo '<script>
            //     Swal.fire({
            //         title: "Success!",
            //         text: "Taggar berhasil dihapus",
            //         icon: "success",
            //         confirmButtonText: "OK",
            //     }).then(function(){
            //         window.location.href = "' . site_url('Admin') . '";
            //     });
            //   </script>';

        }
        redirect(site_url('Admin'));
    }
}
