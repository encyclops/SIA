<?php defined('BASEPATH') or exit('No direct script access allowed');

class TrainingM extends CI_Model
{
    private $t_header = "training_header";
    private $t_detail = "training_detail";
    private $t_access = "training_access";
    private $t_progress = "training_progress";
    private $t_tagdetail = "training_tag_detail";

    /* Notes */
    /*
        Notification from line 300+
    */

    public function isAdmin()
    {
        return $this->session->userdata('role') == 'admin';
    }

    public function saveTraining()
    {
        $data = array(
            'status'                => 1,
            'judul_training_header' => $this->input->post('temaTraining'),
            'pemateri'              => $this->input->post('pemateri'),
            'created_date'          => date('Y/m/d H:i:s'),
            'modified_date'         => date('Y/m/d H:i:s'),
            'created_by'            => $this->session->userdata('npk'),
            'modified_by'           => $this->session->userdata('npk'),
        );
        return $this->db->insert($this->t_header, $data);
    }

    public function saveParticipant($npk, $id)
    {
        $data = array(
            'access_permission' => $this->isAdmin() ? 1 : 2,
            'npk'               => $npk,
            'id_training_header' => $id,
            'created_date'      => date('Y/m/d H:i:s'),
            'modified_date'     => date('Y/m/d H:i:s'),
            'created_by'        => $this->session->userdata('npk'),
            'modified_by'       => $this->session->userdata('npk'),
        );
        return $this->db->insert($this->t_access, $data);
    }

    public function saveSubstance($path, $judul, $id)
    {
        $data = array(
            'path_file_training_detail' => $path,
            'judul_training_detail'     => $judul,
            'id_training_header'        => $id,
            'status'                    => $this->isAdmin() ? 1 : 2,
            'created_date'              => date('Y/m/d H:i:s'),
            'modified_date'             => date('Y/m/d H:i:s'),
            'created_by'                => $this->session->userdata('npk'),
            'modified_by'               => $this->session->userdata('npk'),
        );
        return $this->db->insert($this->t_detail, $data);
    }

    public function saveTagDetail($id_tag, $id_header)
    {
        $data = array(
            'id_tag'            => $id_tag,
            'id_training_header' => $id_header,
        );
        return $this->db->insert($this->t_tagdetail, $data);
    }

    public function saveProgress($id)
    {
        $npk = $this->session->userdata('npk');
        $query = $this->db->query(
            "   SELECT *
                FROM $this->t_progress
                WHERE npk = '$npk'
                AND id_training_detail = $id    "
        );

        $data = array(
            'npk'               => $npk,
            'id_training_detail' => $id,
            'progress_status'   => 1,
            'created_date'      => date('Y/m/d H:i:s'),
            'modified_date'     => date('Y/m/d H:i:s'),
        );

        if ($query->row()) {
            return $this->db->where('npk', $npk)
                ->where('id_training_detail', $id)
                ->set('progress_status', 'progress_status + 1', FALSE)
                ->set('modified_date', "'" . date('Y/m/d H:i:s') . "'", FALSE)
                ->update($this->t_progress);
        } else {
            return $this->db->insert($this->t_progress, $data);
        }
    }

    public function modifyApproval($npk, $status)
    {
        if ($npk != '') {
            return $this->db->where('id_training_header', $this->input->post('id'))
                ->where('npk', $npk)
                ->set('access_permission', $status, FALSE)
                ->set('modified_date', "'" . date('Y/m/d H:i:s') . "'", FALSE)
                ->set('modified_by', $this->session->userdata('npk'), FALSE)
                ->update($this->t_access);
        } else {
            return $this->db->where('id_training_detail', $this->input->post('idDetail'))
                ->set('status', $status, FALSE)
                ->set('modified_date', "'" . date('Y/m/d H:i:s') . "'", FALSE)
                ->set('modified_by', $this->session->userdata('npk'), FALSE)
                ->update($this->t_detail);
        }
    }

    public function modifyAccess($key, $value)
    {
        $data = array(
            $key => $value,
            'modified_by'   => $this->session->userdata('npk'),
            'modified_date' => date('Y/m/d H:i:s'),
        );
        $where = array(
            'id_training_header'    => $this->input->post('header'),
            'npk'                    => $this->input->post('npk'),
        );
        return $this->db->update($this->t_access, $data, $where);
    }

