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

function _invoice($cabang)
{
    $CI           = &get_instance();

    $now          = date('Y-m-d');

    $lastNumber   = $CI->db->query('SELECT * FROM barang_in_header WHERE tgl_beli = "' . $now . '" AND kode_cabang = "' . $cabang . '" ORDER BY id DESC LIMIT 1')->row();
    $number       = 1;
    if ($lastNumber) {
        $number   = $CI->db->query('SELECT * FROM barang_in_header WHERE tgl_beli = "' . $now . '" AND kode_cabang = "' . $cabang . '"')->num_rows() + 1;
        $invoice  = 'INV~' . $cabang . date('dmY') . sprintf("%05d", $number);
    } else {
        $number   = 0;
        $invoice  = 'INV~' . $cabang . date('dmY') . "00001";
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
        $invoice  = 'NSJ~' . $cabang . date('dmY') . sprintf("%05d", $number);
    } else {
        $number   = 0;
        $invoice  = 'NSJ~' . $cabang . date('dmY') . "00001";
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
        $invoice  = 'NSF~' . $cabang . date('dmY') . sprintf("%05d", $number);
    } else {
        $number   = 0;
        $invoice  = 'NSF~' . $cabang . date('dmY') . "00001";
    }
    return $invoice;
}

function _invoice_retur($cabang)
{
    $CI           = &get_instance();

    $now          = date('Y-m-d');

    $lastNumber   = $CI->db->query('SELECT * FROM barang_in_retur_header WHERE tgl_beli = "' . $now . '" AND kode_cabang = "' . $cabang . '" ORDER BY id DESC LIMIT 1')->row();
    $number       = 1;
    if ($lastNumber) {
        $number   = $CI->db->query('SELECT * FROM barang_in_retur_header WHERE tgl_beli = "' . $now . '" AND kode_cabang = "' . $cabang . '"')->num_rows() + 1;
        $invoice  = 'REINV~' . $cabang . date('dmY') . sprintf("%05d", $number);
    } else {
        $number   = 0;
        $invoice  = 'REINV~' . $cabang . date('dmY') . "00001";
    }
    return $invoice;
}

function konversi_show_satuan($s_akhir, $kode_barang)
{
    $CI   = &get_instance();

    $kode_cabang = $CI->session->userdata("cabang");

    $satuan1 = $CI->M_global->getData('barang_satuan', ['kode_barang' => $kode_barang, 'kode_cabang' => $kode_cabang, 'ke' => 1]);
    $satuan3 = $CI->M_global->getData('barang_satuan', ['kode_barang' => $kode_barang, 'kode_cabang' => $kode_cabang, 'ke' => 3]);

    if ($satuan3) {
        $stok3 = floor($s_akhir / $satuan3->qty_satuan);
        $qty_sat_3 = $stok3 * $satuan3->qty_satuan;

        $cek_sisa3 = $s_akhir - $qty_sat_3;
        if ($stok3 > 0) {
            $satuan2 = $CI->M_global->getData('barang_satuan', ['kode_barang' => $kode_barang, 'kode_cabang' => $kode_cabang, 'ke' => 2]);
            $stok2 = floor($cek_sisa3 / $satuan2->qty_satuan);
            $qty_sat_2 = $stok2 * $satuan2->qty_satuan;

            $cek_sisa2 = $cek_sisa3 - $qty_sat_2;
            if ($stok2 > 0) {
                $sat = number_format($stok3) . ' ' . $CI->M_global->getData('m_satuan', ['kode_satuan' => $satuan3->kode_satuan])->keterangan . (($stok2 > 0) ? '<br>' . number_format($stok2) . ' ' . $CI->M_global->getData('m_satuan', ['kode_satuan' => $satuan2->kode_satuan])->keterangan : '') . (($cek_sisa2 > 0) ? '<br>' . number_format($cek_sisa2) . ' ' . $CI->M_global->getData('m_satuan', ['kode_satuan' => $satuan1->kode_satuan])->keterangan : '');
            } else {
                $sat = number_format($stok3) . ' ' . $CI->M_global->getData('m_satuan', ['kode_satuan' => $satuan3->kode_satuan])->keterangan . (($stok2 > 0) ? '<br>' . number_format($stok2) . ' ' . $CI->M_global->getData('m_satuan', ['kode_satuan' => $satuan2->kode_satuan])->keterangan : '');
            }
        } else {
            $sat = number_format($stok3) . ' ' . $CI->M_global->getData('m_satuan', ['kode_satuan' => $satuan3->kode_satuan])->keterangan;
        }
    } else {
        $satuan2 = $CI->M_global->getData('barang_satuan', ['kode_barang' => $kode_barang, 'kode_cabang' => $kode_cabang, 'ke' => 2]);

        if ($satuan2) {
            $stok2 = floor($s_akhir / $satuan2->qty_satuan);
            $qty_sat_2 = $stok2 * $satuan2->qty_satuan;

            $cek_sisa2 = $s_akhir - $qty_sat_2;
            if ($stok2 > 0) {
                $sat = number_format($stok2) . ' ' . $CI->M_global->getData('m_satuan', ['kode_satuan' => $satuan2->kode_satuan])->keterangan . (($cek_sisa2 > 0) ? '<br>' . number_format($cek_sisa2) . ' ' . $CI->M_global->getData('m_satuan', ['kode_satuan' => $satuan1->kode_satuan])->keterangan : '');
            } else {
                $sat = number_format($cek_sisa2) . ' ' . $CI->M_global->getData('m_satuan', ['kode_satuan' => $satuan1->kode_satuan])->keterangan;
            }
        } else {
            $sat = '';
        }
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
                'kode_cabang'   => $d->kode_cabang,
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
            WHERE kode_barang = '$d->kode_barang' AND kode_gudang = '$kode_gudang' AND kode_cabang = '$kode_cabang'");
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

function _invoicePenyesuaianStok()
{
    $CI           = &get_instance();

    $now          = date('Y-m-d');

    $lastNumber   = $CI->db->query('SELECT * FROM penyesuaian_header WHERE tgl_penyesuaian = "' . $now . '" ORDER BY id DESC LIMIT 1')->row();
    $number       = 1;
    if ($lastNumber) {
        $number   = $CI->db->query('SELECT * FROM penyesuaian_header WHERE tgl_penyesuaian = "' . $now . '"')->num_rows() + 1;
        $invoice  = 'PS~' . date('dmY') . sprintf("%05d", $number);
    } else {
        $number   = 0;
        $invoice  = 'PS~' . date('dmY') . "00001";
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
                'penyesuaian'   => $d->qty,
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
                'so'            => 0,
                'penyesuaian'   => 0 - $d->qty,
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
            penyesuaian = penyesuaian - $d->qty,
            last_tgl_trx = '$date', 
            last_jam_trx = '$time',
            last_no_trx = '$invoice',
            last_user = '$user' 
            WHERE kode_barang = '$d->kode_barang' AND kode_gudang = '$kode_gudang'");
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
