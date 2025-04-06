<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Layar extends CI_Controller
{
    // variable open public untuk controller Home
    public $data;

    public function __construct()
    {
        parent::__construct();
        // load model M_auth
        $this->load->model("M_auth");
        $this->load->model("M_order_emr");

        if (!empty($this->session->userdata("email"))) { // jika session email masih ada

            $id_menu = $this->M_global->getData('m_menu', ['url' => 'Transaksi'])->id;

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
                    'menu'      => 'Transaksi',
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

    public function perawat($cabang)
    {
        // website config
        $web_setting = $this->M_global->getData('web_setting', ['id' => 1]);
        $web_version = $this->M_global->getData('web_version', ['id_web' => $web_setting->id]);

        $parameter = [
            $this->data,
            'judul'         => 'Layar Antrian Perawat',
            'nama_apps'     => $web_setting->nama,
            'page'          => 'Antrian Perawat',
            'web'           => $web_setting,
            'web_version'   => $web_version->version,
            'list_data'     => '',
            'param1'        => '',
            'ruang'         => $this->db->query(
                'SELECT * FROM layar_perawat WHERE kode_cabang = "' . $cabang . '" AND no_trx LIKE "%' . date('Ymd') . '%" GROUP BY kode_ruang ORDER BY kode_ruang ASC'
            )->result(),
        ];

        $this->template->load('Template/Layar', 'Layar/Perawat', $parameter);
    }

    public function antrianPerawat($kode_ruang)
    {
        $kode_cabang = $this->session->userdata('cabang');

        $panggil = $this->db->query('
            SELECT * 
            FROM layar_perawat 
            WHERE status <> 0 
              AND kode_ruang = "' . $kode_ruang . '" 
              AND kode_cabang = "' . $kode_cabang . '" 
              AND no_trx LIKE "%' . date('Ymd') . '%"
              AND panggil = (SELECT MAX(panggil) 
                     FROM layar_perawat 
                     WHERE status <> 0 
                       AND kode_ruang = "' . $kode_ruang . '" 
                       AND kode_cabang = "' . $kode_cabang . '")
            LIMIT 1
        ')->row();
?>
        <div class="row">
            <div class="col-md-12">
                <div class="font-weight-bold" style="font-size: 80px;">
                    <?= (($panggil) ? $panggil->no_antrian : '') ?>
                </div>
            </div>
        </div>
        <input type="hidden" name="now" id="now" value="<?= (($panggil) ? $panggil->no_antrian : '') ?>">
        <?php
    }

    public function antrianPerawat2($kode_ruang, $now)
    {
        $kode_cabang = $this->session->userdata('cabang');

        $panggil = $this->db->query('
            SELECT * 
            FROM layar_perawat 
            WHERE status = 0 
              AND kode_ruang = "' . $kode_ruang . '" 
              AND kode_cabang = "' . $kode_cabang . '" 
              AND no_antrian <> "' . $now . '"
              AND no_trx LIKE "%' . date('Ymd') . '%"
              AND no_trx LIKE "%20250321%" LIMIT 2
        ')->result();
        if ($panggil) :
            foreach ($panggil as $p) :
        ?>
                <button type="button" class="btn btn-info w-100" style="margin-bottom: 5px;" disabled><?= $p->no_antrian ?></button>
            <?php endforeach;
            echo '<button type="button" class="btn btn-warning w-100" style="margin-bottom: 5px;" disabled>Next...</button>';
        else :
            ?>
            <button type="button" class="btn btn-danger w-100" disabled>Kosong</button>
<?php
        endif;
    }
}
