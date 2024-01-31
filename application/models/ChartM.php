<?php defined('BASEPATH') or exit('No direct script access allowed');

class ChartM extends CI_Model
{

    private $t_header = "training_header";
    private $t_detail = "training_detail";
    private $t_access = "training_access";
    private $t_progress = "training_progress";

    //chart
    public function getCountTraining()
    {
        $query = $this->db->query(
            "SELECT COUNT(*) as count_value
                 FROM $this->t_header
                 WHERE status = 2"
        );

        // Assuming $this->t_header is the table name, you can modify it accordingly
        return $query->row()->count_value;
    }

    public function getCountSubstance()
    {
        $query = $this->db->query(
            "SELECT COUNT(*) as count_value
                    FROM $this->t_detail inner join training_header on $this->t_detail.id_training_header = training_header.id_training_header
                    WHERE training_header.status = 2 and training_detail.status = 1"
        );

        // Assuming $this->t_header is the table name, you can modify it accordingly
        return $query->row()->count_value;
    }

    public function getCountDoneLesson()
    {
        $query = $this->db->query(

            "
                SELECT COUNT(DISTINCT tp.id_training_detail) AS count_value
                FROM training_progress tp
                INNER JOIN training_detail td ON td.id_training_detail = tp.id_training_detail
                INNER JOIN training_header th ON td.id_training_header = th.id_training_header
                WHERE tp.progress_status >= 1 AND th.status = 2 AND td.status = 1;
                
                "
        );

        return $query->row()->count_value;
    }

    public function getCountNotDoneEmp()
    {
        $query = $this->db->query(
            "SELECT 
                CASE
                    WHEN (
                        SELECT COUNT(*) AS count_value
                        FROM training_detail
                        INNER JOIN training_header ON training_detail.id_training_header = training_header.id_training_header
                        WHERE training_header.status = 2 AND training_detail.status = 1
                    ) = 0 THEN 0  -- Handling divide by zero by returning 0
                    ELSE
                        (
                            (
                                SELECT COUNT(*) AS count_value
                                FROM training_detail
                                INNER JOIN training_header ON training_detail.id_training_header = training_header.id_training_header
                                WHERE training_header.status = 2 AND training_detail.status = 1
                            ) 
                            - 
                            (
                                SELECT COUNT(DISTINCT td.id_training_detail) AS count_value
                                FROM training_progress tp
                                INNER JOIN training_detail td ON td.id_training_detail = tp.id_training_detail
                                INNER JOIN training_header th ON td.id_training_header = th.id_training_header
                                WHERE progress_status >= 1 AND th.status = 2 AND td.status = 1

                            )
                        ) * 100 / (
                            SELECT COUNT(*) AS count_value
                            FROM training_detail
                            INNER JOIN training_header ON training_detail.id_training_header = training_header.id_training_header
                            WHERE training_header.status = 2 AND training_detail.status = 1
                        )
                END AS result;"
        );

        // Assuming $this->t_header is the table name, you can modify it accordingly
        return $query->row()->result;
    }


    public function getFavoriteSubstance()
    {
        $query = $this->db->query(
            "WITH RankedProgress AS (
                    SELECT
                        tp.[id_training_detail],
                        tp.[npk],
                        tp.[progress_status],
                        tp.[created_date],
                        SUM(tp.[progress_status]) OVER (PARTITION BY tp.[id_training_detail]) AS total,
                        th.judul_training_header,
                        td.judul_training_detail
                    FROM
                        [training].[dbo].[training_progress] tp
                        INNER JOIN training_detail td ON td.id_training_detail = tp.id_training_detail
                        INNER JOIN training_header th ON th.id_training_header = td.id_training_header
                        where td.status = 1 and th.status = 2
                )
                SELECT
                    rp.id_training_detail,
                    MAX([total]) AS total,
                    rp.judul_training_header,
                    rp.judul_training_detail
                FROM
                    RankedProgress rp
                GROUP BY
                    rp.id_training_detail,
                    rp.judul_training_header,
                    rp.judul_training_detail
                ORDER BY
                    MAX([total]) DESC;
                "
        );

