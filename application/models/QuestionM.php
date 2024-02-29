<?php defined('BASEPATH') or exit('No direct script access allowed');

class QuestionM extends CI_Model
{
    private $t_question = "training_question";

    public function getQuestions()
    {
        $query = $this->db->query(
            "   SELECT *
                FROM $this->t_question
                WHERE status = 1        "
        );
        return $query->result();
    }

    public function getQuestion($id)
    {
        $query = $this->db->query(
            "   SELECT  question_id, question, answer AS answerSelect,
                        q_level AS levelSelect, a AS aOption, b AS bOption,
                        c AS cOption, d AS dOption
                FROM $this->t_question
                WHERE question_id = $id "
        );
        return $query->row();
    }

    public function saveQuestion()
    {
        $data = array(
            'status'        => 1,
            'question'      => $this->input->post('question'),
            'answer'        => $this->input->post('answerSelect'),
            'a'             => $this->input->post('aOption'),
            'b'             => $this->input->post('bOption'),
            'c'             => $this->input->post('cOption'),
            'd'             => $this->input->post('dOption'),
            'q_level'       => $this->input->post('levelSelect'),
            'created_date'  => date('Y/m/d H:i:s'),
            'modified_date' => date('Y/m/d H:i:s'),
            'created_by'    => $this->session->userdata('npk'),
            'modified_by'   => $this->session->userdata('npk'),
        );
        return $this->db->insert($this->t_question, $data);
    }

    public function editQuestion()
    {
        $data = array(
            'question'      => $this->input->post('question'),
            'answer'        => $this->input->post('answerSelect'),
            'a'             => $this->input->post('aOption'),
            'b'             => $this->input->post('bOption'),
            'c'             => $this->input->post('cOption'),
            'd'             => $this->input->post('dOption'),
            'q_level'       => $this->input->post('levelSelect'),
            'modified_date' => date('Y/m/d H:i:s'),
            'modified_by'   => $this->session->userdata('npk'),
        );
        $where = array(
            'question_id'    => $this->input->post('question_id')
        );
        return $this->db->update($this->t_question, $data, $where);
    }

    public function deleteQuestion($id)
    {
        $data = array(
            'status'        => 0,
            'modified_date' => date('Y/m/d H:i:s'),
            'modified_by'   => $this->session->userdata('npk'),
        );
        $where = array(
            'question_id'    => $id
        );
        return $this->db->update($this->t_question, $data, $where);
    }
}