    public function modifyTraining($id, $code)
    {
        $data = array(
            'status'        => $code,
            'modified_by'   => $this->session->userdata('npk'),
            'modified_date' => date('Y/m/d H:i:s'),
        );
        $where = array(
            'id_training_header'    => $id,
        );
        return $this->db->update($this->t_header, $data, $where);
    }

    public function modifyTrainingHeader()
    {
        $data = array(
            'judul_training_header' => $this->input->post('temaTraining'),
            'pemateri'              => $this->input->post('pemateri'),
            'modified_date'         => date('Y/m/d H:i:s'),
            'modified_by'           => $this->session->userdata('npk'),
        );
        $where = array(
            'id_training_header'    => $this->input->post('idTraining')
        );
        return $this->db->update($this->t_header, $data, $where);
    }

    public function modifySubstance($id)
    {
        return $this->db->where('id_training_detail', $id)
            ->set('status', 0, FALSE)
            ->set('modified_date', "'" . date('Y/m/d H:i:s') . "'", FALSE)
            ->set('modified_by', $this->session->userdata('npk'), FALSE)
            ->update($this->t_detail);
    }

    public function modifyParticipant($npk, $id)
    {
        $this->db->where('id_training_header', $id)
             ->where('npk', $npk);

        $access_permission = $this->db->get($this->t_access)->row()->access_permission;

        $this->db->where('id_training_header', $id)
             ->where('npk', $npk);
        $this->db->set('access_permission', $this->isAdmin() ? 1 : 2, FALSE)
                ->set('modified_date', "'" . date('Y/m/d H:i:s') . "'", FALSE)
                ->set('modified_by', $this->session->userdata('npk'), FALSE);

        if ($access_permission == 0) {
            $this->db->set('created_date', "'" . date('Y/m/d H:i:s') . "'", FALSE)
                    ->set('created_by', $this->session->userdata('npk'), FALSE);
        }

        return $this->db->update($this->t_access);
    }

    public function resetParticipant($npks, $id)
    {
        if (empty($npks)) {
            return;
        }

        $npk = implode("','", $npks);
        $status = $this->isAdmin() ? '0' : '2';
        $this->db   ->where('id_training_header', $id)
                    ->set('access_permission', $status, FALSE)
                    ->set('modified_date', "'" . date('Y/m/d H:i:s') . "'", FALSE)
                    ->set('modified_by', $this->session->userdata('npk'), FALSE);
            
        if ($this->isAdmin()) {
            $this->db   ->where_not_in('npk', $npk);
        } else {
            $this->db   ->where_in('npk', $npks, FALSE)
                        ->where('access_permission !=', 1);
        }
        $this->db->update($this->t_access);
    }

    public function resetTags($tagID, $id)
    {
        $tag = implode(",", $tagID);
        $this->db   ->where('id_training_header', $id)
                    ->where_not_in('id_tag', explode(",", $tag));

        $this->db->delete($this->t_tagdetail);
    }

    public function getAllSubstance()
    {
        $status = $this->isAdmin() ? '> 0' : '= 1';
        $query = $this->db->query(
            "   SELECT *
                FROM $this->t_detail
                WHERE status " . $status
        );
        return $query->result();
    }

    public function getTrainingHeader($id)
    {
        $query = $this->db->query(
            "   SELECT *
                FROM $this->t_header
                WHERE id_training_header = " . $id
        );
        return $query->result();
    }

