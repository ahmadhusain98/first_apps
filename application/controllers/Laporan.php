<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan extends CI_Controller
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
                'menu'      => 'Laporan',
            ];
        } else { // selain itu
            // kirimkan kembali ke Auth
            redirect('Auth');
        }
    }

    /*
    * Pembelian Laporan
    **/

    // index page
    public function index()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter = [
            $this->data,
            'judul'         => 'Laporan',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Laporan',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => '',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Laporan/Data', $parameter);
    }

    function report_print($param)
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
        $kode_supplier  = $this->input->get('kode_supplier');
        $kode_gudang    = $this->input->get('kode_gudang');

        $breaktable     = '<br>';

        // PEMBELIAN
        if ($laporan == 1) {
            $file = 'Laporan Pembelian';

            // isi body
            $header = $this->M_global->getDataResult('barang_in_header', ['tgl_beli >= ' => $dari, 'tgl_beli <= ' => $sampai, 'is_valid' => 1]);

            // body header
            $body .= '<table style="width: 100%; font-size: 11px;">
                <tr>
                    <td style="width: 15%;">Perihal</td>
                    <td style="width: 2%;"> : </td>
                    <td colspan="2">' . $file . '</td>
                </tr>
                <tr>
                    <td style="width: 15%;">Periode</td>
                    <td style="width: 2%;"> : </td>
                    <td colspan="2">' . date('d-m-Y', strtotime($dari)) . ' ~ ' . date('d-m-Y', strtotime($sampai)) . '</td>
                </tr>
                <tr>
                    <td style="width: 15%;">Pemasok</td>
                    <td style="width: 2%;"> : </td>
                    <td colspan="2">' . $this->M_global->getData('m_supplier', ['kode_supplier' => $kode_supplier])->nama . '</td>
                </tr>
                <tr>
                    <td style="width: 15%;">Gudang</td>
                    <td style="width: 2%;"> : </td>
                    <td style="width: 33%;">' . $this->M_global->getData('m_gudang', ['kode_gudang' => $kode_gudang])->nama . '</td>
                    <td style="width: 50%; text-align: right;">Pencetak : ' . $pencetak . '</td>
                </tr>
            </table>';

            $body .= $breaktable;

            $body .= '<table style="width: 100%; font-size: 10px;" autosize="1" cellpadding="5px">';
            $body .= '<thead>
                <tr>
                    <th rowspan="2" style="width: 5%; border: 1px solid black; background-color: #0e1d2e; color: white;">#</th>
                    <th rowspan="2" style="width: 30%; border: 1px solid black; background-color: #0e1d2e; color: white;">Barang</th>
                    <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: #0e1d2e; color: white;">Harga</th>
                    <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: #0e1d2e; color: white;">Jumlah</th>
                    <th colspan="2" style="width: 20%; border: 1px solid black; background-color: #0e1d2e; color: white;">Diskon</th>
                    <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: #0e1d2e; color: white;">Pajak</th>
                    <th rowspan="2" style="width: 15%; border: 1px solid black; background-color: #0e1d2e; color: white;">Total</th>
                </tr>
                <tr>
                    <th style="width: 10%; border: 1px solid black; background-color: #0e1d2e; color: white;">%</th>
                    <th style="width: 10%; border: 1px solid black; background-color: #0e1d2e; color: white;">Rp</th>
                </tr>
            </thead>';

            $body .= '<tbody>';

            if ($header) {
                foreach ($header as $h) {
                    if ($param == 1) {
                        $total = number_format($h->total);
                    } else {
                        $total = ceil($h->total);
                    }
                    $body .= '<tr style="background-color: skyblue;">
                        <td colspan="6" style="border: 1px solid black; font-weight: bold;">No. Transaksi: ' . $h->invoice . '</td>
                        <td colspan="2" style="border: 1px solid black; font-weight: bold; text-align: right">' . $total . '</td>
                    </tr>';

                    // detail barang
                    $detail   = $this->M_global->getDataResult('barang_in_detail', ['invoice' => $h->invoice]);

                    $no       = 1;
                    $tdiskon  = 0;
                    $tpajak   = 0;
                    $ttotal   = 0;
                    foreach ($detail as $d) {
                        $tdiskon    += $d->discrp;
                        $tpajak     += $d->pajakrp;
                        $ttotal     += $d->jumlah;

                        if ($param == 1) {
                            $harga    = number_format($d->harga);
                            $qty      = number_format($d->qty);
                            $discpr   = number_format($d->discpr);
                            $discrp   = number_format($d->discrp);
                            $pajak    = number_format($d->pajakrp);
                            $jumlah   = number_format($d->jumlah);

                            $tdiskonx = number_format($tdiskon);
                            $tpajakx  = number_format($tpajak);
                            $ttotalx  = number_format($ttotal);
                        } else {
                            $harga    = ceil($d->harga);
                            $qty      = ceil($d->qty);
                            $discpr   = ceil($d->discpr);
                            $discrp   = ceil($d->discrp);
                            $pajak    = ceil($d->pajakrp);
                            $jumlah   = ceil($d->jumlah);

                            $tdiskonx = ceil($tdiskon);
                            $tpajakx  = ceil($tpajak);
                            $ttotalx  = ceil($ttotal);
                        }
                        $body .= '<tr>
                            <td style="border: 1px solid black;">' . $no . '</td>
                            <td style="border: 1px solid black;">' . $d->kode_barang . ' ~ ' . $this->M_global->getData('barang', ['kode_barang' => $d->kode_barang])->nama . '</td>
                            <td style="border: 1px solid black; text-align: right;">' . $harga . '</td>
                            <td style="border: 1px solid black; text-align: right;">' . $qty . '</td>
                            <td style="border: 1px solid black; text-align: right;">' . $discpr . '</td>
                            <td style="border: 1px solid black; text-align: right;">' . $discrp . '</td>
                            <td style="border: 1px solid black; text-align: right;">' . $pajak . '</td>
                            <td style="border: 1px solid black; text-align: right;">' . $jumlah . '</td>
                        </tr>';
                        $no++;
                    }
                    $body .= '<tr style="background-color: green;">
                        <td colspan="5" style="border: 1px solid black; font-weight: bold; color: white;">Total</td>
                        <td style="border: 1px solid black; font-weight: bold; color: white; text-align: right">' . $tdiskonx . '</td>
                        <td style="border: 1px solid black; font-weight: bold; color: white; text-align: right">' . $tpajakx . '</td>
                        <td style="border: 1px solid black; font-weight: bold; color: white; text-align: right">' . $ttotalx . '</td>
                    </tr>';
                }
            } else {
                $body .= '<tr>
                    <td colspan="8" style="border: 1px solid black; font-weight: bold; text-align: center;">Belum ada transaksi</td>
                </tr>';
            }


            $body .= '</tbody>';

            $body .= '<tfoot>
                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td colspan="3" style="text-align: center;">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="5" style="width:60%;">&nbsp;</td>
                    <td colspan="3" style="width:40%; text-align: center;">Yogyakarta, ' . date('d M Y') . '</td>
                </tr>
                <tr>
                    <td colspan="5" style="width:60%;">&nbsp;</td>
                    <td colspan="3" style="width:40%; text-align: center;">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="5" style="width:60%;">&nbsp;</td>
                    <td colspan="3" style="width:40%; text-align: center;">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="5" style="width:60%;">&nbsp;</td>
                    <td colspan="3" style="width:40%; text-align: center;">' . $pencetak . '</td>
                </tr>
            </tfoot>';

            $body .= '</table>';
        } else if ($laporan == 2) {
            $file = 'Laporan Retur Pembelian';

            // isi body
            $header = $this->M_global->getDataResult('barang_in_retur_header', ['tgl_beli >= ' => $dari, 'tgl_beli <= ' => $sampai]);

            // body header
            $body .= '<table style="width: 100%; font-size: 11px;">
                <tr>
                    <td style="width: 15%;">Perihal</td>
                    <td style="width: 2%;"> : </td>
                    <td colspan="2">' . $file . '</td>
                </tr>
                <tr>
                    <td style="width: 15%;">Periode</td>
                    <td style="width: 2%;"> : </td>
                    <td colspan="2">' . date('d-m-Y', strtotime($dari)) . ' ~ ' . date('d-m-Y', strtotime($sampai)) . '</td>
                </tr>
                <tr>
                    <td style="width: 15%;">Pemasok</td>
                    <td style="width: 2%;"> : </td>
                    <td colspan="2">' . $this->M_global->getData('m_supplier', ['kode_supplier' => $kode_supplier])->nama . '</td>
                </tr>
                <tr>
                    <td style="width: 15%;">Gudang</td>
                    <td style="width: 2%;"> : </td>
                    <td style="width: 33%;">' . $this->M_global->getData('m_gudang', ['kode_gudang' => $kode_gudang])->nama . '</td>
                    <td style="width: 50%; text-align: right;">Pencetak : ' . $pencetak . '</td>
                </tr>
            </table>';

            $body .= $breaktable;

            $body .= '<table style="width: 100%; font-size: 10px;" cellpadding="5px">';
            $body .= '<thead>
                <tr>
                    <th rowspan="2" style="width: 5%; border: 1px solid black; background-color: #0e1d2e; color: white;">#</th>
                    <th rowspan="2" style="width: 30%; border: 1px solid black; background-color: #0e1d2e; color: white;">Barang</th>
                    <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: #0e1d2e; color: white;">Harga</th>
                    <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: #0e1d2e; color: white;">Jumlah</th>
                    <th colspan="2" style="width: 20%; border: 1px solid black; background-color: #0e1d2e; color: white;">Diskon</th>
                    <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: #0e1d2e; color: white;">Pajak</th>
                    <th rowspan="2" style="width: 15%; border: 1px solid black; background-color: #0e1d2e; color: white;">Total</th>
                </tr>
                <tr>
                    <th style="width: 10%; border: 1px solid black; background-color: #0e1d2e; color: white;">%</th>
                    <th style="width: 10%; border: 1px solid black; background-color: #0e1d2e; color: white;">Rp</th>
                </tr>
            </thead>';

            $body .= '<tbody>';
            foreach ($header as $h) {
                if ($param == 1) {
                    $total = number_format($h->total);
                } else {
                    $total = ceil($h->total);
                }
                $body .= '<tr style="background-color: skyblue;">
                    <td colspan="6" style="border: 1px solid black; font-weight: bold;">No. Transaksi: ' . $h->invoice . '</td>
                    <td colspan="2" style="border: 1px solid black; font-weight: bold; text-align: right">' . $total . '</td>
                </tr>';

                // detail barang
                $detail   = $this->M_global->getDataResult('barang_in_retur_detail', ['invoice' => $h->invoice]);

                $no       = 1;
                $tdiskon  = 0;
                $tpajak   = 0;
                $ttotal   = 0;
                foreach ($detail as $d) {
                    $tdiskon += $d->discrp;
                    $tpajak += $d->pajakrp;
                    $ttotal += $d->jumlah;

                    if ($param == 1) {
                        $harga    = number_format($d->harga);
                        $qty      = number_format($d->qty);
                        $discpr   = number_format($d->discpr);
                        $discrp   = number_format($d->discrp);
                        $pajak    = number_format($d->pajakrp);
                        $jumlah   = number_format($d->jumlah);

                        $tdiskonx = number_format($tdiskon);
                        $tpajakx  = number_format($tpajak);
                        $ttotalx  = number_format($ttotal);
                    } else {
                        $harga    = ceil($d->harga);
                        $qty      = ceil($d->qty);
                        $discpr   = ceil($d->discpr);
                        $discrp   = ceil($d->discrp);
                        $pajak    = ceil($d->pajakrp);
                        $jumlah   = ceil($d->jumlah);

                        $tdiskonx = ceil($tdiskon);
                        $tpajakx  = ceil($tpajak);
                        $ttotalx  = ceil($ttotal);
                    }
                    $body .= '<tr>
                        <td style="border: 1px solid black;">' . $no . '</td>
                        <td style="border: 1px solid black;">' . $d->kode_barang . ' ~ ' . $this->M_global->getData('barang', ['kode_barang' => $d->kode_barang])->nama . '</td>
                        <td style="border: 1px solid black; text-align: right;">' . $harga . '</td>
                        <td style="border: 1px solid black; text-align: right;">' . $qty . '</td>
                        <td style="border: 1px solid black; text-align: right;">' . $discpr . '</td>
                        <td style="border: 1px solid black; text-align: right;">' . $discrp . '</td>
                        <td style="border: 1px solid black; text-align: right;">' . $pajak . '</td>
                        <td style="border: 1px solid black; text-align: right;">' . $jumlah . '</td>
                    </tr>';
                    $no++;
                }
                $body .= '<tr style="background-color: green    ;">
                    <td colspan="5" style="border: 1px solid black; font-weight: bold;">Total</td>
                    <td style="border: 1px solid black; font-weight: bold; text-align: right">' . $tdiskonx . '</td>
                    <td style="border: 1px solid black; font-weight: bold; text-align: right">' . $tpajakx . '</td>
                    <td style="border: 1px solid black; font-weight: bold; text-align: right">' . $ttotalx . '</td>
                </tr>';
            }
            $body .= '</tbody>';

            $body .= '<tfoot>
                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td colspan="3" style="text-align: center;">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td colspan="3" style="text-align: center;">Yogyakarta, ' . date('d M Y') . '</td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td colspan="3" style="text-align: center;">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td colspan="3" style="text-align: center;">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td colspan="3" style="text-align: center;">' . $pencetak . '</td>
                </tr>
            </tfoot>';

            $body .= '</table>';
        } else if ($laporan == 3) {
            $file = 'Laporan Stok Pembelian';

            $position = 'L';

            // isi body
            $detail = $this->M_global->getReportPembelian($dari, $sampai, $kode_gudang);

            // body header
            $body .= '<table style="width: 100%; font-size: 11px;">
                <tr>
                    <td style="width: 15%;">Perihal</td>
                    <td style="width: 2%;"> : </td>
                    <td colspan="2">' . $file . '</td>
                </tr>
                <tr>
                    <td style="width: 15%;">Periode</td>
                    <td style="width: 2%;"> : </td>
                    <td colspan="2">' . date('d-m-Y', strtotime($dari)) . ' ~ ' . date('d-m-Y', strtotime($sampai)) . '</td>
                </tr>
                <tr>
                    <td style="width: 15%;">Gudang</td>
                    <td style="width: 2%;"> : </td>
                    <td style="width: 33%;">' . $this->M_global->getData('m_gudang', ['kode_gudang' => $kode_gudang])->nama . '</td>
                    <td style="width: 50%; text-align: right;">Pencetak : ' . $pencetak . '</td>
                </tr>
            </table>';

            $body .= $breaktable;

            $body .= '<table style="width: 100%; font-size: 11px;" autosize="2.4" cellpadding="5px">';

            $body .= '<thead>
                <tr>
                    <th rowspan="2" style="width: 5%; text-align: center; border: 1px solid black; background-color: #0e1d2e; color: white;">#</th>
                    <th rowspan="2" style="width: 15%; text-align: center; border: 1px solid black; background-color: #0e1d2e; color: white;">Tgl/Jam</th>
                    <th rowspan="2" style="width: 15%; text-align: center; border: 1px solid black; background-color: #0e1d2e; color: white;">Keterangan</th>
                    <th rowspan="2" style="text-align: center; border: 1px solid black; background-color: #0e1d2e; color: white;">No. Transaksi</th>
                    <th rowspan="2" style="text-align: center; border: 1px solid black; background-color: #0e1d2e; color: white;">Barang</th>
                    <th rowspan="2" style="width: 10%; text-align: center; border: 1px solid black; background-color: #0e1d2e; color: white;">Harga</th>
                    <th colspan="3" style="width: 30%; text-align: center; border: 1px solid black; background-color: #0e1d2e; color: white;">Stok</th>
                </tr>
                <tr>
                    <th style="width: 10%; text-align: center; border: 1px solid black; background-color: #0e1d2e; color: white;">Masuk</th>
                    <th style="width: 10%; text-align: center; border: 1px solid black; background-color: #0e1d2e; color: white;">Keluar</th>
                    <th style="width: 10%; text-align: center; border: 1px solid black; background-color: #0e1d2e; color: white;">Akhir</th>
                </tr>
            </thead>';

            if (empty($detail)) {

                $body .= '<tbody>
                    <tr>
                        <td colspan="8" style="border: 1px solid black; text-align: center;">Data Tidak Tersedia</td>
                    </tr>
                </tbody>';
            } else {
                $body .= '<tbody>';

                $no           = 1;
                $stok_akhir   = 0;
                foreach ($detail as $d) {
                    $stok_akhir += ($d->masuk - $d->keluar);

                    if ($param == 1) {
                        $harga    = number_format($d->harga);
                        $masuk    = number_format($d->masuk);
                        $keluar   = number_format($d->keluar);
                        $akhir    = number_format($stok_akhir);
                    } else {
                        $harga    = ceil($d->harga);
                        $masuk    = ceil($d->masuk);
                        $keluar   = ceil($d->keluar);
                        $akhir    = ceil($stok_akhir);
                    }

                    $body .= '<tr>
                        <td style="border: 1px solid black;">' . $no . '</td>
                        <td style="border: 1px solid black;">' . $d->record_date . '</td>
                        <td style="border: 1px solid black;">' . $d->keterangan . '</td>
                        <td style="border: 1px solid black;">' . $d->no_trx . '</td>
                        <td style="border: 1px solid black;">' . $d->barang . '</td>
                        <td style="border: 1px solid black; text-align: right;">' . $harga . '</td>
                        <td style="border: 1px solid black; text-align: right;">' . $masuk . '</td>
                        <td style="border: 1px solid black; text-align: right;">' . $keluar . '</td>
                        <td style="border: 1px solid black; text-align: right;">' . $akhir . '</td>
                    </tr>';

                    $no++;
                }

                $body .= '</tbody>';
            }

            $body .= '</table>';
        } else if ($laporan == 4) {
            $file = 'Laporan Penjualan';

            // isi body
            $header = $this->M_global->getDataResult('barang_out_header', ['tgl_jual >= ' => $dari, 'tgl_jual <= ' => $sampai]);

            // body header
            $body .= '<table style="width: 100%; font-size: 11px;">
                <tr>
                    <td style="width: 15%;">Perihal</td>
                    <td style="width: 2%;"> : </td>
                    <td colspan="2">' . $file . '</td>
                </tr>
                <tr>
                    <td style="width: 15%;">Periode</td>
                    <td style="width: 2%;"> : </td>
                    <td colspan="2">' . date('d-m-Y', strtotime($dari)) . ' ~ ' . date('d-m-Y', strtotime($sampai)) . '</td>
                </tr>
                <tr>
                    <td style="width: 15%;">Gudang</td>
                    <td style="width: 2%;"> : </td>
                    <td style="width: 33%;">' . $this->M_global->getData('m_gudang', ['kode_gudang' => $kode_gudang])->nama . '</td>
                    <td style="width: 50%; text-align: right;">Pencetak : ' . $pencetak . '</td>
                </tr>
            </table>';

            $body .= $breaktable;

            $body .= '<table style="width: 100%; font-size: 10px;" cellpadding="5px">';
            $body .= '<tr>
                <th rowspan="2" style="width: 5%; border: 1px solid black; background-color: #0e1d2e; color: white;">#</th>
                <th rowspan="2" style="width: 30%; border: 1px solid black; background-color: #0e1d2e; color: white;">Barang</th>
                <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: #0e1d2e; color: white;">Harga</th>
                <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: #0e1d2e; color: white;">Jumlah</th>
                <th colspan="2" style="width: 20%; border: 1px solid black; background-color: #0e1d2e; color: white;">Diskon</th>
                <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: #0e1d2e; color: white;">Pajak</th>
                <th rowspan="2" style="width: 15%; border: 1px solid black; background-color: #0e1d2e; color: white;">Total</th>
            </tr>
            <tr>
                <th style="width: 10%; border: 1px solid black; background-color: #0e1d2e; color: white;">%</th>
                <th style="width: 10%; border: 1px solid black; background-color: #0e1d2e; color: white;">Rp</th>
            </tr>';
            foreach ($header as $h) {
                if ($param == 1) {
                    $total = number_format($h->total);
                } else {
                    $total = ceil($h->total);
                }
                $body .= '<tr style="background-color: skyblue;">
                    <td colspan="6" style="border: 1px solid black; font-weight: bold;">No. Transaksi: ' . $h->invoice . '</td>
                    <td colspan="2" style="border: 1px solid black; font-weight: bold; text-align: right">' . $total . '</td>
                </tr>';

                // detail barang
                $detail = $this->M_global->getDataResult('barang_out_detail', ['invoice' => $h->invoice]);

                $no = 1;
                foreach ($detail as $d) {
                    if ($param == 1) {
                        $harga = number_format($d->harga);
                        $qty = number_format($d->qty);
                        $discpr = number_format($d->discpr);
                        $discrp = number_format($d->discrp);
                        $pajak = number_format($d->pajakrp);
                        $jumlah = number_format($d->jumlah);
                    } else {
                        $harga = ceil($d->harga);
                        $qty = ceil($d->qty);
                        $discpr = ceil($d->discpr);
                        $discrp = ceil($d->discrp);
                        $pajak = ceil($d->pajakrp);
                        $jumlah = ceil($d->jumlah);
                    }
                    $body .= '<tr>
                        <td style="border: 1px solid black;">' . $no . '</td>
                        <td style="border: 1px solid black;">' . $d->kode_barang . ' ~ ' . $this->M_global->getData('barang', ['kode_barang' => $d->kode_barang])->nama . '</td>
                        <td style="border: 1px solid black; text-align: right;">' . $harga . '</td>
                        <td style="border: 1px solid black; text-align: right;">' . $qty . '</td>
                        <td style="border: 1px solid black; text-align: right;">' . $discpr . '</td>
                        <td style="border: 1px solid black; text-align: right;">' . $discrp . '</td>
                        <td style="border: 1px solid black; text-align: right;">' . $pajak . '</td>
                        <td style="border: 1px solid black; text-align: right;">' . $jumlah . '</td>
                    </tr>';
                    $no++;
                }
            }

            $body .= '</table>';

            $body .= $breaktable;

            $body .= '<table style="width: 100%; font-weight: 12px;">
                <tr>
                    <td style="width:60%;">&nbsp;</td>
                    <td style="width:40%; text-align: center;">Yogyakarta, ' . date('d M Y') . '</td>
                </tr>
                <tr>
                    <td style="width:60%;">&nbsp;</td>
                    <td style="width:40%; text-align: center;">&nbsp;</td>
                </tr>
                <tr>
                    <td style="width:60%;">&nbsp;</td>
                    <td style="width:40%; text-align: center;">&nbsp;</td>
                </tr>
                <tr>
                    <td style="width:60%;">&nbsp;</td>
                    <td style="width:40%; text-align: center;">' . $pencetak . '</td>
                </tr>
            </table>';
        } else if ($laporan == 5) {
            $file = 'Laporan Retur Penjualan';

            // isi body
            $header = $this->M_global->getDataResult('barang_out_retur_header', ['tgl_retur >= ' => $dari, 'tgl_retur <= ' => $sampai]);

            // body header
            $body .= '<table style="width: 100%; font-size: 11px;">
                <tr>
                    <td style="width: 15%;">Perihal</td>
                    <td style="width: 2%;"> : </td>
                    <td colspan="2">' . $file . '</td>
                </tr>
                <tr>
                    <td style="width: 15%;">Periode</td>
                    <td style="width: 2%;"> : </td>
                    <td colspan="2">' . date('d-m-Y', strtotime($dari)) . ' ~ ' . date('d-m-Y', strtotime($sampai)) . '</td>
                </tr>
                <tr>
                    <td style="width: 15%;">Gudang</td>
                    <td style="width: 2%;"> : </td>
                    <td style="width: 33%;">' . $this->M_global->getData('m_gudang', ['kode_gudang' => $kode_gudang])->nama . '</td>
                    <td style="width: 50%; text-align: right;">Pencetak : ' . $pencetak . '</td>
                </tr>
            </table>';

            $body .= $breaktable;

            $body .= '<table style="width: 100%; font-size: 10px;" cellpadding="5px">';
            $body .= '<tr>
                <th rowspan="2" style="width: 5%; border: 1px solid black; background-color: #0e1d2e; color: white;">#</th>
                <th rowspan="2" style="width: 30%; border: 1px solid black; background-color: #0e1d2e; color: white;">Barang</th>
                <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: #0e1d2e; color: white;">Harga</th>
                <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: #0e1d2e; color: white;">Jumlah</th>
                <th colspan="2" style="width: 20%; border: 1px solid black; background-color: #0e1d2e; color: white;">Diskon</th>
                <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: #0e1d2e; color: white;">Pajak</th>
                <th rowspan="2" style="width: 15%; border: 1px solid black; background-color: #0e1d2e; color: white;">Total</th>
            </tr>
            <tr>
                <th style="width: 10%; border: 1px solid black; background-color: #0e1d2e; color: white;">%</th>
                <th style="width: 10%; border: 1px solid black; background-color: #0e1d2e; color: white;">Rp</th>
            </tr>';
            foreach ($header as $h) {
                if ($param == 1) {
                    $total = number_format($h->total);
                } else {
                    $total = ceil($h->total);
                }
                $body .= '<tr style="background-color: skyblue;">
                    <td colspan="6" style="border: 1px solid black; font-weight: bold;">No. Transaksi: ' . $h->invoice . '</td>
                    <td colspan="2" style="border: 1px solid black; font-weight: bold; text-align: right">' . $total . '</td>
                </tr>';

                // detail barang
                $detail = $this->M_global->getDataResult('barang_out_retur_detail', ['invoice' => $h->invoice]);

                $no = 1;
                foreach ($detail as $d) {
                    if ($param == 1) {
                        $harga = number_format($d->harga);
                        $qty = number_format($d->qty);
                        $discpr = number_format($d->discpr);
                        $discrp = number_format($d->discrp);
                        $pajak = number_format($d->pajakrp);
                        $jumlah = number_format($d->jumlah);
                    } else {
                        $harga = ceil($d->harga);
                        $qty = ceil($d->qty);
                        $discpr = ceil($d->discpr);
                        $discrp = ceil($d->discrp);
                        $pajak = ceil($d->pajakrp);
                        $jumlah = ceil($d->jumlah);
                    }
                    $body .= '<tr>
                        <td style="border: 1px solid black;">' . $no . '</td>
                        <td style="border: 1px solid black;">' . $d->kode_barang . ' ~ ' . $this->M_global->getData('barang', ['kode_barang' => $d->kode_barang])->nama . '</td>
                        <td style="border: 1px solid black; text-align: right;">' . $harga . '</td>
                        <td style="border: 1px solid black; text-align: right;">' . $qty . '</td>
                        <td style="border: 1px solid black; text-align: right;">' . $discpr . '</td>
                        <td style="border: 1px solid black; text-align: right;">' . $discrp . '</td>
                        <td style="border: 1px solid black; text-align: right;">' . $pajak . '</td>
                        <td style="border: 1px solid black; text-align: right;">' . $jumlah . '</td>
                    </tr>';
                    $no++;
                }
            }

            $body .= '</table>';

            $body .= $breaktable;

            $body .= '<table style="width: 100%; font-weight: 12px;">
                <tr>
                    <td style="width:60%;">&nbsp;</td>
                    <td style="width:40%; text-align: center;">Yogyakarta, ' . date('d M Y') . '</td>
                </tr>
                <tr>
                    <td style="width:60%;">&nbsp;</td>
                    <td style="width:40%; text-align: center;">&nbsp;</td>
                </tr>
                <tr>
                    <td style="width:60%;">&nbsp;</td>
                    <td style="width:40%; text-align: center;">&nbsp;</td>
                </tr>
                <tr>
                    <td style="width:60%;">&nbsp;</td>
                    <td style="width:40%; text-align: center;">' . $pencetak . '</td>
                </tr>
            </table>';
        } else if ($laporan == 6) {
            $file = 'Laporan Stok Penjualan';

            $position = 'L';

            // isi body
            $detail = $this->M_global->getReportPenjualan($dari, $sampai, $kode_gudang);

            // body header
            $body .= '<table style="width: 100%; font-size: 11px;">
                <tr>
                    <td style="width: 15%;">Perihal</td>
                    <td style="width: 2%;"> : </td>
                    <td colspan="2">' . $file . '</td>
                </tr>
                <tr>
                    <td style="width: 15%;">Periode</td>
                    <td style="width: 2%;"> : </td>
                    <td colspan="2">' . date('d-m-Y', strtotime($dari)) . ' ~ ' . date('d-m-Y', strtotime($sampai)) . '</td>
                </tr>
                <tr>
                    <td style="width: 15%;">Gudang</td>
                    <td style="width: 2%;"> : </td>
                    <td style="width: 33%;">' . $this->M_global->getData('m_gudang', ['kode_gudang' => $kode_gudang])->nama . '</td>
                    <td style="width: 50%; text-align: right;">Pencetak : ' . $pencetak . '</td>
                </tr>
            </table>';

            $body .= $breaktable;

            $body .= '<table style="width: 100%; font-size: 11px;" cellpadding="5px">';

            $body .= '<tr>
                <th rowspan="2" style="width: 5%; text-align: center; border: 1px solid black; background-color: #0e1d2e; color: white;">#</th>
                <th rowspan="2" style="width: 15%; text-align: center; border: 1px solid black; background-color: #0e1d2e; color: white;">Tgl/Jam</th>
                <th rowspan="2" style="width: 15%; text-align: center; border: 1px solid black; background-color: #0e1d2e; color: white;">Keterangan</th>
                <th rowspan="2" style="text-align: center; border: 1px solid black; background-color: #0e1d2e; color: white;">No. Transaksi</th>
                <th rowspan="2" style="text-align: center; border: 1px solid black; background-color: #0e1d2e; color: white;">Barang</th>
                <th rowspan="2" style="width: 10%; text-align: center; border: 1px solid black; background-color: #0e1d2e; color: white;">Harga</th>
                <th colspan="3" style="width: 30%; text-align: center; border: 1px solid black; background-color: #0e1d2e; color: white;">Stok</th>
            </tr>
            <tr>
                <th style="width: 10%; text-align: center; border: 1px solid black; background-color: #0e1d2e; color: white;">Masuk</th>
                <th style="width: 10%; text-align: center; border: 1px solid black; background-color: #0e1d2e; color: white;">Keluar</th>
                <th style="width: 10%; text-align: center; border: 1px solid black; background-color: #0e1d2e; color: white;">Akhir</th>
            </tr>';

            if (empty($detail)) {
                $body .= '<tr>
                    <td colspan="8" style="border: 1px solid black; text-align: center;">Data Tidak Tersedia</td>
                </tr>';
            } else {
                $no = 1;
                $stok_akhir = 0;
                foreach ($detail as $d) {
                    $stok_akhir += ($d->masuk - $d->keluar);

                    if ($param == 1) {
                        $harga = number_format($d->harga);
                        $masuk = number_format($d->masuk);
                        $keluar = number_format($d->keluar);
                        $akhir = number_format($stok_akhir);
                    } else {
                        $harga = ceil($d->harga);
                        $masuk = ceil($d->masuk);
                        $keluar = ceil($d->keluar);
                        $akhir = ceil($stok_akhir);
                    }

                    $body .= '<tr>
                        <td style="border: 1px solid black;">' . $no . '</td>
                        <td style="border: 1px solid black;">' . $d->record_date . '</td>
                        <td style="border: 1px solid black;">' . $d->keterangan . '</td>
                        <td style="border: 1px solid black;">' . $d->no_trx . '</td>
                        <td style="border: 1px solid black;">' . $d->barang . '</td>
                        <td style="border: 1px solid black; text-align: right;">' . $harga . '</td>
                        <td style="border: 1px solid black; text-align: right;">' . $masuk . '</td>
                        <td style="border: 1px solid black; text-align: right;">' . $keluar . '</td>
                        <td style="border: 1px solid black; text-align: right;">' . $akhir . '</td>
                    </tr>';

                    $no++;
                }
            }

            $body .= '</table>';
        }

        $judul = $file . ' Periode: ' . date('d-m-Y', strtotime($dari)) . ' ~ ' . date('d-m-Y', strtotime($sampai));
        $filename = $file; // nama file yang ingin di simpan

        // jalankan fungsi cetak_pdf
        cetak_pdf($judul, $body, $param, $position, $filename, $web_setting);
    }
}
