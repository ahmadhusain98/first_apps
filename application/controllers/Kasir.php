<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kasir extends CI_Controller
{
    // variable open public untuk controller Kasir
    public $data;

    public function __construct()
    {
        parent::__construct();
        // load model M_auth
        $this->load->model("M_auth");

        if (!empty($this->session->userdata("email"))) { // jika session email masih ada

            $id_menu = $this->M_global->getData('m_menu', ['url' => 'Kasir'])->id;

            // ambil isi data berdasarkan email session dari table user, kemudian tampung ke variable $user
            $user = $this->M_global->getData("user", ["email" => $this->session->userdata("email")]);

            $cek_akses_menu = $this->M_global->getData('akses_menu', ['id_menu' => $id_menu, 'kode_role' => $user->kode_role]);
            if ($cek_akses_menu) {
                // tampung data ke variable data public
                $this->data = [
                    'nama'      => $user->nama,
                    'email'     => $user->email,
                    'kode_role' => $user->kode_role,
                    'actived'   => $user->actived,
                    'foto'      => $user->foto,
                    'shift'     => $this->session->userdata('shift'),
                    'menu'      => 'Kasir',
                ];
            } else {
                // kirimkan kembali ke Auth
                redirect('Where');
            }
        } else { // selain itu
            // kirimkan kembali ke Auth
            redirect('Auth');
        }
    }

    // home page
    public function index()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter = [
            $this->data,
            'judul'         => 'Pembayaran',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Pembayaran',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Kasir/pembayaran_list/',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Kasir/Daftar', $parameter);
    }

    // fungsi list pembayaran
    public function pembayaran_list($param1 = 1, $param2 = '')
    {
        // parameter untuk list table
        $table            = 'pembayaran';
        $colum            = ['id', 'approved', 'token_pembayaran', 'invoice', 'inv_jual', 'no_trx', 'tgl_pembayaran', 'jam_pembayaran', 'kembalian', 'total', 'kode_user', 'jenis_pembayaran', 'cash', 'card'];
        $order            = 'id';
        $order2           = 'desc';
        $order_arr        = ['id' => 'asc'];
        $kondisi_param2   = '';
        $kondisi_param1   = 'tgl_pembayaran';

        // kondisi role
        $updated          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;
        $confirmed        = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->confirmed;

        // table server side tampung kedalam variable $list
        $dat    = explode("~", $param1);

        if ($dat[0] == 1) {
            $bulan        = date('m');
            $tahun        = date('Y');
            $type         = 1;
        } else {
            $bulan        = date('Y-m-d', strtotime($dat[1]));
            $tahun        = date('Y-m-d', strtotime($dat[2]));
            $type         = 2;
        }

        $list             = $this->M_datatables2->get_datatables($table, $colum, $order_arr, $order, $order2, $kondisi_param1, $type, $bulan, $tahun, $param2, $kondisi_param2);

        $data             = [];
        $no               = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {
            if ($updated > 0) {
                if ($rd->approved < 1) {
                    $upd_diss = '';
                } else {
                    $upd_diss = 'disabled';
                }
            } else {
                $upd_diss = 'disabled';
            }

            if ($deleted > 0) {
                if ($rd->approved < 1) {
                    $del_diss = '';
                } else {
                    $del_diss = 'disabled';
                }
            } else {
                $del_diss = 'disabled';
            }

            if ($confirmed > 0) {
                $confirm_diss = '';
            } else {
                $confirm_diss = 'disabled';
            }

            $row    = [];
            $row[]  = $no++;
            $row[]  = date('d/m/Y', strtotime($rd->tgl_pembayaran)) . ' ~ ' . date('H:i:s', strtotime($rd->jam_pembayaran)) . '<br>' . (($rd->approved > 0) ? '<span class="badge badge-primary">Acc</span>' : '<span class="badge badge-danger">Belum diAcc</span>');
            $row[]  = $rd->invoice;
            $row[]  = $rd->no_trx;
            $row[]  = ($rd->jenis_pembayaran == 0 ? 'CASH' : (($rd->jenis_pembayaran == 1) ? 'CARD' : 'CASH & CARD'));
            $row[]  = $rd->kode_user . ' ~ ' . $this->M_global->getData('user', ['kode_user' => $rd->kode_user])->nama;

            if ($confirmed > 0) {
                if ($rd->approved > 0) {
                    $actived_akun = '<button type="button" style="margin-bottom: 5px;" class="btn btn-dark" onclick="actived(' . "'" . $rd->token_pembayaran . "', 1" . ')" ' . $confirm_diss . '><i class="fa-solid fa-circle-xmark"></i></button>';
                } else {
                    $actived_akun = '<button type="button" style="margin-bottom: 5px;" class="btn btn-primary" onclick="actived(' . "'" . $rd->token_pembayaran . "', 0" . ')" ' . $confirm_diss . '><i class="fa-solid fa-circle-check"></i></button>';
                }
            } else {
                $actived_akun = '<button type="button" style="margin-bottom: 5px;" class="btn btn-primary" disabled><i class="fa-solid fa-circle-check"></i></button>';
            }

            $row[]  = '<div class="text-center">
                ' . $actived_akun . '
                <button type="button" style="margin-bottom: 5px;" class="btn btn-secondary" onclick="cetak(' . "'" . $rd->token_pembayaran . "', 0" . ')"><i class="fa-solid fa-file-pdf"></i></button>
                <button type="button" style="margin-bottom: 5px;" class="btn btn-info" onclick="email(' . "'" . $rd->token_pembayaran . "'" . ')"><i class="fa-solid fa-envelope-open-text"></i></button>
                <br>
                <button type="button" style="margin-bottom: 5px;" class="btn btn-warning" onclick="ubah(' . "'" . $rd->token_pembayaran . "'" . ')" ' . $upd_diss . '><i class="fa-regular fa-pen-to-square"></i></button>
                <button type="button" style="margin-bottom: 5px;" class="btn btn-danger" onclick="hapus(' . "'" . $rd->token_pembayaran . "'" . ')" ' . $del_diss . '><i class="fa-regular fa-circle-xmark"></i></button>
            </div>';
            $data[] = $row;
        }

        // hasil server side
        $output = [
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->M_datatables2->count_all($table, $colum, $order_arr, $order, $order2, $kondisi_param1, $type, $bulan, $tahun, $param2, $kondisi_param2),
            "recordsFiltered" => $this->M_datatables2->count_filtered($table, $colum, $order_arr, $order, $order2, $kondisi_param1, $type, $bulan, $tahun, $param2, $kondisi_param2),
            "data"            => $data,
        ];

        // kirimkan ke view
        echo json_encode($output);
    }

    // fungsi kirim email barang in
    public function email($token_pembayaran)
    {
        $email = $this->input->get('email');

        $header = $this->M_global->getData('pembayaran', ['token_pembayaran' => $token_pembayaran]);

        $jual = $this->M_global->getData('barang_out_header', ['invoice' => $header->inv_jual]);

        $judul = 'Kwitansi ' . $header->invoice;

        // $attched_file    = base_url() . 'assets/file/pdf/' . $judul . '.pdf';ahmad.ummgl@gmail.com
        $attched_file    = $_SERVER["DOCUMENT_ROOT"] . '/first_apps/assets/file/pdf/' . $judul . '.pdf';

        $ready_message   = "";
        $ready_message   .= "<table border=0>
            <tr>
                <td style='width: 30%;'>Invoice</td>
                <td style='width: 10%;'> : </td>
                <td style='width: 60%;'> $header->invoice </td>
            </tr>
            <tr>
                <td style='width: 30%;'>Tgl/Jam</td>
                <td style='width: 10%;'> : </td>
                <td style='width: 60%;'>" . date('d-m-Y', strtotime($header->tgl_pembayaran)) . " / " . date('H:i:s', strtotime($header->jam_pembayaran)) . "</td>
            </tr>
            <tr>
                <td style='width: 30%;'>Pembeli</td>
                <td style='width: 10%;'> : </td>
                <td style='width: 60%;'>" . $this->M_global->getData('member', ['kode_member' => $jual->kode_member])->nama . "</td>
            </tr>
            <tr>
                <td style='width: 30%;'>Gudang</td>
                <td style='width: 10%;'> : </td>
                <td style='width: 60%;'>" . $this->M_global->getData('m_gudang', ['kode_gudang' => $jual->kode_gudang])->nama . "</td>
            </tr>
            <tr>
                <td style='width: 30%;'>Jumlah</td>
                <td style='width: 10%;'> : </td>
                <td style='width: 60%;'>Rp. " . number_format($header->total) . " </td>
            </tr>
        </table>";

        $server_subject = $judul;

        if ($this->email->send_my_email($email, $server_subject, $ready_message, $attched_file)) {
            echo json_encode(["status" => 1, 'result' => $attched_file]);
        } else {
            echo json_encode(["status" => 0]);
        }

        // echo json_encode($attched_file);
    }

    // fungsi cetak kwitansi
    public function print_kwitansi($token_pembayaran, $yes)
    {
        $kode_cabang          = $this->session->userdata('cabang');
        $web_setting          = $this->M_global->getData('web_setting', ['id' => 1]);

        $position             = 'P'; // cek posisi l/p

        // body cetakan
        $body                 = '';
        $body                 .= '<br><br>'; // beri jarak antara kop dengan body

        $pembayaran           = $this->M_global->getData('pembayaran', ['token_pembayaran' => $token_pembayaran]);
        $pendaftaran          = $this->M_global->getData('pendaftaran', ['no_trx' => $pembayaran->no_trx]);
        $barang_out_header    = $this->M_global->getData('barang_out_header', ['invoice' => $pembayaran->inv_jual]);
        $barang_out_detail    = $this->M_global->getDataResult('barang_out_detail', ['invoice' => $pembayaran->inv_jual]);
        $tarif_paket_pasien   = $this->M_global->getDataResult('tarif_paket_pasien', ['no_trx' => $pembayaran->no_trx]);
        $tarif_single_pasien  = $this->M_global->getDataResult('pembayaran_tarif_single', ['token_pembayaran' => $token_pembayaran]);
        $member               = $this->M_global->getData('member', ['kode_member' => (($pendaftaran) ? $pendaftaran->kode_member : $barang_out_header->kode_member)]);

        $judul                = 'Kwitansi ' . $pembayaran->invoice;
        $filename             = $judul;

        if ($pembayaran->approved == 1) {
            $open       = '<input type="checkbox" style="width: 80px;" checked="checked"> Lunas';
            $close      = '<input type="checkbox" style="width: 80px;"> Belum Lunas';
        } else {
            $open       = '<input type="checkbox" style="width: 80px;"> Lunas';
            $close      = '<input type="checkbox" style="width: 80px;" checked="checked"> Belum Lunas';
        }

        if ($pembayaran->cek_um == 1) {
            $umopen     = '<input type="checkbox" style="width: 80px;" checked="checked"> Uang Muka';
            $umclose    = '<input type="checkbox" style="width: 80px;"> Member';
        } else {
            if ((($pendaftaran) ? $pendaftaran->kode_member : $barang_out_header->kode_member) != 'U00001') {
                $umopen   = '<input type="checkbox" style="width: 80px;"> Uang Muka';
                $umclose  = '<input type="checkbox" style="width: 80px;" checked="checked"> Member';
            } else {
                $umopen   = '';
                $umclose  = '<input type="checkbox" style="width: 80px;" checked="checked"> Umum';
            }
        }

        $body .= '<table style="width: 100%; font-size: 9px;" cellpadding="2px">';

        $body .= '<tr>
            <td style="text-align: center;">' . date('d/m/Y') . ' ~ ' . date('H:i:s') . '</td>
        </tr>';

        $body .= '</table>';

        $body .= '<table style="width: 100%; font-size: 9px;" cellpadding="2px">';

        $body .= '<tr>
            <td style="width: 23%;">Invoice</td>
            <td style="width: 2%;">:</td>
            <td style="width: 75%;">' . $pembayaran->invoice . '</td>
        </tr>
        <tr>
            <td style="width: 23%;">Kasir</td>
            <td style="width: 2%;">:</td>
            <td style="width: 75%;">' . $this->M_global->getData('user', ['kode_user' => $pembayaran->kode_user])->nama . '</td>
        </tr>
        <tr>
            <td style="width: 23%;">Member</td>
            <td style="width: 2%;">:</td>
            <td style="width: 75%;">' . $member->nama . ' ('.$member->jkel.', '.hitung_umur($member->tgl_lahir).')</td>
        </tr>
        <tr>
            <td style="width: 23%;">Alamat</td>
            <td style="width: 2%;">:</td>
            <td style="width: 75%;">'.$this->M_global->getData('m_provinsi', ['kode_provinsi' => $member->provinsi])->provinsi.', '.$this->M_global->getData('kabupaten', ['kode_kabupaten' => $member->kabupaten])->kabupaten.', '.$this->M_global->getData('kecamatan', ['kode_kecamatan' => $member->kecamatan])->kecamatan.'</td>
        </tr>
        <tr>
            <td style="width: 23%;"></td>
            <td style="width: 2%;"></td>
            <td style="width: 75%;">'.$member->desa.' ('.$member->kodepos.'), RT/RW ('.$member->rt.'/'.$member->rw.')</td>
        </tr>';

        $body .= '<tr>
            <td style="width: 100%;" colspan="3">&nbsp;</td>
        </tr>';

        $body .= '</table>';

        $body .= '<table style="width: 100%; font-size: 9px;" cellpadding="2px">';

        $body .= '<tbody>';

        $body .= '<tr>
            <td style="width: 80%; font-weight: bold;" colspan="3">Tarif Paket</td>
            <td style="width: 20%; text-align: right; font-weight: bold;">' . (!empty($tarif_paket_pasien) ? number_format($pembayaran->paket) : 0) . '</td>
        </tr>';

        $body .= '<tr>
            <td style="width: 100%;" colspan="4"><hr style="margin: 0px;"></td>
        </tr>';

        if (!empty($tarif_paket_pasien)) {
            foreach ($tarif_paket_pasien as $tpp) {
                $m_tarif = $this->M_global->getData('m_tarif', ['kode_tarif' => $tpp->kode_tarif]);
                $tarif_paket = $this->M_global->getData('tarif_paket', ['kode_tarif' => $tpp->kode_tarif, 'kunjungan' => $tpp->kunjungan, 'kode_cabang' => $kode_cabang]);
                $body .= '<tr>
                    <td style="width: 60%;" colspan="2">' . $m_tarif->kode_tarif . ' (' . $m_tarif->nama . ')' . '</td>
                    <td style="text-align: right; width: 20%;">@Kunj ' . number_format($tpp->kunjungan) . '</td>
                    <td style="text-align: right; width: 20%;">' . number_format(($tarif_paket->jasa_rs + $tarif_paket->jasa_dokter + $tarif_paket->jasa_pelayanan + $tarif_paket->jasa_poli)) . '</td>
                </tr>';
            }

            $body .= '<tr>
                <td style="width: 100%;" colspan="4"><hr style="margin: 0px;"></td>
            </tr>';
        }

        $disc_paket = 0;

        $body .= '<tr>
            <td style="width: 80%; font-weight: bold;" colspan="3">Tarif Single</td>
            <td style="width: 20%; text-align: right; font-weight: bold;">' . (!empty($tarif_single_pasien) ? number_format($pembayaran->single) : 0) . '</td>
        </tr>';

        if (!empty($tarif_single_pasien)) {
            foreach ($tarif_single_pasien as $tsp) {
                $m_tarif = $this->M_global->getData('m_tarif', ['kode_tarif' => $tsp->kode_tarif]);
                $body .= '<tr>
                    <td style="width: 40%;">' . $m_tarif->kode_tarif . ' (' . $m_tarif->nama . ')' . '</td>
                    <td style="text-align: right; width: 20%;">' . number_format($tsp->harga) . '</td>
                    <td style="text-align: right; width: 20%;">' . number_format($tsp->discrp) . '</td>
                    <td style="text-align: right; width: 20%;">' . number_format($tsp->jumlah) . '</td>
                </tr>';
            }

            $body .= '<tr>
                <td style="width: 100%;" colspan="4"><hr style="margin: 0px;"></td>
            </tr>';
        }

        $disc_single = $pembayaran->disc_single;

        $body .= '<tr>
            <td style="width: 80%; font-weight: bold;" colspan="3">Penjualan Obat</td>
            <td style="width: 20%; text-align: right; font-weight: bold;">' . (!empty($pembayaran) ? number_format($pembayaran->jual) : 0) . '</td>
        </tr>';

        if (!empty($barang_out_header)) {
            foreach ($barang_out_detail as $bod) {
                $barang = $this->M_global->getData('barang', ['kode_barang' => $bod->kode_barang]);
                $body .= '<tr>
                    <td style="width: 40%;">' . $barang->nama . '(' . $this->M_global->getData('m_satuan', ['kode_satuan' => $barang->kode_satuan])->keterangan . ')' . '</td>
                    <td style="text-align: right; width: 20%;">' . number_format($bod->qty) . ' @ ' . number_format($bod->harga) . '</td>
                    <td style="text-align: right; width: 20%;">' . number_format($bod->discrp) . '</td>
                    <td style="text-align: right; width: 20%;">' . number_format(($bod->jumlah + $bod->discrp)) . '</td>
                </tr>';
            }

            $body .= '<tr>
                <td style="width: 100%;" colspan="4"><hr style="margin: 0px;"></td>
            </tr>';

            $disc_jual = $barang_out_header->diskon;
        } else {
            $disc_jual = 0;
        }

        $body .= '</tbody>';

        $body .= '</table>';

        $body .= '<page_break>';
        

        $body .= '<table style="width: 100%; font-size: 9px; padding-top: 35vh;" cellpadding="2px">';

        $body .= '<tr>
            <td style="text-align: center;">' . date('d/m/Y') . ' ~ ' . date('H:i:s') . '</td>
        </tr>';

        $body .= '</table>';

        $body .= '<table style="width: 100%; font-size: 9px;" cellpadding="2px">';

        $body .= '<tr>
            <td style="width: 23%;">Invoice</td>
            <td style="width: 2%;">:</td>
            <td style="width: 75%;">' . $pembayaran->invoice . '</td>
        </tr>
        <tr>
            <td style="width: 23%;">Bayar</td>
            <td style="width: 2%;">:</td>
            <td style="width: 75%;">(Cash: Rp. ' . number_format($pembayaran->cash) . ') @ (Card: Rp. ' . number_format($pembayaran->card) . ')</td>
        </tr>
        <tr>
            <td style="width: 23%;">Status</td>
            <td style="width: 2%;">:</td>
            <td style="width: 75%;">' . $open . '&nbsp;&nbsp;' . $close . '</td>
        </tr>';

        if ((($pendaftaran) ? $pendaftaran->kode_member : $barang_out_header->kode_member) != 'U00001') {
            $body .= '<tr>
                <td style="width: 23%;">UM Pakai</td>
                <td style="width: 2%;">:</td>
                <td style="width: 75%;">Rp. ' . number_format($pembayaran->um_keluar) . '</td>
            </tr>';
        }

        $body .= '<tr>
            <td style="width: 100%;" colspan="3">&nbsp;</td>
        </tr>';

        $body .= '</table>';

        $body .= '<table style="width: 50%; font-size: 9px;" cellpadding="2px" autosize="1">
            <tr>
                <td style="width: 38%;">Total</td>
                <td style="width: 2%;">: </td>
                <td style="text-align: right; font-weight: bold; width: 60%;">' . number_format($pembayaran->paket + $pembayaran->single + $pembayaran->jual) . '</td>
            </tr>
            <tr>
                <td style="width: 38%;">Pembayaran</td>
                <td style="width: 2%;">: </td>
                <td style="text-align: right; font-weight: bold; width: 60%;">' . number_format($pembayaran->total) . '</td>
            </tr>
            <tr>
                <td style="width: 38%;">Kembalian</td>
                <td style="width: 2%;">: </td>
                <td style="text-align: right; font-weight: bold; width: 60%;">' . number_format($pembayaran->kembalian) . '</td>
            </tr>
            <tr>
                <td colspan="3">' . $umopen . '&nbsp;&nbsp;' . $umclose . '</td>
            </tr>
        </table>';

        cetak_pdf_small($judul, $body, 1, $position, $filename, $web_setting, $yes);
    }

    // fungsi aktif/non-aktif pembayaran
    public function actived_pembayaran($token_pembayaran, $batal)
    {
        $user_batal = $this->session->userdata('kode_user');
        $pembayaran = $this->M_global->getData('pembayaran', ['token_pembayaran' => $token_pembayaran]);

        if ($batal == 0) { // jika batal = 0
            if($pembayaran->cek_um > 0) {
                $um_masuk = $pembayaran->um_masuk;
                $this->db->query("UPDATE uang_muka SET uang_masuk = uang_masuk + $um_masuk, uang_sisa = uang_sisa + $um_masuk WHERE last_invoice = '$pembayaran->invoice'");
            }

            // update batal jadi 0
            $cek = [
                $this->M_global->updateData('pembayaran', ['approved' => 1, 'batal' => 0, 'tgl_batal' => null, 'jam_batal' => null, 'user_batal' => null], ['token_pembayaran' => $token_pembayaran]),
                $this->M_global->updateData('tarif_paket_pasien', ['status' => 1], ['no_trx' => $pembayaran->no_trx]),
            ];
        } else { // selain itu
            if($pembayaran->cek_um > 0) {
                $um_masuk = $pembayaran->um_masuk;
                $this->db->query("UPDATE uang_muka SET uang_masuk = uang_masuk - $um_masuk, uang_sisa = uang_sisa - $um_masuk WHERE last_invoice = '$pembayaran->invoice'");
            }

            // update batal jadi 1
            $cek = [
                $this->M_global->updateData('pembayaran', ['approved' => 0, 'batal' => 1, 'tgl_batal' => date('Y-m-d'), 'jam_batal' => date('H:i:s'), 'user_batal' => $user_batal], ['token_pembayaran' => $token_pembayaran]),
                $this->M_global->updateData('tarif_paket_pasien', ['status' => 0], ['no_trx' => $pembayaran->no_trx]),
            ];
        }

        if ($cek) { // jika fungsi cek berjalan
            // kirim status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirim status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi hapus pembayaran
    public function delPembayaran($token_pembayaran)
    {
        $pembayaran = $this->M_global->getData('pembayaran', ['token_pembayaran' => $token_pembayaran]);
        $jual       = $this->M_global->getData('barang_out_header', ['invoice' => $pembayaran->inv_jual]);

        if ($pembayaran->cek_um == 1) {
            $um_awal = $pembayaran;
            $total_awal = $um_awal->kembalian;

            updateUangMukaUpdate($jual->kode_member, $pembayaran->invoice, $pembayaran->tgl_pembayaran, $pembayaran->jam_pembayaran, 0, $total_awal);
        }

        $cek_retur = $this->M_global->getData('barang_out_retur_header', ['invoice' => $pembayaran->inv_jual]);

        if ($pembayaran->no_trx != null) {
            $this->M_global->updateData('member', ['status_regist' => 1], ['last_regist' => $pembayaran->no_trx]);
            $this->M_global->updateData('pendaftaran', ['status_trx' => 0], ['no_trx' => $pembayaran->no_trx]);
        }

        if ($cek_retur) {
            $kasir = $this->M_global->updateData('barang_out_retur_header', ['status_retur' => 0], ['invoice' => $pembayaran->inv_jual]);
        } else {
            if ($jual) {
                $kasir = $this->M_global->updateData('barang_out_header', ['status_jual' => 0], ['invoice' => $pembayaran->inv_jual]);
            } else {
                $kasir = '';
            }
        }

        if ($kasir) {
            $kasir = $kasir;
        } else {
            $kasir = '';
        }

        $cek = [
            $kasir,
            $this->M_global->delData('pembayaran', ['token_pembayaran' => $token_pembayaran]),
            $this->M_global->delData('pembayaran_tarif_single', ['token_pembayaran' => $token_pembayaran]),
            $this->M_global->delData('bayar_card_detail', ['token_pembayaran' => $token_pembayaran]),
            $this->M_global->updateData('tarif_paket_pasien', ['status' => 0], ['no_trx' => $pembayaran->no_trx]),
        ];

        if ($cek) {
            echo json_encode(['status' => 1]);
        } else {
            echo json_encode(['status' => 0]);
        }
    }

    // form kasir page
    public function form_kasir($param)
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        if ($param == '0') {
            $pembayaran     = null;
            $riwayat        = null;
            $bayar_detail   = null;
            $tarif_paket    = null;
            $single_tarif   = null;
            $penjualan      = null;
        } else {
            $bayar_detail   = $this->M_global->getDataResult('bayar_card_detail', ['token_pembayaran' => $param]);
            $pembayaran     = $this->M_global->getData('pembayaran', ['token_pembayaran' => $param]);
            $pendaftaran    = $this->M_global->getData('pendaftaran', ['no_trx' => $pembayaran->no_trx]);
            if (!empty($pendaftaran)) {
                $tarif_paket    = $this->M_global->getDataResult('tarif_paket_pasien', ['no_trx' => $pendaftaran->no_trx]);

                $kode_member    = $pendaftaran->kode_member;

                $riwayat        = $this->M_global->getDataResult('pendaftaran', ['kode_member' => $kode_member]);
                $single_tarif   = $this->M_global->getDataResult('pembayaran_tarif_single', ['token_pembayaran' => $param]);
                $penjualan      = $this->db->query("SELECT bo.*, b.nama AS nama_barang, s.keterangan AS nama_satuan FROM barang_out_detail bo JOIN barang b ON b.kode_barang = bo.kode_barang JOIN m_satuan s ON s.kode_satuan = bo.kode_satuan WHERE bo.invoice = '$pembayaran->inv_jual'")->result();
            } else {
                $tarif_paket    = null;
                $riwayat        = null;
                $single_tarif   = null;
                $penjualan      = null;
            }
        }

        $parameter = [
            $this->data,
            'judul'             => 'Pembayaran',
            'nama_apps'         => $web_setting->nama,
            'page'              => 'Pembayaran',
            'web'               => $web_setting,
            'web_version'       => $web_version->version,
            'list_data'         => '',
            'data_pembayaran'   => $pembayaran,
            'bayar_detail'      => $bayar_detail,
            'tarif_paket'       => $tarif_paket,
            'riwayat'           => $riwayat,
            'single_tarif'      => $single_tarif,
            'penjualan'         => $penjualan,
            'role'              => $this->M_global->getResult('m_role'),
        ];

        $this->template->load('Template/Content', 'Kasir/Form_pembayaran', $parameter);
    }

    // form retur page
    public function form_retur($param)
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        if ($param == '0') {
            $pembayaran     = null;
            $riwayat        = null;
            $bayar_detail   = null;
            $tarif_paket    = null;
            $single_tarif   = null;
            $penjualan      = null;
        } else {
            $bayar_detail   = $this->M_global->getDataResult('bayar_card_detail', ['token_pembayaran' => $param]);
            $pembayaran     = $this->M_global->getData('pembayaran', ['token_pembayaran' => $param]);
            $pendaftaran    = $this->M_global->getData('pendaftaran', ['no_trx' => $pembayaran->no_trx]);
            if (!empty($pendaftaran)) {
                $tarif_paket    = $this->M_global->getDataResult('tarif_paket_pasien', ['no_trx' => $pendaftaran->no_trx]);

                $kode_member    = $pendaftaran->kode_member;

                $riwayat        = $this->M_global->getDataResult('pendaftaran', ['kode_member' => $kode_member]);
                $single_tarif   = $this->M_global->getDataResult('pembayaran_tarif_single', ['token_pembayaran' => $param]);
                $penjualan      = $this->db->query("SELECT bo.*, b.nama AS nama_barang, s.keterangan AS nama_satuan FROM barang_out_detail bo JOIN barang b ON b.kode_barang = bo.kode_barang JOIN m_satuan s ON s.kode_satuan = bo.kode_satuan WHERE bo.invoice = '$pembayaran->inv_jual'")->result();
            } else {
                $tarif_paket    = null;
                $riwayat        = null;
                $single_tarif   = null;
                $penjualan      = null;
            }
        }

        $parameter = [
            $this->data,
            'judul'             => 'Pembayaran',
            'nama_apps'         => $web_setting->nama,
            'page'              => 'Pembayaran',
            'web'               => $web_setting,
            'web_version'       => $web_version->version,
            'list_data'         => '',
            'data_pembayaran'   => $pembayaran,
            'bayar_detail'      => $bayar_detail,
            'tarif_paket'       => $tarif_paket,
            'riwayat'           => $riwayat,
            'single_tarif'      => $single_tarif,
            'penjualan'         => $penjualan,
            'param2'            => $param2,
            'role'              => $this->M_global->getResult('m_role'),
        ];

        $this->template->load('Template/Content', 'Kasir/Form_pembayaran', $parameter);
    }

    public function getJual($invoice) {
        $barang_out = $this->db->query("SELECT bo.*, b.nama AS nama_barang, s.keterangan AS nama_satuan FROM barang_out_detail bo JOIN barang b ON b.kode_barang = bo.kode_barang JOIN m_satuan s ON s.kode_satuan = bo.kode_satuan WHERE bo.invoice = '$invoice'")->result();

        echo json_encode($barang_out);
    }

    public function getTarifSingle($kode_tarif)
    {
        $kode_cabang = $this->session->userdata('cabang');

        $tarif = $this->db->query("SELECT m.kode_tarif, m.nama, tj.jasa_rs, tj.jasa_dokter, tj.jasa_pelayanan, tj.jasa_poli FROM m_tarif m JOIN tarif_jasa tj USING(kode_tarif) WHERE tj.kode_cabang = '$kode_cabang' AND m.jenis = 1 AND m.kode_tarif = '$kode_tarif'")->row();

        $data = [
            'status'            => 1,
            'jasa_rs'           => $tarif->jasa_rs,
            'jasa_dokter'       => $tarif->jasa_dokter,
            'jasa_pelayanan'    => $tarif->jasa_pelayanan,
            'jasa_poli'         => $tarif->jasa_poli,
            'jasa_total'        => ($tarif->jasa_rs + $tarif->jasa_dokter + $tarif->jasa_pelayanan + $tarif->jasa_poli),
        ];

        echo json_encode($data);
    }

    public function getPaket($no_trx)
    {
        $kode_cabang = $this->session->userdata('cabang');
        $pendaftaran = $this->M_global->getData('pendaftaran', ['no_trx' => $no_trx]);
        $tarif = $this->M_global->getDataResult('tarif_paket_pasien', ['no_trx' => $no_trx]);
        $jual = $this->M_global->getData('barang_out_header', ['no_trx' => $no_trx]);

        if ($jual) {
            $invoice = $jual->invoice;
        } else {
            $invoice = '';
        }

        $data = [];
        foreach ($tarif as $t) {
            $m_tarif = $this->M_global->getData('tarif_paket', ['kode_tarif' => $t->kode_tarif, 'kunjungan' => $t->kunjungan, 'kode_cabang' => $kode_cabang]);
            $m_tarif2 = $this->M_global->getData('m_tarif', ['kode_tarif' => $t->kode_tarif]);
            $data[] = [
                'kode_tarif' => $m_tarif->kode_tarif,
                'nama_tarif' => $m_tarif2->nama,
                'kunjungan' => $t->kunjungan,
                'harga' => ($m_tarif->jasa_rs + $m_tarif->jasa_dokter + $m_tarif->jasa_pelayanan + $m_tarif->jasa_poli),
            ];
        }

        echo json_encode([['status' => 1, 'invoice' => $invoice, 'kode_member' => $pendaftaran->kode_member], $data]);
    }

    // fungsi get Info
    public function getInfoJual($inv_jual)
    {
        $data = $this->M_global->getData('barang_out_header', ['invoice' => $inv_jual]);
        $kode_member = $data->kode_member;

        if ($data) {
            echo json_encode([$data, ['kode_member' => $kode_member]]);
        } else {
            echo json_encode(['status' => 0, 'kode_member' => $kode_member]);
            if ($kode_member == '') {
                echo json_encode(['status' => 1]);
            } else {
                echo json_encode(['status' => 0, 'kode_member' => $kode_member]);
            }
        }
    }

    // fungsi get info um
    public function getInfoUM($kode_member)
    {
        $data = $this->M_global->getData('uang_muka', ['kode_member' => $kode_member]);

        if ($data) {
            echo json_encode($data);
        } else {
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi proses insert/update
    public function kasir_proses($param)
    {
        $kode_cabang            = $this->session->userdata('cabang');

        if ($param == 1) { // jika param 1
            // buat token dan invoice
            $token_pembayaran   = tokenKasir(30);
            $invoice            = _invoiceKasir($kode_cabang);
        } else { // selain itu
            // ambil token dan invoice dari inputan
            $token_pembayaran   = $this->input->post('token_pembayaran');
            $invoice            = $this->input->post('invoice');
        }

        // variable
        $no_trx                 = $this->input->post('no_trx');
        $jenis_pembayaran       = $this->input->post('jenis_pembayaran');
        $tgl_pembayaran         = $this->input->post('tgl_pembayaran');
        $jam_pembayaran         = $this->input->post('jam_pembayaran');
        $inv_jual               = $this->input->post('inv_jual');
        $kode_promo             = $this->input->post('kode_promo');
        $cek_um                 = $this->input->post('cek_um');
        $discpr_promo           = str_replace(',', '', $this->input->post('potongan_promo'));
        $paket                  = str_replace(',', '', $this->input->post('sumPaket'));
        $jual                   = str_replace(',', '', $this->input->post('sumJual'));
        $single                 = str_replace(',', '', $this->input->post('sumTarif'));
        $disc_single            = str_replace(',', '', $this->input->post('discTarif'));

        $kode_tarif             = $this->input->post('kode_tarif');
        $kunjungan              = $this->input->post('kunjungan');

        $kode_tarif_single      = $this->input->post('kode_tarif_single');
        $harga                  = $this->input->post('jasa_total');
        $discpr                 = $this->input->post('discpr_tarif');
        $discrp                 = $this->input->post('discrp_tarif');
        $jumlah                 = $this->input->post('jumlah_tarif');

        // query barang out header
        $cek_pendaftaran        = $this->M_global->getData('pendaftaran', ['no_trx' => $no_trx]);
        // ambil kode member
        if ($cek_pendaftaran) { // jika ada di barang out header
            $kode_member        = $cek_pendaftaran->kode_member;
            // ambil notrx nya
            $no_trx             = $cek_pendaftaran->no_trx;

            // update status_trx di pendaftaran menjadi 1
            $this->M_global->updateData('pendaftaran', ['status_trx' => 1, 'tgl_keluar' => $tgl_pembayaran, 'jam_keluar' => $jam_pembayaran], ['no_trx' => $no_trx]);
        } else { // selain itu
            // notrx null
            $kode_member        = null;
            $no_trx             = null;
        }

        // variable card
        $cash                   = str_replace(',', '', $this->input->post('cash'));
        $card                   = str_replace(',', '', $this->input->post('card'));
        $total                  = str_replace(',', '', $this->input->post('total'));
        $kembalian              = str_replace(',', '', $this->input->post('total_kurang'));
        $um_keluar              = str_replace(',', '', $this->input->post('um_keluar'));
        $kode_user              = $this->session->userdata('kode_user');

        // isi pembayaran
        $isi_pembayaran = [
            'kode_cabang'       => $kode_cabang,
            'token_pembayaran'  => $token_pembayaran,
            'approved'          => 1,
            'invoice'           => $invoice,
            'inv_jual'          => $inv_jual,
            'no_trx'            => $no_trx,
            'tgl_pembayaran'    => $tgl_pembayaran,
            'jam_pembayaran'    => $jam_pembayaran,
            'paket'             => $paket,
            'single'            => $single,
            'jual'              => $jual,
            'disc_single'       => $disc_single,
            'total'             => $total,
            'kode_user'         => $kode_user,
            'um_keluar'         => $um_keluar,
            'jenis_pembayaran'  => $jenis_pembayaran,
            'cash'              => $cash,
            'card'              => $card,
            'kode_promo'        => $kode_promo,
            'discpr_promo'      => $discpr_promo,
            'kembalian'         => $kembalian,
            'um_masuk'          => $kembalian,
            'cek_um'            => $cek_um,
        ];

        if ($param == 1) { // jika param = 1
            // insert ke pembayaran
            $update_um = $this->db->query("UPDATE uang_muka SET 
                last_tgl = '$tgl_pembayaran', 
                last_jam = '$jam_pembayaran', 
                last_invoice = '$invoice', 
                uang_keluar = uang_keluar + '$um_keluar', 
                uang_sisa = uang_sisa - '$um_keluar' 
            WHERE kode_member = '$kode_member'");

            if ($cek_um == 1) {
                updateUangMukaIn($kode_member, $invoice, $tgl_pembayaran, $jam_pembayaran, $kembalian);
            }

            $cek = [
                $this->M_global->insertData('pembayaran', $isi_pembayaran),
                $update_um,
            ];

            if (isset($kode_tarif)) {
                $jumPaket = count($kode_tarif);

                for ($x = 0; $x <= ($jumPaket - 1); $x++) {
                    $this->M_global->updateData('tarif_paket_pasien', ['status' => 1], ['no_trx' => $no_trx, 'kode_tarif' => $kode_tarif[$x], 'kunjungan' => $kunjungan[$x]]);
                }
            }
        } else { // selain itu
            if (isset($kode_tarif)) {
                $this->M_global->updateData('tarif_paket_pasien', ['status' => 0], ['no_trx' => $no_trx]);

                $jumPaket = count($kode_tarif);

                for ($x = 0; $x <= ($jumPaket - 1); $x++) {
                    $this->M_global->updateData('tarif_paket_pasien', ['status' => 1], ['no_trx' => $no_trx, 'kode_tarif' => $kode_tarif[$x], 'kunjungan' => $kunjungan[$x]]);
                }
            }

            $um_awal = $this->M_global->getData('pembayaran', ['invoice' => $invoice]);
            $total_awal = $um_awal->kembalian;

            updateUangMukaUpdate($kode_member, $invoice, $tgl_pembayaran, $jam_pembayaran, $kembalian, $total_awal);

            // update pembayaran dan hapus cardnya
            $cek = [
                $this->M_global->updateData('pembayaran', $isi_pembayaran, ['token_pembayaran' => $token_pembayaran]),
                $this->M_global->delData('bayar_card_detail', ['token_pembayaran' => $token_pembayaran]),
                $this->M_global->delData('pembayaran_tarif_single', ['token_pembayaran' => $token_pembayaran]),
            ];
        }

        if ($cek) { // jika fungsi cek berjalan
            // variable detail card
            $kode_bank    = $this->input->post('kode_bank');
            $tipe_bank    = $this->input->post('tipe_bank');
            $no_card      = $this->input->post('no_card');
            $approval     = $this->input->post('approval');
            $jumlah_card  = $this->input->post('jumlah_card');

            if (!empty($kode_bank)) { // jika kodebank exist/ ada
                // ambil jumlah row berdasarkan kode_bank
                $jum = count($kode_bank);

                // lakukan loop dengan for
                for ($x = 0; $x <= ($jum - 1); $x++) {
                    $_kode_bank   = $kode_bank[$x];
                    $_tipe_bank   = $tipe_bank[$x];
                    $_no_card     = $no_card[$x];
                    $_approval    = $approval[$x];
                    $_jumlah_card = str_replace(',', '', $jumlah_card[$x]);

                    // isi detail card
                    $isi_card = [
                        'token_pembayaran'  => $token_pembayaran,
                        'kode_bank'         => $_kode_bank,
                        'kode_tipe'         => $_tipe_bank,
                        'no_card'           => $_no_card,
                        'approval'          => $_approval,
                        'jumlah'            => $_jumlah_card,
                    ];

                    // insert ke bayar_card_detail
                    $this->M_global->insertData('bayar_card_detail', $isi_card);
                }
            }

            if (isset($kode_tarif_single)) {
                $jumTarif = count($kode_tarif_single);

                for ($y = 0; $y <= ($jumTarif - 1); $y++) {
                    $kode_single    = $kode_tarif_single[$y];
                    $harga_single   = str_replace(',', '', $harga[$y]);
                    $discpr_single  = str_replace(',', '', $discpr[$y]);
                    $discrp_single  = str_replace(',', '', $discrp[$y]);
                    $jumlah_single  = str_replace(',', '', $jumlah[$y]);

                    $data_tarif = [
                        'token_pembayaran'  => $token_pembayaran,
                        'kode_tarif'        => $kode_single,
                        'harga'             => $harga_single,
                        'discpr'            => $discpr_single,
                        'discrp'            => $discrp_single,
                        'jumlah'            => $jumlah_single,
                    ];

                    $this->M_global->insertData('pembayaran_tarif_single', $data_tarif);
                }
            }

            aktifitas_user_transaksi('Pembayaran', 'membayar Kasir', $invoice);

            // update barang_out_header dan member
            $this->M_global->updateData(
                'barang_out_header',
                ['status_jual' => 1],
                ['invoice' => $inv_jual]
            );

            $this->M_global->updateData(
                'member',
                ['status_regist' => 0],
                ['kode_member' => $kode_member]
            );

            $this->print_kwitansi($token_pembayaran, 1);

            // kirim status 1 ke view
            echo json_encode(['status' => 1, 'token_pembayaran' => $token_pembayaran]);
        } else { // salain itu
            // kirim status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    /*
    * Retur Kasir Laporan
    **/

    // pembayaran report page
    public function report()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter = [
            $this->data,
            'judul'         => 'Pembayaran',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Laporan Kasir',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => '',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Kasir/Laporan', $parameter);
    }

    // print report
    public function report_print($param)
    {
        // param website
        $web_setting    = $this->M_global->getData('web_setting', ['id' => 1]);

        $position       = 'P'; // cek posisi l/p

        // body cetakan
        $body           = '';
        $body           .= '<br><br>'; // beri jarak antara kop dengan body

        // parameter dari view laporan
        $pencetak       = $this->M_global->getData('user', ['kode_user' => $this->session->userdata('kode_user')])->nama;
        $laporan        = $this->input->get('laporan');
        $dari           = $this->input->get('dari');
        $sampai         = $this->input->get('sampai');
        $kode_user      = $this->input->get('kode_user');

        $breaktable     = '<br>';

        if ($laporan == 1) {
            $file = 'Laporan Penjualan Kasir';
            $position = 'L';

            // isi body
            $detail = $this->db->query("SELECT p.* FROM pembayaran p WHERE p.tgl_pembayaran>= '$dari' AND p.tgl_pembayaran <= '$sampai' AND p.kode_user = '$kode_user' AND inv_jual IN (SELECT invoice FROM barang_out_header)")->result();

            // body header
            $body .= '<table style="width: 100%; font-size: 11px;">
                <tr>
                    <td style="width: 10%;">Perihal</td>
                    <td style="width: 2%;"> : </td>
                    <td colspan="2">' . $file . '</td>
                </tr>
                <tr>
                    <td style="width: 10%;">Periode</td>
                    <td style="width: 2%;"> : </td>
                    <td colspan="2">' . date('d-m-Y', strtotime($dari)) . ' ~ ' . date('d-m-Y', strtotime($sampai)) . '</td>
                </tr>
                <tr>
                    <td style="width: 10%;">Kasir</td>
                    <td style="width: 2%;"> : </td>
                    <td colspan="2">' . $this->M_global->getData('user', ['kode_user' => $kode_user])->nama . '</td>
                </tr>
            </table>';

            $body .= $breaktable;

            $tipe_bank = $this->M_global->getResult('tipe_bank');

            $body .= '<table style="width: 100%; font-size: 12px;" cellpadding="5px">';
            $body .= '<thead>';

            $body .= '<tr>
                <th rowspan="2" style="width: 5%; border: 1px solid black; background-color: red; color: white;">#</th>
                <th rowspan="2" style="width: 15%; border: 1px solid black; background-color: red; color: white;">Kwitansi</th>
                <th rowspan="2" style="width: 20%; border: 1px solid black; background-color: red; color: white;">Member</th>
                <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: red; color: white;">Jumlah Bayar</th>
                <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: red; color: white;">Cash</th>
                <th colspan="' . count($tipe_bank) . '" style="width: ' . count($tipe_bank) . '0%; border: 1px solid black; background-color: red; color: white;">Card</th>
                <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: red; color: white;">Kembalian</th>
                <th colspan="3" style="width: 30%; border: 1px solid black; background-color: red; color: white;">Promo</th>
                <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: red; color: white;">Jual</th>
                <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: red; color: white;">Total</th>
            </tr>';

            $body .= '<tr>';

            foreach ($tipe_bank as $tb) {
                $body .= '<th style="width: 10%; border: 1px solid black; background-color: red; color: white;">' . $tb->keterangan . '</th>';
            }

            $body .= '<th style="width: 10%; border: 1px solid black; background-color: red; color: white;">Nama</th>';
            $body .= '<th style="width: 10%; border: 1px solid black; background-color: red; color: white;">Potongan (%)</th>';
            $body .= '<th style="width: 10%; border: 1px solid black; background-color: red; color: white;">Subtotal (Rp)</th>';

            $body .= '</tr>';

            $body .= '</thead>';
            $body .= '<tbody>';

            if (count($detail) < 1) {
                $body .= '<tr>
                    <td colspan="15" style="width: 5%; border: 1px solid black; color: red; font-weight: bold; text-align: center;">Tidak Ada Transaksi</td>
                </tr>';
            } else {
                $no = 1;
                foreach ($detail as $d) {
                    $cek_member = $this->M_global->getData('barang_out_header', ['invoice' => $d->inv_jual]);

                    if ($cek_member) {
                        $member = $this->M_global->getData('member', ['kode_member' => $cek_member->kode_member])->nama;
                    } else {
                        $member = 'Masyarakat Umum';
                    }

                    if ($param == 1) {
                        $total = number_format($d->total);
                        $cash = number_format($d->cash);
                        $kembalian = number_format($d->kembalian);
                        $result = number_format($d->total - $d->kembalian);
                    } else {
                        $total = ceil($d->total);
                        $cash = ceil($d->cash);
                        $kembalian = ceil($d->kembalian);
                        $result = ceil($d->total - $d->kembalian);
                    }

                    $body .= '<tr>';

                    $body .= '<td style="border: 1px solid black; text-align: right;">' . $no . '</td>
                        <td style="border: 1px solid black;">' . $d->invoice . '</td>
                        <td style="border: 1px solid black;">' . $cek_member->kode_member . ' ~ ' . $member . '</td>
                        <td style="border: 1px solid black; text-align: right;">' . $total . '</td>
                        <td style="border: 1px solid black; text-align: right;">' . $cash . '</td>';

                    foreach ($tipe_bank as $tb) {
                        $card_detail = $this->M_global->getDataResult('bayar_card_detail', ['token_pembayaran' => $d->token_pembayaran, 'kode_tipe' => $tb->kode_tipe]);
                        if (count($card_detail) > 0) {
                            foreach ($card_detail as $cd) {
                                if ($param == 1) {
                                    $jumlah = number_format($cd->jumlah);
                                } else {
                                    $jumlah = ceil($cd->jumlah);
                                }

                                $body .= '<td style="border: 1px solid black; text-align: right;">' . $jumlah . '</td>';
                            }
                        } else {
                            $body .= '<td style="border: 1px solid black; text-align: right;">0.00</td>';
                        }
                    }

                    $promo = $this->M_global->getData('m_promo', ['kode_promo' => $d->kode_promo]);
                    if ($promo) {
                        $jual             = $this->M_global->getData('barang_out_header', ['invoice' => $d->inv_jual]);
                        $total_jual       = $jual->total;

                        $nama_promo       = $promo->nama;
                        $potongan_promo   = $promo->discpr;
                        $subtotal_promo   = ($total_jual * ($promo->discpr / 100));
                    } else {
                        $nama_promo     = '';
                        $total_jual     = 0;
                        $potongan_promo = 0;
                        $subtotal_promo = 0;
                    }

                    if ($param == 1) {
                        $tjual = number_format($total_jual);
                        $pprom = number_format($potongan_promo);
                        $sprom = number_format($subtotal_promo);
                    } else {
                        $tjual = ceil($total_jual);
                        $pprom = ceil($potongan_promo);
                        $sprom = ceil($subtotal_promo);
                    }

                    $body .= '<td style="border: 1px solid black; text-align: right;">' . $kembalian . '</td>';
                    $body .= '<td style="border: 1px solid black; text-align: right;">' . $nama_promo . '</td>';
                    $body .= '<td style="border: 1px solid black; text-align: right;">' . $pprom . '</td>';
                    $body .= '<td style="border: 1px solid black; text-align: right;">' . $sprom . '</td>';
                    $body .= '<td style="border: 1px solid black; text-align: right;">' . $tjual . '</td>';
                    $body .= '<td style="border: 1px solid black; text-align: right;">' . $result . '</td>';


                    $body .= '</tr>';

                    $no++;
                }
            }

            $body .= '</tbody>';
            $body .= '</table>';
        } else if ($laporan == 2) {
            $file = 'Laporan Retur Penjualan Kasir';

            // isi body
            $detail = $this->db->query("SELECT p.* FROM pembayaran p WHERE p.tgl_pembayaran>= '$dari' AND p.tgl_pembayaran <= '$sampai' AND p.kode_user = '$kode_user' AND inv_jual IN (SELECT invoice FROM barang_out_retur_header)")->result();

            // body header
            $body .= '<table style="width: 100%; font-size: 11px;">
                <tr>
                    <td style="width: 10%;">Perihal</td>
                    <td style="width: 2%;"> : </td>
                    <td colspan="2">' . $file . '</td>
                </tr>
                <tr>
                    <td style="width: 10%;">Periode</td>
                    <td style="width: 2%;"> : </td>
                    <td colspan="2">' . date('d-m-Y', strtotime($dari)) . ' ~ ' . date('d-m-Y', strtotime($sampai)) . '</td>
                </tr>
                <tr>
                    <td style="width: 10%;">Kasir</td>
                    <td style="width: 2%;"> : </td>
                    <td colspan="2">' . $this->M_global->getData('user', ['kode_user' => $kode_user])->nama . '</td>
                </tr>
            </table>';

            $body .= $breaktable;

            $tipe_bank = $this->M_global->getResult('tipe_bank');

            $body .= '<table style="width: 100%; font-size: 10px;" cellpadding="5px">';
            $body .= '<thead>';

            $body .= '<tr>
                <th style="width: 5%; border: 1px solid black; background-color: red; color: white;">#</th>
                <th style="width: 10%; border: 1px solid black; background-color: red; color: white;">Kwitansi</th>
                <th style="width: 10%; border: 1px solid black; background-color: red; color: white;">Jumlah Bayar</th>
            </tr>';

            $body .= '</thead>';

            $body .= '<tbody>';

            if (count($detail) < 1) {
                $body .= '<tr>
                    <td colspan="3" style="width: 5%; border: 1px solid black; color: red; font-weight: bold; text-align: center;">Tidak Ada Transaksi</td>
                </tr>';
            } else {
                $no = 1;
                foreach ($detail as $d) {
                    $cek_member = $this->M_global->getData('barang_out_retur_header', ['invoice' => $d->inv_jual]);

                    if ($param == 1) {
                        $kembalian = number_format($d->kembalian);
                    } else {
                        $kembalian = ceil($d->kembalian);
                    }

                    $body .= '<tr>';

                    $body .= '<td style="border: 1px solid black; text-align: right;">' . $no . '</td>
                        <td style="border: 1px solid black;">' . $d->invoice . '</td>
                        <td style="border: 1px solid black; text-align: right;">' . $kembalian . '</td>
                    </tr>';

                    $no++;
                }
            }

            $body .= '</tbody>';

            $body .= '</table>';
        } else {
            $file = 'Laporan Penjualan Poli';
            $position = 'L';

            // isi body
            $detail = $this->db->query("SELECT p.*,
            IF(boh.kode_poli = 'K00001', boh.total, 0) AS kulit,
            IF(boh.kode_poli = 'U00001', boh.total, 0) AS umum,
            IF(boh.kode_poli = 'G00001', boh.total, 0) AS gigi,
            IF(boh.kode_poli = 'S00001', boh.total, 0) AS spa,
            IF(boh.kode_poli = 'T00001', boh.total, 0) AS tht
            FROM pembayaran p
            JOIN barang_out_header boh ON p.inv_jual = boh.invoice 
            WHERE p.tgl_pembayaran>= '$dari' AND p.tgl_pembayaran <= '$sampai'
            AND p.kode_user = '$kode_user'")->result();

            // body header
            $body .= '<table style="width: 100%; font-size: 11px;">
                <tr>
                    <td style="width: 10%;">Perihal</td>
                    <td style="width: 2%;"> : </td>
                    <td colspan="2">' . $file . '</td>
                </tr>
                <tr>
                    <td style="width: 10%;">Periode</td>
                    <td style="width: 2%;"> : </td>
                    <td colspan="2">' . date('d-m-Y', strtotime($dari)) . ' ~ ' . date('d-m-Y', strtotime($sampai)) . '</td>
                </tr>
                <tr>
                    <td style="width: 10%;">Kasir</td>
                    <td style="width: 2%;"> : </td>
                    <td colspan="2">' . $this->M_global->getData('user', ['kode_user' => $kode_user])->nama . '</td>
                </tr>
            </table>';

            $body .= $breaktable;

            $body .= '<table style="width: 100%; font-size: 12px;" cellpadding="5px">';
            $body .= '<thead>';

            $poli = $this->M_global->getResult('m_poli');

            $body .= '<tr>
                <th rowspan="2" style="width: 5%; border: 1px solid black; background-color: red; color: white;">#</th>
                <th rowspan="2" style="width: 15%; border: 1px solid black; background-color: red; color: white;">Kwitansi</th>
                <th rowspan="2" style="width: 20%; border: 1px solid black; background-color: red; color: white;">Member</th>
                <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: red; color: white;">Jumlah Bayar</th>
                <th colspan="' . count($poli) . '" style="width: ' . count($poli) . '0%; border: 1px solid black; background-color: red; color: white;">Poli</th>
                <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: red; color: white;">Kembalian</th>
                <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: red; color: white;">Total</th>
            </tr>
            <tr>';

            foreach ($poli as $p) {
                $body .= '<th style="width: 10%; border: 1px solid black; background-color: red; color: white;">' . $p->keterangan . '</th>';
            }

            $body .= '</tr>';

            $body .= '</thead>';

            $body .= '<tbody>';

            $no       = 1;
            $_umum    = 0;
            $_kulit   = 0;
            $_spa     = 0;
            $_gigi    = 0;
            $_tht     = 0;
            foreach ($detail as $d) {
                $cek_member = $this->M_global->getData('barang_out_header', ['invoice' => $d->inv_jual]);

                if ($cek_member) {
                    $member = $this->M_global->getData('member', ['kode_member' => $cek_member->kode_member])->nama;
                } else {
                    $member = 'Masyarakat Umum';
                }

                $_umum    += $d->umum;
                $_kulit   += $d->kulit;
                $_spa     += $d->spa;
                $_gigi    += $d->gigi;
                $_tht     += $d->tht;

                if ($param == 1) {
                    $total        = number_format($d->total);
                    $cash         = number_format($d->cash);
                    $kembalian    = number_format($d->kembalian);
                    $result       = number_format($d->total - $d->kembalian);

                    $umum         = number_format($d->umum);
                    $kulit        = number_format($d->kulit);
                    $spa          = number_format($d->spa);
                    $gigi         = number_format($d->gigi);
                    $tht          = number_format($d->tht);

                    $_umum_       = number_format($_umum);
                    $_kulit_      = number_format($_kulit);
                    $_spa_        = number_format($_spa);
                    $_gigi_       = number_format($_gigi);
                    $_tht_        = number_format($_tht);
                } else {
                    $total        = ceil($d->total);
                    $cash         = ceil($d->cash);
                    $kembalian    = ceil($d->kembalian);
                    $result       = ceil($d->total - $d->kembalian);

                    $umum         = ceil($d->umum);
                    $kulit        = ceil($d->kulit);
                    $spa          = ceil($d->spa);
                    $gigi         = ceil($d->gigi);
                    $tht          = ceil($d->tht);

                    $_umum_       = ceil($_umum);
                    $_kulit_      = ceil($_kulit);
                    $_spa_        = ceil($_spa);
                    $_gigi_       = ceil($_gigi);
                    $_tht_        = ceil($_tht);
                }

                $body .= '<tr>
                    <td style="border: 1px solid black; text-align: right;">' . $no . '</td>
                    <td style="border: 1px solid black;">' . $d->invoice . '</td>
                    <td style="border: 1px solid black;">' . $cek_member->kode_member . ' ~ ' . $member . '</td>
                    <td style="border: 1px solid black; text-align: right;">' . $total . '</td>
                    <td style="border: 1px solid black; text-align: right;">' . $umum . '</td>
                    <td style="border: 1px solid black; text-align: right;">' . $kulit . '</td>
                    <td style="border: 1px solid black; text-align: right;">' . $spa . '</td>
                    <td style="border: 1px solid black; text-align: right;">' . $gigi . '</td>
                    <td style="border: 1px solid black; text-align: right;">' . $tht . '</td>
                    <td style="border: 1px solid black; text-align: right;">' . $kembalian . '</td>
                    <td style="border: 1px solid black; text-align: right;">' . $result . '</td>
                </tr>';

                $no++;
            }


            $body .= '</tbody>';

            $body .= '<tfoot>';

            $body .= '<tr>
                <th colspan="4" style="width: 5%; border: 1px solid black; background-color: red; color: white;">Total</th>
                <th style="width: 10%; border: 1px solid black; background-color: red; color: white; text-align: right;">' . $_umum_ . '</th>
                <th style="width: 10%; border: 1px solid black; background-color: red; color: white; text-align: right;">' . $_kulit_ . '</th>
                <th style="width: 10%; border: 1px solid black; background-color: red; color: white; text-align: right;">' . $_spa_ . '</th>
                <th style="width: 10%; border: 1px solid black; background-color: red; color: white; text-align: right;">' . $_gigi_ . '</th>
                <th style="width: 10%; border: 1px solid black; background-color: red; color: white; text-align: right;">' . $_tht_ . '</th>
                <th style="width: 10%; border: 1px solid black; background-color: red; color: white; text-align: right;">Kembalian</th>
                <th style="width: 10%; border: 1px solid black; background-color: red; color: white; text-align: right;">Total</th>
            </tr>';

            $body .= '</tfoot>';

            $body .= '</table>';
        }

        $judul = $file . ' Periode: ' . date('d-m-Y', strtotime($dari)) . ' ~ ' . date('d-m-Y', strtotime($sampai));
        $filename = $file; // nama file yang ingin di simpan

        // jalankan fungsi cetak_pdf
        cetak_pdf($judul, $body, $param, $position, $filename, $web_setting);
    }

    // report_um page
    public function report_um()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter = [
            $this->data,
            'judul'         => 'Pembayaran',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Pembayaran',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Kasir/uangmuka_list/',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Kasir/Uangmuka', $parameter);
    }

    // fungsi list uang muka
    public function uangmuka_list($param1 = '')
    {
        // parameter untuk list table
        $table            = 'uang_muka';
        $colum            = ['id', 'last_tgl', 'last_jam', 'last_invoice', 'kode_member', 'uang_masuk', 'uang_keluar', 'uang_sisa'];
        $order            = 'id';
        $order2           = 'desc';
        $order_arr        = ['id' => 'asc'];
        $kondisi_param1   = '';

        // table server side tampung kedalam variable $list
        $list             = $this->M_datatables->get_datatables($table, $colum, $order_arr, $order, $order2, $param1, $kondisi_param1);
        $data             = [];
        $no               = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {
            $row    = [];
            $row[]  = $no++;
            $row[]  = $rd->kode_member . ' ~ ' . $this->M_global->getData('member', ['kode_member' => $rd->kode_member])->nama;
            $row[]  = date('d/m/Y', strtotime($rd->last_tgl)) . ' ~ ' . date('H:i:s', strtotime($rd->last_jam));
            $row[]  = $rd->last_invoice;
            $row[]  = 'Rp. <span class="float-right">' . number_format($rd->uang_masuk) . '</sp>';
            $row[]  = 'Rp. <span class="float-right">' . number_format($rd->uang_keluar) . '</sp>';
            $row[]  = 'Rp. <span class="float-right">' . number_format($rd->uang_sisa) . '</sp>';
            $data[] = $row;
        }

        // hasil server side
        $output = [
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->M_datatables->count_all($table, $colum, $order_arr, $order, $order2, $param1, $kondisi_param1),
            "recordsFiltered" => $this->M_datatables->count_filtered($table, $colum, $order_arr, $order, $order2, $param1, $kondisi_param1),
            "data"            => $data,
        ];

        // kirimkan ke view
        echo json_encode($output);
    }

    // deposit_um page
    public function deposit_um()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter = [
            $this->data,
            'judul'         => 'Pembayaran',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Pembayaran',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Kasir/uangmukadepo_list/',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Kasir/UangmukaDepo', $parameter);
    }

    // fungsi list uangmukadepo_list
    public function uangmukadepo_list($param1 = 1, $param2 = '')
    {
        // parameter untuk list table
        $table            = 'pembayaran_uangmuka';
        $colum            = ['id', 'invoice', 'tgl_pembayaran', 'jam_pembayaran', 'kode_member', 'jenis_pembayaran', 'cash', 'card', 'total'];
        $order            = 'id';
        $order2           = 'desc';
        $order_arr        = ['id' => 'asc'];
        $kondisi_param2   = '';
        $kondisi_param1   = 'tgl_pembayaran';

        // kondisi role
        $updated          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;
        $confirmed        = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->confirmed;

        // table server side tampung kedalam variable $list
        $dat    = explode("~", $param1);

        if ($dat[0] == 1) {
            $bulan        = date('m');
            $tahun        = date('Y');
            $type         = 1;
        } else {
            $bulan        = date('Y-m-d', strtotime($dat[1]));
            $tahun        = date('Y-m-d', strtotime($dat[2]));
            $type         = 2;
        }

        $list             = $this->M_datatables2->get_datatables($table, $colum, $order_arr, $order, $order2, $kondisi_param1, $type, $bulan, $tahun, $param2, $kondisi_param2);

        $data             = [];
        $no               = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {
            if ($updated > 0) {
                $upd_diss = '';
            } else {
                $upd_diss = 'disabled';
            }

            if ($deleted > 0) {
                $del_diss = '';
            } else {
                $del_diss = 'disabled';
            }

            $row    = [];
            $row[]  = $no++;
            $row[]  = $rd->invoice;
            $row[]  = date('d/m/Y', strtotime($rd->tgl_pembayaran)) . ' ~ ' . date('H:i:s', strtotime($rd->jam_pembayaran));
            $row[]  = $rd->kode_member . ' ~ ' . $this->M_global->getData('member', ['kode_member' => $rd->kode_member])->nama;
            $row[]  = ($rd->jenis_pembayaran == 0 ? 'CASH' : (($rd->jenis_pembayaran == 1) ? 'CARD' : 'CASH & CARD'));
            $row[]  = 'Rp. <span class="float-right">' . number_format($rd->total) . '</span>';
            $row[]  = '<div class="text-center">
                <button type="button" style="margin-bottom: 5px;" class="btn btn-warning" title="Ubah" onclick="ubah(' . "'" . $rd->invoice . "'" . ')" ' . $upd_diss . '><i class="fa-regular fa-pen-to-square"></i></button>
                <button type="button" style="margin-bottom: 5px;" class="btn btn-danger" title="Hapus" onclick="hapus(' . "'" . $rd->invoice . "'" . ')" ' . $del_diss . '><i class="fa-regular fa-circle-xmark"></i></button>
            </div>';
            $data[] = $row;
        }

        // hasil server side
        $output = [
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->M_datatables2->count_all($table, $colum, $order_arr, $order, $order2, $kondisi_param1, $type, $bulan, $tahun, $param2, $kondisi_param2),
            "recordsFiltered" => $this->M_datatables2->count_filtered($table, $colum, $order_arr, $order, $order2, $kondisi_param1, $type, $bulan, $tahun, $param2, $kondisi_param2),
            "data"            => $data,
        ];

        // kirimkan ke view
        echo json_encode($output);
    }

    // form uangmuka page
    public function form_uangmuka($param)
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        if ($param == '0') {
            $pembayaran     = null;
            $bayar_detail   = null;
        } else {
            $bayar_detail   = $this->M_global->getDataResult('bayar_um_card_detail', ['invoice' => $param]);
            $pembayaran     = $this->M_global->getData('pembayaran_uangmuka', ['invoice' => $param]);
        }

        $parameter = [
            $this->data,
            'judul'             => 'Pembayaran',
            'nama_apps'         => $web_setting->nama,
            'page'              => 'Pembayaran Uang Muka',
            'web'               => $web_setting,
            'web_version'       => $web_version->version,
            'list_data'         => '',
            'data_pembayaran'   => $pembayaran,
            'bayar_detail'      => $bayar_detail,
        ];

        $this->template->load('Template/Content', 'Kasir/Form_pembayaran_um', $parameter);
    }

    // fungsi proses insert/update
    public function um_proses($param)
    {
        if ($param == 1) { // jika param 1
            // buat invoice
            $invoice            = _invoiceDepoUM();
        } else { // selain itu
            // ambil invoice dari inputan
            $invoice            = $this->input->post('invoice');
        }

        // variable
        $jenis_pembayaran       = $this->input->post('jenis_pembayaran');
        $tgl_pembayaran         = $this->input->post('tgl_pembayaran');
        $jam_pembayaran         = $this->input->post('jam_pembayaran');
        $kode_member            = $this->input->post('kode_member');

        // variable card
        $cash                   = str_replace(',', '', $this->input->post('cash'));
        $card                   = str_replace(',', '', $this->input->post('card'));
        $total                  = str_replace(',', '', $this->input->post('total'));
        $kode_user              = $this->session->userdata('kode_user');

        // isi pembayaran
        $isi_pembayaran = [
            'invoice'           => $invoice,
            'kode_member'       => $kode_member,
            'tgl_pembayaran'    => $tgl_pembayaran,
            'jam_pembayaran'    => $jam_pembayaran,
            'total'             => $total,
            'kode_user'         => $kode_user,
            'jenis_pembayaran'  => $jenis_pembayaran,
            'cash'              => $cash,
            'card'              => $card,
        ];


        if ($param == 1) { // jika param = 1
            // insert ke pembayaran_uangmuka
            $cek = $this->M_global->insertData('pembayaran_uangmuka', $isi_pembayaran);

            updateUangMukaIn($kode_member, $invoice, $tgl_pembayaran, $jam_pembayaran, $total);
        } else { // selain itu

            $um_awal = $this->M_global->getData('pembayaran_uangmuka', ['invoice' => $invoice]);
            $total_awal = $um_awal->total;

            updateUangMukaUpdate($kode_member, $invoice, $tgl_pembayaran, $jam_pembayaran, $total, $total_awal);

            // update pembayaran_uangmuka dan hapus cardnya
            $cek = [
                $this->M_global->updateData('pembayaran_uangmuka', $isi_pembayaran, ['invoice' => $invoice]),
                $this->M_global->delData('bayar_um_card_detail', ['invoice' => $invoice])
            ];
        }


        if ($cek) { // jika fungsi cek berjalan
            // variable detail card
            $kode_bank    = $this->input->post('kode_bank');
            $tipe_bank    = $this->input->post('tipe_bank');
            $no_card      = $this->input->post('no_card');
            $approval     = $this->input->post('approval');
            $jumlah_card  = $this->input->post('jumlah_card');

            if (!empty($kode_bank)) { // jika kodebank exist/ ada
                // ambil jumlah row berdasarkan kode_bank
                $jum = count($kode_bank);

                // lakukan loop dengan for
                for ($x = 0; $x <= ($jum - 1); $x++) {
                    $_kode_bank   = $kode_bank[$x];
                    $_tipe_bank   = $tipe_bank[$x];
                    $_no_card     = $no_card[$x];
                    $_approval    = $approval[$x];
                    $_jumlah_card = str_replace(',', '', $jumlah_card[$x]);

                    // isi detail card
                    $isi_card = [
                        'invoice'           => $invoice,
                        'kode_bank'         => $_kode_bank,
                        'kode_tipe'         => $_tipe_bank,
                        'no_card'           => $_no_card,
                        'approval'          => $_approval,
                        'jumlah'            => $_jumlah_card,
                    ];

                    // insert ke bayar_card_detail
                    $this->M_global->insertData('bayar_um_card_detail', $isi_card);
                }
            }

            // kirim status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // salain itu
            // kirim status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi hapus pembayaran UM
    public function delPembayaran_um($invoice)
    {
        $pembayaran = $this->M_global->getData('pembayaran_uangmuka', ['invoice' => $invoice]);

        updateUangMukaDelete($pembayaran->kode_member, $invoice, $pembayaran->tgl_pembayaran, $pembayaran->jam_pembayaran, $pembayaran->total);

        $cek = [
            $this->M_global->delData('pembayaran_uangmuka', ['invoice' => $invoice]),
            $this->M_global->delData('bayar_um_card_detail', ['invoice' => $invoice])
        ];

        if ($cek) {
            echo json_encode(['status' => 1]);
        } else {
            echo json_encode(['status' => 0]);
        }
    }
}
