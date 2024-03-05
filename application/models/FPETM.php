<?php defined('BASEPATH') or exit('No direct script access allowed');

class FPETM extends CI_Model
{
    public function saveFpet($data)
    {
        $this->db->insert('fpet', $data);
    }

    public function saveTrain($data)
    {
        $this->db->insert('training_other', $data);
    }
    public function getFpet()
    {
        $query = $this->db->query(
            "   SELECT * 
                FROM fpet
                WHERE status = 1 or status = 2   "
        );
        return $query->result();
    }

    public function getApprovedFpet($npk)
    {
        $query = $this->db->query(
            "   SELECT * 
                FROM fpet
                WHERE  status = 1 and (approvedHr = $npk or approved = $npk) "
        );
        return $query->result();
    }
    public function detailFpet($id)
    {
        $query = $this->db->query(
            "   SELECT * 
                FROM fpet
                WHERE idFpet = $id"
        );
        return $query->row();
    }
    public function removeFpet($id)
    {
        $data = array(
            'status'        => 0,
            'modified_by'   => $this->session->userdata('npk'),
            'modified_date' => date('Y/m/d H:i:s'),
        );
        $where = array(
            'idFpet'    => $id
        );

        return $this->db->update('fpet', $data, $where);
    }

    public function publishFpet($id)
    {
        $data = array(
            'status'        => 1,

            'modified_by'   => $this->session->userdata('npk'),
            'modified_date' => date('Y/m/d H:i:s'),
        );
        $where = array(
            'idFpet'    => $id
        );

        return $this->db->update('fpet', $data, $where);
    }

    public function rejectApproveFpet($id, $kode)
    {
        $data = array(
            'statusApproved'        => $kode,

            'modified_by'   => $this->session->userdata('npk'),
            'modified_date' => date('Y/m/d H:i:s'),
        );
        $where = array(
            'idFpet'    => $id
        );

        return $this->db->update('fpet', $data, $where);
    }

    public function rejectApproveHrFpet($id, $kode)
    {
        $data = array(
            'statusApprovedHr'        => $kode,

            'modified_by'   => $this->session->userdata('npk'),
            'modified_date' => date('Y/m/d H:i:s'),
        );
        $where = array(
            'idFpet'    => $id
        );

        return $this->db->update('fpet', $data, $where);
    }



    public function modifyFpet($data, $id)
    {
        $where = array(
            'idFpet'    => $id
        );
        return $this->db->update('fpet', $data, $where);
    }
}
