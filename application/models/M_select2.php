<?php
class M_select2 extends CI_Model
{
    // fungsi Cabang
    function getCabang($key, $email)
    {
        $limit = ' LIMIT 50';

        if ($email == null || $email == "" || $email == "null") {
            $sintak = $this->db->query('SELECT 0 AS id, "Pilih Cabang Dahulu" AS text FROM cabang_user LIMIT 1')->result();
        } else {
            if (!empty($key)) {
                $add_sintak = ' AND (cu.kode_cabang LIKE "%' . $key . '%" OR c.cabang LIKE "%' . $key . '%") ORDER BY c.cabang ASC';
            } else {
                $add_sintak = ' ORDER BY c.cabang ASC';
            }

            $sintak = $this->db->query('SELECT cu.kode_cabang AS id, c.cabang AS text FROM cabang_user cu JOIN cabang c USING (kode_cabang) WHERE cu.email = "' . $email . '" ' . $add_sintak . $limit)->result();
        }

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
    function getMember($key)
    {
        $limit = ' LIMIT 50';

        if (!empty($key)) {
            $add_sintak = ' AND (nik LIKE "%' . $key . '%" OR kode_member LIKE "%' . $key . '%" OR nama LIKE "%' . $key . '%" OR email LIKE "%' . $key . '%") ORDER BY nama ASC';
        } else {
            $add_sintak = ' ORDER BY nama ASC';
        }

        $sintak = $this->db->query('SELECT kode_member AS id, CONCAT(kode_member, " ~ " , nama) AS text FROM member WHERE actived = 1 ' . $add_sintak . $limit)->result();

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
            $add_sintak = ' WHERE (keterangan LIKE "%' . $key . '%") ORDER BY keterangan ASC';
        } else {
            $add_sintak = ' ORDER BY keterangan ASC';
        }

        $sintak = $this->db->query('SELECT kode_poli AS id, keterangan AS text FROM m_poli ' . $add_sintak . $limit)->result();

        return $sintak;
    }

    // fungsi dokter_poli
    function getDokterPoli($key, $kode_poli)
    {
        $limit = ' LIMIT 50';

        if ($kode_poli == null || $kode_poli == "" || $kode_poli == "null") {
            $sintak = $this->db->query('SELECT 0 AS id, "Pilih Poli Dahulu" AS text FROM dokter_poli LIMIT 1')->result();
        } else {
            if (!empty($key)) {
                $add_sintak = ' AND (dp.kode_dokter LIKE "%' . $key . '%" OR dp.kode_poli LIKE "%' . $key . '%") ORDER BY dp.kode_dokter ASC';
            } else {
                $add_sintak = ' ORDER BY dp.kode_dokter ASC';
            }

            $sintak = $this->db->query('SELECT dp.kode_dokter AS id, CONCAT(d.nama, " ~ ", p.keterangan) AS text FROM dokter_poli dp JOIN dokter d ON dp.kode_dokter = d.kode_dokter JOIN m_poli p ON p.kode_poli = dp.kode_poli WHERE dp.kode_poli = "' . $kode_poli . '" ' . $add_sintak . $limit)->result();
        }

        return $sintak;
    }

    // fungsi ruang
    function getRuang($key)
    {
        $limit = ' LIMIT 50';

        if (!empty($key)) {
            $add_sintak = ' WHERE (keterangan LIKE "%' . $key . '%") ORDER BY keterangan ASC';
        } else {
            $add_sintak = ' ORDER BY keterangan ASC';
        }

        $sintak = $this->db->query('SELECT kode_ruang AS id, keterangan AS text FROM m_ruang ' . $add_sintak . $limit)->result();

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
        $limit = ' LIMIT 50';

        if (!empty($key)) {
            $add_sintak = ' AND (m.nama LIKE "%' . $key . '%" OR p.kode_member LIKE "%' . $key . '%") ORDER BY p.no_trx ASC';
        } else {
            $add_sintak = ' ORDER BY p.no_trx ASC';
        }

        $sintak = $this->db->query('SELECT p.no_trx AS id, CONCAT(p.no_trx, " ~ Kode Member: " , p.kode_member, " | Nama Member: ", m.nama) AS text FROM pendaftaran p JOIN member m ON p.kode_member = m.kode_member WHERE p.kode_poli = "' . $kode_poli . '" AND p.status_trx = 0 ' . $add_sintak . $limit)->result();

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

        $sintak = $this->db->query('SELECT bo.invoice AS id, CONCAT(bo.invoice, " ~ Tgl/Jam: ", bo.tgl_jual, "/", bo.jam_jual, " | Total: Rp.", FORMAT(bo.total, 2)) AS text FROM barang_out_header bo JOIN member m ON bo.kode_member = m.kode_member WHERE bo.status_jual = 1 AND tgl_jual = "' . $now . '" ' . $add_sintak . $limit)->result();

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
}
