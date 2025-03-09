<?php
class M_select2 extends CI_Model
{
    function __construct()
    {
        parent::__construct();

        $this->db->query("SET SESSION sql_mode = REPLACE(
            REPLACE(
                REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY,', ''),
            ',ONLY_FULL_GROUP_BY', ''),
        'ONLY_FULL_GROUP_BY', '')");
    }
    // fungsi Cabang
    function getCabang($key, $email)
    {
        $now = date('Y-m-d');

        $limit = ' LIMIT 50';

        if ($email == null || $email == "" || $email == "null") {
            $sintak = $this->db->query('SELECT 0 AS id, "Pilih Cabang Dahulu" AS text FROM cabang_user LIMIT 1')->result();
        } else {
            if (!empty($key)) {
                $add_sintak = ' AND (cu.kode_cabang LIKE "%' . $key . '%" OR c.cabang LIKE "%' . $key . '%") ORDER BY c.cabang ASC';
            } else {
                $add_sintak = ' ORDER BY c.cabang ASC';
            }

            $sintak = $this->db->query('SELECT cu.kode_cabang AS id, c.cabang AS text FROM cabang_user cu JOIN cabang c USING (kode_cabang) WHERE c.aktif_sampai > "' . $now . '" AND cu.email = "' . $email . '" ' . $add_sintak . $limit)->result();
        }

        return $sintak;
    }

    function getBarangStok($key)
    {
        $limit = ' LIMIT 50';
        $kode_cabang = $this->session->userdata('cabang');
        $g_utama = $this->M_global->getData('M_gudang', ['utama' => 1])->kode_gudang;

        if (!empty($key)) {
            $add_sintak = ' AND (bs.kode_cabang LIKE "%' . $key . '%" OR b.nama LIKE "%' . $key . '%") ORDER BY b.nama ASC';
        } else {
            $add_sintak = ' ORDER BY b.nama ASC';
        }

        $sintak = $this->db->query('SELECT bs.kode_barang AS id, CONCAT(b.nama, " | stok: ", ROUND(bs.masuk - bs.keluar), " ", (SELECT keterangan FROM m_satuan WHERE kode_satuan = b.kode_satuan)) AS text FROM barang_stok bs JOIN barang b ON b.kode_barang = bs.kode_barang WHERE bs.kode_cabang = "' . $kode_cabang . '" ' . $add_sintak . $limit)->result();

        return $sintak;
    }

    function getAllCabang($key)
    {
        $limit = ' LIMIT 50';

        if (!empty($key)) {
            $add_sintak = ' WHERE (c.kode_cabang LIKE "%' . $key . '%" OR c.cabang LIKE "%' . $key . '%") ORDER BY c.cabang ASC';
        } else {
            $add_sintak = ' ORDER BY c.cabang ASC';
        }

        $sintak = $this->db->query('SELECT c.kode_cabang AS id, c.cabang AS text FROM cabang c ' . $add_sintak . $limit)->result();

        return $sintak;
    }

    function getTarifSingle($key)
    {
        $this->db->query("SET SESSION sql_mode = REPLACE(
            REPLACE(
                REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY,', ''),
            ',ONLY_FULL_GROUP_BY', ''),
        'ONLY_FULL_GROUP_BY', '')");

        $kode_cabang = $this->session->userdata('cabang');

        $limit = ' LIMIT 50';

        if (!empty($key)) {
            $add_sintak = ' AND (m.kode_tarif LIKE "%' . $key . '%" OR m.nama LIKE "%' . $key . '%") ORDER BY m.nama ASC';
        } else {
            $add_sintak = ' ORDER BY m.nama ASC';
        }

        $sintak = $this->db->query('SELECT m.kode_tarif AS id, m.nama AS text FROM m_tarif m JOIN tarif_jasa t ON m.kode_tarif = t.kode_tarif WHERE m.jenis = 1 AND t.kode_cabang = "' . $kode_cabang . '" ' . $add_sintak . $limit)->result();

        return $sintak;
    }

