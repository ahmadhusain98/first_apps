<?php

// add new field
function add_field($table, $kolom, $tipe, $length, $default)
{
    $CI           = &get_instance();

    $cfield = $CI->db->query("SELECT COLUMN_NAME 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_NAME = '$table' AND column_name = '$kolom'")->num_rows();

    if ($cfield < 1) {
        $CI->db->query("ALTER TABLE $table ADD $kolom $tipe($length) DEFAULT $default NULL");
        $text = "proses tambah kolom";
    } else {
        $text = "sudah ada";
    }

    return $text;
}

// dell new field
function dell_field($table, $kolom)
{
    $CI           = &get_instance();

    $cfield = $CI->db->query("SELECT COLUMN_NAME 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_NAME = '$table' AND column_name = '$kolom'")->num_rows();

    if ($cfield < 1) {
        $CI->db->query("ALTER TABLE $table DROP COLUMN $kolom");
        $text = "proses tambah kolom";
    } else {
        $text = "sudah ada";
    }

    return $text;
}

// send email
function _sendMail($emailUser, $title, $message)
{
    $CI = &get_instance();
    // configurasi email
    $config = [
        'protocol'  => 'smtp',
        'smtp_host' => 'ssl://smtp.googlemail.com',
        'smtp_user' => 'myhers.official@gmail.com',
        'smtp_pass' => 'gkgf yxav gone uqon',
        'smtp_port' => 465,
        'mailtype'  => 'html',
        'charset'   => 'utf-8',
        'newline'   => "\r\n"
    ];
    $CI->email->initialize($config);
    $CI->email->from('myhers.official@gmail.com', 'Myhers');
    $CI->email->to($emailUser);
    $CI->email->subject($title);
    $CI->email->message($message);
    if ($CI->email->send()) { // email terkirim
        echo json_encode(["status" => 1, "email" => $emailUser]);
    } else { // email gagal terkirim
        echo json_encode(["status" => 2, "email" => $emailUser]);
    }
}

function _codeUser($nama)
{
    $CI         = &get_instance();

    $inisial    = strtoupper(substr($nama, 0, 1));
    $lastNumber = $CI->db->query('SELECT * FROM user WHERE nama LIKE "' . $inisial . '%" ORDER BY nama DESC LIMIT 1')->row();
    $number     = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM user WHERE nama LIKE "' . $inisial . '%"')->result()) + 1;
        $kode_user    = $inisial . sprintf("%05d", $number);
    } else {
        $number       = 0;
        $kode_user    = $inisial . "00001";
    }
    return $kode_user;
}

function _kodeSatuan($keterangan)
{
    $CI         = &get_instance();

    $inisial    = strtoupper(substr($keterangan, 0, 1));
    $lastNumber = $CI->db->query('SELECT * FROM m_satuan WHERE kode_satuan LIKE "' . $inisial . '%" ORDER BY kode_satuan DESC LIMIT 1')->row();
    $number     = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM m_satuan WHERE kode_satuan LIKE "' . $inisial . '%"')->result()) + 1;
        $kode_user    = $inisial . sprintf("%05d", $number);
    } else {
        $number       = 0;
        $kode_user    = $inisial . "00001";
    }
    return $kode_user;
}

function _kodeKategori($keterangan)
{
    $CI         = &get_instance();

    $inisial    = strtoupper(substr($keterangan, 0, 1));
    $lastNumber = $CI->db->query('SELECT * FROM m_kategori WHERE kode_kategori LIKE "' . $inisial . '%" ORDER BY kode_kategori DESC LIMIT 1')->row();
    $number     = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM m_kategori WHERE kode_kategori LIKE "' . $inisial . '%"')->result()) + 1;
        $kode_user    = $inisial . sprintf("%05d", $number);
    } else {
        $number       = 0;
        $kode_user    = $inisial . "00001";
    }
    return $kode_user;
}

function _kodePoli($keterangan)
{
    $CI               = &get_instance();

    $inisial          = strtoupper(substr($keterangan, 0, 1));
    $lastNumber       = $CI->db->query('SELECT * FROM m_poli WHERE kode_poli LIKE "' . $inisial . '%" ORDER BY kode_poli DESC LIMIT 1')->row();
    $number           = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM m_poli WHERE kode_poli LIKE "' . $inisial . '%"')->result()) + 1;
        $kode_user    = $inisial . sprintf("%05d", $number);
    } else {
        $number       = 0;
        $kode_user    = $inisial . "00001";
    }
    return $kode_user;
}

