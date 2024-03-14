<?php defined('BASEPATH') or exit('No direct script access allowed');

class QuestionM extends CI_Model
{
    private $t_question = "KMS_TRNQUE";
    private $t_package = "KMS_TRNPCK";

    public function getPackages()
    {
        $query = $this->db->query(
            "   SELECT  *
                FROM    KMS_TRNPCK
                WHERE   TRNPCK_STATUS   = 1 "
        );
        return $query->result();
    }

    public function getPackage($id)
    {
        $query = $this->db->query(
            "   SELECT  *
                FROM    KMS_TRNPCK
                WHERE   TRNPCK_ID       = $id
                AND     TRNPCK_STATUS   = 1     "
        );
        return $query->row();
    }

    public function getQuestions($id)
    {
        $query = $this->db->query(
            "   SELECT  *
                FROM    KMS_TRNQUE
                WHERE   TRNQUE_STATUS   = 1        
                AND     TRNPCK_ID       = $id   "
        );
        return $query->result();
    }

    public function saveQuestion($data)
    {
        return $this->db->insert($this->t_question, $data);
    }

    public function editQuestion($data, $where)
    {
        return $this->db->update($this->t_question, $data, $where);
    }

    public function savePackage($data)
    {
        return $this->db->insert($this->t_package, $data);
    }

    public function editPackage($data, $where)
    {
        return $this->db->update($this->t_package, $data, $where);
    }

    public function getGlobalScore()
    {
        $query = $this->db->query(
            "  SELECT *, tp.training_id, tp.package_name 
            FROM training_score as ts inner join training_question_package as tp  on ts.package_id = tp.package_id
           "
        );
        return $query->result();
    }

    public function getPreExam($id)
    {
        $query = $this->db->query(
            "  SELECT * 
            FROM training_question 
            WHERE package_id IN (SELECT package_id 
                                 FROM training_question_package 
                                 WHERE status = 1 AND training_id = '2070') "
        );
        return $query->result();
    }

    public function getpackageQuest($id)
    {
        $query = $this->db->query(
            "  SELECT * 
            FROM training_question_package
            where training_id = $id "
        );
        return $query->result();
    }

    public function getTotalQuestion($idPackage)
    {
        $query = $this->db->query(
            "SELECT COUNT(*) as totalQ
                 FROM training_question
                 WHERE status = 1 and package_id = $idPackage  "
        );

        // Assuming $this->t_header is the table name, you can modify it accordingly
        return $query->row()->totalQ;
    }

    public function getAnswerKey($id)
    {
        $query = $this->db->query(
            "   SELECT  answer
                FROM $this->t_question
                WHERE question_id = $id AND status = 1"
        );
        return $query->row();
    }

    // public function saveAnswerUser($data)
    // {
    //     return $this->db->insert('training_userAnswer', $data);
    // }

    public function savePreExam($data)
    {
        return $this->db->insert('training_score', $data);
    }

    public function savePostExam($data)
    {
        return $this->db->insert('training_score', $data);
    }
}