    function getTarifPaket($key)
    {
        $this->db->query("SET SESSION sql_mode = REPLACE(
            REPLACE(
                REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY,', ''),
            ',ONLY_FULL_GROUP_BY', ''),
        'ONLY_FULL_GROUP_BY', '')");

        $kode_cabang = $this->session->userdata('cabang');

        $limit = ' LIMIT 50';

        if (!empty($key)) {
            $add_sintak = ' AND (m.kode_tarif LIKE "%' . $key . '%" OR m.nama LIKE "%' . $key . '%") GROUP BY t.kode_tarif ORDER BY m.nama ASC';
        } else {
            $add_sintak = ' GROUP BY t.kode_tarif ORDER BY m.nama ASC';
        }

        $sintak = $this->db->query('SELECT m.kode_tarif AS id, m.nama AS text FROM m_tarif m JOIN tarif_paket t ON m.kode_tarif = t.kode_tarif WHERE t.kode_cabang = "' . $kode_cabang . '" AND m.jenis = 2 ' . $add_sintak . $limit)->result();

        return $sintak;
    }

    function getTerdaftar($key)
    {
        $kode_cabang = $this->session->userdata('cabang');
        $limit = ' LIMIT 50';

        if (!empty($key)) {
            $add_sintak = ' AND (p.no_trx LIKE "%' . $key . '%" OR m.nama LIKE "%' . $key . '%" OR pol.keterangan LIKE "%' . $key . '%" OR dok.nama LIKE "%' . $key . '%") ORDER BY p.id DESC';
        } else {
            $add_sintak = ' ORDER BY p.id DESC';
        }

        $sintak = $this->db->query('SELECT p.no_trx AS id, CONCAT(p.no_trx, " | Nama: ", m.nama, " | Tgl/Jam: ", p.tgl_daftar, "/", p.jam_daftar, " | Poli/Dokter: ", pol.keterangan, "/", dok.nama) AS text FROM pendaftaran p JOIN member m ON p.kode_member = m.kode_member JOIN m_poli pol ON pol.kode_poli = p.kode_poli JOIN dokter dok ON dok.kode_dokter = p.kode_dokter WHERE p.kode_cabang = "' . $kode_cabang . '" AND p.status_trx = 0 ' . $add_sintak . $limit)->result();

        return $sintak;
    }

    // fungsi kategori
    function getKategori($key)
    {
        $limit = ' LIMIT 50';

        if (!empty($key)) {
            $add_sintak = ' WHERE (keterangan LIKE "%' . $key . '%") ORDER BY keterangan ASC';
        } else {
            $add_sintak = ' ORDER BY keterangan ASC';
        }

        $sintak = $this->db->query('SELECT kode_kategori AS id, keterangan AS text FROM m_kategori ' . $add_sintak . $limit)->result();

        return $sintak;
    }

    // fungsi klasifikasi
    function getKlasifikasiAkun($key)
    {
        $limit = ' LIMIT 50';

        if (!empty($key)) {
            $add_sintak = ' WHERE (klasifikasi LIKE "%' . $key . '%") ORDER BY klasifikasi ASC';
        } else {
            $add_sintak = ' ORDER BY klasifikasi ASC';
        }

        $sintak = $this->db->query('SELECT kode_klasifikasi AS id, klasifikasi AS text FROM klasifikasi_akun ' . $add_sintak . $limit)->result();

        return $sintak;
    }

    // fungsi pajak
    function getPajak($key)
    {
        $limit = ' LIMIT 50';

        if (!empty($key)) {
            $add_sintak = ' WHERE (kode_pajak LIKE "%' . $key . '%" OR pajak LIKE "%' . $key . '%") ORDER BY nama ASC';
        } else {
            $add_sintak = ' ORDER BY nama ASC';
        }

        $sintak = $this->db->query('SELECT kode_pajak AS id, nama AS text FROM m_pajak ' . $add_sintak . $limit)->result();

        return $sintak;
    }

