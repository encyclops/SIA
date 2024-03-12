<?php defined('BASEPATH') or exit('No direct script access allowed');

class SettingM extends CI_Model
{
    private $t_setting = "awits_setting";

    public function getSettings()
    {
        $query = $this->db->query(
            "   SELECT *
                FROM $this->t_setting   "
        );
        return $query->result();
    }

    public function editSetting($data, $where)
    {
        return $this->db->update($this->t_setting, $data, $where);
    }
}