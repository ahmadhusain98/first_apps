<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Emr extends CI_Controller
{
    // variable open public untuk controller Home
    public $data;

    public function __construct()
    {
        parent::__construct();
        // load model M_auth
        $this->load->model("M_auth");

        if (!empty($this->session->userdata("email"))) { // jika session email masih ada

            $id_menu = $this->M_global->getData('m_menu', ['url' => 'Health'])->id;

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
                    'menu'      => 'Home',
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

    public function index()
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter = [
            $this->data,
            'judul'         => 'Electrical Medical Record',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'EMR',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => 'Emr/daftar_list',
            'param1'        => '',
        ];

        $this->template->load('Template/Content', 'Emr/Daftar', $parameter);
    }

    public function pencarian($key = '')
    {
        $cabang = $this->session->userdata('cabang');

        if ($key == '' || $key == null || $key == 'null') {
            $add_sintak = '';
        } else {
            $add_sintak = 'AND (m.nama LIKE "%' . $key . '%" OR m.kode_member LIKE "%' . $key . '%")';
        }

        $sintak = $this->db->query(
            'SELECT p.*, m.nama
            FROM pendaftaran p
            JOIN member m ON p.kode_member = m.kode_member
            WHERE p.status_trx = 0 AND p.kode_cabang = "' . $cabang . '"' . $add_sintak
        )->result();

?>
        <table class="table table-hover table-bordered" id="tablePendaftaran" width="100%" style="border-radius: 10px;">
            <tbody>
                <?php foreach ($sintak as $s) : ?>
                    <tr>
                        <td style="width: 15%;"><?= $s->no_antrian ?></td>
                        <td style="width: 55%;"><?= $s->nama ?></td>
                        <td style="width: 30%;" class="text-center">
                            <button type="button" class="btn mb-1 btn-success btn-circle" title="Panggil"><i class="fa-solid fa-microphone-lines"></i></button>
                            <button type="button" class="btn mb-1 btn-dark btn-circle" title="Lewati"><i class="fa-solid fa-microphone-lines-slash"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
<?php
    }
}
