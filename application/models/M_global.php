<?php
class M_global extends CI_Model
{
    // fungsi ambil semua baris
    function getResult($table)
    {
        return $this->db->get($table)->result();
    }

    // fungsi ambil data 1 baris berdasarkan lemparan tertentu
    function getData($table, $kondisi)
    {
        return $this->db->get_where($table, $kondisi)->row();
    }

    // fungsi ambil data 1 baris berdasarkan lemparan tertentu
    function getDataResult($table, $kondisi)
    {
        return $this->db->get_where($table, $kondisi)->result();
    }

    // fungsi cek jumlah data
    function jumDataRow($table, $kondisi)
    {
        return $this->db->get_where($table, $kondisi)->num_rows();
    }

    // fungsi insert data
    function insertData($table, $isi)
    {
        return $this->db->insert($table, $isi);
    }

    // fungsi update data berdasarkan lemparan tertentu
    function updateData($table, $isi, $kondisi)
    {
        return $this->db->update($table, $isi, $kondisi);
    }

    // fungsi hapus data berdasarkan lemparan tertentu
    function delData($table, $kondisi)
    {
        return $this->db->delete($table, $kondisi);
    }

    // fungsi ambil data menggunakan like
    function getDataLike($table, $field1, $field2, $kondisi)
    {
        return $this->db->query('SELECT * FROM ' . $table . ' WHERE (' . $field1 . ' LIKE "%' . $kondisi . '%" OR ' . $field2 . ' LIKE "%' . $kondisi . '%")')->row();
    }

    // fungsi track record stok pembelian
    function getReportPembelian($dari, $sampai, $kode_gudang)
    {
        $sintax = $this->db->query("SELECT * FROM (
            SELECT h.invoice AS no_trx,
            CONCAT('Pembelian ~ ', s.nama) AS keterangan,
            CONCAT(d.kode_barang, ' ~ ', b.nama) AS barang,
            d.qty AS masuk,
            '0' AS keluar,
            (d.harga - (d.discrp / d.qty)) AS harga,
            h.tgl_beli,
            h.jam_beli,
            h.kode_gudang,
            CONCAT(DATE_FORMAT(h.tgl_beli, '%d/%m/%Y'), ' ~ ', h.jam_beli) AS record_date
            FROM barang_in_header h
            JOIN barang_in_detail d ON h.invoice = d.invoice
            JOIN barang b ON d.kode_barang = b.kode_barang
            JOIN m_supplier s ON h.kode_supplier = s.kode_supplier

            UNION ALL

            SELECT h.invoice AS no_trx,
            CONCAT('Retur Pembelian ~ ', s.nama) AS keterangan,
            CONCAT(d.kode_barang, ' ~ ', b.nama) AS barang,
            '0' AS masuk,
            d.qty AS keluar,
            (d.harga - (d.discrp / d.qty)) AS harga,
            h.tgl_beli,
            h.jam_beli,
            h.kode_gudang,
            CONCAT(DATE_FORMAT(h.tgl_beli, '%d/%m/%Y'), ' ~ ', h.jam_beli) AS record_date
            FROM barang_in_retur_header h
            JOIN barang_in_retur_detail d ON h.invoice = d.invoice
            JOIN barang b ON d.kode_barang = b.kode_barang
            JOIN m_supplier s ON h.kode_supplier = s.kode_supplier
        ) AS m_pembelian
        WHERE kode_gudang = '$kode_gudang' AND tgl_beli >= '$dari' AND tgl_beli <= '$sampai' ORDER BY tgl_beli, jam_beli ASC")->result();

        return $sintax;
    }

    // fungsi track record stok penjualan
    function getReportPenjualan($dari, $sampai, $kode_gudang)
    {
        $sintax = $this->db->query("SELECT * FROM (
            SELECT h.invoice AS no_trx,
            CONCAT('Penjualan ~ ', s.nama) AS keterangan,
            CONCAT(d.kode_barang, ' ~ ', b.nama) AS barang,
            d.qty AS masuk,
            '0' AS keluar,
            (d.harga - (d.discrp / d.qty)) AS harga,
            h.tgl_jual AS tgl,
            h.jam_jual AS jam,
            h.kode_gudang,
            CONCAT(DATE_FORMAT(h.tgl_jual, '%d/%m/%Y'), ' ~ ', h.jam_jual) AS record_date
            FROM barang_out_header h
            JOIN barang_out_detail d ON h.invoice = d.invoice
            JOIN barang b ON d.kode_barang = b.kode_barang
            JOIN member s ON h.kode_member = s.kode_member

            UNION ALL

            SELECT h.invoice AS no_trx,
            CONCAT('Retur Penjualan ~ ', h.invoice_jual) AS keterangan,
            CONCAT(d.kode_barang, ' ~ ', b.nama) AS barang,
            '0' AS masuk,
            d.qty AS keluar,
            (d.harga - (d.discrp / d.qty)) AS harga,
            h.tgl_retur AS tgl,
            h.jam_retur AS jam,
            h.kode_gudang,
            CONCAT(DATE_FORMAT(h.tgl_retur, '%d/%m/%Y'), ' ~ ', h.jam_retur) AS record_date
            FROM barang_out_retur_header h
            JOIN barang_out_retur_detail d ON h.invoice = d.invoice
            JOIN barang b ON d.kode_barang = b.kode_barang
        ) AS m_pembelian
        WHERE kode_gudang = '$kode_gudang' AND tgl >= '$dari' AND tgl <= '$sampai' ORDER BY tgl, jam ASC")->result();

        return $sintax;
    }
}