function _kodeSupplier($keterangan)
{
    $CI         = &get_instance();

    $inisial    = strtoupper(substr($keterangan, 0, 1));
    $lastNumber = $CI->db->query('SELECT * FROM m_supplier WHERE kode_supplier LIKE "' . $inisial . '%" ORDER BY kode_supplier DESC LIMIT 1')->row();
    $number     = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM m_supplier WHERE kode_supplier LIKE "' . $inisial . '%"')->result()) + 1;
        $kode_user    = $inisial . sprintf("%05d", $number);
    } else {
        $number       = 0;
        $kode_user    = $inisial . "00001";
    }
    return $kode_user;
}

function _kodeGudang($keterangan)
{
    $CI         = &get_instance();

    $inisial    = strtoupper(substr($keterangan, 0, 1));
    $lastNumber = $CI->db->query('SELECT * FROM m_gudang WHERE kode_gudang LIKE "' . $inisial . '%" ORDER BY kode_gudang DESC LIMIT 1')->row();
    $number     = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM m_gudang WHERE kode_gudang LIKE "' . $inisial . '%"')->result()) + 1;
        $kode_user    = $inisial . sprintf("%05d", $number);
    } else {
        $number       = 0;
        $kode_user    = $inisial . "00001";
    }
    return $kode_user;
}

function _kodeBank($keterangan)
{
    $CI         = &get_instance();

    $inisial    = strtoupper(substr($keterangan, 0, 1));
    $lastNumber = $CI->db->query('SELECT * FROM m_bank WHERE kode_bank LIKE "' . $inisial . '%" ORDER BY kode_bank DESC LIMIT 1')->row();
    $number     = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM m_bank WHERE kode_bank LIKE "' . $inisial . '%"')->result()) + 1;
        $kode_user    = $inisial . sprintf("%05d", $number);
    } else {
        $number       = 0;
        $kode_user    = $inisial . "00001";
    }
    return $kode_user;
}

function _kodePekerjaan($keterangan)
{
    $CI         = &get_instance();

    $inisial    = strtoupper(substr($keterangan, 0, 1));
    $lastNumber = $CI->db->query('SELECT * FROM m_pekerjaan WHERE kode_pekerjaan LIKE "' . $inisial . '%" ORDER BY kode_pekerjaan DESC LIMIT 1')->row();
    $number     = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM m_pekerjaan WHERE kode_pekerjaan LIKE "' . $inisial . '%"')->result()) + 1;
        $kode_user    = $inisial . sprintf("%05d", $number);
    } else {
        $number       = 0;
        $kode_user    = $inisial . "00001";
    }
    return $kode_user;
}

function _kodeBarang($keterangan)
{
    $CI         = &get_instance();

    $inisial    = strtoupper(substr($keterangan, 0, 1));
    $lastNumber = $CI->db->query('SELECT * FROM barang WHERE kode_barang LIKE "' . $inisial . '%" ORDER BY kode_barang DESC LIMIT 1')->row();
    $number     = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM barang WHERE kode_barang LIKE "' . $inisial . '%"')->result()) + 1;
        $kode_user    = $inisial . sprintf("%05d", $number);
    } else {
        $number       = 0;
        $kode_user    = $inisial . "00001";
    }
    return $kode_user;
}

function _kodeLogistik($keterangan)
{
    $CI         = &get_instance();

    $inisial    = strtoupper(substr($keterangan, 0, 1));
    $lastNumber = $CI->db->query('SELECT * FROM logistik WHERE kode_logistik LIKE "' . $inisial . '%" ORDER BY kode_logistik DESC LIMIT 1')->row();
    $number     = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM logistik WHERE kode_logistik LIKE "' . $inisial . '%"')->result()) + 1;
        $kode_user    = $inisial . sprintf("%05d", $number);
    } else {
        $number       = 0;
        $kode_user    = $inisial . "00001";
    }
    return $kode_user;
}

