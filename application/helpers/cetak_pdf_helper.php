<?php

use Mpdf\Mpdf;

function cetak_pdf($judul, $body, $cek_param, $position, $filename, $web, $yes = 0)
{
    $CI = &get_instance();

    $mpdf = new Mpdf();

    // buat html for kop surat
    $kop = '';

    if ($position == 'P') {
        $max_width = '8';
    } else {
        $max_width = '5';
    }

    $kop .= '<table style="width: 100%; font-size: 12px; border-bottom: 3px solid #000;">
        <tbody>
            <tr>
                <td style="width: ' . $max_width . '%;">
                    <img src="./assets/img/web/' . $web->logo . '" style="width: 40px;"/>
                </td>
                <td>
                    <span style="font-weight: bold; font-size: 14px;">' . strtoupper($web->nama) . '</span>
                    <br>
                    <span style="">' . $web->alamat . '</span>
                    <br>
                    <span>Telepon: ' . $web->nohp . ' | Email: ' . $web->email . '</span>
                </td>
            </tr>
        </tbody>
    </table>';

    if ($cek_param == 0) {
        echo ("<title>$judul</title>");
        echo ($body);
    } else if ($cek_param == 1) { // jika paramnya 1 maka cetak pdf
        $mpdf->SetTitle($judul); // berikan judul
        $mpdf->SetHTMLHeader($kop); // set kop
        $mpdf->setAutoBottomMargin = 'pad';
        // $mpdf->SetWatermarkText($web->nama, 0.05); // beri watermark dengan transparansi 0.1
        // $mpdf->showWatermarkText = true; // izinkan watermark tampil
        $mpdf->SetWatermarkImage(base_url('assets/img/web/') . $web->watermark, 0.1, [70, 40]);
        $mpdf->showWatermarkImage = true;
        $mpdf->AddPage($position); // isi posisi cetakan L (landscape)/ P (potrait)
        $mpdf->setAutoTopMargin = 'pad';
        $mpdf->WriteHTML($body); // body html
        $mpdf->SetFooter('<table width="100%">
            <tr>
                <td width="33%">Tgl Cetak: {DATE j/m/Y}</td>
                <td width="33%" align="center">{PAGENO}/{nbpg}</td>
                <td width="33%" style="text-align: right;">' . $filename . '</td>
            </tr>
        </table>'); // set footer
        if ($yes < 1) {
            $mpdf->Output(); // tampilkan pdf
        }
        $mpdf->OutputFile('./assets/file/pdf/' . $filename . '.pdf'); // simpan file pdf
    } else {
        $parameter['body'] = $body;
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Content-Type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=$judul.xls");
        view_loader('Cetak/Master_cetak', $parameter);
    }
}

function cetak_pdf_small($judul, $body, $cek_param, $position, $filename, $web, $yes = 0)
{
    $CI = &get_instance();

    $mpdf = new Mpdf([
        'mode'          => 'utf-8',
        'format'        => [89, 140],
        'margin_left'   => 5,
        'margin_right'  => 5,
        'margin_top'    => 5,
        'margin_bottom' => 5,
        'margin_header' => 5,
        'margin_footer' => 5
    ]);

    // buat html for kop surat
    $kop = '';

    $kop .= '<table style="width: 100%; font-size: 8px; border-bottom: 3px solid #000;">
        <tbody>
            <tr>
                <td style="width: 15%;">
                    <img src="./assets/img/web/' . $web->logo . '" style="width: 30px;"/>
                </td>
                <td style="width: 85%;">
                    <span style="font-weight: bold; font-size: 10px;">' . strtoupper($web->nama) . '</span>
                    <br>
                    <span style="">' . $web->alamat . '</span>
                    <br>
                    <span>Telepon: ' . $web->nohp . ' | Email: ' . $web->email . '</span>
                </td>
            </tr>
        </tbody>
    </table>';

    if ($CI->uri->segment(1) == 'Kasir' || $CI->uri->segment(1) == 'Kasir') {
        $footer = '<table width="100%" style="font-size: 8px;">
            <tr>
                <td width="100%" style="text-align: center;">Terima Kasih</td>
            </tr>
        </table>';
    } else {
        $footer = '<table width="100%" style="font-size: 8px;">
            <tr>
                <td width="33%">Tgl Cetak: {DATE j/m/Y}</td>
                <td width="33%" align="center">{PAGENO}/{nbpg}</td>
                <td width="33%" style="text-align: right;">' . $filename . '</td>
            </tr>
        </table>';
    }

    if ($cek_param == 0) {
        echo ("<title>$judul</title>");
        echo ($body);
    } else if ($cek_param == 1) { // jika paramnya 1 maka cetak pdf
        $mpdf->SetTitle($judul); // berikan judul
        $mpdf->SetHTMLHeader($kop); // set kop
        $mpdf->setAutoBottomMargin = 'stretch';
        // $mpdf->SetWatermarkText($web->nama, 0.05); // beri watermark dengan transparansi 0.1
        // $mpdf->showWatermarkText = true; // izinkan watermark tampil
        $mpdf->SetWatermarkImage(base_url('assets/img/web/') . $web->watermark, 0.1, [55, 30]);
        $mpdf->showWatermarkImage = true;
        $mpdf->AddPage($position); // isi posisi cetakan L (landscape)/ P (potrait)
        $mpdf->setAutoTopMargin = 'stretch';
        $mpdf->WriteHTML($body); // body html
        $mpdf->SetFooter($footer); // set footer
        if ($yes < 1) {
            $mpdf->Output(); // tampilkan pdf
        }
        $mpdf->OutputFile('./assets/file/pdf/' . $filename . '.pdf'); // simpan file pdf
    } else {
        $parameter['body'] = $body;
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Content-Type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=$judul.xls");
        view_loader('Cetak/Master_cetak', $parameter);
    }
}
