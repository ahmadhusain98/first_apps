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
// function _sendMail($emailUser, $title, $message)
// {
//     $CI = &get_instance();
//     // configurasi email
//     $config = [
//         'protocol'  => 'smtp',
//         'smtp_host' => 'ssl://smtp.googlemail.com',
//         'smtp_user' => 'myhers.official@gmail.com',
//         'smtp_pass' => 'gkgf yxav gone uqon',
//         'smtp_port' => 465,
//         'mailtype'  => 'html',
//         'charset'   => 'utf-8',
//         'newline'   => "\r\n"
//     ];
//     $CI->email->initialize($config);
//     $CI->email->from('myhers.official@gmail.com', 'Myhers');
//     $CI->email->to($emailUser);
//     $CI->email->subject($title);
//     $CI->email->message($message);
//     if ($CI->email->send()) { // email terkirim
//         echo json_encode(["status" => 1, "email" => $emailUser]);
//     } else { // email gagal terkirim
//         echo json_encode(["status" => 2, "email" => $emailUser]);
//     }
// }

function aktifitas_user($menu, $message, $kode, $value)
{
    $CI         = &get_instance();
    $sess       = $CI->session->userdata('email');
    $cabang     = $CI->session->userdata('init_cabang');
    $shift      = $CI->session->userdata('shift');

    $aktifitas = [
        'email'         => $sess,
        'kegiatan'      => $sess . " Telah <b>" . $message . " " . $value . "</b> dengan kode/inv <b>" . $kode . "</b>",
        'menu'          => $menu,
        'waktu'         => date('Y-m-d H:i:s'),
        'kode_cabang'   => $cabang,
        'shift'         => $shift,
    ];

    $CI->db->insert("activity_user", $aktifitas);
}

function aktifitas_user_transaksi($menu, $message, $kode)
{
    $CI         = &get_instance();
    $sess       = $CI->session->userdata('email');
    $cabang     = $CI->session->userdata('init_cabang');
    $shift      = $CI->session->userdata('shift');

    $aktifitas = [
        'email'         => $sess,
        'kegiatan'      => $sess . " Telah <b>" . $message . "</b> dengan kode/inv <b>" . $kode . "</b>",
        'menu'          => $menu,
        'waktu'         => date('Y-m-d H:i:s'),
        'kode_cabang'   => $cabang,
        'shift'         => $shift,
    ];

    $CI->db->insert("activity_user", $aktifitas);
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

function _kodeAkun()
{
    $CI         = &get_instance();

    $inisial    = "AKN";
    $lastNumber = $CI->db->query('SELECT * FROM m_akun ORDER BY id DESC LIMIT 1')->row();
    $number     = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM m_akun')->result()) + 1;
        $kode_akun    = $inisial . sprintf("%07d", $number);
    } else {
        $number       = 0;
        $kode_akun    = $inisial . "0000001";
    }
    return $kode_akun;
}

function _kodeSatuan()
{
    $CI         = &get_instance();

    $inisial    = "SAT";
    $lastNumber = $CI->db->query('SELECT * FROM m_satuan ORDER BY kode_satuan DESC LIMIT 1')->row();
    $number     = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM m_satuan')->result()) + 1;
        $kode_user    = $inisial . sprintf("%07d", $number);
    } else {
        $number       = 0;
        $kode_user    = $inisial . "0000001";
    }
    return $kode_user;
}

function _kodeKategori()
{
    $CI         = &get_instance();

    $inisial    = "KAT";
    $lastNumber = $CI->db->query('SELECT * FROM m_kategori ORDER BY kode_kategori DESC LIMIT 1')->row();
    $number     = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM m_kategori')->result()) + 1;
        $kode_user    = $inisial . sprintf("%07d", $number);
    } else {
        $number       = 0;
        $kode_user    = $inisial . "0000001";
    }
    return $kode_user;
}

function _kodeTarif($jenis)
{
    $CI         = &get_instance();

    if ($jenis == 1) {
        $inisial    = "TRF-S";
    } else {
        $inisial    = "TRF-P";
    }
    $lastNumber = $CI->db->query('SELECT * FROM m_tarif WHERE jenis = "' . $jenis . '" ORDER BY id DESC LIMIT 1')->row();
    $number     = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM m_tarif WHERE jenis = "' . $jenis . '"')->result()) + 1;
        $kode_user    = $inisial . sprintf("%05d", $number);
    } else {
        $number       = 0;
        $kode_user    = $inisial . "00001";
    }
    return $kode_user;
}

function _kodePajak()
{
    $CI               = &get_instance();

    $inisial          = "PJK";
    $lastNumber       = $CI->db->query('SELECT * FROM m_pajak ORDER BY kode_pajak DESC LIMIT 1')->row();
    $number           = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM m_pajak')->result()) + 1;
        $kode_user    = $inisial . sprintf("%07d", $number);
    } else {
        $number       = 0;
        $kode_user    = $inisial . "0000001";
    }
    return $kode_user;
}

function _kodePoli()
{
    $CI               = &get_instance();

    $inisial          = "POL";
    $lastNumber       = $CI->db->query('SELECT * FROM m_poli ORDER BY kode_poli DESC LIMIT 1')->row();
    $number           = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM m_poli')->result()) + 1;
        $kode_user    = $inisial . sprintf("%07d", $number);
    } else {
        $number       = 0;
        $kode_user    = $inisial . "0000001";
    }
    return $kode_user;
}