    public function getTrainingByNPK($isAll, $keyword, $tag)
    {
        $status = $this->isAdmin() ? '> 0' : '= 2';
        $npk = $this->session->userdata('npk');
        $column = $isAll ? '' : 'a.*,';
        $column .= $tag == '' ? '' : 'td.*,';
        $table = $isAll ? '' : ' JOIN training_access a ON h.id_training_header = a.id_training_header';
        $table .= $tag == '' ? '' : ' JOIN training_tag_detail td ON h.id_training_header = td.id_training_header';
        $queryAdd = $isAll ? '' : " AND a.npk = '$npk' AND a.access_permission = 1";
        $queryAdd .= $tag == '' ? '' : " AND td.id_tag = $tag";
        $searchBy = $keyword == '' ? '' : " AND LOWER(h.judul_training_header) LIKE '%$keyword%'";

        $query = $this->db->query(
            "   SELECT " . $column . " h.*,
                    (   SELECT COUNT(*)
                        FROM $this->t_detail d
                        WHERE d.id_training_header = h.id_training_header
                        AND d.status = 1) AS detail_count,
                    (   SELECT COUNT(*)
                        FROM $this->t_access a
                        WHERE a.id_training_header = h.id_training_header
                        AND a.access_permission = 1) AS participant_count,
                    IIF((SELECT COUNT(*) FROM $this->t_detail d
                        WHERE d.id_training_header = h.id_training_header
                        AND d.status = 2) > 0, 'true', 'false') AS detail_request,
                    IIF((SELECT COUNT(*) FROM $this->t_access a
                        WHERE a.id_training_header = h.id_training_header
                        AND a.access_permission = 2) > 0, 'true', 'false') AS participant_request
                FROM $this->t_header h "
                . $table . "
                WHERE h.status " . $status . $queryAdd . $searchBy . " 
                ORDER BY detail_request, participant_request DESC"
        );

        return $query->result();
    }

    public function getTrainingByStatus($status)
    {
        $quer = '';
        if ($status == '> x') { 
            $status = '> 0';
            $quer = "WHERE subquery.detail_request = 'true' OR subquery.participant_request = 'true'";
        }
        $query = $this->db->query(
            "   SELECT *
                FROM (
                    SELECT h.*,
                        (   SELECT COUNT(*)
                            FROM $this->t_detail d
                            WHERE d.id_training_header = h.id_training_header
                            AND d.status = 1) AS detail_count,
                        (   SELECT COUNT(*)
                            FROM $this->t_access a
                            WHERE a.id_training_header = h.id_training_header
                            AND a.access_permission = 1) AS participant_count,
                        IIF((SELECT COUNT(*) FROM $this->t_detail d
                            WHERE d.id_training_header = h.id_training_header
                            AND d.status = 2) > 0, 'true', 'false') AS detail_request,
                        IIF((SELECT COUNT(*) FROM $this->t_access a
                            WHERE a.id_training_header = h.id_training_header
                            AND a.access_permission = 2) > 0, 'true', 'false') AS participant_request
                    FROM $this->t_header h
                    WHERE h.status $status
                ) AS subquery " . $quer . "
                ORDER BY subquery.detail_request, subquery.participant_request DESC"
        );

        return $query->result();
    }

    public function getSubstanceByTraining($id)
    {
        $status = $this->isAdmin() ? '> 0 AND status < 3' : '= 1';
        $query = $this->db->query(
            "   SELECT *
                FROM $this->t_detail
                WHERE id_training_header = $id
                AND status " . $status
        );
        return $query->result();
    }

    public function getEmployeeByTraining($id)
    {
        $status = $this->isAdmin() ? '> 0 AND access_permission < 3' : '= 1';
        $query = $this->db->query(
            "   SELECT npk
                FROM $this->t_access
                WHERE id_training_header = $id
                AND access_permission " . $status
        );
        return $query->result();
    }

    public function getAccessByNPKID($npk, $id)
    {
        $query = $this->db->query(
            "   SELECT access_permission
                FROM $this->t_access
                WHERE id_training_header = $id
                AND npk = $npk                  "
        );
        return $query->row();
    }

