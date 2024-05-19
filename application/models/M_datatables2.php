<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_datatables2 extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        setlocale(LC_ALL, 'id_ID.utf8');
        date_default_timezone_set('Asia/Jakarta');
    }

    private function _get_datatables_query($table, $colum, $order_arr, $order, $order2, $kondisi_param1, $jns, $bulan, $tahun, $param2, $kondisi_param2)
    {
        if ($this->uri->segment(1) == 'Marketing') {
            $add_kondisi = ' <= ';
        } else {
            $add_kondisi = '';
        }
        $this->db->select($colum);
        $this->db->from($table);
        if (!empty($param2)) {

            if ($jns == 1) {
                $tanggal = date('Y-m-d');
                $this->db->where([$kondisi_param1 . $add_kondisi  => $tanggal]);

                if ($param2 != 'semua') {
                    $this->db->where([$kondisi_param2 => $param2]);
                }
            } else {
                if ($param2 != 'semua') {
                    $this->db->where([$kondisi_param2 => $param2]);
                }
                $this->db->where([$kondisi_param1 . ' >=' => $bulan, $kondisi_param1 . ' <= ' => $tahun]);
            }
        } else {

            if ($jns == 1) {
                $tanggal = date('Y-m-d');
                $this->db->where([$kondisi_param1 . $add_kondisi => $tanggal]);
            } else {
                $this->db->where([$kondisi_param1 . ' >=' => $bulan, $kondisi_param1 . ' <= ' => $tahun]);
            }
        }
        $i = 0;
        foreach ($colum as $item) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($colum) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }
        if (isset($_POST['order'])) {
            $this->db->order_by($colum[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($order_arr)) {
            $orderan = $order_arr;
            $this->db->order_by(key($orderan), $orderan[key($orderan)]);
        }
    }

    public function get_datatables($table, $colum, $order_arr, $order, $order2, $kondisi_param1, $jns, $bulan, $tahun, $param2, $kondisi_param2)
    {
        $this->_get_datatables_query($table, $colum, $order_arr, $order, $order2, $kondisi_param1, $jns, $bulan, $tahun, $param2, $kondisi_param2);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $this->input->post('start'));
        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered($table, $colum, $order_arr, $order, $order2, $kondisi_param1, $jns, $bulan, $tahun, $param2, $kondisi_param2)
    {
        $this->_get_datatables_query($table, $colum, $order_arr, $order, $order2, $kondisi_param1, $jns, $bulan, $tahun, $param2, $kondisi_param2);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($table, $colum, $order_arr, $order, $order2, $kondisi_param1, $jns, $bulan, $tahun, $param2, $kondisi_param2)
    {
        if ($this->uri->segment(1) == 'Marketing') {
            $add_kondisi = ' <= ';
        } else {
            $add_kondisi = '';
        }
        $this->db->select($colum);
        $this->db->from($table);
        if (!empty($param2)) {

            if ($jns == 1) {
                $tanggal = date('Y-m-d');
                $this->db->where([$kondisi_param1 . $add_kondisi => $tanggal]);

                if ($param2 != 'semua') {
                    $this->db->where([$kondisi_param2 => $param2]);
                }
            } else {
                if ($param2 != 'semua') {
                    $this->db->where([$kondisi_param2 => $param2]);
                }
                $this->db->where([$kondisi_param1 . ' >=' => $bulan, $kondisi_param1 . ' <= ' => $tahun]);
            }
        } else {

            if ($jns == 1) {
                $tanggal = date('Y-m-d');
                $this->db->where([$kondisi_param1 . $add_kondisi => $tanggal]);
            } else {
                $this->db->where([$kondisi_param1 . ' >=' => $bulan, $kondisi_param1 . ' <= ' => $tahun]);
            }
        }
        return $this->db->count_all_results();
    }
}
