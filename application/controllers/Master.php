<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master extends CI_Controller
{
    // variable open public untuk controller Home
    public $data;

    public function __construct()
    {
        parent::__construct();
        // load model M_auth
        $this->load->model("M_auth");

        if (!empty($this->session->userdata("email"))) { // jika session email masih ada
            $id_menu          = $this->M_global->getData('m_menu', ['url' => 'Master'])->id;

            // ambil isi data berdasarkan email session dari table user, kemudian tampung ke variable $user
            $user             = $this->M_global->getData("user", ["email" => $this->session->userdata("email")]);

            $cek_akses_menu   = $this->M_global->getData('akses_menu', ['id_menu' => $id_menu, 'kode_role' => $user->kode_role]);
            if ($cek_akses_menu) {
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

                $this->load->model('M_barang');
            } else {
                // kirimkan kembali ke Auth
                redirect('Where');
            }
        } else { // selain itu
            // kirimkan kembali ke Auth
            redirect('Auth');
        }
    }

    /**
     * Master Satuan
     * untuk menampilkan, menambahkan, dan mengubah satuan dalam sistem
     */

    // satuan page
    public function satuan()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter   = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Satuan',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Master/satuan_list',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Master/Umum/Satuan', $parameter);
    }

    // fungsi list satuan
    public function satuan_list($param1 = '')
    {
        // parameter untuk list table
        $table            = 'm_satuan';
        $colum            = ['id', 'kode_satuan', 'keterangan'];
        $order            = 'id';
        $order2           = 'desc';
        $order_arr        = ['id' => 'asc'];
        $kondisi_param1   = '';

        // kondisi role
        $updated          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;

        if ($updated > 0) {
            $upd_diss     = '';
        } else {
            $upd_diss     = 'disabled';
        }

        // table server side tampung kedalam variable $list
        $list             = $this->M_datatables->get_datatables($table, $colum, $order_arr, $order, $order2, $param1, $kondisi_param1);
        $data             = [];
        $no               = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {
            if ($deleted > 0) {
                $barang             = $this->M_global->getResult('barang');

                $satuan             = [];
                foreach ($barang as $b) {
                    $satuan[]       = [$b->kode_satuan, $b->kode_satuan2, $b->kode_satuan3];
                }

                $flattened_satuan   = array_merge(...$satuan);

                if (in_array($rd->kode_satuan, $flattened_satuan)) {
                    $del_diss       = 'disabled';
                } else {
                    $del_diss       = '';
                }
            } else {
                $del_diss           = 'disabled';
            }

            $row    = [];
            $row[]  = $no++;
            $row[]  = $rd->kode_satuan;
            $row[]  = $rd->keterangan;
            $row[]  = '<div class="text-center">
                <button type="button" class="btn btn-warning" style="margin-bottom: 5px;" onclick="ubah(' . "'" . $rd->kode_satuan . "'" . ')" ' . $upd_diss . '><i class="fa-regular fa-pen-to-square"></i></button>
                <button type="button" class="btn btn-danger" style="margin-bottom: 5px;" onclick="hapus(' . "'" . $rd->kode_satuan . "'" . ')" ' . $del_diss . '><i class="fa-regular fa-circle-xmark"></i></button>
            </div>';
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

    // fungsi cek satuan berdasarkan keterangan satuan
    public function cekSat()
    {
        // ambil keterangan inputan
        $keterangan   = $this->input->post('keterangan');

        // cek keterangan pada table m_satuan
        $cek          = $this->M_global->jumDataRow('m_satuan', ['keterangan' => $keterangan]);

        if ($cek < 1) { // jika tidak ada/ kurang dari 1
            // kirimkan status 1
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi proses simpan/update satuan
    public function satuan_proses($param)
    {
        // variable
        $keterangan       = $this->input->post('keterangan');

        if ($param == 1) { // jika parameternya 1
            // maka buat kode baru
            $kodeSatuan   = _kodeSatuan();
        } else { // selain itu
            // ambil kode dari inputan
            $kodeSatuan   = $this->input->post('kodeSatuan');
        }

        // tampung variable kedalam $isi
        $isi = [
            'kode_satuan'   => $kodeSatuan,
            'keterangan'    => $keterangan,
        ];

        if ($param == 1) { // jika parameternya 1
            // jalankan fungsi simpan
            $cek = $this->M_global->insertData('m_satuan', $isi);

            $cek_param = 'menambahkan';
        } else { // selain itu
            // jalankan fungsi update
            $cek = $this->M_global->updateData('m_satuan', $isi, ['kode_satuan' => $kodeSatuan]);

            $cek_param = 'mengubah';
        }

        if ($cek) { // jika fungsi berjalan
            aktifitas_user('Master Satuan', $cek_param, $kodeSatuan, $this->M_global->getData('m_satuan', ['kode_satuan' => $kodeSatuan])->keterangan);

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi ambil informasi satuan berdasarkan kode satuan
    public function getInfoSat($kode_satuan)
    {
        // ambil data satuan berdasarkan kode_satuan
        $data = $this->M_global->getData('m_satuan', ['kode_satuan' => $kode_satuan]);
        // lempar ke view
        echo json_encode($data);
    }

    // fungsi hapus satuan berdasarkan kode_satuan
    public function delSat($kode_satuan)
    {
        // jalankan fungsi hapus satuan berdasarkan kode_satuan
        aktifitas_user('Master Satuan', 'menghapus', $kode_satuan, $this->M_global->getData('m_satuan', ['kode_satuan' => $kode_satuan])->keterangan);
        $cek = $this->M_global->delData('m_satuan', ['kode_satuan' => $kode_satuan]);

        if ($cek) { // jika fungsi berjalan

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // ############################################################################################################################################################################

    /**
     * Master Kategori
     * untuk menampilkan, menambahkan, dan mengubah kategori dalam sistem
     */

    // kategori page
    public function kategori()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter   = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Kategori',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Master/kategori_list',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Master/Umum/Kategori', $parameter);
    }

    // fungsi list kategori
    public function kategori_list($param1 = '')
    {
        // parameter untuk list table
        $table            = 'm_kategori';
        $colum            = ['id', 'kode_kategori', 'keterangan'];
        $order            = 'id';
        $order2           = 'desc';
        $order_arr        = ['id' => 'asc'];
        $kondisi_param1   = '';

        // kondisi role
        $updated          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;

        if ($updated > 0) {
            $upd_diss     = '';
        } else {
            $upd_diss     = 'disabled';
        }

        // table server side tampung kedalam variable $list
        $list             = $this->M_datatables->get_datatables($table, $colum, $order_arr, $order, $order2, $param1, $kondisi_param1);
        $data             = [];
        $no               = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {
            if ($deleted > 0) {
                $cekIsset       = $this->M_global->jumDataRow('barang', ['kode_kategori' => $rd->kode_kategori]);

                if ($cekIsset > 0) {
                    $del_diss   = 'disabled';
                } else {
                    $del_diss   = '';
                }
            } else {
                $del_diss       = 'disabled';
            }

            $row    = [];
            $row[]  = $no++;
            $row[]  = $rd->kode_kategori;
            $row[]  = $rd->keterangan;
            $row[]  = '<div class="text-center">
                <button type="button" class="btn btn-warning" style="margin-bottom: 5px;" onclick="ubah(' . "'" . $rd->kode_kategori . "'" . ')" ' . $upd_diss . '><i class="fa-regular fa-pen-to-square"></i></button>
                <button type="button" class="btn btn-danger" style="margin-bottom: 5px;" onclick="hapus(' . "'" . $rd->kode_kategori . "'" . ')" ' . $del_diss . '><i class="fa-regular fa-circle-xmark"></i></button>
            </div>';
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

    // fungsi cek kategori berdasarkan keterangan kategori
    public function cekKat()
    {
        // ambil keterangan inputan
        $keterangan   = $this->input->post('keterangan');

        // cek keterangan pada table m_kategori
        $cek          = $this->M_global->jumDataRow('m_kategori', ['keterangan' => $keterangan]);

        if ($cek < 1) { // jika tidak ada/ kurang dari 1
            // kirimkan status 1
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi proses simpan/update kategori
    public function kategori_proses($param)
    {
        // variable
        $keterangan         = $this->input->post('keterangan');

        if ($param == 1) { // jika parameternya 1
            // maka buat kode baru
            $kodeKategori   = _kodeKategori();
        } else { // selain itu
            // ambil kode dari inputan
            $kodeKategori   = $this->input->post('kodeKategori');
        }

        // tampung variable kedalam $isi
        $isi = [
            'kode_kategori' => $kodeKategori,
            'keterangan'    => $keterangan,
        ];

        if ($param == 1) { // jika parameternya 1
            // jalankan fungsi simpan
            $cek          = $this->M_global->insertData('m_kategori', $isi);

            $cek_param    = 'menambahkan';
        } else { // selain itu
            // jalankan fungsi update
            $cek          = $this->M_global->updateData('m_kategori', $isi, ['kode_kategori' => $kodeKategori]);

            $cek_param    = 'mengubah';
        }

        if ($cek) { // jika fungsi berjalan
            aktifitas_user('Master Kategori', $cek_param, $kodeKategori, $this->M_global->getData('m_kategori', ['kode_kategori' => $kodeKategori])->keterangan);

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi ambil informasi kategori berdasarkan kode kategori
    public function getInfoKat($kode_kategori)
    {
        // ambil data kategori berdasarkan kode_kategori
        $data = $this->M_global->getData('m_kategori', ['kode_kategori' => $kode_kategori]);
        // lempar ke view
        echo json_encode($data);
    }

    // fungsi hapus kategori berdasarkan kode_kategori
    public function delKat($kode_kategori)
    {
        // jalankan fungsi hapus kategori berdasarkan kode_kategori
        aktifitas_user('Master Kategori', 'menghapus', $kode_kategori, $this->M_global->getData('m_kategori', ['kode_kategori' => $kode_kategori])->keterangan);
        $cek = $this->M_global->delData('m_kategori', ['kode_kategori' => $kode_kategori]);

        if ($cek) { // jika fungsi berjalan

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // ############################################################################################################################################################################

    /**
     * Master Pemasok
     * untuk menampilkan, menambahkan, dan mengubah pemasok dalam sistem
     */

    // supplier page
    public function supplier()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter   = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Pemasok',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Master/supplier_list',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Master/Umum/Supplier', $parameter);
    }

    // form supplier page
    public function form_supplier($param)
    {
        // website config
        $web_setting  = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version  = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        if ($param != '0') {
            $supplier = $this->M_global->getData('m_supplier', ['kode_supplier' => $param]);
        } else {
            $supplier = null;
        }

        $parameter = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Pemasok',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => '',
            'supplier'      => $supplier,
        ];

        $this->template->load('Template/Content', 'Master/Umum/Form_supplier', $parameter);
    }

    // fungsi list supplier
    public function supplier_list($param1 = '')
    {
        // parameter untuk list table
        $table            = 'm_supplier';
        $colum            = ['id', 'kode_supplier', 'nama', 'nohp', 'alamat', 'email', 'fax'];
        $order            = 'id';
        $order2           = 'desc';
        $order_arr        = ['id' => 'asc'];
        $kondisi_param1   = '';

        // kondisi role
        $updated          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;

        if ($updated > 0) {
            $upd_diss     = '';
        } else {
            $upd_diss     = 'disabled';
        }

        // table server side tampung kedalam variable $list
        $list             = $this->M_datatables->get_datatables($table, $colum, $order_arr, $order, $order2, $param1, $kondisi_param1);
        $data             = [];
        $no               = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {
            if ($deleted > 0) {
                $cekIsset1        = $this->M_global->jumDataRow('barang_in_header', ['kode_supplier' => $rd->kode_supplier]);
                if ($cekIsset1 > 0) {
                    $del_diss     = 'disabled';
                } else {
                    $cekIsset2    = $this->M_global->jumDataRow('barang_in_retur_header', ['kode_supplier' => $rd->kode_supplier]);
                    if ($cekIsset2 > 0) {
                        $del_diss = 'disabled';
                    } else {
                        $del_diss = '';
                    }
                }
            } else {
                $del_diss         = 'disabled';
            }

            $row    = [];
            $row[]  = $no++;
            $row[]  = $rd->kode_supplier;
            $row[]  = $rd->nama;
            $row[]  = $rd->nohp;
            $row[]  = $rd->email;
            $row[]  = $rd->fax;
            $row[]  = $rd->alamat;
            $row[]  = '<div class="text-center">
                <button type="button" class="btn btn-warning" style="margin-bottom: 5px;" onclick="ubah(' . "'" . $rd->kode_supplier . "'" . ')" ' . $upd_diss . '><i class="fa-regular fa-pen-to-square"></i></button>
                <button type="button" class="btn btn-danger" style="margin-bottom: 5px;" onclick="hapus(' . "'" . $rd->kode_supplier . "'" . ')" ' . $del_diss . '><i class="fa-regular fa-circle-xmark"></i></button>
            </div>';
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

    // fungsi cek supplier berdasarkan nama supplier
    public function cekSup()
    {
        // ambil nama inputan
        $nama = $this->input->post('nama');

        // cek nama pada table m_supplier
        $cek  = $this->M_global->jumDataRow('m_supplier', ['nama' => $nama]);

        if ($cek < 1) { // jika tidak ada/ kurang dari 1
            // kirimkan status 1
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi proses simpan/update supplier
    public function supplier_proses($param)
    {
        // variable
        $nama   = $this->input->post('nama');
        $nohp   = $this->input->post('nohp');
        $alamat = $this->input->post('alamat');
        $email  = $this->input->post('email');
        $fax    = $this->input->post('fax');

        if ($param == 1) { // jika parameternya 1
            // maka buat kode baru
            $kodeSupplier = _kodeSupplier();
        } else { // selain itu
            // ambil kode dari inputan
            $kodeSupplier = $this->input->post('kodeSupplier');
        }

        // tampung variable kedalam $isi
        $isi = [
            'kode_supplier' => $kodeSupplier,
            'nama'          => $nama,
            'nohp'          => $nohp,
            'alamat'        => $alamat,
            'email'         => $email,
            'fax'           => $fax,
        ];

        if ($param == 1) { // jika parameternya 1
            // jalankan fungsi simpan
            $cek          = $this->M_global->insertData('m_supplier', $isi);

            $cek_param    = 'menambahkan';
        } else { // selain itu
            // jalankan fungsi update
            $cek          = $this->M_global->updateData('m_supplier', $isi, ['kode_supplier' => $kodeSupplier]);

            $cek_param    = 'mengubah';
        }

        if ($cek) { // jika fungsi berjalan
            aktifitas_user('Master Pemasok', $cek_param, $kodeSupplier, $this->M_global->getData('m_supplier', ['kode_supplier' => $kodeSupplier])->nama);

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi hapus supplier berdasarkan kode_supplier
    public function delSup($kode_supplier)
    {
        // jalankan fungsi hapus supplier berdasarkan kode_supplier
        aktifitas_user('Master Pemasok', 'menghapus', $kode_supplier, $this->M_global->getData('m_supplier', ['kode_supplier' => $kode_supplier])->nama);
        $cek = $this->M_global->delData('m_supplier', ['kode_supplier' => $kode_supplier]);

        if ($cek) { // jika fungsi berjalan

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // ############################################################################################################################################################################

    /**
     * Master Gudang
     * untuk menampilkan, menambahkan, dan mengubah gudang dalam sistem
     */

    // gudang page
    public function gudang()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter   = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Gudang',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Master/gudang_list',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Master/Umum/Gudang', $parameter);
    }

    // form gudang page
    public function form_gudang($param)
    {
        // website config
        $web_setting    = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version    = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        if ($param != '0') {
            $gudang     = $this->M_global->getData('m_gudang', ['kode_gudang' => $param]);
        } else {
            $gudang     = null;
        }

        $parameter = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Gudang',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => '',
            'gudang'        => $gudang,
        ];

        $this->template->load('Template/Content', 'Master/Umum/Form_gudang', $parameter);
    }

    // fungsi list gudang
    public function gudang_list($param1 = '')
    {
        // parameter untuk list table
        $table            = 'm_gudang';
        $colum            = ['id', 'kode_gudang', 'nama', 'bagian', 'keterangan', 'aktif', 'utama'];
        $order            = 'id';
        $order2           = 'desc';
        $order_arr        = ['id' => 'asc'];
        $kondisi_param1   = 'bagian';

        // kondisi role
        $updated          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;

        if ($updated > 0) {
            $upd_diss     = '';
        } else {
            $upd_diss     = 'disabled';
        }

        // table server side tampung kedalam variable $list
        $list             = $this->M_datatables->get_datatables($table, $colum, $order_arr, $order, $order2, $param1, $kondisi_param1);
        $data             = [];
        $no               = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {
            if ($deleted > 0) {
                $cekIsset1        = $this->M_global->jumDataRow('barang_in_header', ['kode_gudang' => $rd->kode_gudang]);
                if ($cekIsset1 > 0) {
                    $del_diss     = 'disabled';
                } else {
                    $cekIsset2    = $this->M_global->jumDataRow('barang_in_retur_header', ['kode_gudang' => $rd->kode_gudang]);
                    if ($cekIsset2 > 0) {
                        $del_diss = 'disabled';
                    } else {
                        $del_diss = '';
                    }
                }
            } else {
                $del_diss         = 'disabled';
            }

            $row    = [];
            $row[]  = $no;
            $row[]  = $rd->kode_gudang;
            $row[]  = $rd->nama;
            $row[]  = $rd->bagian;
            $row[]  = '<div class="text-center">' . (($rd->aktif > 0) ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-dark">Non-aktif</span>') . '</div>';
            $row[]  = $rd->keterangan;
            $row[]  = '<div class="text-center">' . '<input type="checkbox" class="form-control" name="default_ppn" id="default_ppn' . $no . '" ' . ($rd->utama == 1 ? 'checked' : '') . '  onclick="set_default(' . "'" . $rd->kode_gudang . "', '" . $no . "'" . ')" ' . (($rd->utama == 1) ? 'disabled' : '') . '>' . '</div>';
            $row[]  = '<div class="text-center">
                    <button type="button" class="btn btn-warning" style="margin-bottom: 5px;" onclick="ubah(' . "'" . $rd->kode_gudang . "'" . ')" ' . $upd_diss . '><i class="fa-regular fa-pen-to-square"></i></button>
                    <button type="button" class="btn btn-danger" style="margin-bottom: 5px;" onclick="hapus(' . "'" . $rd->kode_gudang . "'" . ')" ' . $del_diss . '><i class="fa-regular fa-circle-xmark"></i></button>
            </div>';
            $data[] = $row;

            $no++;
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

    public function setDefGudang($kode_gudang)
    {
        $cek = $this->db->query("UPDATE m_gudang SET utama = 0");

        if ($cek) {
            $cek2 = $this->db->query("UPDATE m_gudang SET utama = 1 WHERE kode_gudang = '$kode_gudang'");
        } else {
            $cek2 = TRUE;
        }

        if ($cek2) {
            echo json_encode(['status' => 1]);
        } else {
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi cek gudang berdasarkan nama gudang
    public function cekGud()
    {
        // ambil nama inputan
        $nama = $this->input->post('nama');

        // cek nama pada table m_gudang
        $cek  = $this->M_global->jumDataRow('m_gudang', ['nama' => $nama]);

        if ($cek < 1) { // jika tidak ada/ kurang dari 1
            // kirimkan status 1
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi proses simpan/update gudang
    public function gudang_proses($param)
    {
        // variable
        $nama             = $this->input->post('nama');
        $bagian           = $this->input->post('bagian');
        $aktif            = $this->input->post('aktif');
        $keterangan       = $this->input->post('keterangan');

        if ($param == 1) { // jika parameternya 1
            // maka buat kode baru
            $kodeGudang   = _kodeGudang($nama);
        } else { // selain itu
            // ambil kode dari inputan
            $kodeGudang   = $this->input->post('kodeGudang');
        }

        // tampung variable kedalam $isi
        $isi = [
            'kode_gudang' => $kodeGudang,
            'nama'        => $nama,
            'bagian'      => $bagian,
            'aktif'       => $aktif,
            'keterangan'  => $keterangan,
        ];

        if ($param == 1) { // jika parameternya 1
            // jalankan fungsi simpan
            $cek          = $this->M_global->insertData('m_gudang', $isi);

            $cek_param    = 'menambahkan';
        } else { // selain itu
            // jalankan fungsi update
            $cek          = $this->M_global->updateData('m_gudang', $isi, ['kode_gudang' => $kodeGudang]);

            $cek_param    = 'mengubah';
        }

        if ($cek) { // jika fungsi berjalan
            aktifitas_user('Master Gudang', $cek_param, $kodeGudang, $this->M_global->getData('m_gudang', ['kode_gudang' => $kodeGudang])->nama);

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi hapus gudang berdasarkan kode_gudang
    public function delGud($kode_gudang)
    {
        // jalankan fungsi hapus gudang berdasarkan kode_gudang
        aktifitas_user('Master Gudang', 'menghapus', $kode_gudang, $this->M_global->getData('m_gudang', ['kode_gudang' => $kode_gudang])->nama);
        $cek = $this->M_global->delData('m_gudang', ['kode_gudang' => $kode_gudang]);

        if ($cek) { // jika fungsi berjalan

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // ############################################################################################################################################################################

    /**
     * Master Bank
     * untuk menampilkan, menambahkan, dan mengubah bank dalam sistem
     */

    // bank page
    public function bank()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter   = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Bank',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Master/bank_list',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Master/Umum/Bank', $parameter);
    }

    // fungsi list bank
    public function bank_list($param1 = '')
    {
        // parameter untuk list table
        $table            = 'm_bank';
        $colum            = ['id', 'kode_bank', 'keterangan'];
        $order            = 'id';
        $order2           = 'desc';
        $order_arr        = ['id' => 'asc'];
        $kondisi_param1   = '';

        // kondisi role
        $updated          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;

        if ($updated > 0) {
            $upd_diss     = '';
        } else {
            $upd_diss     = 'disabled';
        }

        if ($deleted > 0) {
            $del_diss     = '';
        } else {
            $del_diss     = 'disabled';
        }

        // table server side tampung kedalam variable $list
        $list             = $this->M_datatables->get_datatables($table, $colum, $order_arr, $order, $order2, $param1, $kondisi_param1);
        $data             = [];
        $no               = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {
            $row    = [];
            $row[]  = $no++;
            $row[]  = $rd->kode_bank;
            $row[]  = $rd->keterangan;
            $row[]  = '<div class="text-center">
                <button type="button" class="btn btn-warning" style="margin-bottom: 5px;" onclick="ubah(' . "'" . $rd->kode_bank . "'" . ')" ' . $upd_diss . '><i class="fa-regular fa-pen-to-square"></i></button>
                <button type="button" class="btn btn-danger" style="margin-bottom: 5px;" onclick="hapus(' . "'" . $rd->kode_bank . "'" . ')" ' . $del_diss . '><i class="fa-regular fa-circle-xmark"></i></button>
            </div>';
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

    // fungsi cek bank berdasarkan keterangan bank
    public function cekBank()
    {
        // ambil keterangan inputan
        $keterangan   = $this->input->post('keterangan');

        // cek keterangan pada table m_bank
        $cek          = $this->M_global->jumDataRow('m_bank', ['keterangan' => $keterangan]);

        if ($cek < 1) { // jika tidak ada/ kurang dari 1
            // kirimkan status 1
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi proses simpan/update bank
    public function bank_proses($param)
    {
        // variable
        $keterangan   = $this->input->post('keterangan');

        if ($param == 1) { // jika parameternya 1
            // maka buat kode baru
            $kodeBank = _kodeBank();
        } else { // selain itu
            // ambil kode dari inputan
            $kodeBank = $this->input->post('kodeBank');
        }

        // tampung variable kedalam $isi
        $isi = [
            'kode_bank'     => $kodeBank,
            'keterangan'    => $keterangan,
        ];

        if ($param == 1) { // jika parameternya 1
            // jalankan fungsi simpan
            $cek          = $this->M_global->insertData('m_bank', $isi);

            $cek_param    = 'menambahkan';
        } else { // selain itu
            // jalankan fungsi update
            $cek          = $this->M_global->updateData('m_bank', $isi, ['kode_bank' => $kodeBank]);

            $cek_param    = 'mengubah';
        }

        if ($cek) { // jika fungsi berjalan
            aktifitas_user('Master Bank EDC', $cek_param, $kodeBank, $this->M_global->getData('m_bank', ['kode_bank' => $kodeBank])->keterangan);

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi ambil informasi bank berdasarkan kode bank
    public function getInfoBank($kode_bank)
    {
        // ambil data bank berdasarkan kode_bank
        $data = $this->M_global->getData('m_bank', ['kode_bank' => $kode_bank]);
        // lempar ke view
        echo json_encode($data);
    }

    // fungsi hapus bank berdasarkan kode_bank
    public function delBank($kode_bank)
    {
        // jalankan fungsi hapus bank berdasarkan kode_bank
        aktifitas_user('Master Bank EDC', 'menghapus', $kode_bank, $this->M_global->getData('m_bank', ['kode_bank' => $kode_bank])->keterangan);
        $cek = $this->M_global->delData('m_bank', ['kode_bank' => $kode_bank]);

        if ($cek) { // jika fungsi berjalan

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // ############################################################################################################################################################################

    /**
     * Master Pekerjaan
     * untuk menampilkan, menambahkan, dan mengubah pekerjaan dalam sistem
     */

    // pekerjaan page
    public function pekerjaan()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter   = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Pekerjaan',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Master/pekerjaan_list',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Master/Umum/Pekerjaan', $parameter);
    }

    // fungsi list pekerjaan
    public function pekerjaan_list($param1 = '')
    {
        // parameter untuk list table
        $table            = 'm_pekerjaan';
        $colum            = ['id', 'kode_pekerjaan', 'keterangan'];
        $order            = 'id';
        $order2           = 'desc';
        $order_arr        = ['id' => 'asc'];
        $kondisi_param1   = '';

        // kondisi role
        $updated          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;

        if ($updated > 0) {
            $upd_diss     = '';
        } else {
            $upd_diss     = 'disabled';
        }

        // table server side tampung kedalam variable $list
        $list             = $this->M_datatables->get_datatables($table, $colum, $order_arr, $order, $order2, $param1, $kondisi_param1);
        $data             = [];
        $no               = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {
            if ($deleted > 0) {
                $cekIsset       = $this->M_global->getData('member', ['pekerjaan' => $rd->kode_pekerjaan]);
                if ($cekIsset) {
                    $del_diss   = 'disabled';
                } else {
                    $del_diss   = '';
                }
            } else {
                $del_diss       = 'disabled';
            }

            $row    = [];
            $row[]  = $no++;
            $row[]  = $rd->kode_pekerjaan;
            $row[]  = $rd->keterangan;
            $row[]  = '<div class="text-center">
                <button type="button" class="btn btn-warning" style="margin-bottom: 5px;" onclick="ubah(' . "'" . $rd->kode_pekerjaan . "'" . ')" ' . $upd_diss . '><i class="fa-regular fa-pen-to-square"></i></button>
                <button type="button" class="btn btn-danger" style="margin-bottom: 5px;" onclick="hapus(' . "'" . $rd->kode_pekerjaan . "'" . ')" ' . $del_diss . '><i class="fa-regular fa-circle-xmark"></i></button>
            </div>';
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

    // fungsi cek pekerjaan berdasarkan keterangan pekerjaan
    public function cekPekerjaan()
    {
        // ambil keterangan inputan
        $keterangan   = $this->input->post('keterangan');

        // cek keterangan pada table m_pekerjaan
        $cek          = $this->M_global->jumDataRow('m_pekerjaan', ['keterangan' => $keterangan]);

        if ($cek < 1) { // jika tidak ada/ kurang dari 1
            // kirimkan status 1
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi proses simpan/update pekerjaan
    public function pekerjaan_proses($param)
    {
        // variable
        $keterangan = $this->input->post('keterangan');

        if ($param == 1) { // jika parameternya 1
            // maka buat kode baru
            $kodePekerjaan = _kodePekerjaan();
        } else { // selain itu
            // ambil kode dari inputan
            $kodePekerjaan = $this->input->post('kodePekerjaan');
        }

        // tampung variable kedalam $isi
        $isi = [
            'kode_pekerjaan' => $kodePekerjaan,
            'keterangan'     => $keterangan,
        ];

        if ($param == 1) { // jika parameternya 1
            // jalankan fungsi simpan
            $cek          = $this->M_global->insertData('m_pekerjaan', $isi);

            $cek_param    = 'menambahkan';
        } else { // selain itu
            // jalankan fungsi update
            $cek          = $this->M_global->updateData('m_pekerjaan', $isi, ['kode_pekerjaan' => $kodePekerjaan]);

            $cek_param    = 'mengubah';
        }

        if ($cek) { // jika fungsi berjalan
            aktifitas_user('Master Pekerjaan', $cek_param, $kodePekerjaan, $this->M_global->getData('m_pekerjaan', ['kode_pekerjaan' => $kodePekerjaan])->keterangan);

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi ambil informasi pekerjaan berdasarkan kode pekerjaan
    public function getInfoPekerjaan($kode_pekerjaan)
    {
        // ambil data pekerjaan berdasarkan kode_pekerjaan
        $data = $this->M_global->getData('m_pekerjaan', ['kode_pekerjaan' => $kode_pekerjaan]);
        // lempar ke view
        echo json_encode($data);
    }

    // fungsi hapus pekerjaan berdasarkan kode_pekerjaan
    public function delPekerjaan($kode_pekerjaan)
    {
        // jalankan fungsi hapus pekerjaan berdasarkan kode_pekerjaan
        aktifitas_user('Master Pekerjaan', 'menghapus', $kode_pekerjaan, $this->M_global->getData('m_pekerjaan', ['kode_pekerjaan' => $kode_pekerjaan])->keterangan);
        $cek = $this->M_global->delData('m_pekerjaan', ['kode_pekerjaan' => $kode_pekerjaan]);

        if ($cek) { // jika fungsi berjalan

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // ############################################################################################################################################################################

    /**
     * Master Barang
     * untuk menampilkan, menambahkan, dan mengubah barang dalam sistem
     */

    // barang page
    public function barang()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter   = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Barang',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Master/barang_list',
            'param1'        => '',
            'kategori'      => $this->M_global->getResult('m_kategori'),
        ];

        $this->template->load('Template/Content', 'Master/Internal/Barang', $parameter);
    }

    // form barang page
    public function form_barang($param)
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        if ($param == '0') {
            $barang = null;
        } else {
            $barang = $this->M_global->getData('barang', ['kode_barang' => $param]);
        }

        $parameter = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Barang',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => '',
            'barang'        => $barang,
            'satuan1'       => $this->M_global->getData('barang_satuan', ['kode_barang' => $param, 'ke' => 1]),
            'satuan2'       => $this->M_global->getData('barang_satuan', ['kode_barang' => $param, 'ke' => 2]),
            'satuan3'       => $this->M_global->getData('barang_satuan', ['kode_barang' => $param, 'ke' => 3]),
            'kategori'      => $this->M_global->getResult('m_kategori'),
            'm_satuan'      => $this->M_global->getResult('m_satuan'),
            'jenis'         => $this->M_global->getResult('m_jenis'),
            'barang_jenis'  => $this->M_global->getDataResult('barang_jenis', ['kode_barang' => $param]),
            'cabang_all'    => $this->M_global->getResult('cabang'),
            'barang_cabang' => $this->M_global->getDataResult('barang_cabang', ['kode_barang' => $param]),
            'pajak'         => $this->M_global->getData('m_pajak', ['id' => 1])->persentase,
        ];

        $this->template->load('Template/Content', 'Master/Internal/Form_barang', $parameter);
    }

    // fungsi list barang
    public function barang_list($param = '')
    {
        // kondisi role
        $updated        = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted        = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;

        if ($updated > 0) {
            $upd_diss   = '';
        } else {
            $upd_diss   = 'disabled';
        }

        // table server side tampung kedalam variable $list
        $list           = $this->M_barang->get_datatables($param);
        $data           = [];
        $no             = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {
            if ($deleted > 0) {
                $cekIsset       = $this->M_global->jumDataRow('barang_in_detail', ['kode_barang' => $rd->kode_barang]);
                $cekIsset2      = $this->M_global->jumDataRow('barang_in_retur_detail', ['kode_barang' => $rd->kode_barang]);
                $cekIsset3      = $this->M_global->jumDataRow('barang_out_detail', ['kode_barang' => $rd->kode_barang]);
                $cekIsset4      = $this->M_global->jumDataRow('barang_out_retur_detail', ['kode_barang' => $rd->kode_barang]);

                if ($cekIsset > 0 || $cekIsset2 > 0 || $cekIsset3 > 0 || $cekIsset4 > 0) {
                    $del_diss   = 'disabled';
                } else {
                    $del_diss   = '';
                }
            } else {
                $del_diss       = 'disabled';
            }

            $satuan1    = $this->M_global->getData('m_satuan', ['kode_satuan' => $rd->kode_satuan]);
            $satuan2    = $this->M_global->getData('m_satuan', ['kode_satuan' => $rd->kode_satuan2]);
            $satuan3    = $this->M_global->getData('m_satuan', ['kode_satuan' => $rd->kode_satuan3]);

            $row        = [];
            $row[]      = $no++;
            $row[]      = $rd->kode_barang . '<br><a type="button" style="margin-bottom: 5px;" class="btn btn-dark" target="_blank" href="' . site_url('Master/print_barcode/') . $rd->kode_barang . '"><i class="fa-solid fa-barcode"></i> Barcode</a>';
            $row[]      = $rd->nama;
            $row[]      = $satuan1->keterangan . ((!empty($satuan2) ? '<br>' . $satuan2->keterangan . ' ~ ' . number_format($rd->qty_satuan2) . ' ' . $satuan1->keterangan : '')) . ((!empty($satuan3) ? '<br>' . $satuan3->keterangan . ' ~ ' . number_format($rd->qty_satuan3) . ' ' . $satuan1->keterangan : ''));
            $row[]      = $this->M_global->getData('m_kategori', ['kode_kategori' => $rd->kode_kategori])->keterangan;
            $row[]      = '<div class="text-right">' . number_format($rd->hna) . '</div>';
            $row[]      = '<div class="text-right">' . number_format($rd->hpp) . '</div>';
            $row[]      = '<div class="text-right">' . number_format($rd->harga_jual) . '</div>';
            $row[]      = '<div class="text-right">' . number_format($rd->nilai_persediaan) . '</div>';
            $row[]      = '<div class="text-center">
                <button type="button" style="margin-bottom: 5px;" class="btn btn-warning" onclick="ubah(' . "'" . $rd->kode_barang . "'" . ')" ' . $upd_diss . '><i class="fa-regular fa-pen-to-square"></i></button>
                <button type="button" style="margin-bottom: 5px;" class="btn btn-danger" onclick="hapus(' . "'" . $rd->kode_barang . "'" . ')" ' . $del_diss . '><i class="fa-regular fa-circle-xmark"></i></button>
            </div>';
            $data[]     = $row;
        }

        // hasil server side
        $output = [
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->M_barang->count_all($param),
            "recordsFiltered" => $this->M_barang->count_filtered($param),
            "data"            => $data,
        ];

        // kirimkan ke view
        echo json_encode($output);
    }

    // fungsi print barcode
    public function print_barcode($kode_barang)
    {
        barcode($kode_barang);
    }

    // fungsi cek barang berdasarkan nama barang
    public function cekBar()
    {
        // ambil nama inputan
        $nama = $this->input->post('nama');

        // cek nama pada table barang
        $cek  = $this->M_global->jumDataRow('barang', ['nama' => $nama]);

        if ($cek < 1) { // jika tidak ada/ kurang dari 1
            // kirimkan status 1
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi proses simpan/update barang
    public function barang_proses($param)
    {
        // variable
        $input_kode         = $this->input->post('kodeBarang');
        $nama               = $this->input->post('nama');
        $kode_satuan        = $this->input->post('kode_satuan');
        $kode_satuan2       = $this->input->post('kode_satuan2');
        $kode_satuan3       = $this->input->post('kode_satuan3');
        $qty_satuan2        = $this->input->post('qty_satuan2');
        $qty_satuan3        = $this->input->post('qty_satuan3');
        $opsi_hpp           = $this->input->post('opsi_hpp');
        $persentase_hpp     = str_replace(",", "", $this->input->post('persentase_hpp'));
        $kode_kategori      = $this->input->post('kode_kategori');
        $kode_jenis         = $this->input->post('kode_jenis');
        $hna                = str_replace(",", "", $this->input->post('hna'));
        $hpp                = str_replace(",", "", $this->input->post('hpp'));
        $harga_jual         = str_replace(",", "", $this->input->post('harga_jual'));
        $nilai_persediaan   = str_replace(",", "", $this->input->post('nilai_persediaan'));
        $stok_min           = str_replace(",", "", $this->input->post('stok_min'));
        $stok_max           = str_replace(",", "", $this->input->post('stok_max'));
        $kode_cabang        = $this->session->userdata('cabang');

        if ($param == 1) { // jika parameternya 1
            // maka buat kode baru
            if ($input_kode == "") {
                $kodeBarang = _kodeBarang($nama);
            } else {
                $kodeBarang = $input_kode;
            }
        } else { // selain itu
            // ambil kode dari inputan
            $kodeBarang = $input_kode;
        }

        // configurasi upload file
        $config['upload_path']    = 'assets/img/obat/';
        $config['allowed_types']  = 'jpg|png|jpeg';
        $config['max_size']       = '10240';
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($_FILES['filefoto']['name']) { // jika file didapatkan nama filenya
            // upload file
            $this->upload->do_upload('filefoto');

            // ambil namanya berdasarkan nama file upload
            $image                = $this->upload->data('file_name');
        } else { // selain itu
            // beri nilai default
            $cek_barang           = $this->M_global->getData('barang', ['kode_barang' => $kodeBarang]);
            if ($cek_barang) {
                $image            = $cek_barang->image;
            } else {
                $image            = 'default.jpg';
            }
        }

        // dell_field('barang', 'kode_jenis');

        $isi = [
            'kode_barang'       => $kodeBarang,
            'nama'              => $nama,
            'kode_satuan'       => $kode_satuan,
            'kode_satuan2'      => $kode_satuan2,
            'kode_satuan3'      => $kode_satuan3,
            'qty_satuan2'       => $qty_satuan2,
            'qty_satuan3'       => $qty_satuan3,
            'opsi_hpp'          => $opsi_hpp,
            'persentase_hpp'    => $persentase_hpp,
            'kode_kategori'     => $kode_kategori,
            'image'             => $image,
            'hna'               => $hna,
            'hpp'               => $hpp,
            'harga_jual'        => $harga_jual,
            'nilai_persediaan'  => $nilai_persediaan,
            'stok_min'          => $stok_min,
            'stok_max'          => $stok_max,
        ];

        if ($param == 1) { // jika parameternya 1
            // jalankan fungsi simpan
            $cek = [
                $this->M_global->insertData('barang', $isi),
            ];

            $cek_param = 'menambahkan';
        } else { // selain itu
            // jalankan fungsi update
            $cek = [
                $this->M_global->updateData('barang', $isi, ['kode_barang' => $kodeBarang]),
                $this->M_global->delData('barang_jenis', ['kode_barang' => $kodeBarang]),
                $this->M_global->delData('barang_cabang', ['kode_barang' => $kodeBarang]),
            ];

            $cek_param = 'mengubah';
        }

        // barang cabang
        $kode_cabang = $this->input->post('kode_cabang');
        foreach ($kode_cabang as $kc) {
            $_cabang        = $kc;
            $data_cabang    = [
                'kode_cabang' => $_cabang,
                'kode_barang' => $kodeBarang,
            ];

            $this->M_global->insertData('barang_cabang', $data_cabang);
        }

        foreach ($kode_jenis as $kj) {
            $_kode_jenis    = $kj;
            $isi_jenis      = [
                'kode_jenis'    => $_kode_jenis,
                'kode_barang'   => $kodeBarang,
            ];

            $this->M_global->insertData('barang_jenis', $isi_jenis);
        }

        if ($cek) { // jika fungsi berjalan
            aktifitas_user('Master Barang', $cek_param, $kodeBarang, $this->M_global->getData('barang', ['kode_barang' => $kodeBarang])->nama, $isi);

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi hapus barang berdasarkan kode_barang
    public function delBar($kode_barang)
    {
        // jalankan fungsi hapus barang berdasarkan kode_barang
        $barang_cabang = count($this->M_global->getDataResult('barang_cabang', ['kode_barang' => $kode_barang, 'kode_cabang <> ' => $this->session->userdata('cabang')]));

        if ($barang_cabang > 0) {
            echo json_encode(['status' => 2]);
        } else {
            aktifitas_user('Master Barang', 'menghapus', $kode_barang, $this->M_global->getData('barang', ['kode_barang' => $kode_barang])->nama);
            $cek = [
                $this->M_global->delData('barang', ['kode_barang' => $kode_barang]),
                $this->M_global->delData('barang_cabang', ['kode_barang' => $kode_barang]),
                $this->M_global->delData('barang_jenis', ['kode_barang' => $kode_barang]),
            ];

            if ($cek) { // jika fungsi berjalan

                // kirimkan status 1 ke view
                echo json_encode(['status' => 1]);
            } else { // selain itu
                // kirimkan status 0 ke view
                echo json_encode(['status' => 0]);
            }
        }
    }

    // ############################################################################################################################################################################

    /**
     * Master Logistik barang
     * untuk menampilkan, menambahkan, dan mengubah logistik dalam sistem
     */

    // logistik page
    public function logistik()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter   = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Logistik',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Master/logistik_list',
            'param1'        => '',
            'kategori'      => $this->M_global->getResult('m_kategori'),
        ];

        $this->template->load('Template/Content', 'Master/Internal/Logistik', $parameter);
    }

    // form logistik page
    public function form_logistik($param)
    {
        // website config
        $web_setting    = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version    = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        if ($param != '0') {
            $logistik   = $this->M_global->getData('logistik', ['kode_logistik' => $param]);
        } else {
            $logistik   = null;
        }

        $parameter = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Logistik',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => '',
            'logistik'      => $logistik,
            'satuan'        => $this->M_global->getResult('m_satuan'),
            'kategori'      => $this->M_global->getResult('m_kategori'),
            'cabang_all'    => $this->M_global->getResult('cabang'),
            'barang_cabang' => $this->M_global->getDataResult('logistik_cabang', ['kode_barang' => $param]),
            'pajak'         => $this->M_global->getData('m_pajak', ['id' => 1])->persentase,
        ];

        $this->template->load('Template/Content', 'Master/Internal/Form_logistik', $parameter);
    }

    // fungsi list logistik
    public function logistik_list($param1 = '')
    {
        // parameter untuk list table
        $table            = 'logistik';
        $colum            = ['id', 'kode_logistik', 'nama', 'kode_satuan', 'kode_kategori', 'hna', 'hpp', 'harga_jual', 'nilai_persediaan'];
        $order            = 'id';
        $order2           = 'desc';
        $order_arr        = ['id' => 'asc'];
        $kondisi_param1   = 'kode_kategori';

        // kondisi role
        $updated          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;

        if ($updated > 0) {
            $upd_diss     = '';
        } else {
            $upd_diss     = 'disabled';
        }

        if ($deleted > 0) {
            $del_diss     = '';
        } else {
            $del_diss     = 'disabled';
        }

        // table server side tampung kedalam variable $list
        $list             = $this->M_datatables->get_datatables($table, $colum, $order_arr, $order, $order2, $param1, $kondisi_param1);
        $data             = [];
        $no               = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {
            $row    = [];
            $row[]  = $no++;
            $row[]  = $rd->kode_logistik . '<br><a type="button" class="btn btn-dark" target="_blank" href="' . site_url('Master/print_barcode/') . $rd->kode_logistik . '"><i class="fa-solid fa-barcode"></i> Barcode</a>';
            $row[]  = $rd->nama;
            $row[]  = $this->M_global->getData('m_satuan', ['kode_satuan' => $rd->kode_satuan])->keterangan;
            $row[]  = $this->M_global->getData('m_kategori', ['kode_kategori' => $rd->kode_kategori])->keterangan;
            $row[]  = '<div class="text-right">' . number_format($rd->hna) . '</div>';
            $row[]  = '<div class="text-right">' . number_format($rd->hpp) . '</div>';
            $row[]  = '<div class="text-right">' . number_format($rd->harga_jual) . '</div>';
            $row[]  = '<div class="text-right">' . number_format($rd->nilai_persediaan) . '</div>';
            $row[]  = '<div class="text-center">
                <button type="button" class="btn btn-warning" onclick="ubah(' . "'" . $rd->kode_logistik . "'" . ')" ' . $upd_diss . '><i class="fa-regular fa-pen-to-square"></i></button>
                <button type="button" class="btn btn-danger" onclick="hapus(' . "'" . $rd->kode_logistik . "'" . ')" ' . $del_diss . '><i class="fa-regular fa-circle-xmark"></i></button>
            </div>';
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

    // fungsi cek logistik berdasarkan nama logistik
    public function cekLog()
    {
        // ambil nama inputan
        $nama = $this->input->post('nama');

        // cek nama pada table logistik
        $cek  = $this->M_global->jumDataRow('logistik', ['nama' => $nama]);

        if ($cek < 1) { // jika tidak ada/ kurang dari 1
            // kirimkan status 1
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi proses simpan/update logistik
    public function logistik_proses($param)
    {
        // variable
        $input_kode         = $this->input->post('kodeBarang');
        $nama               = $this->input->post('nama');
        $kode_satuan        = $this->input->post('kode_satuan');
        $kode_satuan2       = $this->input->post('kode_satuan2');
        $kode_satuan3       = $this->input->post('kode_satuan3');
        $kode_kategori      = $this->input->post('kode_kategori');
        $qty_satuan2        = str_replace(",", "", $this->input->post('qty_satuan2'));
        $qty_satuan3        = str_replace(",", "", $this->input->post('qty_satuan3'));
        $hna                = str_replace(",", "", $this->input->post('hna'));
        $hpp                = str_replace(",", "", $this->input->post('hpp'));
        $opsi_hpp           = str_replace(",", "", $this->input->post('opsi_hpp'));
        $persentase_hpp     = str_replace(",", "", $this->input->post('persentase_hpp'));
        $harga_jual         = str_replace(",", "", $this->input->post('harga_jual'));
        $nilai_persediaan   = str_replace(",", "", $this->input->post('nilai_persediaan'));

        if ($param == 1) { // jika parameternya 1
            // maka buat kode baru
            if ($input_kode == "") {
                $kodeLogistik   = _kodeLogistik($nama);
            } else {
                $kodeLogistik   = $input_kode;
            }
        } else { // selain itu
            // ambil kode dari inputan
            $kodeLogistik       = $this->input->post('kodeLogistik');
        }

        // tampung variable kedalam $isi
        $isi = [
            'kode_logistik'     => $kodeLogistik,
            'nama'              => $nama,
            'kode_satuan'       => $kode_satuan,
            'kode_satuan2'      => $kode_satuan2,
            'kode_satuan3'      => $kode_satuan3,
            'qty_satuan2'       => $qty_satuan2,
            'qty_satuan3'       => $qty_satuan3,
            'kode_kategori'     => $kode_kategori,
            'hna'               => $hna,
            'hpp'               => $hpp,
            'opsi_hpp'          => $opsi_hpp,
            'persentase_hpp'    => $persentase_hpp,
            'harga_jual'        => $harga_jual,
            'nilai_persediaan'  => $nilai_persediaan,
        ];

        if ($param == 1) { // jika parameternya 1
            // jalankan fungsi simpan
            $cek          = $this->M_global->insertData('logistik', $isi);

            $cek_param    = 'menambahkan';
        } else { // selain itu
            // jalankan fungsi update
            $cek = [
                $this->M_global->updateData('logistik', $isi, ['kode_logistik' => $kodeLogistik]),
                $this->M_global->delData('logistik_cabang', ['kode_barang' => $kodeLogistik]),
            ];

            $cek_param    = 'mengubah';
        }

        // barang cabang
        $kode_cabang = $this->input->post('kode_cabang');
        foreach ($kode_cabang as $kc) {
            $_cabang        = $kc;
            $data_cabang    = [
                'kode_cabang' => $_cabang,
                'kode_barang' => $kodeLogistik,
            ];

            $this->M_global->insertData('logistik_cabang', $data_cabang);
        }

        if ($cek) { // jika fungsi berjalan
            aktifitas_user('Master Logistik', $cek_param, $kodeLogistik, $this->M_global->getData('logistik', ['kode_logistik' => $kodeLogistik])->nama, $isi);

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi hapus logistik berdasarkan kode_logistik
    public function delLog($kode_logistik)
    {
        // jalankan fungsi hapus logistik berdasarkan kode_logistik
        $barang_cabang = count($this->M_global->getDataResult('logistik_cabang', ['kode_barang' => $kode_logistik, 'kode_cabang <> ' => $this->session->userdata('cabang')]));

        if ($barang_cabang > 0) {
            echo json_encode(['status' => 2]);
        } else {
            aktifitas_user('Master Logistik', 'menghapus', $kode_logistik, $this->M_global->getData('logistik', ['kode_logistik' => $kode_logistik])->nama);
            $cek = [
                $this->M_global->delData('logistik', ['kode_logistik' => $kode_logistik]),
                $this->M_global->delData('logistik_cabang', ['kode_barang' => $kode_logistik]),
            ];

            if ($cek) { // jika fungsi berjalan

                // kirimkan status 1 ke view
                echo json_encode(['status' => 1]);
            } else { // selain itu
                // kirimkan status 0 ke view
                echo json_encode(['status' => 0]);
            }
        }
    }

    // ############################################################################################################################################################################

    /**
     * Master Pengguna
     * untuk menampilkan, menambahkan, dan mengubah pengguna dalam sistem
     */

    // user page
    public function user()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter   = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Pengguna',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Master/user_list',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Master/Internal/Pengguna', $parameter);
    }

    // fungsi list user
    public function user_list($param1 = '')
    {
        // parameter untuk list table
        $table                  = 'user';
        $colum                  = ['id', 'kode_user', 'nama', 'email', 'password', 'secondpass', 'jkel', 'foto', 'kode_role', 'actived', 'joined', 'on_off'];
        $order                  = 'id';
        $order2                 = 'desc';
        $order_arr              = ['id' => 'asc'];
        $kondisi_param1         = '';

        // kondisi role
        $updated                = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted                = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;

        // table server side tampung kedalam variable $list
        $list                   = $this->M_datatables->get_datatables($table, $colum, $order_arr, $order, $order2, $param1, $kondisi_param1);
        $data                   = [];
        $no                     = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {
            if ($updated > 0) {
                if ($rd->on_off < 1) {
                    $upd_diss   = '';
                } else {
                    $upd_diss   = 'disabled';
                }
            } else {
                $upd_diss       = 'disabled';
            }

            if ($deleted > 0) {
                if ($rd->on_off < 1) {
                    $del_diss   = '';
                } else {
                    $del_diss   = 'disabled';
                }
            } else {
                $del_diss       = 'disabled';
            }

            $row    = [];
            $row[]  = $no++;
            $row[]  = $rd->kode_user;
            $row[]  = $rd->nama;
            $row[]  = $rd->email;
            $row[]  = (($rd->jkel == 'P') ? 'Laki-laki' : 'Perempuan');
            $row[]  = $this->M_global->getData("m_role", ["kode_role" => $rd->kode_role])->keterangan;
            $row[]  = '<div class="text-center">' . (($rd->actived == 1) ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-dark">Non-aktif</span>') . '</div>';

            if ($rd->actived > 0) {
                $actived_akun = '<button type="button" style="margin-bottom: 5px;" class="btn btn-info" onclick="actived(' . "'" . $rd->kode_user . "', 0" . ')" ' . $upd_diss . '><i class="fa-solid fa-user-xmark"></i></button>';
            } else {
                $actived_akun = '<button type="button" style="margin-bottom: 5px;" class="btn btn-info" onclick="actived(' . "'" . $rd->kode_user . "', 1" . ')" ' . $upd_diss . '><i class="fa-solid fa-user-check"></i></button>';
            }

            $row[]  = '<div class="text-center">
                ' . $actived_akun . '
                <button type="button" style="margin-bottom: 5px;" class="btn btn-warning" onclick="ubah(' . "'" . $rd->kode_user . "'" . ')" ' . $upd_diss . '><i class="fa-regular fa-pen-to-square"></i></button>
                <button type="button" style="margin-bottom: 5px;" class="btn btn-danger" onclick="hapus(' . "'" . $rd->kode_user . "'" . ')" ' . $del_diss . '><i class="fa-regular fa-circle-xmark"></i></button>
            </div>';
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

    // fungsi aktif/nonaktif user
    public function activeduser($kode_user, $param)
    {
        // jalankan fungsi update actived user
        $cek = $this->M_global->updateData('user', ['actived' => $param], ['kode_user' => $kode_user]);

        if ($cek) { // jika fungsi berjalan
            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi cek user
    public function cekUser()
    {
        $email = $this->input->post('email');

        $cek   = $this->M_global->jumDataRow('user', ['email' => $email]);

        if ($cek < 1) {
            echo json_encode(['status' => 1]);
        } else {
            echo json_encode(['status' => 0]);
        }
    }

    // form user page
    public function form_user($param)
    {
        // website config
        $web_setting    = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version    = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        if ($param != '0') {
            $user       = $this->M_global->getData('user', ['kode_user' => $param]);
        } else {
            $user       = null;
        }

        $parameter = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Pengguna',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => '',
            'data_user'     => $user,
            'role'          => $this->M_global->getResult('m_role'),
        ];

        $this->template->load('Template/Content', 'Master/Internal/Form_user', $parameter);
    }

    // fungsi user proses
    public function user_proses($param)
    {
        // variable
        $nama         = $this->input->post('nama');
        $email        = $this->input->post('email');
        $secondpass   = $this->input->post('password');
        $password     = md5($secondpass);
        $jkel         = $this->input->post('jkel');
        $kode_role    = $this->input->post('kode_role');
        $nohp         = $this->input->post('nohp');

        // cek jkel untuk foto
        if ($jkel == 'P') { // jika pria
            // isi dengan pria
            $foto = 'pria.png';
        } else { // selain itu
            // isi dengan wanita
            $foto = 'wanita.png';
        }

        if ($param == 1) { // jika parameternya 1
            // maka buat kode baru
            $kodeUser = _codeUser($nama);
        } else { // selain itu
            // ambil kode dari inputan
            $kodeUser = $this->input->post('kodeUser');
        }

        // tampung variable kedalam $isi
        $isi = [
            'kode_user'     => $kodeUser,
            'nama'          => $nama,
            'email'         => $email,
            'password'      => $password,
            'secondpass'    => $secondpass,
            'jkel'          => $jkel,
            'foto'          => $foto,
            'kode_role'     => $kode_role,
            'nohp'          => $nohp,
            'actived'       => 1,
            'joined'        => date('Y-m-d H:i:s'),
        ];

        // tampung variable kedalam $isi2
        $isi2 = [
            'email' => $email,
            'token' => '000000',
            'valid' => 1,
        ];

        if ($param == 1) { // jika parameternya 1
            // jalankan fungsi simpan
            $cek = [
                $this->M_global->insertData('user', $isi), // insert ke table user
                $this->M_global->insertData('user_token', $isi2), // insert ke table user_token
            ];

            $cek_param    = 'menambahkan';
        } else { // selain itu
            // jalankan fungsi update
            $cek          = $this->M_global->updateData('user', $isi, ['kode_user' => $kodeUser]);

            $cek_param    = 'mengubah';
        }

        if ($cek) { // jika fungsi berjalan
            aktifitas_user('Master Pengguna', $cek_param, $kodeUser, $this->M_global->getData('user', ['kode_user' => $kodeUser])->email);

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi hapus user berdasarkan kode_user
    public function delUser($kode_user)
    {
        // jalankan fungsi hapus user berdasarkan kode_user
        aktifitas_user('Master Pengguna', 'menghapus', $kode_user, $this->M_global->getData('user', ['kode_user' => $kode_user])->email);
        $cek = $this->M_global->delData('user', ['kode_user' => $kode_user]);

        if ($cek) { // jika fungsi berjalan

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // ############################################################################################################################################################################

    /**
     * Master Agama
     * untuk menampilkan, menambahkan, dan mengubah agama dalam sistem
     */

    // agama page
    public function agama()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter   = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Agama',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Master/agama_list',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Master/Umum/Agama', $parameter);
    }

    // fungsi list agama
    public function agama_list($param1 = '')
    {
        // parameter untuk list table
        $table                  = 'm_agama';
        $colum                  = ['id', 'kode_agama', 'keterangan'];
        $order                  = 'id';
        $order2                 = 'desc';
        $order_arr              = ['id' => 'asc'];
        $kondisi_param1         = '';

        // kondisi role
        $updated                = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted                = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;

        if ($updated > 0) {
            $upd_diss           = '';
        } else {
            $upd_diss           = 'disabled';
        }

        // table server side tampung kedalam variable $list
        $list                   = $this->M_datatables->get_datatables($table, $colum, $order_arr, $order, $order2, $param1, $kondisi_param1);
        $data                   = [];
        $no                     = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {
            if ($deleted > 0) {
                $cekIsset       = $this->M_global->getData('member', ['agama' => $rd->kode_agama]);
                if ($cekIsset) {
                    $del_diss   = 'disabled';
                } else {
                    $del_diss   = '';
                }
            } else {
                $del_diss       = 'disabled';
            }

            $row    = [];
            $row[]  = $no++;
            $row[]  = $rd->kode_agama;
            $row[]  = $rd->keterangan;
            $row[]  = '<div class="text-center">
                <button type="button" class="btn btn-warning" style="margin-bottom: 5px;" onclick="ubah(' . "'" . $rd->kode_agama . "'" . ')" ' . $upd_diss . '><i class="fa-regular fa-pen-to-square"></i></button>
                <button type="button" class="btn btn-danger" style="margin-bottom: 5px;" onclick="hapus(' . "'" . $rd->kode_agama . "'" . ')" ' . $del_diss . '><i class="fa-regular fa-circle-xmark"></i></button>
            </div>';
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

    // fungsi cek agama berdasarkan keterangan agama
    public function cekAgama()
    {
        // ambil keterangan inputan
        $keterangan   = $this->input->post('keterangan');

        // cek keterangan pada table m_agama
        $cek          = $this->M_global->jumDataRow('m_agama', ['keterangan' => $keterangan]);

        if ($cek < 1) { // jika tidak ada/ kurang dari 1
            // kirimkan status 1
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi proses simpan/update agama
    public function agama_proses($param)
    {
        // variable
        $keterangan       = $this->input->post('keterangan');

        if ($param == 1) { // jika parameternya 1
            // maka buat kode baru
            $kodeAgama    = _kodeAgama();
        } else { // selain itu
            // ambil kode dari inputan
            $kodeAgama    = $this->input->post('kodeAgama');
        }

        // tampung variable kedalam $isi
        $isi = [
            'kode_agama'    => $kodeAgama,
            'keterangan'    => $keterangan,
        ];

        if ($param == 1) { // jika parameternya 1
            // jalankan fungsi simpan
            $cek          = $this->M_global->insertData('m_agama', $isi);

            $cek_param    = 'menambahkan';
        } else { // selain itu
            // jalankan fungsi update
            $cek          = $this->M_global->updateData('m_agama', $isi, ['kode_agama' => $kodeAgama]);

            $cek_param    = 'mengubah';
        }

        if ($cek) { // jika fungsi berjalan
            aktifitas_user('Master Agama', $cek_param, $kodeAgama, $this->M_global->getData('m_agama', ['kode_agama' => $kodeAgama])->keterangan);

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi ambil informasi agama berdasarkan kode agama
    public function getInfoAgama($kode_agama)
    {
        // ambil data agama berdasarkan kode_agama
        $data = $this->M_global->getData('m_agama', ['kode_agama' => $kode_agama]);
        // lempar ke view
        echo json_encode($data);
    }

    // fungsi hapus agama berdasarkan kode_agama
    public function delAgama($kode_agama)
    {
        // jalankan fungsi hapus agama berdasarkan kode_agama
        aktifitas_user('Master Agama', 'menghapus', $kode_agama, $this->M_global->getData('m_agama', ['kode_agama' => $kode_agama])->keterangan);
        $cek = $this->M_global->delData('m_agama', ['kode_agama' => $kode_agama]);

        if ($cek) { // jika fungsi berjalan

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // ############################################################################################################################################################################

    /**
     * Master Pendidikan
     * untuk menampilkan, menambahkan, dan mengubah pendidikan dalam sistem
     */

    // pendidikan page
    public function pendidikan()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter   = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Pendidikan',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Master/pendidikan_list',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Master/Umum/Pendidikan', $parameter);
    }

    // fungsi list pendidikan
    public function pendidikan_list($param1 = '')
    {
        // parameter untuk list table
        $table                  = 'm_pendidikan';
        $colum                  = ['id', 'kode_pendidikan', 'keterangan'];
        $order                  = 'id';
        $order2                 = 'desc';
        $order_arr              = ['id' => 'asc'];
        $kondisi_param1         = '';

        // kondisi role
        $updated                = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted                = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;

        if ($updated > 0) {
            $upd_diss           = '';
        } else {
            $upd_diss           = 'disabled';
        }

        // table server side tampung kedalam variable $list
        $list                   = $this->M_datatables->get_datatables($table, $colum, $order_arr, $order, $order2, $param1, $kondisi_param1);
        $data                   = [];
        $no                     = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {
            if ($deleted > 0) {
                $cekIsset       = $this->M_global->getData('member', ['pendidikan' => $rd->kode_pendidikan]);
                if ($cekIsset) {
                    $del_diss   = 'disabled';
                } else {
                    $del_diss   = '';
                }
            } else {
                $del_diss       = 'disabled';
            }

            $row    = [];
            $row[]  = $no++;
            $row[]  = $rd->kode_pendidikan;
            $row[]  = $rd->keterangan;
            $row[]  = '<div class="text-center">
                <button type="button" class="btn btn-warning" style="margin-bottom: 5px;" onclick="ubah(' . "'" . $rd->kode_pendidikan . "'" . ')" ' . $upd_diss . '><i class="fa-regular fa-pen-to-square"></i></button>
                <button type="button" class="btn btn-danger" style="margin-bottom: 5px;" onclick="hapus(' . "'" . $rd->kode_pendidikan . "'" . ')" ' . $del_diss . '><i class="fa-regular fa-circle-xmark"></i></button>
            </div>';
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

    // fungsi cek pendidikan berdasarkan keterangan pendidikan
    public function cekPendidikan()
    {
        // ambil keterangan inputan
        $keterangan   = $this->input->post('keterangan');

        // cek keterangan pada table m_pendidikan
        $cek          = $this->M_global->jumDataRow('m_pendidikan', ['keterangan' => $keterangan]);

        if ($cek < 1) { // jika tidak ada/ kurang dari 1
            // kirimkan status 1
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi proses simpan/update pendidikan
    public function pendidikan_proses($param)
    {
        // variable
        $keterangan           = $this->input->post('keterangan');

        if ($param == 1) { // jika parameternya 1
            // maka buat kode baru
            $kodePendidikan   = _kodePendidikan();
        } else { // selain itu
            // ambil kode dari inputan
            $kodePendidikan   = $this->input->post('kodePendidikan');
        }

        // tampung variable kedalam $isi
        $isi = [
            'kode_pendidikan'   => $kodePendidikan,
            'keterangan'        => $keterangan,
        ];

        if ($param == 1) { // jika parameternya 1
            // jalankan fungsi simpan
            $cek          = $this->M_global->insertData('m_pendidikan', $isi);

            $cek_param    = 'menambahkan';
        } else { // selain itu
            // jalankan fungsi update
            $cek          = $this->M_global->updateData('m_pendidikan', $isi, ['kode_pendidikan' => $kodePendidikan]);

            $cek_param    = 'mengubah';
        }

        if ($cek) { // jika fungsi berjalan
            aktifitas_user('Master Pendidikan', $cek_param, $kodePendidikan, $this->M_global->getData('m_pendidikan', ['kode_pendidikan' => $kodePendidikan])->keterangan);

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi ambil informasi pendidikan berdasarkan kode pendidikan
    public function getInfoPendidikan($kode_pendidikan)
    {
        // ambil data pendidikan berdasarkan kode_pendidikan
        $data = $this->M_global->getData('m_pendidikan', ['kode_pendidikan' => $kode_pendidikan]);
        // lempar ke view
        echo json_encode($data);
    }

    // fungsi hapus pendidikan berdasarkan kode_pendidikan
    public function delPendidikan($kode_pendidikan)
    {
        // jalankan fungsi hapus pendidikan berdasarkan kode_pendidikan
        aktifitas_user('Master Pendidikan', 'menghapus', $kode_pendidikan, $this->M_global->getData('m_pendidikan', ['kode_pendidikan' => $kode_pendidikan])->keterangan);
        $cek = $this->M_global->delData('m_pendidikan', ['kode_pendidikan' => $kode_pendidikan]);

        if ($cek) { // jika fungsi berjalan

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // ############################################################################################################################################################################

    /**
     * Master Poli
     * untuk menampilkan, menambahkan, dan mengubah poli dalam sistem
     */

    // poli page
    public function poli()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter   = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Poli',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Master/poli_list',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Master/Umum/Poli', $parameter);
    }

    // fungsi list poli
    public function poli_list($param1 = '')
    {
        // parameter untuk list table
        $table                    = 'm_poli';
        $colum                    = ['id', 'kode_poli', 'keterangan'];
        $order                    = 'id';
        $order2                   = 'desc';
        $order_arr                = ['id' => 'asc'];
        $kondisi_param1           = '';

        // kondisi role
        $updated                  = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted                  = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;

        if ($updated > 0) {
            $upd_diss             = '';
        } else {
            $upd_diss             = 'disabled';
        }

        // table server side tampung kedalam variable $list
        $list                     = $this->M_datatables->get_datatables($table, $colum, $order_arr, $order, $order2, $param1, $kondisi_param1);
        $data                     = [];
        $no                       = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {
            if ($deleted > 0) {
                $cekIsset         = $this->M_global->jumDataRow('dokter_poli', ['kode_poli' => $rd->kode_poli]);
                if ($cekIsset < 1) {
                    $cekIsset2    = $this->M_global->jumDataRow('perawat_poli', ['kode_poli' => $rd->kode_poli]);
                    if ($cekIsset2 < 1) {
                        $del_diss = '';
                    } else {
                        $del_diss = 'disabled';
                    }
                } else {
                    $del_diss     = 'disabled';
                }
            } else {
                $del_diss         = 'disabled';
            }

            $row    = [];
            $row[]  = $no++;
            $row[]  = $rd->kode_poli;
            $row[]  = $rd->keterangan;
            $row[]  = '<div class="text-center">
                <button type="button" class="btn btn-warning" style="margin-bottom: 5px;" onclick="ubah(' . "'" . $rd->kode_poli . "'" . ')" ' . $upd_diss . '><i class="fa-regular fa-pen-to-square"></i></button>
                <button type="button" class="btn btn-danger" style="margin-bottom: 5px;" onclick="hapus(' . "'" . $rd->kode_poli . "'" . ')" ' . $del_diss . '><i class="fa-regular fa-circle-xmark"></i></button>
            </div>';
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

    // fungsi cek poli berdasarkan keterangan poli
    public function cekPol()
    {
        // ambil keterangan inputan
        $keterangan   = $this->input->post('keterangan');

        // cek keterangan pada table m_poli
        $cek          = $this->M_global->jumDataRow('m_poli', ['keterangan' => $keterangan]);

        if ($cek < 1) { // jika tidak ada/ kurang dari 1
            // kirimkan status 1
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi proses simpan/update poli
    public function poli_proses($param)
    {
        // variable
        $keterangan   = $this->input->post('keterangan');

        if ($param == 1) { // jika parameternya 1
            // maka buat kode baru
            $kodePoli = _kodePoli();
        } else { // selain itu
            // ambil kode dari inputan
            $kodePoli = $this->input->post('kodePoli');
        }

        // tampung variable kedalam $isi
        $isi = [
            'kode_poli'     => $kodePoli,
            'keterangan'    => $keterangan,
        ];

        if ($param == 1) { // jika parameternya 1
            // jalankan fungsi simpan
            $cek          = $this->M_global->insertData('m_poli', $isi);

            $cek_param    = 'menambahkan';
        } else { // selain itu
            // jalankan fungsi update
            $cek          = $this->M_global->updateData('m_poli', $isi, ['kode_poli' => $kodePoli]);

            $cek_param    = 'mengubah';
        }

        if ($cek) { // jika fungsi berjalan
            aktifitas_user('Master Poli', $cek_param, $kodePoli, $this->M_global->getData('m_poli', ['kode_poli' => $kodePoli])->keterangan);

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi ambil informasi poli berdasarkan kode poli
    public function getInfoPol($kode_poli)
    {
        // ambil data poli berdasarkan kode_poli
        $data = $this->M_global->getData('m_poli', ['kode_poli' => $kode_poli]);
        // lempar ke view
        echo json_encode($data);
    }

    // fungsi hapus poli berdasarkan kode_poli
    public function delPol($kode_poli)
    {
        // jalankan fungsi hapus poli berdasarkan kode_poli
        aktifitas_user('Master Poli', 'menghapus', $kode_poli, $this->M_global->getData('m_poli', ['kode_poli' => $kode_poli])->keterangan);
        $cek = $this->M_global->delData('m_poli', ['kode_poli' => $kode_poli]);

        if ($cek) { // jika fungsi berjalan

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // ############################################################################################################################################################################

    /**
     * Master Dokyer
     * untuk menampilkan, menambahkan, dan mengubah dokter dalam sistem
     */

    // dokter page
    public function dokter()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter   = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Dokter',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Master/dokter_list',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Master/Internal/Dokter', $parameter);
    }

    // fungsi list dokter
    public function dokter_list($param1 = '')
    {
        // parameter untuk list table
        $table                  = 'dokter';
        $colum                  = ['id', 'kode_dokter', 'nama', 'email', 'nik', 'sip', 'npwp', 'nohp', 'tgl_mulai', 'tgl_berhenti', 'status', 'provinsi', 'kabupaten', 'kecamatan', 'desa', 'kodepos'];
        $order                  = 'id';
        $order2                 = 'desc';
        $order_arr              = ['id' => 'asc'];
        $kondisi_param1         = '';

        // kondisi role
        $updated                = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted                = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;

        // table server side tampung kedalam variable $list
        $list                   = $this->M_datatables->get_datatables($table, $colum, $order_arr, $order, $order2, $param1, $kondisi_param1);
        $data                   = [];
        $no                     = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {

            $prov               = $this->M_global->getData('m_provinsi', ['kode_provinsi' => $rd->provinsi])->provinsi;
            $kab                = $this->M_global->getData('kabupaten', ['kode_kabupaten' => $rd->kabupaten])->kabupaten;
            $kec                = $this->M_global->getData('kecamatan', ['kode_kecamatan' => $rd->kecamatan])->kecamatan;

            if ($updated > 0) {
                $upd_diss       = '';
            } else {
                $upd_diss       = 'disabled';
            }

            if ($deleted > 0) {
                $cekIsset       = $this->M_global->jumDataRow('pendaftaran', ['kode_dokter' => $rd->kode_dokter]);
                if ($cekIsset > 0) {
                    $del_diss   = 'disabled';
                } else {
                    $del_diss   = '';
                }
            } else {
                $del_diss       = 'disabled';
            }

            $dokter_poli        = $this->M_global->getDataResult('dokter_poli', ['kode_dokter' => $rd->kode_dokter]);

            $dpoli              = [];
            foreach ($dokter_poli as $dp) {
                $dpoli[]        = ' ' . $this->M_global->getData('m_poli', ['kode_poli' => $dp->kode_poli])->keterangan;
            }

            if ($rd->status > 0) {
                $actived_akun   = '<button type="button" style="margin-bottom: 5px;" class="btn btn-info" onclick="actived(' . "'" . $rd->kode_dokter . "', 0" . ')" ' . $upd_diss . '><i class="fa-solid fa-user-xmark"></i></button>';
            } else {
                $actived_akun   = '<button type="button" style="margin-bottom: 5px;" class="btn btn-info" onclick="actived(' . "'" . $rd->kode_dokter . "', 1" . ')" ' . $upd_diss . '><i class="fa-solid fa-user-check"></i></button>';
            }

            $row    = [];
            $row[]  = $no++;
            $row[]  = $rd->kode_dokter;
            $row[]  = $rd->nama;
            $row[]  = $rd->nohp;
            $row[]  = 'Prov. ' . $prov . ',<br>Kab. ' . $kab . ',<br>Kec. ' . $kec . ',<br>Ds. ' . $rd->desa . ',<br>(POS: ' . $rd->kodepos . ')';
            $row[]  = 'Mulai: <br><span class="float-right">' . date('d/m/Y', strtotime($rd->tgl_mulai)) . '</span><br>Hingga: <br><span class="float-right">' . date('d/m/Y', strtotime($rd->tgl_berhenti)) . '</span>';
            $row[]  = $dpoli;
            $row[]  = '<div class="text-center">' . (($rd->status == 1) ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-dark">Non-aktif</span>') . '</div>';
            $row[]  = '<div class="text-center">
                ' . $actived_akun . '
                <button type="button" style="margin-bottom: 5px;" class="btn btn-warning" onclick="ubah(' . "'" . $rd->kode_dokter . "'" . ')" ' . $upd_diss . '><i class="fa-regular fa-pen-to-square"></i></button>
                <button type="button" style="margin-bottom: 5px;" class="btn btn-danger" onclick="hapus(' . "'" . $rd->kode_dokter . "'" . ')" ' . $del_diss . '><i class="fa-regular fa-circle-xmark"></i></button>
            </div>';
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

    // fungsi aktif/nonaktif dokter
    public function activeddokter($kode_dokter, $param)
    {
        // jalankan fungsi update actived dokter
        $cek = $this->M_global->updateData('dokter', ['status' => $param], ['kode_dokter' => $kode_dokter]);

        if ($cek) { // jika fungsi berjalan
            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi cek dokter
    public function cekDokter()
    {
        $nik = $this->input->post('nik');

        $cek = $this->M_global->jumDataRow('dokter', ['nik' => $nik]);

        if ($cek < 1) {
            echo json_encode(['status' => 1]);
        } else {
            echo json_encode(['status' => 0]);
        }
    }

    // form dokter page
    public function form_dokter($param)
    {
        // website config
        $web_setting        = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version        = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        if ($param != '0') {
            $dokter         = $this->M_global->getData('dokter', ['kode_dokter' => $param]);
            $dokter_poli    = $this->M_global->getDataResult('dokter_poli', ['kode_dokter' => $param]);
        } else {
            $dokter         = null;
            $dokter_poli    = null;
        }

        $parameter = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Dokter',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => '',
            'data_dokter'   => $dokter,
            'dokter_poli'   => $dokter_poli,
            'role'          => $this->M_global->getResult('m_role'),
            'poli'          => $this->M_global->getResult('m_poli'),
        ];

        $this->template->load('Template/Content', 'Master/Internal/Form_dokter', $parameter);
    }

    // fungsi dokter proses
    public function dokter_proses($param)
    {
        // variable
        $nama             = $this->input->post('nama');

        if ($param == 1) { // jika parameternya 1
            // maka buat kode baru
            $kodeDokter   = _kodeDokter($nama);
        } else { // selain itu
            // ambil kode dari inputan
            $kodeDokter   = $this->input->post('kodeDokter');

            $this->M_global->delData('dokter_poli', ['kode_dokter' => $kodeDokter]);
        }
        $nik          = $this->input->post('nik');
        $email        = $this->input->post('email');
        $nohp         = $this->input->post('nohp');
        $npwp         = $this->input->post('npwp');
        $sip          = $this->input->post('sip');
        $tgl_mulai    = $this->input->post('tgl_mulai');
        $tgl_berhenti = $this->input->post('tgl_berhenti');
        $status       = $this->input->post('statusDokter');
        $provinsi     = $this->input->post('provinsi');
        $kabupaten    = $this->input->post('kabupaten');
        $kecamatan    = $this->input->post('kecamatan');
        $desa         = $this->input->post('desa');
        $kodepos      = $this->input->post('kodepos');
        $kode_poli    = $this->input->post('kode_poli');

        // tampung variable kedalam $isi
        $isi = [
            'kode_dokter'   => $kodeDokter,
            'nik'           => $nik,
            'sip'           => $sip,
            'npwp'          => $npwp,
            'nama'          => $nama,
            'email'         => $email,
            'nohp'          => $nohp,
            'tgl_mulai'     => $tgl_mulai,
            'tgl_berhenti'  => $tgl_berhenti,
            'status'        => $status,
            'provinsi'      => $provinsi,
            'kabupaten'     => $kabupaten,
            'kecamatan'     => $kecamatan,
            'desa'          => $desa,
            'kodepos'       => $kodepos,
        ];

        foreach ($kode_poli as $kp) {
            $_kode_poli_input = $kp;

            $isi_poli = [
                'kode_dokter'   => $kodeDokter,
                'kode_poli'     => $_kode_poli_input,
            ];

            $this->M_global->insertData('dokter_poli', $isi_poli);
        }

        if ($param == 1) { // jika parameternya 1
            // jalankan fungsi simpan
            $cek          = $this->M_global->insertData('dokter', $isi);

            $cek_param    = 'menambahkan';
        } else { // selain itu
            // jalankan fungsi update
            $cek          = $this->M_global->updateData('dokter', $isi, ['kode_dokter' => $kodeDokter]);

            $cek_param    = 'mengubah';
        }

        if ($cek) { // jika fungsi berjalan
            aktifitas_user('Master Dokter', $cek_param, $kodeDokter, $this->M_global->getData('dokter', ['kode_dokter' => $kodeDokter])->nama);

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi hapus dokter berdasarkan kode_dokter
    public function delDokter($kode_dokter)
    {
        // jalankan fungsi hapus dokter berdasarkan kode_dokter
        aktifitas_user('Master Dokter', 'menghapus', $kode_dokter, $this->M_global->getData('dokter', ['kode_dokter' => $kode_dokter])->nama);
        $cek = [
            $this->M_global->delData('dokter', ['kode_dokter' => $kode_dokter]),
            $this->M_global->delData('dokter_poli', ['kode_dokter' => $kode_dokter]),
        ];

        if ($cek) { // jika fungsi berjalan

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi getPoli
    public function getPoli($kode_poli)
    {
        $data = $this->db->query('SELECT * FROM m_poli WHERE (kode_poli = "' . $kode_poli . '" OR keterangan LIKE "%' . $kode_poli . '%")')->row();
        if ($data) {
            echo json_encode($data);
        } else {
            echo json_encode(['status' => 0]);
        }
    }

    // ############################################################################################################################################################################

    /**
     * Master Perawat
     * untuk menampilkan, menambahkan, dan mengubah perawat dalam sistem
     */

    // perawat page
    public function perawat()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter   = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Perawat',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Master/perawat_list',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Master/Internal/Perawat', $parameter);
    }

    // fungsi list perawat
    public function perawat_list($param1 = '')
    {
        // parameter untuk list table
        $table            = 'perawat';
        $colum            = ['id', 'kode_perawat', 'nama', 'email', 'nik', 'sip', 'npwp', 'nohp', 'tgl_mulai', 'tgl_berhenti', 'status', 'provinsi', 'kabupaten', 'kecamatan', 'desa', 'kodepos'];
        $order            = 'id';
        $order2           = 'desc';
        $order_arr        = ['id' => 'asc'];
        $kondisi_param1   = '';

        // kondisi role
        $updated          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;

        // table server side tampung kedalam variable $list
        $list             = $this->M_datatables->get_datatables($table, $colum, $order_arr, $order, $order2, $param1, $kondisi_param1);
        $data             = [];
        $no               = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {

            $prov         = $this->M_global->getData('m_provinsi', ['kode_provinsi' => $rd->provinsi])->provinsi;
            $kab          = $this->M_global->getData('kabupaten', ['kode_kabupaten' => $rd->kabupaten])->kabupaten;
            $kec          = $this->M_global->getData('kecamatan', ['kode_kecamatan' => $rd->kecamatan])->kecamatan;

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

            $perawat_poli = $this->M_global->getDataResult('perawat_poli', ['kode_perawat' => $rd->kode_perawat]);

            $dpoli        = [];
            foreach ($perawat_poli as $dp) {
                $dpoli[] = ' ' . $this->M_global->getData('m_poli', ['kode_poli' => $dp->kode_poli])->keterangan;
            }

            if ($rd->status > 0) {
                $actived_akun = '<button type="button" style="margin-bottom: 5px;" class="btn btn-info" onclick="actived(' . "'" . $rd->kode_perawat . "', 0" . ')" ' . $upd_diss . '><i class="fa-solid fa-user-xmark"></i></button>';
            } else {
                $actived_akun = '<button type="button" style="margin-bottom: 5px;" class="btn btn-info" onclick="actived(' . "'" . $rd->kode_perawat . "', 1" . ')" ' . $upd_diss . '><i class="fa-solid fa-user-check"></i></button>';
            }

            $row    = [];
            $row[]  = $no++;
            $row[]  = $rd->kode_perawat;
            $row[]  = $rd->nama;
            $row[]  = $rd->nohp;
            $row[]  = 'Prov. ' . $prov . ',<br>Kab. ' . $kab . ',<br>Kec. ' . $kec . ',<br>Ds. ' . $rd->desa . ',<br>(POS: ' . $rd->kodepos . ')';
            $row[]  = 'Mulai: <br><span class="float-right">' . date('d/m/Y', strtotime($rd->tgl_mulai)) . '</span><br>Hingga: <br><span class="float-right">' . date('d/m/Y', strtotime($rd->tgl_berhenti)) . '</span>';
            $row[]  = $dpoli;
            $row[]  = '<div class="text-center">' . (($rd->status == 1) ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-dark">Non-aktif</span>') . '</div>';
            $row[]  = '<div class="text-center">
                ' . $actived_akun . '
                <button type="button" style="margin-bottom: 5px;" class="btn btn-warning" onclick="ubah(' . "'" . $rd->kode_perawat . "'" . ')" ' . $upd_diss . '><i class="fa-regular fa-pen-to-square"></i></button>
                <button type="button" style="margin-bottom: 5px;" class="btn btn-danger" onclick="hapus(' . "'" . $rd->kode_perawat . "'" . ')" ' . $del_diss . '><i class="fa-regular fa-circle-xmark"></i></button>
            </div>';
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

    // fungsi aktif/nonaktif perawat
    public function activedperawat($kode_perawat, $param)
    {
        // jalankan fungsi update actived perawat
        $cek = $this->M_global->updateData('perawat', ['status' => $param], ['kode_perawat' => $kode_perawat]);

        if ($cek) { // jika fungsi berjalan
            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi cek perawat
    public function cekPerawat()
    {
        $nik = $this->input->post('nik');

        $cek = $this->M_global->jumDataRow('perawat', ['nik' => $nik]);

        if ($cek < 1) {
            echo json_encode(['status' => 1]);
        } else {
            echo json_encode(['status' => 0]);
        }
    }

    // form perawat page
    public function form_perawat($param)
    {
        // website config
        $web_setting        = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version        = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        if ($param != '0') {
            $perawat        = $this->M_global->getData('perawat', ['kode_perawat' => $param]);
            $perawat_poli   = $this->M_global->getDataResult('perawat_poli', ['kode_perawat' => $param]);
        } else {
            $perawat        = null;
            $perawat_poli   = null;
        }

        $parameter = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Perawat',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => '',
            'data_perawat'  => $perawat,
            'perawat_poli'  => $perawat_poli,
            'role'          => $this->M_global->getResult('m_role'),
            'poli'          => $this->M_global->getResult('m_poli'),
        ];

        $this->template->load('Template/Content', 'Master/Internal/Form_perawat', $parameter);
    }

    // fungsi perawat proses
    public function perawat_proses($param)
    {
        // variable
        $nama               = $this->input->post('nama');

        if ($param == 1) { // jika parameternya 1
            // maka buat kode baru
            $kodePerawat    = _kodePerawat($nama);
        } else { // selain itu
            // ambil kode dari inputan
            $kodePerawat    = $this->input->post('kodePerawat');

            $this->M_global->delData('perawat_poli', ['kode_perawat' => $kodePerawat]);
        }

        $nik          = $this->input->post('nik');
        $email        = $this->input->post('email');
        $nohp         = $this->input->post('nohp');
        $npwp         = $this->input->post('npwp');
        $sip          = $this->input->post('sip');
        $tgl_mulai    = $this->input->post('tgl_mulai');
        $tgl_berhenti = $this->input->post('tgl_berhenti');
        $status       = $this->input->post('statusPerawat');
        $provinsi     = $this->input->post('provinsi');
        $kabupaten    = $this->input->post('kabupaten');
        $kecamatan    = $this->input->post('kecamatan');
        $desa         = $this->input->post('desa');
        $kodepos      = $this->input->post('kodepos');
        $kode_poli    = $this->input->post('kode_poli');

        // tampung variable kedalam $isi
        $isi = [
            'kode_perawat'  => $kodePerawat,
            'nik'           => $nik,
            'sip'           => $sip,
            'npwp'          => $npwp,
            'nama'          => $nama,
            'email'         => $email,
            'nohp'          => $nohp,
            'tgl_mulai'     => $tgl_mulai,
            'tgl_berhenti'  => $tgl_berhenti,
            'status'        => $status,
            'provinsi'      => $provinsi,
            'kabupaten'     => $kabupaten,
            'kecamatan'     => $kecamatan,
            'desa'          => $desa,
            'kodepos'       => $kodepos,
        ];

        foreach ($kode_poli as $kp) {
            $_kode_poli_input = $kp;

            $isi_poli = [
                'kode_perawat'  => $kodePerawat,
                'kode_poli'     => $_kode_poli_input,
            ];

            $this->M_global->insertData('perawat_poli', $isi_poli);
        }

        if ($param == 1) { // jika parameternya 1
            // jalankan fungsi simpan
            $cek          = $this->M_global->insertData('perawat', $isi);

            $cek_param    = 'menambahkan';
        } else { // selain itu
            // jalankan fungsi update
            $cek          = $this->M_global->updateData('perawat', $isi, ['kode_perawat' => $kodePerawat]);

            $cek_param    = 'mengubah';
        }

        if ($cek) { // jika fungsi berjalan
            aktifitas_user('Master Perawat', $cek_param, $kodePerawat, $this->M_global->getData('perawat', ['kode_perawat' => $kodePerawat])->nama);

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi hapus perawat berdasarkan kode_perawat
    public function delPerawat($kode_perawat)
    {
        // jalankan fungsi hapus perawat berdasarkan kode_perawat
        aktifitas_user('Master Perawat', 'menghapus', $kode_perawat, $this->M_global->getData('perawat', ['kode_perawat' => $kode_perawat])->nama);
        $cek = [
            $this->M_global->delData('perawat', ['kode_perawat' => $kode_perawat]),
            $this->M_global->delData('perawat_poli', ['kode_perawat' => $kode_perawat]),
        ];

        if ($cek) { // jika fungsi berjalan

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // ############################################################################################################################################################################

    /**
     * Master Jenis
     * untuk menampilkan, menambahkan, dan mengubah kategori dalam sistem
     */

    // jenis page
    public function jenis()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Jenis',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Master/jenis_list',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Master/Umum/Jenis', $parameter);
    }

    // fungsi list jenis
    public function jenis_list($param1 = '')
    {
        // parameter untuk list table
        $table                  = 'm_jenis';
        $colum                  = ['id', 'kode_jenis', 'keterangan'];
        $order                  = 'id';
        $order2                 = 'desc';
        $order_arr              = ['id' => 'asc'];
        $kondisi_param1         = '';

        // kondisi role
        $updated                = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted                = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;

        if ($updated > 0) {
            $upd_diss           = '';
        } else {
            $upd_diss           = 'disabled';
        }

        // table server side tampung kedalam variable $list
        $list                   = $this->M_datatables->get_datatables($table, $colum, $order_arr, $order, $order2, $param1, $kondisi_param1);
        $data                   = [];
        $no                     = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {
            if ($deleted > 0) {
                $cekIsset       = $this->M_global->jumDataRow('barang_jenis', ['kode_jenis' => $rd->kode_jenis]);
                if ($cekIsset > 0) {
                    $del_diss   = 'disabled';
                } else {
                    $del_diss   = '';
                }
            } else {
                $del_diss       = 'disabled';
            }

            $row    = [];
            $row[]  = $no++;
            $row[]  = $rd->kode_jenis;
            $row[]  = $rd->keterangan;
            $row[]  = '<div class="text-center">
                <button type="button" class="btn btn-warning" style="margin-bottom: 5px;" onclick="ubah(' . "'" . $rd->kode_jenis . "'" . ')" ' . $upd_diss . '><i class="fa-regular fa-pen-to-square"></i></button>
                <button type="button" class="btn btn-danger" style="margin-bottom: 5px;" onclick="hapus(' . "'" . $rd->kode_jenis . "'" . ')" ' . $del_diss . '><i class="fa-regular fa-circle-xmark"></i></button>
            </div>';
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

    // fungsi cek jenis berdasarkan keterangan jenis
    public function cekJenis()
    {
        // ambil keterangan inputan
        $keterangan   = $this->input->post('keterangan');

        // cek keterangan pada table m_jenis
        $cek          = $this->M_global->jumDataRow('m_jenis', ['keterangan' => $keterangan]);

        if ($cek < 1) { // jika tidak ada/ kurang dari 1
            // kirimkan status 1
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi proses simpan/update jenis
    public function jenis_proses($param)
    {
        // variable
        $keterangan       = $this->input->post('keterangan');

        if ($param == 1) { // jika parameternya 1
            // maka buat kode baru
            $kodeJenis    = _kodeJenis();
        } else { // selain itu
            // ambil kode dari inputan
            $kodeJenis    = $this->input->post('kodeJenis');
        }

        // tampung variable kedalam $isi
        $isi = [
            'kode_jenis'    => $kodeJenis,
            'keterangan'    => $keterangan,
        ];

        if ($param == 1) { // jika parameternya 1
            // jalankan fungsi simpan
            $cek          = $this->M_global->insertData('m_jenis', $isi);

            $cek_param    = 'menambahkan';
        } else { // selain itu
            // jalankan fungsi update
            $cek          = $this->M_global->updateData('m_jenis', $isi, ['kode_jenis' => $kodeJenis]);

            $cek_param    = 'mengubah';
        }

        if ($cek) { // jika fungsi berjalan
            aktifitas_user('Master Jenis Obat', $cek_param, $kodeJenis, $this->M_global->getData('m_jenis', ['kode_jenis' => $kodeJenis])->keterangan);

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi ambil informasi jenis berdasarkan kode jenis
    public function getInfoJenis($kode_jenis)
    {
        // ambil data jenis berdasarkan kode_jenis
        $data = $this->M_global->getData('m_jenis', ['kode_jenis' => $kode_jenis]);
        // lempar ke view
        echo json_encode($data);
    }

    // fungsi hapus jenis berdasarkan kode_jenis
    public function delJenis($kode_jenis)
    {
        // jalankan fungsi hapus jenis berdasarkan kode_jenis
        aktifitas_user('Master Jenis Obat', 'menghapus', $kode_jenis, $this->M_global->getData('m_jenis', ['kode_jenis' => $kode_jenis])->keterangan);
        $cek = $this->M_global->delData('m_jenis', ['kode_jenis' => $kode_jenis]);

        if ($cek) { // jika fungsi berjalan

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // ############################################################################################################################################################################

    /**
     * Master Kas_bank
     * untuk menampilkan, menambahkan, dan mengubah poli dalam sistem
     */

    // kas_bank page
    public function kas_bank()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter   = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Kas & Bank',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Master/kas_bank_list',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Master/Umum/Kas_bank', $parameter);
    }

    // fungsi list kas_bank
    public function kas_bank_list($param1 = '')
    {
        // parameter untuk list table
        $table            = 'kas_bank';
        $colum            = ['id', 'kode_kas_bank', 'nama', 'tipe', 'akun'];
        $order            = 'id';
        $order2           = 'desc';
        $order_arr        = ['id' => 'asc'];
        $kondisi_param1   = '';

        // kondisi role
        $updated          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;

        if ($updated > 0) {
            $upd_diss     = '';
        } else {
            $upd_diss     = 'disabled';
        }

        if ($deleted > 0) {
            $del_diss     = '';
        } else {
            $del_diss     = 'disabled';
        }

        // table server side tampung kedalam variable $list
        $list             = $this->M_datatables->get_datatables($table, $colum, $order_arr, $order, $order2, $param1, $kondisi_param1);
        $data             = [];
        $no               = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {
            if ($rd->tipe == 1) {
                $tipe     = "Cash";
            } else {
                $tipe     = "Bank";
            }

            if ($rd->akun == 1) {
                $akun     = "Kas Besar";
            } else {
                $akun     = "Kas Kecil";
            }

            $row    = [];
            $row[]  = $no++;
            $row[]  = $rd->kode_kas_bank;
            $row[]  = $rd->nama;
            $row[]  = $tipe;
            $row[]  = $akun;
            $row[]  = '<div class="text-center">
                <button type="button" class="btn btn-warning" style="margin-bottom: 5px;" onclick="ubah(' . "'" . $rd->kode_kas_bank . "'" . ')" ' . $upd_diss . '><i class="fa-regular fa-pen-to-square"></i></button>
                <button type="button" class="btn btn-danger" style="margin-bottom: 5px;" onclick="hapus(' . "'" . $rd->kode_kas_bank . "'" . ')" ' . $del_diss . '><i class="fa-regular fa-circle-xmark"></i></button>
            </div>';
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

    // form kas_bank page
    public function form_kas_bank($param)
    {
        // website config
        $web_setting    = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version    = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        if ($param != '0') {
            $kas_bank   = $this->M_global->getData('kas_bank', ['kode_kas_bank' => $param]);
        } else {
            $kas_bank   = null;
        }

        $parameter = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Kas & Bank',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => '',
            'data_kas_bank' => $kas_bank,
        ];

        $this->template->load('Template/Content', 'Master/Internal/Form_kas_bank', $parameter);
    }

    // fungsi cek kas_bank
    public function cekKas_bank()
    {
        $nama   = $this->input->post('nama');

        $cek    = $this->M_global->jumDataRow('kas_bank', ['nama' => $nama]);

        if ($cek < 1) {
            echo json_encode(['status' => 1]);
        } else {
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi kas_bank proses
    public function kas_bank_proses($param)
    {
        // variable
        $nama               = $this->input->post('nama');

        if ($param == 1) { // jika parameternya 1
            // maka buat kode baru
            $kodeKas_bank   = _kodeKas_bank();
        } else { // selain itu
            // ambil kode dari inputan
            $kodeKas_bank   = $this->input->post('kode_kas_bank');
        }
        $nama               = $this->input->post('nama');
        $tipe               = $this->input->post('tipe');
        $akun               = $this->input->post('akun');

        // tampung variable kedalam $isi
        $isi = [
            'kode_kas_bank' => $kodeKas_bank,
            'nama'          => $nama,
            'tipe'          => $tipe,
            'akun'          => $akun,
        ];

        if ($param == 1) { // jika parameternya 1
            // jalankan fungsi simpan
            $cek          = $this->M_global->insertData('kas_bank', $isi);

            $cek_param    = 'menambahkan';
        } else { // selain itu
            // jalankan fungsi update
            $cek          = $this->M_global->updateData('kas_bank', $isi, ['kode_kas_bank' => $kodeKas_bank]);

            $cek_param    = 'mengubah';
        }

        if ($cek) { // jika fungsi berjalan
            aktifitas_user('Master Kas & Bank', $cek_param, $kodeKas_bank, $this->M_global->getData('kas_bank', ['kode_kas_bank' => $kodeKas_bank])->nama);

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi hapus kas_bank berdasarkan kode_kas_bank
    public function delKas_bank($kode_kas_bank)
    {
        // jalankan fungsi hapus kas_bank berdasarkan kode_kas_bank
        aktifitas_user('Master Kas & Bank', 'menghapus', $kode_kas_bank, $this->M_global->getData('kas_bank', ['kode_kas_bank' => $kode_kas_bank])->nama);
        $cek = $this->M_global->delData('kas_bank', ['kode_kas_bank' => $kode_kas_bank]);

        if ($cek) { // jika fungsi berjalan

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    /**
     * Master Pajak
     * untuk menampilkan, menambahkan, dan mengubah pajak dalam sistem
     */

    // pajak page
    public function pajak()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter   = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Pajak',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Master/pajak_list',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Master/Umum/Pajak', $parameter);
    }

    // fungsi list pajak
    public function pajak_list($param1 = '')
    {
        // parameter untuk list table
        $table            = 'm_pajak';
        $colum            = ['id', 'kode_pajak', 'nama', 'persentase', 'aktif'];
        $order            = 'id';
        $order2           = 'desc';
        $order_arr        = ['id' => 'asc'];
        $kondisi_param1   = '';

        // kondisi role
        $updated          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;

        if ($updated > 0) {
            $upd_diss     = '';
        } else {
            $upd_diss     = 'disabled';
        }

        // table server side tampung kedalam variable $list
        $list             = $this->M_datatables->get_datatables($table, $colum, $order_arr, $order, $order2, $param1, $kondisi_param1);
        $data             = [];
        $no               = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {
            if ($deleted > 0) {
                $del_diss = '';
            } else {
                $del_diss = 'disabled';
            }

            $row    = [];
            $row[]  = $no;
            $row[]  = $rd->nama;
            $row[]  = '<span class="float-right">' . $rd->persentase . '%</span>';
            $row[]  = '<div class="text-center">' . '<input type="checkbox" class="form-control" name="default_ppn" id="default_ppn' . $no . '" ' . ($rd->aktif == 1 ? 'checked' : '') . '  onclick="set_default(' . "'" . $rd->kode_pajak . "', '" . $no . "'" . ')" ' . (($rd->aktif > 0) ? 'disabled' : '') . '>' . '</div>';
            $row[]  = '<div class="text-center">
                <button type="button" class="btn btn-warning" style="margin-bottom: 5px;" onclick="ubah(' . "'" . $rd->kode_pajak . "'" . ')" ' . $upd_diss . '><i class="fa-regular fa-pen-to-square"></i></button>
                <button type="button" class="btn btn-danger" style="margin-bottom: 5px;" onclick="hapus(' . "'" . $rd->kode_pajak . "'" . ')" ' . $del_diss . '><i class="fa-regular fa-circle-xmark"></i></button>
            </div>';
            $data[] = $row;

            $no++;
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

    public function setDefPajak($kode_pajak)
    {
        $cek = $this->db->query("UPDATE m_pajak SET aktif = 0");

        if ($cek) {
            $cek2 = $this->db->query("UPDATE m_pajak SET aktif = 1 WHERE kode_pajak = '$kode_pajak'");
        } else {
            $cek2 = TRUE;
        }

        if ($cek2) {
            echo json_encode(['status' => 1]);
        } else {
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi cek pajak berdasarkan keterangan pajak
    public function cekPajak()
    {
        // ambil nama inputan
        $nama = $this->input->post('nama');

        // cek nama pada table m_pajak
        $cek = $this->M_global->jumDataRow('m_pajak', ['nama' => $nama]);

        if ($cek < 1) { // jika tidak ada/ kurang dari 1
            // kirimkan status 1
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi proses simpan/update pajak
    public function pajak_proses($param)
    {
        // variable
        $nama             = $this->input->post('nama');
        $persentase       = $this->input->post('persentase');

        if ($param == 1) { // jika parameternya 1
            // maka buat kode baru
            $kodePajak    = _kodePajak();
        } else { // selain itu
            // ambil kode dari inputan
            $kodePajak    = $this->input->post('kodePajak');
        }

        // tampung variable kedalam $isi
        $isi = [
            'kode_pajak'    => $kodePajak,
            'nama'          => $nama,
            'persentase'    => $persentase,
            'aktif'         => 0,
        ];

        if ($param == 1) { // jika parameternya 1
            // jalankan fungsi simpan
            $cek          = $this->M_global->insertData('m_pajak', $isi);

            $cek_param    = 'menambahkan';
        } else { // selain itu
            // jalankan fungsi update
            $cek          = $this->M_global->updateData('m_pajak', $isi, ['kode_pajak' => $kodePajak]);

            $cek_param    = 'mengubah';
        }

        if ($cek) { // jika fungsi berjalan
            aktifitas_user('Master Pajak', $cek_param, $kodePajak, $this->M_global->getData('m_pajak', ['kode_pajak' => $kodePajak])->nama);

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi ambil informasi pajak berdasarkan kode pajak
    public function getInfoPajak($kode_pajak)
    {
        // ambil data pajak berdasarkan kode_pajak
        $data = $this->M_global->getData('m_pajak', ['kode_pajak' => $kode_pajak]);
        // lempar ke view
        echo json_encode($data);
    }

    // fungsi hapus pajak berdasarkan kode_pajak
    public function delPajak($kode_pajak)
    {
        // jalankan fungsi hapus pajak berdasarkan kode_pajak
        aktifitas_user('Master Pajak', 'menghapus', $kode_pajak, $this->M_global->getData('m_pajak', ['kode_pajak' => $kode_pajak])->nama);
        $cek = $this->M_global->delData('m_pajak', ['kode_pajak' => $kode_pajak]);

        if ($cek) { // jika fungsi berjalan

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // ############################################################################################################################################################################

    /**
     * Master Tarif Single
     */

    // single page
    public function tin_single()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter   = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Tindakan Single',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Master/tin_single_list',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Master/Tarif/Single', $parameter);
    }

    // fungsi list single
    public function tin_single_list($param1 = '1')
    {
        $this->load->model('M_tarif');

        // kondisi role
        $updated                = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted                = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;

        if ($updated > 0) {
            $upd_diss           = '';
        } else {
            $upd_diss           = 'disabled';
        }

        $list                   = $this->M_tarif->get_datatables($param1);

        $data                   = [];
        $no                     = $_POST['start'] + 1;

        // Loop through the list to populate the data array
        foreach ($list as $rd) {
            if ($deleted > 0) {
                $cekIsset       = $this->M_global->jumDataRow('pembayaran_tarif_single', ['kode_tarif' => $rd->kode_tarif]);
                if ($cekIsset > 0) {
                    $del_diss   = 'disabled';
                } else {
                    $del_diss   = '';
                }
            } else {
                $del_diss       = 'disabled';
            }

            $row    = [];
            $row[]  = $no++;
            $row[]  = $rd->kode_tarif;
            $row[]  = $rd->nama;
            $row[]  = 'Rp. <div class="float-right">' . number_format($rd->jasa_rs) . '</div>';
            $row[]  = 'Rp. <div class="float-right">' . number_format($rd->jasa_dokter) . '</div>';
            $row[]  = 'Rp. <div class="float-right">' . number_format($rd->jasa_pelayanan) . '</div>';
            $row[]  = 'Rp. <div class="float-right">' . number_format($rd->jasa_poli) . '</div>';
            $row[]  = '<div class="text-center">
                <button type="button" class="btn btn-warning" onclick="ubah(' . "'" . $rd->kode_tarif . "'" . ')" ' . $upd_diss . '><i class="fa-regular fa-pen-to-square"></i></button>
                <button type="button" class="btn btn-danger" onclick="hapus(' . "'" . $rd->kode_tarif . "'" . ')" ' . $del_diss . '><i class="fa-regular fa-circle-xmark"></i></button>
            </div>';
            $data[] = $row;
        }

        // Prepare the output in JSON format
        $output = [
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->M_tarif->count_all($param1),
            "recordsFiltered"   => $this->M_tarif->count_filtered($param1),
            "data"              => $data,
        ];

        // Send the output to the view
        echo json_encode($output);
    }

    // form tin_single page
    public function form_tin_single($param)
    {
        // website config
        $web_setting        = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version        = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        if ($param != '0') {
            $tarif          = $this->M_global->getData('m_tarif', ['kode_tarif' => $param]);
            $single_jasa    = $this->M_global->getDataResult('tarif_jasa', ['kode_tarif' => $param]);
            $single_bhp     = $this->M_global->getDataResult('tarif_single_bhp', ['kode_tarif' => $param]);
        } else {
            $tarif          = null;
            $single_jasa    = null;
            $single_bhp     = null;
        }

        $parameter = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Tarif Single',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => '',
            'tarif'         => $tarif,
            'single_jasa'   => $single_jasa,
            'single_bhp'    => $single_bhp,
        ];

        $this->template->load('Template/Content', 'Master/Tarif/Form_single', $parameter);
    }

    public function add_kategori_tarif()
    {
        $kode_kategori    = _kodeKategoriTarif();
        $inisial          = $this->input->post('inisial_kategori');
        $keterangan       = $this->input->post('keterangan_kategori');

        $cek              = $this->M_global->insertData('kategori_tarif', ['kode_kategori' => $kode_kategori, 'keterangan' => $keterangan, 'inisial_kode' => $inisial]);

        if ($cek) {
            aktifitas_user('Master Tarif (Kategori)', 'menambahkan Kategori Tarif', $kode_kategori, $keterangan);
            echo json_encode(['status' => 1]);
        } else {
            echo json_encode(['status' => 0]);
        }
    }

    public function tin_single_proses($param)
    {
        $kategori       = $this->input->post('kategori');

        if ($param == 1) {
            $kode_tarif = _kodeTarif(1);
        } else {
            $kode_tarif = $this->input->post('kodeTarif');
        }

        $nama           = $this->input->post('nama');
        $jenis          = 1;

        $kode_cabang    = $this->input->post('kode_cabang');
        $jasa_rs        = $this->input->post('jasa_rs');
        $jasa_dokter    = $this->input->post('jasa_dokter');
        $jasa_pelayanan = $this->input->post('jasa_pelayanan');
        $jasa_poli      = $this->input->post('jasa_poli');

        $kode_barang    = $this->input->post('kode_barang');
        $kode_satuan    = $this->input->post('kode_satuan');
        $harga          = $this->input->post('harga');
        $qty            = $this->input->post('qty');
        $jumlah         = $this->input->post('jumlah');

        $isi = [
            'kode_tarif'    => $kode_tarif,
            'nama'          => $nama,
            'kategori'      => $kategori,
            'jenis'         => $jenis,
        ];

        if (isset($kode_cabang)) {
            $jum = count($kode_cabang);

            if ($param == 1) {
                $cek_param = 'menambahkan';

                $cek = $this->M_global->insertData('m_tarif', $isi, ['kode_tarif' => $kode_tarif]);
            } else {
                $cek_param = 'mengubah';

                $cek = [
                    $this->M_global->delData('tarif_single_bhp', ['kode_tarif' => $kode_tarif]),
                    $this->M_global->delData('tarif_jasa', ['kode_tarif' => $kode_tarif]),
                    $this->M_global->updateData('m_tarif', $isi, ['kode_tarif' => $kode_tarif]),
                ];
            }

            aktifitas_user('Master Tarif Single', $cek_param . ' Tarif Single', $kode_tarif, $nama, $isi);

            if ($cek) {
                // JASA
                for ($x = 0; $x <= ($jum - 1); $x++) {
                    $_kode_cabang       = $kode_cabang[$x];
                    $_jasa_rs           = str_replace(',', '', $jasa_rs[$x]);
                    $_jasa_dokter       = str_replace(',', '', $jasa_dokter[$x]);
                    $_jasa_pelayanan    = str_replace(',', '', $jasa_pelayanan[$x]);
                    $_jasa_poli         = str_replace(',', '', $jasa_poli[$x]);

                    $detail = [
                        'kode_tarif'        => $kode_tarif,
                        'kode_cabang'       => $_kode_cabang,
                        'jasa_rs'           => $_jasa_rs,
                        'jasa_dokter'       => $_jasa_dokter,
                        'jasa_pelayanan'    => $_jasa_pelayanan,
                        'jasa_poli'         => $_jasa_poli,
                    ];

                    $this->M_global->insertData('tarif_jasa', $detail);
                }

                // BHP
                if (isset($kode_barang)) {
                    $jumBhp = count($kode_barang);
                    for ($z = 0; $z <= ($jumBhp - 1); $z++) {
                        $_kode_barang   = $kode_barang[$z];
                        $_kode_satuan   = $kode_satuan[$z];
                        $_qty           = str_replace(',', '', $qty[$z]);
                        $_harga         = str_replace(',', '', $harga[$z]);
                        $_jumlah        = str_replace(',', '', $jumlah[$z]);

                        $barang1        = $this->M_global->getData('barang', ['kode_barang' => $_kode_barang, 'kode_satuan' => $_kode_satuan]);
                        $barang2        = $this->M_global->getData('barang', ['kode_barang' => $_kode_barang, 'kode_satuan2' => $_kode_satuan]);
                        $barang3        = $this->M_global->getData('barang', ['kode_barang' => $_kode_barang, 'kode_satuan3' => $_kode_satuan]);

                        if ($barang1) {
                            $qty_satuan = 1;
                        } else if ($barang2) {
                            $qty_satuan = $barang2->qty_satuan2;
                        } else {
                            $qty_satuan = $barang3->qty_satuan3;
                        }

                        $qty_konversi   = $_qty * $qty_satuan;

                        $detail_bhp = [
                            'kode_tarif'        => $kode_tarif,
                            'kode_barang'       => $_kode_barang,
                            'kode_satuan'       => $_kode_satuan,
                            'qty_konversi'      => $qty_konversi,
                            'qty'               => $_qty,
                            'harga'             => $_harga,
                            'jumlah'            => $_jumlah,
                        ];

                        $this->M_global->insertData('tarif_single_bhp', $detail_bhp);
                    }
                }

                echo json_encode(['status' => 1]);
            } else {
                echo json_encode(['status' => 0]);
            }
        } else {
            echo json_encode(['status' => 0]);
        }
    }

    public function delTarifSingle($kode_tarif)
    {
        aktifitas_user('Master Tarif Single', 'hapus Tarif Single', $kode_tarif, $this->M_global->getData('m_tarif', ['kode_tarif' => $kode_tarif])->nama);

        $cek = [
            $this->M_global->delData('tarif_single_bhp', ['kode_tarif' => $kode_tarif]),
            $this->M_global->delData('tarif_jasa', ['kode_tarif' => $kode_tarif]),
            $this->M_global->delData('m_tarif', ['kode_tarif' => $kode_tarif]),
        ];

        if ($cek) {
            echo json_encode(['status' => 1]);
        } else {
            echo json_encode(['status' => 0]);
        }
    }

    // ############################################################################################################################################################################

    /**
     * Master Tarif Paket
     */

    // paket page
    public function tin_paket()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter   = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Tindakan Paket',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Master/tin_paket_list',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Master/Tarif/Paket', $parameter);
    }

    // fungsi list paket
    public function tin_paket_list($param1 = 2)
    {
        $this->load->model('M_tarif');
        $kode_cabang                = $this->session->userdata('cabang');

        // kondisi role
        $updated                    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted                    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;

        if ($updated > 0) {
            $upd_diss               = '';
        } else {
            $upd_diss               = 'disabled';
        }

        $list                       = $this->M_tarif->get_datatables($param1);

        $data                       = [];
        $no                         = $_POST['start'] + 1;

        // Loop through the list to populate the data array
        foreach ($list as $rd) {
            if ($deleted > 0) {
                $cekIsset           = $this->M_global->jumDataRow('tarif_paket_pasien', ['kode_tarif' => $rd->kode_tarif]);
                if ($cekIsset > 0) {
                    $del_diss       = 'disabled';
                } else {
                    $del_diss       = '';
                }
            } else {
                $del_diss           = 'disabled';
            }

            $kunjungan              = count($this->M_global->getDataResult('tarif_paket', ['kode_tarif' => $rd->kode_tarif, 'kode_cabang' => $kode_cabang]));

            $jasa_rs                = [];
            $jasa_dokter            = [];
            $jasa_pelayanan         = [];
            $jasa_poli              = [];
            $kunj                   = [];

            for ($x = 1; $x <= $kunjungan; $x++) {
                $jasa               = $this->M_global->getData('tarif_paket', ['kode_tarif' => $rd->kode_tarif, 'kunjungan' => $x]);

                $kunj[$x]           = $x;
                $jasa_rs[$x]        = number_format($jasa->jasa_rs);
                $jasa_dokter[$x]    = number_format($jasa->jasa_dokter);
                $jasa_pelayanan[$x] = number_format($jasa->jasa_pelayanan);
                $jasa_poli[$x]      = number_format($jasa->jasa_poli);
            }

            $jasa_rs_str            = implode('<br>', array_map(fn($k, $v) => "<div style='float: left;'>Paket $k: Rp.</div><div class='float-right'>$v</div>", array_keys($jasa_rs), $jasa_rs));
            $jasa_dokter_str        = implode('<br>', array_map(fn($k, $v) => "<div style='float: left;'>Paket $k: Rp.</div><div class='float-right'>$v</div>", array_keys($jasa_dokter), $jasa_dokter));
            $jasa_pelayanan_str     = implode('<br>', array_map(fn($k, $v) => "<div style='float: left;'>Paket $k: Rp.</div><div class='float-right'>$v</div>", array_keys($jasa_pelayanan), $jasa_pelayanan));
            $jasa_poli_str          = implode('<br>', array_map(fn($k, $v) => "<div style='float: left;'>Paket $k: Rp.</div><div class='float-right'>$v</div>", array_keys($jasa_poli), $jasa_poli));

            $row    = [];
            $row[]  = $no++;
            $row[]  = $rd->kode_tarif . '<br><span class="badge badge-primary">Kunjungan: ' . $kunjungan . '</span>';
            $row[]  = $rd->nama;
            $row[]  = '<div>' . $jasa_rs_str . '</div>';
            $row[]  = '<div>' . $jasa_dokter_str . '</div>';
            $row[]  = '<div>' . $jasa_pelayanan_str . '</div>';
            $row[]  = '<div>' . $jasa_poli_str . '</div>';
            $row[]  = '<div class="text-center">
                <button type="button" class="btn btn-warning" onclick="ubah(' . "'" . $rd->kode_tarif . "'" . ')" ' . $upd_diss . '><i class="fa-regular fa-pen-to-square"></i></button>
                <button type="button" class="btn btn-danger" onclick="hapus(' . "'" . $rd->kode_tarif . "'" . ')" ' . $del_diss . '><i class="fa-regular fa-circle-xmark"></i></button>
            </div>';
            $data[] = $row;
        }

        // Prepare the output in JSON format
        $output = [
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->M_tarif->count_all($param1),
            "recordsFiltered"   => $this->M_tarif->count_filtered($param1),
            "data"              => $data,
        ];

        // Send the output to the view
        echo json_encode($output);
    }

    // form tin_paket page
    public function form_tin_paket($param)
    {
        // website config
        $web_setting    = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version    = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        if ($param != '0') {
            $tarif      = $this->M_global->getData('m_tarif', ['kode_tarif' => $param]);
            $paket_jasa = $this->M_global->getDataResult('tarif_paket', ['kode_tarif' => $param]);
            $single_bhp = $this->M_global->getDataResult('tarif_paket_bhp', ['kode_tarif' => $param]);
        } else {
            $tarif      = null;
            $paket_jasa = null;
            $single_bhp = null;
        }

        $parameter = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Tarif Single',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => '',
            'tarif'         => $tarif,
            'paket_jasa'    => $paket_jasa,
            'single_bhp'    => $single_bhp,
        ];

        $this->template->load('Template/Content', 'Master/Tarif/Form_paket', $parameter);
    }

    public function tin_paket_proses($param)
    {
        $kategori       = $this->input->post('kategori');

        if ($param == 1) {
            $kode_tarif = _kodeTarif(2);
        } else {
            $kode_tarif = $this->input->post('kodeTarif');
        }

        $nama           = $this->input->post('nama');
        $jenis          = 2;

        $kode_cabang    = $this->input->post('kode_cabang');
        $kunjungan      = $this->input->post('kunjungan');
        $jasa_rs        = $this->input->post('jasa_rs');
        $jasa_dokter    = $this->input->post('jasa_dokter');
        $jasa_pelayanan = $this->input->post('jasa_pelayanan');
        $jasa_poli      = $this->input->post('jasa_poli');

        $kode_barang    = $this->input->post('kode_barang');
        $kode_satuan    = $this->input->post('kode_satuan');
        $harga          = $this->input->post('harga');
        $qty            = $this->input->post('qty');
        $jumlah         = $this->input->post('jumlah');

        $isi = [
            'kode_tarif'    => $kode_tarif,
            'nama'          => $nama,
            'kategori'      => $kategori,
            'jenis'         => $jenis,
        ];

        if (isset($kode_cabang)) {
            $jum = count($kode_cabang);

            if ($param == 1) {
                $cek_param = 'menambahkan';
                $cek = $this->M_global->insertData('m_tarif', $isi, ['kode_tarif' => $kode_tarif]);
            } else {
                $cek_param = 'mengubah';
                $cek = [
                    $this->M_global->delData('tarif_paket_bhp', ['kode_tarif' => $kode_tarif]),
                    $this->M_global->delData('tarif_paket', ['kode_tarif' => $kode_tarif]),
                    $this->M_global->updateData('m_tarif', $isi, ['kode_tarif' => $kode_tarif]),
                ];
            }

            aktifitas_user('Master Tarif Paket', $cek_param . ' Tarif Paket', $kode_tarif, $nama, $isi);

            if ($cek) {
                // JASA
                for ($x = 0; $x <= ($jum - 1); $x++) {
                    $_kode_cabang       = $kode_cabang[$x];
                    $_kunjungan         = str_replace(',', '', $kunjungan[$x]);
                    $_jasa_rs           = str_replace(',', '', $jasa_rs[$x]);
                    $_jasa_dokter       = str_replace(',', '', $jasa_dokter[$x]);
                    $_jasa_pelayanan    = str_replace(',', '', $jasa_pelayanan[$x]);
                    $_jasa_poli         = str_replace(',', '', $jasa_poli[$x]);

                    $detail = [
                        'kode_tarif'        => $kode_tarif,
                        'kode_cabang'       => $_kode_cabang,
                        'kunjungan'         => $_kunjungan,
                        'jasa_rs'           => $_jasa_rs,
                        'jasa_dokter'       => $_jasa_dokter,
                        'jasa_pelayanan'    => $_jasa_pelayanan,
                        'jasa_poli'         => $_jasa_poli,
                    ];

                    $this->M_global->insertData('tarif_paket', $detail);
                }

                // BHP
                if (isset($kode_barang)) {
                    $jumBhp             = count($kode_barang);

                    for ($z = 0; $z <= ($jumBhp - 1); $z++) {
                        $_kode_barang   = $kode_barang[$z];
                        $_kode_satuan   = $kode_satuan[$z];
                        $_qty           = str_replace(',', '', $qty[$z]);
                        $_harga         = str_replace(',', '', $harga[$z]);
                        $_jumlah        = str_replace(',', '', $jumlah[$z]);

                        $barang1        = $this->M_global->getData('barang', ['kode_barang' => $_kode_barang, 'kode_satuan' => $_kode_satuan]);
                        $barang2        = $this->M_global->getData('barang', ['kode_barang' => $_kode_barang, 'kode_satuan2' => $_kode_satuan]);
                        $barang3        = $this->M_global->getData('barang', ['kode_barang' => $_kode_barang, 'kode_satuan3' => $_kode_satuan]);

                        if ($barang1) {
                            $qty_satuan = 1;
                        } else if ($barang2) {
                            $qty_satuan = $barang2->qty_satuan2;
                        } else {
                            $qty_satuan = $barang3->qty_satuan3;
                        }

                        $qty_konversi   = $_qty * $qty_satuan;

                        $detail_bhp     = [
                            'kode_tarif'        => $kode_tarif,
                            'kode_barang'       => $_kode_barang,
                            'kode_satuan'       => $_kode_satuan,
                            'qty_konversi'      => $qty_konversi,
                            'qty'               => $_qty,
                            'harga'             => $_harga,
                            'jumlah'            => $_jumlah,
                        ];

                        $this->M_global->insertData('tarif_paket_bhp', $detail_bhp);
                    }
                }


                echo json_encode(['status' => 1]);
            } else {
                echo json_encode(['status' => 0]);
            }
        } else {
            echo json_encode(['status' => 0]);
        }
    }

    public function delTarifPaket($kode_tarif)
    {
        aktifitas_user('Master Tarif Paket', 'hapus Tarif Paket', $kode_tarif, $this->M_global->getData('m_tarif', ['kode_tarif' => $kode_tarif])->nama);

        $cek = [
            $this->M_global->delData('tarif_paket_bhp', ['kode_tarif' => $kode_tarif]),
            $this->M_global->delData('tarif_paket', ['kode_tarif' => $kode_tarif]),
            $this->M_global->delData('m_tarif', ['kode_tarif' => $kode_tarif]),
        ];

        if ($cek) {
            echo json_encode(['status' => 1]);
        } else {
            echo json_encode(['status' => 0]);
        }
    }

    /**
     * Master Akun
     * untuk menampilkan, menambahkan, dan mengubah akun dalam sistem
     */

    // akun page
    public function akun()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter   = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Akun',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'akun'          => $this->M_global->getResult('m_akun'),
            'list_data'     => 'Master/akun_list',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Master/Umum/Akun', $parameter);
    }

    // fungsi list akun
    public function akun_list($param1 = '')
    {
        // Parameter untuk list table
        $table                    = 'm_akun';
        $columns                  = ['id', 'kode_akun', 'nama_akun', 'kode_klasifikasi', 'header', 'sub_akun'];
        $order                    = 'id';
        $order_dir                = 'desc';
        $order_arr                = ['id' => 'asc'];
        $param_condition          = '';

        // Kondisi role
        $role                     = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']]);
        $updated                  = $role->updated;
        $deleted                  = $role->deleted;

        $upd_diss                 = ($updated > 0) ? '' : 'disabled';
        $del_diss                 = ($deleted > 0) ? '' : 'disabled';

        // Table server side tampung kedalam variable $list
        $list                     = $this->M_datatables->get_datatables($table, $columns, $order_arr, $order, $order_dir, $param1, $param_condition);
        $data                     = [];
        $no                       = $_POST['start'] + 1;

        // Loop $list
        foreach ($list as $rd) {
            $sub_akun             = ($rd->sub_akun) ? $this->M_global->getData('m_akun', ['kode_akun' => $rd->sub_akun])->nama_akun : 'Root';

            if ($deleted > 0) {
                $sub_akun         = $rd->kode_akun;

                // Gunakan parameter binding untuk keamanan
                $query            = $this->db->get('m_akun');
                $cek_dis          = $query->result();

                // Inisialisasi array untuk menyimpan kode akun dari hasil query
                $cek_akun         = [];
                foreach ($cek_dis as $cd) {
                    $cek_akun[]   = $cd->sub_akun;
                }

                // Cek apakah kode akun ada dalam array $cek_akun
                if (in_array($rd->kode_akun, $cek_akun)) {
                    $del_diss     = 'disabled';  // Set to 'disabled' jika $kode_akun ditemukan
                } else {
                    $del_diss     = '';  // Set to '' (enabled) jika $kode_akun tidak ditemukan
                }
            } else {
                $del_diss         = 'disabled';
            }

            $row   = [];
            $row[] = $no++;
            $row[] = htmlspecialchars($rd->kode_akun);
            $row[] = htmlspecialchars($rd->nama_akun);
            $row[] = htmlspecialchars($this->M_global->getData('klasifikasi_akun', ['kode_klasifikasi' => $rd->kode_klasifikasi])->klasifikasi);
            $row[] = htmlspecialchars($sub_akun);
            $row[] = '<div class="text-center">
                <button type="button" class="btn btn-warning" onclick="ubah(' . "'" . $rd->kode_akun . "'" . ')" ' . $upd_diss . '><i class="fa-regular fa-pen-to-square"></i></button>
                <button type="button" class="btn btn-danger" onclick="hapus(' . "'" . $rd->kode_akun . "'" . ')" ' . $del_diss . '><i class="fa-regular fa-circle-xmark"></i></button>
            </div>';

            $data[] = $row;
        }

        // Hasil server side
        $output = [
            "draw"            => intval($_POST['draw']),
            "recordsTotal"    => $this->M_datatables->count_all($table, $columns, $order_arr, $order, $order_dir, $param1, $param_condition),
            "recordsFiltered" => $this->M_datatables->count_filtered($table, $columns, $order_arr, $order, $order_dir, $param1, $param_condition),
            "data"            => $data,
        ];

        // Kirimkan ke view
        echo json_encode($output);
    }


    // fungsi cek akun berdasarkan nama_akun akun
    public function cekAkun()
    {
        // ambil nama_akun inputan
        $nama_akun    = $this->input->post('nama_akun');

        // cek nama_akun pada table m_akun
        $cek          = $this->M_global->jumDataRow('m_akun', ['nama_akun' => $nama_akun]);

        if ($cek < 1) { // jika tidak ada/ kurang dari 1
            // kirimkan status 1
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi proses simpan/update akun
    public function akun_proses($param)
    {
        // variable
        $nama_akun          = $this->input->post('nama_akun');
        $kode_klasifikasi   = $this->input->post('kode_klasifikasi');
        $sub_akun           = $this->input->post('sub_akun');
        if (!$sub_akun || $sub_akun == null) {
            $header         = 1;
        } else {
            $header         = 2;
        }

        if ($param == 1) { // jika parameternya 1
            // maka buat kode baru
            $kodeAkun       = _kodeAkun();
        } else { // selain itu
            // ambil kode dari inputan
            $kodeAkun       = $this->input->post('kodeAkun');
        }

        // tampung variable kedalam $isi
        $isi = [
            'kode_akun'         => $kodeAkun,
            'nama_akun'         => $nama_akun,
            'kode_klasifikasi'  => $kode_klasifikasi,
            'header'            => $header,
            'sub_akun'          => $sub_akun,
        ];

        if ($param == 1) { // jika parameternya 1
            // jalankan fungsi simpan
            $cek          = $this->M_global->insertData('m_akun', $isi);

            $cek_param    = 'menambahkan';
        } else { // selain itu
            // jalankan fungsi update
            $cek          = $this->M_global->updateData('m_akun', $isi, ['kode_akun' => $kodeAkun]);

            $cek_param    = 'mengubah';
        }

        if ($cek) { // jika fungsi berjalan
            aktifitas_user('Master Akun', $cek_param, $kodeAkun, $this->M_global->getData('m_akun', ['kode_akun' => $kodeAkun])->nama_akun);

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi ambil informasi akun berdasarkan kode akun
    public function getInfoAkun($kode_akun)
    {
        // ambil data akun berdasarkan kode_akun
        $data = $this->db->query("SELECT a.*, (SELECT nama_akun FROM m_akun WHERE kode_akun = a.sub_akun) AS nama_sub, (SELECT klasifikasi FROM klasifikasi_akun WHERE kode_klasifikasi = a.kode_klasifikasi) AS nama_klasifikasi FROM m_akun a WHERE a.kode_akun = '$kode_akun'")->row();
        // lempar ke view
        echo json_encode($data);
    }

    public function subAkun()
    {
        $sub_akun = $this->M_global->getResult('m_akun');

        echo json_encode($sub_akun);
    }

    // fungsi hapus akun berdasarkan kode_akun
    public function delAkun($kode_akun)
    {
        // jalankan fungsi hapus akun berdasarkan kode_akun
        aktifitas_user('Master Akun', 'menghapus', $kode_akun, $this->M_global->getData('m_akun', ['kode_akun' => $kode_akun])->nama_akun);
        $cek = $this->M_global->delData('m_akun', ['kode_akun' => $kode_akun]);

        if ($cek) { // jika fungsi berjalan

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    /**
     * Master tipe_bank
     * untuk menampilkan, menambahkan, dan mengubah tipe_bank dalam sistem
     */

    // tipe_bank page
    public function tipe_bank()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter   = [
            $this->data,
            'judul'         => 'Master',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Tipe Bank',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Master/tipe_bank_list',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Master/Umum/Tipe', $parameter);
    }

    // fungsi list tipe_bank
    public function tipe_bank_list($param1 = '')
    {
        // parameter untuk list table
        $table            = 'tipe_bank';
        $colum            = ['id', 'kode_tipe', 'keterangan'];
        $order            = 'id';
        $order2           = 'desc';
        $order_arr        = ['id' => 'asc'];
        $kondisi_param1   = '';

        // kondisi role
        $updated          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->updated;
        $deleted          = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->deleted;

        if ($updated > 0) {
            $upd_diss     = '';
        } else {
            $upd_diss     = 'disabled';
        }

        // table server side tampung kedalam variable $list
        $list             = $this->M_datatables->get_datatables($table, $colum, $order_arr, $order, $order2, $param1, $kondisi_param1);
        $data             = [];
        $no               = $_POST['start'] + 1;

        // loop $list
        foreach ($list as $rd) {
            if ($deleted > 0) {
                $del_diss       = '';
            } else {
                $del_diss           = 'disabled';
            }

            $row    = [];
            $row[]  = $no++;
            $row[]  = $rd->kode_tipe;
            $row[]  = $rd->keterangan;
            $row[]  = '<div class="text-center">
                <button type="button" class="btn btn-warning" style="margin-bottom: 5px;" onclick="ubah(' . "'" . $rd->kode_tipe . "'" . ')" ' . $upd_diss . '><i class="fa-regular fa-pen-to-square"></i></button>
                <button type="button" class="btn btn-danger" style="margin-bottom: 5px;" onclick="hapus(' . "'" . $rd->kode_tipe . "'" . ')" ' . $del_diss . '><i class="fa-regular fa-circle-xmark"></i></button>
            </div>';
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

    // fungsi cek tipe_bank berdasarkan keterangan tipe_bank
    public function cekTipeBank()
    {
        // ambil keterangan inputan
        $keterangan   = $this->input->post('keterangan');

        // cek keterangan pada table tipe_bank
        $cek          = $this->M_global->jumDataRow('tipe_bank', ['keterangan' => $keterangan]);

        if ($cek < 1) { // jika tidak ada/ kurang dari 1
            // kirimkan status 1
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi proses simpan/update tipe_bank
    public function tipe_bank_proses($param)
    {
        // variable
        $keterangan       = $this->input->post('keterangan');

        if ($param == 1) { // jika parameternya 1
            // maka buat kode baru
            $kodeTipe   = _kodeTipeBank();
        } else { // selain itu
            // ambil kode dari inputan
            $kodeTipe   = $this->input->post('kodeTipe');
        }

        // tampung variable kedalam $isi
        $isi = [
            'kode_tipe'     => $kodeTipe,
            'keterangan'    => $keterangan,
        ];

        if ($param == 1) { // jika parameternya 1
            // jalankan fungsi simpan
            $cek = $this->M_global->insertData('tipe_bank', $isi);

            $cek_param = 'menambahkan';
        } else { // selain itu
            // jalankan fungsi update
            $cek = $this->M_global->updateData('tipe_bank', $isi, ['kode_tipe' => $kodeTipe]);

            $cek_param = 'mengubah';
        }

        if ($cek) { // jika fungsi berjalan
            aktifitas_user(
                'Master Tipe Bank',
                $cek_param,
                $kodeTipe,
                $this->M_global->getData('tipe_bank', ['kode_tipe' => $kodeTipe])->keterangan
            );

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }

    // fungsi ambil informasi tipe_bank berdasarkan kode tipe_bank
    public function getInfoTipe($kode_satuan)
    {
        // ambil data tipe berdasarkan kode_tipe
        $data = $this->M_global->getData('tipe_bank', ['kode_tipe' => $kode_satuan]);
        // lempar ke view
        echo json_encode($data);
    }

    // fungsi hapus tipe berdasarkan kode_tipe
    public function delTipe($kode_tipe)
    {
        // jalankan fungsi hapus tipe berdasarkan kode_tipe
        aktifitas_user('Master Tipe Bank', 'menghapus', $kode_tipe, $this->M_global->getData('tipe_bank', ['kode_tipe' => $kode_tipe])->keterangan);
        $cek = $this->M_global->delData('tipe_bank', ['kode_tipe' => $kode_tipe]);

        if ($cek) { // jika fungsi berjalan

            // kirimkan status 1 ke view
            echo json_encode(['status' => 1]);
        } else { // selain itu
            // kirimkan status 0 ke view
            echo json_encode(['status' => 0]);
        }
    }
}