function _kodeAgama($keterangan)
{
    $CI         = &get_instance();

    $inisial    = strtoupper(substr($keterangan, 0, 1));
    $lastNumber = $CI->db->query('SELECT * FROM m_agama WHERE kode_agama LIKE "' . $inisial . '%" ORDER BY kode_agama DESC LIMIT 1')->row();
    $number     = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM m_agama WHERE kode_agama LIKE "' . $inisial . '%"')->result()) + 1;
        $kode_user    = $inisial . sprintf("%05d", $number);
    } else {
        $number       = 0;
        $kode_user    = $inisial . "00001";
    }
    return $kode_user;
}

function _kodePendidikan($keterangan)
{
    $CI         = &get_instance();

    $inisial    = strtoupper(substr($keterangan, 0, 1));
    $lastNumber = $CI->db->query('SELECT * FROM m_pendidikan WHERE kode_pendidikan LIKE "' . $inisial . '%" ORDER BY kode_pendidikan DESC LIMIT 1')->row();
    $number     = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM m_pendidikan WHERE kode_pendidikan LIKE "' . $inisial . '%"')->result()) + 1;
        $kode_user    = $inisial . sprintf("%05d", $number);
    } else {
        $number       = 0;
        $kode_user    = $inisial . "00001";
    }
    return $kode_user;
}

function _codeMember($nama)
{
    $CI         = &get_instance();

    $inisial    = strtoupper(substr($nama, 0, 1));
    $lastNumber = $CI->db->query('SELECT * FROM member WHERE nama LIKE "' . $inisial . '%" ORDER BY nama DESC LIMIT 1')->row();
    $number     = 1;
    if ($lastNumber) {
        $number         = count($CI->db->query('SELECT * FROM member WHERE nama LIKE "' . $inisial . '%"')->result()) + 1;
        $kode_member    = $inisial . sprintf("%05d", $number);
    } else {
        $number         = 0;
        $kode_member    = $inisial . "00001";
    }
    return $kode_member;
}

function _kodeDokter($keterangan)
{
    $CI         = &get_instance();

    $inisial    = strtoupper(substr($keterangan, 0, 1));
    $lastNumber = $CI->db->query('SELECT * FROM dokter WHERE kode_dokter LIKE "' . $inisial . '%" ORDER BY kode_dokter DESC LIMIT 1')->row();
    $number     = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM dokter WHERE kode_dokter LIKE "' . $inisial . '%"')->result()) + 1;
        $kode_user    = $inisial . sprintf("%05d", $number);
    } else {
        $number       = 0;
        $kode_user    = $inisial . "00001";
    }
    return $kode_user;
}

function _kodePerawat($keterangan)
{
    $CI         = &get_instance();

    $inisial    = strtoupper(substr($keterangan, 0, 1));
    $lastNumber = $CI->db->query('SELECT * FROM perawat WHERE kode_perawat LIKE "' . $inisial . '%" ORDER BY kode_perawat DESC LIMIT 1')->row();
    $number     = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM perawat WHERE kode_perawat LIKE "' . $inisial . '%"')->result()) + 1;
        $kode_user    = $inisial . sprintf("%05d", $number);
    } else {
        $number       = 0;
        $kode_user    = $inisial . "00001";
    }
    return $kode_user;
}

function _kodeTrx($kode_poli)
{
    $CI           = &get_instance();

    $now          = date('Y-m-d');

    $poli         = $CI->db->get_where('m_poli', ['kode_poli' => $kode_poli])->row();

    $awal         = strtoupper(substr($poli->keterangan, 0, 2));

    $lastNumber   = $CI->db->query('SELECT * FROM pendaftaran WHERE tgl_daftar = "' . $now . '" AND kode_poli = "' . $kode_poli . '" ORDER BY id DESC LIMIT 1')->row();
    $number       = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM pendaftaran WHERE tgl_daftar = "' . $now . '" AND kode_poli = "' . $kode_poli . '"')->result()) + 1;
        $kode_user    = $awal . '~' . date('dmY') . sprintf("%05d", $number);
    } else {
        $number       = 0;
        $kode_user    = $awal . '~' . date('dmY') . "00001";
    }
    return $kode_user;
}

