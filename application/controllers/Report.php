<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report extends CI_Controller
{
    // variable open public untuk controller Home
    public $data;

    public function __construct()
    {
        parent::__construct();
        // load model M_auth
        $this->load->model("M_auth");

        if (!empty($this->session->userdata("email"))) { // jika session email masih ada
            // ambil isi data berdasarkan email session dari table user, kemudian tampung ke variable $user
            $user = $this->M_global->getData("user", ["email" => $this->session->userdata("email")]);

            // tampung data ke variable data public
            $this->data = [
                'nama'      => $user->nama,
                'email'     => $user->email,
                'kode_role' => $user->kode_role,
                'actived'   => $user->actived,
                'foto'      => $user->foto,
                'shift'     => $this->session->userdata('shift'),
                'menu'      => 'Master',
            ];
        } else { // selain itu
            // kirimkan kembali ke Auth
            redirect('Auth');
        }
    }

    // barang
    public function barang()
    {
        // param website
        $web_setting    = $this->M_global->getData('web_setting', ['id' => 1]);

        $position       = 'P'; // cek posisi l/p

        // body cetakan
        $body           = '';
        $body           .= '<br><br>'; // beri jarak antara kop dengan body

        // parameter dari view laporan
        $pencetak       = $this->M_global->getData('user', ['kode_user' => $this->session->userdata('kode_user')])->nama;

        // sintak
        $sintak         = $this->db->query("SELECT b.*, s.keterangan AS satuan, k.keterangan AS kategori, j.keterangan AS jenis FROM barang b JOIN m_satuan s USING(kode_satuan) JOIN m_kategori k USING(kode_kategori) JOIN m_jenis j USING(kode_jenis) ORDER BY b.kode_barang ASC")->result();

        $body .= '<table style="width: 100%; font-size: 10px;" cellpadding="5px">';
        $body .= '<tr>
            <th rowspan="2" style="width: 5%; border: 1px solid black; background-color: red; color: white;">#</th>
            <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: red; color: white;">Kode</th>
            <th rowspan="2" style="width: 15%; border: 1px solid black; background-color: red; color: white;">Nama</th>
            <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: red; color: white;">Satuan</th>
            <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: red; color: white;">Kategori</th>
            <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: red; color: white;">Jenis</th>
            <th colspan="4" style="width: 40%; border: 1px solid black; background-color: red; color: white;">Harga</th>
        </tr>
        <tr>
            <th style="width: 10%; border: 1px solid black; background-color: red; color: white;">HNA</th>
            <th style="width: 10%; border: 1px solid black; background-color: red; color: white;">HPP</th>
            <th style="width: 10%; border: 1px solid black; background-color: red; color: white;">Jual</th>
            <th style="width: 10%; border: 1px solid black; background-color: red; color: white;">Persediaan</th>
        </tr>';

        $no = 1;
        foreach ($sintak as $s) {
            $body .= '<tr>
                <td style="border: 1px solid black;">' . $no . '</td>
                <td style="border: 1px solid black;">' . $s->kode_barang . '</td>
                <td style="border: 1px solid black;">' . $s->nama . '</td>
                <td style="border: 1px solid black;">' . $s->satuan . '</td>
                <td style="border: 1px solid black;">' . $s->kategori . '</td>
                <td style="border: 1px solid black;">' . $s->jenis . '</td>
                <td style="border: 1px solid black; text-align: right;">' . number_format($s->hna, 2) . '</td>
                <td style="border: 1px solid black; text-align: right;">' . number_format($s->hpp, 2) . '</td>
                <td style="border: 1px solid black; text-align: right;">' . number_format($s->harga_jual, 2) . '</td>
                <td style="border: 1px solid black; text-align: right;">' . number_format($s->nilai_persediaan, 2) . '</td>
            </tr>';
            $no++;
        }

        $body .= '</table>';

        $judul = 'Master Barang';
        $filename = $judul; // nama file yang ingin di simpan

        // jalankan fungsi cetak_pdf
        cetak_pdf($judul, $body, 1, $position, $filename, $web_setting);
    }

    // logistik
    public function logistik($param)
    {
        // param website
        $web_setting    = $this->M_global->getData('web_setting', ['id' => 1]);

        $position       = 'P'; // cek posisi l/p

        // body cetakan
        $body           = '';
        $body           .= '<br><br>'; // beri jarak antara kop dengan body

        // parameter dari view laporan
        $pencetak       = $this->M_global->getData('user', ['kode_user' => $this->session->userdata('kode_user')])->nama;

        // sintak
        $sintak         = $this->db->query("SELECT b.*, s.keterangan AS satuan, k.keterangan AS kategori FROM logistik b JOIN m_satuan s USING(kode_satuan) JOIN m_kategori k USING(kode_kategori) ORDER BY b.kode_logistik ASC")->result();

        $body .= '<table style="width: 100%; font-size: 10px;" cellpadding="5px">';
        $body .= '<tr>
            <th rowspan="2" style="width: 5%; border: 1px solid black; background-color: red; color: white;">#</th>
            <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: red; color: white;">Kode</th>
            <th rowspan="2" style="width: 15%; border: 1px solid black; background-color: red; color: white;">Nama</th>
            <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: red; color: white;">Satuan</th>
            <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: red; color: white;">Kategori</th>
            <th colspan="4" style="width: 40%; border: 1px solid black; background-color: red; color: white;">Harga</th>
        </tr>
        <tr>
            <th style="width: 10%; border: 1px solid black; background-color: red; color: white;">HNA</th>
            <th style="width: 10%; border: 1px solid black; background-color: red; color: white;">HPP</th>
            <th style="width: 10%; border: 1px solid black; background-color: red; color: white;">Jual</th>
            <th style="width: 10%; border: 1px solid black; background-color: red; color: white;">Persediaan</th>
        </tr>';

        $no = 1;
        foreach ($sintak as $s) {
            $body .= '<tr>
                <td style="border: 1px solid black;">' . $no . '</td>
                <td style="border: 1px solid black;">' . $s->kode_logistik . '</td>
                <td style="border: 1px solid black;">' . $s->nama . '</td>
                <td style="border: 1px solid black;">' . $s->satuan . '</td>
                <td style="border: 1px solid black;">' . $s->kategori . '</td>
                <td style="border: 1px solid black; text-align: right;">' . number_format($s->hna, 2) . '</td>
                <td style="border: 1px solid black; text-align: right;">' . number_format($s->hpp, 2) . '</td>
                <td style="border: 1px solid black; text-align: right;">' . number_format($s->harga_jual, 2) . '</td>
                <td style="border: 1px solid black; text-align: right;">' . number_format($s->nilai_persediaan, 2) . '</td>
            </tr>';
            $no++;
        }

        $body .= '</table>';

        $judul = 'Master Logistik';
        $filename = $judul; // nama file yang ingin di simpan

        // jalankan fungsi cetak_pdf
        cetak_pdf($judul, $body, $param, $position, $filename, $web_setting);
    }

    // pengguna
    public function pengguna($param)
    {
        // param website
        $web_setting    = $this->M_global->getData('web_setting', ['id' => 1]);

        $position       = 'P'; // cek posisi l/p

        // body cetakan
        $body           = '';
        $body           .= '<br><br>'; // beri jarak antara kop dengan body

        // parameter dari view laporan
        $pencetak       = $this->M_global->getData('user', ['kode_user' => $this->session->userdata('kode_user')])->nama;

        // sintak
        $sintak         = $this->M_global->getResult('user');

        $body .= '<table style="width: 100%; font-size: 10px;" cellpadding="5px">';
        $body .= '<tr>
            <th style="width: 5%; border: 1px solid black; background-color: red; color: white;">#</th>
            <th style="width: 10%; border: 1px solid black; background-color: red; color: white;">Kode</th>
            <th style="width: 20%; border: 1px solid black; background-color: red; color: white;">Nama</th>
            <th style="width: 20%; border: 1px solid black; background-color: red; color: white;">Tingkatan</th>
            <th style="width: 15%; border: 1px solid black; background-color: red; color: white;">No Hp</th>
            <th style="width: 25%; border: 1px solid black; background-color: red; color: white;">Email</th>
            <th style="width: 5%; border: 1px solid black; background-color: red; color: white;">Status</th>
        </tr>';

        $no = 1;
        foreach ($sintak as $s) {

            $body .= '<tr>
                <td style="border: 1px solid black;">' . $no . '</td>
                <td style="border: 1px solid black;">' . $s->kode_user . '</td>
                <td style="border: 1px solid black;">' . $s->nama . '</td>
                <td style="border: 1px solid black;">' . $this->M_global->getData('m_role', ['kode_role' => $s->kode_role])->keterangan . '</td>
                <td style="border: 1px solid black;">' . $s->nohp . '</td>
                <td style="border: 1px solid black;">' . $s->email . '</td>
                <td style="border: 1px solid black; font-weight: bold; text-align: center; background-color: ' . (($s->actived == 1) ? 'green' : 'grey') . '; color: ' . (($s->actived == 1) ? 'white' : 'black') . '">' . (($s->actived == 1) ? 'Aktif' : 'Non-aktif') . '</td>
            </tr>';
            $no++;
        }

        $body .= '</table>';

        $judul = 'Master Pengguna';
        $filename = $judul; // nama file yang ingin di simpan

        // jalankan fungsi cetak_pdf
        cetak_pdf($judul, $body, $param, $position, $filename, $web_setting);
    }

    // dokter
    public function dokter($param)
    {
        // param website
        $web_setting    = $this->M_global->getData('web_setting', ['id' => 1]);

        $position       = 'P'; // cek posisi l/p

        // body cetakan
        $body           = '';
        $body           .= '<br><br>'; // beri jarak antara kop dengan body

        // parameter dari view laporan
        $pencetak       = $this->M_global->getData('user', ['kode_user' => $this->session->userdata('kode_user')])->nama;

        // sintak
        $sintak         = $this->M_global->getResult('dokter');

        $body .= '<table style="width: 100%; font-size: 10px;" cellpadding="5px">';
        $body .= '<tr>
            <th style="width: 5%; border: 1px solid black; background-color: red; color: white;">#</th>
            <th style="width: 10%; border: 1px solid black; background-color: red; color: white;">NIK</th>
            <th style="width: 10%; border: 1px solid black; background-color: red; color: white;">SIP</th>
            <th style="width: 10%; border: 1px solid black; background-color: red; color: white;">NPWP</th>
            <th style="width: 20%; border: 1px solid black; background-color: red; color: white;">Nama</th>
            <th style="width: 10%; border: 1px solid black; background-color: red; color: white;">Nohp/Email</th>
            <th style="width: 20%; border: 1px solid black; background-color: red; color: white;">Alamat</th>
            <th style="width: 10%; border: 1px solid black; background-color: red; color: white;">Masa Kerja</th>
            <th style="width: 5%; border: 1px solid black; background-color: red; color: white;">Status</th>
        </tr>';

        $no = 1;
        foreach ($sintak as $s) {

            $prov   = $this->M_global->getData('m_provinsi', ['kode_provinsi' => $s->provinsi])->provinsi;
            $kab    = $this->M_global->getData('kabupaten', ['kode_kabupaten' => $s->kabupaten])->kabupaten;
            $kec    = $this->M_global->getData('kecamatan', ['kode_kecamatan' => $s->kecamatan])->kecamatan;

            $alamat = 'Prov. ' . $prov . ',<br>Kab. ' . $kab . ',<br>Kec. ' . $kec . ',<br>Ds. ' . $s->desa . ',<br>(POS: ' . $s->kodepos . ')';

            $body .= '<tr>
                <td style="border: 1px solid black; text-align: right;">' . $no . '</td>
                <td style="border: 1px solid black;">' . $s->nik . '</td>
                <td style="border: 1px solid black;">' . $s->sip . '</td>
                <td style="border: 1px solid black;">' . $s->npwp . '</td>
                <td style="border: 1px solid black;">' . $s->nama . '</td>
                <td style="border: 1px solid black;">Nohp: <br>' . $s->nohp . '<br><br>Email: <br>' . $s->email . '</td>
                <td style="border: 1px solid black;">' . $alamat . '</td>
                <td style="border: 1px solid black;">Mulai: <br>' . date('d/m/Y', strtotime($s->tgl_mulai)) . '<br><br>Berhenti: <br>' . date('d/m/Y', strtotime($s->tgl_berhenti)) . '</td>
                <td style="border: 1px solid black; font-weight: bold; text-align: center; background-color: ' . (($s->status == 1) ? 'green' : 'grey') . '; color: ' . (($s->status == 1) ? 'white' : 'black') . '">' . (($s->status == 1) ? 'Aktif' : 'Non-aktif') . '</td>
            </tr>';
            $no++;
        }

        $body .= '</table>';

        $judul = 'Master Dokter';
        $filename = $judul; // nama file yang ingin di simpan

        // jalankan fungsi cetak_pdf
        cetak_pdf($judul, $body, $param, $position, $filename, $web_setting);
    }

    // perawat
    public function perawat($param)
    {
        // param website
        $web_setting    = $this->M_global->getData('web_setting', ['id' => 1]);

        $position       = 'P'; // cek posisi l/p

        // body cetakan
        $body           = '';
        $body           .= '<br><br>'; // beri jarak antara kop dengan body

        // parameter dari view laporan
        $pencetak       = $this->M_global->getData('user', ['kode_user' => $this->session->userdata('kode_user')])->nama;

        // sintak
        $sintak         = $this->M_global->getResult('perawat');

        $body .= '<table style="width: 100%; font-size: 10px;" cellpadding="5px">';
        $body .= '<tr>
            <th style="width: 5%; border: 1px solid black; background-color: red; color: white;">#</th>
            <th style="width: 10%; border: 1px solid black; background-color: red; color: white;">NIK</th>
            <th style="width: 10%; border: 1px solid black; background-color: red; color: white;">SIP</th>
            <th style="width: 10%; border: 1px solid black; background-color: red; color: white;">NPWP</th>
            <th style="width: 20%; border: 1px solid black; background-color: red; color: white;">Nama</th>
            <th style="width: 10%; border: 1px solid black; background-color: red; color: white;">Nohp/Email</th>
            <th style="width: 20%; border: 1px solid black; background-color: red; color: white;">Alamat</th>
            <th style="width: 10%; border: 1px solid black; background-color: red; color: white;">Masa Kerja</th>
            <th style="width: 5%; border: 1px solid black; background-color: red; color: white;">Status</th>
        </tr>';

        $no = 1;
        foreach ($sintak as $s) {

            $prov   = $this->M_global->getData('m_provinsi', ['kode_provinsi' => $s->provinsi])->provinsi;
            $kab    = $this->M_global->getData('kabupaten', ['kode_kabupaten' => $s->kabupaten])->kabupaten;
            $kec    = $this->M_global->getData('kecamatan', ['kode_kecamatan' => $s->kecamatan])->kecamatan;

            $alamat = 'Prov. ' . $prov . ',<br>Kab. ' . $kab . ',<br>Kec. ' . $kec . ',<br>Ds. ' . $s->desa . ',<br>(POS: ' . $s->kodepos . ')';

            $body .= '<tr>
                <td style="border: 1px solid black; text-align: right;">' . $no . '</td>
                <td style="border: 1px solid black;">' . $s->nik . '</td>
                <td style="border: 1px solid black;">' . $s->sip . '</td>
                <td style="border: 1px solid black;">' . $s->npwp . '</td>
                <td style="border: 1px solid black;">' . $s->nama . '</td>
                <td style="border: 1px solid black;">Nohp: <br>' . $s->nohp . '<br><br>Email: <br>' . $s->email . '</td>
                <td style="border: 1px solid black;">' . $alamat . '</td>
                <td style="border: 1px solid black;">Mulai: <br>' . date('d/m/Y', strtotime($s->tgl_mulai)) . '<br><br>Berhenti: <br>' . date('d/m/Y', strtotime($s->tgl_berhenti)) . '</td>
                <td style="border: 1px solid black; font-weight: bold; text-align: center; background-color: ' . (($s->status == 1) ? 'green' : 'grey') . '; color: ' . (($s->status == 1) ? 'white' : 'black') . '">' . (($s->status == 1) ? 'Aktif' : 'Non-aktif') . '</td>
            </tr>';
            $no++;
        }

        $body .= '</table>';

        $judul = 'Master Perawat';
        $filename = $judul; // nama file yang ingin di simpan

        // jalankan fungsi cetak_pdf
        cetak_pdf($judul, $body, $param, $position, $filename, $web_setting);
    }
}