    public function getProgress($id, $npk)
    {
        $query = $this->db->query(
            "   WITH Counts AS (
                    SELECT 
                    (   SELECT COUNT(*) 
                        FROM $this->t_progress p
                        JOIN $this->t_detail d
                            ON d.id_training_detail = p.id_training_detail 
                        WHERE p.npk = '$npk'
                        AND d.id_training_header = $id 
                        AND d.status = 1
                    ) AS progress_count,
                    (   SELECT COUNT(*) 
                        FROM $this->t_detail d
                        JOIN $this->t_access a
                            ON a.id_training_header = d.id_training_header 
                        WHERE a.npk = '$npk'
                        AND d.id_training_header = $id
                        AND a.access_permission = 1
                        AND d.status = 1
                    ) AS total_count
                )
                SELECT 
                    CONCAT(
                        COALESCE(progress_count, 0),
                        '/',
                        COALESCE(total_count, 0)
                    ) AS progress,
                    ISNULL(
                        CAST(
                            COALESCE(progress_count, 0) * 100.0 / NULLIF(COALESCE(total_count, 0), 0) 
                            AS DECIMAL(10,2)
                        ), 
                        0
                    ) AS percentage
                FROM Counts"
        );
        return $query->row();
    }

    public function hasRead($id)
    {
        $npk = $this->session->userdata('npk');
        $query = $this->db->query(
            "   SELECT COUNT(*) as record_count
                FROM $this->t_progress
                WHERE npk = '$npk'
                AND id_training_detail = $id"
        );
        return $query->row()->record_count > 0;
    }

    public function getAccessData($npk, $id)
    {
        $query = $this->db->query(
            "   SELECT TOP 1 
                    COALESCE([file], 0) AS [file],
                    COALESCE([part], 0) AS [part]
                FROM $this->t_access
                WHERE npk = '$npk'
                AND access_permission = 1
                AND id_training_header = $id
                UNION ALL
                SELECT 
                    0, 0
                WHERE NOT EXISTS (
                    SELECT 1 FROM training_access 
                    WHERE npk = '$npk'
                    AND id_training_header = $id
                    AND access_permission = 1
                )"
        );
        return $query->result();
    }

    public function getDataTag($idTag, $id)
    {
        $query = $this->db->query(
            "   SELECT COUNT(*) AS total
                FROM $this->t_tagdetail
                WHERE id_training_header = $id
                AND id_tag = $idTag             "
        );
        return $query->row()->total > 0;
    }

    public function getNotifications($npk)
    {
        $query = $this->db->query(
            "
            SELECT 
                'Detail' AS type,
                td.judul_training_detail AS judul,
                td.id_training_detail AS id,
                th.judul_training_header AS header_judul,
                NULL AS created_date,
                NULL AS npk
            FROM 
                training_detail td 
            INNER JOIN 
                training_header th ON th.id_training_header = td.id_training_header
            WHERE 
                td.status = '2' 
            
            UNION
            
            SELECT 
                'Access' AS type,
                th.judul_training_header AS judul,
                NULL AS id_training_detail,
                NULL AS header_judul,
                CONVERT(DATE, ta.created_date) AS created_date,
                ta.npk AS npk
            FROM 
                training_access ta
            INNER JOIN 
                training_header th ON th.id_training_header = ta.id_training_header
            WHERE 
                access_permission = '3' 
                AND ta.created_by = " . $npk
        );

        return $query->result();
    }

    //notif
    public function getNotif($npk)
    {
        $query = $this->db->query(
            "   
            SELECT
            th.judul_training_header AS judul,
            th.id_training_header as id_training_header,
            CONVERT(DATE, ta.created_date) AS created_date,
            ta.npk as npk 

        FROM
            training_access ta
        INNER JOIN
            training_header th ON th.id_training_header = ta.id_training_header
     
        WHERE
            access_permission = '3'
         
             and ta.created_by = " . $npk
        );

        return $query->result();
    }

    public function getNotifMateri($npk)
    {
        $query = $this->db->query(
            "   
            select td.judul_training_detail as judul_training_detail, th.judul_training_header as judul, td.id_training_detail as id_training_detail
            from training_detail td 
            inner join training_header th on th.id_training_header = td.id_training_header
            where td.status ='2' and td.created_by = " . $npk
        );

        return $query->result();
    }

    public function removeNotif($id, $npk)
    {
        $data = array(
            'access_permission'        => 0,

            'modified_by'   => $this->session->userdata('npk'),
            'modified_date' => date('Y/m/d H:i:s'),
        );
        $where = array(
            'id_training_header'    => $id,
            'npk' => $npk
        );

        return $this->db->update($this->t_access, $data, $where);
    }

    public function removeNotifMateri($id)
    {
        $data = array(
            'status'        => 0,

            'modified_by'   => $this->session->userdata('npk'),
            'modified_date' => date('Y/m/d H:i:s'),
        );
        $where = array(
            'id_training_detail'    => $id
        );

        return $this->db->update($this->t_detail, $data, $where);
    }
}