function _kodeSupplier()
{
    $CI         = &get_instance();

    $inisial    = "SUP";
    $lastNumber = $CI->db->query('SELECT * FROM m_supplier ORDER BY kode_supplier DESC LIMIT 1')->row();
    $number     = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM m_supplier')->result()) + 1;
        $kode_user    = $inisial . sprintf("%07d", $number);
    } else {
        $number       = 0;
        $kode_user    = $inisial . "0000001";
    }
    return $kode_user;
}

function _kodeGudang()
{
    $CI         = &get_instance();

    $inisial    = "GUD";
    $lastNumber = $CI->db->query('SELECT * FROM m_gudang ORDER BY kode_gudang DESC LIMIT 1')->row();
    $number     = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM m_gudang')->result()) + 1;
        $kode_user    = $inisial . sprintf("%07d", $number);
    } else {
        $number       = 0;
        $kode_user    = $inisial . "0000001";
    }
    return $kode_user;
}

function _kodeBank()
{
    $CI         = &get_instance();

    $inisial    = "B";
    $lastNumber = $CI->db->query('SELECT * FROM m_bank ORDER BY kode_bank DESC LIMIT 1')->row();
    $number     = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM m_bank')->result()) + 1;
        $kode_user    = $inisial . sprintf("%09d", $number);
    } else {
        $number       = 0;
        $kode_user    = $inisial . "000000001";
    }
    return $kode_user;
}

function _kodePekerjaan()
{
    $CI         = &get_instance();

    $inisial    = "PEK";
    $lastNumber = $CI->db->query('SELECT * FROM m_pekerjaan WHERE kode_pekerjaan LIKE "' . $inisial . '%" ORDER BY kode_pekerjaan DESC LIMIT 1')->row();
    $number     = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM m_pekerjaan WHERE kode_pekerjaan LIKE "' . $inisial . '%"')->result()) + 1;
        $kode_user    = $inisial . sprintf("%07d", $number);
    } else {
        $number       = 0;
        $kode_user    = $inisial . "0000001";
    }
    return $kode_user;
}

function _kodeBarang($keterangan)
{
    $CI             = &get_instance();

    $kode_cabang    = $CI->session->userdata('init_cabang');

    $inisial        = strtoupper(substr($keterangan, 0, 1));
    $lastNumber     = $CI->db->query('SELECT * FROM barang WHERE kode_barang LIKE "' . $kode_cabang . $inisial . '%" ORDER BY kode_barang DESC LIMIT 1')->row();
    $number         = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM barang WHERE kode_barang LIKE "' . $kode_cabang . $inisial . '%"')->result()) + 1;
        $kode_user    = $kode_cabang . '~' . $inisial . sprintf("%05d", $number);
    } else {
        $number       = 0;
        $kode_user    = $kode_cabang . '~' . $inisial . "00001";
    }
    return $kode_user;
}

function _kodeLogistik($keterangan)
{
    $CI         = &get_instance();

    $kode_cabang    = $CI->session->userdata('init_cabang');

    $inisial    = strtoupper(substr($keterangan, 0, 1));
    $lastNumber = $CI->db->query('SELECT * FROM logistik WHERE kode_logistik LIKE "' . $kode_cabang . $inisial . '%" ORDER BY kode_logistik DESC LIMIT 1')->row();
    $number     = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM logistik WHERE kode_logistik LIKE "' . $kode_cabang . $inisial . '%"')->result()) + 1;
        $kode_user    = $kode_cabang . '~' . $inisial . sprintf("%05d", $number);
    } else {
        $number       = 0;
        $kode_user    = $kode_cabang . '~' . $inisial . "00001";
    }
    return $kode_user;
}

function _kodeAgama()
{
    $CI         = &get_instance();

    $inisial    = "AGM";
    $lastNumber = $CI->db->query('SELECT * FROM m_agama ORDER BY kode_agama DESC LIMIT 1')->row();
    $number     = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM m_agama')->result()) + 1;
        $kode_user    = $inisial . sprintf("%07d", $number);
    } else {
        $number       = 0;
        $kode_user    = $inisial . "0000001";
    }
    return $kode_user;
}

function _kodePendidikan()
{
    $CI         = &get_instance();

    $inisial    = "PEN";
    $lastNumber = $CI->db->query('SELECT * FROM m_pendidikan ORDER BY kode_pendidikan DESC LIMIT 1')->row();
    $number     = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM m_pendidikan')->result()) + 1;
        $kode_user    = $inisial . sprintf("%07d", $number);
    } else {
        $number       = 0;
        $kode_user    = $inisial . "0000001";
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

function _noAntrian($kode_poli, $kode_cabang, $tgl_daftar)
{
    $CI           = &get_instance();

    $now          = date('Y-m-d');

    $poli         = $CI->db->get_where('m_poli', ['kode_poli' => $kode_poli])->row();

    $awal         = strtoupper(substr($poli->keterangan, 0, 2));

    $lastNumber   = $CI->db->query('SELECT * FROM pendaftaran WHERE kode_cabang = "' . $kode_cabang . '" AND tgl_daftar = "' . $tgl_daftar . '" AND kode_poli = "' . $kode_poli . '" ORDER BY id DESC LIMIT 1')->row();
    $number       = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM pendaftaran WHERE kode_cabang = "' . $kode_cabang . '" AND tgl_daftar = "' . $tgl_daftar . '" AND kode_poli = "' . $kode_poli . '"')->result()) + 1;
        $kode_user    = $poli->inisial_room . sprintf("%05d", $number);
    } else {
        $number       = 0;
        $kode_user    = $poli->inisial_room . "00001";
    }
    return $kode_user;
}