function _invoice()
{
    $CI           = &get_instance();

    $now          = date('Y-m-d');

    $lastNumber   = $CI->db->query('SELECT * FROM barang_in_header WHERE tgl_beli = "' . $now . '" ORDER BY id DESC LIMIT 1')->row();
    $number       = 1;
    if ($lastNumber) {
        $number   = $CI->db->query('SELECT * FROM barang_in_header WHERE tgl_beli = "' . $now . '"')->num_rows() + 1;
        $invoice  = 'INV~' . date('dmY') . sprintf("%05d", $number);
    } else {
        $number   = 0;
        $invoice  = 'INV~' . date('dmY') . "00001";
    }
    return $invoice;
}

function _invoice_retur()
{
    $CI           = &get_instance();

    $now          = date('Y-m-d');

    $lastNumber   = $CI->db->query('SELECT * FROM barang_in_retur_header WHERE tgl_beli = "' . $now . '" ORDER BY id DESC LIMIT 1')->row();
    $number       = 1;
    if ($lastNumber) {
        $number   = $CI->db->query('SELECT * FROM barang_in_retur_header WHERE tgl_beli = "' . $now . '"')->num_rows() + 1;
        $invoice  = 'REINV~' . date('dmY') . sprintf("%05d", $number);
    } else {
        $number   = 0;
        $invoice  = 'REINV~' . date('dmY') . "00001";
    }
    return $invoice;
}

