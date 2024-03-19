<?php defined('BASEPATH') or exit('No direct script access allowed');

class FPETM extends CI_Model
{
    private $t_fpet = "KMS_FPETFM";
    public function saveFpet($data)
    {
        $this->db->insert($this->t_fpet, $data);
    }

    public function makeTrain($data)
    {
        $this->db->insert('KMS_TRNHDR', $data);
    }

    public function getFpet()
    {
        $query = $this->db->query(
            "   SELECT  * 
                FROM    KMS_FPETFM
                WHERE   FPETFM_STATUS > 0"
        );
        return $query->result();
    }

    public function getApprovedFpet($npk)
    {
        $query = $this->db->query(
            "   SELECT  * 
                FROM    KMS_FPETFM
                WHERE   FPETFM_STATUS = 1
                AND     (   FPETFM_HRAPPROVER   = $npk
                            OR FPETFM_APPROVER  = $npk  )"
        );
        return $query->result();
    }
    public function detailFpet($id)
    {
        $query = $this->db->query(
            "   SELECT  * 
                FROM    KMS_FPETFM
                WHERE   FPETFM_ID = $id"
        );
        return $query->row();
    }
    public function removeFpet($id)
    {
        $data = array(
            'FPETFM_STATUS'     => 0,
            'FPETFM_MODIBY'     => $this->session->userdata('npk'),
            'FPETFM_MODIDATE'   => date('Y/m/d H:i:s'),
        );
        $where = array(
            'FPETFM_ID' => $id
        );

        return $this->db->update($this->t_fpet, $data, $where);
    }

    public function publishFpet($id)
    {
        $data = array(
            'FPETFM_STATUS'     => 1,
            'FPETFM_MODIBY'     => $this->session->userdata('npk'),
            'FPETFM_MODIDATE'   => date('Y/m/d H:i:s'),
        );
        $where = array(
            'FPETFM_ID' => $id
        );

        return $this->db->update($this->t_fpet, $data, $where);
    }



    public function rejectApproveFpet($id, $kode)
    {
        $data = array(
            'FPETFM_APPROVED'   => $kode,
            'FPETFM_MODIBY'     => $this->session->userdata('npk'),
            'FPETFM_MODIDATE'   => date('Y/m/d H:i:s'),
        );
        $where = array(
            'FPETFM_ID'    => $id
        );
        return $this->db->update($this->t_fpet, $data, $where);
    }

    public function rejectApproveHrFpet($id, $kode, $idTrain, $rEstablished)
    {
        $data = array(
            'FPETFM_HRAPPROVED' => $kode,
            'FPETFM_ESTABLISHED'=> $rEstablished,
            'TRNHDR_ID'         => $idTrain,
            'FPETFM_MODIBY'     => $this->session->userdata('npk'),
            'FPETFM_MODIDATE'   => date('Y/m/d H:i:s'),
        );

        $where = array(
            'FPETFM_ID'     => $id
        );

        return $this->db->update($this->t_fpet, $data, $where);
    }

    public function addParticipantTraining($data)
    {
        return $this->db->insert('KMS_TRNACC', $data);
    }

    public function modifyFpet($data, $id)
    {
        $where = array(
            'FPETFM_ID' => $id
        );
        return $this->db->update($this->t_fpet, $data, $where);
    }

    public function checkParticipant($participant, $idTrain)
    {
        $query = $this->db->query(
            "   SELECT  count (*) AS record_count
                FROM    KMS_TRNACC
                WHERE   TRNHDR_ID   = $idTrain
                AND     AWIEMP_NPK  = '$participant'  "
        );
        return $query->row()->record_count > 0;
    }
}