function _kodeTrx($kode_poli, $kode_cabang)
{
    $CI           = &get_instance();

    $now          = date('Y-m-d');

    $poli         = $CI->db->get_where('m_poli', ['kode_poli' => $kode_poli])->row();

    $awal         = strtoupper(substr($poli->keterangan, 0, 2));

    $lastNumber   = $CI->db->query('SELECT * FROM pendaftaran WHERE kode_cabang = "' . $kode_cabang . '" AND tgl_daftar = "' . $now . '" AND kode_poli = "' . $kode_poli . '" ORDER BY id DESC LIMIT 1')->row();
    $number       = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM pendaftaran WHERE kode_cabang = "' . $kode_cabang . '" AND tgl_daftar = "' . $now . '" AND kode_poli = "' . $kode_poli . '"')->result()) + 1;
        $kode_user    = $CI->session->userdata('init_cabang') . 'P' . $awal . '-' . date('Ymd') . sprintf("%05d", $number);
    } else {
        $number       = 0;
        $kode_user    = $CI->session->userdata('init_cabang') . 'P' . $awal . '-' . date('Ymd') . "00001";
    }
    return $kode_user;
}

function _invoicePO($cabang)
{
    $CI           = &get_instance();

    $now          = date('Y-m-d');

    $lastNumber   = $CI->db->query('SELECT * FROM barang_po_in_header WHERE tgl_po = "' . $now . '" AND kode_cabang = "' . $cabang . '" ORDER BY id DESC LIMIT 1')->row();
    $number       = 1;
    if ($lastNumber) {
        $number   = $CI->db->query('SELECT * FROM barang_po_in_header WHERE tgl_po = "' . $now . '" AND kode_cabang = "' . $cabang . '"')->num_rows() + 1;
        $invoice  = $CI->session->userdata('init_cabang') . 'TPO-' . date('Ymd') . sprintf("%05d", $number);
    } else {
        $number   = 0;
        $invoice  = $CI->session->userdata('init_cabang') . 'TPO-' . date('Ymd') . "00001";
    }
    return $invoice;
}

function _invoice($cabang)
{
    $CI           = &get_instance();

    $now          = date('Y-m-d');

    $lastNumber   = $CI->db->query('SELECT * FROM barang_in_header WHERE tgl_beli = "' . $now . '" AND kode_cabang = "' . $cabang . '" ORDER BY id DESC LIMIT 1')->row();
    $number       = 1;
    if ($lastNumber) {
        $number   = $CI->db->query('SELECT * FROM barang_in_header WHERE tgl_beli = "' . $now . '" AND kode_cabang = "' . $cabang . '"')->num_rows() + 1;
        $invoice  = $CI->session->userdata('init_cabang') . 'TPB-' . date('Ymd') . sprintf("%05d", $number);
    } else {
        $number   = 0;
        $invoice  = $CI->session->userdata('init_cabang') . 'TPB-' . date('Ymd') . "00001";
    }
    return $invoice;
}

function _invoiceMutasiKas($cabang)
{
    $CI           = &get_instance();

    $now          = date('Y-m-d');

    $lastNumber   = $CI->db->query('SELECT * FROM mutasi_kas WHERE tgl_mutasi = "' . $now . '" AND kode_cabang = "' . $cabang . '" ORDER BY id DESC LIMIT 1')->row();
    $number       = 1;
    if ($lastNumber) {
        $number   = $CI->db->query('SELECT * FROM mutasi_kas WHERE tgl_mutasi = "' . $now . '" AND kode_cabang = "' . $cabang . '"')->num_rows() + 1;
        $invoice  = $CI->session->userdata('init_cabang') . 'MKB-' . date('Ymd') . sprintf("%05d", $number);
    } else {
        $number   = 0;
        $invoice  = $CI->session->userdata('init_cabang') . 'MKB-' . date('Ymd') . "00001";
    }
    return $invoice;
}

function _noPiutang($cabang)
{
    $CI           = &get_instance();

    $now          = date('Y-m-d');

    $lastNumber   = $CI->db->query('SELECT * FROM piutang WHERE tanggal = "' . $now . '" AND kode_cabang = "' . $cabang . '" ORDER BY id DESC LIMIT 1')->row();
    $number       = 1;
    if ($lastNumber) {
        $number   = $CI->db->query('SELECT * FROM piutang WHERE tanggal = "' . $now . '" AND kode_cabang = "' . $cabang . '"')->num_rows() + 1;
        $invoice  = $CI->session->userdata('init_cabang') . 'PUT-' . date('Ymd') . sprintf("%05d", $number);
    } else {
        $number   = 0;
        $invoice  = $CI->session->userdata('init_cabang') . 'PUT-' . date('Ymd') . "00001";
    }
    return $invoice;
}

function _surat_jalan($cabang)
{
    $CI           = &get_instance();

    $now          = date('Y-m-d');

    $lastNumber   = $CI->db->query('SELECT * FROM barang_in_header WHERE tgl_beli = "' . $now . '" AND kode_cabang = "' . $cabang . '" ORDER BY id DESC LIMIT 1')->row();
    $number       = 1;
    if ($lastNumber) {
        $number   = $CI->db->query('SELECT * FROM barang_in_header WHERE tgl_beli = "' . $now . '" AND kode_cabang = "' . $cabang . '"')->num_rows() + 1;
        $invoice  = 'NSJ~' . $CI->session->userdata('init_cabang') . date('dmY') . sprintf("%05d", $number);
    } else {
        $number   = 0;
        $invoice  = 'NSJ~' . $CI->session->userdata('init_cabang') . date('dmY') . "00001";
    }
    return $invoice;
}

