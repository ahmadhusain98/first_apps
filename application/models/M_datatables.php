<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_datatables extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        setlocale(LC_ALL, 'id_ID.utf8');
        date_default_timezone_set('Asia/Jakarta');
    }

    private function _get_datatables_query($table, $columns, $order_arr, $order, $order2, $param1, $kondisi_param1)
    {
        $this->db->select($columns);
        $this->db->from($table);

        if (!empty($param1) && $param1 != 'semua') {
            $this->db->where([$kondisi_param1 => $param1]);
        }

        $i = 0;

        foreach ($columns as $item) {
            if (!empty($_POST['search']['value'])) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($columns) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($columns[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (!empty($order_arr)) {
            $orderan = $order_arr;
            $this->db->order_by(key($orderan), $orderan[key($orderan)]);
        }
    }

    public function get_datatables($table, $columns, $order_arr, $order, $order2, $param1, $kondisi_param1)
    {
        $this->_get_datatables_query($table, $columns, $order_arr, $order, $order2, $param1, $kondisi_param1);
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $this->input->post('start'));
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered($table, $columns, $order_arr, $order, $order2, $param1, $kondisi_param1)
    {
        $this->_get_datatables_query($table, $columns, $order_arr, $order, $order2, $param1, $kondisi_param1);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($table, $columns, $order_arr, $order, $order2, $param1, $kondisi_param1)
    {
        $this->db->select($columns);
        $this->db->from($table);

        if (!empty($param1) && $param1 != 'semua') {
            $this->db->where([$kondisi_param1 => $param1]);
        }

        return $this->db->count_all_results();
    }
}