    // fungsi prefix
    function getPrefix($key)
    {
        $limit = ' LIMIT 50';

        if (!empty($key)) {
            $add_sintak = ' WHERE nama LIKE "%' . $key . '%" ORDER BY nama ASC';
        } else {
            $add_sintak = ' ORDER BY nama ASC';
        }

        $sintak = $this->db->query('SELECT kode_prefix AS id, nama AS text FROM m_prefix ' . $add_sintak . $limit)->result();

        return $sintak;
    }

    // fungsi provinsi
    function getProvinsi($key)
    {
        $limit = ' LIMIT 50';

        if (!empty($key)) {
            $add_sintak = ' WHERE (kode_provinsi LIKE "%' . $key . '%" OR provinsi LIKE "%' . $key . '%") ORDER BY provinsi ASC';
        } else {
            $add_sintak = ' ORDER BY provinsi ASC';
        }

        $sintak = $this->db->query('SELECT kode_provinsi AS id, provinsi AS text FROM m_provinsi ' . $add_sintak . $limit)->result();

        return $sintak;
    }

    // fungsi Akun Select
    function getAkunSel($key, $kode_klasifikasi)
    {
        $limit = ' LIMIT 50';

        if ($kode_klasifikasi == null || $kode_klasifikasi == "" || $kode_klasifikasi == "null") {
            $sintak = $this->db->query('SELECT 0 AS id, "Pilih Klasifikasi" AS text FROM m_akun LIMIT 1')->result();
        } else {
            if (!empty($key)) {
                $add_sintak = ' AND (kode_akun LIKE "%' . $key . '%" OR nama_akun LIKE "%' . $key . '%") ORDER BY nama_akun ASC';
            } else {
                $add_sintak = ' ORDER BY nama_akun ASC';
            }

            $sintak = $this->db->query('SELECT kode_akun AS id, nama_akun AS text FROM m_akun WHERE kode_klasifikasi = "' . $kode_klasifikasi . '" ' . $add_sintak . $limit)->result();
        }

        return $sintak;
    }

    // fungsi kabupaten
    function getKabupaten($key, $kode_provinsi)
    {
        $limit = ' LIMIT 50';

        if ($kode_provinsi == null || $kode_provinsi == "" || $kode_provinsi == "null") {
            $sintak = $this->db->query('SELECT 0 AS id, "Pilih Provinsi Dahulu" AS text FROM kabupaten LIMIT 1')->result();
        } else {
            if (!empty($key)) {
                $add_sintak = ' AND (kode_kabupaten LIKE "%' . $key . '%" OR kabupaten LIKE "%' . $key . '%") ORDER BY kabupaten ASC';
            } else {
                $add_sintak = ' ORDER BY kabupaten ASC';
            }

            $sintak = $this->db->query('SELECT kode_kabupaten AS id, kabupaten AS text FROM kabupaten WHERE kode_provinsi = "' . $kode_provinsi . '" ' . $add_sintak . $limit)->result();
        }

        return $sintak;
    }

    // fungsi kecamatan
    function getKecamatan($key, $kode_kabupaten)
    {
        $limit = ' LIMIT 50';

        if ($kode_kabupaten == null || $kode_kabupaten == "" || $kode_kabupaten == "null") {
            $sintak = $this->db->query('SELECT 0 AS id, "Pilih Kabupaten Dahulu" AS text FROM kecamatan LIMIT 1')->result();
        } else {
            if (!empty($key)) {
                $add_sintak = ' AND (kode_kecamatan LIKE "%' . $key . '%" OR kecamatan LIKE "%' . $key . '%") ORDER BY kecamatan ASC';
            } else {
                $add_sintak = ' ORDER BY kecamatan ASC';
            }

            $sintak = $this->db->query('SELECT kode_kecamatan AS id, kecamatan AS text FROM kecamatan WHERE kode_kabupaten = "' . $kode_kabupaten . '" ' . $add_sintak . $limit)->result();
        }

        return $sintak;
    }