function _no_faktur($cabang)
{
    $CI           = &get_instance();

    $now          = date('Y-m-d');

    $lastNumber   = $CI->db->query('SELECT * FROM barang_in_header WHERE tgl_beli = "' . $now . '" AND kode_cabang = "' . $cabang . '" ORDER BY id DESC LIMIT 1')->row();
    $number       = 1;
    if ($lastNumber) {
        $number   = $CI->db->query('SELECT * FROM barang_in_header WHERE tgl_beli = "' . $now . '" AND kode_cabang = "' . $cabang . '"')->num_rows() + 1;
        $invoice  = 'NSF~' . $CI->session->userdata('init_cabang') . date('dmY') . sprintf("%05d", $number);
    } else {
        $number   = 0;
        $invoice  = 'NSF~' . $CI->session->userdata('init_cabang') . date('dmY') . "00001";
    }
    return $invoice;
}

function _invoice_retur($cabang)
{
    $CI           = &get_instance();

    $now          = date('Y-m-d');

    $lastNumber   = $CI->db->query('SELECT * FROM barang_in_retur_header WHERE tgl_retur = "' . $now . '" AND kode_cabang = "' . $cabang . '" ORDER BY id DESC LIMIT 1')->row();
    $number       = 1;
    if ($lastNumber) {
        $number   = $CI->db->query('SELECT * FROM barang_in_retur_header WHERE tgl_retur = "' . $now . '" AND kode_cabang = "' . $cabang . '"')->num_rows() + 1;
        $invoice  = $CI->session->userdata('init_cabang') . 'TRB-' . date('Ymd') . sprintf("%05d", $number);
    } else {
        $number   = 0;
        $invoice  = $CI->session->userdata('init_cabang') . 'TRB-' . date('Ymd') . "00001";
    }
    return $invoice;
}

function konversi_show_satuan($s_akhir, $kode_barang)
{
    $CI = &get_instance();
    $kode_cabang = $CI->session->userdata("cabang");
    $barang = $CI->M_global->getData('barang', ['kode_barang' => $kode_barang]);

    $satuan1 = $CI->M_global->getData('m_satuan', ['kode_satuan' => $barang->kode_satuan]);
    $satuan2 = $CI->M_global->getData('m_satuan', ['kode_satuan' => $barang->kode_satuan2]);
    $satuan3 = $CI->M_global->getData('m_satuan', ['kode_satuan' => $barang->kode_satuan3]);

    $sat = '';

    if ($satuan3) {
        $stok3 = floor($s_akhir / $barang->qty_satuan3);
        $sisa3 = $s_akhir % $barang->qty_satuan3;

        if ($stok3 > 0) {
            $sat .= number_format($stok3) . ' ' . $satuan3->keterangan;

            if ($satuan2) {
                $stok2 = floor($sisa3 / $barang->qty_satuan2);
                $sisa2 = $sisa3 % $barang->qty_satuan2;

                if ($stok2 > 0) {
                    $sat .= '<br>' . number_format($stok2) . ' ' . $satuan2->keterangan;
                }

                if ($sisa2 > 0 || $stok2 === 0) {
                    $sat .= '<br>' . number_format($sisa2) . ' ' . $satuan1->keterangan;
                }
            } else {
                if ($sisa3 > 0) {
                    $sat .= '<br>' . number_format($sisa3) . ' ' . $satuan1->keterangan;
                }
            }
        } else {
            if ($sisa3 > 0) {
                $sat .= number_format($sisa3) . ' ' . $satuan1->keterangan;
            }
        }
    } elseif ($satuan2) {
        $stok2 = floor($s_akhir / $barang->qty_satuan2);
        $sisa2 = $s_akhir % $barang->qty_satuan2;

        if ($stok2 > 0) {
            $sat .= number_format($stok2) . ' ' . $satuan2->keterangan;

            if ($sisa2 > 0) {
                $sat .= '<br>' . number_format($sisa2) . ' ' . $satuan1->keterangan;
            }
        } else {
            $sat .= number_format($sisa2) . ' ' . $satuan1->keterangan;
        }
    } else {
        $sat = number_format($s_akhir) . ' ' . $satuan1->keterangan;
    }

    return $sat;
}


