<?php
$created    = $this->M_global->getData('m_role', ['kode_role' => $this->data['kode_role']])->created;

?>

<button type="button" class="btn btn-success" onclick="getUrl('Accounting/form_sa_akun/0')" <?= (($created > 0) ? '' : 'disabled') ?>><i class="fa-solid fa-circle-plus"></i>&nbsp;&nbsp;Tambah</button>