        return $query->result();
    }

    public function getFavoriteTraining()
    {
        $query = $this->db->query(
            "  WITH RankedProgress AS (
                            SELECT
                                tp.[id_training_detail],
                                tp.[npk],
                                tp.[progress_status],
                                tp.[created_date],
                                SUM(tp.[progress_status]) OVER (PARTITION BY tp.[id_training_detail]) AS total,
                                th.judul_training_header,
                                th.id_training_header,
                                td.judul_training_detail
                            FROM
                                [training].[dbo].[training_progress] tp
                                INNER JOIN training_detail td ON td.id_training_detail = tp.id_training_detail
                                INNER JOIN training_header th ON th.id_training_header = td.id_training_header
                                where td.status = 1 and th.status = 2
                        )
                        , AggregatedTotal AS (
                            SELECT
                                rp.id_training_detail,
                                rp.id_training_header,
                                MAX([total]) AS total,
                                rp.judul_training_header,
                                rp.judul_training_detail,
                                ROW_NUMBER() OVER (PARTITION BY rp.id_training_header ORDER BY MAX([total]) DESC) AS RowNum
                            FROM
                                RankedProgress rp
                            GROUP BY
                                rp.id_training_detail,
                                rp.id_training_header,
                                rp.judul_training_header,
                                rp.judul_training_detail
                        )
                        SELECT
                            at.id_training_detail,
                            at.id_training_header,
                            at.total,
                            at.judul_training_header,
                            at.judul_training_detail,
                            SUM(at.total) OVER (PARTITION BY at.id_training_header) AS total2
                        FROM
                            AggregatedTotal at
                        WHERE
                            at.RowNum = 1
                        ORDER BY
                            at.id_training_header; 
                "
        );

        return $query->result();
    }

    public function getHighestEmployee()
    {
        $status = $this->session->userdata('role') == 'admin' ? '> 0' : '= 2';
        $query = $this->db->query(
            "   WITH RankedProgress AS (
                    SELECT
                        ka.npk,
                        tp.[progress_status],
                        ROW_NUMBER() OVER (PARTITION BY ka.npk ORDER BY tp.[npk]) AS row_num
                    FROM
                        [training].[dbo].training_access ka
                    inner JOIN
                        training_progress tp ON tp.npk = ka.npk
                )
                SELECT 
                    npk,
                    progress_status,
                    total
                FROM (
                    SELECT
                        npk,
                        progress_status,
                        SUM(progress_status) OVER (PARTITION BY npk) AS total,
                        row_num
                    FROM
                        RankedProgress
                ) AS ranked
                WHERE
                    row_num = 1; "
        );

        return $query->result();
    }

    public function getNotDoneEmployee()
    {
        $status = $this->session->userdata('role') == 'admin' ? '> 0' : '= 2';
        $query = $this->db->query(
            "   
                SELECT DISTINCT ta2.npk
                FROM training_access ta2
                INNER JOIN training_header th2 ON th2.id_training_header = ta2.id_training_header
                INNER JOIN training_detail td2 ON th2.id_training_header = td2.id_training_header
                WHERE ta2.access_permission = '1' AND th2.status = '2' AND td2.status = '1'
                AND ta2.npk NOT IN (
                    SELECT ta1.npk
                    FROM training_access ta1
                    INNER JOIN training_header th1 ON th1.id_training_header = ta1.id_training_header
                    INNER JOIN training_detail td1 ON th1.id_training_header = td1.id_training_header
                    INNER JOIN training_progress tp1 ON tp1.id_training_detail = td1.id_training_detail
                    WHERE ta1.access_permission = '1' AND th1.status = '2' AND td1.status = '1'
                );
                
              "
        );

        return $query->result();
    }

    public function getNotOpenTrain()
    {
        $status = $this->session->userdata('role') == 'admin' ? '> 0' : '= 2';
        $query = $this->db->query(
            "   
                SELECT DISTINCT ta2.npk
              FROM training_access ta2
              INNER JOIN training_header th2 ON th2.id_training_header = ta2.id_training_header
              INNER JOIN training_detail td2 ON th2.id_training_header = td2.id_training_header
              WHERE ta2.access_permission = 1 AND th2.status = 2 AND td2.status = 1
                AND ta2.npk NOT IN (
                  SELECT DISTINCT ta1.npk
                  FROM training_access ta1
                  INNER JOIN training_header th1 ON th1.id_training_header = ta1.id_training_header
                  INNER JOIN training_detail td1 ON th1.id_training_header = td1.id_training_header
                  INNER JOIN training_progress tp1 ON tp1.id_training_detail = td1.id_training_detail
                  WHERE ta1.access_permission = 1 AND th1.status = 2 AND td1.status = 1
                );
              "
        );

        return $query->result();
    }

    public function getNotDoneLesson()
    {
        $status = $this->session->userdata('role') == 'admin' ? '> 0' : '= 2';
        $query = $this->db->query(
            "   
                    SELECT DISTINCT ta2.npk, th2.judul_training_header as judul_training_header, td2.judul_training_detail as judul_training_detail
                    FROM training_access ta2
                    INNER JOIN training_header th2 ON th2.id_training_header = ta2.id_training_header
                    INNER JOIN training_detail td2 ON th2.id_training_header = td2.id_training_header
                    WHERE ta2.access_permission = 1 AND th2.status = 2 AND td2.status = 1
              "
        );

        return $query->result();
    }

    public function getTrendAccess()
    {
        $query = $this->db->query(
            "   
                SELECT
                CONCAT(
                    YEAR(modified_date),
                    ' ',
                    FORMAT(modified_date, 'MMMM', 'id-ID')
                ) AS YearMonth,
                COUNT(*) AS RecordCount
            FROM
                [training].[dbo].[training_progress]
            WHERE
                modified_date >= DATEADD(MONTH, -5, GETDATE()) 
            GROUP BY
                YEAR(modified_date),
                FORMAT(modified_date, 'MMMM', 'id-ID'),
                MONTH(modified_date)
            ORDER BY
                YEAR(modified_date) ASC,
                MONTH(modified_date) ASC;
            
            
              "
        );

        return $query->result();
    }

    public function getCountMyTraining()
    {
        $npk = $this->session->userdata('npk');
        $query = $this->db->query(
            "select count(*) as count_value from training_access ta inner join training_header th 
                on th.id_training_header = ta.id_training_header 
                where  access_permission ='1' and th.status = '2' and ta.npk = "  . $npk
        );

        // Assuming $this->t_header is the table name, you can modify it accordingly
        return $query->row()->count_value;
    }


    public function getCountMySubstance()
    {
        $npk = $this->session->userdata('npk');
        $query = $this->db->query(
            "
                    SELECT COALESCE(COUNT(*), 0) as count_value
                    FROM training_detail
                    INNER JOIN training_header ON training_detail.id_training_header = training_header.id_training_header
                    INNER JOIN training_access ta ON ta.id_training_header = training_header.id_training_header
                    WHERE training_header.status = 2 AND ta.access_permission = 1 AND training_detail.status = 1 AND ta.npk = " . $npk

        );

        // Assuming $this->t_header is the table name, you can modify it accordingly
        return $query->row()->count_value;
    }

    public function getCountMyDoneLesson()
    {
        $npk = $this->session->userdata('npk');
        $query = $this->db->query(
            "  SELECT COUNT(*) AS count_value
                FROM training_progress tp
                inner join training_detail td on td.id_training_detail = tp.id_training_detail
                INNER JOIN training_header th ON td.id_training_header = th.id_training_header
                WHERE progress_status >= 1 and th.status = 2 and td.status = 1 and npk = " .  $npk
        );

        // Assuming $this->t_header is the table name, you can modify it accordingly
        return $query->row()->count_value;
    }

    public function getCountMyDonePercent()
    {
        $npk = $this->session->userdata('npk');

        $query = $this->db->query("
            WITH HeaderInfo AS (
                SELECT th.id_training_header, th.judul_training_header, ta.npk
                FROM training_header th
                INNER JOIN training_access ta ON ta.id_training_header = th.id_training_header
                WHERE th.status = '2' AND ta.npk = '$npk' AND ta.access_permission = '1'
            )
            , Counts AS (
                SELECT
                    hi.id_training_header,
                    hi.npk, 
                    (SELECT COUNT(*)
                     FROM training_progress p
                     JOIN training_detail d ON d.id_training_detail = p.id_training_detail
                     WHERE p.npk = hi.npk  
                       AND d.id_training_header = hi.id_training_header
                       AND d.status = 1) AS progress_count,
                    (SELECT COUNT(*)
                     FROM training_detail d
                     JOIN training_access a ON a.id_training_header = d.id_training_header
                     WHERE a.npk = hi.npk 
                       AND d.id_training_header = hi.id_training_header
                       AND a.access_permission = 1
                       AND d.status = 1) AS total_count
                FROM HeaderInfo hi
            )
            SELECT
                hi.id_training_header,
                hi.judul_training_header,
                CONCAT(COALESCE(c.progress_count, 0), '/', COALESCE(c.total_count, 0)) AS progress,
                ISNULL(CAST(COALESCE(c.progress_count, 0) * 100.0 / NULLIF(COALESCE(c.total_count, 0), 0) AS DECIMAL(10, 2)), 0) AS percentage
            FROM HeaderInfo hi
            LEFT JOIN Counts c ON hi.id_training_header = c.id_training_header;
            ");

        // Assuming $this->t_header is the table name, you can modify it accordingly
        return $query->result();
    }


    public function getCountMyNotDone()
    {
        $npk = $this->session->userdata('npk');

        $query = $this->db->query("
            SELECT
            CASE
                WHEN COALESCE(
                    (
                        SELECT COALESCE(COUNT(*), 0) as count_value
                        FROM training_detail
                        INNER JOIN training_header ON training_detail.id_training_header = training_header.id_training_header
                        INNER JOIN training_access ta ON ta.id_training_header = training_header.id_training_header
                        WHERE training_header.status = 2 AND training_detail.status = 1 AND ta.npk = $1 AND training_detail.id_training_header IS NOT NULL
                    ), 0
                ) = 0 THEN 0  -- Handling divide by zero by returning 0
                ELSE
                    COALESCE(
                        (
                            COALESCE(
                                (
                                    SELECT COALESCE(COUNT(*), 0) as count_value
                                    FROM training_detail
                                    INNER JOIN training_header ON training_detail.id_training_header = training_header.id_training_header
                                    INNER JOIN training_access ta ON ta.id_training_header = training_header.id_training_header
                                    WHERE training_header.status = 2 AND training_detail.status = 1 AND ta.npk = $npk AND training_detail.id_training_header IS NOT NULL
                                ), 0
                            )
                            -
                            COALESCE(
                                (
                                    SELECT COALESCE(COUNT(*), 0) AS count_value
                                    FROM training_progress tp
                                    INNER JOIN training_detail td ON td.id_training_detail = tp.id_training_detail
                                    INNER JOIN training_header th ON td.id_training_header = th.id_training_header
                                    WHERE progress_status >= 1 AND th.status = 2 AND td.status = 1 AND npk = $npk
                                ), 0
                            )
                        ) * 100
                        /
                        COALESCE(
                            (
                                SELECT COALESCE(COUNT(*), 0) as count_value
                                FROM training_detail
                                INNER JOIN training_header ON training_detail.id_training_header = training_header.id_training_header
                                INNER JOIN training_access ta ON ta.id_training_header = training_header.id_training_header
                                WHERE training_header.status = 2 AND training_detail.status = 1 AND ta.npk = $npk AND training_detail.id_training_header IS NOT NULL
                            ), 0
                        ),0
                    )
                END AS result
        
            
            ");

        // Assuming $this->t_header is the table name, you can modify it accordingly
        return $query->row()->result;
    }


    public function getMyTrendAccess()
    {
        $npk = $this->session->userdata('npk');
        $query = $this->db->query(
            "   
                SELECT
                CONCAT(
                    YEAR(modified_date),
                    ' ',
                    FORMAT(modified_date, 'MMMM', 'id-ID')
                ) AS YearMonth,
                COUNT(*) AS RecordCount
            FROM
                [training].[dbo].[training_progress]
            WHERE
                modified_date >= DATEADD(MONTH, -5, GETDATE()) 
                and npk =  '$npk'
            GROUP BY
                YEAR(modified_date),
                FORMAT(modified_date, 'MMMM', 'id-ID'),
                MONTH(modified_date)
            ORDER BY
                YEAR(modified_date) ASC,
                MONTH(modified_date) ASC;
            
            
              "
        );

        return $query->result();
    }
}