function hitungStokBrgIn($detail, $kode_gudang, $invoice)
{
    $CI   = &get_instance();

    $date = date('Y-m-d');
    $time = date('H:i:s');
    $user = $CI->session->userdata('kode_user');

    $kode_cabang = $CI->session->userdata("cabang");

    foreach ($detail as $d) {
        $cek = $CI->M_global->jumDataRow('barang_stok', ['kode_gudang' => $kode_gudang, 'kode_barang' => $d->kode_barang, 'kode_cabang' => $kode_cabang]);

        if ($cek < 1) {
            $isi_stok = [
                'kode_cabang'   => $kode_cabang,
                'kode_barang'   => $d->kode_barang,
                'kode_gudang'   => $kode_gudang,
                'masuk'         => $d->qty_konversi,
                'akhir'         => $d->qty_konversi,
                'last_tgl_trx'  => $date,
                'last_jam_trx'  => $time,
                'last_no_trx'   => $invoice,
                'last_user'     => $user,
            ];

            $CI->M_global->insertData('barang_stok', $isi_stok);
        } else {
            $CI->db->query("UPDATE barang_stok SET 
            masuk           = masuk + $d->qty_konversi, 
            akhir           = akhir + $d->qty_konversi, 
            last_tgl_trx    = '$date', 
            last_jam_trx    = '$time',
            last_no_trx     = '$invoice',
            last_user       = '$user' 
            WHERE kode_barang = '$d->kode_barang' AND kode_gudang = '$kode_gudang' AND kode_cabang = '$kode_cabang'");
        }
    }
}

function hitungStokBrgOut($detail, $kode_gudang, $invoice)
{
    $CI   = &get_instance();

    $date = date('Y-m-d');
    $time = date('H:i:s');
    $user = $CI->session->userdata('kode_user');

    $kode_cabang = $CI->session->userdata("cabang");

    foreach ($detail as $d) {
        $cek = $CI->M_global->jumDataRow('barang_stok', ['kode_gudang' => $kode_gudang, 'kode_barang' => $d->kode_barang, 'kode_cabang' => $kode_cabang]);

        if ($cek < 1) {
            $isi_stok = [
                'kode_cabang'   => $d->kode_cabang,
                'kode_barang'   => $d->kode_barang,
                'kode_gudang'   => $kode_gudang,
                'masuk'         => 0 - $d->qty_konversi,
                'akhir'         => 0 - $d->qty_konversi,
                'last_tgl_trx'  => $date,
                'last_jam_trx'  => $time,
                'last_no_trx'   => $invoice,
                'last_user'     => $user,
            ];

            $CI->M_global->insertData('barang_stock', $isi_stok);
        } else {
            $CI->db->query("UPDATE barang_stok SET 
            masuk = masuk - $d->qty_konversi, 
            akhir = akhir - $d->qty_konversi, 
            last_tgl_trx = '$date', 
            last_jam_trx = '$time',
            last_no_trx = '$invoice',
            last_user = '$user' 
            WHERE kode_barang = '$d->kode_barang' AND kode_gudang = '$kode_gudang' AND kode_cabang = '$kode_cabang'");
        }
    }
}

function hitungStokBrgRtIn($detail, $kode_gudang, $invoice)
{
    $CI   = &get_instance();

    $kode_cabang = $CI->session->userdata('cabang');

    $date = date('Y-m-d');
    $time = date('H:i:s');
    $user = $CI->session->userdata('kode_user');

    foreach ($detail as $d) {
        $cek = $CI->M_global->jumDataRow('barang_stok', ['kode_gudang' => $kode_gudang, 'kode_cabang' => $kode_cabang, 'kode_barang' => $d->kode_barang]);

        if ($cek < 1) {
            $isi_stok = [
                'kode_barang'   => $d->kode_barang,
                'kode_cabang'   => $kode_cabang,
                'kode_gudang'   => $kode_gudang,
                'keluar'        => $d->qty_konversi,
                'akhir'         => 0 - $d->qty_konversi,
                'last_tgl_trx'  => $date,
                'last_jam_trx'  => $time,
                'last_no_trx'   => $invoice,
                'last_user'     => $user,
            ];

            $CI->M_global->insertData('barang_stok', $isi_stok);
        } else {
            $CI->db->query("UPDATE barang_stok SET 
            keluar          = keluar + $d->qty_konversi, 
            akhir           = akhir - $d->qty_konversi, 
            last_tgl_trx    = '$date', 
            last_jam_trx    = '$time',
            last_no_trx     = '$invoice',
            last_user       = '$user' 
            WHERE kode_barang = '$d->kode_barang' AND kode_gudang = '$kode_gudang' AND kode_cabang = '$kode_cabang'");
        }
    }
}

function hitungStokBrgRtOut($detail, $kode_gudang, $invoice)
{
    $CI   = &get_instance();

    $date = date('Y-m-d');
    $time = date('H:i:s');
    $user = $CI->session->userdata('kode_user');
    $kode_cabang = $CI->session->userdata('cabang');

    foreach ($detail as $d) {
        $cek = $CI->M_global->jumDataRow('barang_stok', ['kode_gudang' => $kode_gudang, 'kode_cabang' => $kode_cabang, 'kode_barang' => $d->kode_barang]);

        if ($cek < 1) {
            $isi_stok = [
                'kode_barang'   => $d->kode_barang,
                'kode_cabang'   => $kode_cabang,
                'kode_gudang'   => $kode_gudang,
                'keluar'        => 0 - $d->qty_konversi,
                'akhir'         => $d->qty_konversi,
                'last_tgl_trx'  => $date,
                'last_jam_trx'  => $time,
                'last_no_trx'   => $invoice,
                'last_user'     => $user,
            ];

            $CI->M_global->insertData('barang_stock', $isi_stok);
        } else {
            $CI->db->query("UPDATE barang_stok SET 
            keluar = keluar - $d->qty_konversi, 
            akhir = akhir + $d->qty_konversi, 
            last_tgl_trx = '$date', 
            last_jam_trx = '$time',
            last_no_trx = '$invoice',
            last_user = '$user' 
            WHERE kode_barang = '$d->kode_barang' AND kode_gudang = '$kode_gudang' AND kode_cabang = '$kode_cabang'");
        }
    }
}

function _invoiceJual($cabang)
{
    $CI           = &get_instance();

    $now          = date('Y-m-d');

    $lastNumber   = $CI->db->query('SELECT * FROM barang_out_header WHERE tgl_jual = "' . $now . '" AND kode_cabang = "' . $cabang . '" ORDER BY id DESC LIMIT 1')->row();
    $number       = 1;
    if ($lastNumber) {
        $number   = $CI->db->query('SELECT * FROM barang_out_header WHERE tgl_jual = "' . $now . '" AND kode_cabang = "' . $cabang . '"')->num_rows() + 1;
        $invoice  = $CI->session->userdata('init_cabang') . 'TJB-' . date('Ymd') . sprintf("%05d", $number);
    } else {
        $number   = 0;
        $invoice  = $CI->session->userdata('init_cabang') . 'TJB-' . date('Ymd') . "00001";
    }
    return $invoice;
}

function hitungStokJualOut($detail, $kode_gudang, $invoice)
{
    $CI   = &get_instance();

    $kode_cabang = $CI->session->userdata('cabang');

    $date = date('Y-m-d');
    $time = date('H:i:s');
    $user = $CI->session->userdata('kode_user');

    foreach ($detail as $d) {
        $cek = $CI->M_global->jumDataRow('barang_stok', ['kode_gudang' => $kode_gudang, 'kode_barang' => $d->kode_barang, 'kode_cabang' => $kode_cabang]);

        if ($cek < 1) {
            $isi_stok = [
                'kode_barang'   => $d->kode_barang,
                'kode_cabang'   => $kode_cabang,
                'kode_gudang'   => $kode_gudang,
                'keluar'        => 0 - $d->qty_konversi,
                'akhir'         => $d->qty_konversi,
                'last_tgl_trx'  => $date,
                'last_jam_trx'  => $time,
                'last_no_trx'   => $invoice,
                'last_user'     => $user,
            ];

            $CI->M_global->insertData('barang_stock', $isi_stok);
        } else {
            $CI->db->query("UPDATE barang_stok SET 
            keluar = keluar - $d->qty_konversi, 
            akhir = akhir + $d->qty_konversi, 
            last_tgl_trx = '$date', 
            last_jam_trx = '$time',
            last_no_trx = '$invoice',
            last_user = '$user' 
            WHERE kode_barang = '$d->kode_barang' AND kode_gudang = '$kode_gudang' AND kode_cabang = '$kode_cabang'");
        }
    }
}

function hitungStokJualIn($detail, $kode_gudang, $invoice)
{
    $CI   = &get_instance();

    $kode_cabang = $CI->session->userdata('cabang');

    $date = date('Y-m-d');
    $time = date('H:i:s');
    $user = $CI->session->userdata('kode_user');

    foreach ($detail as $d) {
        $cek = $CI->M_global->jumDataRow('barang_stok', ['kode_gudang' => $kode_gudang, 'kode_cabang' => $kode_cabang, 'kode_barang' => $d->kode_barang]);

        if ($cek < 1) {
            $isi_stok = [
                'kode_barang'   => $d->kode_barang,
                'kode_cabang'   => $kode_cabang,
                'kode_gudang'   => $kode_gudang,
                'keluar'        => $d->qty_konversi,
                'akhir'         => 0 - $d->qty_konversi,
                'last_tgl_trx'  => $date,
                'last_jam_trx'  => $time,
                'last_no_trx'   => $invoice,
                'last_user'     => $user,
            ];

            $CI->M_global->insertData('barang_stock', $isi_stok);
        } else {
            $CI->db->query("UPDATE barang_stok SET 
            keluar = keluar + $d->qty_konversi, 
            akhir = akhir - $d->qty_konversi, 
            last_tgl_trx = '$date', 
            last_jam_trx = '$time',
            last_no_trx = '$invoice',
            last_user = '$user' 
            WHERE kode_barang = '$d->kode_barang' AND kode_gudang = '$kode_gudang' AND kode_cabang = '$kode_cabang'");
        }
    }
}

function generatorToken($jum)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $jum; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }
    return $randomString;
}

function tokenKasir($jum)
{
    $CI             = &get_instance();
    $generatorToken = generatorToken($jum);
    $cek_token      = $CI->db->query("SELECT * FROM pembayaran WHERE token_pembayaran = '$generatorToken'")->num_rows();

    if ($cek_token > 0) {
        generatorToken($jum);
    } else {
        return $generatorToken;
    }
}

function _invoiceKasir($cabang)
{
    $CI           = &get_instance();

    $init_cabang = $CI->session->userdata('init_cabang');

    $now          = date('Y-m-d');

    $lastNumber   = $CI->db->query('SELECT * FROM pembayaran WHERE kode_cabang = "' . $cabang . '" AND tgl_pembayaran = "' . $now . '" ORDER BY id DESC LIMIT 1')->row();
    $number       = 1;
    if ($lastNumber) {
        $number   = $CI->db->query('SELECT * FROM pembayaran WHERE kode_cabang = "' . $cabang . '" AND tgl_pembayaran = "' . $now . '"')->num_rows() + 1;
        $invoice  = $init_cabang . 'KWI-' . date('Ymd') . sprintf("%05d", $number);
    } else {
        $number   = 0;
        $invoice  = $init_cabang . 'KWI-' . date('Ymd') . "00001";
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
        $invoice  = 'UM-' . date('Ymd') . sprintf("%05d", $number);
    } else {
        $number   = 0;
        $invoice  = 'UM-' . date('Ymd') . "00001";
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

function _invoiceRetur($cabang)
{
    $CI           = &get_instance();

    $init_cabang = $CI->session->userdata('init_cabang');

    $now          = date('Y-m-d');

    $lastNumber   = $CI->db->query('SELECT * FROM barang_out_retur_header WHERE kode_cabang = "' . $cabang . '" AND tgl_retur = "' . $now . '" ORDER BY id DESC LIMIT 1')->row();
    $number       = 1;
    if ($lastNumber) {
        $number   = $CI->db->query('SELECT * FROM barang_out_retur_header WHERE kode_cabang = "' . $cabang . '" AND tgl_retur = "' . $now . '"')->num_rows() + 1;
        $invoice  = $init_cabang . 'TRJ-' . date('Ymd') . sprintf("%05d", $number);
    } else {
        $number   = 0;
        $invoice  = $init_cabang . 'TRJ-' . date('Ymd') . "00001";
    }
    return $invoice;
}

function hitungStokReturJualIn($detail, $kode_gudang, $invoice)
{
    $CI   = &get_instance();

    $kode_cabang = $CI->session->userdata('cabang');

    $date = date('Y-m-d');
    $time = date('H:i:s');
    $user = $CI->session->userdata('kode_user');

    foreach ($detail as $d) {
        $cek = $CI->M_global->jumDataRow('barang_stok', ['kode_gudang' => $kode_gudang, 'kode_cabang' => $kode_cabang, 'kode_barang' => $d->kode_barang]);

        if ($cek < 1) {
            $isi_stok = [
                'kode_barang'   => $d->kode_barang,
                'kode_cabang'   => $kode_cabang,
                'kode_gudang'   => $kode_gudang,
                'keluar'        => $d->qty_konversi,
                'masuk'         => 0 - $d->qty_konversi,
                'akhir'         => 0 - $d->qty_konversi,
                'last_tgl_trx'  => $date,
                'last_jam_trx'  => $time,
                'last_no_trx'   => $invoice,
                'last_user'     => $user,
            ];

            $CI->M_global->insertData('barang_stok', $isi_stok);
        } else {
            $CI->db->query("UPDATE barang_stok SET 
            masuk           = masuk - $d->qty_konversi, 
            akhir           = akhir - $d->qty_konversi, 
            last_tgl_trx    = '$date', 
            last_jam_trx    = '$time',
            last_no_trx     = '$invoice',
            last_user       = '$user' 
            WHERE kode_barang = '$d->kode_barang' AND kode_gudang = '$kode_gudang' AND kode_cabang = '$kode_cabang'");
        }
    }
}

function hitungStokReturJualOut($detail, $kode_gudang, $invoice)
{
    $CI   = &get_instance();

    $date = date('Y-m-d');
    $time = date('H:i:s');
    $user = $CI->session->userdata('kode_user');
    $kode_cabang = $CI->session->userdata('cabang');

    foreach ($detail as $d) {
        $cek = $CI->M_global->jumDataRow('barang_stok', ['kode_gudang' => $kode_gudang, 'kode_cabang' => $kode_cabang, 'kode_barang' => $d->kode_barang]);

        if ($cek < 1) {
            $isi_stok = [
                'kode_barang'   => $d->kode_barang,
                'kode_cabang'   => $kode_cabang,
                'kode_gudang'   => $kode_gudang,
                'masuk'         => $d->qty_konversi,
                'akhir'         => $d->qty_konversi,
                'last_tgl_trx'  => $date,
                'last_jam_trx'  => $time,
                'last_no_trx'   => $invoice,
                'last_user'     => $user,
            ];

            $CI->M_global->insertData('barang_stock', $isi_stok);
        } else {
            $CI->db->query("UPDATE barang_stok SET 
            masuk = masuk + $d->qty_konversi, 
            akhir = akhir + $d->qty_konversi, 
            last_tgl_trx = '$date', 
            last_jam_trx = '$time',
            last_no_trx = '$invoice',
            last_user = '$user' 
            WHERE kode_barang = '$d->kode_barang' AND kode_gudang = '$kode_gudang' AND kode_cabang = '$kode_cabang'");
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
        $kode_jenis   = 'JO' . sprintf("%08d", $number);
    } else {
        $number       = 0;
        $kode_jenis   = 'JO' . "00000001";
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

    echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($kode_barang, $generator::TYPE_CODE_128)) . '"><br>' . $kode_barang;
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

function _invoicePenyesuaianStok($cabang)
{
    $CI           = &get_instance();

    $now          = date('Y-m-d');

    $lastNumber   = $CI->db->query('SELECT * FROM penyesuaian_header WHERE kode_cabang = "' . $cabang . '" AND tgl_penyesuaian = "' . $now . '" ORDER BY id DESC LIMIT 1')->row();
    $number       = 1;
    if ($lastNumber) {
        $number   = $CI->db->query('SELECT * FROM penyesuaian_header WHERE kode_cabang = "' . $cabang . '" AND tgl_penyesuaian = "' . $now . '"')->num_rows() + 1;
        $invoice  = $CI->session->userdata('init_cabang') . 'TPS-' . date('Ymd') . sprintf("%05d", $number);
    } else {
        $number   = 0;
        $invoice  = $CI->session->userdata('init_cabang') . 'TPS-' . date('Ymd') . "00001";
    }
    return $invoice;
}

function hitungStokAdjIn($detail, $kode_gudang, $invoice)
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
                'so'            => 0,
                'penyesuaian'   => $d->qty_konversi,
                'akhir'         => $d->qty_konversi,
                'last_tgl_trx'  => $date,
                'last_jam_trx'  => $time,
                'last_no_trx'   => $invoice,
                'last_user'     => $user,
            ];

            $CI->M_global->insertData('barang_stok', $isi_stok);
        } else {
            $CI->db->query("UPDATE barang_stok SET 
            masuk = masuk + $d->qty_konversi, 
            akhir = akhir + $d->qty_konversi, 
            penyesuaian = $d->qty,
            last_tgl_trx = '$date', 
            last_jam_trx = '$time',
            last_no_trx = '$invoice',
            last_user = '$user' 
            WHERE kode_barang = '$d->kode_barang' AND kode_gudang = '$kode_gudang'");
        }
    }
}

function hitungStokAdjOut($detail, $kode_gudang, $invoice)
{
    $CI   = &get_instance();

    $kode_cabang = $CI->session->userdata('cabang');

    $date = date('Y-m-d');
    $time = date('H:i:s');
    $user = $CI->session->userdata('kode_user');

    foreach ($detail as $d) {
        $cek = $CI->M_global->jumDataRow('barang_stok', ['kode_gudang' => $kode_gudang, 'kode_cabang' => $kode_cabang, 'kode_barang' => $d->kode_barang]);

        if ($cek < 1) {
            $isi_stok = [
                'kode_barang'   => $d->kode_barang,
                'kode_gudang'   => $kode_gudang,
                'kode_cabang'   => $kode_cabang,
                'masuk'         => 0 - $d->qty_konversi,
                'akhir'         => 0 - $d->qty_konversi,
                'so'            => 0,
                'penyesuaian'   => 0 - $d->qty_konversi,
                'last_tgl_trx'  => $date,
                'last_jam_trx'  => $time,
                'last_no_trx'   => $invoice,
                'last_user'     => $user,
            ];

            $CI->M_global->insertData('barang_stock', $isi_stok);
        } else {
            $CI->db->query("UPDATE barang_stok SET 
            masuk = masuk - $d->qty_konversi, 
            akhir = akhir - $d->qty_konversi,
            penyesuaian = penyesuaian - $d->qty_konversi,
            last_tgl_trx = '$date', 
            last_jam_trx = '$time',
            last_no_trx = '$invoice',
            last_user = '$user' 
            WHERE kode_barang = '$d->kode_barang' AND kode_gudang = '$kode_gudang' AND kode_cabang = '$kode_cabang'");
        }
    }
}

function _lock_so()
{
    $CI       = &get_instance();

    $date     = date('Y-m-d');
    $clock    = date('H:i:s');

    $last_so  = $CI->db->query("SELECT * FROM jadwal_so ORDER BY id DESC LIMIT 1")->row();

    if ($last_so) {
        $cek_so   = $CI->db->query("SELECT * FROM jadwal_so WHERE tgl_sampai >= '$date' AND jam_sampai >= '$clock' AND id = '$last_so->id'")->row();

        if ($cek_so) {
            $lock = '<div class="alert alert-danger text-center" role="alert">
                    Saat ini sedang dalam proses Stock Opname, Transaksi sedang tidak bisa dilakukan!
                </div>';
        } else {
            $lock = '';
        }
    } else {
        $lock = '';
    }

    return $lock;
}

function _lock_button()
{
    $CI       = &get_instance();

    $date     = date('Y-m-d');
    $clock    = date('H:i:s');

    $last_so  = $CI->db->query("SELECT * FROM jadwal_so ORDER BY id DESC LIMIT 1")->row();

    if ($last_so) {
        $cek_so   = $CI->db->query("SELECT * FROM jadwal_so WHERE tgl_sampai >= '$date' AND jam_sampai >= '$clock' AND id = '$last_so->id'")->row();

        if ($cek_so) {
            $lock = 'disabled';
        } else {
            $lock = '';
        }
    } else {
        $lock = '';
    }

    return $lock;
}

function cek_so()
{
    $CI       = &get_instance();

    $date     = date('Y-m-d');
    $clock    = date('H:i:s');

    $last_so  = $CI->db->query("SELECT * FROM jadwal_so WHERE id = 1")->row();

    if ($last_so) {
        $cek_so   = $CI->db->query("SELECT * FROM jadwal_so WHERE (tgl_sampai >= '$date' AND jam_sampai >= '$clock') AND id = '$last_so->id'")->row();

        if ($cek_so) {
            $lock = 'disabled';
        } else {
            $CI->db->query("UPDATE jadwal_so SET status = 0 WHERE id = 1");

            $lock = '';
        }
    } else {
        $lock = '';
    }

    return $lock;
}

function _kodeKas_bank()
{
    $CI         = &get_instance();

    $inisial    = "KB";
    $lastNumber = $CI->db->query('SELECT * FROM kas_bank ORDER BY kode_kas_bank DESC LIMIT 1')->row();
    $number     = 1;
    if ($lastNumber) {
        $number       = count($CI->db->query('SELECT * FROM kas_bank')->result()) + 1;
        $kode_user    = $inisial . sprintf("%08d", $number);
    } else {
        $number       = 0;
        $kode_user    = $inisial . "00000001";
    }
    return $kode_user;
}

function _drop_db()
{
    $CI         = &get_instance();

    $cek        = $CI->db->query("SELECT table_name AS my_table FROM information_schema.tables
    WHERE table_schema = '" . $CI->db->database . "'");

    if ($cek->num_rows() > 0) {
        foreach ($cek->result() as $c) {
            // if ($c->my_table == 'user' || $c->my_table == 'member' || $c->my_table == 'cabang' || $c->my_table == 'kecamatan' || $c->my_table == 'kabupaten' || $c->my_table == 'backup_db' || $c->my_table == 'cabang_user' || $c->my_table == 'm_agama' || $c->my_table == 'm_gudang' || $c->my_table == 'm_pekerjaan' || $c->my_table == 'm_pendidikan' || $c->my_table == 'm_provinsi' || $c->my_table == 'm_role'  || $c->my_table == 'member_token' || $c->my_table == 'sub_menu' || $c->my_table == 'sub_menu2' || $c->my_table == 'user_token' || $c->my_table == 'web_setting' || $c->my_table == 'web_version' || $c->my_table == 'm_menu') {
            //     $query = TRUE;
            // } else {
            //     $query = $CI->db->query("DROP TABLE $c->my_table");
            // }
            $query = $CI->db->query("DROP TABLE $c->my_table");
        }
    } else {
        $query = TRUE;
    }

    return $query;
}

function _kodeKategoriTarif()
{
    $CI           = &get_instance();

    $lastNumber   = $CI->db->query('SELECT * FROM kategori_tarif ORDER BY id DESC LIMIT 1')->row();
    $number       = 1;
    if ($lastNumber) {
        $number   = $CI->db->query('SELECT * FROM kategori_tarif')->num_rows() + 1;
        $kode  = 'KATTR' . sprintf("%05d", $number);
    } else {
        $number   = 0;
        $kode  = 'KATTR' . "00001";
    }
    return $kode;
}
