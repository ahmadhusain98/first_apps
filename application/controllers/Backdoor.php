<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Backdoor extends CI_Controller
{
    // variable open public untuk controller Home
    public $data;

    public function __construct()
    {
        parent::__construct();
        // load model M_auth
        $this->load->model("M_auth");

        $this->db->query("SET SESSION sql_mode = REPLACE(
            REPLACE(
                REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY,', ''),
            ',ONLY_FULL_GROUP_BY', ''),
        'ONLY_FULL_GROUP_BY', '')");

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
                'menu'      => 'Home',
            ];
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
            'judul'             => 'Pintasan',
            'nama_apps'         => $web_setting->nama,
            'page'              => 'Backdoor',
            'web'               => $web_setting,
            'web_version'       => $web_version->version,
            'kunjungan_poli'    => $this->db->query("SELECT p.keterangan AS poli, COUNT(boh.kode_poli) AS jumlah FROM pembayaran buy JOIN barang_out_header boh ON buy.inv_jual = boh.invoice JOIN m_poli p ON boh.kode_poli = p.kode_poli GROUP BY boh.kode_poli")->result(),
            'jumlah_beli'       => count($this->M_global->getResult('barang_out_header')),
            'jumlah_member'     => count($this->M_global->getResult('member')),
        ];

        $this->template->load('Template/Content', 'Backdoor/Data', $parameter);
    }

    // trx_empty page
    public function trx_empty()
    {
        $sess       = $this->session->userdata('email');
        $cabang     = $this->session->userdata('init_cabang');
        $shift      = $this->session->userdata('shift');

        $aktifitas = [
            'email'         => $sess,
            'kegiatan'      => $sess . " Telah <b>mengosongkan Transaksi</b>",
            'menu'          => 'Pintu Belakang',
            'waktu'         => date('Y-m-d H:i:s'),
            'kode_cabang'   => $cabang,
            'shift'         => $shift,
        ];

        $this->db->insert("activity_user", $aktifitas);

        $sintak = [
            // po
            $this->db->query("TRUNCATE TABLE barang_po_in_header"),
            $this->db->query("TRUNCATE TABLE barang_po_in_detail"),

            // pembelian
            $this->db->query("TRUNCATE TABLE barang_in_header"),
            $this->db->query("TRUNCATE TABLE barang_in_detail"),

            // piutang
            $this->db->query("TRUNCATE TABLE piutang"),

            // retur pembelian
            $this->db->query("TRUNCATE TABLE barang_in_retur_header"),
            $this->db->query("TRUNCATE TABLE barang_in_retur_detail"),

            // penjualan
            $this->db->query("TRUNCATE TABLE barang_out_header"),
            $this->db->query("TRUNCATE TABLE barang_out_detail"),

            // retur penjualan
            $this->db->query("TRUNCATE TABLE barang_out_retur_header"),
            $this->db->query("TRUNCATE TABLE barang_out_retur_detail"),

            // stok
            $this->db->query("TRUNCATE TABLE barang_stok"),

            // pembayaran
            $this->db->query("TRUNCATE TABLE bayar_card_detail"),
            $this->db->query("TRUNCATE TABLE bayar_um_card_detail"),
            $this->db->query("TRUNCATE TABLE cart_header"),
            $this->db->query("TRUNCATE TABLE cart_detail"),
            $this->db->query("TRUNCATE TABLE cart_promo"),
            $this->db->query("TRUNCATE TABLE pembayaran"),
            $this->db->query("TRUNCATE TABLE pembayaran_uangmuka"),
            $this->db->query("TRUNCATE TABLE uang_muka"),

            // pendaftaran
            $this->db->query("TRUNCATE TABLE pendaftaran"),

            // log user
            $this->db->query("TRUNCATE TABLE activity_user"),

        ];

        if ($sintak) {
            echo json_encode(['status' => 1]);
        } else {
            echo json_encode(['status' => 0]);
        }
    }

    public function db_empty()
    {
        $sess       = $this->session->userdata('email');
        $cabang     = $this->session->userdata('init_cabang');
        $shift      = $this->session->userdata('shift');

        $aktifitas = [
            'email'         => $sess,
            'kegiatan'      => $sess . " Telah <b>mengosongkan Database</b>",
            'menu'          => 'Pintu Belakang',
            'waktu'         => date('Y-m-d H:i:s'),
            'kode_cabang'   => $cabang,
            'shift'         => $shift,
        ];

        $this->db->insert("activity_user", $aktifitas);

        $cek        = $this->db->query("SELECT table_name AS my_table FROM information_schema.tables WHERE table_schema = '" . $this->db->database . "'");

        if ($cek->num_rows() > 0) {
            foreach ($cek->result() as $c) {
                if ($c->my_table == 'user' || $c->my_table == 'member' || $c->my_table == 'cabang' || $c->my_table == 'kecamatan' || $c->my_table == 'kabupaten' || $c->my_table == 'backup_db' || $c->my_table == 'cabang_user' || $c->my_table == 'm_agama' || $c->my_table == 'm_gudang' || $c->my_table == 'm_pekerjaan' || $c->my_table == 'm_pendidikan' || $c->my_table == 'm_provinsi' || $c->my_table == 'm_role'  || $c->my_table == 'member_token' || $c->my_table == 'sub_menu' || $c->my_table == 'sub_menu2' || $c->my_table == 'user_token' || $c->my_table == 'web_setting' || $c->my_table == 'web_version' || $c->my_table == 'm_menu') {
                    $query = TRUE;
                } else {
                    $query = $this->db->query("TRUNCATE TABLE $c->my_table");
                }
            }
        } else {
            $query = TRUE;
        }

        if ($query) {
            echo json_encode(['status' => 1]);
        } else {
            echo json_encode(['status' => 0]);
        }
    }

    // home page
    public function data_db()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter = [
            $this->data,
            'judul'             => 'Backup Database',
            'nama_apps'         => $web_setting->nama,
            'page'              => 'Backdoor',
            'web'               => $web_setting,
            'web_version'       => $web_version->version,
            'kunjungan_poli'    => $this->db->query("SELECT p.keterangan AS poli, COUNT(boh.kode_poli) AS jumlah FROM pembayaran buy JOIN barang_out_header boh ON buy.inv_jual = boh.invoice JOIN m_poli p ON boh.kode_poli = p.kode_poli GROUP BY boh.kode_poli")->result(),
            'backup_db'         => $this->M_global->getResult('backup_db'),
        ];

        $this->template->load('Template/Content', 'Backdoor/Data_db', $parameter);
    }

    // backup db
    public function backup_db()
    {
        $this->load->dbutil();
        $this->load->helper('file');

        $date   = date('Ymd');
        $clock  = date('Hi');

        $dbname = $this->db->database . '_' . $date . '_' . $clock;

        $sess       = $this->session->userdata('email');
        $cabang     = $this->session->userdata('init_cabang');
        $shift      = $this->session->userdata('shift');

        $aktifitas = [
            'email'         => $sess,
            'kegiatan'      => $sess . " Telah <b>melakukan Backup Database " . $dbname . "</b>",
            'menu'          => 'Pintu Belakang',
            'waktu'         => date('Y-m-d H:i:s'),
            'kode_cabang'   => $cabang,
            'shift'         => $shift,
        ];

        $this->db->insert("activity_user", $aktifitas);

        $save   = 'database/' . $dbname . '.sql';

        $config = [
            'tables'                => [],
            'ignore'                => [],
            'format'                => 'txt',
            'filename'              => $dbname . '.sql',
            'add_drop'              => TRUE,
            'add_insert'            => TRUE,
            'foreign_key_checks'    => FALSE,
            'newline'               => "\n",
        ];

        $backup = $this->dbutil->backup($config);
        write_file($save, $backup);

        $data = [
            'nama' => $dbname . '.sql',
            'tgl_backup' => date('Y-m-d H:i:s'),
        ];

        $this->M_global->insertData('backup_db', $data);

        echo json_encode(['status' => 1]);
    }

    public function del_db($id)
    {
        $dbname = $this->M_global->getData('backup_db', ['id' => $id])->nama;

        $sess       = $this->session->userdata('email');
        $cabang     = $this->session->userdata('init_cabang');
        $shift      = $this->session->userdata('shift');

        $aktifitas = [
            'email'         => $sess,
            'kegiatan'      => $sess . " Telah <b>menghapus Backupan Database " . $dbname . "</b>",
            'menu'          => 'Pintu Belakang',
            'waktu'         => date('Y-m-d H:i:s'),
            'kode_cabang'   => $cabang,
            'shift'         => $shift,
        ];

        $this->db->insert("activity_user", $aktifitas);

        $cek = $this->M_global->delData('backup_db', ['id' => $id]);

        if ($cek) {
            if (file_exists("database/" . $dbname)) {
                unlink("database/" . $dbname);
            }

            echo json_encode(['status' => 1]);
        } else {
            echo json_encode(['status' => 0]);
        }
    }

    public function restore_db()
    {
        _drop_db();

        $fupload  = $_FILES['myfile'];
        $filename = $_FILES['myfile']['name'];

        if (isset($fupload)) {
            $lokasifile     = $fupload['tmp_name'];
            $directory      = "database/" . $filename;

            move_uploaded_file($lokasifile, $directory);

            // restore
            $isifile    = file_get_contents($directory);
            $query_arr  = explode(";", $isifile);


            foreach ($query_arr as $query) {
                $query = trim($query);
                if ($query) {
                    $this->db->query($query);
                }
            }

            unlink($directory);

            echo json_encode(['status' => 1]);
        } else {
            echo json_encode(['status' => 0]);
        }
    }

    // user akses page
    public function user_akses()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter = [
            $this->data,
            'judul'             => 'Pintasan',
            'nama_apps'         => $web_setting->nama,
            'page'              => 'Backdoor',
            'web'               => $web_setting,
            'web_version'       => $web_version->version,
            'kunjungan_poli'    => $this->db->query("SELECT p.keterangan AS poli, COUNT(boh.kode_poli) AS jumlah FROM pembayaran buy JOIN barang_out_header boh ON buy.inv_jual = boh.invoice JOIN m_poli p ON boh.kode_poli = p.kode_poli GROUP BY boh.kode_poli")->result(),
            'role'              => $this->M_global->getResult('m_role'),
            'list_data'         => 'Backdoor/akses_user_list/',
            'param1'            => null,
        ];

        $this->template->load('Template/Content', 'Backdoor/Akses_user', $parameter);
    }

    // list akses user
    public function akses_user_list() {
        $this->load->model("M_user_list");
        // Retrieve data from the model
        $list = $this->M_user_list->get_datatables();

        $data = [];
        $no = $_POST['start'] + 1;

        // Loop through the list to populate the data array
        foreach ($list as $rd) {
            $role       = $this->M_global->getResult('m_role');

            $row = [];
            $row[] = $no++;
            $row[] = $rd->kode_user . ' ~ ' . $rd->nama;
            $nor = 1;
            foreach($role AS $r) {
                $row[] = '<div class="text-center">
                    <input type="checkbox" class="form-control" id="krole'.$nor.'" '.(($r->kode_role == $rd->kode_role) ? 'checked' : '' ).' name="krole[]" value="'.$r->kode_role.'" onclick="changeRole(' . "'" . $rd->kode_user . "', '" . $r->kode_role . "', '" . $nor . "'" . ')">
                </div>';
                $nor++;
            }
            $data[] = $row;
        }

        // Prepare the output in JSON format
        $output = [
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_user_list->count_all(),
            "recordsFiltered" => $this->M_user_list->count_filtered(),
            "data" => $data,
        ];

        // Send the output to the view
        echo json_encode($output);
    }
}
