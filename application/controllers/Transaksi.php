<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaksi extends CI_Controller
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
                'menu'      => 'Transaksi',
            ];
        } else { // selain itu
            // kirimkan kembali ke Auth
            redirect('Auth');
        }
    }

    // barang_in page
    public function barang_in()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter = [
            $this->data,
            'judul'         => 'Transaksi',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Pembelian',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Transaksi/barang_in_list/',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Barang/Masuk', $parameter);
    }

    // fungsi list barang_in
    public function barang_in_list($param1 = 1, $param2 = '')
    {
        // parameter untuk list table
        $table            = 'barang_in_header';
        $colum            = ['id', 'invoice', 'tgl_beli', 'jam_beli', 'kode_supplier', 'kode_gudang', 'surat_jalan', 'no_faktur', 'pajak', 'diskon', 'total', 'kode_user', 'batal', 'tgl_batal', 'jam_batal', 'user_batal', 'is_valid'];
        $order            = 'id';
        $order2           = 'desc';
        $order_arr        = ['id' => 'asc'];
        $kondisi_param2   = 'kode_gudang';
        $kondisi_param1   = 'tgl_beli';

        // kondisi role
        $updated          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;
        $confirmed        = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->confirmed;

        // table server side tampung kedalam variable $list
        $dat    = explode("~", $param1);
        if ($dat[0] == 1) {
            $bulan   = date('m');
            $tahun   = date('Y');
            $list    = $this->M_datatables2->get_datatables($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 1, $bulan, $tahun, $param2, $kondisi_param2);
        } else {
            $bulan   = date('Y-m-d', strtotime($dat[1]));
            $tahun   = date('Y-m-d', strtotime($dat[2]));
            $list    = $this->M_datatables2->get_datatables($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 2, $bulan, $tahun, $param2, $kondisi_param2);
        }
        $data             = [];
        $no               = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {
            if ($updated > 0) {
                if ($rd->batal > 0) {
                    $upd_diss = 'disabled';
                } else {
                    if ($rd->is_valid > 0) {
                        $upd_diss = 'disabled';
                    } else {
                        $upd_diss =  _lock_button();
                    }
                }
            } else {
                $upd_diss = 'disabled';
            }

            if ($deleted > 0) {
                if ($rd->batal > 0) {
                    $del_diss = 'disabled';
                } else {
                    if ($rd->is_valid > 0) {
                        $del_diss = 'disabled';
                    } else {
                        $del_diss =  _lock_button();
                    }
                }
            } else {
                $del_diss = 'disabled';
            }

            if ($confirmed > 0) {
                $confirm_diss =  _lock_button();
            } else {
                $confirm_diss = 'disabled';
            }

            $row    = [];
            $row[]  = $no++;
            $row[]  = $rd->invoice . '<br>' . (($rd->batal == 0) ? (($rd->is_valid > 0) ? '<span class="badge badge-primary">ACC</span>' : '<span class="badge badge-success">Buka</span>') : '<span class="badge badge-danger">Batal</span>');
            $row[]  = date('d/m/Y', strtotime($rd->tgl_beli)) . ' ~ ' . date('H:i:s', strtotime($rd->jam_beli));
            $row[]  = $this->M_global->getData('m_supplier', ['kode_supplier' => $rd->kode_supplier])->nama;
            $row[]  = $this->M_global->getData('m_gudang', ['kode_gudang' => $rd->kode_gudang])->nama;
            $row[]  = $this->M_global->getData('user', ['kode_user' => $rd->kode_user])->nama;
            $row[]  = 'Rp. <span class="float-right">' . number_format($rd->total) . '</span>';
            if ($rd->is_valid < 1) {
                if ($rd->batal < 1) {
                    $actived_akun = '<button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-warning" title="Batalkan" onclick="actived(' . "'" . $rd->invoice . "', 1" . ')" ' . $confirm_diss . '>
                        <ion-icon name="ban-outline"></ion-icon>
                    </button>';
                    $valid = '<button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-success" title="ACC" onclick="valided(' . "'" . $rd->invoice . "', 1" . ')" ' . $confirm_diss . '>
                        <ion-icon name="checkmark-done-circle-outline"></ion-icon>
                    </button>
                    <button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-secondary" title="Ubah" onclick="ubah(' . "'" . $rd->invoice . "'" . ')" ' . $upd_diss . '>
                        <ion-icon name="create-outline"></ion-icon>
                    </button>
                    <button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-danger" title="Hapus" onclick="hapus(' . "'" . $rd->invoice . "'" . ')" ' . $del_diss . '>
                        <ion-icon name="close-circle-outline"></ion-icon>
                    </button>';
                } else {
                    $actived_akun = '<button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-dark" title="Re-batalkan" onclick="actived(' . "'" . $rd->invoice . "', 0" . ')" ' . $confirm_diss . '>
                        <ion-icon name="ban-outline"></ion-icon>
                    </button>';
                    $valid = '
                    <button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-warning" title="Cetak" onclick="cetak(' . "'" . $rd->invoice . "', 0" . ')">
                        <ion-icon name="print-outline"></ion-icon>
                    </button>
                    <button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-info" title="Kirim Email" onclick="email(' . "'" . $rd->invoice . "', 0" . ')">
                        <ion-icon name="send-outline"></ion-icon>
                    </button>';
                }
            } else {
                $actived_akun = '';
                $valid = '<button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-dark" title="Re-ACC" onclick="valided(' . "'" . $rd->invoice . "', 0" . ')" ' . $confirm_diss . '>
                    <ion-icon name="checkmark-done-circle-outline"></ion-icon>
                </button>
                <button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-warning" title="Cetak" onclick="cetak(' . "'" . $rd->invoice . "', 0" . ')">
                    <ion-icon name="print-outline"></ion-icon>
                </button>
                <button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-info" title="Kirim Email" onclick="email(' . "'" . $rd->invoice . "', 0" . ')">
                    <ion-icon name="send-outline"></ion-icon>
                </button>';
            }
            $row[]  = '<div class="text-center">
                ' . $actived_akun . '
                ' . $valid . '
            </div>';
            $data[] = $row;
        }

        // hasil server side
        $output = [
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->M_datatables2->count_all($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 1, $bulan, $tahun, $param2, $kondisi_param2),
            "recordsFiltered" => $this->M_datatables2->count_filtered($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 1, $bulan, $tahun, $param2, $kondisi_param2),
            "data"            => $data,
        ];

        // kirimkan ke view
        echo json_encode($output);
    }

    // fungsi print single barang_in
    public function single_print_bin($invoice, $yes)
    {
        $param          = 1;

        // param website
        $web_setting    = $this->M_global->getData('web_setting', ['id' => 1]);

        $position       = 'P'; // cek posisi l/p

        // body cetakan
        $body           = '';
        $body           .= '<br><br>'; // beri jarak antara kop dengan body

        // parameter dari view laporan
        $pencetak       = $this->M_global->getData('user', ['kode_user' => $this->session->userdata('kode_user')])->nama;

        $breaktable     = '<br>';
        $file = 'Pembelian ~ ' . $invoice;

        // isi body
        $header = $this->M_global->getData('barang_in_header', ['invoice' => $invoice]);

        // body header
        $body .= '<table style="width: 100%; font-size: 11px;">
            <tr>
                <td style="width: 15%;">Perihal</td>
                <td style="width: 2%;"> : </td>
                <td colspan="2">' . $file . '</td>
            </tr>
            <tr>
                <td style="width: 15%;">Tgl/Jam Beli</td>
                <td style="width: 2%;"> : </td>
                <td colspan="2">' . date('d-m-Y', strtotime($header->tgl_beli)) . ' ~ ' . date('H:i:s', strtotime($header->jam_beli)) . '</td>
            </tr>
            <tr>
                <td style="width: 15%;">Pemasok</td>
                <td style="width: 2%;"> : </td>
                <td colspan="2">' . $this->M_global->getData('m_supplier', ['kode_supplier' => $header->kode_supplier])->nama . '</td>
            </tr>
            <tr>
                <td style="width: 15%;">Gudang</td>
                <td style="width: 2%;"> : </td>
                <td style="width: 33%;">' . $this->M_global->getData('m_gudang', ['kode_gudang' => $header->kode_gudang])->nama . '</td>
                <td style="width: 50%; text-align: right;">Pencetak : ' . $pencetak . '</td>
            </tr>
        </table>';

        $body .= $breaktable;

        $body .= '<table style="width: 100%; font-size: 10px;" autosize="1" cellpadding="5px">';

        $body .= '<thead>
            <tr>
                <th rowspan="2" style="width: 5%; border: 1px solid black; background-color: #224b79; color: white;">#</th>
                <th rowspan="2" style="width: 30%; border: 1px solid black; background-color: #224b79; color: white;">Barang</th>
                <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: #224b79; color: white;">Harga</th>
                <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: #224b79; color: white;">Jumlah</th>
                <th colspan="2" style="width: 20%; border: 1px solid black; background-color: #224b79; color: white;">Diskon</th>
                <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: #224b79; color: white;">Pajak</th>
                <th rowspan="2" style="width: 15%; border: 1px solid black; background-color: #224b79; color: white;">Total</th>
            </tr>
            <tr>
                <th style="width: 10%; border: 1px solid black; background-color: #224b79; color: white;">%</th>
                <th style="width: 10%; border: 1px solid black; background-color: #224b79; color: white;">Rp</th>
            </tr>
        </thead>';

        $body .= '<tbody>';

        if ($param == 1) {
            $total = number_format($header->total);
        } else {
            $total = ceil($header->total);
        }
        $body .= '<tr style="background-color: skyblue;">
            <td colspan="6" style="border: 1px solid black; font-weight: bold;">No. Transaksi: ' . $header->invoice . '</td>
            <td colspan="2" style="border: 1px solid black; font-weight: bold; text-align: right">' . $total . '</td>
        </tr>';

        // detail barang
        $detail   = $this->M_global->getDataResult('barang_in_detail', ['invoice' => $header->invoice]);

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

        $judul = $file . ' Tgl/Jam: ' . date('d-m-Y', strtotime($header->tgl_beli)) . ' ~ ' . date('H:i:s', strtotime($header->jam_beli));
        $filename = $file; // nama file yang ingin di simpan

        // jalankan fungsi cetak_pdf
        cetak_pdf($judul, $body, $param, $position, $filename, $web_setting, $yes);
    }

    // fungsi kirim email barang in
    public function email($invoice)
    {
        $email = $this->input->get('email');

        $header = $this->M_global->getData('barang_in_header', ['invoice' => $invoice]);

        $judul = 'Pembelian ~ ' . $invoice;

        // $attched_file    = base_url() . 'assets/file/pdf/' . $judul . '.pdf';ahmad.ummgl@gmail.com
        $attched_file    = $_SERVER["DOCUMENT_ROOT"] . '/first_apps/assets/file/pdf/' . $judul . '.pdf';

        $ready_message   = "";
        $ready_message   .= "<table border=0>
            <tr>
                <td style='width: 30%;'>Invoice</td>
                <td style='width: 10%;'> : </td>
                <td style='width: 60%;'> $invoice </td>
            </tr>
            <tr>
                <td style='width: 30%;'>Tgl/Jam</td>
                <td style='width: 10%;'> : </td>
                <td style='width: 60%;'>" . date('d-m-Y', strtotime($header->tgl_beli)) . " / " . date('H:i:s', strtotime($header->jam_beli)) . "</td>
            </tr>
            <tr>
                <td style='width: 30%;'>Pemasok</td>
                <td style='width: 10%;'> : </td>
                <td style='width: 60%;'>" . $this->M_global->getData('m_supplier', ['kode_supplier' => $header->kode_supplier])->nama . "</td>
            </tr>
            <tr>
                <td style='width: 30%;'>Gudang</td>
                <td style='width: 10%;'> : </td>
                <td style='width: 60%;'>" . $this->M_global->getData('m_gudang', ['kode_gudang' => $header->kode_gudang])->nama . "</td>
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

    // form barang_in page
    public function form_barang_in($param)
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        if ($param != '0') {
            $barang_in     = $this->M_global->getData('barang_in_header', ['invoice' => $param]);
            $barang_detail = $this->M_global->getDataResult('barang_in_detail', ['invoice' => $param]);
        } else {
            $barang_in     = null;
            $barang_detail = null;
        }

        $parameter = [
            $this->data,
            'judul'             => 'Transaksi',
            'nama_apps'         => $web_setting->nama,
            'page'              => 'Pembelian',
            'web'               => $web_setting,
            'web_version'       => $web_version->version,
            'list_data'         => '',
            'data_barang_in'    => $barang_in,
            'barang_detail'     => $barang_detail,
            'role'              => $this->M_global->getResult('m_role'),
            'pajak'             => $this->M_global->getData('m_pajak', ['id' => 1])->persentase,
            'list_barang'       => $this->M_global->getResult('barang'),
        ];

        $this->template->load('Template/Content', 'Barang/Form_barang_in', $parameter);
    }

    // fungsi ambil data barang
    public function getBarang($kode_barang)
    {
        $barang = $this->M_global->getDataLike('barang', 'nama', 'kode_barang', $kode_barang);

        if ($barang) {
            echo json_encode($barang);
        } else {
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi insert/update proses barang_in
    public function barang_in_proses($param)
    {
        // header
        if ($param == 1) { // jika param = 1
            $invoice = _invoice();
        } else {
            $invoice = $this->input->post('invoice');
        }
        $tgl_beli         = $this->input->post('tgl_beli');
        $jam_beli         = $this->input->post('jam_beli');
        $kode_supplier    = $this->input->post('kode_supplier');
        $kode_gudang      = $this->input->post('kode_gudang');
        $surat_jalan      = $this->input->post('surat_jalan');
        $no_faktur        = $this->input->post('no_faktur');

        $subtotal         = str_replace(',', '', $this->input->post('subtotal'));
        $diskon           = str_replace(',', '', $this->input->post('diskon'));
        $pajak            = str_replace(',', '', $this->input->post('pajak'));
        $total            = str_replace(',', '', $this->input->post('total'));

        // detail
        $kode_barang_in   = $this->input->post('kode_barang_in');
        $harga_in         = $this->input->post('harga_in');
        $qty_in           = $this->input->post('qty_in');
        $discpr_in        = $this->input->post('discpr_in');
        $discrp_in        = $this->input->post('discrp_in');
        $pajakrp_in       = $this->input->post('pajakrp_in');
        $jumlah_in        = $this->input->post('jumlah_in');

        // cek jumlah detail barang_in
        $jum              = count($kode_barang_in);

        // tampung isi header
        $isi_header = [
            'invoice'       => $invoice,
            'tgl_beli'      => $tgl_beli,
            'jam_beli'      => $jam_beli,
            'kode_supplier' => $kode_supplier,
            'kode_gudang'   => $kode_gudang,
            'surat_jalan'   => $surat_jalan,
            'no_faktur'     => $no_faktur,
            'pajak'         => $pajak,
            'diskon'        => $diskon,
            'subtotal'      => $subtotal,
            'total'         => $total,
            'kode_user'     => $this->session->userdata('kode_user'),
            'batal'         => 0,
            'is_valid'      => 0,
        ];

        if ($param == 2) { // jika param = 2
            // jalankan fungsi cek
            $cek = [
                $this->M_global->updateData('barang_in_header', $isi_header, ['invoice' => $invoice]), // update header
                $this->M_global->delData('barang_in_detail', ['invoice' => $invoice]), // delete detail
            ];
        } else { // selain itu
            // jalankan fungsi cek
            $cek = $this->M_global->insertData('barang_in_header', $isi_header); // insert header
        }

        if ($cek) { // jika fungsi cek berjalan
            // lakukan loop
            for ($x = 0; $x <= ($jum - 1); $x++) {
                $kode_barang    = $kode_barang_in[$x];
                $harga          = str_replace(',', '', $harga_in[$x]);
                $qty            = str_replace(',', '', $qty_in[$x]);
                $discpr         = str_replace(',', '', $discpr_in[$x]);
                $discrp         = str_replace(',', '', $discrp_in[$x]);
                $pajakrp        = str_replace(',', '', $pajakrp_in[$x]);
                $jumlah         = str_replace(',', '', $jumlah_in[$x]);

                // tamping isi detail
                $isi_detail = [
                    'invoice'       => $invoice,
                    'kode_barang'   => $kode_barang,
                    'harga'         => $harga,
                    'qty'           => $qty,
                    'discpr'        => $discpr,
                    'discrp'        => $discrp,
                    'pajak'         => (($pajakrp > 0) ? 1 : 0),
                    'pajakrp'       => $pajakrp,
                    'jumlah'        => $jumlah,
                ];

                // insert detail
                $this->M_global->insertData('barang_in_detail', $isi_detail);

                $new_hna = $harga - ($discrp / $qty);
                $this->M_global->updateData('barang', ['hna' => $new_hna, 'nilai_persediaan' => $new_hna], ['kode_barang' => $kode_barang]); // update barang
            }

            $this->single_print_bin($invoice, 1);

            // beri nilai status = 1 kirim ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // beri nilai status = 0 kirim ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi batal/re-batal
    public function activedbarang_in($invoice, $batal)
    {
        $user_batal = $this->session->userdata('kode_user');

        if ($batal == 0) { // jika batal = 0
            // update batal jadi 0
            $cek = $this->M_global->updateData('barang_in_header', ['batal' => 0, 'tgl_batal' => null, 'jam_batal' => null, 'user_batal' => null], ['invoice' => $invoice]);
        } else { // selain itu
            // update batal jadi 1
            $cek = $this->M_global->updateData('barang_in_header', ['batal' => 1, 'tgl_batal' => date('Y-m-d'), 'jam_batal' => date('H:i:s'), 'user_batal' => $user_batal], ['invoice' => $invoice]);
        }

        if ($cek) { // jika fungsi cek berjalan
            // kirim status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirim status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi acc/re-acc
    public function accbarang_in($invoice, $acc)
    {
        // header barang by invoice
        $header = $this->M_global->getData('barang_in_header', ['invoice' => $invoice]);
        // kode_gudang
        $kode_gudang = $header->kode_gudang;

        // detail barang
        $detail = $this->M_global->getDataResult('barang_in_detail', ['invoice' => $invoice]);

        if ($acc == 0) { // jika acc = 0
            // update is_valid jadi 0
            $cek = $this->M_global->updateData('barang_in_header', ['is_valid' => 0, 'tgl_valid' => null, 'jam_valid' => null], ['invoice' => $invoice]);

            hitungStokBrgOut($detail, $kode_gudang, $invoice);
        } else { // selain itu
            // update is_valid jadi 1
            $cek = $this->M_global->updateData('barang_in_header', ['is_valid' => 1, 'tgl_valid' => date('Y-m-d'), 'jam_valid' => date('H:i:s')], ['invoice' => $invoice]);
            hitungStokBrgIn($detail, $kode_gudang, $invoice);
        }

        if ($cek) { // jika fungsi cek berjalan
            // kirim status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirim status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi hapus barang in
    public function delBeliIn($invoice)
    {
        // jalankan fungsi cek
        $cek = [
            $this->M_global->delData('barang_in_detail', ['invoice' => $invoice]), // del data detail pembelian
            $this->M_global->delData('barang_in_header', ['invoice' => $invoice]), // del data header pembelian
        ];

        if ($cek) { // jika fungsi cek berjalan
            // kirim status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirim status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    /*
    * Pembelian Retur
    **/

    // barang_in_retur page
    public function barang_in_retur()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter = [
            $this->data,
            'judul'         => 'Transaksi',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Retur Pembelian',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Transaksi/barang_in_retur_list/',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Barang/Retur', $parameter);
    }

    // fungsi list barang_in_retur
    public function barang_in_retur_list($param1 = 1, $param2 = '')
    {
        // parameter untuk list table
        $table            = 'barang_in_retur_header';
        $colum            = ['id', 'invoice', 'invoice_in', 'tgl_beli', 'jam_beli', 'kode_supplier', 'kode_gudang', 'surat_jalan', 'no_faktur', 'pajak', 'diskon', 'total', 'kode_user', 'batal', 'tgl_batal', 'jam_batal', 'user_batal', 'is_valid'];
        $order            = 'id';
        $order2           = 'desc';
        $order_arr        = ['id' => 'asc'];
        $kondisi_param2   = 'kode_gudang';
        $kondisi_param1   = 'tgl_beli';

        // kondisi role
        $updated          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;
        $confirmed        = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->confirmed;

        // table server side tampung kedalam variable $list
        $dat    = explode("~", $param1);
        if ($dat[0] == 1) {
            $bulan   = date('m');
            $tahun   = date('Y');
            $list    = $this->M_datatables2->get_datatables($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 1, $bulan, $tahun, $param2, $kondisi_param2);
        } else {
            $bulan   = date('Y-m-d', strtotime($dat[1]));
            $tahun   = date('Y-m-d', strtotime($dat[2]));
            $list    = $this->M_datatables2->get_datatables($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 2, $bulan, $tahun, $param2, $kondisi_param2);
        }
        $data             = [];
        $no               = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {
            if ($updated > 0) {
                if ($rd->batal > 0) {
                    $upd_diss = 'disabled';
                } else {
                    if ($rd->is_valid > 0) {
                        $upd_diss = 'disabled';
                    } else {
                        $upd_diss =  _lock_button();
                    }
                }
            } else {
                $upd_diss = 'disabled';
            }

            if ($deleted > 0) {
                if ($rd->batal > 0) {
                    $del_diss = 'disabled';
                } else {
                    if ($rd->is_valid > 0) {
                        $del_diss = 'disabled';
                    } else {
                        $del_diss = _lock_button();
                    }
                }
            } else {
                $del_diss = 'disabled';
            }

            if ($confirmed > 0) {
                $confirm_diss = _lock_button();
            } else {
                $confirm_diss = 'disabled';
            }

            $row    = [];
            $row[]  = $no++;
            $row[]  = $rd->invoice . '<br>' . (($rd->batal == 0) ? (($rd->is_valid > 0) ? '<span class="badge badge-primary">ACC</span>' : '<span class="badge badge-success">Buka</span>') : '<span class="badge badge-danger">Batal</span>');
            $row[]  = date('d/m/Y', strtotime($rd->tgl_beli)) . ' ~ ' . date('H:i:s', strtotime($rd->jam_beli));
            $row[]  = $this->M_global->getData('m_supplier', ['kode_supplier' => $rd->kode_supplier])->nama;
            $row[]  = $this->M_global->getData('m_gudang', ['kode_gudang' => $rd->kode_gudang])->nama;
            $row[]  = $this->M_global->getData('user', ['kode_user' => $rd->kode_user])->nama;
            $row[]  = 'Rp. <span class="float-right">' . number_format($rd->total) . '</span>';
            if ($rd->is_valid < 1) {
                if ($rd->batal < 1) {
                    $actived_akun = '<button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-warning" title="Batalkan" onclick="actived(' . "'" . $rd->invoice . "', 1" . ')" ' . $confirm_diss . '>
                        <ion-icon name="ban-outline"></ion-icon>
                    </button>';
                    $valid = '<button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-success" title="ACC" onclick="valided(' . "'" . $rd->invoice . "', 1" . ')" ' . $confirm_diss . '>
                        <ion-icon name="checkmark-done-circle-outline"></ion-icon>
                    </button>';
                } else {
                    $actived_akun = '<button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-dark" title="Re-batalkan" onclick="actived(' . "'" . $rd->invoice . "', 0" . ')" ' . $confirm_diss . '>
                        <ion-icon name="ban-outline"></ion-icon>
                    </button>';
                    $valid = '';
                }
            } else {
                $actived_akun = '';
                $valid = '<button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-dark" title="Re-ACC" onclick="valided(' . "'" . $rd->invoice . "', 0" . ')" ' . $confirm_diss . '>
                    <ion-icon name="checkmark-done-circle-outline"></ion-icon>
                </button>';
            }
            $row[]  = '<div class="text-center">
                ' . $actived_akun . '
                ' . $valid . '
                <button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-secondary" title="Ubah" onclick="ubah(' . "'" . $rd->invoice . "'" . ')" ' . $upd_diss . '>
                    <ion-icon name="create-outline"></ion-icon>
                </button>
                <button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-danger" title="Hapus" onclick="hapus(' . "'" . $rd->invoice . "'" . ')" ' . $del_diss . '>
                    <ion-icon name="close-circle-outline"></ion-icon>
                </button>
            </div>';
            $data[] = $row;
        }

        // hasil server side
        $output = [
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->M_datatables2->count_all($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 1, $bulan, $tahun, $param2, $kondisi_param2),
            "recordsFiltered" => $this->M_datatables2->count_filtered($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 1, $bulan, $tahun, $param2, $kondisi_param2),
            "data"            => $data,
        ];

        // kirimkan ke view
        echo json_encode($output);
    }

    // form barang_in_retur page
    public function form_barang_in_retur($param)
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        if ($param != '0') {
            $barang_in_retur    = $this->M_global->getData('barang_in_retur_header', ['invoice' => $param]);
            $barang_detail      = $this->M_global->getDataResult('barang_in_retur_detail', ['invoice' => $param]);
            $pembeli            = $this->db->query('SELECT * FROM barang_in_header WHERE is_valid = 1')->result();
        } else {
            $barang_in_retur    = null;
            $barang_detail      = null;
            $pembeli            = $this->db->query('SELECT * FROM barang_in_header WHERE is_valid = 1 AND invoice NOT IN (SELECT invoice_in FROM barang_in_retur_header WHERE invoice_in IS NOT NULL)')->result();
        }

        $parameter = [
            $this->data,
            'judul'                 => 'Transaksi',
            'nama_apps'             => $web_setting->nama,
            'page'                  => 'Retur Pembelian',
            'web'                   => $web_setting,
            'web_version'           => $web_version->version,
            'list_data'             => '',
            'data_barang_in_retur'  => $barang_in_retur,
            'barang_detail'         => $barang_detail,
            'pembelian'             => $pembeli,
            'role'                  => $this->M_global->getResult('m_role'),
            'pajak'                 => $this->M_global->getData('m_pajak', ['id' => 1])->persentase,
            'list_barang'           => $this->M_global->getResult('barang'),
        ];

        $this->template->load('Template/Content', 'Barang/Form_barang_in_retur', $parameter);
    }

    // fungsi get Barang In 
    public function getBarangIn($invoice)
    {
        $header = $this->db->query('SELECT b.*, (s.nama) AS nama_supplier, (g.nama) AS nama_gudang FROM barang_in_header b JOIN m_supplier s USING (kode_supplier) JOIN m_gudang g USING(kode_gudang) WHERE b.invoice = "' . $invoice . '"')->row();
        $detail = $this->db->query('SELECT b.*, (brg.nama) AS nama_barang FROM barang_in_detail b JOIN barang brg USING(kode_barang) WHERE b.invoice = "' . $invoice . '"')->result();

        if ($header) {
            echo json_encode([['status' => 1], $header, $detail]);
        } else {
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi insert/update proses barang_in_retur
    public function barang_in_retur_proses($param)
    {
        // header
        if ($param == 1) { // jika param = 1
            $invoice    = _invoice_retur();
        } else {
            $invoice    = $this->input->post('invoice');
        }
        $invoice_in     = $this->input->post('invoice_in');
        $tgl_beli       = $this->input->post('tgl_beli');
        $jam_beli       = $this->input->post('jam_beli');
        $kode_supplier  = $this->input->post('kode_supplier');
        $kode_gudang    = $this->input->post('kode_gudang');
        $surat_jalan    = $this->input->post('surat_jalan');
        $no_faktur      = $this->input->post('no_faktur');

        $subtotal       = str_replace(',', '', $this->input->post('subtotal'));
        $diskon         = str_replace(',', '', $this->input->post('diskon'));
        $pajak          = str_replace(',', '', $this->input->post('pajak'));
        $total          = str_replace(',', '', $this->input->post('total'));

        // detail
        $kode_barang_in = $this->input->post('kode_barang_in');
        $harga_in       = $this->input->post('harga_in');
        $qty_in         = $this->input->post('qty_in');
        $discpr_in      = $this->input->post('discpr_in');
        $discrp_in      = $this->input->post('discrp_in');
        $pajakrp_in     = $this->input->post('pajakrp_in');
        $jumlah_in      = $this->input->post('jumlah_in');

        // cek jumlah detail barang_in
        $jum            = count($kode_barang_in);

        // tampung isi header
        $isi_header = [
            'invoice'       => $invoice,
            'invoice_in'    => $invoice_in,
            'tgl_beli'      => $tgl_beli,
            'jam_beli'      => $jam_beli,
            'kode_supplier' => $kode_supplier,
            'kode_gudang'   => $kode_gudang,
            'surat_jalan'   => $surat_jalan,
            'no_faktur'     => $no_faktur,
            'pajak'         => $pajak,
            'diskon'        => $diskon,
            'subtotal'      => $subtotal,
            'total'         => $total,
            'kode_user'     => $this->session->userdata('kode_user'),
            'batal'         => 0,
            'is_valid'      => 0,
        ];

        if ($param == 2) { // jika param = 2
            // jalankan fungsi cek
            $cek = [
                $this->M_global->updateData('barang_in_retur_header', $isi_header, ['invoice' => $invoice]), // update header
                $this->M_global->delData('barang_in_retur_detail', ['invoice' => $invoice]), // delete detail
            ];
        } else { // selain itu
            // jalankan fungsi cek
            $cek = $this->M_global->insertData('barang_in_retur_header', $isi_header); // insert header
        }

        if ($cek) { // jika fungsi cek berjalan
            // lakukan loop
            for ($x = 0; $x <= ($jum - 1); $x++) {
                $kode_barang    = $kode_barang_in[$x];
                $harga          = str_replace(',', '', $harga_in[$x]);
                $qty            = str_replace(',', '', $qty_in[$x]);
                $discpr         = str_replace(',', '', $discpr_in[$x]);
                $discrp         = str_replace(',', '', $discrp_in[$x]);
                $pajakrp        = str_replace(',', '', $pajakrp_in[$x]);
                $jumlah         = str_replace(',', '', $jumlah_in[$x]);

                // tamping isi detail
                $isi_detail = [
                    'invoice'       => $invoice,
                    'kode_barang'   => $kode_barang,
                    'harga'         => $harga,
                    'qty'           => $qty,
                    'discpr'        => $discpr,
                    'discrp'        => $discrp,
                    'pajak'         => (($pajakrp > 0) ? 1 : 0),
                    'pajakrp'       => $pajakrp,
                    'jumlah'        => $jumlah,
                ];

                // insert detail
                $this->M_global->insertData('barang_in_retur_detail', $isi_detail);
                $this->M_global->updateData('barang', ['hna' => $harga, 'nilai_persediaan' => $harga], ['kode_barang' => $kode_barang]); // update barang
            }

            // beri nilai status = 1 kirim ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // beri nilai status = 0 kirim ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi batal/re-batal
    public function activedbarang_in_retur($invoice, $batal)
    {
        if ($batal == 0) { // jika batal = 0
            // update batal jadi 0
            $cek = $this->M_global->updateData('barang_in_retur_header', ['batal' => 0, 'tgl_batal' => null, 'jam_batal' => null], ['invoice' => $invoice]);
        } else { // selain itu
            // update batal jadi 1
            $cek = $this->M_global->updateData('barang_in_retur_header', ['batal' => 1, 'tgl_batal' => date('Y-m-d'), 'jam_batal' => date('H:i:s')], ['invoice' => $invoice]);
        }

        if ($cek) { // jika fungsi cek berjalan
            // kirim status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirim status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi acc/re-acc
    public function accbarang_in_retur($invoice, $acc)
    {
        // header barang by invoice
        $header         = $this->M_global->getData('barang_in_retur_header', ['invoice' => $invoice]);
        // kode_gudang
        $kode_gudang    = $header->kode_gudang;

        // detail barang
        $detail         = $this->M_global->getDataResult('barang_in_retur_detail', ['invoice' => $invoice]);

        if ($acc == 0) { // jika acc = 0
            // update is_valid jadi 0
            $cek = $this->M_global->updateData('barang_in_retur_header', ['is_valid' => 0, 'tgl_valid' => null, 'jam_valid' => null], ['invoice' => $invoice]);

            hitungStokBrgRtOut($detail, $kode_gudang, $invoice);
        } else { // selain itu
            // update is_valid jadi 1
            $cek = $this->M_global->updateData('barang_in_retur_header', ['is_valid' => 1, 'tgl_valid' => date('Y-m-d'), 'jam_valid' => date('H:i:s')], ['invoice' => $invoice]);
            hitungStokBrgRtIn($detail, $kode_gudang, $invoice);
        }

        if ($cek) { // jika fungsi cek berjalan
            // kirim status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirim status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi hapus barang in
    public function delBeliInRetur($invoice)
    {
        // jalankan fungsi cek
        $cek = [
            $this->M_global->delData('barang_in_retur_detail', ['invoice' => $invoice]), // del data detail pembelian
            $this->M_global->delData('barang_in_retur_header', ['invoice' => $invoice]), // del data header pembelian
        ];

        if ($cek) { // jika fungsi cek berjalan
            // kirim status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirim status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    /*
    * Pembelian Laporan
    **/

    // barang_in_retur page
    public function barang_in_report()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter = [
            $this->data,
            'judul'         => 'Transaksi',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Laporan Pembelian',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => '',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Barang/Laporan', $parameter);
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
                    <th rowspan="2" style="width: 5%; border: 1px solid black; background-color: #224b79; color: white;">#</th>
                    <th rowspan="2" style="width: 30%; border: 1px solid black; background-color: #224b79; color: white;">Barang</th>
                    <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: #224b79; color: white;">Harga</th>
                    <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: #224b79; color: white;">Jumlah</th>
                    <th colspan="2" style="width: 20%; border: 1px solid black; background-color: #224b79; color: white;">Diskon</th>
                    <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: #224b79; color: white;">Pajak</th>
                    <th rowspan="2" style="width: 15%; border: 1px solid black; background-color: #224b79; color: white;">Total</th>
                </tr>
                <tr>
                    <th style="width: 10%; border: 1px solid black; background-color: #224b79; color: white;">%</th>
                    <th style="width: 10%; border: 1px solid black; background-color: #224b79; color: white;">Rp</th>
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
                    <th rowspan="2" style="width: 5%; border: 1px solid black; background-color: red; color: white;">#</th>
                    <th rowspan="2" style="width: 30%; border: 1px solid black; background-color: red; color: white;">Barang</th>
                    <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: red; color: white;">Harga</th>
                    <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: red; color: white;">Jumlah</th>
                    <th colspan="2" style="width: 20%; border: 1px solid black; background-color: red; color: white;">Diskon</th>
                    <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: red; color: white;">Pajak</th>
                    <th rowspan="2" style="width: 15%; border: 1px solid black; background-color: red; color: white;">Total</th>
                </tr>
                <tr>
                    <th style="width: 10%; border: 1px solid black; background-color: red; color: white;">%</th>
                    <th style="width: 10%; border: 1px solid black; background-color: red; color: white;">Rp</th>
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
        } else {
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
                    <th rowspan="2" style="width: 5%; text-align: center; border: 1px solid black; background-color: red; color: white;">#</th>
                    <th rowspan="2" style="width: 15%; text-align: center; border: 1px solid black; background-color: red; color: white;">Tgl/Jam</th>
                    <th rowspan="2" style="width: 15%; text-align: center; border: 1px solid black; background-color: red; color: white;">Keterangan</th>
                    <th rowspan="2" style="text-align: center; border: 1px solid black; background-color: red; color: white;">No. Transaksi</th>
                    <th rowspan="2" style="text-align: center; border: 1px solid black; background-color: red; color: white;">Barang</th>
                    <th rowspan="2" style="width: 10%; text-align: center; border: 1px solid black; background-color: red; color: white;">Harga</th>
                    <th colspan="3" style="width: 30%; text-align: center; border: 1px solid black; background-color: red; color: white;">Stok</th>
                </tr>
                <tr>
                    <th style="width: 10%; text-align: center; border: 1px solid black; background-color: red; color: white;">Masuk</th>
                    <th style="width: 10%; text-align: center; border: 1px solid black; background-color: red; color: white;">Keluar</th>
                    <th style="width: 10%; text-align: center; border: 1px solid black; background-color: red; color: white;">Akhir</th>
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
        }

        $judul = $file . ' Periode: ' . date('d-m-Y', strtotime($dari)) . ' ~ ' . date('d-m-Y', strtotime($sampai));
        $filename = $file; // nama file yang ingin di simpan

        // jalankan fungsi cetak_pdf
        cetak_pdf($judul, $body, $param, $position, $filename, $web_setting);
    }

    /*
    * Penjualan
    **/

    // barang_out page
    public function barang_out()
    {
        // website config
        $web_setting    = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version    = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter      = [
            $this->data,
            'judul'         => 'Transaksi',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Penjualan',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Transaksi/barang_out_list/',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Jual/Keluar', $parameter);
    }

    // fungsi list barang_out
    public function barang_out_list($param1 = 1, $param2 = '')
    {
        // parameter untuk list table
        $table            = 'barang_out_header';
        $colum            = ['id', 'invoice', 'kode_member', 'no_trx', 'tgl_jual', 'jam_jual', 'status_jual', 'kode_gudang', 'pajak', 'diskon', 'total', 'kode_user', 'batal', 'tgl_batal', 'jam_batal', 'user_batal'];
        $order            = 'id';
        $order2           = 'desc';
        $order_arr        = ['id' => 'asc'];
        $kondisi_param2   = 'kode_gudang';
        $kondisi_param1   = 'tgl_jual';

        // kondisi role
        $updated          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;
        $confirmed        = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->confirmed;

        // table server side tampung kedalam variable $list
        $dat              = explode("~", $param1);
        if ($dat[0] == 1) {
            $bulan        = date('m');
            $tahun        = date('Y');
            $list         = $this->M_datatables2->get_datatables($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 1, $bulan, $tahun, $param2, $kondisi_param2);
        } else {
            $bulan        = date('Y-m-d', strtotime($dat[1]));
            $tahun        = date('Y-m-d', strtotime($dat[2]));
            $list         = $this->M_datatables2->get_datatables($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 2, $bulan, $tahun, $param2, $kondisi_param2);
        }
        $data             = [];
        $no               = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {
            if ($updated > 0) {
                if ($rd->batal > 0) {
                    $upd_diss = 'disabled';
                } else {
                    if ($rd->status_jual > 0) {
                        $upd_diss = 'disabled';
                    } else {
                        $upd_diss =  _lock_button();
                    }
                }
            } else {
                $upd_diss = 'disabled';
            }

            if ($deleted > 0) {
                if ($rd->batal > 0) {
                    $del_diss = 'disabled';
                } else {
                    if ($rd->status_jual > 0) {
                        $del_diss = 'disabled';
                    } else {
                        $del_diss = _lock_button();
                    }
                }
            } else {
                $del_diss = 'disabled';
            }

            if ($confirmed > 0) {
                if ($rd->status_jual > 0) {
                    $confirm_diss = 'disabled';
                } else {
                    $confirm_diss = _lock_button();
                }
            } else {
                $confirm_diss = 'disabled';
            }

            $row    = [];
            $row[]  = $no++;
            $row[]  = $rd->invoice . '<br>' . (($rd->status_jual == 0) ? (($rd->status_jual > 1) ? '<span class="badge badge-danger">Batal</span>' : '<span class="badge badge-success">Buka</span>') : '<span class="badge badge-primary">Selesai</span>') . (($this->M_global->jumDataRow('barang_out_retur_header', ['invoice_jual' => $rd->invoice]) > 0) ? '<br><span class="badge badge-warning">Tedapat Returan ~ ' . (($this->M_global->jumDataRow('pembayaran', ['inv_jual' => $this->M_global->getData('barang_out_retur_header', ['invoice_jual' => $rd->invoice])->invoice]) > 0) ? 'Sudah diproses kasir' : 'Belum diproses kasir') . '</span>' : '');
            $row[]  = date('d/m/Y', strtotime($rd->tgl_jual)) . ' ~ ' . date('H:i:s', strtotime($rd->jam_jual));
            $row[]  = $rd->kode_member . ' ~ ' . $this->M_global->getData('member', ['kode_member' => $rd->kode_member])->nama;
            $row[]  = $this->M_global->getData('m_gudang', ['kode_gudang' => $rd->kode_gudang])->nama;
            $row[]  = 'Rp. <span class="float-right">' . number_format($rd->total) . '</span>';
            if ($rd->batal < 1) {
                $actived_jual = '<button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-danger" title="Batalkan" onclick="actived(' . "'" . $rd->invoice . "', 1" . ')" ' . $confirm_diss . '>
                    <ion-icon name="ban-outline"></ion-icon>
                </button>';
            } else {
                $actived_jual = '<button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-dark" title="Re-batalkan" onclick="actived(' . "'" . $rd->invoice . "', 0" . ')" ' . $confirm_diss . '>
                    <ion-icon name="ban-outline"></ion-icon>
                </button>';
            }
            $row[]  = '<div class="text-center">
                ' . $actived_jual . '
                <button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-secondary" title="Ubah" onclick="ubah(' . "'" . $rd->invoice . "'" . ')" ' . $upd_diss . '>
                    <ion-icon name="create-outline"></ion-icon>
                </button>
                <button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-danger" title="Hapus" onclick="hapus(' . "'" . $rd->invoice . "'" . ')" ' . $del_diss . '>
                    <ion-icon name="close-circle-outline"></ion-icon>
                </button>
                <a target="_blank" style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-success" title="Kartu" href="' . site_url("Transaksi/print_barang_out/") . $rd->invoice . '">
                    <ion-icon name="id-card-outline"></ion-icon>
                </a>
            </div>';
            $data[] = $row;
        }

        // hasil server side
        $output = [
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->M_datatables2->count_all($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 1, $bulan, $tahun, $param2, $kondisi_param2),
            "recordsFiltered" => $this->M_datatables2->count_filtered($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 1, $bulan, $tahun, $param2, $kondisi_param2),
            "data"            => $data,
        ];

        // kirimkan ke view
        echo json_encode($output);
    }

    // fungsi cetak barang out
    public function print_barang_out($invoice)
    {
        $web_setting    = $this->M_global->getData('web_setting', ['id' => 1]);

        $position       = 'P'; // cek posisi l/p

        // body cetakan
        $body           = '';
        $body           .= '<br><br>'; // beri jarak antara kop dengan body

        $barang_out_header    = $this->M_global->getData('barang_out_header', ['invoice' => $invoice]);
        $barang_out_detail    = $this->M_global->getDataResult('barang_out_detail', ['invoice' => $invoice]);
        $member               = $this->M_global->getData('member', ['kode_member' => $barang_out_header->kode_member]);

        $judul                = 'Pendaftaran ' . $invoice;
        $filename             = $judul;

        if ($barang_out_header->status_jual == 1) {
            $open   = '<input type="checkbox" style="width: 80px;" checked="checked"> Terbayar';
            $close  = '<input type="checkbox" style="width: 80px;"> Belum Bayar';
        } else {
            $open   = '<input type="checkbox" style="width: 80px;"> Terbayar';
            $close  = '<input type="checkbox" style="width: 80px;" checked="checked"> Belum Bayar';
        }

        $body .= '<table style="width: 100%; font-size: 12px;" cellpadding="2px">';

        $body .= '<tr>
            <td style="width: 13%;">No Trx</td>
            <td style="width: 2%;">:</td>
            <td style="width: 35%;">' . $invoice . '</td>
            <td style="width: 13%;">No RM</td>
            <td style="width: 2%;">:</td>
            <td style="width: 35%;">' . $member->kode_member . '</td>
        </tr>
        <tr>
            <td style="width: 13%;">Poli</td>
            <td style="width: 2%;">:</td>
            <td style="width: 35%;">' . $this->M_global->getData('m_poli', ['kode_poli' => $barang_out_header->kode_poli])->keterangan . '</td>
            <td style="width: 13%;">Member</td>
            <td style="width: 2%;">:</td>
            <td style="width: 35%;">' . $member->kode_member . ' ~ ' . $member->nama . '</td>
        </tr>
        <tr>
            <td style="width: 13%;">Dokter</td>
            <td style="width: 2%;">:</td>
            <td style="width: 35%;">' . (($barang_out_header->kode_dokter == null || $barang_out_header->kode_dokter == '') ? '' : $this->M_global->getData('dokter', ['kode_dokter' => $barang_out_header->kode_dokter])->nama) . '</td>
            <td style="width: 13%;">Nama</td>
            <td style="width: 2%;">:</td>
            <td style="width: 35%;">' . $member->nama . '</td>
        </tr>
        <tr>
            <td style="width: 13%;">Gudang</td>
            <td style="width: 2%;">:</td>
            <td style="width: 35%;">' . $this->M_global->getData('m_gudang', ['kode_gudang' => $barang_out_header->kode_gudang])->keterangan . '</td>
            <td style="width: 13%;">Umur</td>
            <td style="width: 2%;">:</td>
            <td style="width: 35%;">' . hitung_umur($member->tgl_lahir) . '</td>
        </tr>
        <tr>
            <td style="width: 13%;">Tgl/Jam Order</td>
            <td style="width: 2%;">:</td>
            <td style="width: 35%;">' . date('d/m/Y', strtotime($barang_out_header->tgl_jual)) . ' ~ ' . date('H:i:s', strtotime($barang_out_header->jam_jual)) . '</td>
            <td style="width: 13%;">Status</td>
            <td style="width: 2%;">:</td>
            <td style="width: 35%;">' . $open . '&nbsp;&nbsp;' . $close . '</td>
        </tr>
        <tr>
            <td style="width: 100%;" colspan="3">&nbsp;</td>
        </tr>';
        $body .= '</table>';

        $body .= '<table style="width: 100%; font-size: 10px;" autosize="1" cellpadding="5px">';

        $body .= '<thead>
            <tr>
                <th style="width: 5%; border: 1px solid black; background-color: red; color: white;">#</th>
                <th style="width: 20%; border: 1px solid black; background-color: red; color: white;">Barang</th>
                <th style="width: 15%; border: 1px solid black; background-color: red; color: white;">Harga</th>
                <th style="width: 15%; border: 1px solid black; background-color: red; color: white;">Jumlah</th>
                <th style="width: 15%; border: 1px solid black; background-color: red; color: white;">Diskon</th>
                <th style="width: 15%; border: 1px solid black; background-color: red; color: white;">Pajak</th>
                <th style="width: 15%; border: 1px solid black; background-color: red; color: white;">Total</th>
            </tr>
        </thead>';

        $body .= '<tbody>';

        $no = 1;
        foreach ($barang_out_detail as $bod) {
            $barang = $this->M_global->getData('barang', ['kode_barang' => $bod->kode_barang]);

            $body .= '<tr>
                <td style="border: 1px solid black;">' . $no . '</td>
                <td style="border: 1px solid black;">' . $barang->kode_barang . ' ~ ' . $barang->nama . '</td>
                <td style="border: 1px solid black; text-align: right;">Rp. ' . number_format($bod->harga) . '</td>
                <td style="border: 1px solid black; text-align: right;">' . number_format($bod->qty) . '</td>
                <td style="border: 1px solid black; text-align: right;">Rp. ' . number_format($bod->discrp) . '</td>
                <td style="border: 1px solid black; text-align: right;">Rp. ' . number_format($bod->pajakrp) . '</td>
                <td style="border: 1px solid black; text-align: right;">Rp. ' . number_format($bod->jumlah) . '</td>
            </tr>';

            $no++;
        }

        $body .= '</tbody>';

        $body .= '<tfoot>';

        $body .= '<tr>
            <th colspan="6" style="text-align: right;">Subtotal: Rp. </th>
            <th style="text-align: right;">' . number_format($barang_out_header->subtotal) . '</th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: right;">Diskon: Rp. </th>
            <th style="text-align: right;">' . number_format($barang_out_header->diskon) . '</th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: right;">Pajak: Rp. </th>
            <th style="text-align: right;">' . number_format($barang_out_header->pajak) . '</th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: right;">Total: Rp. </th>
            <th style="text-align: right;">' . number_format($barang_out_header->total) . '</th>
        </tr>';

        $body .= '</tfoot>';

        $body .= '</table>';

        cetak_pdf($judul, $body, 1, $position, $filename, $web_setting);
    }

    // form barang_out page
    public function form_barang_out($param)
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        if ($param != '0') {
            $barang_out     = $this->M_global->getData('barang_out_header', ['invoice' => $param]);
            $barang_detail  = $this->M_global->getDataResult('barang_out_detail', ['invoice' => $param]);
        } else {
            $barang_out     = null;
            $barang_detail  = null;
        }

        $parameter = [
            $this->data,
            'judul'             => 'Transaksi',
            'nama_apps'         => $web_setting->nama,
            'page'              => 'Penjualan',
            'web'               => $web_setting,
            'web_version'       => $web_version->version,
            'list_data'         => '',
            'data_barang_out'   => $barang_out,
            'barang_detail'     => $barang_detail,
            'role'              => $this->M_global->getResult('m_role'),
            'pajak'             => $this->M_global->getData('m_pajak', ['id' => 1])->persentase,
            'list_barang'       => $this->M_global->getResult('barang'),
        ];

        $this->template->load('Template/Content', 'Jual/Form_barang_out', $parameter);
    }

    // fungsi barang stok by gudang
    public function getBarangGudang($key_barang, $kode_gudang)
    {
        $stok = $this->db->query("SELECT bs.kode_barang, bs.akhir, b.nama, b.harga_jual FROM barang_stok bs JOIN barang b ON bs.kode_barang = b.kode_barang WHERE bs.kode_gudang = '$kode_gudang' AND (bs.kode_barang LIKE '%$key_barang%' OR b.nama LIKE '%$key_barang%')")->row();

        if ($stok) {
            echo json_encode($stok);
        } else {
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi info pendaftaran
    public function getInfoPendaftaran($no_trx)
    {
        $pendaftaran = $this->db->query("SELECT p.*, m.nama AS nama_member, d.nama AS nama_dokter FROM pendaftaran p JOIN member m ON p.kode_member = m.kode_member JOIN dokter d ON p.kode_dokter = d.kode_dokter WHERE p.no_trx = '$no_trx'")->row();

        if ($pendaftaran) {
            echo json_encode($pendaftaran);
        } else {
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi info alamat
    public function getAddressMember($kode_member)
    {
        $member = $this->M_global->getData('member', ['kode_member' => $kode_member]);

        if ($member) {
            $prov       = $this->M_global->getData('m_provinsi', ['kode_provinsi' => $member->provinsi])->provinsi;
            $kab        = $this->M_global->getData('kabupaten', ['kode_kabupaten' => $member->kabupaten])->kabupaten;
            $kec        = $this->M_global->getData('kecamatan', ['kode_kecamatan' => $member->kecamatan])->kecamatan;

            $address    = 'Prov.' . $prov . ', Kab.' . $kab . ', Kec.' . $kec . ', Ds.' . $member->desa . ', (POS: ' . $member->kodepos . '), RT.' . $member->rt . '/RW.' . $member->rw;

            echo json_encode(['alamat' => $address]);
        } else {
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi cek member terdaftar
    public function cekMember($kode_member)
    {
        $member = $this->M_global->getData('member', ['kode_member' => $kode_member, 'status_regist' => 1]);

        if ($member) {
            if ($member->status_regist == 1) {
                echo json_encode(['status' => 1, 'no_trx' => $member->last_regist]);
            } else {
                echo json_encode(['status' => 0]);
            }
        } else {
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi cek notrx exist or not in penjualan
    public function cekJual($no_trx)
    {
        $jual = $this->M_global->jumDataRow('barang_out_header', ['no_trx' => $no_trx]);

        if ($jual < 1) { // jika jual exist/ lebih dari 1
            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi insert/update proses barang_out
    public function barang_out_proses($param)
    {
        // header
        $kode_poli          = $this->input->post('kode_poli');

        if ($kode_poli == '' || $kode_poli == null) { // jika kodepoli kosong/ null
            // isi dengan umum
            $kopoli = 'U00001';
        } else { // selain itu
            // isi dengan kodepoli
            $kopoli = $kode_poli;
        }

        if ($param == 1) { // jika param = 1
            $invoice        = _invoiceJual($kopoli);
        } else {
            $invoice        = $this->input->post('invoice');
        }
        $tgl_jual           = $this->input->post('tgl_jual');
        $jam_jual           = $this->input->post('jam_jual');
        $no_trx             = $this->input->post('kode_pendaftaran');
        $kode_dokter        = $this->input->post('kode_dokter');
        $kode_member        = $this->input->post('kode_member');
        $kode_gudang        = $this->input->post('kode_gudang');
        $alamat             = $this->input->post('alamat');

        $subtotal           = str_replace(',', '', $this->input->post('subtotal'));
        $diskon             = str_replace(',', '', $this->input->post('diskon'));
        $pajak              = str_replace(',', '', $this->input->post('pajak'));
        $total              = str_replace(',', '', $this->input->post('total'));

        // detail
        $kode_barang_out    = $this->input->post('kode_barang_out');
        $harga_out          = $this->input->post('harga_out');
        $qty_out            = $this->input->post('qty_out');
        $discpr_out         = $this->input->post('discpr_out');
        $discrp_out         = $this->input->post('discrp_out');
        $pajakrp_out        = $this->input->post('pajakrp_out');
        $jumlah_out         = $this->input->post('jumlah_out');

        // cek jumlah detail barang_out
        $jum                = count($kode_barang_out);

        // tampung isi header
        $isi_header = [
            'invoice'       => $invoice,
            'no_trx'        => $no_trx,
            'kode_member'   => $kode_member,
            'alamat'        => $alamat,
            'kode_dokter'   => $kode_dokter,
            'kode_poli'     => $kopoli,
            'tgl_jual'      => $tgl_jual,
            'jam_jual'      => $jam_jual,
            'status_jual'   => 0,
            'kode_gudang'   => $kode_gudang,
            'pajak'         => $pajak,
            'diskon'        => $diskon,
            'subtotal'      => $subtotal,
            'total'         => $total,
            'kode_user'     => $this->session->userdata('kode_user'),
            'batal'         => 0,
        ];

        if ($param == 2) { // jika param = 2
            $header = $this->M_global->getData('barang_out_header', ['invoice' => $invoice]);

            $gudang = $header->kode_gudang;

            $detail = $this->M_global->getDataResult('barang_out_detail', ['invoice' => $invoice]);

            hitungStokJualOut($detail, $gudang, $invoice);

            // jalankan fungsi cek
            $cek = [
                $this->M_global->updateData('barang_out_header', $isi_header, ['invoice' => $invoice]), // update header
                $this->M_global->delData('barang_out_detail', ['invoice' => $invoice]), // delete detail
            ];
        } else { // selain itu
            // jalankan fungsi cek
            $cek = $this->M_global->insertData('barang_out_header', $isi_header); // insert header
        }

        if ($cek) { // jika fungsi cek berjalan
            // lakukan loop
            for ($x = 0; $x <= ($jum - 1); $x++) {
                $kode_barang    = $kode_barang_out[$x];
                $harga          = str_replace(',', '', $harga_out[$x]);
                $qty            = str_replace(',', '', $qty_out[$x]);
                $discpr         = str_replace(',', '', $discpr_out[$x]);
                $discrp         = str_replace(',', '', $discrp_out[$x]);
                $pajakrp        = str_replace(',', '', $pajakrp_out[$x]);
                $jumlah         = str_replace(',', '', $jumlah_out[$x]);

                // tamping isi detail
                $isi_detail = [
                    'invoice'       => $invoice,
                    'kode_barang'   => $kode_barang,
                    'harga'         => $harga,
                    'qty'           => $qty,
                    'discpr'        => $discpr,
                    'discrp'        => $discrp,
                    'pajak'         => (($pajakrp > 0) ? 1 : 0),
                    'pajakrp'       => $pajakrp,
                    'jumlah'        => $jumlah,
                ];

                // insert detail
                $this->M_global->insertData('barang_out_detail', $isi_detail);

                $detail = $this->M_global->getDataResult('barang_out_detail', ['invoice' => $invoice]);

                hitungStokJualIn($detail, $kode_gudang, $invoice);
            }

            if ($kopoli == 'UMUM') {
                $last_regist = 'UMUM / ' . $invoice;
            } else {
                $last_regist = $no_trx;
            }

            $this->M_global->updateData('member', ['status_regist' => 1, 'last_regist' => $last_regist], ['kode_member' => $kode_member]);

            // beri nilai status = 1 kirim ke view
            echo json_encode(['status' => 1, 'invoice' => $invoice]);
        } else { // selain itu
            // beri nilai status = 0 kirim ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi hapus barang out
    public function delBeliOut($invoice)
    {
        // jalankan fungsi cek
        $header         = $this->M_global->getData('barang_out_header', ['invoice' => $invoice]);

        $kode_gudang    = $header->kode_gudang;

        $detail         = $this->M_global->getDataResult('barang_out_detail', ['invoice' => $invoice]);
        hitungStokJualOut($detail, $kode_gudang, $invoice);

        $cek = [
            $this->M_global->delData('barang_out_detail', ['invoice' => $invoice]), // del data detail penjualan
            $this->M_global->delData('barang_out_header', ['invoice' => $invoice]), // del data header penjualan
        ];

        if ($cek) { // jika fungsi cek berjalan
            // kirim status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirim status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi batal/re-batal
    public function activedbarang_out($invoice, $batal)
    {
        $user_batal = $this->session->userdata('kode_user');

        if ($batal == 0) { // jika batal = 0
            // update batal jadi 0
            $cek = $this->M_global->updateData('barang_out_header', ['batal' => 0, 'tgl_batal' => null, 'jam_batal' => null, 'user_batal' => null], ['invoice' => $invoice]);
        } else { // selain itu
            // update batal jadi 1
            $cek = $this->M_global->updateData('barang_out_header', ['batal' => 1, 'tgl_batal' => date('Y-m-d'), 'jam_batal' => date('H:i:s'), 'user_batal' => $user_batal], ['invoice' => $invoice]);
            $header         = $this->M_global->getData('barang_out_header', ['invoice' => $invoice]);

            $kode_gudang    = $header->kode_gudang;

            $detail         = $this->M_global->getDataResult('barang_out_detail', ['invoice' => $invoice]);
            hitungStokJualOut($detail, $kode_gudang, $invoice);
        }

        if ($cek) { // jika fungsi cek berjalan
            // kirim status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirim status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    /*
    * Penjualan
    **/

    // barang_out_retur page
    public function barang_out_retur()
    {
        // website config
        $web_setting    = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version    = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter      = [
            $this->data,
            'judul'         => 'Transaksi',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Retur Penjualan',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Transaksi/barang_out_retur_list/',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Jual/Retur', $parameter);
    }

    // fungsi list barang_out_retur
    public function barang_out_retur_list($param1 = 1, $param2 = '')
    {
        // parameter untuk list table
        $table            = 'barang_out_retur_header';
        $colum            = ['id', 'invoice', 'invoice_jual', 'tgl_retur', 'jam_retur', 'status_retur', 'kode_gudang', 'pajak', 'diskon', 'total', 'kode_user', 'batal', 'tgl_batal', 'jam_batal', 'user_batal'];
        $order            = 'id';
        $order2           = 'desc';
        $order_arr        = ['id' => 'asc'];
        $kondisi_param2   = 'kode_gudang';
        $kondisi_param1   = 'tgl_retur';

        // kondisi role
        $updated          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;
        $confirmed        = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->confirmed;

        // table server side tampung kedalam variable $list
        $dat              = explode("~", $param1);
        if ($dat[0] == 1) {
            $bulan        = date('m');
            $tahun        = date('Y');
            $list         = $this->M_datatables2->get_datatables($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 1, $bulan, $tahun, $param2, $kondisi_param2);
        } else {
            $bulan        = date('Y-m-d', strtotime($dat[1]));
            $tahun        = date('Y-m-d', strtotime($dat[2]));
            $list         = $this->M_datatables2->get_datatables($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 2, $bulan, $tahun, $param2, $kondisi_param2);
        }
        $data             = [];
        $no               = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {
            if ($updated > 0) {
                if ($rd->batal > 0) {
                    $upd_diss = 'disabled';
                } else {
                    if ($rd->status_retur > 0) {
                        $upd_diss = 'disabled';
                    } else {
                        $upd_diss =  _lock_button();
                    }
                }
            } else {
                $upd_diss = 'disabled';
            }

            if ($deleted > 0) {
                if ($rd->batal > 0) {
                    $del_diss = 'disabled';
                } else {
                    if ($rd->status_retur > 0) {
                        $del_diss = 'disabled';
                    } else {
                        $del_diss = _lock_button();
                    }
                }
            } else {
                $del_diss = 'disabled';
            }

            if ($confirmed > 0) {
                if ($rd->status_retur > 0) {
                    $confirm_diss = 'disabled';
                } else {
                    $confirm_diss = _lock_button();
                }
            } else {
                $confirm_diss = 'disabled';
            }

            $row    = [];
            $row[]  = $no++;
            $row[]  = $rd->invoice . '<br>' . (($rd->status_retur == 0) ? (($rd->status_retur > 1) ? '<span class="badge badge-danger">Batal</span>' : '<span class="badge badge-success">Buka</span>') : '<span class="badge badge-primary">Selesai</span>');
            $row[]  = $rd->invoice_jual;
            $row[]  = date('d/m/Y', strtotime($rd->tgl_retur)) . ' ~ ' . date('H:i:s', strtotime($rd->jam_retur));
            $row[]  = $this->M_global->getData('m_gudang', ['kode_gudang' => $rd->kode_gudang])->nama;
            $row[]  = 'Rp. <span class="float-right">' . number_format($rd->total) . '</span>';
            if ($rd->batal < 1) {
                $actived_jual = '<button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-danger" title="Batalkan" onclick="actived(' . "'" . $rd->invoice . "', 1" . ')" ' . $confirm_diss . '>
                    <ion-icon name="ban-outline"></ion-icon>
                </button>';
            } else {
                $actived_jual = '<button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-dark" title="Re-batalkan" onclick="actived(' . "'" . $rd->invoice . "', 0" . ')" ' . $confirm_diss . '>
                    <ion-icon name="ban-outline"></ion-icon>
                </button>';
            }
            $row[]  = '<div class="text-center">
                ' . $actived_jual . '
                <button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-secondary" title="Ubah" onclick="ubah(' . "'" . $rd->invoice . "'" . ')" ' . $upd_diss . '>
                    <ion-icon name="create-outline"></ion-icon>
                </button>
                <button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-danger" title="Hapus" onclick="hapus(' . "'" . $rd->invoice . "'" . ')" ' . $del_diss . '>
                    <ion-icon name="close-circle-outline"></ion-icon>
                </button>
            </div>';
            $data[] = $row;
        }

        // hasil server side
        $output = [
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->M_datatables2->count_all($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 1, $bulan, $tahun, $param2, $kondisi_param2),
            "recordsFiltered" => $this->M_datatables2->count_filtered($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 1, $bulan, $tahun, $param2, $kondisi_param2),
            "data"            => $data,
        ];

        // kirimkan ke view
        echo json_encode($output);
    }

    // form barang_out_retur page
    public function form_barang_out_retur($param)
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        if ($param != '0') {
            $barang_out_retur   = $this->M_global->getData('barang_out_retur_header', ['invoice' => $param]);
            $barang_detail      = $this->M_global->getDataResult('barang_out_retur_detail', ['invoice' => $param]);
        } else {
            $barang_out_retur   = null;
            $barang_detail      = null;
        }

        $parameter = [
            $this->data,
            'judul'                     => 'Transaksi',
            'nama_apps'                 => $web_setting->nama,
            'page'                      => 'Retur Penjualan',
            'web'                       => $web_setting,
            'web_version'               => $web_version->version,
            'list_data'                 => '',
            'data_barang_out_retur'     => $barang_out_retur,
            'barang_detail'             => $barang_detail,
            'role'                      => $this->M_global->getResult('m_role'),
            'pajak'                     => $this->M_global->getData('m_pajak', ['id' => 1])->persentase,
            'list_barang'               => $this->M_global->getResult('barang'),
        ];

        $this->template->load('Template/Content', 'Jual/Form_barang_out_retur', $parameter);
    }

    // fungsi ambil data penjualan
    public function getDataJual($invoice)
    {
        // cek ada/tidak di penjualan berdasarkan invoice
        $cek = $this->db->query("SELECT h.*, g.nama AS nama_gudang FROM barang_out_header h JOIN m_gudang g ON h.kode_gudang = g.kode_gudang WHERE h.invoice = '$invoice'")->row();

        if ($cek) { // jika penjualan ada
            // ambil data detailnya
            $detail = $this->db->query("SELECT d.*, b.nama AS nama_barang FROM barang_out_detail d JOIN barang b ON d.kode_barang = b.kode_barang WHERE d.invoice = '$invoice'")->result();
        } else { // selain itu
            // isi null
            $detail = null;
        }

        if ($detail == null) { // jika detail null
            // kirim status 0 ke view
            echo json_encode(['status' => 0]);
        } else { // selain itu
            // kirimkan data header dan detail penjualan
            echo json_encode([$cek, $detail]);
        }
    }

    // fungsi cek qty penjualan untuk di retur
    public function getQtyJual($invoice, $kode_barang)
    {
        $cek = $this->db->query("SELECT d.* FROM barang_out_detail d WHERE d.invoice = '$invoice' AND d.kode_barang = '$kode_barang'")->row();

        if ($cek) { // jika cek ada
            // kirimkan data qty ke view
            echo json_encode(['qty' => $cek->qty]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi proses insert/update retur
    public function barang_out_retur_proses($param)
    {
        // header
        if ($param == 1) { // jika param = 1
            $invoice        = _invoiceRetur();
        } else { // selain itu
            $invoice        = $this->input->post('invoice');
        }

        $tgl_retur          = $this->input->post('tgl_retur');
        $jam_retur          = $this->input->post('jam_retur');
        $invoice_jual       = $this->input->post('invoice_jual');
        $kode_gudang        = $this->input->post('kode_gudang');
        $alasan             = $this->input->post('alasan');

        $subtotal           = str_replace(',', '', $this->input->post('subtotal'));
        $diskon             = str_replace(',', '', $this->input->post('diskon'));
        $pajak              = str_replace(',', '', $this->input->post('pajak'));
        $total              = str_replace(',', '', $this->input->post('total'));

        // detail
        $kode_barang_out    = $this->input->post('kode_barang_out');
        $harga_out          = $this->input->post('harga_out');
        $qty_out            = $this->input->post('qty_out');
        $discpr_out         = $this->input->post('discpr_out');
        $discrp_out         = $this->input->post('discrp_out');
        $pajakrp_out        = $this->input->post('pajakrp_out');
        $jumlah_out         = $this->input->post('jumlah_out');

        // cek jumlah detail barang_out
        $jum                = count($kode_barang_out);

        // tampung isi header
        $isi_header = [
            'invoice'       => $invoice,
            'invoice_jual'  => $invoice_jual,
            'alasan'        => $alasan,
            'tgl_retur'     => $tgl_retur,
            'jam_retur'     => $jam_retur,
            'status_retur'  => 0,
            'kode_gudang'   => $kode_gudang,
            'pajak'         => $pajak,
            'diskon'        => $diskon,
            'subtotal'      => $subtotal,
            'total'         => $total,
            'kode_user'     => $this->session->userdata('kode_user'),
            'batal'         => 0,
        ];

        if ($param == 2) { // jika param = 2
            $header = $this->M_global->getData('barang_out_retur_header', ['invoice' => $invoice]);

            $gudang = $header->kode_gudang;

            $detail = $this->M_global->getDataResult('barang_out_retur_detail', ['invoice' => $invoice]);

            hitungStokReturJualOut($detail, $gudang, $invoice);

            // jalankan fungsi cek
            $cek = [
                $this->M_global->updateData('barang_out_retur_header', $isi_header, ['invoice' => $invoice]), // update header
                $this->M_global->delData('barang_out_retur_detail', ['invoice' => $invoice]), // delete detail
            ];
        } else { // selain itu
            // jalankan fungsi cek
            $cek = $this->M_global->insertData('barang_out_retur_header', $isi_header); // insert header
        }

        if ($cek) { // jika fungsi cek berjalan
            // lakukan loop
            for ($x = 0; $x <= ($jum - 1); $x++) {
                $kode_barang    = $kode_barang_out[$x];
                $harga          = str_replace(',', '', $harga_out[$x]);
                $qty            = str_replace(',', '', $qty_out[$x]);
                $discpr         = str_replace(',', '', $discpr_out[$x]);
                $discrp         = str_replace(',', '', $discrp_out[$x]);
                $pajakrp        = str_replace(',', '', $pajakrp_out[$x]);
                $jumlah         = str_replace(',', '', $jumlah_out[$x]);

                // tamping isi detail
                $isi_detail = [
                    'invoice'       => $invoice,
                    'kode_barang'   => $kode_barang,
                    'harga'         => $harga,
                    'qty'           => $qty,
                    'discpr'        => $discpr,
                    'discrp'        => $discrp,
                    'pajak'         => (($pajakrp > 0) ? 1 : 0),
                    'pajakrp'       => $pajakrp,
                    'jumlah'        => $jumlah,
                ];

                // insert detail
                $this->M_global->insertData('barang_out_retur_detail', $isi_detail);

                $detail = $this->M_global->getDataResult('barang_out_retur_detail', ['invoice' => $invoice]);

                hitungStokReturJualIn($detail, $kode_gudang, $invoice);
            }

            // beri nilai status = 1 kirim ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // beri nilai status = 0 kirim ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi hapus retur barang out
    public function delBeliOutRetur($invoice)
    {
        // jalankan fungsi cek
        $header         = $this->M_global->getData('barang_out_retur_header', ['invoice' => $invoice]);

        $kode_gudang    = $header->kode_gudang;

        $detail         = $this->M_global->getDataResult('barang_out_retur_detail', ['invoice' => $invoice]);
        hitungStokReturJualOut($detail, $kode_gudang, $invoice);

        $cek = [
            $this->M_global->delData('barang_out_retur_detail', ['invoice' => $invoice]), // del data detail penjualan
            $this->M_global->delData('barang_out_retur_header', ['invoice' => $invoice]), // del data header penjualan
        ];

        if ($cek) { // jika fungsi cek berjalan
            // kirim status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirim status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    /*
    * Retur Penjualan Laporan
    **/

    // barang_out_retur page
    public function barang_out_report()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter = [
            $this->data,
            'judul'         => 'Transaksi',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Laporan Penjualan',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => '',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Barang/Laporan_out', $parameter);
    }

    function report_print_out($param)
    {
        // param website
        $web_setting    = $this->M_global->getData('web_setting', ['id' => 1]);

        $position       = 'P'; // cek posisi l/p

        // body cetakan
        $body           = '';
        $body           .= '<br><br><br><br>'; // beri jarak antara kop dengan body

        // parameter dari view laporan
        $pencetak       = $this->M_global->getData('user', ['kode_user' => $this->session->userdata('kode_user')])->nama;
        $laporan        = $this->input->get('laporan');
        $dari           = $this->input->get('dari');
        $sampai         = $this->input->get('sampai');
        $kode_gudang    = $this->input->get('kode_gudang');

        $breaktable     = '<br><br>';

        if ($laporan == 1) {
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
                <th rowspan="2" style="width: 5%; border: 1px solid black; background-color: red; color: white;">#</th>
                <th rowspan="2" style="width: 30%; border: 1px solid black; background-color: red; color: white;">Barang</th>
                <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: red; color: white;">Harga</th>
                <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: red; color: white;">Jumlah</th>
                <th colspan="2" style="width: 20%; border: 1px solid black; background-color: red; color: white;">Diskon</th>
                <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: red; color: white;">Pajak</th>
                <th rowspan="2" style="width: 15%; border: 1px solid black; background-color: red; color: white;">Total</th>
            </tr>
            <tr>
                <th style="width: 10%; border: 1px solid black; background-color: red; color: white;">%</th>
                <th style="width: 10%; border: 1px solid black; background-color: red; color: white;">Rp</th>
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
        } else if ($laporan == 2) {
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
                <th rowspan="2" style="width: 5%; border: 1px solid black; background-color: red; color: white;">#</th>
                <th rowspan="2" style="width: 30%; border: 1px solid black; background-color: red; color: white;">Barang</th>
                <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: red; color: white;">Harga</th>
                <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: red; color: white;">Jumlah</th>
                <th colspan="2" style="width: 20%; border: 1px solid black; background-color: red; color: white;">Diskon</th>
                <th rowspan="2" style="width: 10%; border: 1px solid black; background-color: red; color: white;">Pajak</th>
                <th rowspan="2" style="width: 15%; border: 1px solid black; background-color: red; color: white;">Total</th>
            </tr>
            <tr>
                <th style="width: 10%; border: 1px solid black; background-color: red; color: white;">%</th>
                <th style="width: 10%; border: 1px solid black; background-color: red; color: white;">Rp</th>
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
        } else {
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
                <th rowspan="2" style="width: 5%; text-align: center; border: 1px solid black; background-color: red; color: white;">#</th>
                <th rowspan="2" style="width: 15%; text-align: center; border: 1px solid black; background-color: red; color: white;">Tgl/Jam</th>
                <th rowspan="2" style="width: 15%; text-align: center; border: 1px solid black; background-color: red; color: white;">Keterangan</th>
                <th rowspan="2" style="text-align: center; border: 1px solid black; background-color: red; color: white;">No. Transaksi</th>
                <th rowspan="2" style="text-align: center; border: 1px solid black; background-color: red; color: white;">Barang</th>
                <th rowspan="2" style="width: 10%; text-align: center; border: 1px solid black; background-color: red; color: white;">Harga</th>
                <th colspan="3" style="width: 30%; text-align: center; border: 1px solid black; background-color: red; color: white;">Stok</th>
            </tr>
            <tr>
                <th style="width: 10%; text-align: center; border: 1px solid black; background-color: red; color: white;">Masuk</th>
                <th style="width: 10%; text-align: center; border: 1px solid black; background-color: red; color: white;">Keluar</th>
                <th style="width: 10%; text-align: center; border: 1px solid black; background-color: red; color: white;">Akhir</th>
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

    /*
    * Stok
    **/

    // penyesuaian_stok page
    public function penyesuaian_stok()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter = [
            $this->data,
            'judul'         => 'Transaksi',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Penyesuaian Stok',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Transaksi/penyesuaian_stok_list/',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Barang/Penyesuaian_stok', $parameter);
    }

    // fungsi list penyesuaian_stok
    public function penyesuaian_stok_list($param1 = 1, $param2 = '0')
    {
        // parameter untuk list table
        $table            = 'penyesuaian_header';
        $colum            = ['id', 'invoice', 'tgl_penyesuaian', 'jam_penyesuaian', 'kode_user', 'kode_gudang', 'tipe_penyesuaian', 'acc', 'user_acc', 'tgl_acc', 'jam_acc'];
        $order            = 'id';
        $order2           = 'desc';
        $order_arr        = ['id' => 'asc'];
        $kondisi_param2   = 'tipe_penyesuaian';
        $kondisi_param1   = 'tgl_penyesuaian';

        // kondisi role
        $updated          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;
        $confirmed        = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->confirmed;

        // table server side tampung kedalam variable $list
        $dat    = explode("~", $param1);
        if ($dat[0] == 1) {
            $bulan   = date('m');
            $tahun   = date('Y');
            $list    = $this->M_datatables2->get_datatables($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 1, $bulan, $tahun, $param2, $kondisi_param2);
        } else {
            $bulan   = date('Y-m-d', strtotime($dat[1]));
            $tahun   = date('Y-m-d', strtotime($dat[2]));
            $list    = $this->M_datatables2->get_datatables($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 2, $bulan, $tahun, $param2, $kondisi_param2);
        }
        $data             = [];
        $no               = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {
            if ($updated > 0) {
                if ($rd->acc > 0) {
                    $upd_diss = 'disabled';
                } else {
                    $upd_diss =  _lock_button();
                }
            } else {
                $upd_diss = 'disabled';
            }

            if ($deleted > 0) {
                if ($rd->acc > 0) {
                    $del_diss = 'disabled';
                } else {
                    $del_diss = _lock_button();
                }
            } else {
                $del_diss = 'disabled';
            }

            if ($confirmed > 0) {
                $confirm_diss = _lock_button();
            } else {
                $confirm_diss = 'disabled';
            }

            $row    = [];
            $row[]  = $no++;
            $row[]  = date('d/m/Y', strtotime($rd->tgl_penyesuaian)) . ' ~ ' . date('H:i:s', strtotime($rd->jam_penyesuaian));
            $row[]  = $rd->invoice . '<span class="float-right">' . (($rd->acc == 1) ? '<span class="badge badge-primary">ACC</span>' : '<span class="badge badge-danger">Belum di ACC</span>') . '</span>';
            $row[]  = $this->M_global->getData('m_gudang', ['kode_gudang' => $rd->kode_gudang])->nama;
            $row[]  = '<div class="text-center">' . (($rd->tipe_penyesuaian == 1) ? '<span class="badge badge-primary text-center">SO</span>' : '<span class="badge badge-success text-center">Adjusment</span>') . '</div>';
            if ($rd->acc < 1) {
                $valid = '<button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-success" title="ACC" onclick="valided(' . "'" . $rd->invoice . "', 1" . ')" ' . $confirm_diss . '>
                    <ion-icon name="checkmark-done-circle-outline"></ion-icon>
                </button>';
            } else {
                $valid = '<button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-dark" title="Re-ACC" onclick="valided(' . "'" . $rd->invoice . "', 0" . ')" ' . $confirm_diss . '>
                    <ion-icon name="checkmark-done-circle-outline"></ion-icon>
                </button>';
            }
            $row[]  = '<div class="text-center">
                ' . $valid . '
                <button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-secondary" title="Ubah" onclick="ubah(' . "'" . $rd->invoice . "'" . ')" ' . $upd_diss . '>
                    <ion-icon name="create-outline"></ion-icon>
                </button>
                <button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-danger" title="Hapus" onclick="hapus(' . "'" . $rd->invoice . "'" . ')" ' . $del_diss . '>
                    <ion-icon name="close-circle-outline"></ion-icon>
                </button>
            </div>';
            $data[] = $row;
        }

        // hasil server side
        $output = [
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->M_datatables2->count_all($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 1, $bulan, $tahun, $param2, $kondisi_param2),
            "recordsFiltered" => $this->M_datatables2->count_filtered($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 1, $bulan, $tahun, $param2, $kondisi_param2),
            "data"            => $data,
        ];

        // kirimkan ke view
        echo json_encode($output);
    }

    // form penyesuaian_stok page
    public function form_penyesuaian_stok($param)
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        if ($param != '0') {
            $penyesuaian_stok    = $this->M_global->getData('penyesuaian_header', ['invoice' => $param]);
            $barang_detail      = $this->M_global->getDataResult('penyesuaian_detail', ['invoice' => $param]);
        } else {
            $penyesuaian_stok    = null;
            $barang_detail      = null;
        }

        $parameter = [
            $this->data,
            'judul'                 => 'Transaksi',
            'nama_apps'             => $web_setting->nama,
            'page'                  => 'Penyesuaian Stok',
            'web'                   => $web_setting,
            'web_version'           => $web_version->version,
            'list_data'             => '',
            'data_penyesuaian_stok' => $penyesuaian_stok,
            'barang_detail'         => $barang_detail,
            'role'                  => $this->M_global->getResult('m_role'),
            'pajak'                 => $this->M_global->getData('m_pajak', ['id' => 1])->persentase,
            'list_barang'           => $this->M_global->getResult('barang'),
        ];

        $this->template->load('Template/Content', 'Barang/Form_penyesuaian_stok', $parameter);
    }

    // fungsi proses insert/update penyesuaian stok
    public function penyesuaian_stok_proses($param)
    {
        // header
        if ($param == 1) { // jika param = 1
            $invoice              = _invoicePenyesuaianStok();
        } else { // selain itu
            $invoice              = $this->input->post('invoice');
        }

        $tgl_penyesuaian          = $this->input->post('tgl_penyesuaian');
        $jam_penyesuaian          = $this->input->post('jam_penyesuaian');
        $kode_gudang              = $this->input->post('kode_gudang');
        $tipe_penyesuaian         = $this->input->post('tipe_penyesuaian');
        $kode_user                = $this->session->userdata('kode_user');

        // detail
        $kode_penyesuaian_stok    = $this->input->post('kode_penyesuaian_stok');
        $qty_ps                   = $this->input->post('qty_ps');

        // cek jumlah detail barang
        $jum                      = count($kode_penyesuaian_stok);

        // tampung isi header
        $isi_header = [
            'invoice'           => $invoice,
            'tgl_penyesuaian'   => $tgl_penyesuaian,
            'jam_penyesuaian'   => $jam_penyesuaian,
            'kode_gudang'       => $kode_gudang,
            'tipe_penyesuaian'  => $tipe_penyesuaian,
            'acc'               => 0,
            'kode_user'         => $kode_user,
        ];

        if ($param == 2) { // jika param = 2
            // jalankan fungsi cek
            $cek = [
                $this->M_global->updateData('penyesuaian_header', $isi_header, ['invoice' => $invoice]), // update header
                $this->M_global->delData('penyesuaian_detail', ['invoice' => $invoice]), // delete detail
            ];
        } else { // selain itu
            // jalankan fungsi cek
            $cek = $this->M_global->insertData('penyesuaian_header', $isi_header); // insert header
        }

        if ($cek) { // jika fungsi cek berjalan
            // lakukan loop
            for ($x = 0; $x <= ($jum - 1); $x++) {
                $kode_barang    = $kode_penyesuaian_stok[$x];
                $qty            = str_replace(',', '', $qty_ps[$x]);

                // tamping isi detail
                $isi_detail = [
                    'invoice'       => $invoice,
                    'kode_barang'   => $kode_barang,
                    'qty'           => $qty,
                ];

                // insert detail
                $this->M_global->insertData('penyesuaian_detail', $isi_detail);
            }

            // beri nilai status = 1 kirim ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // beri nilai status = 0 kirim ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi hapus barang in
    public function delPenyeStok($invoice)
    {
        // jalankan fungsi cek
        $cek = [
            $this->M_global->delData('penyesuaian_detail', ['invoice' => $invoice]), // del data detail pembelian
            $this->M_global->delData('penyesuaian_header', ['invoice' => $invoice]), // del data header pembelian
        ];

        if ($cek) { // jika fungsi cek berjalan
            // kirim status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirim status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi acc/re-acc
    public function accpenyesuaian_stok($invoice, $acc)
    {
        // header barang by invoice
        $header = $this->M_global->getData('penyesuaian_header', ['invoice' => $invoice]);
        // kode_gudang
        $kode_gudang = $header->kode_gudang;

        // detail barang
        $detail = $this->M_global->getDataResult('penyesuaian_detail', ['invoice' => $invoice]);

        if ($acc == 0) { // jika acc = 0
            $cek = $this->M_global->updateData('penyesuaian_header', ['acc' => 0, 'tgl_acc' => null, 'jam_acc' => null], ['invoice' => $invoice]);

            hitungStokAdjOut($detail, $kode_gudang, $invoice);
        } else { // selain itu
            // update acc jadi 1
            $cek = $this->M_global->updateData('penyesuaian_header', ['acc' => 1, 'tgl_acc' => date('Y-m-d'), 'jam_acc' => date('H:i:s')], ['invoice' => $invoice]);

            hitungStokAdjIn($detail, $kode_gudang, $invoice);
        }

        if ($cek) { // jika fungsi cek berjalan
            // kirim status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirim status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    /*
    * Stok Opname
    **/

    // so page
    public function so()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $now = date('Y-m-d');

        $cek_jadwal_so = $this->M_global->getData('jadwal_so', ['id' => 1]);

        $parameter = [
            $this->data,
            'judul'         => 'Transaksi',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Stock Opname',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Transaksi/so_list/',
            'param1'        => '',
            'cek_jadwal'    => $cek_jadwal_so,
        ];

        $this->template->load('Template/Content', 'Barang/So', $parameter);
    }

    // fungsi list so
    public function so_list($param1 = 1, $param2 = '1')
    {
        // parameter untuk list table
        $table            = 'penyesuaian_header';
        $colum            = ['id', 'invoice', 'tgl_penyesuaian', 'jam_penyesuaian', 'kode_user', 'kode_gudang', 'tipe_penyesuaian', 'acc', 'user_acc', 'tgl_acc', 'jam_acc'];
        $order            = 'id';
        $order2           = 'desc';
        $order_arr        = ['id' => 'asc'];
        $kondisi_param2   = 'tipe_penyesuaian';
        $kondisi_param1   = 'tgl_penyesuaian';

        // kondisi role
        $updated          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;
        $confirmed        = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->confirmed;

        // table server side tampung kedalam variable $list
        $dat    = explode("~", $param1);

        if ($dat[0] == 1) {
            $bulan   = date('m');
            $tahun   = date('Y');
            $list    = $this->M_datatables2->get_datatables($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 1, $bulan, $tahun, $param2, $kondisi_param2);
        } else {
            $bulan   = date('Y-m-d', strtotime($dat[1]));
            $tahun   = date('Y-m-d', strtotime($dat[2]));
            $list    = $this->M_datatables2->get_datatables($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 2, $bulan, $tahun, $param2, $kondisi_param2);
        }

        $data             = [];
        $no               = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {
            if ($updated > 0) {
                if ($rd->acc > 0) {
                    $upd_diss = 'disabled';
                } else {
                    $upd_diss =  _lock_button();
                }
            } else {
                $upd_diss = 'disabled';
            }

            if ($deleted > 0) {
                if ($rd->acc > 0) {
                    $del_diss = 'disabled';
                } else {
                    $del_diss = _lock_button();
                }
            } else {
                $del_diss = 'disabled';
            }

            if ($confirmed > 0) {
                $confirm_diss = _lock_button();
            } else {
                $confirm_diss = 'disabled';
            }

            $row    = [];
            $row[]  = $no++;
            $row[]  = date('d/m/Y', strtotime($rd->tgl_penyesuaian)) . ' ~ ' . date('H:i:s', strtotime($rd->jam_penyesuaian));
            $row[]  = $rd->invoice . '<span class="float-right">' . (($rd->acc == 1) ? '<span class="badge badge-primary">ACC</span>' : '<span class="badge badge-danger">Belum di ACC</span>') . '</span>';
            $row[]  = $this->M_global->getData('m_gudang', ['kode_gudang' => $rd->kode_gudang])->nama;
            $row[]  = '<div class="text-center">' . (($rd->tipe_penyesuaian == 1) ? '<span class="badge badge-primary text-center">SO</span>' : '<span class="badge badge-success text-center">Adjusment</span>') . '</div>';
            if ($rd->acc < 1) {
                $valid = '<button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-success" title="ACC" onclick="valided(' . "'" . $rd->invoice . "', 1" . ')" ' . $confirm_diss . '>
                    <ion-icon name="checkmark-done-circle-outline"></ion-icon>
                </button>';
            } else {
                $valid = '<button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-dark" title="Re-ACC" onclick="valided(' . "'" . $rd->invoice . "', 0" . ')" ' . $confirm_diss . '>
                    <ion-icon name="checkmark-done-circle-outline"></ion-icon>
                </button>';
            }
            $row[]  = '<div class="text-center">
                ' . $valid . '
                <button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-secondary" title="Ubah" onclick="ubah(' . "'" . $rd->invoice . "'" . ')" ' . $upd_diss . '>
                    <ion-icon name="create-outline"></ion-icon>
                </button>
                <button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-danger" title="Hapus" onclick="hapus(' . "'" . $rd->invoice . "'" . ')" ' . $del_diss . '>
                    <ion-icon name="close-circle-outline"></ion-icon>
                </button>
            </div>';
            $data[] = $row;
        }

        // hasil server side
        $output = [
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->M_datatables2->count_all($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 1, $bulan, $tahun, $param2, $kondisi_param2),
            "recordsFiltered" => $this->M_datatables2->count_filtered($table, $colum, $order_arr, $order, $order2, $kondisi_param1, 1, $bulan, $tahun, $param2, $kondisi_param2),
            "data"            => $data,
        ];

        // kirimkan ke view
        echo json_encode($output);
    }

    public function schedule_so()
    {
        $id             = $this->input->post('id_so');
        $tgl_dari_so    = $this->input->post('tgl_dari_so');
        $jam_dari_so    = $this->input->post('jam_dari_so');
        $tgl_sampai_so  = $this->input->post('tgl_sampai_so');
        $jam_sampai_so  = $this->input->post('jam_sampai_so');
        $status         = 1;
        $kode_user      = $this->session->userdata('kode_user');

        $data_so = [
            'tgl_dari'      => $tgl_dari_so,
            'jam_dari'      => $jam_dari_so,
            'tgl_sampai'    => $tgl_sampai_so,
            'jam_sampai'    => $jam_sampai_so,
            'status'        => $status,
            'kode_user'     => $kode_user,
        ];

        if ($id == '' || $id == null) {
            $cek = $this->M_global->insertData('jadwal_so', $data_so);
        } else {
            $cek = $this->M_global->updateData('jadwal_so', $data_so, ['id' => $id]);
        }

        if ($cek) {
            echo json_encode(['status' => 1]);
        } else {
            echo json_encode(['status' => 0]);
        }
    }

    /*
    * Riwayat Stok
    **/

    // riwayat_stok page
    public function riwayat_stok()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter = [
            $this->data,
            'judul'         => 'Transaksi',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Stock Opname',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Transaksi/riwayat_stok_list/',
            'param1'        => null,
        ];

        $this->template->load('Template/Content', 'Barang/Riwayat_stok', $parameter);
    }

    // fungsi list riwayat_stok
    public function riwayat_stok_list($gudang = null)
    {
        $this->load->model("M_riwayat_stok");
        // Retrieve data from the model
        $list = $this->M_riwayat_stok->get_datatables($gudang);

        $data = [];
        $no = $_POST['start'] + 1;

        // Loop through the list to populate the data array
        foreach ($list as $rd) {
            $row = [];
            $row[] = $no++;
            $row[] = $rd->kode_barang;
            $row[] = $rd->nama;
            $row[] = $rd->gudang;
            $row[] = $rd->hpp;
            $row[] = $rd->harga_jual;
            $row[] = '<div class="float-right">' . number_format($rd->akhir) . '</div>';
            $row[] = '<div class="text-center">
                <button style="margin-bottom: 5px;" type="button" class="btn btn-sm btn-warning" title="Lihat" onclick="lihat(' . "'" . $rd->kode_barang . "'" . ')">
                    <ion-icon name="eye-outline"></ion-icon>
                </button>
            </div>';
            $data[] = $row;
        }

        // Prepare the output in JSON format
        $output = [
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_riwayat_stok->count_all($gudang),
            "recordsFiltered" => $this->M_riwayat_stok->count_filtered($gudang),
            "data" => $data,
        ];

        // Send the output to the view
        echo json_encode($output);
    }
}