    // fungsi member
    function getMember($key, $param)
    {
        $limit = ' LIMIT 50';

        if (!empty($key)) {
            $add_sintak = ' AND (nik LIKE "%' . $key . '%" OR kode_member LIKE "%' . $key . '%" OR nama LIKE "%' . $key . '%" OR email LIKE "%' . $key . '%")';
        } else {
            $add_sintak = '';
        }

        if ($param == 'Health') {
            $sintak = $this->db->query('SELECT kode_member AS id, CONCAT(kode_member, " ~ ", (SELECT nama FROM m_prefix WHERE kode_prefix = member.kode_prefix), ". ", nama) AS text, nik, email
            FROM member 
            WHERE kode_member != "U00001" AND actived = 1 ' . $add_sintak . ' ORDER BY kode_member ASC ' . $limit)->result();
        } else {
            $sintak = $this->db->query('SELECT kode_member AS id, CONCAT(kode_member, " ~ " , nama) AS text, nik, email, id2
            FROM ( 
                SELECT kode_member, CONCAT((SELECT nama FROM m_prefix WHERE kode_prefix = member.kode_prefix), ". ", nama) AS nama, actived, 0 AS id2, nik, email
                FROM member 
                WHERE kode_member = "U00001"
                
                UNION ALL 
                
                SELECT kode_member, nama, actived, kode_member AS id2, nik, email
                FROM member 
                WHERE kode_member != "U00001"
            ) AS member_in 
            WHERE actived = 1 ' . $add_sintak . ' ORDER BY id2 ASC ' . $limit)->result();
        }


        return $sintak;
    }

    // fungsi user
    function getUser($key)
    {
        $limit = ' LIMIT 50';

        if (!empty($key)) {
            $add_sintak = ' AND (nik LIKE "%' . $key . '%" OR kode_user LIKE "%' . $key . '%" OR nama LIKE "%' . $key . '%" OR email LIKE "%' . $key . '%") ORDER BY nama ASC';
        } else {
            $add_sintak = ' ORDER BY nama ASC';
        }

        $sintak = $this->db->query('SELECT kode_user AS id, CONCAT("Status: ", (SELECT keterangan FROM m_role WHERE kode_role = user.kode_role), " ~ Nama: " , nama) AS text FROM user WHERE kode_role <> "R0005"' . $add_sintak . $limit)->result();

        return $sintak;
    }

    // fungsi poli
    function getPoli($key)
    {
        $limit = ' LIMIT 50';

        if (!empty($key)) {
            $add_sintak = ' WHERE (keterangan LIKE "%' . $key . '%")';
        } else {
            $add_sintak = '';
        }

        $sintak = $this->db->query('SELECT kode_poli AS id, CONCAT(keterangan) AS text, id2
        FROM ( 
            SELECT kode_poli, keterangan, 0 AS id2
            FROM m_poli
            WHERE kode_poli = "POL0000001"
            
            UNION ALL 
            
            SELECT kode_poli, keterangan, kode_poli AS id2
            FROM m_poli
            WHERE kode_poli != "POL0000001"
        ) AS member_in 
        ' . $add_sintak . ' ORDER BY id2 ASC ' . $limit)->result();

        return $sintak;
    }

    // fungsi dokter_poli
    function getDokterPoli($key, $kode_poli)
    {
        $now = date('Y-m-d');
        $limit = ' LIMIT 50';

        if ($kode_poli == null || $kode_poli == "" || $kode_poli == "null") {
            $sintak = $this->db->query('SELECT 0 AS id, "Pilih Poli Dahulu" AS text FROM dokter_poli LIMIT 1')->result();
        } else {
            if (!empty($key)) {
                $add_sintak = ' AND (dp.kode_dokter LIKE "%' . $key . '%" OR dp.kode_poli LIKE "%' . $key . '%" OR d.nama LIKE "%' . $key . '%") GROUP BY dp.kode_dokter ORDER BY dp.kode_dokter ASC';
            } else {
                $add_sintak = ' GROUP BY dp.kode_dokter ORDER BY dp.kode_dokter ASC ';
            }

            $sintak = $this->db->query(
                'SELECT dp.kode_dokter AS id, CONCAT("Dr. ", d.nama) AS text 
                FROM dokter_poli dp 
                JOIN dokter d ON dp.kode_dokter = d.kode_dokter 
                JOIN m_poli p ON p.kode_poli = dp.kode_poli
                JOIN jadwal_dokter jd ON jd.kode_dokter = d.kode_dokter 
                WHERE jd.status = 1 AND jd.kode_cabang = "' . $this->session->userdata('cabang') . '" AND jd.date_start <= "' . $now . '" AND jd.date_end >= "' . $now . '" AND dp.kode_poli = "' . $kode_poli . '" ' . $add_sintak . $limit
            )->result();
        }

        return $sintak;
    }

    // fungsi poli_dokter
    function getPoliDokter($key, $kode_dokter)
    {
        $now = date('Y-m-d');
        $limit = ' LIMIT 50';

        if ($kode_dokter == null || $kode_dokter == "" || $kode_dokter == "null") {
            $sintak = $this->db->query('SELECT 0 AS id, "Pilih Dokter Dahulu" AS text FROM dokter_poli LIMIT 1')->result();
        } else {
            if (!empty($key)) {
                $add_sintak = ' AND (dp.kode_dokter LIKE "%' . $key . '%" OR dp.kode_poli LIKE "%' . $key . '%" OR p.keterangan LIKE "%' . $key . '%")  GROUP BY dp.kode_dokter ORDER BY p.keterangan ASC';
            } else {
                $add_sintak = '  GROUP BY dp.kode_dokter ORDER BY p.keterangan ASC';
            }

            $sintak = $this->db->query(
                'SELECT dp.kode_poli AS id, p.keterangan AS text 
                FROM dokter_poli dp 
                JOIN m_poli p ON p.kode_poli = dp.kode_poli
                WHERE dp.kode_dokter = "' . $kode_dokter . '" ' . $add_sintak . $limit
            )->result();
        }

        return $sintak;
    }

    // fungsi bed
    function getBed($key, $kode_ruang)
    {
        $limit = ' LIMIT 50';

        if ($kode_ruang == null || $kode_ruang == "" || $kode_ruang == "null") {
            $sintak = $this->db->query('SELECT 0 AS id, "Pilih Ruang Dahulu" AS text FROM bed LIMIT 1')->result();
        } else {
            if (!empty($key)) {
                $add_sintak = ' AND (b.kode_bed LIKE "%' . $key . '%" OR b.nama_bed LIKE "%' . $key . '%" OR r.keterangan LIKE "%' . $key . '%") ORDER BY b.nama_bed ASC';
            } else {
                $add_sintak = ' ORDER BY b.nama_bed ASC';
            }

            $sintak = $this->db->query(
                'SELECT b.kode_bed AS id, nama_bed AS text 
                FROM bed b
                JOIN m_ruang r ON r.kode_ruang = b.kode_ruang
                JOIN bed_cabang bc ON b.kode_bed = bc.kode_bed
                WHERE bc.status_bed = 0 AND bc.kode_cabang = "' . $this->session->userdata('cabang') . '" AND b.kode_ruang = "' . $kode_ruang . '" ' . $add_sintak . $limit
            )->result();
        }

        return $sintak;
    }

    // fungsi dokter_all
    function getDokterAll($key)
    {
        $limit = ' LIMIT 50';

        if (!empty($key)) {
            $add_sintak = ' WHERE (dp.kode_dokter LIKE "%' . $key . '%" OR dp.nik LIKE "%' . $key . '%" OR dp.npwp LIKE "%' . $key . '%" OR dp.nama LIKE "%' . $key . '%") ORDER BY dp.nama ASC';
        } else {
            $add_sintak = ' ORDER BY dp.nama ASC';
        }

        $sintak = $this->db->query('SELECT dp.kode_dokter AS id, CONCAT("Dr. ", dp.nama) AS text FROM dokter dp ' . $add_sintak . $limit)->result();

        return $sintak;
    }

    // fungsi ruang
    function getRuang($key)
    {
        $limit = ' LIMIT 50';

        if (!empty($key)) {
            $add_sintak = ' WHERE (r.keterangan LIKE "%' . $key . '%") ORDER BY r.keterangan ASC';
        } else {
            $add_sintak = ' ORDER BY r.keterangan ASC';
        }

        $sintak = $this->db->query("SELECT r.kode_ruang AS id, r.keterangan AS text 
        FROM m_ruang r $add_sintak " . $limit)->result();

        return $sintak;
    }

    // fungsi supplier
    function getSupplier($key)
    {
        $limit = ' LIMIT 50';

        if (!empty($key)) {
            $add_sintak = ' WHERE (nama LIKE "%' . $key . '%") ORDER BY nama ASC';
        } else {
            $add_sintak = ' ORDER BY nama ASC';
        }

        $sintak = $this->db->query('SELECT kode_supplier AS id, nama AS text FROM m_supplier ' . $add_sintak . $limit)->result();

        return $sintak;
    }

    // fungsi gudang Internal
    function getGudang($key, $cekgud)
    {
        $limit = ' LIMIT 50';

        if ($cekgud == 1) {
            $keygud = " bagian = 'Internal'";
        } else {
            $keygud = " bagian = 'Logistik'";
        }

        if (!empty($key)) {
            $add_sintak = ' AND (nama LIKE "%' . $key . '%") ORDER BY nama ASC';
        } else {
            $add_sintak = ' ORDER BY nama ASC';
        }

        $sintak = $this->db->query('SELECT kode_gudang AS id, nama AS text FROM m_gudang WHERE ' . $keygud . $add_sintak . $limit)->result();

        return $sintak;
    }

    // fungsi pekerjaan
    function getPekerjaan($key)
    {
        $limit = ' LIMIT 50';

        if (!empty($key)) {
            $add_sintak = ' WHERE (keterangan LIKE "%' . $key . '%") ORDER BY keterangan ASC';
        } else {
            $add_sintak = ' ORDER BY keterangan ASC';
        }

        $sintak = $this->db->query('SELECT kode_pekerjaan AS id, keterangan AS text FROM m_pekerjaan ' . $add_sintak . $limit)->result();

        return $sintak;
    }

    // fungsi agama
    function getAgama($key)
    {
        $limit = ' LIMIT 50';

        if (!empty($key)) {
            $add_sintak = ' WHERE (keterangan LIKE "%' . $key . '%") ORDER BY keterangan ASC';
        } else {
            $add_sintak = ' ORDER BY keterangan ASC';
        }

        $sintak = $this->db->query('SELECT kode_agama AS id, keterangan AS text FROM m_agama ' . $add_sintak . $limit)->result();

        return $sintak;
    }

    // fungsi pendidikan
    function getPendidikan($key)
    {
        $limit = ' LIMIT 50';

        if (!empty($key)) {
            $add_sintak = ' WHERE (keterangan LIKE "%' . $key . '%") ORDER BY keterangan ASC';
        } else {
            $add_sintak = ' ORDER BY keterangan ASC';
        }

        $sintak = $this->db->query('SELECT kode_pendidikan AS id, keterangan AS text FROM m_pendidikan ' . $add_sintak . $limit)->result();

        return $sintak;
    }

    // fungsi pendaftaran by poli
    function getPendaftaran($key, $kode_poli)
    {
        $kode_cabang = $this->session->userdata('cabang');

        $limit = ' LIMIT 50';

        if (!empty($key)) {
            $add_sintak = ' AND (m.nama LIKE "%' . $key . '%" OR p.kode_member LIKE "%' . $key . '%") ORDER BY p.no_trx ASC';
        } else {
            $add_sintak = ' ORDER BY p.no_trx ASC';
        }

        $sintak = $this->db->query('SELECT p.no_trx AS id, CONCAT(p.no_trx, " ~ Kode Member: " , p.kode_member, " | Nama Member: ", m.nama) AS text FROM pendaftaran p JOIN member m ON p.kode_member = m.kode_member LEFT JOIN barang_out_header b ON p.no_trx = b.no_trx WHERE b.no_trx IS NULL AND p.kode_cabang = "' . $kode_cabang . '" AND p.kode_poli = "' . $kode_poli . '" AND p.status_trx = 0 ' . $add_sintak . $limit)->result();

        return $sintak;
    }

    // fungsi penjualan
    function getPenjualan($key)
    {
        $limit = ' LIMIT 50';

        if (!empty($key)) {
            $add_sintak = ' AND (bo.invoice LIKE "%' . $key . '%" OR bo.kode_member LIKE "%' . $key . '%" OR m.nama LIKE "%' . $key . '%" OR bo.no_trx LIKE "%' . $key . '%") ORDER BY bo.invoice ASC';
        } else {
            $add_sintak = ' ORDER BY bo.invoice ASC';
        }

        $sintak = $this->db->query('SELECT bo.invoice AS id, CONCAT(bo.invoice, " ~ No. Trans: ", IF(bo.no_trx IS NULL, "Tidak Mendaftar", bo.no_trx), " | Tgl/Jam: " , bo.tgl_jual, "/", bo.jam_jual, " | Member: ", m.nama, " | Total: Rp.", FORMAT(bo.total, 2)) AS text FROM barang_out_header bo JOIN member m ON bo.kode_member = m.kode_member WHERE bo.status_jual = 0 ' . $add_sintak . $limit)->result();

        return $sintak;
    }

    // fungsi penjualan retur
    function getPenjualanRetur($key)
    {
        $limit = ' LIMIT 50';

        if (!empty($key)) {
            $add_sintak = ' AND (bo.invoice LIKE "%' . $key . '%" OR bo.invoice_jual LIKE "%' . $key . '%") ORDER BY bo.invoice ASC';
        } else {
            $add_sintak = ' ORDER BY bo.invoice ASC';
        }

        $sintak = $this->db->query('SELECT bo.invoice AS id, CONCAT(bo.invoice, " ~ Inv Jual: ", bo.invoice_jual, " | Tgl/Jam: " , bo.tgl_retur, "/", bo.jam_retur, " | Total: Rp.", FORMAT(bo.total, 2)) AS text FROM barang_out_retur_header bo WHERE bo.status_retur = 0 ' . $add_sintak . $limit)->result();

        return $sintak;
    }

    // fungsi bank
    function getBank($key)
    {
        $limit = ' LIMIT 50';

        if (!empty($key)) {
            $add_sintak = ' WHERE (keterangan LIKE "%' . $key . '%") ORDER BY keterangan ASC';
        } else {
            $add_sintak = ' ORDER BY keterangan ASC';
        }

        $sintak = $this->db->query('SELECT kode_bank AS id, keterangan AS text FROM m_bank ' . $add_sintak . $limit)->result();

        return $sintak;
    }

    // fungsi tipe bank
    function getTipeBank($key)
    {
        $limit = ' LIMIT 50';

        if (!empty($key)) {
            $add_sintak = ' WHERE (keterangan LIKE "%' . $key . '%") ORDER BY keterangan ASC';
        } else {
            $add_sintak = ' ORDER BY keterangan ASC';
        }

        $sintak = $this->db->query('SELECT kode_tipe AS id, keterangan AS text FROM tipe_bank ' . $add_sintak . $limit)->result();

        return $sintak;
    }

    // fungsi penjualan untuk diretur
    function getJualForRetur($key)
    {
        $now = date('Y-m-d');

        $limit = ' LIMIT 50';

        if (!empty($key)) {
            $add_sintak = ' AND (bo.invoice LIKE "%' . $key . '%" OR bo.kode_member LIKE "%' . $key . '%" OR m.nama LIKE "%' . $key . '%" OR bo.no_trx LIKE "%' . $key . '%") ORDER BY bo.invoice ASC';
        } else {
            $add_sintak = ' ORDER BY bo.invoice ASC';
        }

        $sintak = $this->db->query('SELECT bo.invoice AS id, CONCAT(bo.invoice, " ~ Tgl/Jam: ", bo.tgl_jual, "/", bo.jam_jual, " | Total: Rp.", FORMAT(bo.total, 0)) AS text FROM barang_out_header bo JOIN member m ON bo.kode_member = m.kode_member WHERE bo.status_jual = 1 ' . $add_sintak . $limit)->result();

        return $sintak;
    }

    // fungsi promo
    function getPromo($key, $min_buy)
    {
        $limit = ' LIMIT 50';
        $now = date('Y-m-d');

        if (!empty($key)) {
            $add_sintak = ' AND (nama LIKE "%' . $key . '%") ORDER BY nama ASC';
        } else {
            $add_sintak = ' ORDER BY nama ASC';
        }

        $sintak = $this->db->query('SELECT kode_promo AS id, nama AS text FROM m_promo WHERE "' . $now . '" > tgl_mulai AND "' . $now . '" <= tgl_selesai OR min_buy <= "' . $min_buy . '" ' . $add_sintak . $limit)->result();

        return $sintak;
    }

    // fungsi barang
    function getBarang($key)
    {
        $kode_cabang = $this->session->userdata('cabang');

        $limit = ' LIMIT 20';

        if (!empty($key)) {
            $add_sintak = ' AND (b.kode_barang LIKE "%' . $key . '%" OR b.nama LIKE "%' . $key . '%") ORDER BY b.nama ASC';
        } else {
            $add_sintak = ' ORDER BY b.nama ASC';
        }

        $sintak = $this->db->query('SELECT b.kode_barang AS id, b.nama AS text FROM barang b JOIN barang_cabang bc ON bc.kode_barang = b.kode_barang WHERE bc.kode_cabang = "' . $kode_cabang . '" ' . $add_sintak . $limit)->result();

        return $sintak;
    }

    // fungsi kas bank
    function getKasBank($key)
    {
        $kode_cabang = $this->session->userdata('cabang');

        $limit = ' LIMIT 20';

        if (!empty($key)) {
            $add_sintak = ' WHERE (id LIKE "%' . $key . '%" OR text LIKE "%' . $key . '%") ORDER BY indek ASC';
        } else {
            $add_sintak = ' ORDER BY indek ASC';
        }

        $sintak = $this->db->query(
            'SELECT id, text FROM (
                SELECT "KB00000000" AS id, "** SALDO UTAMA **" AS text, 0 AS indek
                FROM kas_utama WHERE kode_cabang = "' . $kode_cabang . '"

                UNION ALL

                SELECT kode_kas_bank AS id, nama AS text, id AS indek
                FROM kas_bank
            ) AS kas ' . $add_sintak . $limit
        )->result();

        return $sintak;
    }

    // fungsi kategori tarif
    function dataKatTarif($key)
    {
        $limit = ' LIMIT 20';

        if (!empty($key)) {
            $add_sintak = ' WHERE (kode_kategori LIKE "%' . $key . '%" OR keterangan LIKE "%' . $key . '%") ORDER BY keterangan ASC';
        } else {
            $add_sintak = ' ORDER BY keterangan ASC';
        }

        $sintak = $this->db->query(
            'SELECT kode_kategori AS id, (keterangan) AS text FROM kategori_tarif ' . $add_sintak . $limit
        )->result();

        return $sintak;
    }
}