function hitungStokBrgIn($detail, $kode_gudang, $invoice)
{
    $CI   = &get_instance();

    $date = date('Y-m-d');
    $time = date('H:i:s');
    $user = $CI->session->userdata('kode_user');

    foreach ($detail as $d) {
        $cek = $CI->M_global->jumDataRow('barang_stok', ['kode_gudang' => $kode_gudang, 'kode_barang' => $d->kode_barang]);

        if ($cek < 1) {
            $isi_stok = [
                'kode_barang'   => $d->kode_barang,
                'kode_gudang'   => $kode_gudang,
                'masuk'         => $d->qty,
                'akhir'         => $d->qty,
                'last_tgl_trx'  => $date,
                'last_jam_trx'  => $time,
                'last_no_trx'   => $invoice,
                'last_user'     => $user,
            ];

            $CI->M_global->insertData('barang_stok', $isi_stok);
        } else {
            $CI->db->query("UPDATE barang_stok SET 
            masuk = masuk + $d->qty, 
            akhir = akhir + $d->qty, 
            last_tgl_trx = '$date', 
            last_jam_trx = '$time',
            last_no_trx = '$invoice',
            last_user = '$user' 
            WHERE kode_barang = '$d->kode_barang' AND kode_gudang = '$kode_gudang'");
        }
    }
}

function hitungStokBrgOut($detail, $kode_gudang, $invoice)
{
    $CI   = &get_instance();

    $date = date('Y-m-d');
    $time = date('H:i:s');
    $user = $CI->session->userdata('kode_user');

    foreach ($detail as $d) {
        $cek = $CI->M_global->jumDataRow('barang_stok', ['kode_gudang' => $kode_gudang, 'kode_barang' => $d->kode_barang]);

        if ($cek < 1) {
            $isi_stok = [
                'kode_barang'   => $d->kode_barang,
                'kode_gudang'   => $kode_gudang,
                'masuk'         => 0 - $d->qty,
                'akhir'         => 0 - $d->qty,
                'last_tgl_trx'  => $date,
                'last_jam_trx'  => $time,
                'last_no_trx'   => $invoice,
                'last_user'     => $user,
            ];

            $CI->M_global->insertData('barang_stock', $isi_stok);
        } else {
            $CI->db->query("UPDATE barang_stok SET 
            masuk = masuk - $d->qty, 
            akhir = akhir - $d->qty, 
            last_tgl_trx = '$date', 
            last_jam_trx = '$time',
            last_no_trx = '$invoice',
            last_user = '$user' 
            WHERE kode_barang = '$d->kode_barang' AND kode_gudang = '$kode_gudang'");
        }
    }
}

function hitungStokBrgRtIn($detail, $kode_gudang, $invoice)
{
    $CI   = &get_instance();

    $date = date('Y-m-d');
    $time = date('H:i:s');
    $user = $CI->session->userdata('kode_user');

    foreach ($detail as $d) {
        $cek = $CI->M_global->jumDataRow('barang_stok', ['kode_gudang' => $kode_gudang, 'kode_barang' => $d->kode_barang]);

        if ($cek < 1) {
            $isi_stok = [
                'kode_barang'   => $d->kode_barang,
                'kode_gudang'   => $kode_gudang,
                'keluar'        => $d->qty,
                'akhir'         => 0 - $d->qty,
                'last_tgl_trx'  => $date,
                'last_jam_trx'  => $time,
                'last_no_trx'   => $invoice,
                'last_user'     => $user,
            ];

            $CI->M_global->insertData('barang_stok', $isi_stok);
        } else {
            $CI->db->query("UPDATE barang_stok SET 
            keluar = keluar + $d->qty, 
            akhir = akhir - $d->qty, 
            last_tgl_trx = '$date', 
            last_jam_trx = '$time',
            last_no_trx = '$invoice',
            last_user = '$user' 
            WHERE kode_barang = '$d->kode_barang' AND kode_gudang = '$kode_gudang'");
        }
    }
}

function hitungStokBrgRtOut($detail, $kode_gudang, $invoice)
{
    $CI   = &get_instance();

    $date = date('Y-m-d');
    $time = date('H:i:s');
    $user = $CI->session->userdata('kode_user');

    foreach ($detail as $d) {
        $cek = $CI->M_global->jumDataRow('barang_stok', ['kode_gudang' => $kode_gudang, 'kode_barang' => $d->kode_barang]);

        if ($cek < 1) {
            $isi_stok = [
                'kode_barang'   => $d->kode_barang,
                'kode_gudang'   => $kode_gudang,
                'keluar'        => 0 - $d->qty,
                'akhir'         => $d->qty,
                'last_tgl_trx'  => $date,
                'last_jam_trx'  => $time,
                'last_no_trx'   => $invoice,
                'last_user'     => $user,
            ];

            $CI->M_global->insertData('barang_stock', $isi_stok);
        } else {
            $CI->db->query("UPDATE barang_stok SET 
            keluar = keluar - $d->qty, 
            akhir = akhir + $d->qty, 
            last_tgl_trx = '$date', 
            last_jam_trx = '$time',
            last_no_trx = '$invoice',
            last_user = '$user' 
            WHERE kode_barang = '$d->kode_barang' AND kode_gudang = '$kode_gudang'");
        }
    }
}

function _invoiceJual($kopoli)
{
    $CI   = &get_instance();

    $now  = date('Y-m-d');

    $poli = $CI->M_global->getData('m_poli', ['kode_poli' => $kopoli]);

    if ($poli) {
        $awal         = strtoupper(substr($poli->keterangan, 0, 2));
    } else {
        $awal         = 'MS';
    }

    $lastNumber   = $CI->db->query('SELECT * FROM barang_out_header WHERE kode_poli = "' . $kopoli . '" AND tgl_jual = "' . $now . '" ORDER BY id DESC LIMIT 1')->row();
    $number       = 1;
    if ($lastNumber) {
        $number   = $CI->db->query('SELECT * FROM barang_out_header WHERE kode_poli = "' . $kopoli . '" AND tgl_jual = "' . $now . '"')->num_rows() + 1;
        $invoice  = 'INV~' . $awal . date('dmY') . sprintf("%05d", $number);
    } else {
        $number   = 0;
        $invoice  = 'INV~' . $awal . date('dmY') . "00001";
    }
    return $invoice;
}

function hitungStokJualOut($detail, $kode_gudang, $invoice)
{
    $CI   = &get_instance();

    $date = date('Y-m-d');
    $time = date('H:i:s');
    $user = $CI->session->userdata('kode_user');

    foreach ($detail as $d) {
        $cek = $CI->M_global->jumDataRow('barang_stok', ['kode_gudang' => $kode_gudang, 'kode_barang' => $d->kode_barang]);

        if ($cek < 1) {
            $isi_stok = [
                'kode_barang'   => $d->kode_barang,
                'kode_gudang'   => $kode_gudang,
                'keluar'        => 0 - $d->qty,
                'akhir'         => $d->qty,
                'last_tgl_trx'  => $date,
                'last_jam_trx'  => $time,
                'last_no_trx'   => $invoice,
                'last_user'     => $user,
            ];

            $CI->M_global->insertData('barang_stock', $isi_stok);
        } else {
            $CI->db->query("UPDATE barang_stok SET 
            keluar = keluar - $d->qty, 
            akhir = akhir + $d->qty, 
            last_tgl_trx = '$date', 
            last_jam_trx = '$time',
            last_no_trx = '$invoice',
            last_user = '$user' 
            WHERE kode_barang = '$d->kode_barang' AND kode_gudang = '$kode_gudang'");
        }
    }
}

function hitungStokJualIn($detail, $kode_gudang, $invoice)
{
    $CI   = &get_instance();

    $date = date('Y-m-d');
    $time = date('H:i:s');
    $user = $CI->session->userdata('kode_user');

    foreach ($detail as $d) {
        $cek = $CI->M_global->jumDataRow('barang_stok', ['kode_gudang' => $kode_gudang, 'kode_barang' => $d->kode_barang]);

        if ($cek < 1) {
            $isi_stok = [
                'kode_barang'   => $d->kode_barang,
                'kode_gudang'   => $kode_gudang,
                'keluar'        => $d->qty,
                'akhir'         => 0 - $d->qty,
                'last_tgl_trx'  => $date,
                'last_jam_trx'  => $time,
                'last_no_trx'   => $invoice,
                'last_user'     => $user,
            ];

            $CI->M_global->insertData('barang_stock', $isi_stok);
        } else {
            $CI->db->query("UPDATE barang_stok SET 
            keluar = keluar + $d->qty, 
            akhir = akhir - $d->qty, 
            last_tgl_trx = '$date', 
            last_jam_trx = '$time',
            last_no_trx = '$invoice',
            last_user = '$user' 
            WHERE kode_barang = '$d->kode_barang' AND kode_gudang = '$kode_gudang'");
        }
    }
}

function tokenKasir($jum)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $jum; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }

    return $randomString;
}

function _invoiceKasir()
{
    $CI           = &get_instance();

    $now          = date('Y-m-d');

    $lastNumber   = $CI->db->query('SELECT * FROM pembayaran WHERE tgl_pembayaran = "' . $now . '" ORDER BY id DESC LIMIT 1')->row();
    $number       = 1;
    if ($lastNumber) {
        $number   = $CI->db->query('SELECT * FROM pembayaran WHERE tgl_pembayaran = "' . $now . '"')->num_rows() + 1;
        $invoice  = 'KWITANSI~' . date('dmY') . sprintf("%05d", $number);
    } else {
        $number   = 0;
        $invoice  = 'KWITANSI~' . date('dmY') . "00001";
    }
    return $invoice;
}

function _invoiceDepoUM()
{
    $CI           = &get_instance();

    $now          = date('Y-m-d');

    $lastNumber   = $CI->db->query('SELECT * FROM pembayaran_uangmuka WHERE tgl_pembayaran = "' . $now . '" ORDER BY id DESC LIMIT 1')->row();
    $number       = 1;
    if ($lastNumber) {
        $number   = $CI->db->query('SELECT * FROM pembayaran_uangmuka WHERE tgl_pembayaran = "' . $now . '"')->num_rows() + 1;
        $invoice  = 'DEPOUM~' . date('dmY') . sprintf("%05d", $number);
    } else {
        $number   = 0;
        $invoice  = 'DEPOUM~' . date('dmY') . "00001";
    }
    return $invoice;
}

function _invoiceChart($user)
{
    $CI           = &get_instance();

    $now          = date('Y-m-d');

    $lastNumber   = $CI->db->query('SELECT * FROM cart_header WHERE tgl_order = "' . $now . '" ORDER BY id DESC LIMIT 1')->row();
    $number       = 1;
    if ($lastNumber) {
        $number   = $CI->db->query('SELECT * FROM cart_header WHERE tgl_order = "' . $now . '"')->num_rows() + 1;
        $invoice  = 'ORDER~' . $user . '/' . date('dmY') . sprintf("%05d", $number);
    } else {
        $number   = 0;
        $invoice  = 'ORDER~' . $user . '/' . date('dmY') . "00001";
    }
    return $invoice;
}

function _invoiceRetur()
{
    $CI           = &get_instance();

    $now          = date('Y-m-d');

    $lastNumber   = $CI->db->query('SELECT * FROM barang_out_retur_header WHERE tgl_retur = "' . $now . '" ORDER BY id DESC LIMIT 1')->row();
    $number       = 1;
    if ($lastNumber) {
        $number   = $CI->db->query('SELECT * FROM barang_out_retur_header WHERE tgl_retur = "' . $now . '"')->num_rows() + 1;
        $invoice  = 'RE-INV~' . date('dmY') . sprintf("%05d", $number);
    } else {
        $number   = 0;
        $invoice  = 'RE-INV~' . date('dmY') . "00001";
    }
    return $invoice;
}

function hitungStokReturJualIn($detail, $kode_gudang, $invoice)
{
    $CI   = &get_instance();

    $date = date('Y-m-d');
    $time = date('H:i:s');
    $user = $CI->session->userdata('kode_user');

    foreach ($detail as $d) {
        $cek = $CI->M_global->jumDataRow('barang_stok', ['kode_gudang' => $kode_gudang, 'kode_barang' => $d->kode_barang]);

        if ($cek < 1) {
            $isi_stok = [
                'kode_barang'   => $d->kode_barang,
                'kode_gudang'   => $kode_gudang,
                'masuk'         => 0 - $d->qty,
                'akhir'         => 0 - $d->qty,
                'last_tgl_trx'  => $date,
                'last_jam_trx'  => $time,
                'last_no_trx'   => $invoice,
                'last_user'     => $user,
            ];

            $CI->M_global->insertData('barang_stock', $isi_stok);
        } else {
            $CI->db->query("UPDATE barang_stok SET 
            masuk = masuk - $d->qty, 
            akhir = akhir - $d->qty, 
            last_tgl_trx = '$date', 
            last_jam_trx = '$time',
            last_no_trx = '$invoice',
            last_user = '$user' 
            WHERE kode_barang = '$d->kode_barang' AND kode_gudang = '$kode_gudang'");
        }
    }
}

function hitungStokReturJualOut($detail, $kode_gudang, $invoice)
{
    $CI   = &get_instance();

    $date = date('Y-m-d');
    $time = date('H:i:s');
    $user = $CI->session->userdata('kode_user');

    foreach ($detail as $d) {
        $cek = $CI->M_global->jumDataRow('barang_stok', ['kode_gudang' => $kode_gudang, 'kode_barang' => $d->kode_barang]);

        if ($cek < 1) {
            $isi_stok = [
                'kode_barang'   => $d->kode_barang,
                'kode_gudang'   => $kode_gudang,
                'masuk'         => $d->qty,
                'akhir'         => $d->qty,
                'last_tgl_trx'  => $date,
                'last_jam_trx'  => $time,
                'last_no_trx'   => $invoice,
                'last_user'     => $user,
            ];

            $CI->M_global->insertData('barang_stock', $isi_stok);
        } else {
            $CI->db->query("UPDATE barang_stok SET 
            masuk = masuk + $d->qty, 
            akhir = akhir + $d->qty, 
            last_tgl_trx = '$date', 
            last_jam_trx = '$time',
            last_no_trx = '$invoice',
            last_user = '$user' 
            WHERE kode_barang = '$d->kode_barang' AND kode_gudang = '$kode_gudang'");
        }
    }
}

function _kodeJenis()
{
    $CI           = &get_instance();

    $lastNumber   = $CI->db->query('SELECT * FROM m_jenis ORDER BY kode_jenis DESC LIMIT 1')->row();
    $number       = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM m_jenis')->result()) + 1;
        $kode_jenis   = 'JO' . sprintf("%05d", $number);
    } else {
        $number       = 0;
        $kode_jenis   = 'JO' . "00001";
    }
    return $kode_jenis;
}

function updateUangMukaIn($kode_member, $invoice, $tgl_pembayaran, $jam_pembayaran, $total)
{
    $CI = &get_instance();

    $cek = $CI->M_global->jumDataRow('uang_muka', ['kode_member' => $kode_member]);

    if ($cek > 0) { // jika cek ada um maka update
        $CI->db->query("UPDATE uang_muka SET 
            last_tgl = '$tgl_pembayaran', 
            last_jam = '$jam_pembayaran', 
            last_invoice = '$invoice', 
            uang_masuk = uang_masuk + '$total', 
            uang_sisa = uang_sisa + '$total' 
        WHERE kode_member = '$kode_member'");
    } else { // selain itu inser
        $isi = [
            'last_tgl'      => $tgl_pembayaran,
            'last_jam'      => $jam_pembayaran,
            'last_invoice'  => $invoice,
            'kode_member'   => $kode_member,
            'uang_masuk'    => $total,
            'uang_keluar'   => 0,
            'uang_sisa'     => $total,
        ];

        $CI->db->insert('uang_muka', $isi);
    }
}

function updateUangMukaUpdate($kode_member, $invoice, $tgl_pembayaran, $jam_pembayaran, $total, $um_awal)
{
    $CI = &get_instance();

    $cek = $CI->M_global->jumDataRow('uang_muka', ['kode_member' => $kode_member]);

    if ($um_awal) {
        $total_awal = $um_awal;
    } else {
        $total_awal = 0;
    }

    if ($cek > 0) { // jika cek ada um maka update
        $CI->db->query("UPDATE uang_muka SET 
            last_tgl = '$tgl_pembayaran', 
            last_jam = '$jam_pembayaran', 
            last_invoice = '$invoice', 
            uang_masuk = uang_masuk - '$total_awal' + '$total', 
            uang_sisa = uang_sisa - '$total_awal' + '$total' 
        WHERE kode_member = '$kode_member'");
    } else { // selain itu inser
        $isi = [
            'last_tgl'      => $tgl_pembayaran,
            'last_jam'      => $jam_pembayaran,
            'last_invoice'  => $invoice,
            'kode_member'   => $kode_member,
            'uang_masuk'    => $total,
            'uang_keluar'   => 0,
            'uang_sisa'     => $total,
        ];

        $CI->db->insert('uang_muka', $isi);
    }
}

function updateUangMukaDelete($kode_member, $invoice, $tgl_pembayaran, $jam_pembayaran, $total)
{
    $CI = &get_instance();

    $cek = $CI->M_global->jumDataRow('uang_muka', ['kode_member' => $kode_member]);

    if ($cek > 0) { // jika cek ada um maka update
        $CI->db->query("UPDATE uang_muka SET 
            last_tgl = '$tgl_pembayaran', 
            last_jam = '$jam_pembayaran', 
            last_invoice = '$invoice', 
            uang_masuk = uang_masuk - '$total', 
            uang_sisa = uang_sisa - '$total' 
        WHERE kode_member = '$kode_member'");
    } else { // selain itu inser
        $isi = [
            'last_tgl'      => $tgl_pembayaran,
            'last_jam'      => $jam_pembayaran,
            'last_invoice'  => $invoice,
            'kode_member'   => $kode_member,
            'uang_masuk'    => 0 - $total,
            'uang_keluar'   => 0,
            'uang_sisa'     => 0 - $total,
        ];

        $CI->db->insert('uang_muka', $isi);
    }
}

function hitung_umur($tgl_lahir)
{
    $tanggal_lahir = new DateTime($tgl_lahir);
    $sekarang = new DateTime("today");
    if ($tanggal_lahir > $sekarang) {
        $thn = "0";
        $bln = "0";
        $tgl = "0";
    }
    $thn = $sekarang->diff($tanggal_lahir)->y;
    $bln = $sekarang->diff($tanggal_lahir)->m;
    $tgl = $sekarang->diff($tanggal_lahir)->d;
    return $thn . " tahun " . $bln . " bulan " . $tgl . " hari";
}

function barcode($kode_barang)
{
    $redColor = [255, 0, 0];
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    file_put_contents('barcode.png', $generator->getBarcode($kode_barang, $generator::TYPE_CODE_128, 3, 50, $redColor));

    echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($kode_barang, $generator::TYPE_CODE_128)) . '">';
}

function _code_promo()
{
    $CI         = &get_instance();

    $lastNumber = $CI->db->query('SELECT * FROM m_promo ORDER BY id DESC LIMIT 1')->row();
    $number     = 1;
    if ($lastNumber) {
        $number         = count($CI->db->query('SELECT * FROM m_promo')->result()) + 1;
        $kode_member    = 'P' . sprintf("%04d", $number);
    } else {
        $number         = 0;
        $kode_member    = 'P' . "0001";
    }
    return $kode_member;
}
